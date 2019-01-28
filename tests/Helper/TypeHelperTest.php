<?php declare(strict_types=1);

namespace danjam\Clump\tests\Helper;

use danjam\Clump\Exception\InvalidTypeException;
use danjam\Clump\Helper\TypeHelper;
use PHPUnit\Framework\TestCase;

/**
 * Class TypeHelperTest
 *
 * @package danjam\Clump\tests\Helper
 *
 * @SuppressWarnings(StaticAccess)
 */
class TypeHelperTest extends TestCase
{
    /**
     * @return array[]
     */
    public function getValidatorDataProvider(): array
    {
        return [
            'array'    => ['array', 'is_array'],
            'bool'     => ['bool', 'is_bool'],
            'callable' => ['callable', 'is_callable'],
            'float'    => ['float', 'is_float'],
            'int'      => ['int', 'is_int'],
            'Iterable' => ['Iterable', 'is_iterable'],
            'resource' => ['resource', 'is_resource'],
            'string'   => ['string', 'is_string'],
        ];
    }

    /**
     * @dataProvider getValidatorDataProvider
     *
     * @param string $type
     * @param string $validator
     */
    public function testGetValidator(string $type, string $validator): void
    {
        self::assertSame(
            TypeHelper::getValidator($type),
            $validator
        );
    }

    public function testGetValidatorThrowsExceptionOnInvalidType(): void
    {
        $this->expectException(InvalidTypeException::class);
        $this->expectExceptionMessage(TypeHelper::ERROR_INVALID_TYPE);

        TypeHelper::getValidator('INVALID_TYPE');
    }

    public function testGetTypes(): void
    {
        self::assertSame(
            TypeHelper::getValidTypes(),
            [
                'array',
                'bool',
                'callable',
                'float',
                'int',
                'Iterable',
                'resource',
                'string',
            ]
        );
    }
}
