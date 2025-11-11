<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function show($id)
    {
        try {
            $user = User::findOrFail($id);
            return response()->json($user);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function index()
    {
        $users = User::orderBy('id', 'DESC')->get();
        return response()->json($users);
    }

    public function create()
    {
        return response()->json(['message' => 'Create user']);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        User::create([
            'name'  => $request->name,
            'email' => $request->email,
            'google_id' => $request->google_id,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('users.index')->with('success', 'Thêm người dùng thành công');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name'  => 'required',
            'email' => 'required|email|unique:users,email,' . $id,
        ]);

        $user = User::findOrFail($id);

        $data = [
            'name'      => $request->name,
            'email'     => $request->email,
            'google_id' => $request->google_id,
        ];

        // Nếu user nhập mật khẩu mới thì update, còn không thì bỏ qua
        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }

        $user->update($data);

        return response()->json([
            'message' => 'Cập nhật user thành công',
            'user' => $user
        ]);
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Update Name
        $user->name = $request->name;

        // Upload Avatar nếu có file
        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $filename = time() . "_" . $file->getClientOriginalName();
            $file->storeAs('public/avatars', $filename);

            // TẠO URL ĐẦY ĐỦ (ABSOLUTE URL)
            $user->avatar = asset('storage/avatars/' . $filename);
        }

        $user->save();

        return response()->json([
            'message' => 'Cập nhật profile thành công',
            'user' => $user
        ]);
    }
    public function destroy($id)
    {
        User::destroy($id);
        return back()->with('success', 'Xóa thành công');
    }
}
