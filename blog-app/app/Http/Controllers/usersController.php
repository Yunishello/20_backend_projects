<?php

namespace App\Http\Controllers;
use App\Models\User;

use Illuminate\Http\Request;

class usersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = User::Where('isAdmin', 1)->get();
        if (!empty($user) && !isset($user)) {
            return [
                'message' => $user,
                'status-code' => 200
            ];
        } else {
            return [
                'message' => 'No Admin record found',
                'status-code' => 404
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
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = md5($request->password);
        $user->isAdmin = 0;
        $user->role = 0;
        if ($user->save() == true) {
            $user->save();
            return [
                'message' => 'User record saved',
                'status-code' => 200
            ];
        }else {
            return [
                'message' => 'User record not saved',
                'status-code' => 500
            ];
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
        $admin = User::where('isAdmin', 1);
        if (!empty($admin)) {
            if ($admin->role == 1) {
                return [
                    'message' => User::find($id)->toJson(),
                    'status-code' => 200
                ];
            } else {
                return [
                    'message' => 'you are not allowed',
                    'status-code' => 500
                ];
            }
        } else {
            return [
                'message' => 'You are not an admin',
                'status-code' => 500
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
        $user = User::find($id);
        $user->name = $request->name;
        $user->email = $request->email;
        if ($user->save() == true) {
            $user->save();
            return [
                'message' => 'User record saved',
                'status-code' => 200
            ];
        }else {
            return [
                'message' => 'User record not saved',
                'status-code' => 500
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
        if (!empty($user)) {
            if ($user->isAdmin == 1 && $user->role == 1) {
                //update is published
                $user = User::find($id);
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

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $request
     * @return \Illuminate\Http\Response
     */
    public function MakeAdmin(Request $request)
    {
        $admin = User::where('isAdmin', 1);
        // return ['message' => $admin];
        if (!empty($admin)) {
            if ($admin->role == 1) {
                //update is published
                $user = User::find($request->user_id);
                if (!empty($user)) {
                    $user->isAdmin = 1;
                    $user->update();
                    return "Admin Created";
                } else {
                    return [
                        'message' => "User Not found",
                        'status-code' => 404
                    ];
                }
            } else {
                return [
                    'message' => "You are not authorized",
                    'status-code' => 403
                    ];
            }
        } else {
            return [
                'message' => "admin record not found",
                'status-code' => 404
            ];
        }

    }
}
