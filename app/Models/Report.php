<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Report extends Model
{
    protected $fillable = [
        'user_id', 'project_id', 'client_id', 'title',
        'period_start', 'period_end', 'status', 'ai_summary',
        'share_token', 'share_enabled', 'shared_at', 'sent_at', 'view_count',
    ];

    protected $casts = [
        'period_start' => 'date',
        'period_end' => 'date',
        'share_enabled' => 'boolean',
        'shared_at' => 'datetime',
        'sent_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($report) {
            if (! $report->share_token) {
                // Use a long, high-entropy token for public sharing.
                $report->share_token = Str::random(64);
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function entries()
    {
        return $this->hasMany(ReportEntry::class)->orderBy('occurred_at');
    }

    public function shareUrl(): string
    {
        return url("/r/{$this->share_token}");
    }

    /**
     * Verification hash used in PDFs and the public report view.
     *
     * This intentionally covers the key fields that appear in a report:
     * - identifying metadata
     * - period bounds
     * - AI summary
     * - all entries with their visible attributes
     */
    public function verificationHash(): string
    {
        $this->loadMissing('entries');

        $entryPayload = $this->entries
            ->sortBy('id')
            ->map(function (ReportEntry $entry) {
                return [
                    'id' => $entry->id,
                    'title' => $entry->title,
                    'source' => $entry->source,
                    'type' => $entry->type,
                    'description' => $entry->description,
                    'source_url' => $entry->source_url,
                    'occurred_at' => optional($entry->occurred_at)->toIso8601String(),
                ];
            })
            ->values()
            ->all();

        $data = [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'project_id' => $this->project_id,
            'client_id' => $this->client_id,
            'title' => $this->title,
            'ai_summary' => $this->ai_summary,
            'period_start' => optional($this->period_start)->toDateString(),
            'period_end' => optional($this->period_end)->toDateString(),
            'created_at' => optional($this->created_at)->toIso8601String(),
            'entries' => $entryPayload,
        ];

        return hash('sha256', json_encode($data));
    }

    public function isDraft(): bool
    {
        return $this->status === 'draft';
    }

    public function isReady(): bool
    {
        return $this->status === 'ready';
    }

    public function isSent(): bool
    {
        return $this->status === 'sent';
    }

    public function periodLabel(): string
    {
        $start = $this->period_start instanceof \Illuminate\Support\Carbon
            ? $this->period_start
            : \Illuminate\Support\Carbon::parse($this->period_start);

        $end = $this->period_end instanceof \Illuminate\Support\Carbon
            ? $this->period_end
            : \Illuminate\Support\Carbon::parse($this->period_end);

        return $start->format('M d').' - '.$end->format('M d, Y');
    }

    public function entriesBySource(): array
    {
        return $this->entries->groupBy('source')->toArray();
    }
}
