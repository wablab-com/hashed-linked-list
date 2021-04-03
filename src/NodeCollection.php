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

    public function rehash($hash, $newHash): bool {
        if(isset($this->collection[$hash])) {
            // change node hash
            $node = $this->collection[$hash];
            $node->_setHash($newHash);

            // rehash in the current collection hash directory
            unset($this->collection[$hash]);
            $this->collection[$newHash] = $node;

            // rehash in all left nodes' rights
            foreach($node->left()->collection as $leftNode) {
                unset($leftNode->right()->collection[$hash]);
                $leftNode->right()->collection[$newHash] = $node;
            }

            // rehash in all right nodes' lefts
            foreach($node->right()->collection as $rightNode) {
                unset($rightNode->left()->collection[$hash]);
                $rightNode->left()->collection[$newHash] = $node;
            }

            return true;
        }
        return false;
    }

    public function unset(string $hash): bool {
        if(isset($this->collection[$hash])) {
            unset($this->collection[$hash]);
            $this->count--;

            return true;
        }
        return false;
    }

    public function set(Node $node):bool {
        if(!isset($this->collection[$node->getHash()])) {
            $this->count++;
        }
        $this->collection[$node->getHash()] = $node;
        return true;
    }

    public function get(string $hash): ?Node {
        return $this->collection[$hash] ?? null;
    }

    public function first(): ?Node {
        reset($this->collection);
        return current($this->collection) ?: null;
    }

    public function &allRef(): array {
        return $this->collection;
    }

    public function count() {
        return $this->count;
    }

    public function yieldAll()
    {
        foreach($this->collection as $node) {
            yield $node;
        }
    }

}