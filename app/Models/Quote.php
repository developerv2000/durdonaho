<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quote extends Model
{
    use HasFactory;

    public function author()
    {
        return $this->belongsTo(Author::class);
    }

    public function publisher()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    public function scopeApproved($query)
    {
        return $query->where('approved', true);
    }

    public function scopeUnapproved($query)
    {
        return $query->where('approved', false);
    }

    public function source()
    {
        return $this->belongsTo(Source::class);
    }

    public function sourceBook()
    {
        return $this->belongsTo(SourceBook::class, 'source_book_id');
    }

    public function sourceMovie()
    {
        return $this->belongsTo(SourceMovie::class, 'source_movie_id');
    }

    public function sourceSong()
    {
        return $this->belongsTo(SourceSong::class, 'source_song_id');
    }

    public function sourcedFrom($key)
    {
        return $this->source->key == $key ? true : false;
    }

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        // Also delete model relations while deleting
        static::deleting(function ($quote) {
            $quote->categories()->detach();

            $quote->likes()->each(function ($like) {
                $like->delete();
            });

            $quote->favorites()->each(function ($favorite) {
                $favorite->delete();
            });

            $quote->reports()->each(function ($report) {
                $report->delete();
            });
        });
    }
}
