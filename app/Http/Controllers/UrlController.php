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

        $lastChecksId = DB::table('url_checks')
            ->select(DB::raw('url_id, MAX(id) as last_check_id'))
            ->groupBy('url_id')
            ->pluck('last_check_id')
            ->all();

        $lastChecks = DB::table('url_checks')
            ->whereIn('id', $lastChecksId)
            ->get()
            ->keyBy('url_id');

        return view('urls.index', compact('urls', 'lastChecks'));
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
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
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

        $urlChecks = DB::table('url_checks')
            ->where('url_id', $id)
            ->orderBy('id', 'desc')
            ->get();

        return view('urls.show', compact('url', 'urlChecks'));
    }
}
