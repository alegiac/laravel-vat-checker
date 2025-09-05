<?php

namespace Alegiac\LaravelVatChecker;

use Alegiac\LaravelVatChecker\Format\LaravelVatFormatChecker;
use Alegiac\LaravelVatChecker\Vies\Client;

class LaravelVatChecker
{
    public function check($vatNumber):array
    {
        $response = new LaravelVatCheckerResponse();

        $formatter = new LaravelVatFormatChecker();
        $client = new Client();
        $isFormatted = $formatter->validateFormat($vatNumber);
        $response->setIsFormatted($isFormatted);

        if ($isFormatted) {
            $viesData = $client->check($vatNumber);
            $response->setIsVies($viesData['valid']);
            if ($viesData['valid'] === true) {
                $response->setDetails($viesData);
            }
        }

        return $response->output();
    }
}
