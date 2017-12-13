<?php

namespace prgTW\SqlDateTime;

use DateInterval;

/**
 * @author Ton Sharp <66Ton99@gmail.com>
 * @author Tomasz Wójcik <tomasz.prgtw.wojcik@gmail.com>
 * @link   https://gist.github.com/66Ton99/60571ee49bf1906aaa1c
 */
class SqlDateTime extends \DateTime
{
    /** {@inheritdoc} */
    public function setDate($year, $month, $day)
    {
        if (null == $year) {
            $year = $this->format('Y');
        }
        if (null == $month) {
            $month = $this->format('n');
        }
        if (null == $day) {
            $day = $this->format('j');
        }
        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $day         = $day > $daysInMonth ? $daysInMonth : $day;
        $return      = parent::setDate($year, $month, $day);

        return $return;
    }

    /** {@inheritdoc} */
    public function modify($modify)
    {
        $pattern = '/(\s*?[-+]?\s*\d+?\s*?(?:month|year)s?)?/i';
        $modify  = preg_replace_callback(
            $pattern,
            function ($matches) use ($pattern) {
                if (empty($matches[0])) {
                    return;
                }
                $orDay = $this->format('j');
                $this->setDate(null, null, 1);
                if (!parent::modify($matches[0])) {
                    return;
                }
                $this->setDate(null, null, $orDay);

                return;
            },
            $modify
        );
        if ($modify = trim($modify)) {
            return parent::modify($modify);
        }

        return $this;
    }

    /** {@inheritdoc} */
    public function add($interval)
    {
        $format = $this->intervalToString($interval, $interval->invert ? '-' : '+');

        return $this->modify($format);
    }

    /** {@inheritdoc} */
    public function sub($interval)
    {
        $format = $this->intervalToString($interval, $interval->invert ? '+' : '-');

        return $this->modify($format);
    }

    /**
     * @param DateInterval $interval
     * @param string       $sign
     *
     * @return string
     */
    protected function intervalToString(DateInterval $interval, $sign)
    {
        $format = vsprintf(
            '%1$s%2$d years %1$s%3$d months %1$s%4$d days %1$s%5$d hours %1$s%6$d minutes %1$s%7$d seconds',
            [
                $sign,
                $interval->y,
                $interval->m,
                $interval->d,
                $interval->h,
                $interval->i,
                $interval->s,
            ]
        );

        return $format;
    }
}
