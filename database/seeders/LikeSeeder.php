<?php

namespace Database\Seeders;

use App\Models\Like;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LikeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $quoteId = [1,1,3,6,6,8,9];
        $userId = [1,2,2,1,2,1,2];

        for($i=0; $i<count($quoteId); $i++) {
            $like = new Like();
            $like->quote_id = $quoteId[$i];
            $like->user_id = $userId[$i];
            $like->save();   
        }


        $authorId = [2,3,3,4,5,8,9];
        $userId = [2,2,1,2,2,1,1];

        for($i=0; $i<count($quoteId); $i++) {
            $like = new Like();
            $like->author_id = $authorId[$i];
            $like->user_id = $userId[$i];
            $like->save();   
        }
    }
}