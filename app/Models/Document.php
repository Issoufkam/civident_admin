<?php

namespace App\Models;

use App\Enums\DocumentStatus;
use App\Enums\DocumentType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Document extends Model
{
    protected $fillable = [
        'type',
        'status',
        'registry_number',
        'registry_page',
        'registry_volume',
        'metadata',
        'justificatif_path',
        'user_id',
        'commune_id',
        'agent_id'
    ];

    protected $casts = [
        'type' => DocumentType::class,
        'status' => DocumentStatus::class,
        'metadata' => 'array'
    ];

    // Citoyen demandeur
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Commune concernée
    public function commune(): BelongsTo
    {
        return $this->belongsTo(Commune::class);
    }

    // Agent traiteur
    public function agent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    // Pièces jointes
    public function attachments(): HasMany
    {
        return $this->hasMany(Attachment::class);
    }
}
