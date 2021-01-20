<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostCategory extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $primaryKey = 'id';
    protected $table = 'post_category';


    public function getUser()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id')->select('name');
    }

    public function scopeStatusActive($query)
    {
        return $query->where('status', 1);
    }

    public function scopeDefaultSearch($query, $text)
    {
        $query->where(function ($query) use ($text /*, $type */)
        {
            $query->where('name', '%' . $text . '%')
                ->orWhere('slug', 'LIKE', '%' . $text . '½')
                ->orWhere('content', 'LIKE', '%' . $text . '%');
        });
    }

}
