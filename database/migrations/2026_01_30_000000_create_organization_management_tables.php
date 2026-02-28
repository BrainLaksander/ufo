<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // TABLE: ORGANIZATIONS
        Schema::create('organizations', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('shortname')->unique();
            $table->text('description')->nullable();
            $table->text('vision')->nullable();
            $table->text('mission')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('logo')->nullable();
            $table->string('banner')->nullable();
            $table->string('instagram')->nullable();
            $table->string('line')->nullable();
            $table->enum('profile_status', ['complete', 'incomplete'])->default('incomplete');
            $table->enum('status', ['active', 'inactive', 'suspended'])->default('active');
            $table->timestamps();
        });

        // TABLE: MEMBERS
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained('organizations')->onDelete('cascade');
            $table->string('name');
            $table->string('nim')->unique();
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->string('faculty');
            $table->string('major')->nullable();
            $table->enum('position', ['ketua', 'sekretaris', 'bendahara', 'staff'])->default('staff');
            $table->enum('status', ['aktif', 'nonaktif', 'suspended'])->default('aktif');
            $table->enum('join_type', ['founder', 'pendaftar', 'invited'])->default('pendaftar');
            $table->date('join_date');
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->unique(['organization_id', 'nim']);
        });

        // TABLE: EVENTS
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained('organizations')->onDelete('cascade');
            $table->foreignId('created_by')->constrained('members')->onDelete('restrict');
            $table->string('name');
            $table->text('description');
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->string('location');
            $table->integer('quota')->default(50);
            $table->integer('current_participants')->default(0);
            $table->string('banner')->nullable();
            $table->enum('status', ['draft', 'approved', 'ongoing', 'completed', 'cancelled'])->default('draft');
            $table->text('internal_notes')->nullable();
            $table->timestamps();
        });

        // TABLE: SUBMISSIONS (Pengajuan/Proposal)
        Schema::create('submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained('organizations')->onDelete('cascade');
            $table->foreignId('member_id')->constrained('members')->onDelete('restrict');
            $table->string('title');
            $table->text('description');
            $table->enum('type', ['proposal', 'budget', 'activity_plan'])->default('proposal');
            $table->enum('status', ['draft', 'submitted', 'reviewing', 'approved', 'rejected', 'revised'])->default('draft');
            $table->text('feedback')->nullable();
            $table->integer('revision_count')->default(0);
            $table->date('submitted_date')->nullable();
            $table->date('approved_date')->nullable();
            $table->string('file_path')->nullable();
            $table->timestamps();
        });

        // TABLE: REPORTS (Laporan Kegiatan)
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained('organizations')->onDelete('cascade');
            $table->foreignId('event_id')->nullable()->constrained('events')->onDelete('set null');
            $table->foreignId('member_id')->constrained('members')->onDelete('restrict');
            $table->string('title');
            $table->text('content');
            $table->integer('participants')->default(0);
            $table->string('report_type')->default('activity'); // activity, financial, semester
            $table->enum('status', ['draft', 'submitted', 'reviewing', 'approved', 'rejected', 'revision_needed'])->default('draft');
            $table->text('reviewer_notes')->nullable();
            $table->date('submitted_date')->nullable();
            $table->date('approved_date')->nullable();
            $table->string('attachment')->nullable();
            $table->timestamps();
        });

        // TABLE: TASKS (Tugas/Reminder Internal)
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained('organizations')->onDelete('cascade');
            $table->foreignId('assigned_to')->nullable()->constrained('members')->onDelete('set null');
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('priority', ['urgent', 'normal', 'low'])->default('normal');
            $table->enum('status', ['pending', 'completed', 'overdue'])->default('pending');
            $table->enum('task_type', ['profile_completion', 'report_submission', 'revision', 'deadline_reminder'])->default('deadline_reminder');
            $table->dateTime('deadline')->nullable();
            $table->dateTime('completed_at')->nullable();
            $table->foreignId('related_submission_id')->nullable()->constrained('submissions')->onDelete('set null');
            $table->foreignId('related_report_id')->nullable()->constrained('reports')->onDelete('set null');
            $table->timestamps();
        });

        // TABLE: ACTIVITY LOGS (Log Aktivitas Otomatis)
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained('organizations')->onDelete('cascade');
            $table->foreignId('member_id')->nullable()->constrained('members')->onDelete('set null');
            $table->string('activity_type'); // event_published, member_joined, submission_approved, report_received, etc
            $table->text('description');
            $table->string('related_model')->nullable(); // Event, Submission, Report, Member
            $table->unsignedBigInteger('related_id')->nullable(); // ID dari model terkait
            $table->json('metadata')->nullable(); // data tambahan untuk konteks
            $table->timestamps();
            
            $table->index(['organization_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
        Schema::dropIfExists('tasks');
        Schema::dropIfExists('reports');
        Schema::dropIfExists('submissions');
        Schema::dropIfExists('events');
        Schema::dropIfExists('members');
        Schema::dropIfExists('organizations');
    }
};
