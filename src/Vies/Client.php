<?php

namespace Alegiac\LaravelVatChecker\Vies;

use Alegiac\LaravelVatChecker\Format\LaravelVatFormatChecker;
use Alegiac\LaravelVatChecker\Mail\ViesConnectionError;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use SoapClient;
use SoapFault;

class Client
{
    /**
     * @const string
     */
    public const URL = 'https://ec.europa.eu/taxation_customs/vies/checkVatService.wsdl';

    private ?\SoapClient $client = null;

    /**
     * Client constructor.
     *
     * @param int $timeout
     */
    public function __construct(protected int $timeout = 10)
    {
    }

    /**
     * Check via Vies the VAT number
     * @param string $vatNumber
     *
     * @return bool
     *
     * @throws ViesException
     */
    public function check(string $vatNumber): array
    {
        $cacheEnabled = (bool) (config('vat-checker.cache.enabled', true));
        $cacheTtl = (int) (config('vat-checker.cache.ttl', 86400));
        $cacheKey = 'vat-checker:' . strtoupper(trim($vatNumber));

        try {
            $vat = LaravelVatFormatChecker::splitVat($vatNumber);

            $response = $this->getClient()->checkVat(
                [
                    'countryCode' => $vat[0] ?? '',
                    'vatNumber' => $vat[1] ?? '',
                    'requestDate' => $vat[2] ?? '',
                    'valid' => $vat[3] ?? false,
                    'name' => $vat[3] ?? '',
                    'address' => $vat[4] ?? '',
                ]
            );

            $responseArray = (array) $response;
            // normalize shape with error flags
            $responseArray['isError'] = false;
            $responseArray['errorDescription'] = null;

            if ($cacheEnabled) {
                if ($cacheTtl <= 0) {
                    Cache::forever($cacheKey, $responseArray);
                } else {
                    Cache::put($cacheKey, $responseArray, $cacheTtl);
                }
            }

            return $responseArray;

        } catch (SoapFault $soapFault) {
            // Notifications on connection error
            if ((bool) config('vat-checker.notifications.enabled', false)
                && (bool) config('vat-checker.notifications.mail.enabled', false)
            ) {
                $to = (string) config('vat-checker.notifications.mail.to', '');
                if (!empty($to)) {
                    try {
                        Mail::to(explode(',', $to))->send(new ViesConnectionError(
                            strtoupper(trim($vatNumber)),
                            $soapFault->getMessage()
                        ));
                    } catch (\Throwable $e) {
                        // Silently ignore mail errors to not break flow
                    }
                }
            }
            if ($cacheEnabled && Cache::has($cacheKey)) {
                $cached = (array) Cache::get($cacheKey);
                $cached['isError'] = true;
                $cached['errorDescription'] = $soapFault->getMessage();
                return $cached;
            }
            // No cache fallback: return explicit error payload so callers can surface isError
            return [
                'valid' => false,
                'isError' => true,
                'errorDescription' => $soapFault->getMessage(),
            ];
        }

        // Should not reach here; added for type completeness
        // Return empty array if something unexpected happens
        return [];
    }

    /**
     * Create SoapClient
     * @return SoapClient
     */
    protected function getClient(): SoapClient
    {
        if (! $this->client instanceof \SoapClient) {
            $this->client = new SoapClient(self::URL, ['connection_timeout' => $this->timeout]);
        }

        return $this->client;
    }
}
