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
    const PLN = "PLN";
    /**
     * @OA\Post(
     * path="/api/exchange",
     * summary="currency conversion e.g. from euro to dollar",
     * description="currency conversion e.g. from euro to dollar",
     * operationId="postListContent",
     * tags={"List Contents"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Provide product list id and product id",
     *    @OA\JsonContent(
     *       required={"from","to","quantity"},
     *       @OA\Property(property="from", type="string", example="EUR"),
     *       @OA\Property(property="to", type="string", example="CAD"),
     *       @OA\Property(property="quantity", type="number", example="1")
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Success",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="response json")
     *        )
     *     )
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $fromCurrency = strtoupper($request['from']);
        $toCurrency = strtoupper($request['to']);
        $quantity = $request['quantity'];

        $exchangeHave = Exchange::where('code', $fromCurrency)->first();
        $exchangeWant = Exchange::where('code', $toCurrency)->first();
        $day = CurrencyDate::orderBy('date', 'desc')->first()->date ?? Carbon::now();

        $data = [
            'exchange_date' => $day,
            'from' => $fromCurrency,
            'to' => $toCurrency
        ];

        if(!$exchangeHave || !$exchangeWant) {
            return response()->json([
                'error' => "exchange not found"
            ],404);
        }

        if($fromCurrency == self::PLN){
            $data['rate'] = round(($exchangeHave->mid / $exchangeWant->mid) * $quantity,3);
            return response()->json($data,200);
        }elseif ($fromCurrency == $toCurrency) {
            $data['rate'] = $quantity;
            return response()->json($data,200);
        }
        else {
            $data['rate'] = round($exchangeHave->mid / $exchangeWant->mid * $quantity, 2);
            return response()->json($data,200);
        }
    }

    /**
     * @OA\Get(
     * path="/api/currencies",
     * summary="Get all available currencies",
     * description="Get all available currencies",
     * operationId="getAllCurrencies",
     * tags={"Get All Currencies"},
     * @OA\Response(
     *    response=200,
     *    description="Success"
     *    )
     *
     *
     * )
     */
    public function getAllCurrencies(): JsonResponse
    {
        $currencies = Exchange::all()->toArray();

        return response()->json($currencies);
    }

    /**
     * @OA\Get(
     * path="/api/currency/EUR",
     * summary="Get information aboout currency",
     * description="Get information aboout currency",
     * operationId="getCurrency",
     * tags={"Get Currency"},
     * @OA\Response(
     *    response=200,
     *    description="Success",
     *     @OA\JsonContent(
     *       @OA\Property(property="currencyName", type="string", example="EUR")
     *       )
     *    )
     *
     *
     * )
     */
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

    /**
     * @OA\Get(
     * path="/api/count",
     * summary="Get information how many currencies are available",
     * description="Get information how many currencies are available",
     * operationId="countCurrencies",
     * tags={"Count Currencies"},
     * @OA\Response(
     *    response=200,
     *    description="Success"
     *    )
     *
     *
     * )
     */
    public function countCurrencies(): JsonResponse
    {
        $currencies = Exchange::all()->count();

        return response()->json(['currency counter' => $currencies]);
    }
}
