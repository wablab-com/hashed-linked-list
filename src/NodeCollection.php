<?php

namespace WabLab\HashedLinkedList;


class NodeCollection
{
    /**
     * @var Node[]
     */
    protected array $collection = [];

    /**
     * @var int
     */
    protected int $count = 0;

    public function isset(string $hash): bool {
        return isset($this->collection[$hash]);
    }

    public function unset(string $hash): bool {
        if(isset($this->collection[$hash])) {
            unset($this->collection[$hash]);
            $this->count--;

            return true;
        }
        return false;
    }

    public function set(Node $node) {
        if(!isset($this->collection[$node->getHash()])) {
            $this->count++;
        }
        $this->collection[$node->getHash()] = $node;
    }

    public function get(string $hash): ?Node {
        return $this->collection[$hash] ?? null;
    }

    public function first(): ?Node {
        reset($this->collection);
        return current($this->collection) ?: null;
    }

    public function all(): array {
        return $this->collection;
    }

    public function &allRef(): array {
        return $this->collection;
    }

    public function count() {
        return $this->count;
    }

}