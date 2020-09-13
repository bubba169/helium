<?php namespace Helium\Support;

use Helium\Contract\EntityInterface;
use Helium\Traits\HeliumEntity;
use Illuminate\Database\Eloquent\Model;

class Entity extends Model implements EntityInterface
{
    use HeliumEntity;
}
