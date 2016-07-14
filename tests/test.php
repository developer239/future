<?php

require_once __DIR__ . "/../vendor/autoload.php";

class TestClass
{
    private $count = 0;

    public function add($number)
    {
        $future = \MichalJarnot\Future::guessFutureEvents(debug_backtrace());

        // Adds to count only if there is a subtract method somewhere in the future
        if (in_array("subtract", $future)) {
            $this->count += $number;
        }

        return $this;
    }

    public function subtract($number)
    {
        $this->count -= $number;
        return $this;
    }

    public function result()
    {
        return $this->count;
    }
}

$testClass = new TestClass();
$number = $testClass->add(5)->result();

if($number == 0) {
    echo "it is working result is $number";
} else {
    echo "nope ... result is $number it should be 0";
}