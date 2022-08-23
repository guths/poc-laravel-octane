<?php

namespace App\Jobs;

use Google\Cloud\Logging\LoggingClient;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendLogJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public array $labels;
    public string $logName;

    public function __construct(string $logName, array $labels)
    {
        $this->labels = $labels;
        $this->logName = $logName;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $logging = new LoggingClient([
                'projectId' => 'crack-parser-359620', //deve ser mudado para o nome de seu projeto
                'keyFile' => json_decode(file_get_contents('google/google-credentials.json'), true) //deve ser mudado para o local de sua chave
            ]);

            $logger = $logging->logger('PAYLIVRE1');

            // Write a log entry.
            $logger->write($this->logName, [
                'name' => $this->logName,
                'labels' => $this->labels,
                'severity' => 500,
                'resource' => [
                    'type' => 'gce_instance',
                    'labels' => [
                        'instance_id' => '1234567890123456789',
                        'zone' => 'us-central1-f',
                    ],
                ],
            ]);

        } catch (\Exception $exception) {
            dd($exception);
        }
    }
}
