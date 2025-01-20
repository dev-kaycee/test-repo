<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAssetTypeIdToAssetsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('assets', function (Blueprint $table) {
			$table->unsignedInteger('asset_type_id')->after('id');
			$table->foreign('asset_type_id')->references('id')->on('asset_types')->onDelete('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('assets', function (Blueprint $table) {
			$table->dropForeign(['asset_type_id']);
			$table->dropColumn('asset_type_id');
		});
	}
}