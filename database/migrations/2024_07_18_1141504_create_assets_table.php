<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssetsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('assets', function (Blueprint $table) {
			$table->increments('id');
			$table->string('name');
			$table->string('serial_number')->unique();
			$table->string('model');
			$table->string('status');
			$table->decimal('cost', 10, 2);
			$table->string('location');
			$table->date('purchase_date');
			$table->date('warranty_date');
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
		Schema::dropIfExists('assets');
	}
}