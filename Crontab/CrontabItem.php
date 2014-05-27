<?php

namespace Dedipanel\PHPSeclibWrapperBundle\Crontab;

class CrontabItem
{
    /** @var $command string */
    private $command;
    /** @var $min string Minutes: 0-59 */
    private $min;
    /** @var $hour string Hour: 0-23 */
    private $hour;
    /** @var $dayOfMonth string Day of month: 1-31 */
    private $dayOfMonth;
    /** @var $month string Month: 1-12 */
    private $month;
    /** @var $dayOfWeek string Day of week: 1-7 */
    private $dayOfWeek;

    public function __construct(
        $command,
        $hour = '*',
        $min = '*',
        $dayOfMonth = '*',
        $month = '*',
        $dayOfWeek = '*'
    )
    {
        $this->command = $command;
        $this->min = $min;
        $this->hour = $hour;
        $this->dayOfMonth = $dayOfMonth;
        $this->month = $month;
        $this->dayOfWeek = $dayOfWeek;
    }

    public function setCommand($command)
    {
        $this->command = $command;

        return $this;
    }

    public function getCommand()
    {
        return $this->command;
    }

    public function setMin($min)
    {
        $this->min = $min;

        return $this;
    }

    public function getMin()
    {
        return $this->min;
    }

    public function setHour($hour)
    {
        $this->hour = $hour;

        return $this;
    }

    public function getHour()
    {
        return $this->hour;
    }

    public function setDayOfMonth($dayOfMonth = '*')
    {
        $this->dayOfMonth = $dayOfMonth;

        return $this;
    }

    public function getDayOfMonth()
    {
        return $this->dayOfMonth;
    }

    public function setMonth($month = '*')
    {
        $this->month = $month;

        return $this;
    }

    public function getMonth()
    {
        return $this->month;
    }

    public function setDayOfWeek($dayOfWeek = '*')
    {
        $this->dayOfWeek = $dayOfWeek;

        return $this;
    }

    public function __toString()
    {
        return $this->min . ' ' .
               $this->hour . ' ' .
               $this->dayOfMonth . ' ' .
               $this->month . ' ' .
               $this->dayOfWeek . ' ' .
               $this->command;
    }
}
