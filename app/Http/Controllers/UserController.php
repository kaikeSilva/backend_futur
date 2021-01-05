<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;

class UserController extends Controller
{
    public function index() {
        /** @var User $user */
        $user = auth()->user();
        $user->load('courses');

        return new UserResource($user);
    }
}
