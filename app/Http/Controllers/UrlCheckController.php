<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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
        DB::table('urls')->where('id', $id)->update(['updated_at' => Carbon::now()]);

        DB::table('url_checks')->insert(
            [
                'url_id' => $id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        );

        flash('Страница успешно проверена')->info();
        return redirect()->route('urls.show', ['url' => $id]);
    }
}
