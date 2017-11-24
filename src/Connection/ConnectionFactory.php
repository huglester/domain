<?php

namespace Webas\Domain\Connection;

/**
 * ConnectionFactory
 *
 * @subpackage connection
 * @author     Florian Eckerstorfer <florian@eckerstorfer.co>
 * @copyright  2014 Florian Eckerstorfer
 * @license    http://opensource.org/licenses/MIT The MIT License
 */
class ConnectionFactory
{
    /**
     * Creates a new StreamConnection object.
     *
     * @return StreamConnection
     */
    public function createStreamConnection()
    {
        return new StreamConnection();
    }
}
