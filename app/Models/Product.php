<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Product extends Model
{


    protected $fillable = [
        'name',
        'price',
        'description',
        'image_url',
        'rating',
        'product_url',
    ];
}