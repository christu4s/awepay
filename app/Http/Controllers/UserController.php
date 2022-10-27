<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $search = $_GET['search']??'';
        $sort= $_GET['sort']??'';
        if($sort !='' && gettype($sort) == 'string' && in_array($sort,["email","phone"]))
        $user = User::orderBy($sort)->get();
        if($search !='')
        $user = User::where('email',$search)->whereOr('phone',$search)->get();
        else 
        $user = User::get();
        if($user)
        {
            return response()->json([
                'status' => true,
                'message' => 'success',
                'user' => $user
            ]); 
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Not found any record'
            ]); 
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->only(['name', 'email']), [ 
            'name' => 'required',
            'email' => 'required|email'
        ]);
      
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Email and name are required',
            ]); 
        }


        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone??'';
        $user->age = $request->age??'';
        $user->save();
        $userId = $user->id;

        return response()->json([
            'status' => true,
            'message' => 'success',
            'user_id' => $userId
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
        $validator = Validator::make($request->only(['name', 'email']), [ 
            'name' => 'required',
            'email' => 'required|email'
        ]);
      
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Email and name are required',
            ]); 
        }
        
        $user = User::find($id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone??'';
        $user->age = $request->age??'';
        $user->save();

        return response()->json([
            'status' => true,
            'message' => 'success',
            'user' => $user
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
        $user = User::find($id);
        if($user) {
        $user->delete();

        return response()->json([
            'status' => true,
            'message' => 'success',
        ]); 
    } else {
        return response()->json([
            'status' => false,
            'message' => 'User not found',
        ]); 
    }

    }
}
