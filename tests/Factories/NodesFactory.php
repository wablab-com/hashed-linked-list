<?php

namespace Tests\Factories;

use WabLab\HashedLinkedList\Node;

class NodesFactory
{
    public static function createSingleNodesChain($count, $ltr = true) : Node
    {
        $firstNode = null;
        for($nodeHash = 1; $nodeHash <= $count; $nodeHash++) {
            if(!$firstNode) {
                $firstNode = new Node($nodeHash, "Node Number #{$nodeHash}");
                $preNode = $firstNode;
            } else {
                $node = new Node($nodeHash, "Node Number #{$nodeHash}");
                if($ltr) {
                    Node::chainNodes($preNode, $node);
                } else {
                    Node::chainNodes($node, $preNode);
                }

                $preNode = $node;
            }
        }
        return $firstNode;
    }


    public static function createMultipleNodesChain($count, $ltr = true) : Node
    {
        $rootNode = new Node('root', null);
        for($nodeHash = 1; $nodeHash <= $count; $nodeHash++) {
            $node = new Node($nodeHash, "Node Number #{$nodeHash}");
            if($ltr) {
                Node::chainNodes($rootNode, $node);
            } else {
                Node::chainNodes($node, $rootNode);
            }

        }
        return $rootNode;
    }


    public static function createHashTree($count, $ltr = true) {
        $rootNode = new Node('root', null);
        $shaLength = 40;
        for($nodeHash = 1; $nodeHash <= $count; $nodeHash++) {
            $sha1 = sha1($nodeHash);

            $firstNode = new Node($sha1[0], null);
            if($ltr) {
                Node::chainNodes($rootNode, $firstNode);
            } else {
                Node::chainNodes($firstNode, $rootNode);
            }

            $preNode = $firstNode;

            for($i = 1; $i < $shaLength; $i++) {
                $node = new Node($sha1[$i], null);
                if($ltr) {
                    Node::chainNodes($preNode, $node);
                } else {
                    Node::chainNodes($node, $preNode);
                }
                $preNode = $node;
            }
            $node->setPayload($sha1);

        }
        return $rootNode;
    }
}
