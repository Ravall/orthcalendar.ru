<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Tests_Lib_TestCaseSimple extends PHPUnit_Framework_TestCase {


  protected function assertDataTime($timeExpected, $timeActual, $sec=0, $mess = '') {
      $tEx = strtotime($timeExpected);
      $tAc = strtotime($timeActual);
      $this->assertTrue(abs($tEx - $tAc) <= $sec, $mess ."\n" . $timeExpected . "\n" . $timeActual);
  }
}

?>
