<?php

namespace Alegiac\LaravelVatChecker;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Alegiac\LaravelVatChecker\Skeleton\SkeletonClass
 */
class LaravelVatCheckerFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'laravel-vat-checker';
    }
}
