<?php

namespace Tests\Unit;

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
        $data = [
            "from" => "PLN",
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
        $response = $this->get('/api/currency/PLN');
        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function test_one_currency_if_not_exist()
    {
        $response = $this->get('/api/currency/not_exist');

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }
}
