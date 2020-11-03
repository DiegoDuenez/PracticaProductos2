<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $table = 'productos';

    protected $fillable = [
        'nombre', 'precio',
    ];

    public function comentarios(){
        return $this->hasMany(Comentario::class);
    }
}
