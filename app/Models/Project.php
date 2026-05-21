<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'client_id', 'name', 'description', 'color',
        'status', 'auto_report', 'report_frequency', 'report_day', 'auto_send',
    ];

    protected $casts = [
        'auto_report' => 'boolean',
        'auto_send' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function reports()
    {
        return $this->hasMany(Report::class)->orderByDesc('period_end');
    }

    public function integrations()
    {
        return $this->hasMany(Integration::class);
    }

    public function latestReport()
    {
        return $this->hasOne(Report::class)->latestOfMany('period_end');
    }

    public function initials(): string
    {
        return strtoupper(substr($this->name, 0, 2));
    }
}
