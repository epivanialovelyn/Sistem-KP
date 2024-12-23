<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\Submission;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubmissionController extends Controller
{
    public function __construct()
    {
        // Middleware untuk memastikan hanya admin yang bisa mengakses update status
        $this->middleware('admin')->only(['updateStatus']);
    }

    public function add()
    {
        return view('submissions.add');
    }

    public function index()
    {
        $user = auth()->user();

        $submissions = Submission::where('user_id', $user->id)->orderBy('created_at', 'desc')->get();
        $activeSubmission = Submission::where('user_id', $user->id)->whereIn('status', ['pending', 'approved'])->exists();

        $activeSchedule = Schedule::where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->first();
        if (!$activeSchedule) {
            $activeSchedule = Schedule::where('start_date', '>', now())->first();
        }

        return view('dashboards.dashboardMhs', compact('user', 'submissions', 'activeSubmission', 'activeSchedule'));
    }

    public function indexAdm()
    {
        $user = auth()->user();

        $submissions = Submission::orderByRaw("
            CASE 
                WHEN status = 'pending' THEN 1
                WHEN status = 'approved' THEN 2
                WHEN status = 'rejected' THEN 3
            END ASC
        ")->get();

        $totalPending = Submission::where('status', 'pending')->count();

        $totalMhs = User::where('role', 'student')->count();

        $activeSchedule = Schedule::where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->first();
        
        if (!$activeSchedule) {
            $activeSchedule = Schedule::where('start_date', '>', now())->first();
        }

        return view('dashboards.dashboardAdm', compact('user', 'submissions', 'totalPending', 'totalMhs' , 'activeSchedule'));
    }
    
    public function store(Request $request)
    {
        $user = Auth::user();

        
        $existing = Submission::where('user_id', $user->id)
            ->where('status', 'pending')
            ->first();

        if ($existing) {
            return redirect()->back()->with('error', 'Anda sudah memiliki pengajuan yang sedang diproses.');
        }

        // Cek apakah jadwal pengajuan valid
        $schedule = Schedule::whereDate('start_date', '<=', now())
            ->whereDate('end_date', '>=', now())
            ->first();

        if (!$schedule) {
            return redirect()->back()->with('error', 'Saat ini tidak ada jadwal pengajuan.');
        }

        $validatedData = $request->validate([
            'title' => 'required|string', 
            'abstract' => 'required|string',
            'file' => 'required|file'
        ]);
        $filePath = $request->file('file')->store('submissions', 'public');

        Submission::create([
            'user_id' => $user->id,
            'schedule_id' => $schedule->id,
            'title' => $validatedData['title'], 
            'abstract' => $validatedData['abstract'],
            'file_path' => $filePath,
        ]);
        
        return redirect()->route('dashboards.dashboardMhs')->with('success', 'Pengajuan Anda berhasil disimpan!');
    }

    public function downloadTemplate()
    {
        // Tentukan path ke file template
        $filePath = public_path('templates/draft_kp_template.docx');

        // Periksa apakah file ada
        if (file_exists($filePath)) {
            // Mengirimkan file untuk diunduh
            return response()->download($filePath);
        }

        // Jika file tidak ditemukan, kembalikan response error
        return redirect()->back()->with('error', 'Template tidak ditemukan.');
    }

    public function show($id)
    {
        $submission = Submission::findOrFail($id);
        return view('submissions.add', compact('submission'));
    }

    public function approve($id)
    {
        $submission = Submission::findOrFail($id);
        $submission->status = 'approved';
        $submission->save();
        
        return redirect()->route('dashboards.dashboardAdm', $submission->id)
                         ->with('aprrove success', 'Submission approved successfully.');
    }

    public function reject($id)
    {
        $submission = Submission::findOrFail($id);
        $submission->status = 'rejected';
        $submission->save();
        
        return redirect()->route('dashboards.dashboardAdm', $submission->id)
                         ->with('success', 'Submission rejected successfully.');
    }
}
