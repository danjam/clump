<?php declare(strict_types=1);

namespace danjam\Clump\tests;

use danjam\Clump\Collection;
use danjam\Clump\Exception\InvalidTypeException;
use danjam\Clump\Exception\ItemExistsException;
use DateTime;
use PHPUnit\Framework\TestCase;
use stdClass;

/**
 * Class CollectionTest
 */
class CollectionTest extends TestCase
{
    /**
     * @return stdClass[]
     */
    private function getTestItems(): array
    {
        // casting array to object creates stdClass
        return [
            (object) ['property' => 'one'],
            (object) ['property' => 'two'],
            (object) ['property' => 'three'],
        ];
    }

    /**
     * Constructor should populate type
     */
    public function testGetType(): void
    {
        $type = stdClass::class;

        self::assertSame(
            $type,
            (new Collection($type))->getType()
        );
    }

    /**
     * toArray should give indexed array of items
     */
    public function testToArray(): void
    {
        $items = $this->getTestItems();

        self::assertSame(
            $items,
            (new Collection(stdClass::class, $items))->toArray()
        );
    }

    public function testJsonSerialize(): void
    {
        $items = $this->getTestItems();

        self::assertSame(
            json_encode($items),
            json_encode(new Collection(stdClass::class, $items))
        );
    }

    public function testGetIterator(): void
    {
        $items = $this->getTestItems();
        $iterator = (new Collection(stdClass::class, $items))->getIterator();

        $itemsFromIterator = [];
        foreach ($iterator as $iteratorItem) {
            $itemsFromIterator[] = $iteratorItem;
        }

        self::assertSame(
            $items,
            $itemsFromIterator
        );
    }

    /**
     * @depends testToArray
     */
    public function testAdd(): void
    {
        $items = $this->getTestItems();

        self::assertSame(
            $items[0],
            (new Collection(stdClass::class))->add($items[0])->toArray()[0]
        );
    }

    public function testAddWithInvalidItemThrowsException(): void
    {
        self::expectException(InvalidTypeException::class);
        self::expectExceptionMessage(Collection::ERROR_INVALID_TYPE);

        (new Collection(stdClass::class))->add(new DateTime());
    }

    public function testAddWithDuplicateItemThrowsException(): void
    {
        self::expectException(ItemExistsException::class);
        self::expectExceptionMessage(Collection::ERROR_ITEM_EXISTS);

        $item = new stdClass();

        (new Collection(stdClass::class))
            ->add($item)
            ->add($item);
    }

    /**
     * @depends testToArray
     */
    public function testAddMany(): void
    {
        $items = $this->getTestItems();

        self::assertSame(
            $items,
            (new Collection(stdClass::class))->addMany($items)->toArray()
        );
    }

    public function testCount(): void
    {
        $items = $this->getTestItems();

        self::assertSame(
            count($items),
            count(new Collection(stdClass::class, $items))
        );
    }
}
