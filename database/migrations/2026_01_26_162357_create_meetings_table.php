<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('meetings', function (Blueprint $table) {
            $table->id();
            $table->string('subject');
            $table->text('note')->nullable();
            $table->date('date');
            $table->time('start_time');
            $table->integer('duration')->nullable();
            $table->string('zoom_meeting_id', 50)->nullable();
            $table->text('join_url')->nullable();
            $table->timestamps();
            $table->string('zoom_meeting_id')->nullable(); // رقم الاجتماع في زووم
    $table->text('start_url')->nullable();         // لينك البدء (للمحاضر فقط)
    $table->text('join_url')->nullable();          // لينك الانضمام (للطلبة)
    $table->string('zoom_password')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('meetings');
    }
};

