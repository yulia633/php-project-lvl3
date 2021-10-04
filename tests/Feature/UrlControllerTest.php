<?php

namespace Tests\Feature;

use Tests\TestCase;
use Faker\Factory;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UrlControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    public $id;

    protected function setUp(): void
    {
        parent::setUp();
        $faker = Factory::create();

        $parsedUrl = parse_url($faker->url);

        $urlScheme = strtolower($parsedUrl['scheme']);
        $urlHost = strtolower($parsedUrl['host']);

        $normalizedUrl = "{$urlScheme}://{$urlHost}";
        $newUrl = [
            'name' => $normalizedUrl,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ];

        $this->id = DB::table('urls')->insertGetId($newUrl);
    }

    /**
     * Test of urls index.
     *
     * @return void
     */
    public function testIndex()
    {
        $response = $this->get(route('urls.index'));
        $response->assertOk();
    }

    private function formData($overrides = [])
    {
        $faker = Factory::create();

        return array_merge([
            'name' => $faker->name,
        ], $overrides);
    }

    /**
     * Test of urls store basic.
     *
     * @return void
     */
    public function testEmptyStore()
    {
        $response = $this->post(route('urls.store'), $this->formData(['name' => '']));
        $response->assertSessionHasErrors(['name']);

        $response->assertRedirect();
    }

    /**
     * Test of urls store.
     *
     * @return void
     */
    public function testStore()
    {
        $data_fake =  array_merge([
            'name' => "https://google.com",
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        $data = ['url' => 'https://google.com'];
        $response = $this->post(route('urls.store'), $data);

        $response->assertRedirect();

        $this->assertDatabaseHas('urls', $data_fake);
    }

    /**
     * Test of urls show.
     *
     * @return void
     */
    public function testShow()
    {
        $response = $this->get(route('urls.show', ['url' => $this->id]));
        $response->assertOk();
    }
}
