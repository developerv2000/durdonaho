<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Source extends Model
{
    use HasFactory;

    const OWN_QUOTE_KEY = 'user';
    const UNKNOWN_AUTHOR_KEY = 'unknown';
    const AUTHORS_QUOTE_KEY = 'author';
    const FROM_BOOK_KEY = 'book';
    const FROM_MOVIE_KEY = 'movie';
    const FROM_SONG_KEY = 'song';
    const FROM_PROVERB_KEY = 'proverb';
    const FROM_PARABLE_KEY = 'parable';

    const UNKNOWN_AUTHOR_DEFAULT_IMAGE = '__default-unknown-author.png';
    const FROM_BOOK_DEFAULT_IMAGE = '__default-book.png';
    const FROM_MOVIE_DEFAULT_IMAEG = '__default-movie.png';
    const FROM_SONG_DEFAULT_IMAGE = '__default-song.png';
    const FROM_PROVERB_DEFAULT_IMAGE = '__default-proverb.png';
    const FROM_PARABLE_DEFAULT_IMAGE = '__default-parable.png';

    public function quotes()
    {
        return $this->hasMany(Quote::class);
    }
}
