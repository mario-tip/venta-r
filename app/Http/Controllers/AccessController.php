<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\User;

class AccessController extends Controller
{
    public function login(Request $request){

      session_start();
      if ($request->has('email') && $request->has('password')) {

        $email = $request->input('email');
        $password = $request->input('password');
        // isset get booleano
        if (isset($_SESSION['auth'])) {
          return response()->json(['error' => ['msg'=> 'ya se logueo', 'code'=> 302]], 302);
        }else {
          $user = DB::table('users')->where('email',$email)
                      ->where('password',$password)->get();
          if (count ($user)) {
            $_SESSION['auth'] = true;
            $_SESSION['id_user'] = json_decode($user->toJson(),true)[0]['id'];
            $_SESSION['admin'] = json_decode($user->toJson(),true)[0]['id_usertype'];
            $user = User::findOrFail($_SESSION['id_user']);
            return response()->json($user, 200);
          }else {
            return response()->json(['error' => "usuario no encontrado",
                                      "code" => 404], 404);
          }
        }
      }
    }
    public function logout(){
      session_start();
      if (isset($_SESSION['auth'])) {

        session_destroy();
        return response()->json(['data' => "Usuario cerro sesion",
                                  "code" => 200], 200);
      }else {
        return response()->json(['error' => "no se ha logueado usuario",
                                  "code" => 401], 401);
      }

    }
}
