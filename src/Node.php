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
    public function left():NodeCollection
    {
        return $this->left;
    }


    //
    // RIGHT
    //

    public function right():NodeCollection
    {
        return $this->right;
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
            $left->right()->unset($this->hash);
        }

        // unset all rights
        foreach ($this->right->allRef() as $right) {
            $right->left()->unset($this->hash);
        }
    }

    public static function chainNodes(Node $left, Node $right) {
        $left->right()->set($right);
        $right->left()->set($left);
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
