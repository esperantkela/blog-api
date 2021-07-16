<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Post;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response([
            'posts'=>Post::orderBy('created_at', 'desc')->with('user:id,name,image')->withCount('comments', 'likes')->get(),
        ],200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $attrs = request()->validate([
            'body'=>'required|string'
        ]);

        $post = Post::create([
            'body'=>$attrs['body'],
            'user_id'=>auth()->user()->id
        ]);

        return response([
            'message'=>'la publication a été créée avec succès',
            'post'=>$post
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
        return response([
            'posts'=>Post::where('id', $id)->withCount('comments', 'likes')->get()
        ]);
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
        $post= Post::find($id);

        if(!$post){
            return response([
                'message'=> 'aucune publication trouvée '
            ,403]);
        }

        if($post->user_id !=auth()->user()->id){
            return response([
                'message'=> 'permission non accordée '
            ,403]);
        }

        $attrs = request()->validate([
            'body'=>'required|string'
        ]);

        $post->update([
            'body'=>$attrs['body']
        ]);

        return response([
            'message'=>'la publication a été modifiée avec succès',
            'post'=>$post
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
        $post= Post::find($id);

        if(!$post){
            return response([
                'message'=> 'aucune publication trouvée '
            ,403]);
        }

        if($post->user_id !=auth()->user()->id){
            return response([
                'message'=> 'permission non accordée '
            ,403]);
        }

        $post->comments()->delete();
        $post->likes()->delete();
        $post->delete();


        return response([
            'message'=>'cette publication a été supprimée',
        ],200);
    }
}
