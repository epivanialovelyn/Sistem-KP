@extends('layouts.app')

@section('content')
<div class="container">
<div class="mb-4">
        <div class="row align-items-center">
            <div class="col-12 col-md-6 text-center text-md-start mb-3 mb-md-0">
                <h2>Data Mahasiswa</h2>
            </div>

            <div class="col-12 col-md-6 text-center text-md-end">
                <div class="d-flex flex-column flex-md-row justify-content-md-end align-items-center gap-2">
                    <a href="{{ route('studentForm') }}" class="btn btn-success">Tambah Data Mahasiswa</a>
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
                        <th>NIM</th>
                        <th>Nama Mahasiswa</th>
                        <th>Username</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($students as $student)
                        <tr onclick="window.location.href='/students/{{ $student->id }}';">
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $student->nim }}</td>
                            <td>{{ $student->name }}</td>
                            <td>{{ $student->username }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center">Tidak ada data mahasiswa.</td>
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
