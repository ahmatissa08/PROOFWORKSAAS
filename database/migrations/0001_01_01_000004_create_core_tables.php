<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ── Clients
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('company')->nullable();
            $table->string('avatar_color', 7)->default('#e8a325');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // ── Projects
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('client_id')->nullable()->constrained()->onDelete('set null');
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('color', 7)->default('#e8a325');
            $table->enum('status', ['active', 'paused', 'completed'])->default('active');
            $table->boolean('auto_report')->default(true);
            $table->enum('report_frequency', ['weekly', 'biweekly', 'monthly'])->default('weekly');
            $table->string('report_day')->default('friday'); // day of week
            $table->boolean('auto_send')->default(false);
            $table->timestamps();
        });

        // ── Integrations
        Schema::create('integrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('project_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('provider'); // github, linear, notion, google_calendar, jira
            $table->string('provider_account_id')->nullable();
            $table->string('provider_account_name')->nullable();
            $table->string('resource_id')->nullable(); // repo id, workspace id...
            $table->string('resource_name')->nullable();
            $table->text('access_token')->nullable();
            $table->text('refresh_token')->nullable();
            $table->timestamp('token_expires_at')->nullable();
            $table->json('settings')->nullable(); // extra config per integration
            $table->boolean('active')->default(true);
            $table->timestamps();

            $table->index(['user_id', 'provider']);
        });

        // ── Reports
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->foreignId('client_id')->nullable()->constrained()->onDelete('set null');
            $table->string('title');
            $table->date('period_start');
            $table->date('period_end');
            $table->enum('status', ['draft', 'ready', 'sent'])->default('draft');
            $table->text('ai_summary')->nullable();
            $table->string('share_token', 64)->unique()->nullable();
            $table->boolean('share_enabled')->default(true);
            $table->timestamp('shared_at')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->integer('view_count')->default(0);
            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index('share_token');
        });

        // ── Report entries
        Schema::create('report_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_id')->constrained()->onDelete('cascade');
            $table->string('source'); // github, linear, notion, calendar, manual
            $table->string('type'); // commit, task, meeting, decision, pr, etc.
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('source_url')->nullable();
            $table->string('source_id')->nullable();
            $table->timestamp('occurred_at')->nullable();
            $table->json('metadata')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('report_entries');
        Schema::dropIfExists('reports');
        Schema::dropIfExists('integrations');
        Schema::dropIfExists('projects');
        Schema::dropIfExists('clients');
    }
};
