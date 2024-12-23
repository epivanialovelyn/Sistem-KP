@extends('layouts.app')

@section('content')
<div class="container">
<div class="mb-4">
        <div class="row align-items-center">
            <div class="col-12 col-md-6 text-center text-md-start mb-3 mb-md-0">
                <h2>Data Jadwal Pengajuan</h2>
            </div>

            <div class="col-12 col-md-6 text-center text-md-end">
                <div class="d-flex flex-column flex-md-row justify-content-md-end align-items-center gap-2">
                    <a href="{{ route('scheduleForm') }}" class="btn btn-success">Tambah Jadwal Aktif</a>
                </div>
            </div>
        </div>
        
        
    </div>
    

    <!-- Tabel Submission -->
    <div class="card mt-4">
        <div class="card-header">
            <h4>List Data</h4>
        </div>
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal Mulai</th>
                        <th>Tanggal Berakhir</th>
                        <th class="text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($schedules as $schedule)
                    <tr onclick="window.location.href='{{ $schedule->end_date < now() ? '#' : '/schedules/' . $schedule->id }}';">
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $schedule->start_date->format('d M Y') }}</td>
                            <td>{{ $schedule->end_date->format('d M Y') }}</td>
                            <td class="w-25">
                                <div class="border rounded text-center text-white
                                @if ($schedule->start_date <= now() && $schedule->end_date >= now()) bg-success
                                @elseif ($schedule->start_date >= now()) bg-warning
                                @else bg-danger 
                                @endif"
                                >
                                @if ($schedule->start_date <= now() && $schedule->end_date >= now())
                                    Sedang Berlangsung
                                @elseif ($schedule->start_date >= now()) 
                                    Akan Datang
                                @else
                                    Tidak Aktif
                                @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center">Tidak ada data jadwal.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
<style>
    .table tbody tr:hover {
        background-color: #eef4ff;
        cursor: pointer;
    }
    .table td {
        vertical-align: middle;
    }
</style>

@if(session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: '{{ session("success") }}',
            text: '{{ session("success") }}',
            showConfirmButton: false,
            timer: 2000
        });
    </script>
@endif
@endsection
