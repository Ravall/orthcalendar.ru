<?php
/*
 * Тест великих празников
 */
require_once realpath(dirname(__FILE__).'/../../../../../') . '/config/init.php';
require_once dirname(__FILE__) . '/Fake/FakeConfigRemark.php';
require_once PATH_TESTS . '/init.php';
require_once PATH_LIBS . '/Mindfly/Date/Christian/Meatfare.php';
require_once PATH_LIBS . '/Mindfly/Date.php';

class Mindfly_Date_Christian_GreatFeastsTest extends PHPUnit_Framework_TestCase {

    public function providerGreatFeast() {
        return array(
            array('orthodoxyEasterDay', '2000', '2000-04-30'),
            array('orthodoxyEasterDay', '2001', '2001-04-15'),
            array('orthodoxyEasterDay', '2002', '2002-05-05'),
            array('orthodoxyEasterDay', '2003', '2003-04-27'),
            array('orthodoxyEasterDay', '2004', '2004-04-11'),
            array('orthodoxyEasterDay', '2005', '2005-05-01'),
            array('orthodoxyEasterDay', '2006', '2006-04-23'),
            array('orthodoxyEasterDay', '2007', '2007-04-08'),
            array('orthodoxyEasterDay', '2008', '2008-04-27'),
            array('orthodoxyEasterDay', '2009', '2009-04-19'),
            array('orthodoxyEasterDay', '2010', '2010-04-04'),
            array('orthodoxyEasterDay', '2011', '2011-04-24'),
            array('orthodoxyEasterDay', '2012', '2012-04-15'),
            array('orthodoxyEasterDay', '2013', '2013-05-05'),
            array('orthodoxyEasterDay', '2014', '2014-04-20'),
            array('orthodoxyEasterDay', '2015', '2015-04-12'),
            array('orthodoxyEasterDay', '2016', '2016-05-01'),
            array('orthodoxyEasterDay', '2017', '2017-04-16'),
            array('orthodoxyEasterDay', '2018', '2018-04-08'),
            array('orthodoxyEasterDay', '2019', '2019-04-28'),
            array('orthodoxyEasterDay', '2020', '2020-04-19'),
            array('catholicEasterDay', '2000',  '2000-04-23'),
            array('catholicEasterDay', '2001', '2001-04-15'),
            array('catholicEasterDay', '2002', '2002-03-31'),
            array('catholicEasterDay', '2003', '2003-04-20'),
            array('catholicEasterDay', '2004', '2004-04-11'),
            array('catholicEasterDay', '2005', '2005-03-27'),
            array('catholicEasterDay', '2006', '2006-04-16'),
            array('catholicEasterDay', '2007', '2007-04-08'),
            array('catholicEasterDay', '2008', '2008-03-23'),
            array('catholicEasterDay', '2009', '2009-04-12'),
            array('catholicEasterDay', '2010', '2010-04-04'),
            array('catholicEasterDay', '2011', '2011-04-24'),
            array('catholicEasterDay', '2012', '2012-04-08'),
            array('catholicEasterDay', '2013', '2013-03-31'),
            array('catholicEasterDay', '2014', '2014-04-20'),
            array('catholicEasterDay', '2015', '2015-04-05'),
            array('catholicEasterDay', '2016', '2016-03-27'),
            array('catholicEasterDay', '2017', '2017-04-16'),
            array('catholicEasterDay', '2018', '2018-04-01'),
            array('catholicEasterDay', '2019', '2019-04-21'),
            array('catholicEasterDay', '2020', '2020-04-12'),
            array('nativityOfTheBlessedVirgin', '2010', '2010-09-21'),
            array('nativityOfTheBlessedVirgin', '2011', '2011-09-21'),
            array('nativityOfTheBlessedVirgin', '2012', '2012-09-21'),
            array('exaltationOfTheCross', '2010', '2010-09-27'),
            array('exaltationOfTheCross', '2011', '2011-09-27'),
            array('exaltationOfTheCross', '2012', '2012-09-27'),
            array('presentationOfTheTheotokos', '2010', '2010-12-04'),
            array('presentationOfTheTheotokos', '2011', '2011-12-04'),
            array('presentationOfTheTheotokos', '2012', '2012-12-04'),
            array('christmas', '2010', '2010-01-07'),
            array('christmas', '2011', '2011-01-07'),
            array('christmas', '2012', '2012-01-07'),
            array('theBaptismOfOurLord', '2010', '2010-01-19'),
            array('theBaptismOfOurLord', '2011', '2011-01-19'),
            array('theBaptismOfOurLord', '2012', '2012-01-19'),
            array('thePresentationOfTheLordInTheTemple', '2010', '2010-02-15'),
            array('thePresentationOfTheLordInTheTemple', '2011', '2011-02-15'),
            array('thePresentationOfTheLordInTheTemple', '2012', '2012-02-15'),
            array('feastOfTheAnnunciation', '2010', '2010-04-07'),
            array('feastOfTheAnnunciation', '2011', '2011-04-07'),
            array('feastOfTheAnnunciation', '2012', '2012-04-07'),
            array('theEntryOfOurLordIntoJerusalem', '2010', '2010-03-28'),
            array('theEntryOfOurLordIntoJerusalem', '2011', '2011-04-17'),
            array('theEntryOfOurLordIntoJerusalem', '2012', '2012-04-08'),
            array('theEntryOfOurLordIntoJerusalem', '2013', '2013-04-28'),
            array('theEntryOfOurLordIntoJerusalem', '2014', '2014-04-13'),
            array('theEntryOfOurLordIntoJerusalem', '2015', '2015-04-05'),
            array('theAscensionOfChrist', '2010', '2010-05-13'),
            array('theAscensionOfChrist', '2011', '2011-06-02'),
            array('theAscensionOfChrist', '2012', '2012-05-24'),
            array('theAscensionOfChrist', '2013', '2013-06-13'),
            array('theAscensionOfChrist', '2014', '2014-05-29'),
            array('theAscensionOfChrist', '2015', '2015-05-21'),
            array('whitsundayDay', '2010', '2010-05-23'),
            array('whitsundayDay', '2011', '2011-06-12'),
            array('whitsundayDay', '2012', '2012-06-03'),
            array('whitsundayDay', '2013', '2013-06-23'),
            array('whitsundayDay', '2014', '2014-06-08'),
            array('whitsundayDay', '2015', '2015-05-31'),
            array('theHolyTransfigurationOfOurLordJesusChrist', '2010', '2010-08-19'),
            array('theHolyTransfigurationOfOurLordJesusChrist', '2011', '2011-08-19'),
            array('theHolyTransfigurationOfOurLordJesusChrist', '2012', '2012-08-19'),
            array('dormitionOfTheTheotokos', '2010', '2010-08-28'),
            array('dormitionOfTheTheotokos', '2011', '2011-08-28'),
            array('dormitionOfTheTheotokos', '2012', '2012-08-28'),
            array('theVirginOfMercy', '2010', '2010-10-14'),
            array('theVirginOfMercy', '2011', '2011-10-14'),
            array('theVirginOfMercy', '2012', '2012-10-14'),
            array('theCircumcisionOfChrist', '2010', '2010-01-14'),
            array('theCircumcisionOfChrist', '2011', '2011-01-14'),
            array('theCircumcisionOfChrist', '2012', '2012-01-14'),
            array('theNativityOfStJohntheBaptist', '2010', '2010-07-07'),
            array('theNativityOfStJohntheBaptist', '2011', '2011-07-07'),
            array('theNativityOfStJohntheBaptist', '2012', '2012-07-07'),
            array('peterAndPavelDay', '2010', '2010-07-12'),
            array('peterAndPavelDay', '2011', '2011-07-12'),
            array('peterAndPavelDay', '2012', '2012-07-12'),
            array('beheadingOfStJohnTheBaptist', '2010', '2010-09-11'),
            array('beheadingOfStJohnTheBaptist', '2011', '2011-09-11'),
            array('beheadingOfStJohnTheBaptist', '2012', '2012-09-11'),
        );
    }

    /**
     * @dataProvider providerGreatFeast
     * @param <type> $year год
     * @param <type> $event событие (метод)
     * @param <type> $day ожидаемая дата
     */
    public function testGreatFeast($event, $year, $day) {
        $this->assertEquals(Mindfly_Date_Christian_GreatFeasts::$event($year), $day);
    }

}