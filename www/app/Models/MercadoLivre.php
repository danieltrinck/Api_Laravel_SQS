<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MercadoLivre extends Model
{
    use HasFactory;

    protected $table = 'mercadolivre';
    
    protected $fillable = [
        'store_id',
        'dados'
    ];
}
