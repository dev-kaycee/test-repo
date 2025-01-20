<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuoteItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quote_items', function (Blueprint $table) {
					$table->increments('id');
					$table->unsignedInteger('quote_id');
					$table->string('description');
					$table->integer('quantity');
					$table->decimal('unit_price', 10, 2);
					$table->decimal('vat_rate', 5, 2);
					$table->decimal('amount', 10, 2);
					$table->timestamps();

					$table->foreign('quote_id')
							->references('id')
							->on('quotes')
							->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('quote_items');
    }
}