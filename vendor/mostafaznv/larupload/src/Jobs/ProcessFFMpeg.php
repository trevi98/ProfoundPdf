<?php

namespace Mostafaznv\Larupload\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Exception;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\DB;
use Mostafaznv\Larupload\Events\LaruploadFFMpegQueueFinished;
use Mostafaznv\Larupload\Larupload;
use Mostafaznv\Larupload\LaruploadEnum;

class ProcessFFMpeg implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected int       $queueId;
    protected int       $id;
    protected string    $name;
    protected string    $model;
    protected Larupload $standalone;


    public function __construct(int $queueId, int $id, string $name, string $model, string $standalone = null)
    {
        $this->queueId = $queueId;
        $this->id = $id;
        $this->name = $name;
        $this->model = $model;

        if ($standalone) {
            $this->standalone = unserialize(base64_decode($standalone));
        }
    }

    /**
     * @throws Exception
     */
    public function handle()
    {
        $this->updateStatus(false, true);

        // we need to handle ffmpeg queue after model saved event
        sleep(1);

        try {
            if (isset($this->standalone) and $this->standalone) {
                $this->standalone->handleFFMpegQueue();
            }
            else {
                $class = $this->model;
                $modelNotSaved = true;

                while ($modelNotSaved) {
                    $model = $class::where('id', $this->id)->first();

                    if ($model->{$this->name}->meta('name')) {
                        $modelNotSaved = false;

                        $availableQueues = DB::table(LaruploadEnum::FFMPEG_QUEUE_TABLE)
                            ->where('record_id', $this->id)
                            ->where('record_class', $this->model)
                            ->where('status', false)
                            ->count();

                        $model->{$this->name}->handleFFMpegQueue($availableQueues === 1);
                    }

                    sleep(1);
                }
            }

            $this->updateStatus(true, false);
        }
        catch (FileNotFoundException | Exception $e) {
            $this->updateStatus(false, false, $e->getMessage());

            throw new Exception($e->getMessage());
        }
    }

    /**
     * Update LaruploadFFMpegQueue table
     *
     * @param bool $status
     * @param bool $isStarted
     * @param string|null $message
     * @return int
     */
    protected function updateStatus(bool $status, bool $isStarted, string $message = null): int
    {
        $dateColumn = $isStarted ? 'started_at' : 'finished_at';

        $result = DB::table(LaruploadEnum::FFMPEG_QUEUE_TABLE)->where('id', $this->queueId)->update([
            'status'    => $status,
            'message'   => $message,
            $dateColumn => now(),
        ]);

        if ($result and $status) {
            event(new LaruploadFFMpegQueueFinished($this->id, $this->model, $this->queueId));
        }

        return $result;
    }
}
