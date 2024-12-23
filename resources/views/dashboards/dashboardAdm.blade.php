@extends('layouts.app')

@section('content')
<div class="container">
<h1 class="gradient-text">Dashboard Admin</h1>

    <!-- Statistik Terkait Pengajuan -->
    <div class="row pt-3">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5>Total Mahasiswa</h5>
                    <p>{{ $totalMhs }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5>Pending Submissions</h5>
                    <p>{{ $totalPending }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5>Active Schedules</h5>
                    <p>{{ $activeSchedule->start_date->format('d M Y') }} s/d {{ $activeSchedule->end_date->format('d M Y') }} </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Submission -->
    <div class="card mt-4">
        <div class="card-header">
            <h4>List Pengajuan Proposal</h4>
        </div>
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Mahasiswa</th>
                        <th>Judul Proposal</th>
                        <th class="text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($submissions as $submission)
                        <tr onclick="window.location.href='/submissions/{{ $submission->id }}';">
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $submission->user->name }}</td>
                            <td>{{ $submission->title }}</td>
                            <td>
                                <div style="font-weight: 500;" class="border rounded text-center 
                                    @if ($submission->status == 'pending') bg-warning  text-white
                                    @elseif ($submission->status == 'approved') bg-success text-white
                                    @elseif ($submission->status == 'rejected') bg-danger text-white
                                    @endif">
                                    {{ ucfirst($submission->status) }}
                                </div>
                            </td>
                        </tr>
                    @endforeach
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
    .table tbody tr:hover {
        background-color: #eef4ff;
        cursor: pointer;
    }
    .table td {
        vertical-align: middle;
    }
    .gradient-text {
        background: linear-gradient(45deg, #1d2671, #c33764);
        -webkit-background-clip: text;
        color: transparent;
    }
</style>

@if(session('approve success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil Approve',
            text: '{{ session("approve success") }}',
            showConfirmButton: false,
            timer: 2000
        });
    </script>
@elseif(session('reject success'))
<script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil Reject',
            text: '{{ session("reject success") }}',
            showConfirmButton: false,
            timer: 2000
        });
    </script>
@endif
@endsection
