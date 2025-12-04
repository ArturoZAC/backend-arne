<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'Titulo01',
        'Parrafo01',
        'Subtitulo01',
        'Imagen01',
        'Subtitulo02',
        'Parrafo02',
        'Imagen02',
        'Imagen03',
        'BotonLink',
    ];

}
