<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Cart extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'total_qty',
        'product_id',
        'user_id',
    ];

    protected $hidden = [
        "total_price",
    ];

    public function product(): HasOne //belum tahu implementasinya
    {
        return $this->HasOne(User::class);
    }
}
