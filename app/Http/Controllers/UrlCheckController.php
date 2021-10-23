<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use DiDom\Document;
use Illuminate\Http\Client\ConnectionException;

class UrlCheckController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(int $id)
    {
        $url = DB::table('urls')->find($id);

        try {
            $response = Http::timeout(3)->get($url->name);
        } catch (ConnectionException $e) {
            flash('Сайт не доступен')->error();
            return redirect()
                ->route('urls.show', ['url' => $id]);
        }

        $document = new Document($response->body());
        $h1 = optional($document->first('h1'))->text();
        $keywords = optional($document->first('meta[name=keywords]'))->getAttribute('content');
        $description = optional($document->first('meta[name=description]'))->getAttribute('content');

        DB::table('url_checks')->insert(
            [
                'url_id' => $id,
                'status_code' => $response->status(),
                'h1' => $h1,
                'keywords' => $keywords,
                'description' => $description,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        );

        flash('Страница успешно проверена')->info();

        return redirect()
            ->route('urls.show', ['url' => $id]);
    }
}
