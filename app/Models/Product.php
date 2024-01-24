<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Product extends Model
{
    use HasFactory;

       /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'image',
        'price',
        'qty',
        "description",
        'category_id',
    ];

    protected $casts = [
        'price' => 'integer',
        'qty' => 'integer',
        'category_id' => 'integer',
    ];

    protected $appends = [
        "image_url",
        "image_location"
    ];

    protected function imageUrl(): Attribute
    {
        return Attribute::get(fn() => asset("/storage/products/" . $this->image));
    }

    protected function imageLocation(): Attribute
    {
        return Attribute::get(fn() => "public/products/" . $this->image);
    }
    
}
