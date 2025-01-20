<?php
use Illuminate\Database\Seeder;
class ProjectTypeSeeder extends Seeder
{
	public function run()
	{
		$items = [
				['name' => 'Vegetation Management', 'slug' => 'vegetation-management'],
				['name' => 'Training', 'slug' => 'training'],
				['name' => 'Innovation', 'slug' => 'innovation'],
				['name' => 'Planning', 'slug' => 'planning'],

		];

		foreach ($items as $item) {
			\App\Models\ProjectType::create($item);
		}
	}
}