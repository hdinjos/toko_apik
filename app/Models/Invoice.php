<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'invoice_number',
        'buying_date',
        'paying_date',
        'status',
        'user_id',
    ];

    protected $hidden = [
        "created_at",
        "updated_at",
    ];
}
