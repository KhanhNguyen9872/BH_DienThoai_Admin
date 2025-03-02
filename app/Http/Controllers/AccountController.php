<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;
use App\Models\UserInfo;

class AccountController extends Controller
{
    public function index()
    {
        // Retrieve accounts with their related user (for full_name)
        $accounts = Account::with('user')->paginate(10);
    
        // Transform each account to include only the desired fields.
        $accounts->getCollection()->transform(function ($account) {
            return [
                'id'             => $account->id,
                'username'       => $account->username,
                // Convert lock_acc: '1' => true, '0' => false
                'lock_acc'       => $account->lock_acc == '1' ? 'Bị khóa' : 'Đang hoạt động',
                // Get full name from the related user, if available.
                'full_name_user' => $account->user ? $account->user->full_name : null,
            ];
        });
    
        return view('accounts.index', compact('accounts'));
    }
    
    /**
     * Show the form for creating a new account.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // Retrieve users for the dropdown selection in the create view.
        $users = UserInfo::all();
        return view('accounts.create', compact('users'));
    }

    /**
     * Store a newly created account in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validate the incoming request data.
        $validated = $request->validate([
            'username' => 'required|string|max:255|unique:account,username',
            'password' => 'required|string|min:6|confirmed',
            'lock_acc' => 'required|in:0,1',
            'user_id'  => 'required|exists:user,id',
        ]);

        // Create a new account instance.
        $account = new Account();
        $account->username = $validated['username'];
        // Hash the password using md5 (not recommended for production)
        $account->password = md5($validated['password']);
        $account->lock_acc = $validated['lock_acc'];
        $account->user_id  = $validated['user_id'];

        $account->save();

        return redirect()->route('accounts')
                         ->with('success', 'Tài khoản đã được tạo thành công.');
    }

    /**
     * Show the form for editing the specified account.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $account = Account::findOrFail($id);
        // Retrieve all users for the dropdown; adjust as needed.
        $users = UserInfo::all();

        return view('accounts.edit', compact('account', 'users'));
    }

    /**
     * Update the specified account in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $account = Account::findOrFail($id);

        $validated = $request->validate([
            'username' => 'required|string|max:255|unique:account,username,' . $id,
            'lock_acc' => 'required|in:0,1',
            'user_id'  => 'required|exists:user,id',
            // Password is optional; if provided, it must be at least 6 characters and confirmed.
            'password' => 'nullable|string|min:6|confirmed'
        ]);

        $account->username = $validated['username'];
        $account->lock_acc = $validated['lock_acc'];
        $account->user_id  = $validated['user_id'];

        // Only update the password if a new one is provided.
        if (!empty($validated['password'])) {
            $account->password = md5($validated['password']);
        }

        $account->save();

        return redirect()->route('accounts')
                         ->with('success', 'Tài khoản đã được cập nhật thành công.');
    }

    public function delete($id)
    {
        $account = Account::findOrFail($id);
        $account->delete();

        return redirect()->route('accounts')
                        ->with('success', 'Tài khoản đã được xóa thành công.');
    }

}
