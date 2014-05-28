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
        $this->setCommand($command);
        $this->setMin($min);
        $this->setHour($hour);
        $this->setDayOfMonth($dayOfMonth);
        $this->setMonth($month);
        $this->setDayOfWeek($dayOfWeek);
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

    public function setMin($min = '*')
    {
        if ($min != '*'
        && ($min < 0 || $min > 59)) {
            throw new \InvalidArgumentException('The minute crontab item parameter must be between 0 and 59.');
        }

        $this->min = $min;

        return $this;
    }

    public function getMin()
    {
        return $this->min;
    }

    public function setHour($hour = '*')
    {
        if ($hour != '*'
        && ($hour < 0 || $hour > 23)) {
            throw new \InvalidArgumentException('The hour crontab item parameter must be between 0 and 23.');
        }

        $this->hour = $hour;

        return $this;
    }

    public function getHour()
    {
        return $this->hour;
    }

    public function setDayOfMonth($dayOfMonth = '*')
    {
        if ($dayOfMonth != '*'
        && ($dayOfMonth < 1 || $dayOfMonth > 31)) {
            throw new \InvalidArgumentException('The day of month crontab item parameter must be between 1 and 31.');
        }

        $this->dayOfMonth = $dayOfMonth;

        return $this;
    }

    public function getDayOfMonth()
    {
        return $this->dayOfMonth;
    }

    public function setMonth($month = '*')
    {
        if ($month != '*'
        && ($month < 1 || $month > 12)) {
            throw new \InvalidArgumentException('Themonth crontab item parameter must be between 1 and 12.');
        }

        $this->month = $month;

        return $this;
    }

    public function getMonth()
    {
        return $this->month;
    }

    public function setDayOfWeek($dayOfWeek = '*')
    {
        if ($dayOfWeek != '*'
        && ($dayOfWeek < 0 || $dayOfWeek > 6)) {
            throw new \InvalidArgumentException('The day of week crontab item parameter must be between 0 and 6.');
        }

        $this->dayOfWeek = $dayOfWeek;

        return $this;
    }

    public function getDayOfWeek()
    {
        return $this->dayOfWeek;
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
