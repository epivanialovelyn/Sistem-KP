@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="mb-4">
        <h2>Formulir Penambahan Mahasiswa</h2>
        <p>Silakan lengkapi formulir berikut</p>
    </div>

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <!-- Formulir Pengajuan -->
    <form action="{{ isset($student) ? route('students.update', $student->id) : route('students.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @if(isset($student))
            @method('PUT')
        @endif
        <div class="form-group mb-3">
            <label for="nim">NIM</label>
            <input type="text" name="nim" id="nim" class="form-control @error('nim') is-invalid @enderror" required value="{{ $student->nim ?? old('nim')}}">
            @error('nim')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group mb-3">
            <label for="name">Nama Mahasiswa</label>
            <input name="name" id="name" class="form-control @error('name') is-invalid @enderror" required value="{{ $student->name ?? old('name')}}">
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group mb-3">
            <label for="username">Username Mahasiswa</label>
            <input name="username" id="username" class="form-control @error('username') is-invalid @enderror" required value="{{ $student->username ?? old('username')}}">
            @error('username')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        @if( !isset($student) )
        <div class="form-group mb-3">
            <label for="password">Password</label>
            <input name="password" id="password" class="form-control @error('password') is-invalid @enderror" required value="{{ $student->password ?? old('password')}}">
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        @endif
        
        <button type="submit" class="btn btn-success">
            {{ isset($student) ? 'Update Data' : 'Tambah Data' }}
        </button>
        
    </form>
</div>
@endsection
