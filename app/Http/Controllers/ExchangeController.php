<?php

namespace App\Http\Controllers;

use App\Models\Exchange;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ExchangeController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $iHave = $request['have'];
        $iWant = $request['want'];
        $howMany = $request['how'];

        $exchangeHave = Exchange::where('code', $iHave)->first();
        $exchangeWant = Exchange::where('code', $iWant)->first();

        if(!$exchangeHave || !$exchangeWant) {
            return Response()->json([
                'error' => "not found exchange"
            ]);
        }

        if($iHave == "PLN"){
            return $this->getResponse([
                'from' => $iHave,
                'to' => $iWant,
                'course' => round(($exchangeHave->mid / $exchangeWant->mid) * $howMany,3)
            ]);
        }elseif ($iHave == $iWant) {
            return $this->getResponse([
                'from' => $iHave,
                'to' => $iWant,
                'course' => $howMany
            ]);
        }
        else {
            return $this->getResponse([
                'from' => $iHave,
                'to' => $iWant,
                'course' => round($exchangeHave->mid / $exchangeWant->mid * $howMany, 2)
            ]);
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
