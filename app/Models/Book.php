<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Book extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $hidden = [
        'created_at', 'updated_at', 'deleted_at'
    ];
    protected $guarded = [];

    protected $dates = ['deleted_at'];

    public function authors()
    {
        return $this->belongsToMany(\App\Models\Author::class, 'book_authors', 'book_id', 'author_id')->withTimestamps();
    }
}
