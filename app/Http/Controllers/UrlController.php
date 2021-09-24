<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class UrlController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $urls = DB::table('urls')->get();
        return view('urls.index', compact('urls'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'url.name' => 'required|url|max:255'
        ]);

        if ($validator->fails()) {
            flash('Некорретный url')->error()->important();
            return redirect()->route('welcome');
        }

        $data = $request->input('url');

        $parsedUrl = parse_url($data['name']);

        $normalizedUrl = strtolower("{$parsedUrl['scheme']}://{$parsedUrl['host']}");
        $url = DB::table('urls')
            ->where('name', $normalizedUrl)
            ->first();

        if (empty($url)) {
            $newUrl = DB::table('urls')->insertGetId(
                [
                    'name' => $normalizedUrl,
                    'created_at' => Carbon::now()->toString(),
                    'updated_at' => Carbon::now()->toString()
                ]
            );

            flash('Страница успешно добавлена')->success();
            return redirect()->route('urls.show', ['url' => $newUrl]);
        }

        flash('Страница уже существует')->info();
        return redirect()->route('urls.show', ['url' => $url->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $url = DB::table('urls')->find($id);
        return view('urls.show', compact('url'));
    }
}
