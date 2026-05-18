<?php

use Illuminate\Support\Facades\Schedule;

// Generate weekly reports every day at midnight
Schedule::command('proofwork:weekly-reports')->dailyAt('00:00');
