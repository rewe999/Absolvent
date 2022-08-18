<?php

namespace App\Http\Controllers;

use App\Models\CurrencyDate;
use App\Models\Exchange;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExchangeController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $quantity_before_conversion = strtoupper($request['from']);
        $quantity_after_conversion = strtoupper($request['to']);
        $target_quantity = $request['how'];

        $exchangeHave = Exchange::where('code', $quantity_before_conversion)->first();
        $exchangeWant = Exchange::where('code', $quantity_after_conversion)->first();
        $day = CurrencyDate::orderBy('date', 'desc')->first()->date ?? Carbon::now();

        $data = [
            'exchange date' => $day,
            'from' => $quantity_before_conversion,
            'to' => $quantity_after_conversion,
        ];

        if(!$exchangeHave || !$exchangeWant) {
            return response()->json([
                'error' => "not found exchange"
            ],404);
        }

        if($quantity_before_conversion == "PLN"){
            $data['course'] = round(($exchangeHave->mid / $exchangeWant->mid) * $target_quantity,3);
            return response()->json($data,200);
        }elseif ($quantity_before_conversion == $quantity_after_conversion) {
            $data['course'] = $target_quantity;
            return response()->json($data,200);
        }
        else {
            $data['course'] = round($exchangeHave->mid / $exchangeWant->mid * $target_quantity, 2);
            return response()->json($data,200);
        }
    }

    public function getAllCurrencies(): JsonResponse
    {
        $currencies = Exchange::all()->toArray();

        return response()->json($currencies);
    }

    public function getCurrency($currencyName)
    {
        $currency = Exchange::where('code', strtoupper($currencyName))->first();

        if(!$currency) return response()->json(['error' => 'not found currency'], 404);

        return response()->json([
            "currency" => $currency->currency,
            "code" => $currency->code,
            "mid" => $currency->mid,
        ]);
    }

    public function countCurrencies(): JsonResponse
    {
        $currencies = Exchange::all()->count();

        return response()->json(['currency counter' => $currencies]);
    }
}
