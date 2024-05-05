<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;

class SubscriptionPage
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $data = User::where('id', auth()->user()->id)
                                        ->with(['user_api_subscription.user_tier'])
                                        ->with('user_api_token')
                                        ->first();
        if(isset($data->user_api_subscription)){
            return redirect('/subscription');
        }
        return $next($request);
    }
}
