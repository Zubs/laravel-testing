<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Collection|Post[]
     */
    public function index()
    {
        return Post::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Post
     */
    public function store(Request $request): Post
    {
        $fields = $request->validate([
            'title' => ['string', 'required'],
            'body' => ['string', 'required']
        ]);

        return Post::create($fields);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Post
     */
    public function show(int $id): Post
    {
        return Post::find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, int $id)
    {
        $fields = $request->validate([
            'title' => ['string', 'required'],
            'body' => ['string', 'required']
        ]);

        $post = Post::find($id);
        $post->update($fields);

        return $post;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy(int $id)
    {
        $post = Post::find($id);
        $post->delete();

        return $post;
    }
}
