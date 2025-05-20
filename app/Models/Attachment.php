<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attachment extends Model
{
    protected $fillable = ['path', 'mime_type', 'document_id'];

    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class);
    }

    // URL publique pour l'affichage
    public function getPublicUrlAttribute(): string
    {
        return asset("storage/{$this->path}");
    }
}
