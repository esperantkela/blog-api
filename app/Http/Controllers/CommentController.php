<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Post;
use App\Models\Comment;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {

        $post= Post::find($id);

        if(!$post){
            return response([
                'message'=> 'aucune publication trouvée '
            ,403]);
        }

        return response([
            'post'=>$post->comments()->with('user:id,name,image')->get()
        ],200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id)
    {

        $post= Post::find($id);

        if(!$post){
            return response([
                'message'=> 'aucune publication trouvée '
            ,403]);
        }

        $attrs = request()->validate([
            'comment'=>'required|string'
        ]);

        Comment::create([
            'comment'=>$attrs['comment'],
            'post_id'=>$id,
            'user_id'=>auth()->user()->id
        ]);

        return response([
            'message'=>'commentaire ajouté !',
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $comment = Comment::find($id);

        if(!$comment){
            return response([
                'message'=> 'aucun commentaire trouvé '
            ,403]);
        }

        if($comment->user_id !=auth()->user()->id){
            return response([
                'message'=> 'permission non accordée '
            ,403]);
        }

        $attrs = request()->validate([
            'comment'=>'required|string'
        ]);

        $post->update([
            'comment'=>$attrs['comment']
        ]);

        return response([
            'message'=>'commentaire modifié',

        ]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $comment = Comment::find($id);

        if(!$comment){
            return response([
                'message'=> 'aucun commentaire trouvé '
            ,403]);
        }

        if($comment->user_id !=auth()->user()->id){
            return response([
                'message'=> 'permission non accordée '
            ,403]);
        }

        $comment->delete();

        return response([
            'message'=>'ce commentaire est supprimé',
        ],200);
    }
}
