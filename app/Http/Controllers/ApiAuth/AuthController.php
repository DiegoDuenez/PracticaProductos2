<?php

namespace App\Http\Controllers\ApiAuth;
use App\User;
use Log;
use App\Modelos\Comentario;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\MessageBag;

class AuthController extends Controller
{
    public function index(Request $request, $id = null){
        
        if($request->user()->tokenCan('admin:admin') || $request->user()->tokenCan('user:admin')) {
            if($id)
                return response()->json(["usuario"=>User::find($id)],200);
            return response()->json(["users"=>User::all(),200]);
        }
        if($request->user()->tokenCan('user:show')){
            if($id)
                return abort(400, "Permisos Invalidos");
            return response()->json(["user"=>$request->user()],200);
        }
        return abort(400, "Permisos Invalidos");

    }

    public function registrate(Request $request){


      
        $request ->validate([

            'name'=>'required',
            'years_old'=>'required',
            'email'=> 'required|email|unique:users,email',
            'password'=>'required',


        ]);

        $user = new User();
        $user->name = $request->name;
        $user->years_old = $request->years_old;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->rol = 'user';
        if($user->save())
            return response()->json($user, 201);
        return abort(400, "Hubo problemas al registrarse");

    }

    public function login(Request $request){

        $request->validate([

            'email'=> 'required|email',
            'password'=>'required',
            
        ]);

        $user = User::where('email', $request->email)->first();

        if(!$user || !Hash::check($request->password, $user->password)){

            throw ValidationException::withMessages([
                'email|password'=>['Datos incorrectos'],
            ]);


        }
        if($user->rol == 'user'){ //SI EL USUARIO TIENE ESTE ROL SE LE ASIGNARAN ESTOS PERMISOS
            $token = $user->createToken($request->email,['user:show','user:save','user:edit','user:delete'])->plainTextToken;
        }
        if($user->rol == 'admin'){ //SI EL USUARIO TIENE ESTE ROL SE LE ASIGNARAN PERMISOS DE ADMINISTRADOR
            $token = $user->createToken($request->email,['admin:admin'])->plainTextToken;
        }
        if($user->rol == 'superuser'){ //SI EL USUARIO TIENE ESTE ROL SE LE ASIGNARAN LOS PERMISOS DE USUARIO/ADMINISTRADOR
            $token = $user->createToken($request->email,['user:admin'])->plainTextToken;
        }
        return response()->json(['token'=>$token], 201);

        


    }

    public function logOut(Request $request){

        return response()->json(["Afectados"=>$request->user()->tokens()->delete()],200);

    }

    public function edit(Request $request, $id){

        if($request->user()->tokenCan('admin:admin') || $request->user()->tokenCan('user:admin')){

            $user= User::findOrFail($id);

            if($id){

                $user->name = $request->name;
                $user->years_old = $request->years_old;
                $user->email = $request->email; //EL ADMINISTRADOR PUEDE EDITAR A TODOS LOS USUARIOS  
                $user->password = Hash::make($request->password);
                $user->rol = $request->rol;
            
                if($user->save()){
                return response()->json(["user"=>$user],201);

                }
                
                return response()->json(null,400);
            
            }

            return response()->json(null,400);


        } 
        if($request->user()->tokenCan('user:edit'))
            return abort(400, "Permisos Invalidos");
        

    }

    public function delete(Request $request, $id){

        if($request->user()->tokenCan('admin:admin') || $request->user()->tokenCan('user:admin')){

            
            if($id){
                Comentario::where("usuario", "=", $id)->delete();
                User::where("id", "=", $id)->delete();
                return response()->json(["users"=>User::all()],200);
            }
            return response()->json(null,400);
            

        }
        if($request->user()->tokenCan('user:delete'))
            return abort(400, "Permisos Invalidos");

    }

    




}
