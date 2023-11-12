<?php

namespace App\Http\Controllers;

use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $liked = Like::where('user_id', Auth::user()->id)
                    ->where('quote_id', $request->quote_id)
                    ->where('author_id', $request->author_id)
                    ->first();

        if($liked) {
            $liked->delete();

            $status = 'unliked';
        } else {
            $like = new Like();
            $like->user_id = Auth::user()->id;
            $like->quote_id = $request->quote_id;
            $like->author_id = $request->author_id;
            $like->save();

            $status = 'liked';
        }

        $likesCount = Like::where('quote_id', $request->quote_id)
                    ->where('author_id', $request->author_id)
                    ->count();

        return [
            'status' => $status,
            'likesCount' => $likesCount
        ];
    }
}
