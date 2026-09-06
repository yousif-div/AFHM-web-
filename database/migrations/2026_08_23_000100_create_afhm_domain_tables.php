<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('timetables', function (Blueprint $t) {
            $t->id(); $t->foreignId('admin_id')->constrained('users')->restrictOnDelete();
            $t->foreignId('teacher_id')->constrained('users')->restrictOnDelete();
            $t->string('day', 3); $t->time('start_time'); $t->time('end_time');
            $t->string('classroom', 100); $t->string('subject', 100); $t->softDeletes(); $t->timestamps();
        });
        Schema::create('teaching_reports', function (Blueprint $t) {
            $t->id(); $t->foreignId('teacher_id')->constrained('users')->restrictOnDelete();
            $t->string('title', 200); $t->text('content'); $t->string('report_type', 20);
            $t->string('file_path')->nullable(); $t->timestamp('submitted_at'); $t->timestamps();
        });
        Schema::create('performance_reports', function (Blueprint $t) {
            $t->id(); $t->foreignId('generated_by')->constrained('users')->restrictOnDelete();
            $t->foreignId('teacher_id')->nullable()->constrained('users')->nullOnDelete();
            $t->string('title'); $t->longText('content'); $t->string('period');
            $t->date('date_from')->nullable(); $t->date('date_to')->nullable(); $t->timestamp('generated_at'); $t->timestamps();
        });
        Schema::create('teaching_materials', function (Blueprint $t) {
            $t->id(); $t->foreignId('uploaded_by')->constrained('users')->restrictOnDelete();
            $t->string('title'); $t->text('description')->nullable(); $t->string('subject')->nullable();
            $t->string('grade', 50)->nullable(); $t->string('material_type', 50)->nullable();
            $t->string('file_path'); $t->timestamp('uploaded_at'); $t->timestamps();
        });
        Schema::create('feedbacks', function (Blueprint $t) {
            $t->id(); $t->foreignId('supervisor_id')->constrained('users')->restrictOnDelete();
            $t->foreignId('teacher_id')->constrained('users')->restrictOnDelete(); $t->text('content');
            $t->unsignedTinyInteger('rating'); $t->timestamp('submitted_at'); $t->timestamps();
        });
        Schema::create('evaluations', function (Blueprint $t) {
            $t->id(); $t->foreignId('supervisor_id')->constrained('users')->restrictOnDelete();
            $t->foreignId('teacher_id')->constrained('users')->restrictOnDelete(); $t->decimal('score', 3, 2);
            $t->text('comments'); $t->timestamp('evaluated_at'); $t->timestamps();
        });
        Schema::create('notifications', function (Blueprint $t) {
            $t->uuid('id')->primary(); $t->string('type'); $t->morphs('notifiable'); $t->text('data');
            $t->timestamp('read_at')->nullable(); $t->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('notifications'); Schema::dropIfExists('evaluations'); Schema::dropIfExists('feedbacks');
        Schema::dropIfExists('teaching_materials'); Schema::dropIfExists('performance_reports');
        Schema::dropIfExists('teaching_reports'); Schema::dropIfExists('timetables');
    }
};
