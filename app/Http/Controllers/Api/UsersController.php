<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UsersController extends Controller
{
    public function __construct()
    {

    }

    public function login(Request $request)
    {
        $rules = [
            'email' => 'required',
            'password' => 'required'
        ];

        //validation check
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }

        //set username
        if (filter_var($request->input('email'), FILTER_VALIDATE_EMAIL)) {
            $username = 'email';
        } else {
            $username = 'username';
        }

        // create credentials access
        $credentials = [$username => $request->input('email'), 'password' => $request->input('password')];

        if ($authorized = $this->guard()->attempt($credentials)) {
            $token = $this->respondWithToken($authorized);

            return response()->json([
                'status' => true,
                'message' => 'Logged in successfully',
                'user' => $this->guard()->user(),
                'token' => $token->original
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Failed to login, Invalid credentials'
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'Unauthorized'
        ], 401);
    }

    public function register(Request $request)
    {
        $rules = [
            'name' => 'required',
            'username' => 'unique:users,username',
            'email' => 'required|unique:users,email',
            'password' => 'required|confirmed',
            'password_confirmation' => 'required',
        ];

        //validation check
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }

        try {
            // registered new user
            $user = new User();
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $user->username = $request->input('username') ? $request->input('username') : NULL;
            $user->password = app('hash')->make($request->input('password'));

            if ($user->save()) {

                //create token after registration
                $credentials = $request->only('email', 'password');

                if ($authorized = $this->guard()->attempt($credentials)) {
                    $token = $this->respondWithToken($authorized);

                    return response()->json([
                        'status' => true,
                        'message' => 'User registered successfully',
                        'user' => $user,
                        'token' => $token->original
                    ]);
                }
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Failed to register',
                ]);
            }

        }catch(\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong, '. $e->getMessage()
            ]);
        }

        $credentials = $request->only('email', 'password');

        if ($authorized = $this->guard()->attempt($credentials)) {
            $token = $this->respondWithToken($authorized);

            return response()->json([
                'status' => true,
                'message' => 'Logged in successfully',
                'user' => $this->guard()->user(),
                'token' => $token->original
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Failed to login, Invalid email or password'
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'Unauthorized'
        ], 401);
    }

    /**
     * Get the authenticated User
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        if ($this->guard()->user()) {
            return response()->json([
                'status' => true,
                'user' => $this->guard()->user(),
                'message' => 'Authorized'
            ], 200);
        }

        return response()->json([
            'status' => false,
            'message' => 'Unauthorized'
        ], 401);
    }

    /**
     * Log the user out (Invalidate the token)
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        $this->guard()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken($this->guard()->refresh());
    }


    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' =>$this->guard()->factory()->getTTL() * 60
        ]);
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\Guard
     */
    public function guard()
    {
        return Auth::guard('api');
    }
}
