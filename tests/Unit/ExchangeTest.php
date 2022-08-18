<?php

namespace Tests\Unit;

use App\Models\Exchange;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;
use Symfony\Component\HttpFoundation\Response;

class ExchangeTest extends TestCase
{
    use RefreshDatabase;

    public function test_currencies()
    {
        $response = $this->get('/api/currencies');

        $response->assertStatus(Response::HTTP_OK);
    }

    public function test_count_currencies()
    {
        $response = $this->get('/api/count');

        $response->assertStatus(Response::HTTP_OK);
    }

    public function test_currency_exchange()
    {

        $dolar = new Exchange();
        $dolar->currency = "dolar kanadyjski";
        $dolar->code = "CAD";
        $dolar->mid = 3.5994;
        $dolar->save();

        $euro = new Exchange();
        $euro->currency = "euro";
        $euro->code = "EUR";
        $euro->mid = 4.7244;
        $euro->save();

        $data = [
            "from" => "CAD",
            "to" => "EUR",
            "how" => 1
        ];

        $response = $this->post('/api/exchange', $data);
        $response->assertStatus(Response::HTTP_OK);
    }

    public function test_api_nbp_connection()
    {
        $response = Http::get('http://api.nbp.pl/api/exchangerates/tables/a?format=json');
        $status = $response->status();

        if($status == 200) $this->assertTrue(true);
    }

    public function test_command_to_insert_or_update_exchanges()
    {
        $this->artisan('exchange:daily')->assertSuccessful();
    }

    public function test_non_existent_exchange()
    {
        $data = [
            "from" => "non existent exchange 1",
            "to" => "non existent exchange 2",
            "how" => 15
        ];

        $response = $this->post('/api/exchange', $data);
        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function test_one_currency_if_exist()
    {
        $exchange = new Exchange();
        $exchange->currency = "polski złotych";
        $exchange->code = "PLN";
        $exchange->mid = 1;
        $exchange->save();

        $response = $this->get('/api/currency/PLN');
        $response->assertStatus(Response::HTTP_OK);
    }

    public function test_one_currency_if_not_exist()
    {
        $response = $this->get('/api/currency/not_exist');

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }
}
