<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'name', 'email', 'company', 'avatar_color', 'notes',
    ];

    public function user()     { return $this->belongsTo(User::class); }
    public function projects() { return $this->hasMany(Project::class); }
    public function reports()  { return $this->hasMany(Report::class); }

    public function initials(): string
    {
        $parts = explode(' ', $this->name);
        return strtoupper(substr($parts[0], 0, 1) . (isset($parts[1]) ? substr($parts[1], 0, 1) : ''));
    }
}
