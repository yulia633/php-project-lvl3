<?php

namespace App\Jobs;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use DiDom\Document;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CheckUrlJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private int $urlId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(int $id)
    {
        $this->urlId = $id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $check = [
                'url_id' => $this->urlId,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'status' => 'new',
            ];
            $id = DB::table('url_checks')->insertGetId($check);
            // sleep(5);

            DB::table('url_checks')->where(['id' => $id])->update(['status' => 'in_progress']);
            $url = DB::table('urls')->find($this->urlId);
            $response = Http::timeout(3)->get($url->name);

            $document = new Document($response->body());
            $h1 = optional($document->first('h1'))->text();
            $title = optional($document->first('title'))->text();
            $description = optional($document->first('meta[name=description]'))->getAttribute('content');

            // sleep(5);
            DB::table('url_checks')->where(['id' => $id])->update([
                'url_id' => $this->urlId,
                'status_code' => $response->status(),
                'h1' => $h1,
                'title' => $title,
                'description' => $description,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'status' => 'finished'
            ]);
        } catch (\Throwable $e) {
            DB::table('url_checks')->where(['id' => $id])->update(['status' => 'failed']);
            Log::error($e->getMessage(), [self::class]);
        }
    }
}
