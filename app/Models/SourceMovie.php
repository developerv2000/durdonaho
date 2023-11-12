<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SourceMovie extends Model
{
    use HasFactory;

    /**
     * Create unapproved source while creating/updating quotes by USER
     */
    public static function createUnapprovedItem($title, $year)
    {
        $movie = new SourceMovie();
        $movie->title = $title;
        $movie->year = $year;
        $movie->approved = false;
        $movie->save();
    }

    /**
     * Create approved source while creating/updating quotes by ADMIN
     */
    public static function createApprovedItem($title, $year)
    {
        $movie = new SourceMovie();
        $movie->title = $title;
        $movie->year = $year;
        $movie->approved = true;
        $movie->save();
    }
}
