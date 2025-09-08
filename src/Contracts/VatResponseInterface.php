<?php

namespace Alegiac\LaravelVatChecker\Contracts;

interface VatResponseInterface
{
    /**
     * Set the format validation result
     */
    public function setIsFormatted(bool $isFormatted): self;

    /**
     * Set the external validation result
     */
    public function setIsValid(bool $isValid): self;

    /**
     * Set the validation details
     */
    public function setDetails(array $details): self;

    /**
     * Get the response as array (maintains backward compatibility)
     */
    public function output(): array;
}
