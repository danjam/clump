<?php declare(strict_types=1);

namespace danjam\Clump\Helper;

use danjam\Clump\Exception\InvalidTypeException;

/**
 * Class TypeHelper
 *
 * @package danjam\Clump\Helper
 */
class TypeHelper
{
    /** @var string */
    public const ERROR_INVALID_TYPE = 'Invalid type, cannot get validator for type ';

    /** @var array */
    private const TYPE_TO_VALIDATOR = [
        'array' => 'is_array',
        'bool' => 'is_bool',
        'callable' => 'is_callable',
        'float' => 'is_float',
        'int' => 'is_int',
        'Iterable' => 'is_iterable',
        'resource' => 'is_resource',
        'string' => 'is_string',
    ];

    /**
     * @param string $type
     *
     * @return string
     *
     * @throws InvalidTypeException
     */
    public static function getValidator(string $type): string
    {
        if (isset(self::TYPE_TO_VALIDATOR[$type])) {
            return self::TYPE_TO_VALIDATOR[$type];
        }

        throw new InvalidTypeException(self::ERROR_INVALID_TYPE . $type);
    }

    /**
     * @return string[]
     */
    public static function getValidTypes(): array
    {
        return array_keys(self::TYPE_TO_VALIDATOR);
    }
}
