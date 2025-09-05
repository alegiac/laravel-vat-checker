<?php

namespace Alegiac\LaravelVatChecker;

class LaravelVatCheckerResponse
{

    private bool $isFormatted;
    private bool $isVies;
    private array $details;

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

    public function output(): array
    {
        return [
            'isFormatted' => $this->isFormatted,
            'isVies' => $this->isVies,
            'details' => $this->details !== null ? $this->details : [],
        ];
    }
}
