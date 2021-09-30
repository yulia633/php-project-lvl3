<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class UrlCheckController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id)
    {
        $url = DB::table('urls')->find($id);

        try {
            $response = Http::get($url->name);

            DB::table('url_checks')->insert(
                [
                    'url_id' => $id,
                    'status_code' => $response->status(),
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]
            );

            flash('Страница успешно проверена')->info();
            return redirect()->route('urls.show', ['url' => $id]);
        } catch (\Exception $e) {
            return redirect()
                ->route('urls.show', ['url' => $id])
                ->withErrors("{$e->getMessage()}");
        }
    }
}
