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

    public function show(string $id): JsonResponse
    {
        $app = App::findOrFail($id);
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

    public function destroy(string $id): JsonResponse
    {
        $app = App::findOrFail($id);
        $app->delete();

        return $this->ok('App has been unregistered');
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:apps',
        ]);

        $app = App::findOrFail($id);

        $app->update([
            'name' => $request->name,
        ]);
        
        return $this->ok('App has been updated', [
            'app' => $app,
        ]);
    }

    public function refresh(string $id): JsonResponse
    {
        $app = App::findOrFail($id);
        $app->token = bin2hex(random_bytes(16));
        $app->save();

        return $this->ok('App token has been refreshed', [
            'app' => $app,
            'token' => $app->token,
        ]);
    }
}
