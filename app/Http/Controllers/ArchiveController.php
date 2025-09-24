<?php

namespace App\Http\Controllers;

use App\Models\Archive;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ArchiveController extends Controller
{
    public function index(Request $request)
    {
        $query = Archive::with('user')->latest();
        
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('document_number', 'like', "%{$search}%")
                  ->orWhere('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%");
            });
        }

        if ($request->has('category') && $request->category != '') {
            $query->where('category', $request->category);
        }

        $archives = $query->paginate(10);
        $categories = Archive::distinct()->pluck('category');
        
        return view('archives.index', compact('archives', 'categories'));
    }

    public function create()
    {
        $categories = ['Surat', 'Laporan', 'Dokumen', 'Sertifikat', 'Ijazah', 'Kontrak', 'Lainnya'];
        return view('archives.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'document_number' => 'required|unique:archives',
            'title' => 'required|max:255',
            'description' => 'nullable',
            'category' => 'required',
            'file' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png|max:5120',
            'archive_date' => 'required|date'
        ]);

        try {
            $file = $request->file('file');
            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $filename = Str::slug($originalName) . '_' . time() . '.' . $extension;
            $filePath = $file->storeAs('archives', $filename, 'public');

            Archive::create([
                'document_number' => $request->document_number,
                'title' => $request->title,
                'description' => $request->description,
                'category' => $request->category,
                'file_path' => $filePath,
                'archive_date' => $request->archive_date,
                'user_id' => auth()->check() ? auth()->id() : 1
            ]);

            return redirect()->route('archives.index')
                ->with('success', 'Arsip berhasil ditambahkan.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show(Archive $archive)
    {
        return view('archives.show', compact('archive'));
    }

    public function edit(Archive $archive)
    {
        $categories = ['Surat', 'Laporan', 'Dokumen', 'Sertifikat', 'Ijazah', 'Kontrak', 'Lainnya'];
        return view('archives.edit', compact('archive', 'categories'));
    }

    public function update(Request $request, Archive $archive)
    {
        $request->validate([
            'document_number' => 'required|unique:archives,document_number,' . $archive->id,
            'title' => 'required|max:255',
            'description' => 'nullable',
            'category' => 'required',
            'file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png|max:5120',
            'archive_date' => 'required|date'
        ]);

        try {
            $data = $request->only(['document_number', 'title', 'description', 'category', 'archive_date']);

            if ($request->hasFile('file')) {
                // Hapus file lama
                Storage::disk('public')->delete($archive->file_path);
                
                $file = $request->file('file');
                $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $extension = $file->getClientOriginalExtension();
                $filename = Str::slug($originalName) . '_' . time() . '.' . $extension;
                $data['file_path'] = $file->storeAs('archives', $filename, 'public');
            }

            $archive->update($data);

            return redirect()->route('archives.index')
                ->with('success', 'Arsip berhasil diperbarui.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(Archive $archive)
    {
        try {
            Storage::disk('public')->delete($archive->file_path);
            $archive->delete();

            return redirect()->route('archives.index')
                ->with('success', 'Arsip berhasil dihapus.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function download(Archive $archive)
    {
        if (!Storage::disk('public')->exists($archive->file_path)) {
            return redirect()->back()
                ->with('error', 'File tidak ditemukan.');
        }

        return Storage::disk('public')->download($archive->file_path);
    }

    public function preview(Archive $archive)
    {
        if (!Storage::disk('public')->exists($archive->file_path)) {
            return redirect()->back()
                ->with('error', 'File tidak ditemukan.');
        }

        $path = Storage::disk('public')->path($archive->file_path);
        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));

        if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif'])) {
            return response()->file($path);
        } else {
            return Storage::disk('public')->download($archive->file_path);
        }
    }
}