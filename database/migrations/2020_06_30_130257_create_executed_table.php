<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExecutedTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('executed', function (Blueprint $table) {
      $table->id();
      $table->smallInteger("exe_full_time");
      $table->smallInteger("exe_number_of_parts")->default(1);
      $table->smallInteger("exe_break_time")->default(0);
      $table->smallInteger("exe_number_of_break")->default(0);
      $table->smallInteger("exe_completed_time")->default(0);
      $table->foreignId("tasks_id");
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
    Schema::dropIfExists('executed');
  }
}