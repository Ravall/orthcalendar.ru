<?php
/**
 *
 * @author vsemenov
 */
class Mindfly_Grammar {
    static public function nounDeclension($n, $forms) {
        $n = abs($n) % 100;
        $n1 = $n % 10;
        if ($n > 10 && $n < 20) {
            return $forms[2];
        } elseif ($n1 > 1 && $n1 < 5) {
            return $forms[1];
        } elseif ($n1 == 1) {
            return $forms[0];
        } else {
            return $forms[2];
        }
    }
}