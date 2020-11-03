<?php

namespace App\Http\Controllers\ApiAuth;
use App\Modelos\Producto;
use App\Modelos\Comentario;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\MessageBag;

class ProductosController extends Controller
{
    public function index(Request $request, $id = null){
        
        if($request->user()->tokenCan('admin:admin') || $request->user()->tokenCan('user:admin')){
            if($id)
                return response()->json(["producto"=>Producto::find($id)],200);
            return response()->json(["productos"=>Producto::all()],200);
        }

        if($request->user()->tokenCan('user:show')){
            if($id)
                return response()->json(["producto"=>Producto::find($id)],200);
            return response()->json(["productos"=>Producto::all()],200);
        
        }
        if(! $request->user()->tokenCan('admin:admin') || ! $request->user()->tokenCan('user:admin') || ! $request->user()->tokenCan('user:show'))
            return abort(400, "Permisos Invalidos");
    }

    public function save(Request $request){

        if($request->user()->tokenCan('admin:admin') || $request->user()->tokenCan('user:admin')){

            $producto = new Producto();
            $producto->nombre = $request->nombre;
            $producto->precio = $request->precio;

            if($producto->save())
                return response()->json(["producto"=>$producto],201);
            return response()->json(null,400);

        }
        if(! $request->user()->tokenCan('admin:admin') || ! $request->user()->tokenCan('user:admin'))
            return abort(400, "Permisos Invalidos");
        
        

    }

    public function edit(Request $request, $id){

        if($request->user()->tokenCan('admin:admin') || $request->user()->tokenCan('user:admin')){

            $producto = Producto::findOrFail($id);

            if($id){

                $producto->nombre = $request->nombre;
                $producto->precio = $request->precio;
            
                if($producto->save()){

                    return response()->json(["producto"=>$producto],201);

                }
                return response()->json(null,400);
            
            }
            return response()->json(null,400);


        }
        if(! $request->user()->tokenCan('admin:admin') || ! $request->user()->tokenCan('user:admin'))
            return abort(400, "Permisos Invalidos");
        

    }

    public function delete(Request $request, $id){

        if($request->user()->tokenCan('admin:admin') || $request->user()->tokenCan('user:admin')){

            
            if($id)
                Comentario::where("producto", "=", $id)->delete();
                Producto::where("id", "=", $id)->delete();
                return response()->json(["productos"=>Producto::all()],200);
            return response()->json(null,400);

        }
        if(! $request->user()->tokenCan('admin:admin') || ! $request->user()->tokenCan('user:admin'))
            return abort(400, "Permisos Invalidos");

        

    }

}
