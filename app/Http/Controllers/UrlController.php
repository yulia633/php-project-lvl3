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
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function index()
    {
        $urls = DB::table('urls')->paginate(10);

        $lastChecks = DB::table('url_checks')
            ->orderBy('updated_at')
            ->latest()
            ->distinct('url_id')
            ->get()
            ->keyBy('url_id');

        return view('urls.index', compact('urls', 'lastChecks'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'url.name' => 'required|url|max:255',
        ]);

        if ($validator->fails()) {
            flash('Некорректный URL')->error()->important();
            return redirect()->route('welcome')
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->input('url');

        $parsedUrl = parse_url($data['name']);

        $normalizedUrl = strtolower("{$parsedUrl['scheme']}://{$parsedUrl['host']}");

        $url = app('db')->table('urls')
            ->where('name', $normalizedUrl)
            ->first();

        if (is_null($url)) {
            $newUrl = DB::table('urls')->insertGetId(
                [
                    'name' => $normalizedUrl,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
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
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function show(int $id)
    {
        $url = DB::table('urls')->find($id);

        $urlChecks = DB::table('url_checks')
            ->where('url_id', $id)
            ->latest()
            ->paginate(5);

        return view('urls.show', compact('url', 'urlChecks'));
    }
}
