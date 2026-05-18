<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReportEntry extends Model
{
    protected $fillable = [
        'report_id', 'source', 'type', 'title', 'description',
        'source_url', 'source_id', 'occurred_at', 'metadata', 'sort_order',
    ];

    protected $casts = [
        'occurred_at' => 'datetime',
        'metadata'    => 'array',
    ];

    public function report()
    {
        return $this->belongsTo(Report::class);
    }

    public function sourceIcon(): string
    {
        return match($this->source) {
            'github'          => '⌥',
            'linear'          => '◈',
            'notion'          => '◎',
            'google_calendar' => '📅',
            'manual'          => '✏',
            default           => '•',
        };
    }
}
