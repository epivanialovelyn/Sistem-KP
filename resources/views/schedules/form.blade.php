@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="mb-4">
        <h2>Formulir Data Jadwal Pengajuan KP</h2>
        <p>Silakan lengkapi formulir berikut</p>
    </div>

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <!-- Formulir Pengajuan -->
    <form action="{{ $schedule->exists ? route('schedules.update', $schedule->id) : route('schedules.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @if($schedule->exists)
            @method('PUT')
        @endif
        <div class="form-group mb-3">
            <label for="start_date">Tanggal Awal</label>
            <input type="date" name="start_date" id="start_date" class="form-control @error('start_date') is-invalid @enderror"  value="{{ old('start_date', $schedule->start_date ? $schedule->start_date->format('Y-m-d') : '') }}" required>
            @error('start_date')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group mb-3">
            <label for="end_date">Tanggal Akhir</label>
            <input type="date" name="end_date" id="end_date" class="form-control @error('end_date') is-invalid @enderror" value="{{ old('end_date', $schedule->end_date ? $schedule->end_date->format('Y-m-d') : '') }}" required>
            @error('end_date')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        
        
        <button type="submit" class="btn btn-success">
            {{ $schedule->exists ? 'Update Data' : 'Tambah Data' }}
        </button>
        
    </form>
</div>
@endsection
