<?php

namespace Jaltamir\BoxCoreBundle\DBAL\Type;

use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;

class WeekDayEnumType extends BaseEnumType
{
    protected $name = 'WeekDayEnumType';
    protected $values = [0, 1, 2, 3, 4, 5, 6];
}