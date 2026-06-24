<?php

namespace App\Models\Management;

use App\Services\SecureFileService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;


class Document extends Model
{
    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
        });
    }

    public function showFile(): StreamedResponse
    {
        return (new SecureFileService)->streamDecryptedFile(
            $this->path,
            $this->disk ?? 'local',
            $this->mime_type ?? 'image/jpeg'
        )->setCache(['max_age' => 86400, 'public' => true]);
    }
}
