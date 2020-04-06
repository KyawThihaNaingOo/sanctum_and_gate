<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $this->validator($request->all())
            ->validate();

        $user = $this->create($request->all());

        return response()->json([
            'user' => $user,
            'message' => 'registration successful'
        ], 200);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => [
                'required',
                'string',
                'max:255'
            ],
            'phone' => [
                'required',
                'string',
                'min:6',
                'max:255',
                'unique:users'
            ],
            'password' => [
                'required',
                'string',
                'min:6',
                'confirmed'
            ]
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'phone' => $data['phone'],
            'password' => Hash::make($data['password'])
        ]);
    }

    protected function guard()
    {
        return Auth::guard();
    }

    public function login(Request $request)
    {
        $credentials = $request->only('phone', 'password');

        if (Auth::attempt($credentials)) {
            $authUser = User::where('phone', $request->phone)->first();
            $token = $authUser->createToken('api-login');
            return response()->json([
                'message' => 'Login successful',
                'token' => $token->plainTextToken
            ], 200);
        } else {
            return response()->json([
                'message' => 'Login fail',
            ], 422);
        }
    }

    public function logout()
    {
        Auth::logout();
        return response()->json([
            'message' => 'Logged Out'
        ], 200);
    }

    public function update(Request $request)
    {
        $validate = [
            'name' => [
                'required',
                'string',
                'max:255'
            ],
            'phone' => [
                'string',
                'min:6',
                'max:255',
                'unique:users'
            ]
        ];
        // $user = $this->validator($request->all())->validate();
        if ($request->passwordChange === 'true') {
            $validate['password'] =  [
                'required',
                'string',
                'min:6',
                'confirmed'
            ];
            $user = Validator::make($request->all(), $validate)->validate();
            $user['password'] = Hash::make($request->password);
        } else {
            $user = Validator::make($request->all(), $validate)->validate();
        }

        $getUser = User::findOrFail($request->id);
        return response()->json([
            'message' => 'success',
            'data' => $getUser->update($user)
        ]);
    }

    public function test()
    {
        dd(Auth::user());
    }
}
