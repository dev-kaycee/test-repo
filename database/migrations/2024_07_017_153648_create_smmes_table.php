<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmmesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
	public function up()
	{
		Schema::create('smmes', function (Blueprint $table) {
			$table->increments('id');
			$table->string('name');
			$table->date('registration_date');
			$table->integer('years_of_experience');
			$table->text('team_composition');
			$table->boolean('documents_verified');
			$table->string('grade');
			$table->enum('status', ['green', 'yellow', 'red']);
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
        Schema::dropIfExists('smmes');
    }
}