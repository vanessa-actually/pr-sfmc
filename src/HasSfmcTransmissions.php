<?php

namespace Dmgroup\PrSfmc;

use Carbon\Carbon;
use Dmgroup\PrSfmc\Models\SfmcTransmission;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * @mixin \Illuminate\Database\Eloquent\Model
 *
 */
trait HasSfmcTransmissions
{
    public function sfmcTransmissions(): MorphMany
    {
        return $this->morphMany(sfmcTransmission::class, 'transmittable');
    }

    public function successfullyTransmittedAt(): ?Carbon
    {
        $transmission =  $this->sfmcTransmissions()
            ->where('transmission_status',true)
            ->first();

        return $transmission->created_at ?? null;
    }
}