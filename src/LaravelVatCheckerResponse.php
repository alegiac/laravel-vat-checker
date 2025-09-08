<?php

namespace Alegiac\LaravelVatChecker;

use Alegiac\LaravelVatChecker\Contracts\VatResponseInterface;

class LaravelVatCheckerResponse implements VatResponseInterface
{
    private bool $isFormatted = false;
    private bool $isVies = false;
    private array $details = [];
    private bool $isError = false;
    private ?string $errorDescription = null;

    public function __construct(bool $isFormatted = false, bool $isVies = false, array $details = [])
    {
        $this->isFormatted = $isFormatted;
        $this->isVies = $isVies;
        $this->details = $details;
    }

    public function setIsFormatted(bool $isFormatted): LaravelVatCheckerResponse
    {
        $this->isFormatted = $isFormatted;
        return $this;
    }

    public function setIsValid(bool $isValid): LaravelVatCheckerResponse
    {
        $this->isVies = $isValid;
        return $this;
    }

    /**
     * @deprecated Use setIsValid() instead for better naming
     */
    public function setIsVies(bool $isVies): LaravelVatCheckerResponse
    {
        $this->isVies = $isVies;
        return $this;
    }

    public function setDetails(array $details): LaravelVatCheckerResponse
    {
        $this->details = $details;
        return $this;
    }

    public function setIsError(bool $isError): LaravelVatCheckerResponse
    {
        $this->isError = $isError;
        return $this;
    }

    public function setErrorDescription(?string $errorDescription): LaravelVatCheckerResponse
    {
        $this->errorDescription = $errorDescription;
        return $this;
    }

    public function output(): array
    {
        return [
            'isFormatted' => $this->isFormatted,
            'isVies' => $this->isVies,
            'isError' => $this->isError,
            'errorDescription' => $this->errorDescription,
            'details' => $this->details !== null ? $this->details : [],
        ];
    }
}
