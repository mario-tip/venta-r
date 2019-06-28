<?php
namespace App\Http\Controllers;
use Intervention\Image\ImageManagerStatic as Image;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Warehouse;
use App\Company;
use App\User;

class UserController extends ApiController
{
    public function index(Request $request){
      // IDEA: add opcional dont show user auth
		$users = $request->user()->company->users;

        $value = $users ? $users : $this->showError(['mensaje' => 'Sin ususarios']);
        return $value;
    }

    public function store(Request $request){
      $rules = [
        'name' => 'required',
        'last_name' => 'required',
        'phone' => 'required',
        'email' => 'unique:users|required|email',
        'address' => 'required',
        'password' => 'required',
        'user_type' => 'required|numeric',
        'img_file' => 'image | mimes:jpeg,jpg,png',
      ];
      $this->validate($request, $rules);

        $request['name'] = ucwords(strtolower($request->input('name')));
        $request['last_name'] = ucwords(strtolower($request->input('last_name')));
        $request['password'] = bcrypt($request->input('password'));
        $request['company_id'] = $request->user()->company->id;

        if ($request->has('img_file')) {
        $statement = DB::select("SHOW TABLE STATUS LIKE 'users'");
        $nextId = $statement[0]->Auto_increment;

        $file = $request->file('img_file');
        $filename = "";
        $filename = $nextId.".".$file->getClientOriginalExtension();
        $path = 'img/users/' . $filename;

        // IDEA: crear carpeta si no existe
        $folder = public_path().'/img/users';
        if(!file_exists($folder)){
            mkdir($folder, 0777, true);
          }

        Image::make($file->getRealPath())->resize(200, null, function ($constraint) { $constraint->aspectRatio(); })->save($path);

          $img = file_get_contents(public_path($path));
          $base64 = base64_encode($img);
          $request['img'] = $base64;
        }
              $todo = User::create($request->all());
            return $this->showSave($todo);

    }

    public function show(User $user){
        return $this->showAllSimple($user);
    }

    public function update(Request $request, User $user){

      $rules = [
        'email' => 'email',
        'user_type' => 'numeric',
        'company_id' => 'numeric',
        'img_file' => 'image | mimes:jpeg,jpg,png',
      ];
      $this->validate($request, $rules);

      if($request->hasFile('img_file')) {

        $file = $request->file('img_file');
        $filename = "";
        $filename = $user->id.".".$file->getClientOriginalExtension();
        $path = 'img/users/' . $filename;
        Image::make($file->getRealPath())->resize(200, null, function ($constraint) { $constraint->aspectRatio(); })->save($path);
        $user->update($request->all());

        $img = file_get_contents(public_path($path));
        $base64 = base64_encode($img);

        User::where('id', $user->id)->update(['img' => $base64]);

        }

        if($request->has('name')){
          $user->name = ucwords(strtolower($request->input('name')));
        }
        if($request->has('last_name')){
          $user->last_name = ucwords(strtolower($request->input('last_name')));
        }
      if($request->has('password')) {
          $request['password'] = bcrypt($request['password']);
        }
        $input = $request->except('img');
        $user->update($request->all());
        return $this->showSave(['data' => $user]);
    }

    public function destroy(User $user){
      $user->delete();
      return $user;
    }

    public function getDrivers(Request $request){
      $drivers = $request->user()->company->drivers;
      return count($drivers) ? $drivers : $this->showError(['mensaje' => 'No hay choferes.']);

    }

    public function getUsersByCompanyID(Request $request){
      $idCompany = $request->input('company_id');
      $company = Company::find($idCompany);
      if (count($company)) {
        $users = User::where('company_id',$idCompany)->get();
        if (count($users)) {
          return $this->showAllSimple($users);
        }else {
        return $this->showAllSimple(['mensaje' => 'no existen usuarios para esta empresa']);
    }

      }
      else {
        return $this->showAllSimple(['mensaje' => 'error empresa inexistente']);
      }

    }
    public function is_admin($user){

        $user_type = (int) json_decode($user,true)['user_type'];
        return ($user_type == 1)?true:false;
    }

    public function getUserByID($id){

      $user = user::where('id',$id)->get();

      if(count($user)){

          $user = json_encode($user[0]);
          if($this->is_admin($user)){
              return $this->showAllSimple('El usuario es administrador no puede iniciar sesion en la aplicacion Vert',400);
          }
          else{

              $warehouse = Warehouse::where('user_id',$id)->get();
              if(count($warehouse)){

                $user = User::join('warehouses','users.id','=','warehouses.user_id')
                                  ->select('users.*','warehouses.id AS warehouse_id')
                                  ->where('users.id',$id)
                                  ->get()[0];

                return $this->showAllSimple($user);

              }
              else{

                  return $this->showAllSimple('El usuario no tiene asignado ningun almacen');

              }
          }
      }
      else{
          return $this->showAllSimple('Usuario no encontrado');
      }

    }


}
