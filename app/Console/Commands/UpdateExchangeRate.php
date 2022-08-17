<?php

namespace App\Console\Commands;

use App\Models\CurrencyDate;
use App\Models\Exchange;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Monolog\Logger;

class UpdateExchangeRate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'course:daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get daily courses';

    public function handle()
    {
        $client = new Client();
        $url = env('API_NBP').'exchangerates/tables/b?format=json';

        $response = $client->request('GET', $url, [
            'verify'  => false,
        ]);

        $responseBody = json_decode($response->getBody());

        foreach($responseBody[0]->rates as $res){
            Exchange::updateOrCreate(
                ['currency' => $res->currency,
                'code' => $res->code,
                'mid' => $res->mid]
            );
        }

        CurrencyDate::updateOrCreate(
            ['date' => Carbon::now()]
        );
    }
}
