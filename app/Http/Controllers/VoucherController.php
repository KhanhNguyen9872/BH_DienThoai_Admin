<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use App\Models\UserInfo;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
    // Display a paginated list of vouchers
    public function index()
    {
        $vouchers = Voucher::paginate(10);

        foreach ($vouchers as $voucher) {
            try {
                $voucher->discount = $voucher->discount * 100 . "%";
                $voucher->user_id = json_decode($voucher->user_id, true);
            } catch (\Throwable $th) {
                //throw $th;
            }
            
        }

        return view('vouchers.index', compact('vouchers'));
    }

    // Show the form for creating a new voucher
    public function create()
    {
        $users = UserInfo::all();
        return view('vouchers.create', compact('users'));
    }

    // Store a newly created voucher in storage
public function store(Request $request)
{
    if (empty($request->code)) {
        return redirect()->route('vouchers.create')
                         ->with('error', 'Mã giảm giá không được để trống');
    }

    if ($request->discount < 0 || $request->discount > 100) {
        return redirect()->route('vouchers.create')
                         ->with('error', 'Giảm giá phải nằm trong khoảng từ 0 đến 100');
    }

    if ($request->count < 0 || $request->count > 100000) {
        return redirect()->route('vouchers.create')
                         ->with('error', 'Số lượng phải nằm trong khoảng từ 0 đến 100000');
    }

    // Validate the input fields.
    // Note: We changed the rules for limit_user and user_id to "nullable|array"
    // so that when using multiple select inputs, the values come as arrays.
    $request->validate([
        'code'       => 'required|unique:voucher,code',
        'discount'   => 'required|numeric',
        'count'      => 'required|integer',
        'limit_user' => 'nullable|array',
        'user_id'    => 'nullable|array',
    ]);

    // Retrieve arrays or default to an empty array
    $limitUsers = $request->limit_user ?? [];
    $userIds = $request->user_id ?? [];

    // Try to parse each element as integer. If parsing fails, keep the original value.
    $parsedLimitUsers = array_map(function($item) {
        $parsed = filter_var($item, FILTER_VALIDATE_INT);
        return ($parsed === false) ? $item : $parsed;
    }, $limitUsers);

    $parsedUserIds = array_map(function($item) {
        $parsed = filter_var($item, FILTER_VALIDATE_INT);
        return ($parsed === false) ? $item : $parsed;
    }, $userIds);

    // Convert discount from percentage to decimal
    $request->merge([
        'discount'   => $request->discount / 100,
        'limit_user' => $parsedLimitUsers,
        'user_id'    => $parsedUserIds,
    ]);

    Voucher::create($request->all());

    return redirect()->route('vouchers')->with('success', 'Mã giảm giá đã được tạo thành công!');
}


    public function edit($id)
    {
        $voucher = Voucher::findOrFail($id);

        // Multiply discount by 100 to display it as a percentage in the form
        $voucher->discount = $voucher->discount * 100;

        // Prepare the selected users for the multiple select fields
        $selectedLimitUsers = old('limit_user', is_array($voucher->limit_user) ? $voucher->limit_user : json_decode($voucher->limit_user, true) ?? []);
        $selectedUsers = old('user_id', is_array($voucher->user_id) ? $voucher->user_id : json_decode($voucher->user_id, true) ?? []);

        // Retrieve all users from the UserInfo model (adjust this if your model name is different)
        $users = UserInfo::all();

        return view('vouchers.edit', compact('voucher', 'selectedLimitUsers', 'selectedUsers', 'users'));
    }


   public function update(Request $request, $id)
{
    if (empty($request->code)) {
        return redirect()->route('vouchers.edit')
                         ->with('error', 'Mã giảm giá không được để trống');
    }

    if ($request->discount < 0 || $request->discount > 100) {
        return redirect()->route('vouchers.edit')
                        ->with('error', 'Giảm giá phải nằm trong khoảng từ 0 đến 100');
    }

    if ($request->count < 0 || $request->count > 100000) {
        return redirect()->route('vouchers.edit')
                        ->with('error', 'Số lượng phải nằm trong khoảng từ 0 đến 100000');
    }

    $voucher = Voucher::findOrFail($id);

    $request->validate([
        'code'       => 'required|unique:voucher,code,' . $voucher->id,
        'discount'   => 'required|numeric',
        'count'      => 'required|integer',
        'limit_user' => 'nullable|array',
        'user_id'    => 'nullable|array',
    ]);

    // Retrieve arrays or default to an empty array
    $limitUsers = $request->limit_user ?? [];
    $userIds = $request->user_id ?? [];

    // Try to parse each element as integer. If parsing fails, keep the original value.
    $parsedLimitUsers = array_map(function($item) {
        $parsed = filter_var($item, FILTER_VALIDATE_INT);
        return ($parsed === false) ? $item : $parsed;
    }, $limitUsers);

    $parsedUserIds = array_map(function($item) {
        $parsed = filter_var($item, FILTER_VALIDATE_INT);
        return ($parsed === false) ? $item : $parsed;
    }, $userIds);

    // Convert discount from percentage to decimal
    $request->merge([
        'discount'   => $request->discount / 100,
        'limit_user' => $parsedLimitUsers,
        'user_id'    => $parsedUserIds,
    ]);

    $voucher->update($request->all());

    return redirect()->route('vouchers')->with('success', `Mã giảm giá đã được cập nhật [ID: ${id}]`);
}

    // Remove the specified voucher from storage
    public function delete($id)
    {
        $voucher = Voucher::findOrFail($id);
        $voucher->delete();

        return redirect()->route('vouchers')->with('success', `Đã xóa thành công mã giảm giá [ID: ${id}`);
    }
}
