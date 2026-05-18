<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Cashier\Billable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, Billable;

    protected $fillable = [
        'name', 'email', 'password', 'avatar',
        'plan', 'stripe_id', 'pm_type', 'pm_last_four',
        'trial_ends_at', 'timezone', 'notification_preferences',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at'         => 'datetime',
        'trial_ends_at'             => 'datetime',
        'password'                  => 'hashed',
        'notification_preferences'  => 'array',
    ];

    // ── Relations
    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    public function clients()
    {
        return $this->hasMany(Client::class);
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    public function integrations()
    {
        return $this->hasMany(Integration::class);
    }

    public function socialAccounts()
    {
        return $this->hasMany(SocialAccount::class);
    }

    // ── Plan limits
    public function planLimits(): array
    {
        return match($this->plan) {
            'pro'    => ['projects' => 999, 'clients' => 999, 'integrations' => 6, 'auto_send' => true],
            'agency' => ['projects' => 999, 'clients' => 999, 'integrations' => 6, 'auto_send' => true, 'white_label' => true],
            default  => ['projects' => 1,   'clients' => 1,   'integrations' => 2, 'auto_send' => false],
        };
    }

    public function canCreateProject(): bool
    {
        $limit = $this->planLimits()['projects'];
        return $this->projects()->count() < $limit;
    }

    public function canCreateClient(): bool
    {
        $limit = $this->planLimits()['clients'];
        return $this->clients()->count() < $limit;
    }

    public function isPro(): bool
    {
        return in_array($this->plan, ['pro', 'agency']);
    }

    public function isAgency(): bool
    {
        return $this->plan === 'agency';
    }

    public function onTrial(): bool
    {
        return $this->trial_ends_at && $this->trial_ends_at->isFuture();
    }

    public function initials(): string
    {
        $parts = explode(' ', $this->name);
        return strtoupper(substr($parts[0], 0, 1) . (isset($parts[1]) ? substr($parts[1], 0, 1) : ''));
    }
}
