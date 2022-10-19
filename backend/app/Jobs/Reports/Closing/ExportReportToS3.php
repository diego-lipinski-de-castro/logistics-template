<?php

namespace App\Jobs\Reports\Closing;

use App\Models\Report;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Storage;

class ExportReportToS3 implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    private $userId;
    private $filename;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(int $userId, string $filename)
    {
        $this->userId = $userId;
        $this->filename = $filename;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $path = "users/{$this->userId}/reports/{$this->filename}";

        $file = Storage::disk('local')->get($path);

        Storage::cloud()->put($path, $file);

        Storage::disk('local')->delete($path);

        Report::create([
            'user_id' => $this->userId,
            'filename' => $this->filename,
        ]);

        NotifyClosingReportCompleted::dispatch($this->userId, $this->filename);
    }
}
