<?php

namespace App\Http\Controllers;
use App\Models\Admin;
use App\Models\Post;

use Illuminate\Http\Request;

class adminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $admin = Admin::all();
        if (empty($admin)) {
            return ['message' => 'Record not found'];
        } else {
            return ['message' => $admin];
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
        // if (empty($role->role)) {
            $admin = new Admin();
            $admin->name = $request->name;
            $admin->email = $request->email;
            $admin->password = md5($request->password);
            $admin->role = 0;
            $admin->save();
            if ($admin->save() == true) {
                return ['message' => 'User Record Saved', 'status-code' => 200];
            } else {
                return ['message' => 'Record not saved', 'status-code' => 400];
            }
        // } else {
        //     if ($request->role == 1) {
        //         $admin = new Admin();
        //         $admin->name = $request->name;
        //         $admin->email = $request->email;
        //         $admin->password = md5($request->password);
        //         $admin->role = 0;
        //         $admin->save();
        //         return ['message' => 'User Record Saved', 'status-code' => 200];
        //     } else {
        //         return ['message' => 'You are not allowed', 'status-code' => 500];
        //     }
        // }
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
        $role = Admin::find($request->role);
        return $role;
        if ($role->role == 1) {
            $admin = Admin::find($id);
            $admin->name = $request->name;
            $admin->email = $request->email;
            $admin->password = md5($request->password);
            $admin->update();
            return ['message' => 'User updated Saved', 'status-code' => 200];
        } else {
            return ['message' => 'You are not allowed', 'status-code' => 500];
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $role = Admin::find($id);
        if (!$role) {
            throw new Exception("Error Processing Request", 1);
        }
        try {
            if ($role == 1) {
                $user = User::find($request);
                $user->delete();
            }
            return $message = [message => 'Record deleted successfully'];
        } catch (Exception $error) {
            return [message => $error->getMessage()];
        }
    }
}
