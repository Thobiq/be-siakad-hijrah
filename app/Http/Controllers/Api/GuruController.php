<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Exception;

class GuruController extends Controller
{
    public function index()
    {
        $gurus = Guru::with('user')->get();
        
        $mappedData = $gurus->map(function($guru) {
            return [
                'id' => $guru->id,
                'nama' => $guru->nama,
                'nomor_induk' => $guru->nomor_induk,
                'username' => $guru->user ? $guru->user->username : null,
                'email' => $guru->user ? $guru->user->email : null,
                'user_id' => $guru->user_id
            ];
        });

        return response()->json([
            'status' => true,
            'data' => $mappedData
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nomor_induk' => 'required|string|unique:gurus',
            'username' => 'required|string|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6',
        ]);

        DB::beginTransaction();

        try {
            $user = User::create([
                'name' => $request->nama,
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'guru'
            ]);

            $guru = new Guru();
            $guru->user_id = $user->id;
            $guru->nama = $request->nama;
            $guru->nomor_induk = $request->nomor_induk;
            $guru->save();

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Data guru dan akun berhasil dibuat.',
                'data' => [
                    'id' => $guru->id,
                    'nama' => $guru->nama,
                    'nomor_induk' => $guru->nomor_induk,
                    'username' => $user->username,
                    'email' => $user->email
                ]
            ], 201);

        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Gagal membuat data guru: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $guru = Guru::find($id);

        if (!$guru) {
            return response()->json(['status' => false, 'message' => 'Data guru tidak ditemukan.'], 404);
        }

        $request->validate([
            'nama' => 'required|string|max:255',
            'nomor_induk' => 'required|string|unique:gurus,nomor_induk,' . $guru->id,
            'username' => 'required|string|unique:users,username,' . $guru->user_id,
            'email' => 'required|email|unique:users,email,' . $guru->user_id,
            'password' => 'nullable|string|min:6',
        ]);

        DB::beginTransaction();

        try {
            $user = User::find($guru->user_id);
            if ($user) {
                $user->name = $request->nama;
                $user->username = $request->username;
                $user->email = $request->email;
                if ($request->filled('password')) {
                    $user->password = Hash::make($request->password);
                }
                $user->save();
            }

            $guru->nama = $request->nama;
            $guru->nomor_induk = $request->nomor_induk;
            $guru->save();

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Data guru berhasil diperbarui.',
                'data' => [
                    'id' => $guru->id,
                    'nama' => $guru->nama,
                    'nomor_induk' => $guru->nomor_induk,
                    'username' => $user ? $user->username : null,
                    'email' => $user ? $user->email : null
                ]
            ]);

        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Gagal memperbarui data guru: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        $guru = Guru::find($id);

        if (!$guru) {
            return response()->json(['status' => false, 'message' => 'Data guru tidak ditemukan.'], 404);
        }

        DB::beginTransaction();

        try {
            $userId = $guru->user_id;
            $guru->delete();
            
            if ($userId) {
                User::where('id', $userId)->delete();
            }

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Data guru dan akun terkait berhasil dihapus.'
            ]);

        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Gagal menghapus data guru: ' . $e->getMessage()
            ], 500);
        }
    }
}
