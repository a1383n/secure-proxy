<?php

namespace Database\Seeders;

use App\Enums\DomianFilterType;
use App\Enums\FilterItemPatternType;
use App\Models\DomainFilter;
use Illuminate\Database\Seeder;

class DomainFilterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $filter = new DomainFilter(['name' => 'Bypass all', 'enabled' => true, 'type' => DomianFilterType::BYPASS]);
        $filter->save();

        $filter->items()->create([
            'pattern_type' => FilterItemPatternType::WILDCARD,
            'pattern'      => '*',
        ]);
    }
}
