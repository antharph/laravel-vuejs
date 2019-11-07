<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    public $timestamps = true;
    protected $fillable = ['title','content','user_id','slug'];

    public function comments()
    {
        return $this->morphMany('App\Models\Comment', 'commentable');
    }
}