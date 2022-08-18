<?php

namespace App\Console\Commands;

use App\Models\CurrencyDate;
use App\Models\Exchange;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Monolog\Logger;
use Throwable;

class UpdateExchangeRate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'exchange:daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get daily exchanges';

    public function handle()
    {
        try {
            $currencies = Http::get('http://api.nbp.pl/api/exchangerates/tables/a?format=json')->json();

            foreach ($currencies[0]['rates'] as $rates){
                if(!Exchange::where(['currency' => $rates['currency'], 'code' => $rates['code']])->first()){
                    $exchange = new Exchange();
                    $exchange->currency = $rates['currency'];
                    $exchange->code = $rates['code'];
                    $exchange->mid = $rates['mid'];
                    $exchange->save();
                }else {
                    Exchange::where(['currency' => $rates['currency'], 'code' => $rates['code']])->update(['mid' => $rates['mid']]);
                }
            }

            CurrencyDate::updateOrCreate(
                ['date' => Carbon::now()]
            );
        }catch (Throwable $e){
            echo "error update exchange rate " . date('Y-m-d G:i:s'). "\n";
            Log::error('error update exchange rate '. $e->getMessage());
        }
    }
}
