<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use App\Traits\ApiResponser;

class AuthController extends Controller
{
  use ApiResponser;
    /**
     * Login user and create token
     *
     * @param  [string] email
     * @param  [string] password
     * @param  [boolean] remember_me
     * @return [string] access_token
     * @return [string] token_type
     * @return [string] expires_at
     */
    public function login(Request $request)
    {
       // dd($request->all());
      $rule=[
          'email' => 'required|string|email',
          'password' => 'required|string',
          'remember_me' => 'boolean'
        ];

        $this->validate($request,$rule);

        $credentials = request(['email', 'password']);

        if(!Auth::attempt($credentials)){
            return $this->showMessage('Sin autorizacion',422);
        }

        $user = $request->user();
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;
        if ($request->remember_me){
          $token->expires_at = Carbon::now()->addWeeks(1);

        }
        $token->save();
        if ($user->user_type == 'ADMIN') {
          $data =[
              'access_token' => $tokenResult->accessToken,
              'token_type' => 'Bearer',
              'expires_at' => Carbon::parse( $tokenResult->token->expires_at
              )->toDateTimeString()
          ];
          return $this->showSave($data);
        }else {
          return $this->showCapa8('No eres administrador',401);
        }


    }

    /**
     * Logout user (Revoke the token)
     *
     * @return [string] message
     */
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }

    /**
     * Get the authenticated User
     *
     * @return [json] user object
     */
    public function user(Request $request)
    {
        return response()->json($request->user());
    }
}
