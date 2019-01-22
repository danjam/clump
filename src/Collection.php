<?php declare(strict_types=1);

namespace danjam\Clump;

use Countable;
use danjam\Clump\Exception\InvalidTypeException;
use danjam\Clump\Exception\ItemExistsException;
use Generator;
use IteratorAggregate;
use JsonSerializable;

/**
 * Class Collection
 *
 * @package danjam\Clump
 */
class Collection implements Countable, IteratorAggregate, JsonSerializable
{
    /** @var string */
    public const ERROR_INVALID_TYPE = 'Invalid type, must be an instance of ';

    /** @var string */
    public const ERROR_ITEM_EXISTS = 'This item already exists in the collection';

    /** @var string */
    private $type;

    /** @var array */
    private $items = [];

    /**
     * Collection constructor.
     *
     * @param string $type
     * @param array  $items
     */
    public function __construct(string $type, array $items = [])
    {
        $this->type = $type;
        $this->addMany($items);
    }

    /**
     * @param mixed $item
     *
     * @throws InvalidTypeException
     */
    private function validateType($item): void
    {
        if (!$item instanceof $this->type) {
            throw new InvalidTypeException(self::ERROR_INVALID_TYPE . $this->type);
        }
    }

    /**
     * @param mixed $item
     *
     * @throws ItemExistsException
     */
    private function validateItemDoesNotExist($item): void
    {
        if (isset($this->items[\spl_object_hash($item)])) {
            throw new ItemExistsException('This item already exists in the collection');
        }
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param mixed $item
     *
     * @return $this
     */
    public function add($item): self
    {
        $this->validateType($item);
        $this->validateItemDoesNotExist($item);

        $this->items[\spl_object_hash($item)] = $item;

        return $this;
    }

    /**
     * @param array $items
     *
     * @return $this
     */
    public function addMany(array $items): self
    {
        foreach ($items as $item) {
            $this->add($item);
        }

        return $this;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return \array_values($this->items);
    }

    /**
     * @return Generator
     */
    public function getIterator(): Generator
    {
        foreach ($this->items as $item) {
            yield $item;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    /**
     * {@inheritdoc}
     */
    public function count(): int
    {
        return \count($this->items);
    }
}
