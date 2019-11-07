<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\User;
use App\Models\Post;
use App\Models\Comment;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Auth;

class PostsController extends Controller
{

    public function __construct()
    {
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'content' => ['required', 'string'],
            'title' => ['required', 'string'],
        ]);
    }

    public function index()
    {
        $posts = Post::orderBy('id','desc')->paginate($this->paginationLimit);
        return response($posts, 200)
                    ->header($this->accept, $this->applicationJson)
                    ->header($this->contentType, $this->applicationJson);
    }

    public function create(Request $request){

        $validator = $this->validator($request->input());

        if($validator->fails()){
            $payload["message"] = "The given data was invalid.";
            $payload["errors"] = $validator->errors();
            return response($payload, 422)
                    ->header($this->accept, $this->applicationJson)
                    ->header($this->contentType, $this->applicationJson);
        }

        $data = $request->only('title','content');
        $data["user_id"] = Auth::user()->id;
        $data["slug"] = Str::slug($data['title']);
        $post = Post::create($data);
        $post->save();

        $response["data"] = $post;

        return response($response, 201)
                    ->header($this->accept, $this->applicationJson)
                    ->header($this->contentType, $this->applicationJson);
    }

    public function updateBySlug(Request $request, $slug){
        $post = Post::where('slug',$slug)->first();

        $data = $request->only('title');

        if($post === null){
            return response("", 404);
        }

        $title = $request->input('title');
        $post->title = $title;
        $post->slug = Str::slug($title);
        $post->save();

        return response($data, 200)
                    ->header($this->accept, $this->applicationJson)
                    ->header($this->contentType, $this->applicationJson);
    }

    public function deleteBySlug(Request $request, $slug){
        $post = Post::where('slug',$slug)->first();

        if($post === null){
            return response("", 404);
        }

        $post->delete();

        $data["status"] = "record deleted successfully";

        return response($data, 200)
                    ->header($this->accept, $this->applicationJson)
                    ->header($this->contentType, $this->applicationJson);
    }

    public function getBySlug($slug){
        $post = Post::where('slug',$slug)->first();

        if($post === null){
            return response("", 404);
        }

        $result["data"] = $post;

        return response($result, 200)
                    ->header($this->accept, $this->applicationJson)
                    ->header($this->contentType, $this->applicationJson);
    }

    public function comments($slug){
        $post = Post::where('slug',$slug)->with(['comments'])->first();

        if($post === null){
            return response("", 404);
        }

        $response["data"] = [];
        foreach ($post->comments as $comment) {
            $response["data"] = $comment;
        }

        return response($response, 200)
                    ->header($this->accept, $this->applicationJson)
                    ->header($this->contentType, $this->applicationJson);
    }

    public function createPostComment(Request $request,$slug){

        $data = $request->only('body');

        $validator = Validator::make($data, [
            'body' => ['required', 'string']
        ]);

        $payload["message"] = "The given data was invalid.";
        if($validator->fails()){
            $payload["errors"] = $validator->errors();
            return response($payload, 422)
                    ->header($this->accept, $this->applicationJson)
                    ->header($this->contentType, $this->applicationJson);
        }

        $post = Post::where('slug',$slug)->first();

        try {

            if($post === null ){
                throw new \Exception('Post not found!');
            }

            $comment = new Comment($data);
            $comment->creator_id = Auth::user()->id;

            $post->comments()->save($comment);
            $response["data"] = $comment;
            
        } catch (\Exception $e) {
            $payload["message"] = "No query results for ".get_class(new Post);
            $payload["exception"] = $e->getMessage();
            return response($payload, 404)
                    ->header($this->accept, $this->applicationJson)
                    ->header($this->contentType, $this->applicationJson);
        }

        return response($response, 201)
                    ->header($this->accept, $this->applicationJson)
                    ->header($this->contentType, $this->applicationJson);
    }

    public function updatePostComment(Request $request,$slug,$commentId){
        
        $data = $request->only('body');

        $validator = Validator::make($data, [
            'body' => ['required', 'string']
        ]);

        $payload["message"] = "The given data was invalid.";
        if($validator->fails()){
            $payload["errors"] = $validator->errors();
            return response($payload, 422)
                    ->header($this->accept, $this->applicationJson)
                    ->header($this->contentType, $this->applicationJson);
        }

        $post = Post::where('slug',$slug)->first();

        try {

            if($post === null ){
                throw new \Exception('Post not found!');
            }

            $comment = $post->comments()->find($commentId);
            $comment->body = $request->input('body');
            $comment->save();
            
        } catch (\Exception $e) {
            $payload["message"] = "No query results for ".get_class(new Post);
            $payload["exception"] = $e->getMessage();
            return response($payload, 404)
                    ->header($this->accept, $this->applicationJson)
                    ->header($this->contentType, $this->applicationJson);
        }

        return response($data, 200)
                    ->header($this->accept, $this->applicationJson)
                    ->header($this->contentType, $this->applicationJson);
    }

    public function deletePostComment(Request $request,$slug,$commentId){
        
        $post = Post::where('slug',$slug)->first();

        try {

            if($post === null ){
                throw new \Exception('Post not found!');
            }

            $comment = $post->comments()->find($commentId);
            $comment->delete();
            
        } catch (\Exception $e) {
            $payload["exception"] = get_class($e);
            
        } catch (\Error $e)  {
            $payload["exception"] = get_class($e);
        }

        if(isset($payload["exception"])){
            $payload["message"] = "No query results for ".get_class(new Post);
            return response($payload, 404)
                    ->header($this->accept, $this->applicationJson)
                    ->header($this->contentType, $this->applicationJson);
        }

        $data["status"] = "record deleted successfully"; 

        return response($data, 200)
                    ->header($this->accept, $this->applicationJson)
                    ->header($this->contentType, $this->applicationJson);
    }
}
