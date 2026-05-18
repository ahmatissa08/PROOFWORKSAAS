<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Integration extends Model
{
    protected $fillable = [
        'user_id', 'project_id', 'provider', 'provider_account_id',
        'provider_account_name', 'resource_id', 'resource_name',
        'access_token', 'refresh_token', 'token_expires_at', 'settings', 'active',
    ];

    protected $hidden = ['access_token', 'refresh_token'];

    protected $casts = [
        'settings' => 'array',
        'active' => 'boolean',
        'token_expires_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function providerLabel(): string
    {
        return match ($this->provider) {
            'github' => 'GitHub',
            'linear' => 'Linear',
            'notion' => 'Notion',
            'google_calendar' => 'Google Calendar',
            'jira' => 'Jira',
            default => ucfirst($this->provider),
        };
    }

    public function providerIcon(): string
    {
        return match ($this->provider) {
            'github' => 'G',
            'linear' => 'L',
            'notion' => 'N',
            'google_calendar' => 'C',
            'jira' => 'J',
            default => '*',
        };
    }
}
