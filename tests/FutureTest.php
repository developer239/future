<?php
namespace MichalJarnot\Tests;

use PHPUnit\Framework\TestCase;

class FutureTest extends TestCase
{
    /**
     * Fakes count variable.
     *
     * @var int
     */
    private $count = 0;

    /**
     * Fakes add method.
     *
     * @param $number
     * @return $this
     */
    private function add($number)
    {
        $future = \MichalJarnot\Future::predictFuture(debug_backtrace());

        // Adds to count only if there is a subtract method somewhere in the future
        if (in_array("subtract", $future)) {
            $this->count += $number;
        }

        return $this;
    }

    /**
     * Fakes subtract method.
     *
     * @param $number
     * @return $this
     */
    private function subtract($number)
    {
        $this->count -= $number;
        return $this;
    }

    /**
     * Fakes result method.
     *
     * @return int
     */
    private function result()
    {
        return $this->count;
    }

    public function testCanBePredicted()
    {
        $number = $this->add(5)->result();

        // Assert
        $this->assertEquals(0, $number);
    }
}