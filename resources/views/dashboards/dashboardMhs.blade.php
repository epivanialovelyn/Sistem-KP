@extends('layouts.app')

@section('content')
<div class="container py-2">
    <!-- Header -->
    <div class="mb-4">
        <div class="row align-items-center">
            <div class="col-12 col-md-6 text-center text-md-start mb-3 mb-md-0">
                <h2>Selamat Datang,</h2>
                <h2>{{ $user->name }}</h2>
            </div>

            <div class="col-12 col-md-6 text-center text-md-end">
                <div class="d-flex flex-column flex-md-row justify-content-md-end align-items-center gap-2">
                    <a href="{{ route('download.template') }}" class="btn btn-outline-success">Download Template Draft</a>
                    <a id="submitProposalBtn" class="btn btn-success">Ajukan Draft Proposal</a>
                </div>
            </div>
        </div>
        
        
    </div>

    <!-- Jadwal Pengajuan -->
    @if ($activeSchedule->start_date <= now())
    <div class="rounded p-3 d-flex justify-content-md-between align-items-center"  style="background-color: #40b509;">
        <div class="text-white">
            <div>Anda dapat mengajukan proposal saat ini.</div>
            <div>Waktu : <strong>{{ $activeSchedule->start_date->format('d M Y') }} - {{ $activeSchedule->end_date->format('d M Y') }}</strong> </div>
        </div>
        <div class="text-white">
            Status Pengajuan Terkini Anda:
            <div class="rounded bg-white text-center fw-bolder p-2 mt-2">
            @if ($submissions->isEmpty())
                <div class="text-black-50">Belum ada pengajuan</div>
            @else
                @php $latestSubmission = $submissions->first(); @endphp
                    <div class="{{ $latestSubmission->status == 'pending' ? 'text-warning' : ($latestSubmission->status == 'approved' ? 'text-success' : 'text-danger') }}">
                        {{ $latestSubmission->status == 'pending' ? 'Sedang Diproses' : ($latestSubmission->status == 'approved' ? 'Disetujui' : 'Ditolak') }}
                    </div>
            @endif
            </div>

            
        </div>
    </div>
    @else
    <div class="rounded p-3 d-flex justify-content-md-between align-items-center" style="background-color: #cc6f0c;">
        <div class="text-white">
            <div>Mohon maaf saat ini anda tidak dapat melakukan pengajuan proposal.</div>
            <div>Jadwal terdekat : <strong>{{ $activeSchedule->start_date->format('d M Y') }} - {{ $activeSchedule->end_date->format('d M Y') }}</strong> </div>
        </div>
        <div class="text-white">
            Status Pengajuan Terkini Anda:
            <div class="rounded bg-white text-center fw-bolder p-2 mt-2">
            @if ($submissions->isEmpty())
                <div style="color: #cc6f0c;">Belum ada pengajuan</div>
            @else
                @php $latestSubmission = $submissions->first(); @endphp
                <div class="text-info">
                    {{ $latestSubmission->status == 'pending' ? 'Sedang Diproses' : ($latestSubmission->status == 'approved' ? 'Disetujui' : 'Ditolak') }}
                </div>
            @endif
            </div>

            
        </div>
    </div>
    @endif

    <div class="card mt-4">
        <div class="card-header">
            <h4>Riwayat Pengajuan</h4>
        </div>
        <div class="card-body">
            <table class="table table-striped">
            <thead>
                <tr>
                    <th>Judul Proposal</th>
                    <th>Tanggal Pengajuan</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($submissions as $submission)
                    <tr>
                        <td>{{ $submission->title }}</td>
                        <td>{{ $submission->created_at->format('d M Y') }}</td>
                        <td>
                            @if ($submission->status == 'pending')
                                <span class="text-warning">Sedang Diproses</span>
                            @elseif ($submission->status == 'approved')
                                <span class="text-success">Disetujui</span>
                            @else
                                <span class="text-danger">Ditolak</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center">Belum ada pengajuan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div>
    
    
</div>
<style>
    .card {
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }
</style>
<script>
    document.getElementById('submitProposalBtn').addEventListener('click', function () {
        if(@json($activeSubmission)){
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Anda sudah memiliki pengajuan yang sedang diproses.',
            });
        }
        else if(@json($activeSchedule->start_date > now())){
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Belum ada jadwal pengajuan untuk saat ini.',
            });
        }
        else {
            window.location.href = "{{ route('submissions.add') }}";
        }
    });
</script>

@if(session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '{{ session("success") }}',
            showConfirmButton: false,
            timer: 2000
        });
    </script>
@endif

@endsection
