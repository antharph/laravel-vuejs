<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    public $timestamps = true;
    protected $fillable = ['body','creator_id'];

    public function commentable()
    {
        return $this->morphTo();
    }
}