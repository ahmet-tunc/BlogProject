<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;
    protected $table = 'comment';
    protected $primaryKey = 'id';
    protected $guarded = [];

    public function scopeStatusActive($query)
    {
        return $query->where('comment.status', 1);
    }


    public function getPost()
    {
        return $this->hasOne('App\Models\Posts', 'id', 'post_id')->select('title');
    }
}
