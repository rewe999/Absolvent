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
        $response = $this->get('http://api.nbp.pl/api/exchangerates/tables/a?format=json');

        $response->assertStatus(200);
    }
}
