<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;

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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'url.name' => 'required|max:255'
        ]);

        $parsedUrl = parse_url($data['url']['name']);
        $normalizedUrl = "{$parsedUrl['scheme']}://{$parsedUrl['host']}";
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

            return redirect()
                ->route('urls.show', ['url' => $newUrl])
                ->with('status', 'Страница успешно добавлена');
        }

        return redirect()
            ->route('urls.show', ['url' => $url->id])
            ->with('status', 'Страница уже существует');
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

        abort($url, 404);
        return view('urls.show', compact('urls'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
