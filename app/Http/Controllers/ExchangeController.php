<?php

namespace App\Http\Controllers;

use App\Models\Exchange;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExchangeController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $iHave = $request['have'];
        $iWant = $request['want'];
        $howMany = $request['how'];

        $exchangeHave = Exchange::where('code', $iHave)->first();
        $exchangeWant = Exchange::where('code', $iWant)->first();
        $day = DB::selectOne("SELECT * FROM currency_dates order by date desc limit 1")->date;

        $data = [
            'exchange date' => $day,
            'from' => $iHave,
            'to' => $iWant,
        ];

        if(!$exchangeHave || !$exchangeWant) {
            return Response()->json([
                'error' => "not found exchange"
            ]);
        }

        if($iHave == "PLN"){
            $data['course'] = round(($exchangeHave->mid / $exchangeWant->mid) * $howMany,3);
            return $this->getResponse($data);
        }elseif ($iHave == $iWant) {
            $data['course'] = $howMany;
            return $this->getResponse($data);
        }
        else {
            $data['course'] = round($exchangeHave->mid / $exchangeWant->mid * $howMany, 2);
            return $this->getResponse($data);
        }
    }

    public function getAllCurrencies(): JsonResponse
    {
        $currencies = Exchange::all()->toArray();

        return Response()->json($currencies);
    }

    public function countCurrencies(): JsonResponse
    {
        $currencies = Exchange::all()->count();

        return Response()->json(['currency counter' => $currencies]);
    }

    public function getResponse($data): JsonResponse
    {
        return Response()->json($data);
    }
}
