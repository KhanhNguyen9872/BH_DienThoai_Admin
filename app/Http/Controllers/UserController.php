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

    public function store(Request $request)
{
    // Transform the addresses input.
    // Expected flat input (if any):
    // [
    //   { "name": "a" },
    //   { "address": "b" },
    //   { "phone": "1" },
    //   { "name": "c" },
    //   { "address": "d" },
    //   { "phone": "2" }
    // We want to group them as:
    // [
    //   [ "name" => "a", "address" => "b", "phone" => "1" ],
    //   [ "name" => "c", "address" => "d", "phone" => "2" ]
    // ]
    $addressesInput = $request->input('addresses', []);
    $groupedAddresses = [];
    if (is_array($addressesInput)) {
        $chunks = array_chunk($addressesInput, 3);
        foreach ($chunks as $chunk) {
            $combined = [];
            foreach ($chunk as $item) {
                $combined = array_merge($combined, $item);
            }
            // Only include if at least one field is non-empty.
            if (
                !empty(trim($combined['name'] ?? '')) ||
                !empty(trim($combined['address'] ?? '')) ||
                !empty(trim($combined['phone'] ?? ''))
            ) {
                $groupedAddresses[] = $combined;
            }
        }
    }
    // Re-index the grouped array and merge it back into the request.
    $request->merge(['addresses' => array_values($groupedAddresses)]);

    // Validate the input, including addresses.
    $validated = $request->validate([
        'first_name'          => 'required|string|max:255',
        'last_name'           => 'required|string|max:255',
        'email'               => 'required|email|unique:user,email',
        'addresses'           => 'nullable|array',
        'addresses.*.name'    => 'required_with:addresses|string|max:255',
        'addresses.*.address' => 'required_with:addresses|string|max:255',
        'addresses.*.phone'   => 'required_with:addresses|string|max:20',
    ]);

    // Create the user.
    $user = UserInfo::create([
        'first_name' => $validated['first_name'],
        'last_name'  => $validated['last_name'],
        'email'      => $validated['email'],
    ]);

    // Create related addresses if provided.
    if (!empty($validated['addresses'])) {
        foreach ($validated['addresses'] as $addressData) {
            $user->addresses()->create([
                'full_name' => $addressData['name'],
                'address'   => $addressData['address'],
                'phone'     => $addressData['phone'],
            ]);
        }
    }

    return redirect()->route('users')->with('success', 'Người dùng đã được tạo thành công.');
}

    public function edit($id)
{
    // Load the user along with their addresses.
    $user = UserInfo::with('addresses')->findOrFail($id);
    return view('users.edit', compact('user'));
}


public function update(Request $request, $id)
{
    // Retrieve the user.
    $user = UserInfo::findOrFail($id);

    // Transform the addresses input.
    // Expected input (flat array):
    // [
    //   { "name": "a" },
    //   { "address": "b" },
    //   { "phone": "1" },
    //   { "name": "c" },
    //   { "address": "d" },
    //   { "phone": "2" }
    // ]
    // Desired output:
    // [
    //   [ "name" => "a", "address" => "b", "phone" => "1" ],
    //   [ "name" => "c", "address" => "d", "phone" => "2" ]
    $addressesInput = $request->input('addresses', []);
    $groupedAddresses = [];
    if (is_array($addressesInput)) {
        // Chunk every 3 items.
        $chunks = array_chunk($addressesInput, 3);
        foreach ($chunks as $chunk) {
            $combined = [];
            foreach ($chunk as $item) {
                $combined = array_merge($combined, $item);
            }
            // Only include if at least one field is non-empty.
            if (
                !empty(trim($combined['name'] ?? '')) ||
                !empty(trim($combined['address'] ?? '')) ||
                !empty(trim($combined['phone'] ?? ''))
            ) {
                $groupedAddresses[] = $combined;
            }
        }
    }
    // Replace the addresses input with the grouped addresses.
    $request->merge(['addresses' => $groupedAddresses]);


    // Validate the request.
    $validated = $request->validate([
        'first_name'            => 'required|string|max:255',
        'last_name'             => 'required|string|max:255',
        'email'                 => 'required|email|unique:user,email,' . $user->id,
        'addresses'             => 'nullable|array',
        'addresses.*.name'      => 'required_with:addresses|string|max:255',
        'addresses.*.address'   => 'required_with:addresses|string|max:255',
        'addresses.*.phone'     => 'required_with:addresses|string|max:20',
    ]);

    // Update the basic user information.
    $user->update([
        'first_name' => $validated['first_name'],
        'last_name'  => $validated['last_name'],
        'email'      => $validated['email'],
    ]);

    // Remove all existing addresses.
    $user->addresses()->delete();

    // Create new addresses if provided.
    if (!empty($validated['addresses'])) {
        foreach ($validated['addresses'] as $addressData) {
            $user->addresses()->create([
                'full_name' => $addressData['name'],
                'address'   => $addressData['address'],
                'phone'     => $addressData['phone'],
            ]);
        }
    }

    return redirect()->route('users')->with('success', 'Người dùng đã được cập nhật thành công.');
}


    public function delete($id)
    {
        try {
            $user = UserInfo::findOrFail($id);
            
            // Delete all addresses related to the user.
            $user->addresses()->delete();
            
            // Now, attempt to delete the user.
            $user->delete();
            
            return redirect()->route('users')->with('success', 'Người dùng đã được xóa thành công.');
        } catch (\Illuminate\Database\QueryException $ex) {
            // If deletion fails due to a foreign key constraint (other than addresses)
            if ($ex->getCode() == '23000') {
                return redirect()->route('users')->with('error', 'Người dùng này đang còn tồn tại ở những bảng khác.');
            }
            return redirect()->route('users')->with('error', 'Có lỗi xảy ra khi xóa người dùng.');
        }
    }

}
