<?php

namespace WabLab\HashedLinkedList;


class Node
{

    const DELETE_STRATEGY_UNLINK_ONLY = 'unlink_only';
    const DELETE_STRATEGY_MERGE = 'merge';

    /**
     * @var string
     */
    protected string $hash;

    /**
     * @var Node[]
     */
    protected array $left = [];

    /**
     * @var int
     */
    protected int $leftCount = 0;

    /**
     * @var Node[]
     */
    protected array $right = [];

    /**
     * @var int
     */
    protected int $rightCount = 0;

    /**
     * @var mixed|null
     */
    protected $payload = null;


    public function __construct(string $hash, $payload) {
        $this->hash = $hash;
        $this->payload = $payload;
    }

    public function getHash(): string {
        return $this->hash;
    }


    //
    // PAYLOAD
    //


    public function getPayload() {
        return $this->payload;
    }

    public function setPayload($payload): Node {
        $this->payload = $payload;
        return $this;
    }



    //
    // LEFT
    //


    public function unsetLeft(Node $node): Node {
        if(isset($this->left[$node->hash])) {
            unset($this->left[$node->hash]);
            $this->leftCount--;
        }
        return $this;
    }

    public function setLeft(Node $leftNode): Node {
        if(!isset($this->left[$leftNode->hash])) {
            $this->leftCount++;
        }
        $this->left[$leftNode->hash] = $leftNode;
        return $this;
    }

    public function getLeft(string $hash): ?Node {
        return $this->left[$hash] ?? null;
    }

    public function firstLeft(): ?Node {
        reset($this->left);
        return current($this->left) ?: null;
    }

    public function getLefts(): array {
        return $this->left;
    }

    public function countLefts() {
        return $this->leftCount;
    }

    public function yieldLefts() {
        foreach($this->left as $left) {
            yield $left;
        }
    }


    //
    // RIGHT
    //


    public function unsetRight(Node $node): Node {
        if(isset($this->right[$node->hash])) {
            unset($this->right[$node->hash]);
            $this->rightCount--;
        }
        return $this;
    }

    public function setRight(Node $rightNode): Node {
        if(!isset($this->right[$rightNode->hash])) {
            $this->rightCount++;
        }
        $this->right[$rightNode->hash] = $rightNode;
        return $this;
    }

    public function getRight(string $hash): ?Node {
        return $this->right[$hash] ?? null;
    }

    public function firstRight(): ?Node {
        reset($this->right);
        return current($this->right) ?: null;
    }

    public function getRights(): array {
        return $this->right;
    }

    public function countRights() {
        return $this->rightCount;
    }

    public function yieldRights() {
        foreach($this->right as $right) {
            yield $right;
        }
    }


    //
    // PROCESSES
    //


    public function delete($strategy = self::DELETE_STRATEGY_MERGE) {
        if($strategy == self::DELETE_STRATEGY_MERGE) {
            if($this->left) {
                // assign right to left
                foreach($this->left as $left) {
                    foreach($this->right as $right) {
                        static::chainNodes($left, $right);
                    }
                }
            }
        }

        if($this->left) {
            // unset all lefts
            foreach ($this->left as $left) {
                $left->unsetRight($this);
            }
        }

        if($this->right) {
            // unset all rights
            foreach ($this->right as $right) {
                $right->unsetLeft($this);
            }
        }
    }

    public static function chainNodes(Node $left, Node $right) {
        $left->setRight($right);
        $right->setLeft($left);
    }

}