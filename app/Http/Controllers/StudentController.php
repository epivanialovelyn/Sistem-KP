<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class StudentController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function showStudentForm()
    {
        return view('students.form');
    }

    public function index()
    {
        $students = User::where('role', 'student')->orderBy("name", "desc")->get();

        return view('students.student', compact('students'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'username' => 'required',
            'nim' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

       try {
        // Coba untuk menyimpan data
        User::create([
            'name' => $request->name,
            'username' => $request->username,
            'nim' => $request->nim,
            'password' => Hash::make($request->password),
            'role' => 'student',
        ]);

        return redirect()->route('students.student')->with('success', 'Mahasiswa berhasil ditambahkan!');
        } catch (\Exception $e) {
            // Tangani exception jika terjadi error saat penyimpanan
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'username' => 'required',
            'nim' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = User::findOrFail($id); 

        // Update data
        $user->update([
            'name' => $request->name,
            'username' => $request->username,
            'nim' => $request->nim,
        ]);

        return redirect()->route('students.student')->with('success', 'Mahasiswa berhasil diperbarui!');
    }


    public function show($id)
    {
        $student = User::findOrFail($id);
        return view('students.form', compact('student'));
    }
}
