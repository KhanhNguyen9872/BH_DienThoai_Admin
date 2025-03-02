<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Display a listing of the admin accounts.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Retrieve all admin records paginated (10 per page, adjust as needed)
        $admins = Admin::paginate(10);

        // Return the view (e.g., resources/views/admins/index.blade.php) with the admins
        return view('admin-accounts.index', compact('admins'));
    }

     /**
     * Show the form for editing the specified admin.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $admin = Admin::findOrFail($id);
        return view('admin-accounts.edit', compact('admin'));
    }

    /**
     * Update the specified admin in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        // Validate input data
        $validated = $request->validate([
            'username' => 'required|string|max:255|unique:admin,username,' . $id,
            'full_name' => 'required|string|max:255',
            'email'    => 'required|email|max:255|unique:admin,email,' . $id,
            'img'      => 'nullable|image|max:2048',
        ]);

        // Retrieve the admin record
        $admin = Admin::findOrFail($id);

        // Update the admin's properties
        $admin->username  = $validated['username'];
        $admin->full_name = $validated['full_name'];
        $admin->email     = $validated['email'];

        // If a new image is uploaded, store it and update the 'img' column.
        if ($request->hasFile('img')) {
            $path = $request->file('img')->store('admin_images', 'public');
            $admin->img = $path;
        }

        $admin->save();

        return redirect()->route('admins')->with('success', 'Tài khoản admin đã được cập nhật thành công.');
    }

    /**
     * Show the form for creating a new admin account.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin-accounts.create');
    }

    /**
     * Store a newly created admin in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validate incoming data
        $validated = $request->validate([
            'username' => 'required|string|max:255|unique:admin,username',
            'full_name' => 'required|string|max:255',
            'email'    => 'required|email|max:255|unique:admin,email',
            'password' => 'required|string|min:6|confirmed',
            'img'      => 'nullable|image|max:2048',
        ]);

        // Create a new admin record
        $admin = new Admin();
        $admin->username  = $validated['username'];
        $admin->full_name = $validated['full_name'];
        $admin->email     = $validated['email'];
        $admin->password  = md5($validated['password']); // MD5 used as requested

        // Handle image upload if provided
        if ($request->hasFile('img')) {
            $path = $request->file('img')->store('admin_images', 'public');
            $admin->img = $path;
        }

        $admin->save();

        return redirect()->route('admins')->with('success', 'Tài khoản admin đã được tạo thành công.');
    }

    public function delete($id)
{
    $admin = Admin::findOrFail($id);

    // Prevent deleting the currently authenticated admin account.
    if ($admin->id == auth()->id()) {
        return redirect()->route('admins')->with('error', 'Không thể xóa tài khoản hiện tại.');
    }

    $admin->delete();

    return redirect()->route('admins')->with('success', 'Tài khoản admin đã được xóa thành công.');
}

}
