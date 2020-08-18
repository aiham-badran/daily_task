<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePersonalTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('personal', function (Blueprint $table) {
      $table->id();
      $table->string("full_name", 100)->nullable();
      $table->string("phone_number", 15)->nullable();
      $table->string("job", 255)->nullable();
      $table->text("image")->nullable();
      $table->foreignId("userId");
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
    Schema::dropIfExists('personal');
  }
}