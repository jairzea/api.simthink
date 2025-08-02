<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InvestigationFolderItem extends Model
{
    use HasFactory, HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'folder_id',
        'investigation_id',
        'investigation_folder_investigation_id',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'investigation_folder_investigation_id' => 'integer',
        ];
    }

    public function investigationFolderInvestigation(): BelongsTo
    {
        return $this->belongsTo(InvestigationFolderInvestigation::class);
    }

    public function folder(): BelongsTo
    {
        return $this->belongsTo(\App\Models\InvestigationFolder::class);
    }

    public function investigation(): BelongsTo
    {
        return $this->belongsTo(Investigation::class);
    }
}
