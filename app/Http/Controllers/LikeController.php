<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Like;

class LikeController extends Controller
{
    public function likeOrUnlike($id){
        $post= Post::find($id);

        if(!$post){
            return response([
                'message'=> 'aucune publication trouvée '
            ,403]);
        }

        $like = $post->likes()->where('user_id', auth()->user()->id)->first();

        //if not like
        if(!$like){
            Like::create([
                'post_id'=>$id,
                'user_id'=>auth()->user()->id
            ]);
            return response([
                'message'=> 'aimé '
            ],200);
        }

        //else, dislike it

        $like->delete();

        return response([
            'message'=> 'desliked '
        ],200);
    }
}
