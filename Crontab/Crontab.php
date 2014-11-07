<?php

namespace Dedipanel\PHPSeclibWrapperBundle\Crontab;

use Dedipanel\PHPSeclibWrapperBundle\Connection\ConnectionInterface;

class Crontab implements \Countable
{
    /** @var $conn ConnectionInterface */
    private $conn;
    /** @var $items CrontabItem[] */
    private $items;

    public function __construct(ConnectionInterface $conn)
    {
        $this->conn = $conn;
        $this->items = array();

        $this->retrieve();
    }

    public function count()
    {
        return count($this->items);
    }

    public function setItems(array $items = array())
    {
        $this->items= $items;

        return $this;
    }

    public function getItems()
    {
        return $this->items;
    }

    public function addItem(CrontabItem $item)
    {
        $this->items[] = $item;

        return $this;
    }

    public function removeItem(CrontabItem $item)
    {
        $this->items = array_diff($this->items, array($item));

        return $this;
    }

    public function retrieve()
    {
        $items = array();
        $ret   = explode("\n", $this->conn->exec('crontab -l'));

        foreach ($ret AS $line) {
            if (empty($line)) continue;

            list($min, $hour, $dom, $month, $dow, $command) = explode(' ', $line, 6);

            $items[] = new CrontabItem($command, $hour, $min, $dom, $month, $dow);
        }

        $this->items = $items;

        return $this;
    }

    public function update()
    {
        $crontab = strval($this);

        $cmd = 'echo "' . $crontab . '" | crontab -';
        $this->conn->getSSH()->exec($cmd);

        return $this->conn->getSSH()->getExitStatus() == 0;
    }

    public function __toString()
    {
        $crontab = '';

        foreach ($this->items AS $item) {
            $crontab .= strval($item) . "\n";
        }

        return rtrim($crontab, "\n");
    }
}
