<?php

use Livewire\Volt\Component;
use Illuminate\Http\Request;

new class extends Component {
    public $file_path;
    public $file_name;
    public $stl_name;

    public function mount(Request $request){
        $this->file_path = env('APP_URL').'/';
        $this->file_name = $request->stl_name;
    }
}

?>

<div>
    <canvas id="renderCanvas" style="width: 100%; height: 100%;"></canvas>
    <script>
        // Create the Babylon.js engine
        var canvas = document.getElementById("renderCanvas");
        var engine = new BABYLON.Engine(canvas, true);

        // Create a scene
        var scene = new BABYLON.Scene(engine);
        scene.clearColor = new BABYLON.Color3(0.96, 0.96, 0.96); // Set background color to off-white

        // Create a camera
        var camera = new BABYLON.ArcRotateCamera("camera", 7, 5, 5, BABYLON.Vector3.Zero(), scene);
        camera.attachControl(canvas, false); // Disable default camera controls

        // Add a light to the scene
        var light = new BABYLON.DirectionalLight("light", new BABYLON.Vector3(0, 1, 5), scene);

        // Load the STL file asynchronously
        BABYLON.SceneLoader.ImportMesh("", "{{ $file_path }}", "{{ $file_name }}", scene, function (meshes) {
            // Do something with the loaded meshes if needed
            var mesh = meshes[0]; // Assuming only one mesh is loaded
            mesh.rotationQuaternion = null; // Disable auto-rotation

            // Get bounding box information of the loaded mesh
            var boundingInfo = mesh.getBoundingInfo();
            var size = boundingInfo.boundingBox.extendSizeWorld;

            // Calculate camera position and target based on the size of the mesh
            var distance = Math.max(size.x, size.y, size.z) * 2; // Adjust this multiplier as needed
            var target = boundingInfo.boundingBox.centerWorld;
            camera.setTarget(target);
            camera.radius = distance;

            // Adjust camera position to fit the mesh within the view
            var viewSize = engine.getRenderWidth() / engine.getRenderHeight();
            var fitHeightDistance = size.y / Math.sin(camera.fov / 2);
            var fitWidthDistance = (size.x / viewSize) / Math.sin(camera.fov / 2);
            camera.radius = Math.max(fitHeightDistance, fitWidthDistance);
        });

        // Run the render loop
        engine.runRenderLoop(function () {
            scene.render();
        });

        // Resize the canvas when the window is resized
        window.addEventListener("resize", function () {
            engine.resize();
        });
    </script>
</div>
