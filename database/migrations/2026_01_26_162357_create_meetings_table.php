<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{//ss
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
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('meetings');
    }
};

