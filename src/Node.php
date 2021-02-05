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
     * @var NodeCollection
     */
    protected NodeCollection $left;

    /**
     * @var NodeCollection
     */
    protected NodeCollection $right;

    /**
     * @var mixed|null
     */
    protected $payload = null;


    public function __construct(string $hash, $payload) {
        $this->hash = $hash;
        $this->payload = $payload;
        $this->left = new NodeCollection();
        $this->right = new NodeCollection();
    }

    public function getHash(): string {
        return $this->hash;
    }

    /**
     * WARNING: don't use this function to rehash the node, you can use rehash function instead
     * @param string $hash
     */
    public function _setHash(string $hash) {
        $this->hash = $hash;
    }


    //
    // PAYLOAD
    //


    public function getPayload() {
        return $this->payload;
    }

    public function setPayload($payload): bool {
        $this->payload = $payload;
        return true;
    }



    //
    // LEFT
    //

    public function issetLeft(string $hash): bool {
        return $this->left->isset($hash);
    }

    public function unsetLeft(string $hash): bool {
        return $this->left->unset($hash);
    }

    public function setLeft(Node $leftNode): bool {
        return $this->left->set($leftNode);
    }

    public function getLeft(string $hash): ?Node {
        return $this->left->get($hash);
    }

    public function firstLeft(): ?Node {
        return $this->left->first();
    }

    public function getLefts(): array {
        return $this->left->allRef();
    }

    public function countLefts() {
        return $this->left->count();
    }

    public function yieldLefts() {
        $leftRef = &$this->left->allRef();
        foreach($leftRef as $left) {
            yield $left;
        }
    }

    public function rehashLeft($hash, $newHash):bool {
        return $this->left->rehash($hash, $newHash);
    }


    //
    // RIGHT
    //

    public function issetRight(string $hash): bool {
        return $this->right->isset($hash);
    }

    public function unsetRight(string $hash): bool {
        return $this->right->unset($hash);
    }

    public function setRight(Node $rightNode): bool {
        return $this->right->set($rightNode);
    }

    public function getRight(string $hash): ?Node {
        return $this->right->get($hash);
    }

    public function firstRight(): ?Node {
        return $this->right->first();
    }

    public function getRights(): array {
        return $this->right->allRef();
    }

    public function countRights() {
        return $this->right->count();
    }

    public function yieldRights() {
        $rightRef = $this->right->allRef();
        foreach($rightRef as $right) {
            yield $right;
        }
    }

    public function rehashRight($hash, $newHash):bool {
        return $this->right->rehash($hash, $newHash);
    }


    //
    // PROCESSES
    //


    public function delete($strategy = self::DELETE_STRATEGY_MERGE) {
        if($strategy == self::DELETE_STRATEGY_MERGE) {
            // assign right to left
            foreach($this->left->allRef() as $left) {
                foreach($this->right->allRef() as $right) {
                    static::chainNodes($left, $right);
                }
            }
        }

        // unset all lefts
        foreach ($this->left->allRef() as $left) {
            $left->unsetRight($this->hash);
        }

        // unset all rights
        foreach ($this->right->allRef() as $right) {
            $right->unsetLeft($this->hash);
        }
    }

    public static function chainNodes(Node $left, Node $right) {
        $left->setRight($right);
        $right->setLeft($left);
    }

    public function hashTree($ltr = true): string
    {
        $string = "Hash: {$this->getHash()} -> \n";
        $linkedNodesString = '';
        if($ltr) {
            foreach($this->right->allRef() as $right) {
                $linkedNodesString .= "{$right->hashTree($ltr)}\n";
            }
        } else {
            foreach($this->left->allRef() as $left) {
                $linkedNodesString .= "{$left->hashTree($ltr)}\n";
            }
        }

        return $string.'  '.str_replace("\n", "\n  ", $linkedNodesString);
    }

}