<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function showScheduleForm($id = null)
    {
        $schedule = $id ? Schedule::find($id) : new Schedule();  // Jika $id ada, cari jadwal dengan id tersebut, jika tidak buat objek baru
        return view('schedules.form', compact('schedule')); 

    }

    public function index()
    {
        $schedules = Schedule::all()->sortByDesc(function ($schedule) {
            if ($schedule->start_date <= now() && $schedule->end_date >= now()) {
                // Sedang berlangsung
                return 2;
            } elseif ($schedule->start_date > now()) {
                // Akan datang
                return 1;
            }
            // Tidak aktif
            return 0;
        });

        return view('schedules.schedule', compact('schedules'));
    }

    public function show($id)
    {
        $schedule = Schedule::findOrFail($id);
        return view('schedules.form', compact('schedule'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'start_date' => 'required|date|before_or_equal:end_date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $conflictingSchedules = Schedule::where(function ($query) use ($validatedData) {
            $query->whereBetween('start_date', [$validatedData['start_date'], $validatedData['end_date']])
                  ->orWhereBetween('end_date', [$validatedData['start_date'], $validatedData['end_date']])
                  ->orWhere(function ($query) use ($validatedData) {
                      $query->where('start_date', '<=', $validatedData['start_date'])
                            ->where('end_date', '>=', $validatedData['end_date']);
                  });
        })->exists();
    
        if ($conflictingSchedules) {
            return redirect()->back()->with('error', 'Jadwal baru bertabrakan dengan jadwal yang sudah ada. Silakan pilih tanggal lain.');
        }

        try {
            Schedule::create($validatedData);

            return redirect()->route('schedules.schedule')->with('success', 'Jadwal berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
{
    $validatedData = $request->validate([
        'start_date' => 'required|date|before_or_equal:end_date',
        'end_date' => 'required|date|after_or_equal:start_date',
    ]);

    // Cek apakah jadwal baru bertabrakan dengan jadwal yang ada (kecuali jadwal yang sedang diperbarui)
    $conflictingSchedules = Schedule::where(function ($query) use ($validatedData) {
        $query->whereBetween('start_date', [$validatedData['start_date'], $validatedData['end_date']])
              ->orWhereBetween('end_date', [$validatedData['start_date'], $validatedData['end_date']])
              ->orWhere(function ($query) use ($validatedData) {
                  $query->where('start_date', '<=', $validatedData['start_date'])
                        ->where('end_date', '>=', $validatedData['end_date']);
              });
    })
    ->where('id', '!=', $id) // Mengecualikan jadwal yang sedang diperbarui
    ->exists();

    if ($conflictingSchedules) {
        return redirect()->back()->with('error', 'Jadwal baru bertabrakan dengan jadwal yang sudah ada. Silakan pilih tanggal lain.');
    }

    try {
        $schedule = Schedule::findOrFail($id);
        $schedule->update($validatedData);

        return redirect()->route('schedules.schedule')->with('success', 'Jadwal berhasil diperbarui!');
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
    }
}

}
