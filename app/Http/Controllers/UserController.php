<?php
namespace App\Http\Controllers;

use App\Models\UserInfo;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // Show the list of users
    public function index()
    {
        $users = UserInfo::paginate(10);
        return view('users.index', compact('users'));
    }

    // Show the form for creating a new user
    public function create()
    {
        return view('users.create');
    }

    // Store a newly created user
    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email'      => 'required|email|unique:user,email',
        ]);

        UserInfo::create([
            'first_name' => $request->first_name,
            'last_name'  => $request->last_name,
            'email'      => $request->email,
        ]);

        return redirect()->route('users')->with('success', 'Người dùng đã được tạo thành công.');
    }

    // Show the form for editing the user
    public function edit($id)
    {
        $user = UserInfo::findOrFail($id);
        return view('users.edit', compact('user'));
    }

    // Update the specified user
    public function update(Request $request, $id)
    {
        $user = UserInfo::findOrFail($id);

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email'      => 'required|email|unique:user,email,' . $user->id,
        ]);

        $user->update([
            'first_name' => $request->first_name,
            'last_name'  => $request->last_name,
            'email'      => $request->email,
        ]);

        return redirect()->route('users')->with('success', 'Người dùng đã được cập nhật thành công.');
    }

    // Remove the specified user
    public function destroy($id)
    {
        $user = UserInfo::findOrFail($id);
        $user->delete();

        return redirect()->route('users')->with('success', 'Người dùng đã được xóa thành công.');
    }
}
