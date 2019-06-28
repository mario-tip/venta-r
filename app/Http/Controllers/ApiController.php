<?php

namespace App\Http\Controllers;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;

class ApiController extends Controller
{
  use ApiResponser;
    // IDEA: extendemos desde otra clase a esta para pedir
    public function __construct(Request $request)
    {
      $this->middleware('auth:api');
    }
}
