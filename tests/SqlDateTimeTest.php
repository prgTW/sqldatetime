<?php

namespace prgTW\SqlDateTime\Tests;

use DateInterval;
use DateTime;
use prgTW\SqlDateTime\SqlDateTime;
use PHPUnit\Framework\TestCase;

class SqlDateTimeTest extends TestCase
{
    /**
     * @dataProvider provideDataForSetDate
     *
     * @param string $result
     * @param int    $year
     * @param int    $month
     * @param int    $day
     */
    public function testSetDate($result, $year, $month, $day)
    {
        $dateTime = new SqlDateTime;
        $dateTime->setDate($year, $month, $day);
        $this->assertEquals($result, $dateTime->format('Y-m-d'));
    }

    public function provideDataForSetDate()
    {
        $now = new DateTime;

        return [
            ['2014-02-28', 2014, 2, 31],
            ['2014-02-01', 2014, 2, 1],
            ['2014-06-30', 2014, 6, 31],
            [$now->format('Y-m-d'), null, null, null],
        ];
    }

    /**
     * @dataProvider provideDataForModify
     *
     * @param string $date
     * @param string $shift
     * @param string $result
     */
    public function testModify($date, $shift, $result = '2014-02-28')
    {
        $dateTime = new SqlDateTime($date);
        $dateTime->modify($shift);
        $this->assertEquals($result, $dateTime->format('Y-m-d'));
    }

    public function provideDataForModify()
    {
        return [
            ['2014-02-28', '0 day 0 month 0 year'],
            ['2014-01-28', 'next month'],
            ['2014-01-28', 'next months'],
            ['2014-01-02', '+1 month', '2014-02-02'],
            ['2014-01-02', '+  1 month', '2014-02-02'],
            ['2014-01-02', '+ 1 month', '2014-02-02'],
            ['2014-01-31', '1 months'],
            ['2014-01-31', '+2 months', '2014-03-31'],
            ['2014-01-31', ' +   2 months', '2014-03-31'],
            ['2014-01-30', '+3 months', '2014-04-30'],
            ['2014-03-31', ' -   1 month'],
            ['2014-03-31', "\t-   1 month"],
            ['2014-03-31', "\t -\t 1 month"],
            ['2012-02-29', '24 month'],
            ['2012-02-29', '2 years'],
            ['2016-02-29', '-24 months'],
            ['2016-02-29', '-2 years'],
            ['2014-02-27', 'next day'],
            ['2014-02-27', '1 day'],
            ['2014-02-27', '+1 day'],
            ['2013-02-01', '-1 day next month next year'],
            ['2013-01-27', '1 day 1 month 1 year'],
            ['2015-04-01', '-1 day -1 month -1 year'],
            ['2011-12-26', '2 day 2 month 2 year'],
            ['2011-12-31', '2 month 2 year'],
            ['2008-01-31', '0 months', '2008-01-31'],
            ['2008-01-31', '1 months', '2008-02-29'],
            ['2008-01-31', '2 months', '2008-03-31'],
            ['2008-01-31', '3 months', '2008-04-30'],
            ['2008-01-31', '4 months', '2008-05-31'],
            ['2008-01-31', '5 months', '2008-06-30'],
            ['2008-01-31', '6 months', '2008-07-31'],
            ['2008-01-31', '7 months', '2008-08-31'],
            ['2008-01-31', '8 months', '2008-09-30'],
            ['2008-01-31', '9 months', '2008-10-31'],
            ['2008-01-31', '10 months', '2008-11-30'],
            ['2008-01-31', '11 months', '2008-12-31'],
            ['2008-01-31', '12 months', '2009-01-31'],
            ['2008-01-31', '13 months', '2009-02-28'],
            ['2008-01-31', '14 months', '2009-03-31'],
        ];
    }

    /**
     * @dataProvider provideDataForAdd
     *
     * @param string $date
     * @param string $format
     * @param string $expectedResult
     */
    public function testAdd($date, $format, $expectedResult = '2014-02-28')
    {
        $dateTime = new SqlDateTime($date);
        $dateTime->add(new DateInterval($format));
        $this->assertEquals($expectedResult, $dateTime->format('Y-m-d'));
    }

    public function provideDataForAdd()
    {
        return [
            ['2014-02-28', 'PT1S'],
            ['2014-01-28', 'P1M'],
            ['2014-01-02', 'P1M', '2014-02-02'],
            ['2014-01-31', 'P1M'],
            ['2014-01-31', 'P2M', '2014-03-31'],
            ['2014-01-30', 'P3M', '2014-04-30'],
            ['2012-02-29', 'P2Y'],
            ['2012-02-29', 'P24M'],
            ['2014-02-27', 'P1D'],
            ['2013-01-27', 'P1Y1M1D'],
            ['2011-12-26', 'P2Y2M2D'],
            ['2011-12-31', 'P2Y2M'],
            ['2008-01-31', 'P0M', '2008-01-31'],
            ['2008-01-31', 'P1M', '2008-02-29'],
            ['2008-01-31', 'P2M', '2008-03-31'],
            ['2008-01-31', 'P3M', '2008-04-30'],
            ['2008-01-31', 'P4M', '2008-05-31'],
            ['2008-01-31', 'P5M', '2008-06-30'],
            ['2008-01-31', 'P6M', '2008-07-31'],
            ['2008-01-31', 'P7M', '2008-08-31'],
            ['2008-01-31', 'P8M', '2008-09-30'],
            ['2008-01-31', 'P9M', '2008-10-31'],
            ['2008-01-31', 'P10M', '2008-11-30'],
            ['2008-01-31', 'P11M', '2008-12-31'],
            ['2008-01-31', 'P12M', '2009-01-31'],
            ['2008-01-31', 'P13M', '2009-02-28'],
            ['2008-01-31', 'P14M', '2009-03-31'],
        ];
    }

    /**
     * @dataProvider provideDataForSub
     *
     * @param string $date
     * @param string $format
     * @param string $expectedResult
     */
    public function testSub($date, $format, $expectedResult = '2014-02-28')
    {
        $dateTime = new SqlDateTime($date);
        $dateTime->sub(new DateInterval($format));
        $this->assertEquals($expectedResult, $dateTime->format('Y-m-d'));
    }

    public function provideDataForSub()
    {
        return [
            ['2014-03-31', 'P1M'],
            ['2016-02-29', 'P24M'],
            ['2015-04-01', 'P1Y1M1D'],
        ];
    }

}
