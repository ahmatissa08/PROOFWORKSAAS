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
        'period_start'  => 'date',
        'period_end'    => 'date',
        'share_enabled' => 'boolean',
        'shared_at'     => 'datetime',
        'sent_at'       => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($report) {
            if (!$report->share_token) {
                $report->share_token = Str::random(32);
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
        return $this->period_start->format('M d') . ' - ' . $this->period_end->format('M d, Y');
    }

    public function entriesBySource(): array
    {
        return $this->entries->groupBy('source')->toArray();
    }
}
