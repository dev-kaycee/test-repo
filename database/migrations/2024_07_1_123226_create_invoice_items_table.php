<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvoiceItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_items', function (Blueprint $table) {
					$table->increments('id');
					$table->unsignedInteger('invoice_id');
					$table->string('description');
					$table->integer('quantity');
					$table->decimal('unit_price', 10, 2);
					$table->decimal('vat_rate', 5, 2);
					$table->decimal('amount', 10, 2);
					$table->timestamps();

					$table->foreign('invoice_id')
							->references('id')
							->on('invoices')
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
        Schema::dropIfExists('invoice_items');
    }
}