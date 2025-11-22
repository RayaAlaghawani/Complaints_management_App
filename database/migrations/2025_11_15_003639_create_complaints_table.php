<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('complaints', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('government_agencie_id')->constrained('government_agencies')
                ->onDelete('cascade');
            $table->unsignedBigInteger('locked_by')->nullable();
            $table->dateTime('lock_expires_at')->nullable();
            $table->dateTime('locked_at')->nullable();


            $table->string('title');
            $table->text('description');
            $table->text('note')->nullable();
            $table->string('attachment_path')->nullable()->comment('مسار حفظ الملف المرفق (صورة/ملف)');
            $table->enum('status',
                ['Pending', 'In Progress', 'Resolved', 'Rejected'])->default('Pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('complaints');
    }
};
