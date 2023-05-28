<?php

namespace App\Http\Controllers\Api;

use App\Models\User;

class AuthController extends Controller
{
    public function show(string $uuid)
    {
        $user = User::query()->with(['resume', 'media'])->where('uuid', $uuid)->first();

        $user->setHidden(['id'])->media->setVisible(['uuid', 'collection_name', 'name', 'original_url', 'created_at']);

        return response($user);
    }
}
