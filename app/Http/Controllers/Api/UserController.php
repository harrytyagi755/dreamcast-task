<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

    public function index()
    {
        $users = User::with('role')->get();
        return response()->json($users);
    }

    
    public function getRoles()
    {
        $roles = Role::all();
        return response()->json($roles);
    }

    
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|regex:/^[6-9]\d{9}$/',
            'description' => 'nullable|string',
            'role_id' => 'required|exists:roles,id',
            'profile_image' => 'nullable|image|max:2048',
        ]);
    
        if ($request->hasFile('profile_image')) {
            $fileName = time().'.'.$request->profile_image->extension();
            $request->profile_image->storeAs('public', $fileName);
        } else {
            $fileName = null;
        }
    
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'description' => $request->description,
            'role_id' => $request->role_id,
            'profile_image' => $fileName,
        ]);
    
        return response()->json($user);
    }

    
    
}
