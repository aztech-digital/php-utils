<?php

namespace Aztech\Util\Collections;

class TypedCollectionTest extends \PHPUnit_Framework_TestCase
{

    public function getNonStdClassItems()
    {
        /* @f:off */
        return array(array('string'), array(12345), array(new \SplObjectStorage()));
        /* @f:on */
    }

    /**
     * @dataProvider getNonStdClassItems
     * @expectedException \InvalidArgumentException
     */
    public function testAddObjectRejectsInvalidItems($item)
    {
        $collection = new TypedCollection('\stdClass');

        $collection->addObject($item);
    }

    /**
     * @dataProvider getNonStdClassItems
     * @expectedException \InvalidArgumentException
     */
    public function testRemoveObjectRejectsInvalidItems($item)
    {
        $collection = new TypedCollection('\stdClass', $this->getStdClassItems()[0]);

        $collection->removeObject($item);
    }

    public function getStdClassItems()
    {
        return array(
            array(array(
                new \stdClass(),
                new \stdClass()
            ))
        );
    }

    /**
     * @dataProvider getStdClassItems
     */
    public function testAddObjectAcceptsValidItems($items)
    {
        $collection = new TypedCollection('\stdClass');

        foreach ($items as $item) {
            $collection->addObject($item);
        }

        $iterated = array();

        foreach ($collection as $item) {
            $iterated[] = $item;
        }

        $diff = array_udiff($items, $iterated, function ($a, $b) {
            return $a === $b;
        });

        $this->assertEmpty($diff);
    }

    /**
     *
     * @dataProvider getStdClassItems
     */
    public function testRemoveObjectRemovesValidItems($items)
    {
        $collection = new TypedCollection('\stdClass', $items);

        foreach ($items as $item) {
            $collection->removeObject($item);
        }

        $iterated = array();

        foreach ($collection as $item) {
            $iterated[] = $item;
        }

        $this->assertEmpty($iterated);
    }

    /**
     *
     * @dataProvider getStdClassItems
     */
    public function testAddRangeAddsAllItems($items)
    {
         $collection = new TypedCollection('\stdClass');

         $collection->addRange($items);

         $iterated = array();

         foreach ($collection as $item) {
             $iterated[] = $item;
         }

         $this->assertSame($items, $iterated);
    }

    /**
     *
     * @dataProvider getStdClassItems
     */
    public function testRemovesRangeRemoveAllItems($items)
    {
        $collection = new TypedCollection('\stdClass', $items);

        $collection->removeRange($items);

        $iterated = array();

        foreach ($collection as $item) {
            $iterated[] = $item;
        }

        $this->assertEmpty($iterated);
    }


}
