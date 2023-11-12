<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SourceBook extends Model
{
    use HasFactory;

    /**
     * Create unapproved source while creating/updating quotes by USER
     */
    public static function createUnapprovedItem($title, $author)
    {
        $book = new SourceBook();
        $book->title = $title;
        $book->author = $author;
        $book->approved = false;
        $book->save();
    }

    /**
     * Create approved source while creating/updating quotes by ADMIN
     */
    public static function createApprovedItem($title, $author)
    {
        $book = new SourceBook();
        $book->title = $title;
        $book->author = $author;
        $book->approved = true;
        $book->save();
    }
}
