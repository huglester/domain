<?php

namespace Webas\Domain\Test;

use PHPUnit\Framework\TestCase;
use ReflectionClass;
use Webas\Domain\Availability\Client as AvailabilityClient;
use Webas\Domain\Connection\ConnectionFactory;
use Webas\Domain\Data\DataLoader;
use Webas\Domain\Whois\Client as WhoisClient;

abstract class GenericTest extends TestCase
{

    protected $domainAvailable = 'googlegooglegg';
    protected $domainRegistered = 'google';
    protected $tld;

    /**
     * @var AvailabilityClient
     */
    protected $client;

    public function setUp()
    {
        parent::setUp();

        $this->tld = $this->getTldFromClass();

        $factory = new ConnectionFactory;
        $dataLoader = new DataLoader;
        $data = $dataLoader->load(__DIR__.'/../data/tld.json');

        $whoisClient = new WhoisClient($factory, $data);
        $this->client = new AvailabilityClient($whoisClient, $data);
    }

    /** @test */
    public function available()
    {
        $this->assertTrue(
            $this->client->isAvailable(
                $this->domainAvailable.'.'.$this->tld
            )
        );
    }

    /** @test */
    public function registered()
    {
        $this->assertFalse(
            $this->client->isAvailable(
                $this->domainRegistered.'.'.$this->tld
            )
        );
    }

    protected function getTldFromClass()
    {
        $reflect = new ReflectionClass($this);
        $className = substr($reflect->getShortName(), 0, -4);

        // Explode CoUk to .Co.Uk
        $result = ltrim(preg_replace("([A-Z])", ".$0", $className), '.');

        return strtolower($result);
    }
}
