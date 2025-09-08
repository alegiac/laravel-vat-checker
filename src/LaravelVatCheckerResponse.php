<?php

namespace Alegiac\LaravelVatChecker;

use Alegiac\LaravelVatChecker\Contracts\VatResponseInterface;

/**
 * Immutable-like response builder for VAT checker payloads.
 */
class LaravelVatCheckerResponse implements VatResponseInterface
{
    private bool $isFormatted = false;
    private bool $isVies = false;
    private array $details = [];
    private bool $isError = false;
    private ?string $errorDescription = null;

    /**
     * Initialize the response with optional initial values.
     *
     * @param bool $isFormatted
     * @param bool $isVies
     * @param array $details
     */
    public function __construct(bool $isFormatted = false, bool $isVies = false, array $details = [])
    {
        $this->isFormatted = $isFormatted;
        $this->isVies = $isVies;
        $this->details = $details;
    }

    /**
     * Set the format validation flag.
     *
     * @param bool $isFormatted
     * @return LaravelVatCheckerResponse
     */
    public function setIsFormatted(bool $isFormatted): LaravelVatCheckerResponse
    {
        $this->isFormatted = $isFormatted;
        return $this;
    }

    /**
     * Set the external validation flag.
     *
     * @param bool $isValid
     * @return LaravelVatCheckerResponse
     */
    public function setIsValid(bool $isValid): LaravelVatCheckerResponse
    {
        $this->isVies = $isValid;
        return $this;
    }

    /**
     * @deprecated Use setIsValid() instead for better naming
     */
    /**
     * Deprecated: prefer setIsValid() naming.
     *
     * @param bool $isVies
     * @return LaravelVatCheckerResponse
     */
    public function setIsVies(bool $isVies): LaravelVatCheckerResponse
    {
        $this->isVies = $isVies;
        return $this;
    }

    /**
     * Set details payload.
     *
     * @param array $details
     * @return LaravelVatCheckerResponse
     */
    public function setDetails(array $details): LaravelVatCheckerResponse
    {
        $this->details = $details;
        return $this;
    }

    /**
     * Set error flag.
     *
     * @param bool $isError
     * @return LaravelVatCheckerResponse
     */
    public function setIsError(bool $isError): LaravelVatCheckerResponse
    {
        $this->isError = $isError;
        return $this;
    }

    /**
     * Set error description.
     *
     * @param string|null $errorDescription
     * @return LaravelVatCheckerResponse
     */
    public function setErrorDescription(?string $errorDescription): LaravelVatCheckerResponse
    {
        $this->errorDescription = $errorDescription;
        return $this;
    }

    /**
     * Build the output payload.
     *
     * @return array
     */
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
