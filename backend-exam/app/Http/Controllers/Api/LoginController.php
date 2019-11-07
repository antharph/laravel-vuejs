<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\User;
use Illuminate\Support\Str;
use Auth;

class LoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);
    }

    public function logout(){
        $user = Auth::guard('api')->user();
        if ($user) {
            $user->api_token = null;
            $user->save();
        }

        return response("", 200);
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');

        $validator = $this->validator($credentials);

        $payload["message"] = "The given data was invalid.";
        if($validator->fails()){
            $payload["errors"] = $validator->errors();
        }

        $user = User::where('email',$request->input('email'))->first();

        if($user === null){
            $payload["errors"] = [
                "email" => "These credentials do not match our records."
            ];
        }
        
        if(isset($payload["errors"])){
            return response($payload, 422)
                    ->header($this->accept, $this->applicationJson)
                    ->header($this->contentType, $this->applicationJson);
        }

        if (Auth::attempt($credentials)) {
            // $user = Auth::user();

            $token = Str::random(60);

            $request->user()->forceFill([
                'api_token' => hash('sha256', $token),
            ])->save();

            $date = new \DateTime();
            $date->modify('+5 day');
            $expires_at = $date->format('Y-m-d H:i:s');

            $data = [
                "token" => $token,
                "token_type" => "bearer",
                "expires_at" => $expires_at
            ];

            return response($data, 200)
                    ->header($this->accept, $this->applicationJson)
                    ->header($this->contentType, $this->applicationJson);
        }
    }
}
