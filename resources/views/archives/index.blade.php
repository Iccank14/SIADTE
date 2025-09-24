@extends('layouts.app')

@section('title', 'Daftar Arsip')
@section('page-title', 'Manajemen Arsip')

@section('page-actions')
    <a href="{{ route('archives.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-1"></i> Tambah Arsip
    </a>
@endsection

@section('content')
<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2 stat-card">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Total Arsip
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $archives->total() }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-archive fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Search and Filter -->
<div class="card mb-4">
    <div class="card-header">
        <h5 class="card-title mb-0">
            <i class="fas fa-search me-2"></i>Pencarian & Filter
        </h5>
    </div>
    <div class="card-body">
        <form action="{{ route('archives.index') }}" method="GET">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="search" class="form-label">Cari Arsip</label>
                        <input type="text" name="search" class="form-control" 
                               placeholder="Cari berdasarkan judul, nomor dokumen, atau deskripsi..." 
                               value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="category" class="form-label">Kategori</label>
                        <select name="category" class="form-control">
                            <option value="">Semua Kategori</option>
                            @foreach($categories as $category)
                                <option value="{{ $category }}" 
                                    {{ request('category') == $category ? 'selected' : '' }}>
                                    {{ $category }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="mb-3">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search me-1"></i> Cari
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Archives Table -->
<div class="card">
    <div class="card-body">
        @if($archives->count() > 0)
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>No. Dokumen</th>
                            <th>Judul</th>
                            <th>Kategori</th>
                            <th>Tanggal</th>
                            <th>File</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($archives as $archive)
                        <tr>
                            <td><strong>{{ $archive->document_number }}</strong></td>
                            <td>
                                <div class="fw-bold">{{ Str::limit($archive->title, 40) }}</div>
                                <small class="text-muted">{{ Str::limit($archive->description, 50) }}</small>
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $archive->category }}</span>
                            </td>
                            <td>{{ $archive->archive_date->format('d/m/Y') }}</td>
                            <td>
                                @php
                                    $icon = 'fa-file';
                                    $color = 'text-secondary';
                                    if (in_array($archive->file_extension, ['pdf'])) {
                                        $icon = 'fa-file-pdf';
                                        $color = 'text-danger';
                                    } elseif (in_array($archive->file_extension, ['doc', 'docx'])) {
                                        $icon = 'fa-file-word';
                                        $color = 'text-primary';
                                    } elseif (in_array($archive->file_extension, ['xls', 'xlsx'])) {
                                        $icon = 'fa-file-excel';
                                        $color = 'text-success';
                                    } elseif (in_array($archive->file_extension, ['jpg', 'jpeg', 'png'])) {
                                        $icon = 'fa-file-image';
                                        $color = 'text-warning';
                                    }
                                @endphp
                                <i class="fas {{ $icon }} {{ $color }} file-icon"></i>
                                <small>{{ Str::upper($archive->file_extension) }}</small>
                                <br>
                                <small class="text-muted">{{ $archive->file_size }}</small>
                            </td>
                            <td class="table-actions">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('archives.show', $archive) }}" 
                                       class="btn btn-info" title="Lihat Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('archives.edit', $archive) }}" 
                                       class="btn btn-warning" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="{{ route('archives.download', $archive) }}" 
                                       class="btn btn-success" title="Download">
                                        <i class="fas fa-download"></i>
                                    </a>
                                    <form action="{{ route('archives.destroy', $archive) }}" 
                                          method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" 
                                                title="Hapus"
                                                onclick="return confirm('Yakin ingin menghapus arsip ini?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="text-muted">
                    Menampilkan {{ $archives->firstItem() }} - {{ $archives->lastItem() }} 
                    dari {{ $archives->total() }} arsip
                </div>
                {{ $archives->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                <h4 class="text-muted">Tidak ada arsip ditemukan</h4>
                <p class="text-muted">Mulai dengan menambahkan arsip pertama Anda.</p>
                <a href="{{ route('archives.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i> Tambah Arsip Pertama
                </a>
            </div>
        @endif
    </div>
</div>
@endsection