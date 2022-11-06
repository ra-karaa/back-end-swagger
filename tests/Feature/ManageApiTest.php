<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Employees;

class ManageApiTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_create_employe()
    {
        $postOne = Employees::create([
            'name' => 'Mujianto',
            'salary' => 1
        ]);
        $this->assertTrue(true);
    }
}
