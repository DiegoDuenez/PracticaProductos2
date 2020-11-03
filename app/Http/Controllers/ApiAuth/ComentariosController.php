<?php

namespace App\Http\Controllers\ApiAuth;
use App\Modelos\Comentario;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\MessageBag;

class ComentariosController extends Controller
{
    public function index(Request $request, $id = null){
        
        if($request->user()->tokenCan('admin:admin') || $request->user()->tokenCan('user:admin')) {
            if($id)
                return response()->json(["comentario"=>Comentario::find($id)],200);
            return response()->json(["comentarios"=>Comentario::all()],200);
        }
        if($request->user()->tokenCan('user:show')){ //EL USUARIO COMUN SOLO PUEDE BUSCAR SUS PROPIOS COMENTARIOS

            if($id){
                $comentario = User::find($id);
                $this->authorize('pass', $comentario);
                return response()->json(["comentarios"=>Comentario::where("id", "=", $id)->get()],200);
            }
            return response()->json(["comentarios"=>Comentario::all()],200);

            
        }
        return abort(400, "Permisos Invalidos");

    }

    public function persCom(Request $request, $id){

        if($request->user()->tokenCan('admin:admin') || $request->user()->tokenCan('user:admin')){

            if($id)
                return response()->json(["comentarios"=>Comentario::all()->where('usuario', $id)]);
            return response()->json(null,400);

        }
        if($request->user()->tokenCan('user:show')){
            
            

            if($id)
                return response()->json(["comentarios"=>Comentario::all()->where('usuario','=', $id)]);
            return response()->json(null,400);
            

        }
        if(! $request->user()->tokenCan('admin:admin') || ! $request->user()->tokenCan('user:admin') || ! ! $request->user()->tokenCan('user:show'))
        return abort(400, "Permisos Invalidos");
        
        

    
    }

    public function prodCom(Request $request, $producto){

        if($request->user()->tokenCan('admin:admin') || $request->user()->tokenCan('user:admin')){

            if($producto)
                return response()->json(["comentarios"=>Comentario::all()->where('producto', $producto)]);
            return response()->json(null,400);

        }
        if($request->user()->tokenCan('user:show')){
            if($producto)
                return response()->json(["comentarios"=>Comentario::all()->where('producto', $producto)]);
            return response()->json(null,400);
        }
        if(! $request->user()->tokenCan('admin:admin') || ! $request->user()->tokenCan('user:admin') || ! $request->user()->tokenCan('user:show'))
            return abort(400, "Permisos Invalidos");
       
        
    }

    public function save(Request $request){

        if($request->user()->tokenCan('admin:admin') || $request->user()->tokenCan('user:admin')){

            $comentario = new Comentario();
            $comentario->titulo = $request->titulo;
            $comentario->contenido = $request->contenido;
            $comentario->usuario = $request->usuario;   //EL ADMINISTRADOR PUEDE INSERTAR COMENTARIOS CON EL ID DE LA PERSONA QUE SEA
            $comentario->producto = $request->producto;

            
            if($comentario->save())
                return response()->json(["comentario"=>$comentario],201);
            return response()->json(null,400);

        }

        if($request->user()->tokenCan('user:save')){

            
            $comentario = new Comentario();
            $comentario->titulo = $request->titulo;
            $comentario->contenido = $request->contenido;
            $comentario->usuario =  $request->user()->id;  //UN USUARIO COMUN SOLO PUEDE GUARDAR COMENTARIOS CON SU ID (NO ES NECESARIO ESPECIFICAR EL CAMPO Y SU VALOR EN INSOMNIA)
            $comentario->producto = $request->producto;

            if($comentario->save())
                return response()->json(["comentario"=>$comentario],201);
            return response()->json(null,400);

        }
        if(! $request->user()->tokenCan('admin:admin') || ! $request->user()->tokenCan('user:admin') || ! $request->user()->tokenCan('user:admin'))
        return abort(400, "Permisos Invalidos");


    }

    public function edit(Request $request, $id){

        if($request->user()->tokenCan('admin:admin') || $request->user()->tokenCan('user:admin')){

            $comentario = Comentario::findOrFail($id);

            if($id){

                $comentario->titulo = $request->titulo;
                $comentario->contenido = $request->contenido;
                $comentario->usuario = $request->usuario; //EL ADMINISTRADOR PUEDE EDITAR TODOS LOS COMENTARIOS SIN RESTRICCIONES 
                $comentario->producto = $request->producto;
            
                if($comentario->save()){
                return response()->json(["comentario"=>$comentario],201);

                 }
                
                return response()->json(null,400);
            
            }

            return response()->json(null,400);


        } 
        
        if($request->user()->tokenCan('user:edit')){

            $comentario = Comentario::findOrFail($id);
            $this->authorize('pass', $comentario);

            if($id){
                $comentario->titulo = $request->titulo;
                $comentario->contenido = $request->contenido;
                $comentario->usuario = $request->user()->id; //EL USUARIO COMUN SOLO PUEDE EDITAR SUS COMENTARIOS
                $comentario->producto = $request->producto;
               
                if($comentario->save()){
                    return response()->json(["comentario"=>$comentario],201);

                }
                    
                return response()->json(null,400);
            }
            
            
            

        }
        if(! $request->user()->tokenCan('admin:admin') || ! $request->user()->tokenCan('user:admin') || ! $request->user()->tokenCan('user:edit'))
                return abort(400, "Permisos Invalidos");


    }

    public function delete(Request $request, $id){

        if($request->user()->tokenCan('admin:admin') || $request->user()->tokenCan('user:admin')){

            Comentario::destroy($id);
            if($id)
                return response()->json(["comentarios"=>Comentario::all()],200);
            return response()->json(null,400);

        }
        
        if($request->user()->tokenCan('user:delete')){

            $comentario = Comentario::find($id);
            $this->authorize('pass', $comentario);
            $comentario->delete();

            if($id)
                return response()->json(["comentarios"=>Comentario::all()],200); //QUE EL USUARIO SOLO PUEDA ELIMINAR SUS COMENTARIOS
            return response()->json(null,400);

        }
        if(! $request->user()->tokenCan('admin:admin') || ! $request->user()->tokenCan('user:admin') || ! $request->user()->tokenCan('user:delete'))
        return abort(400, "Permisos Invalidos");
        

    }



}
