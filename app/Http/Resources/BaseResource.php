<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Resources Base Class
 *
 * @category Resources
 * @package  App\Http\Resources
 * @author   Pascual Pérez <pasperezn@gmail.com>
 * @created  2018-04-04
 */
class BaseResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request Request instance
     *
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
