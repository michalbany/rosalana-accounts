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
        return $this->ok('List of all apps', [
            'apps' => App::all(),
        ]);
    }

    public function show(string $token): JsonResponse
    {
        $app = App::where('token', $token)->first();

        if (!$app) {
            throw (new \Illuminate\Database\Eloquent\ModelNotFoundException())->setModel(App::class, $token);
        }

        return $this->ok('App details', [
            'app' => $app,
        ]);
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

        return $this->ok('App has been registered', [
            'app' => $app,
            'token' => $app->token,
        ]);
    }

    public function destroy(string $token): JsonResponse
    {
        $app = App::where('token', $token)->first();
        if (!$app) {
            throw (new \Illuminate\Database\Eloquent\ModelNotFoundException())->setModel(App::class, $token);
        }
        $app->delete();

        return $this->ok('App has been unregistered');
    }
}
