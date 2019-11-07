<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\User;
// use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Auth;

class RegisterController extends Controller
{

    public function __construct()
    {
        $this->middleware('guest');
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    protected function create(Request $request)
    {
        $data = $request->input();
        
        $validator = $this->validator($data);

        if($validator->fails()){
            $payload["message"] = "The given data was invalid.";
            $payload["errors"] = $validator->errors();
            return response($payload, 422)
                    ->header($this->accept, $this->applicationJson)
                    ->header($this->contentType, $this->applicationJson);
        }

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        return response($user, 201)
                    ->header($this->accept, $this->applicationJson)
                    ->header($this->contentType, $this->applicationJson);
    }
}
