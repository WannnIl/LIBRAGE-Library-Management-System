<?php
// app/Http/Controllers/ProfileController.php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        return view('profile.show', compact('user'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . Auth::id(),
            'alamat' => 'required|string|max:255',
            'current_password' => 'nullable|required_with:password',
            'password' => 'nullable|min:8|confirmed',
        ]);

        $user = User::find(Auth::id());
        
        $userData = [
            'nama' => $request->nama,
            'email' => $request->email,
            'alamat' => $request->alamat,
        ];

        if ($request->filled('current_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Password saat ini tidak sesuai']);
            }
            $userData['password'] = Hash::make($request->password);
        }

        $user->update($userData);

        return redirect()->route('profile.show')->with('success', 'Profil berhasil diperbarui');
    }
}