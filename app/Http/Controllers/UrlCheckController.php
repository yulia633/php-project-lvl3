<?php

namespace App\Http\Controllers;

use App\Jobs\CheckUrlJob;


class UrlCheckController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(int $id)
    {
        CheckUrlJob::dispatch($id);
        flash('Страница проверяется. Пожалуйста, подождите =)')->info();

        return redirect()
            ->route('urls.show', ['url' => $id]);
    }
}
