<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Faker\Factory;
use Carbon\Carbon;

class UrlCheckControllerTest extends TestCase
{
    /**
     * Test of url check store.
     *
     * @return void
     */
    public function testStore(): void
    {
        $data = [
            'name' => 'https://google.com',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ];

        $id = DB::table('urls')->insertGetId($data);

        Http::fake([$id => Http::response(200)]);

        $expectedData = [
            'url_id' => $id,
            'status_code' => 200
        ];

        $response = $this->post(route('urls.checks.store', $id));
        $response->assertSessionHasNoErrors();
        $response->assertRedirect();
        $this->assertDatabaseHas('url_checks', $expectedData);
    }
}
