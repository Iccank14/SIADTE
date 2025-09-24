@extends('layouts.app')

@section('title', 'Tambah Arsip')
@section('page-title', 'Tambah Arsip Baru')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">
            <i class="fas fa-plus me-2"></i>Form Tambah Arsip
        </h5>
    </div>
    <div class="card-body">
        <form action="{{ route('archives.store') }}" method="POST" enctype="multipart/form-data" id="archiveForm">
            @csrf

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="document_number" class="form-label">No. Dokumen <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('document_number') is-invalid @enderror" 
                               id="document_number" name="document_number" 
                               value="{{ old('document_number') }}" required
                               placeholder="Contoh: DOC-001">
                        @error('document_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="archive_date" class="form-label">Tanggal Arsip <span class="text-danger">*</span></label>
                        <input type="date" class="form-control @error('archive_date') is-invalid @enderror" 
                               id="archive_date" name="archive_date" 
                               value="{{ old('archive_date', date('Y-m-d')) }}" required>
                        @error('archive_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label for="title" class="form-label">Judul Dokumen <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('title') is-invalid @enderror" 
                       id="title" name="title" value="{{ old('title') }}" required
                       placeholder="Masukkan judul dokumen">
                @error('title')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Deskripsi</label>
                <textarea class="form-control @error('description') is-invalid @enderror" 
                          id="description" name="description" rows="3"
                          placeholder="Masukkan deskripsi dokumen (opsional)">{{ old('description') }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="category" class="form-label">Kategori <span class="text-danger">*</span></label>
                        <select class="form-control @error('category') is-invalid @enderror" 
                                id="category" name="category" required>
                            <option value="">Pilih Kategori</option>
                            @foreach($categories as $category)
                                <option value="{{ $category }}" 
                                    {{ old('category') == $category ? 'selected' : '' }}>
                                    {{ $category }}
                                </option>
                            @endforeach
                        </select>
                        @error('category')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="file" class="form-label">File Dokumen <span class="text-danger">*</span></label>
                        <input type="file" class="form-control @error('file') is-invalid @enderror" 
                               id="file" name="file" required
                               accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png">
                        <div class="form-text">
                            Format yang didukung: PDF, DOC, DOCX, XLS, XLSX, JPG, JPEG, PNG (Max: 5MB)
                        </div>
                        @error('file')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center">
                <a href="{{ route('archives.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Kembali ke Daftar
                </a>
                <button type="submit" class="btn btn-primary" id="submitBtn">
                    <i class="fas fa-save me-1"></i> Simpan Arsip
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>