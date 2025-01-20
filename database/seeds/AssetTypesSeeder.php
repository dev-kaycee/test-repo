<?php

use Illuminate\Database\Seeder;

class AssetTypesSeeder extends Seeder
{
	public function run()
	{
		$items = [
				['name' => 'Vehicle'],
				['name' => 'Equipment'],
				['name' => 'Computer'],
				['name' => 'Furniture'],
				['name' => 'Building'],
		];
	}
}