<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\models\Post;
use App\models\User;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::where('isPublished',1)->get();
        if (empty($posts)) {
            return [
                'message' => 'We currently do not have any post',
                'status-code' => 404
            ];
        }
        return [
            'message' => $posts,
            'status-code' => 200
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function AllPostToAdmin(Request $request)
    {
        $posts = Post::all();
        if (empty($posts)) {
            return [
                'message' => 'We currently do not have any post',
                'status-code' => 404
            ];
        }
        return [
            'message' => $posts,
            'status-code' => 200
        ];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'body' => 'required',
            'cover_image' => 'image|nullable|max:1999'
         ]);
        //  Handle file upload
        if ($request->hasFile('cover_image')) {
            // get file
            $fileNameWithExt = $request->file('cover_image')->getClientOriginalName();
            // get file name
            $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
            // get file extension
            $extension = $request->file('cover_image')->getClientOriginalExtension();
            // Name to store
            $fileNameToStore = $fileName.'_'.time().'.'.$extension;
            // upload to storage
            $path = $request->file('cover_image')->storeAs('public/cover_images', $fileNameToStore);
        }else {
            $fileNameToStore = 'noimage.jpg';
        }

        $user = User::find($request->user_id);
        if ($user) {
            $post = new Post();
            $post->title = $request->title;
            $post->body = $request->body;
            $post->user_id = $user->id;
            $post->cover_image = $fileNameToStore;
            $post->isPublished = 0;
            $post->save();

            return $post->toJson();
        } else {
            return ['message' => 'You are not allowed to make a post, your record is not found'];
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $post = Post::find($id);
        if (!empty($post)) {
            return response()->json(['message' => $post, 'status-code' => 200], 200);
            // return ['message' => $post->toJson(), 'status-code' => 200];
        }else {
            return response()->json(['message' => 'post not found'], 404);
            // return ['message' => 'Post Record Not Found!', 'status-code' => 404];
        }
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
        $this->validate($request, [
            'title' => 'required',
            'body' => 'required',
            'cover_image' => 'image|nullable|max:1999'
            ]);
    
            // Handle file
            if ($request->hasFile('cover_image')) {
                // get file
                $fileNameWithExt = $request->file('cover_image')->getClientOriginalName();
                // get file name
                $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
                // get file extension
                $extension = $request->file('cover_image')->getClientOriginalExtension();
                // Name to store
                $fileNameToStore = $fileName.'_'.time().'.'.$extension;
                // upload to storage
                $path = $request->file('cover_image')->storeAs('public/cover_images', $fileNameToStore);
            }
            
    
            //update data
            $post = Post::find($id);
    
            if (empty($post->id)) {
                return ['message' => 'Post Record Not Found!', 'error-code' => 404];
            }
            $post->title = $request->title;
    
            $post->body = $request->body;
    
            if ($post->user_id == $request->user_id) {
    
                $post->user_id = $request->user_id;
    
            }else {
    
                return "Unauthorized Request";
    
            }
    
            $post->user_id = $request->user_id;
    
            if ($request->hasFile('cover_image')) {
    
                $post->cover_image = $fileNameToStore;
                
            }
            $post->isLogged = 1;
    
            $post->save();
    
            return $post;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $user = User::find($request->user_id);
        if (!empty($user)) {
            if ($user->isAdmin == 1) {
                //delete a post
                $post = Post::find($id);
                if (!empty($post)) {
                    $post->delete();
                    return ['message' => "Post Removed", 'status-code' => 200];
                } else {
                    return ['message' => "Post Not found", 'status-code' => 404];
                }
            } else {
                return ['message' => "You are not authorized", 'status-code' => 500];
            }
        } else {
            return ['message' => "admin record not found", 'status-code' => 404];
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $request
     * @return \Illuminate\Http\Response
     */
    public function Approve(Request $request)
    {
        // Policy/Gate for authorization
        $user = User::find($request->user_id);
        if (!empty($user)) {
            if ($user->isAdmin == 1 && $user->role == 1) {
                //update is published
                $post = Post::find($request->post_id);
                if (!empty($post)) {
                    $post->isPublished = 1;
                    $post->update();
                    return "Post Removed";
                } else {
                    return "Post Not found";
                }
            } else {
                return "You are not authorized";
            }
        } else {
            return ['message' => "admin record not found", 'status-code' => 404];
        }

    }
}
