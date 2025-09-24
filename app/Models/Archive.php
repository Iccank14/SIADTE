<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Archive extends Model
{
    use HasFactory;

    protected $fillable = [
        'document_number',
        'title',
        'description',
        'category',
        'file_path',
        'archive_date',
        'user_id'
    ];

    protected $casts = [
        'archive_date' => 'date'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getFileUrlAttribute()
    {
        return asset('storage/' . $this->file_path);
    }

    public function getFileExtensionAttribute()
    {
        return pathinfo($this->file_path, PATHINFO_EXTENSION);
    }

    public function getFileSizeAttribute()
    {
        if (file_exists(storage_path('app/public/' . $this->file_path))) {
            $size = filesize(storage_path('app/public/' . $this->file_path));
            return $this->formatBytes($size);
        }
        return '0 KB';
    }

    private function formatBytes($size, $precision = 2)
    {
        if ($size > 0) {
            $base = log($size) / log(1024);
            $suffixes = array(' bytes', ' KB', ' MB', ' GB', ' TB');
            return round(pow(1024, $base - floor($base)), $precision) . $suffixes[floor($base)];
        }
        return $size;
    }
}