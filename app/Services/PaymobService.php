<?php

namespace App\Services;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class PaymobService
{
    protected $base_url;
    protected array $header;
    protected $api_key;
    protected $integrations_id;
    public function __construct()
    {
        $this->base_url = env("PAYMOB_BASE_URL");
        $this->api_key = env("PAYMOB_API_KEY");
        $this->header = [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];

        $this->integrations_id = [5092443, 5092444];
    }
    protected function buildRequest($method, $url, $data = null, $type = 'json'): \Illuminate\Http\JsonResponse
    {
        try {
            //type ? json || form_params
            $response = Http::withHeaders($this->header)->send($method, $this->base_url . $url, [
                $type => $data
            ]);

            return response()->json([
                'success' => $response->successful(),
                'status' => $response->status(),
                'data' => $response->json(),
            ], $response->status());
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'status' => 500,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    //first generate token to access api
    protected function generateToken()
    {
        // dd($this->api_key);
        $response = $this->buildRequest('POST', '/api/auth/tokens', ['api_key' => $this->api_key]);

        Log::info('Token Response:', ['response' => $response->getData(true)]);
        $responseData = $response->getData(true);
        if (isset($responseData['data']['token'])) {
            return $responseData['data']['token'];
        }
        throw new \Exception('Unable to generate token, data key missing in response');
    }

    public function sendPayment(Request $request): array
    {
        $this->header['Authorization'] = 'Bearer ' . $this->generateToken();
        $data = $request->all();
        $data['api_source'] = "INVOICE";
        $data['integrations'] = $this->integrations_id;

        $response = $this->buildRequest('POST', '/api/ecommerce/orders', $data);
        $responseData = $response->getData(true);

        if ($responseData['success'] && isset($responseData['data']['url'])) {
            return ['success' => true, 'url' => $responseData['data']['url']];
        }

        return ['success' => false, 'url' => route('payment.failed')];
    }

    public function callBack(Request $request): ?int
    {
        $response = $request->all();
        Storage::put('paymob_response.json', json_encode($response)); // للتشخيص

        if (isset($response['success']) && $response['success'] === 'true') {
            if (isset($response['merchant_order_id'])) {
                return (int) $response['merchant_order_id'];
            }
        }

        return null;
    }

}