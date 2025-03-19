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
        Schema::create('hackathon_themes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('theme_id')->constrained('themes')->onUpdate('cascade');
            $table->foreignId('hackathon_id')->constrained('hackathons')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
