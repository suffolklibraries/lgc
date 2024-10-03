<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('content_reports', function (Blueprint $table) {
            $table->increments('id');
            $table->text('entry_id')->nullable();
            $table->text('reason')->nullable();
            $table->text('comments')->nullable();
            $table->text('email')->nullable();
            $table->boolean('complete')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
     public function down()
     {
         Schema::dropIfExists('content_reports');
     }
};
