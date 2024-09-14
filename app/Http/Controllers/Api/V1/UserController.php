<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller {
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): UserResource {
        return UserResource::make(Auth::user());
    }
}
