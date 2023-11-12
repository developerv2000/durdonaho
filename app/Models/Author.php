<?php

namespace App\Models;

use App\Helpers\Helper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Author extends Model
{
    use HasFactory;

    public function quotes()
    {
        return $this->hasMany(Quote::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function publisher()
    {
        return $this->belongsTo(User::class, 'user_id');
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

    /**
     * Create unapproved author, while creating/updating quotes by USER
     */
    public static function createUnapprovedItem($name)
    {
        $author = new Author();
        $author->name = $name;
        $author->slug = Helper::generateUniqueSlug($name, Author::class);
        $author->user_id = Auth::user()->id;
        $author->biography = $name;
        $author->approved = false;
        $author->save();
    }

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        // Also delete model relations while deleting
        static::deleting(function ($author) {
            $author->quotes()->approved()->each(function ($quote) {
                $quote->delete();
            });

            $author->likes()->each(function ($like) {
                $like->delete();
            });

            $author->favorites()->each(function ($favorite) {
                $favorite->delete();
            });

            $author->reports()->each(function ($report) {
                $report->delete();
            });
        });
    }
}