<?php
namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = request()->user();

        $posts = $user->posts()->paginate();

        return PostResource::collection($posts);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request)
    {
        $data = $request->validated();
        $data['author_id'] = $request->user()->id;

        $post = Post::create($data);

        return response()->json(new PostResource($post), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $post = Post::findOrFail($id);

        if ($post->author_id !== request()->user()->id) {
            abort(403, 'Access Denied');
        }

        return new PostResource($post);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $post = Post::findOrFail($id);

        $data = $request->validate([
            'title' => 'required|string|min:3|max:50',
            'body' => 'required|string|min:10|max:50',
        ]);

        $post->update($data);

        return new PostResource($post);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        $post->delete();

        return response()->noContent();
    }
}
