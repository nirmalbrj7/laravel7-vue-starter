<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Image;
use Mockery\Undefined;
use Gate;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
        //$this->authorize('isAdmin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Gate::allows('isAdmin') || Gate::allows('super')) {
            return User::latest()->paginate(5);
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
        $request->validate([
            'name' => 'required|string|max:191',
            'email' => 'required|email|max:191|unique:users',
            'password' => 'required|max:255|min:8',
        ]);

        return User::create([
           'name' => $request->name,
           'email' => $request->email,
           'type' => $request->type,
           'bio' => $request->bio,
           'password' => bcrypt($request->password)
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //return $id;
        $user = User::findOrFail($id);


        $request->validate([
            'name' => 'required|string|max:191',
            'email' => 'required|email|max:191|unique:users,email,'.$user->id,
            'password' => 'sometimes|min:8',
        ]);

        $user->update($request->all());
        return ['message' => 'Updated user Info'];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //$this->authorize('isAdmin');
        if (Gate::allows('isAdmin')) {
            $user = User::findOrFail($id);
            $user->delete();
            return ['message' => 'User Deleted'];
        }


    }

    public function profile()
    {
        return auth('api')->user();
    }
    public function search(Request $request)
    {
        $search = $request->get('q');
        if ($search){
            $users = User::where(function ($query) use ($search){
                $query->where('name','LIKE',"%$search%")
                    ->orWhere('email','LIKE',"%$search%");

            })->paginate(5);
        }else{
            $users =  User::latest()->paginate(5);
        }

        return $users;
    }

    public function updateProfile(Request $request)
    {
        //dd($request);
        $user =  auth('api')->user();
        $request->validate([
            'name' => 'required|string|max:191',
            'email' => 'required|email|max:191|unique:users,email,'.$user->id,
            'password' => 'sometimes|min:8',
        ]);

        $currentphoto = $user->photo;
        if ($request->photo != $currentphoto){
            $name = time().rand(10,10).'.'.explode('/', explode(':', substr($request->photo, 0, strpos($request->photo, ';')))[1])[1];
            Image::make($request->photo)->resize(320, 240)->save(public_path('images\profile\\').$name);
            $request->merge(['photo' => $name]);

            $userPhoto = public_path('images/profile/').$currentphoto;
            if(file_exists($userPhoto)){
                @unlink($userPhoto);
            }
        }
        if ($request->password != ""){
            $password = bcrypt($request->password);
            //dd($password);
            $request->merge(['password' => $password]);
            //dd($request);
        }

        $user->update($request->all());
        //dd($request);
        return ['message' => 'Success'];
    }
}
