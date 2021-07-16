<?php

namespace App\Models;

use App\Models\Like;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Like extends Model
{
    use HasFactory;

    protected $fillable =[
        'post_id',
        'user_id',
    ];

    public function user(){
        return $this->belongTo(User::class);
    }

    public function like(){
        return $this->belongTo(Like::class);
    }

}
