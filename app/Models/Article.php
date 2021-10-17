<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'info',
        'caption'
    ];

    public function attachments()
    {
        return $this->hasMany(Attachment::class);
    }

    public function getImagePathAttribute()
    {
        return 'articles/' . $this->attachments[0]->name;
    }

    public function getImagePathsAttribute()
    {
        $paths = [];
        foreach ($this->attachments as $attachement) {
            $paths[] = 'articles/' . $attachement->name;
        }
        return $paths;
    }

    public function getImageUrlAttribute()
    {
        if (config('filesystems.default') == 'gcs') {
            return Storage::temporaryUrl($this->attachments[0]->path, now()->addMinutes(5));
        }
        return Storage::url($this->image_path);
    }

    public function getImageUrlsAttribute()
    {
        $urls = [];

        if (config('filesystems.default') == 'gcs') {
            foreach ($this->attachments as $attachment) {
                $urls[] = Storage::temporaryUrl($attachment->path, now()->addMinutes(5));
            }
            return $urls;
        }

        foreach ($this->image_paths as $path) {
            $urls[] = Storage::url($path);
        }
        return $urls;
    }
}
