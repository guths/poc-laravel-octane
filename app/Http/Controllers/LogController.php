<?php

namespace App\Http\Controllers;

use App\Jobs\SendLogJob;
use Google\Cloud\Logging\LoggingClient;
use Illuminate\Http\Request;

class LogController extends Controller
{
    public function index()
    {
        return view('log');
    }

    public function store(Request $request)
    {
//        $trace = (new \Exception())->getTrace();

        $labels = [
            'logContent' => $request->input('log_content'),
            'level' => $request->input('log_level'),
            'user_id' => $request->input('user_id'),
            'merchant_id' => $request->input('merchant_id'),
            'severity' => 'INFO',
            'env' => 'local',
            'class' => __CLASS__,
            'method' => __METHOD__,
            'line' => (string) __LINE__,
        ];


        SendLogJob::dispatch($request->input('log_name'), $labels);
    }

    public function search(Request $request)
    {
        $logging = new LoggingClient([
            'projectId' => 'crack-parser-359620', //deve ser mudado para o nome de seu projeto
            'keyFile' => json_decode(file_get_contents(base_path('google/google-credentials.json')), true) //deve ser mudado para o local de sua chave
        ]);

        $loggerFullName = sprintf('projects/%s/logs/%s', 'crack-parser-359620','PAYLIVRE');

        $oneDayAgo = date(\DateTime::RFC3339, strtotime('-24 hours'));
        $filter = sprintf(
            'logName = "%s" AND timestamp >= "%s" AND labels.env="local"',
            $loggerFullName,
            $oneDayAgo
        );

        $options = [
            'filter' => $filter,
        ];

        $entries = $logging->entries($options);
        $result = [];

        foreach ($entries as $entry) {
            $result[] = $entry->info();
        }

        return response()->json($result);
    }

    public function indexSearch()
    {
        return view('search');
    }
}
