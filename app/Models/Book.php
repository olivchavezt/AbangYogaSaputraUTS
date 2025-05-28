<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class Book extends Model
{
    use HasFactory, HasUlids;

    protected $primaryKey = 'book_id';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'title',
        'isbn',
        'publisher',
        'year_published',
        'stock',
    ];

    protected $casts = [
        'stock' => 'integer',
    ];

    public function authors()
    {
        return $this->belongsToMany(Author::class, 'book_authors', 'book_id', 'author_id');
    }

    public function loans()
    {
        return $this->hasMany(Loan::class, 'book_id', 'book_id');
    }
}