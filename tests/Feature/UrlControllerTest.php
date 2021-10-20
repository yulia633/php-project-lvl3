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

    public int $id;

    protected function setUp(): void
    {
        parent::setUp();

        $data = [
            'name' => 'https://google.com',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];

        $this->id = DB::table('urls')->insertGetId($data);
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

    /**
     * Test of urls store basic.
     *
     * @return void
     */
    public function testEmptyStore()
    {
        $data = ['url' => ['name' => '']];

        $response = $this->post(route('urls.store'), $data);

        $response->assertSessionHasErrors(['url.name']);

        $response->assertRedirect();
    }

    /**
     * Test of urls store.
     *
     * @return void
     */
    public function testStore()
    {
        $data = ['url' => ['name' => 'https://hexlet.io']];

        $response = $this->post(route('urls.store'), $data);
        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas('urls', $data['url']);
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
