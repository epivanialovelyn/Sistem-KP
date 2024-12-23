@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="mb-4">
        <h2> 
            @if(auth()->user()->isAdmin())
                Detail Submission
            @else
                Formulir Pengajuan Kerja Praktik
            @endif
        </h2>

        @if(!auth()->user()->isAdmin())
            <p>Silakan lengkapi formulir berikut untuk mengajukan kerja praktik Anda.</p>
        @endif
        
    </div>

    <!-- Menampilkan pesan sukses -->
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- Formulir Pengajuan -->
    <form id="proposal-form" action="{{ route('submissions.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group mb-3">
            <label for="title">Judul Proposal</label>
            <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" value="{{ auth()->user() && auth()->user()->isAdmin() ? $submission->title : old('title') }}" required @if(auth()->user() && auth()->user()->isAdmin()) disabled @endif >
            @error('title')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group mb-3">
            <label for="description">Abstrak</label>
            <textarea name="abstract" id="abstract" class="form-control @error('abstract') is-invalid @enderror" rows="4" required @if(auth()->user() && auth()->user()->isAdmin()) disabled @endif>{{ auth()->user() && auth()->user()->isAdmin() ? $submission->abstract : old('abstract') }}</textarea>
            @error('abstract')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group mb-3">
            <label for="file">File Proposal</label>
            @if(auth()->user() && auth()->user()->isAdmin())
                <div>
                    <a href="{{ asset('storage/' . $submission->file_path) }}" target="_blank">Download File</a>
                </div>
            @else
                <input type="file" name="file" id="file" class="form-control @error('file') is-invalid @enderror" required>
                @error('file')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            @endif
            
        </div>
        
        @if(!auth()->user()->isAdmin())
            <button type="submit" class="btn btn-success" onclick="confirmSubmit(event)">Ajukan Proposal</button>
        @endif
        
    </form>
    @if(auth()->user()->isAdmin())
    <hr>
    <div class="card-footer">
        <!-- Tombol Approve atau Reject -->
        @if($submission->status === 'pending')         
            <form id="reject-form-{{ $submission->id }}" action="{{ route('submissions.reject', $submission->id) }}" method="POST" style="display:inline;">
                @csrf
                @method('PUT')
                <button onclick="confirmAction(event, 'reject', {{ $submission->id }})" type="submit" class="btn btn-outline-danger" style="width: 100px;">Reject</button>
            </form>
            <form id="approve-form-{{ $submission->id }}" action="{{ route('submissions.approve', $submission->id) }}" method="POST" style="display:inline;">
                @csrf
                @method('PUT')
                <button onclick="confirmAction(event, 'approve', {{ $submission->id }})" type="submit" class="btn btn-success" style="width: 100px;">Approve</button>
            </form>
        @endif
    </div>
    @endif
</div>
<script>
    function confirmSubmit(event) {
        event.preventDefault();

        const form = document.getElementById('proposal-form');

        // Validasi form
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        Swal.fire({
            title: 'Apakah Anda yakin ingin mengajukan proposal?',
            text: "Pastikan semua data telah diisi dengan benar.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Ajukan',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('proposal-form').submit(); 
            }
        });
    }

    function confirmAction(event, action, id) {
        event.preventDefault(); 

        let title = '';
        let text = '';
        if (action === 'approve') {
            title = 'Apakah Anda yakin ingin menyetujui submission ini?';
            text = 'Submission ini akan disetujui.';
        } else if (action === 'reject') {
            title = 'Apakah Anda yakin ingin menolak submission ini?';
            text = 'Submission ini akan ditolak.';
        }

        Swal.fire({
            title: title,
            text: text,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Lanjutkan',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Submit formulir sesuai dengan ID
                document.getElementById(`${action}-form-${id}`).submit();
            }
        });
    }
</script>

@endsection
