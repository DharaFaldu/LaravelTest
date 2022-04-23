<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $userList = User::where('id','!=',auth()->user()->id)->whereNull('deleted_at')->paginate(10);

        if(auth()->user()->role_id == 2) {
            $userList = User::where('id','!=',auth()->user()->id)->where('role_id','!=',1)->whereNull('deleted_at')->paginate(10);
        }

        if(auth()->user()->role_id == 2) {
            $roles = Role::where('name','!=','Admin')->get();
        } else {
            $roles = Role::all();
        }

        return view('users.index', [
            'userList' => $userList,
            'roles'=>$roles
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
            'name' => ['required', 'string', 'max:255'],
        ]);

        if(!isset($request->user_id)) {
            $password = explode('@',$request->email);
            if(strlen($password[0]) < 8) {
                $passwordNew = $password[0].str_repeat("1", 8-strlen($password[0]));
            }

            $request->password = Hash::make($passwordNew);

            $request->validate([
                'email' => 'required|email|unique:users,email',
            ]);
        } else {
            $request->password = User::find($request->user_id)->password;
        }

        User::updateOrCreate(['id' => $request->user_id],
            [
                'name' => $request->name,
                'email' => $request->email,
                'role_id' => $request->role_id,
                'password' => $request->password,
            ]);

        if($request->user_id == '') {
            Session::flash('success', 'User added successfully.');
            return response()->json(['success'=>'User added successfully.']);
        } else {
            Session::flash('success', 'User updated successfully.');
            return response()->json(['success'=>'User updated successfully.']);
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
        $user = User::find($id);
        $roles = Role::all();
        return view('users.edit-profile', [
            'user' => $user,
            'roles'=>$roles
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find($id);
        return response()->json($user);
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
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255',Rule::unique('users')->ignore($id),],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::find($id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->back()->with('success', 'User Data has been updated successfully');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('users.index')
            ->with('success','User has been deleted successfully');
    }
}
