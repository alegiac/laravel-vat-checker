<?php

namespace Alegiac\LaravelVatChecker\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

/**
 * Simple controller to expose VAT rates from config.
 */
class VatRatesController extends Controller
{
    /**
     * GET /rates/{country}
     */
    public function show(string $country)
    {
        $country = strtoupper($country);
        $rates = (array) config('vat-checker.rates');
        if (!isset($rates[$country])) {
            return response()->json([
                'countryCode' => $country,
                'found' => false,
                'rates' => [],
            ]);
        }

        return response()->json([
            'countryCode' => $country,
            'found' => true,
            'rates' => $rates[$country],
        ]);
    }

    /**
     * GET /rates
     */
    public function index()
    {
        return response()->json([
            'countries' => array_keys((array) config('vat-checker.rates')),
        ]);
    }
}


