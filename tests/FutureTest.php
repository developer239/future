<?php
namespace MichalJarnot\Tests;

use MichalJarnot\Future;
use PHPUnit\Framework\TestCase;

class FutureTest extends TestCase
{
    /**
     * TODO: Create separate mock object.
     *
     * Mock count variable.
     *
     * @var int
     */
    private $count = 0;

    /**
     * TODO: Create separate mock object.
     *
     * Mock add method.
     *
     * @param $number
     * @return $this
     */
    private function add($number)
    {
        $future = Future::predictFuture(debug_backtrace());

        // Adds to count only if there is a subtract method somewhere in the future
        if (in_array("subtract", $future)) {
            $this->count += $number;
        }

        return $this;
    }

    /**
     * TODO: Create separate mock object.
     *
     * Mock subtract method.
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
     * TODO: Create separate mock object.
     *
     * Mock result method.
     *
     * @return int
     */
    private function result()
    {
        return $this->count;
    }

    public function testIgnoreAddMethod()
    {
        $number = $this->add(5)->result();
        $this->assertEquals(0, $number);
    }

    public function testExecuteAddMethod()
    {
        $number = $this->add(7)->subtract(1)->result();
        $this->assertEquals(6, $number);
    }

    // TODO: Make prediction work on multiple lines
    // public function testExecuteAddMethodMultipleLines()
    // {
    //     $number = $this->add(7)
    //         ->subtract(1)
    //         ->result();
    //     $this->assertEquals(6, $number);
    // }
}