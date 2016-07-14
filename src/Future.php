<?php

namespace MichalJarnot;

/**
 * This class was created as a joke with my colleagues at work. It is supposed to
 * return names of all methods that are chained on some particular method.
 *
 * Lets say that you have something like $count = $totalCount->add(5)->subtract(3)->divide(2)->result()
 *
 * Now if you want to know what methods will follow when method add() is executed
 * simply call \MichalJarnot\Future::predictFuture(). You will get array filled with the information
 * you need.
 *
 * Class Future
 * @package MichalJarnot
 */
class Future
{
    /**
     * Returns array of substrings from a string.
     *
     * @param $string
     * @param $start
     * @param $end
     * @return array
     */
    private static function get_stuff_between($string, $start, $end)
    {
        $split_string = explode($end, $string);
        $return = array();
        foreach ($split_string as $data) {
            $str_pos = strpos($data, $start);
            $last_pos = strlen($data);
            $capture_len = $last_pos - $str_pos;
            $return[] = substr($data, $str_pos, $capture_len);
        }
        return $return;
    }

    /**
     * Returns array of methods that are chained on method that called this.
     *
     * @param array $debug_backtrace
     * @return array
     */
    public static function predictFuture($debug_backtrace)
    {
        // Get debug backtrace of a calling method
        $debug_backtrace = $debug_backtrace[0];

        // Get target line
        $file = file($debug_backtrace["file"]);
        $target_line = $file[($debug_backtrace["line"] - 1)];

        // Start on target line from a calling method
        $start = $debug_backtrace["function"] . "(" . implode(", ", $debug_backtrace["args"]) . ")";

        // End with ; lets just suppose that people do not use break key
        $end = ";";

        // Get the chain of the future
        $target_line_substring = self::get_stuff_between($target_line, $start, $end);
        $target_line_substring = $target_line_substring[0];

        // Get all the future methods
        $future_methods = explode("->", trim(preg_replace('/\s*\([^)]*\)/', '', $target_line_substring)));

        return $future_methods;
    }

}