<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $usernameOrEmail = $request->input('txtname');
        $password = $request->input('txtpass');

        // Check by name OR email
        $user = DB::table('employees')
            ->where('name', $usernameOrEmail)
            ->orWhere('username', $usernameOrEmail)  // Added username field check
            ->orWhere('email', $usernameOrEmail)
            ->first();

        if ($user && Hash::check($password, $user->password)) {
            // Remove password from response for security
            $userData = (array) $user;
            // unset($userData['password']);
            
            return response()->json(['user' => $userData], 200);
        } else {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }
    }
}