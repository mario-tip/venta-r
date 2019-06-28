<?php
namespace App\Http\Controllers;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\Mail;
use App\Mail\MessageReceived;
class ResponseController extends ApiController
{

    public function getData(Request $request){

      $user = $request->user();
      $user->company->category;
      $user->routes;
      $user->orders;

      $resp = $user ? $user : response(['mensaje' => 'No hay datos']);

      return $resp;
    }

    public function generatePDF(Request $request){

      $user = $request->user();
      // IDEA: crear carpeta si no existe
      $folder = public_path().'/docs/orders';
      if(!file_exists($folder)){
          mkdir($folder, 0777, true);
        }
        // IDEA: read the view and send params
        // dd($user);
        $pdf = PDF::loadView('order', compact('user'));
        // IDEA: save the view in pdf format
        $pdf->save('docs/orders/' . $user->name.$user->id.'.pdf');
        // IDEA: encode in base 64 the file pdf
        $resultTren = base64_encode($pdf->output());
        dd($resultTren);
        return $pdf->stream();
    }

    public function sendEmail(Request $request){

      $mensaje = [
        'name'=>'mario',
        'last_name' => 'de la cruz sandoval',
        'email'=>'ing_mario@outlook.com',
        'subject' => 'no se ',
        'content' => 'lallala pony'
      ];
      // Mail::send('email.message-received', $mensaje, function ($message){
      //    $message->from('contacto@softfullstack.com','test de email');
      //    $message->to('tachi_mcs@hotmail.com')->subject('ola k ase');
      // });
      return 'mensaje enviado sdfsfd';
    }
}
