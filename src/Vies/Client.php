<?php

namespace Alegiac\LaravelVatChecker\Vies;

use Alegiac\LaravelVatChecker\Format\LaravelVatFormatChecker;
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

        } catch (SoapFault $soapFault) {
            throw new ViesException($soapFault->getMessage(), $soapFault->getCode());
        }

        return (array)$response;
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
