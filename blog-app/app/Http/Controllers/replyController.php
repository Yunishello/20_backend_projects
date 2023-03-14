<?php

namespace App\Http\Controllers;
use App\Models\Reply;
use App\Models\User;

use Illuminate\Http\Request;

class replyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $reply = Reply::all();
        if (empty($reply)) {
            return [
                'message' => "comment Not Found", 
                'status-code' => 404
            ];
        }else {
            return [
                'message' => $reply->toJson(),
                'status-code' => 200
                ];
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
            $reply = new Reply();
            $reply->comment_id = $request->comment_id;
            $reply->content = $request->content;
            $reply->user_id = $request->user_id;
            $reply->save();

            return [
                "message "=> "Replied Successfully"
            ];
        } else {
            return [
                'message' => 'please login to reply to this comment',
                'status-code' => 200
            ];
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
        $reply = Reply::find($id);
        if ($reply) {
            if ($request->isLogged == 1) {
                if ($reply->user_id == $request->user_id) {
                    $reply->comment_id = $request->comment_id;
                    $reply->content = $request->content;
                    $reply->user_id = $request->user_id;
                    $reply->save();
        
                    return [
                        "message" => "Your reply editted Successfully",
                        'status-code' => 200
                    ];
                } else {
                    return [
                        "message" => "hey, you can't make changes to others replies",
                        'status-code' => 500
                    ];
                }
            } else {
                return [
                    'message' => 'please login to reply to this comment',
                    'status-code' => 200
                ];
            }
        } else {
            return [
                "message" => 'Record not found',
                'status-code' => 404
            ];
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
            $reply = Reply::find($id);
            if (!empty($reply)) {
                $repl->delete();
                return [
                    'message' => "Reply Removed",
                    'status-code' => 200
                ];
            } else {
                return [
                    'message' => "An error occured",
                    'status-code' => 404
                ];
            }
        } else {
            return [
                'message' => "You are not authorized",
                'status-code' => 500
            ];
        }
    }
}
