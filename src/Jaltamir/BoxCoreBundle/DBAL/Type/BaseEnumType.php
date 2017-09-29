<?php

namespace Jaltamir\BoxCoreBundle\DBAL\Type;

use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;

abstract class BaseEnumType extends Type
{
    protected $name;
    protected $values = [];

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        $values = array_map(
            function ($val) {
                return "'".$val."'";
            },
            $this->values
        );

        return "ENUM(".implode(", ", $values).")";
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return $value;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        $valuesToCheck = [];
        if (is_array($value))
        {
            foreach ($value as $item)
            {
                $valuesToCheck[] = $item;
            }
        }
        else
        {
            $valuesToCheck[] = $value;
        }

        foreach ($valuesToCheck as $itemToCheck)
        {
            if (!in_array($itemToCheck, $this->values)) {
                throw new \InvalidArgumentException("Invalid '".$this->name."' value.");
            }
        }

        return $value;
    }

    public function getName()
    {
        return $this->name;
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform)
    {
        return true;
    }
}
