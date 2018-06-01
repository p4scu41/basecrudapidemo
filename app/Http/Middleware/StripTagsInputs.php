<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\TrimStrings as Middleware;
use p4scu41\BaseCRUDApi\Support\StringSupport;
use p4scu41\BaseCRUDApi\Support\ArraySupport;

class StripTagsInputs extends Middleware
{
    /**
     * The names of the attributes that should not be trimmed.
     *
     * @var array
     */
    protected $except = [
        'password',
        'password_confirmation',
    ];

    /**
     * Transform the given value.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return mixed
     */
    protected function transform($key, $value)
    {
        if (in_array($key, $this->except, true)) {
            return $value;
        }

        return is_string($value) ? StringSupport::stripTags($value) : (
            is_array($value) ? ArraySupport::stripTags($value) : $value
        );
    }
}
