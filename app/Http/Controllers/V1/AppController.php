<?php

namespace App\Http\Controllers\V1;

use App\Models\App;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AppController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'apps' => App::all(),
        ]);
    }

    public function show(string $token): JsonResponse
    {
        $app = App::where('token', $token)->first();
        if (!$app) {
            return response()->json(['error' => 'App not found'], 404);
        }
        return response()->json($app);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:apps',
        ]);

        $app = App::create([
            'name' => $request->name,
            'token' => bin2hex(random_bytes(16)),
        ]);

        return response()->json($app);
    }

    public function destroy(string $token): JsonResponse
    {
        $app = App::where('token', $token)->first();
        if (!$app) {
            return response()->json(['error' => 'App not found'], 404);
        }
        $app->delete();
        return response()->json(['message' => 'App has been unregistered']);
    }
}
