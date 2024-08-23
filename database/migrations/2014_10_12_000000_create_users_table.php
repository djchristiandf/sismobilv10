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
        Schema::create('tbl_users', function (Blueprint $table) {
            $table->id('userId');
            $table->string('email', 128);
            //$table->string('password', 128);
            $table->string('name', 128);
            //$table->string('mobile', 20);
            //$table->smallInteger('roleId');
            $table->smallInteger('isDeleted');
            //$table->integer('createdBy');
            $table->dateTime('createdDtm');
            //$table->integer('updatedBy')->nullable();
            $table->dateTime('updatedDtm')->nullable();
            $table->string('login', 100);
            $table->string('matricula', 9);
            $table->string('setor', 45);
            $table->boolean('empregado');
            $table->string('cargo', 128);
            //$table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
