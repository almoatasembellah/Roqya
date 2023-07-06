<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): JsonResponse
    {
        if ($request->user() && $request->user()->status == User::ADMIN) {
            return $next($request);
        }

        return response()->json([
            'success' => false,
            'error' => 'AdminMiddleware Error',
            'message' => 'You\'re not an Administrator',
        ], 403);
    }
}

