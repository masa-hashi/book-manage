<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'author',
        'publisher',
        'published_year',
        'isbn',
        'genre',
        'description',
        'status',
        'cover_image_url',
        'category_id',
        'rating',
        'read_at',
    ];

    protected $casts = [
        'read_at' => 'date',
        'published_year' => 'integer',
        'rating' => 'integer',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function getStatusBadgeColorAttribute(): string
    {
        return match ($this->status) {
            '読了' => 'green',
            '読中' => 'blue',
            '未読' => 'gray',
            default => 'gray',
        };
    }
}
