<?php

namespace Webas\Domain\Connection;

/**
 * ConnectionInterface
 *
 * @subpackage connection
 * @author     Florian Eckerstorfer <florian@eckerstorfer.co>
 * @copyright  2014 Florian Eckerstorfer
 * @license    http://opensource.org/licenses/MIT The MIT License
 */
interface ConnectionInterface
{
    /**
     * Opens a connection.
     *
     * @param string $hostname Hostname
     * @param integer $port Port
     *
     * @return ConnectionInterface
     */
    public function open($hostname, $port);

    /**
     * Reads from the connection.
     *
     * @param integer $length Number of bytes to read; if `null` all bytes are read.
     *
     * @return string String read from the connection.
     *
     * @throws ConnectionException if the connection is not open.
     */
    public function read($length = null);

    /**
     * Writes to the connection.
     *
     * @param string $string String to write to the connection.
     *
     * @return ConnectionInterface
     */
    public function write($string);

    /**
     * Closes the connection.
     *
     * @return ConnectionInterface
     *
     * @throws ConnectionException if the connection is not open.
     */
    public function close();
}
