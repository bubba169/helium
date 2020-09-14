<?php namespace Helium\Support;

use Helium\Contract\HeliumEntity as HeliumEntityContract;
use Helium\Traits\HeliumEntity;
use Illuminate\Database\Eloquent\Model;

class HeliumEntityModel extends Model implements HeliumEntityContract
{
    use HeliumEntity;
}
