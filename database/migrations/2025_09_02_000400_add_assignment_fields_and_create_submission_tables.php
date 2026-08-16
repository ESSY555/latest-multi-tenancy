<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Extend assignments table
        Schema::table('assignments', function (Blueprint $table) {
            if (!Schema::hasColumn('assignments', 'instructions')) {
                $table->text('instructions')->nullable()->after('description');
            }
            if (!Schema::hasColumn('assignments', 'submission_format')) {
                $table->string('submission_format')->nullable()->after('instructions');
            }
            if (!Schema::hasColumn('assignments', 'is_published')) {
                $table->boolean('is_published')->default(false)->after('due_date');
            }
        });

        // Teacher-provided assignment resources
        if (!Schema::hasTable('assignment_attachments')) {
            Schema::create('assignment_attachments', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('assignment_id');
                $table->string('path');
                $table->string('original_name');
                $table->string('mime_type')->nullable();
                $table->unsignedBigInteger('size')->nullable();
                $table->timestamps();

                $table->foreign('assignment_id')->references('id')->on('assignments')->onDelete('cascade');
                $table->index('assignment_id');
            });
        }

        // Student submissions
        if (!Schema::hasTable('assignment_submissions')) {
            Schema::create('assignment_submissions', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('assignment_id');
                $table->unsignedBigInteger('student_id');
                $table->text('content')->nullable();
                $table->enum('status', ['submitted', 'approved', 'returned', 'graded'])->default('submitted');
                $table->string('grade')->nullable();
                $table->text('remarks')->nullable();
                $table->timestamp('submitted_at')->nullable();
                $table->timestamp('graded_at')->nullable();
                $table->timestamps();

                $table->foreign('assignment_id')->references('id')->on('assignments')->onDelete('cascade');
                $table->foreign('student_id')->references('id')->on('users')->onDelete('cascade');
                $table->index(['assignment_id', 'student_id']);
            });
        }

        // Submission attachments (multiple files per submission)
        if (!Schema::hasTable('assignment_submission_attachments')) {
            Schema::create('assignment_submission_attachments', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('submission_id');
                $table->string('path');
                $table->string('original_name');
                $table->string('mime_type')->nullable();
                $table->unsignedBigInteger('size')->nullable();
                $table->timestamps();

                $table->foreign('submission_id')->references('id')->on('assignment_submissions')->onDelete('cascade');
                $table->index('submission_id');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('assignment_submission_attachments');
        Schema::dropIfExists('assignment_submissions');
        Schema::dropIfExists('assignment_attachments');

        Schema::table('assignments', function (Blueprint $table) {
            if (Schema::hasColumn('assignments', 'is_published')) {
                $table->dropColumn('is_published');
            }
            if (Schema::hasColumn('assignments', 'submission_format')) {
                $table->dropColumn('submission_format');
            }
            if (Schema::hasColumn('assignments', 'instructions')) {
                $table->dropColumn('instructions');
            }
        });
    }
};


