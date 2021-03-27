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
            $nextNode = $nextNode->right()->first();
        } while($nextNode);
        $this->assertEquals(10, $counter);
    }

    public function testMultipleNodesChainLeftToRight() {
        $rootNode = NodesFactory::createMultipleNodesChain(10, true);
        $this->assertEquals(10, $rootNode->right()->count());
    }

    public function testHashTreeLeftToRight() {
        $rootNode = NodesFactory::createHashTree(10, true);

        $sha1 = sha1(5);
        $iterationNode = $rootNode;
        for($i = 0; $i < 40; $i++) {
            $iterationNode = $iterationNode->right()->get($sha1[$i]);
        }

        $this->assertEquals($sha1, $iterationNode->getPayload());
    }

    public function testLinkedNodeDeleteLeftToRight() {
        $firstNode = NodesFactory::createSingleNodesChain(10, true);

        // now we will delete the middle node
        $current = $firstNode;
        do {
            $current = $current->right()->first();
            if($current->getHash() == 5) {
                $preNode = $current->left()->first();
                $nextNode = $current->right()->first();

                $current->delete();
                break;
            }
        } while($current);

        $this->assertEquals($preNode->right()->first()->getHash(), $nextNode->getHash());
        $this->assertEquals($nextNode->left()->first()->getHash(), $preNode->getHash());
    }

    public function testGetAllIterationLeftToRight() {
        $rootNode = NodesFactory::createMultipleNodesChain(10, true);
        $this->assertEquals(10, count($rootNode->right()->allRef()));
    }

    public function testYieldAllIterationLeftToRight() {
        $rootNode = NodesFactory::createMultipleNodesChain(10, true);
        $counter = 0;
        foreach($rootNode->right()->yieldAll() as $node) {
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
            $nextNode = $nextNode->left()->first();
        } while($nextNode);
        $this->assertEquals(10, $counter);
    }

    public function testMultipleNodesChainRightToLeft() {
        $rootNode = NodesFactory::createMultipleNodesChain(10, false);
        $this->assertEquals(10, $rootNode->left()->count());
    }

    public function testHashTreeRightToLeft() {
        $rootNode = NodesFactory::createHashTree(10, false);

        $sha1 = sha1(5);
        $iterationNode = $rootNode;
        for($i = 0; $i < 40; $i++) {
            $iterationNode = $iterationNode->left()->get($sha1[$i]);
        }

        $this->assertEquals($sha1, $iterationNode->getPayload());
    }

    public function testLinkedNodeDeleteRightToLeft() {
        $firstNode = NodesFactory::createSingleNodesChain(10, false);

        // now we will delete the middle node
        $current = $firstNode;
        do {
            $current = $current->left()->first();
            if($current->getHash() == 5) {
                $preNode = $current->right()->first();
                $nextNode = $current->left()->first();

                $current->delete();
                break;
            }
        } while($current);

        $this->assertEquals($preNode->left()->first()->getHash(), $nextNode->getHash());
        $this->assertEquals($nextNode->right()->first()->getHash(), $preNode->getHash());
    }

    public function testGetAllIterationRightToLeft() {
        $rootNode = NodesFactory::createMultipleNodesChain(10, false);
        $this->assertEquals(10, count($rootNode->left()->allRef()));
    }

    public function testYieldAllIterationRightToLeft() {
        $rootNode = NodesFactory::createMultipleNodesChain(10, false);
        $counter = 0;
        foreach($rootNode->left()->yieldAll() as $node) {
            $counter++;
        }
        $this->assertEquals(10, $counter);
    }




    public function testGetNodeHash() {
        $node = new Node('single_node', 'anything');
        $this->assertEquals('single_node', $node->getHash());
    }


    public function testIsSetLeftAndRight() {
        $ltrRootNode = NodesFactory::createMultipleNodesChain(10);
        $rtlRootNode = NodesFactory::createMultipleNodesChain(10, false);

        $this->assertTrue($ltrRootNode->right()->isset(5));
        $this->assertFalse($ltrRootNode->right()->isset(20));

        $this->assertTrue($rtlRootNode->left()->isset(5));
        $this->assertFalse($rtlRootNode->left()->isset(20));
    }


    public function testReHash() {
        $ltrRootNode = NodesFactory::createMultipleNodesChain(10);
        $ltrRootNode->right()->rehash(5,'new-hash');

        $this->assertNull($ltrRootNode->right()->get(5));
        $this->assertEquals('Node Number #5', $ltrRootNode->right()->get('new-hash')->getPayload());
        $this->assertFalse($ltrRootNode->right()->rehash('invalid-hash','new-hash'));


        $rtlRootNode = NodesFactory::createMultipleNodesChain(10, false);
        $rtlRootNode->left()->rehash(5,'new-hash');

        $this->assertNull($rtlRootNode->left()->get(5));
        $this->assertEquals('Node Number #5', $rtlRootNode->left()->get('new-hash')->getPayload());
        $this->assertFalse($rtlRootNode->left()->rehash('invalid-hash','new-hash'));
    }


    public function testUnsetInvalidHash() {
        $ltrRootNode = NodesFactory::createMultipleNodesChain(10);
        $this->assertFalse($ltrRootNode->left()->unset('invalid-hash'));
    }


    public function testHashTree() {
        $ltrRootNode = NodesFactory::createMultipleNodesChain(10);
        $this->assertStringContainsString('Hash: 10 ->', $ltrRootNode->hashTree());

        $rtlRootNode = NodesFactory::createMultipleNodesChain(10, false);
        $this->assertStringContainsString('Hash: 10 ->', $rtlRootNode->hashTree(false));
    }



}
