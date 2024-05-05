<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\UserApiToken;
use App\Models\UserApiSubscription;

use Spatie\Referer\Referer;

class ParserController extends Controller
{
    function parse_stl_file($file_path, $unit, $scale) {
        $vertices = array();
        $faces = array();
    
        // Open the STL file for reading
        $file = fopen($file_path, 'r');
    
        // Read the first 80 bytes (header) and ignore
        fseek($file, 80);
    
        // Read the number of triangles (faces)
        $triangles = unpack('V', fread($file, 4))[1];
    
        // Read each triangle
        for ($i = 0; $i < $triangles; $i++) {
            // Read the normal vector (3 floats, 12 bytes)
            fread($file, 12);
    
            // Read the three vertices (3 floats each, 36 bytes total)
            $faceVertices = [];
            for ($j = 0; $j < 3; $j++) {
                $vertex = unpack('f*', fread($file, 12));
                $index = count($vertices);
                $vertices[] = $vertex;
                $faceVertices[] = $index; // Index of the current vertex
            }
            $faces[] = $faceVertices; // Store the indices of vertices for each face
    
            // Read the attribute byte count (2 bytes), ignore for now
            fread($file, 2);
        }
    
        fclose($file);
    
        // Scale vertices based on unit and scale
        if ($unit === 'inch') {
            $scale *= 25.4; // Convert inches to millimeters
        }
        foreach ($vertices as &$vertex) {
            $vertex = array_map(function($v) use ($scale) {
                return $v * $scale;
            }, $vertex);
        }
    
        return array('vertices' => $vertices, 'faces' => $faces);
    }

    function tetrahedron_volume($v0, $v1, $v2) {
        // Calculate volume using determinant formula
        $volume = (1 / 6) * abs(
            $v0[1] * ($v1[2] * $v2[3] - $v2[2] * $v1[3]) +
            $v1[1] * ($v2[2] * $v0[3] - $v0[2] * $v2[3]) +
            $v2[1] * ($v0[2] * $v1[3] - $v1[2] * $v0[3])
        );
    
        return $volume;
    }

    function calculate_stl_volume($parsed_data, $unit, $scale, $layer_height_mm, $printer_speed_mm_per_s) {
        // Convert vertices to actual dimensions based on unit and scale
        if ($unit === 'inch') {
            $scale *= 25.4; // Convert inches to millimeters
        }
    
        // Apply scale to vertices
        $scaled_vertices = array_map(function($vertex) use ($scale) {
            return array_map(function($v) use ($scale) {
                return $v * $scale;
            }, $vertex);
        }, $parsed_data['vertices']);
    
        $total_volume = 0;
    
        // Calculate printing time
        $printing_time_hours = $this->calculate_printing_time($parsed_data, $layer_height_mm, $printer_speed_mm_per_s);
    
        // Iterate through each face (triangle) to calculate volume
        foreach ($parsed_data['faces'] as $faceVertices) {
            // Get vertices of the face
            $v0 = $scaled_vertices[$faceVertices[0]];
            $v1 = $scaled_vertices[$faceVertices[1]];
            $v2 = $scaled_vertices[$faceVertices[2]];
    
            // Calculate volume of the tetrahedron formed by the face and add to total volume
            $tetrahedron_volume = $this->tetrahedron_volume($v0, $v1, $v2);
            if ($tetrahedron_volume !== false) {
                $total_volume += $tetrahedron_volume;
            } else {
                // Handle error in volume calculation
                return false;
            }
        }
    
        // Cost per cubic centimeter (example value, replace with your actual cost calculation)
        $cost_per_cc = isset($_POST['cost_per_cc']) ? floatval($_POST['cost_per_cc']) : 0.05; // Default to $0.05 if not provided
    
        if($unit == 'inch'){
            $converted_scale = $scale / 1000;
        }else{
            $converted_scale = $scale / 100;
        }
        $scale = $converted_scale;
    
        // Concluder price will be this
        if($scale >= 0.50 && $scale < 1){
            $concluder_price = 0.05;
        }elseif($scale < 0.50){
            $concluder_price = 0.5;
        }else{
            $concluder_price = 0.005;
        }
    
        // Total production cost based on volume
        $production_cost = $total_volume * $cost_per_cc * $concluder_price;
    
        // Additional cost based on printing time (you can adjust this calculation based on your additional costs)
        $additional_cost_per_hour = 10; // Example additional cost per hour
        $additional_cost = $printing_time_hours * $additional_cost_per_hour;
        $production_cost += $additional_cost;
    
        return $production_cost;
    }

    function calculate_printing_time($parsed_data, $layer_height, $printer_speed) {
        // Calculate the total height of the model by finding the maximum z-coordinate
        $max_z = max(array_map(function($vertex) {
            return $vertex[2]; // Assuming z-coordinate is at index 2
        }, $parsed_data['vertices']));
    
        // Calculate the number of layers based on the layer height
        $num_layers = ceil($max_z / $layer_height);
    
        // Calculate printing time in hours based on layer count and printer speed
        $printing_time_hours = ($num_layers * $layer_height) / $printer_speed;
    
        return $printing_time_hours;
    }

    function calculate_total_grams($parsed_data) {
        // Calculate the total grams based on the volume and the density of the material
        // Density of the material is typically provided by the user or obtained from the material specification
        // For demonstration purposes, let's assume a density of 1 gram per cubic centimeter
        $density = 0.000139419183; // grams per cubic centimeter
        $total_volume = 0;
    
        // Iterate through each face (triangle) to calculate volume
        foreach ($parsed_data['faces'] as $faceVertices) {
            // Get vertices of the face
            $v0 = $parsed_data['vertices'][$faceVertices[0]];
            $v1 = $parsed_data['vertices'][$faceVertices[1]];
            $v2 = $parsed_data['vertices'][$faceVertices[2]];
    
            // Calculate volume of the tetrahedron formed by the face and add to total volume
            $tetrahedron_volume = $this->tetrahedron_volume($v0, $v1, $v2);
            if ($tetrahedron_volume !== false) {
                $total_volume += $tetrahedron_volume;
            } else {
                // Handle error in volume calculation
                return false;
            }
        }
    
        // Calculate the total grams based on the total volume and density
        $total_grams = $total_volume * $density;
        return $total_grams;
    }

    public function stl_parser(Request $request){
        $getToken = UserApiToken::where('api_key', $request['api_key'])->with('user')->get();

        if($getToken->count() <= 0){
            return response()->json([
                'status'=>401,
                'message'=>'Token Unauthenticated'
            ], 401);
        }

        if($getToken[0]->usage <= 0){
            return response()->json([
                'status'=>401,
                'message'=>'No more available query for today'
            ], 401);
        }

        $get_agent = explode(';',$request->userAgent());
        $trim_agent = trim(@$get_agent[1], ' ');
        $host_array = json_decode($getToken[0]->host_connection);
        if (in_array($trim_agent, $host_array) || preg_match('/{$request->userAgent()}/i', 'Postman') >= 0) {
            $allow_query = true;
        }else{
            $allow_query = false;
        }

        if(!$allow_query){
            return response()->json([
                'status'=>401,
                'message'=>'Unregistered Host Url'
            ], 401);
        }
        
        $validatedData = $request->validate([
            'stl_file' => 'required',
            'unit' => 'required',
            'scale' => 'required',
            'cost_per_cc' => 'required',
            'printing_technology' => 'required',
            'material' => 'required',
            'quality' => 'required',
            'quantity' => 'required',
            'infill' => 'required',
        ]);

        $file_path = $validatedData['stl_file'];
        $unit = $validatedData['unit'];
        $scale = $validatedData['scale'];
        $cost_per_cc = $validatedData['cost_per_cc'];
        $layer_height_mm = 0.2; // Layer height in millimeters
        $printer_speed_mm_per_s = 90; // Printer speed in millimeters per second
        $cost_per_cc = $cost_per_cc ? $cost_per_cc : 0.05;
        $printing_technology = $validatedData['printing_technology'];
        $material = $validatedData['material'];
        $quality = $validatedData['quality'];
        $quantity = $validatedData['quantity'];
        $infill = $validatedData['infill'];

        if($unit == 'inch'){
            $converted_scale = $scale / 200;
        }else{
            $converted_scale = $scale / 100;
        }
        $scale = $converted_scale;

        if($scale >= 0.50 && $scale < 1){
            $concluder_price = 0.05;
        }elseif($scale < 0.50){
            $concluder_price = 0.5;
        }else{
            $concluder_price = 0.005;
        }

        $parsed_data = $this->parse_stl_file($file_path, $unit, $scale);
        $total_volume = $this->calculate_stl_volume($parsed_data, $unit, $scale, $layer_height_mm, $printer_speed_mm_per_s);
        $printing_time_hours = $this->calculate_printing_time($parsed_data, $layer_height_mm, $printer_speed_mm_per_s);
        $production_cost = ($total_volume * $concluder_price * $quantity) + $material + $printing_technology + $quality + $infill;

        if ($printing_time_hours <= 0.9) {
            // If printing time is less than or equal to 0.9 hours, convert to minutes
            $printing_time_output = ceil($printing_time_hours * 60) . ' minutes';
        } elseif ($printing_time_hours == 1) {
            // If printing time is exactly 1 hour
            $printing_time_output = '1 hour';
        } else {
            // For other values of printing time
            $printing_time_output = round($printing_time_hours) . ' hours';
        }

        $data = [
            'formated_data' => [
                '$'.number_format($production_cost, 2),
                number_format($this->calculate_total_grams($parsed_data), 2),
                $printing_time_output
            ],
            'parsed_data' => $parsed_data
        ];

        UserApiToken::where('id',$getToken[0]->id)->update([
            'usage' => $getToken[0]->usage - 1,
        ]);
        
        return response()->json([
            'status'=>200,
            'data'=>$data,
            'message'=>'Data Retrieved'
        ], 200);
    }
}
