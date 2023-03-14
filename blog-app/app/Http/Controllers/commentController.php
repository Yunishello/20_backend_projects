<?php

namespace App\Http\Controllers;
use App\Models\Comment;

use Illuminate\Http\Request;

class commentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $comment = Comment::all();
        if (empty($comment)) {
            return ['message' => "comment Not Found", 'status-code' => 404];
        }else {
            return ['message' => $comment->toJson(), 'status-code' => 200];
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->isLogged == 1) {
            $comment = new Comment();
            $comment->post_id = $request->post_id;
            $comment->content = $request->content;
            $comment->user_id = $request->user_id;
            $comment->save();
            
            return ['message' => 'You\'ve just commented on this post', 'status-code' => 200];
        } else {
            return ['message' => "Please login to make comment", 'status-code' => 404];
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
        $comment = Comment::find($id);
        if (empty($comment->id)) {
            return ['message' => 'Comment not found', 'status-code' => 404];
        }else {
            return $comment->toJson();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
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
        if ($request->isLogged == 1) {
            $comment = Comment::find($id);
            if ($comment->user_id == $request->user_id) {
                if ($comment) {
                    $comment->post_id = $request->post_id;
                    $comment->content = $request->content;
                    $comment->user_id = $request->user_id;
                    $comment->save();
        
                    return  ['message' => 'Record Saved Successfully', 'status-code' => 200];
                }
            } else {
                return ['message' => 'hey, you can\'t make changes to others comment', 'status-code' => 500];
            }
        } else {
            return ['message' => "Please login to edit your comment", 'status-code' => 404];
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($request->user_id);
        if ($user->isAdmin == 1) {
            $comment = Comment::find($id);
            if (!empty($comment)) {
                $comment->delete();
                return [
                    'message' => "Commment Removed",
                    'status-code' => 200
                ];
            } else {
                return "An error occured";
            }
        } else {
            return "You are not authorized";
        }
    }
}
