<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;

class Release extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'content',
        'author_id',
        'status',
        'type',
        'document_path',
        'background_image',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'document_path' => 'array',
    ];

    protected static function booted()
    {
        $flushCache = fn () => Cache::tags(['releases'])->flush();
        static::saved($flushCache);
        static::deleted($flushCache);
        static::restored($flushCache);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function readByUsers()
    {
        return $this->belongsToMany(User::class, 'release_user')->withPivot('read_at')->withTimestamps();
    }
}
