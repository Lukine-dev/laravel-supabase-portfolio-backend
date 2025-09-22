<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->constrained();
            $table->string('title');
            $table->text('short_description');
            $table->text('full_description');
            $table->date('start_date');
            $table->date('completion_date')->nullable();
            $table->string('position');
            $table->json('tech_stack');
            $table->string('github_url')->nullable();
            $table->string('project_url')->nullable();
            $table->string('image_path')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('projects');
    }
};