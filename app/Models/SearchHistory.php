<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SearchHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'keyword',
        'search_count',
        'last_searched_at',
    ];

    protected $casts = [
        'last_searched_at' => 'datetime',
    ];
}


