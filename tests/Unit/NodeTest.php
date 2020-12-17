<?php

namespace Tests\Unit;

use Tests\AbstractTestCase;
use Tests\Factories\NodesFactory;
use WabLab\HashedLinkedList\Node;

class NodeTest extends AbstractTestCase
{

    public function testSingleNodesChainLeftToRight() {
        $firstNode = NodesFactory::createSingleNodesChain(10, true);

        $nextNode = $firstNode;
        $counter = 0;
        do {
            $counter++;
            $nextNode = $nextNode->firstRight();
        } while($nextNode);
        $this->assertEquals(10, $counter);
    }

    public function testMultipleNodesChainLeftToRight() {
        $rootNode = NodesFactory::createMultipleNodesChain(10, true);
        $this->assertEquals(10, $rootNode->countRights());
    }

    public function testHashTreeLeftToRight() {
        $rootNode = NodesFactory::createHashTree(10, true);

        $sha1 = sha1(5);
        $iterationNode = $rootNode;
        for($i = 0; $i < 40; $i++) {
            $iterationNode = $iterationNode->getRight($sha1[$i]);
        }

        $this->assertEquals($sha1, $iterationNode->getPayload());
    }

    public function testLinkedNodeDeleteLeftToRight() {
        $firstNode = NodesFactory::createSingleNodesChain(10, true);

        // now we will delete the middle node
        $current = $firstNode;
        do {
            $current = $current->firstRight();
            if($current->getHash() == 5) {
                $preNode = $current->firstLeft();
                $nextNode = $current->firstRight();

                $current->delete();
                break;
            }
        } while($current);

        $this->assertEquals($preNode->firstRight()->getHash(), $nextNode->getHash());
        $this->assertEquals($nextNode->firstLeft()->getHash(), $preNode->getHash());
    }

    public function testGetAllIterationLeftToRight() {
        $rootNode = NodesFactory::createMultipleNodesChain(10, true);
        $this->assertEquals(10, count($rootNode->getRights()));
    }

    public function testYieldAllIterationLeftToRight() {
        $rootNode = NodesFactory::createMultipleNodesChain(10, true);
        $counter = 0;
        foreach($rootNode->yieldRights() as $node) {
            $counter++;
        }
        $this->assertEquals(10, $counter);
    }






    public function testSingleNodesChainRightToLeft() {
        $firstNode = NodesFactory::createSingleNodesChain(10, false);

        $nextNode = $firstNode;
        $counter = 0;
        do {
            $counter++;
            $nextNode = $nextNode->firstLeft();
        } while($nextNode);
        $this->assertEquals(10, $counter);
    }

    public function testMultipleNodesChainRightToLeft() {
        $rootNode = NodesFactory::createMultipleNodesChain(10, false);
        $this->assertEquals(10, $rootNode->countLefts());
    }

    public function testHashTreeRightToLeft() {
        $rootNode = NodesFactory::createHashTree(10, false);

        $sha1 = sha1(5);
        $iterationNode = $rootNode;
        for($i = 0; $i < 40; $i++) {
            $iterationNode = $iterationNode->getLeft($sha1[$i]);
        }

        $this->assertEquals($sha1, $iterationNode->getPayload());
    }

    public function testLinkedNodeDeleteRightToLeft() {
        $firstNode = NodesFactory::createSingleNodesChain(10, false);

        // now we will delete the middle node
        $current = $firstNode;
        do {
            $current = $current->firstLeft();
            if($current->getHash() == 5) {
                $preNode = $current->firstRight();
                $nextNode = $current->firstLeft();

                $current->delete();
                break;
            }
        } while($current);

        $this->assertEquals($preNode->firstLeft()->getHash(), $nextNode->getHash());
        $this->assertEquals($nextNode->firstRight()->getHash(), $preNode->getHash());
    }

    public function testGetAllIterationRightToLeft() {
        $rootNode = NodesFactory::createMultipleNodesChain(10, false);
        $this->assertEquals(10, count($rootNode->getLefts()));
    }

    public function testYieldAllIterationRightToLeft() {
        $rootNode = NodesFactory::createMultipleNodesChain(10, false);
        $counter = 0;
        foreach($rootNode->yieldLefts() as $node) {
            $counter++;
        }
        $this->assertEquals(10, $counter);
    }




    public function testGetNodeHash() {
        $node = new Node('single_node', 'anything');
        $this->assertEquals('single_node', $node->getHash());
    }












}
