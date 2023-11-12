<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SourceSong extends Model
{
    use HasFactory;

    /**
     * Create unapproved source while creating/updating quotes by USER
     */
    public static function createUnapprovedItem($title, $singer)
    {
        $song = new SourceSong();
        $song->title = $title;
        $song->singer = $singer;
        $song->approved = false;
        $song->save();
    }

    /**
     * Create approved source while creating/updating quotes by ADMIN
     */
    public static function createApprovedItem($title, $singer)
    {
        $song = new SourceSong();
        $song->title = $title;
        $song->singer = $singer;
        $song->approved = true;
        $song->save();
    }
}