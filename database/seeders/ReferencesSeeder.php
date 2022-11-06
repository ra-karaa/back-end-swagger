<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\References;

class ReferencesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $referense = References::create([
            'code' => 'overtime_method',
            'name' => 'Salary / 173',
            'expresion' => '(salary / 173) * overtime_duration_total'
        ]);

        $referenses = References::create([
            'code' => 'overtime_method',
            'name' => 'Fixed',
            'expresion' => '10000 * overtime_duration_total'
        ]);
    }
}
