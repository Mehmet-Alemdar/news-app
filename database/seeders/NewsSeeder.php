<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\News;

class NewsSeeder extends Seeder
{
    public function run(): void
    {
        $batch = 10000;
        $total = 250000;
        for ($i = 0; $i < $total / $batch; $i++) {
            News::factory()->count($batch)->create();
        }
    }
}