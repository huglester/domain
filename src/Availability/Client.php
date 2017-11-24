<?php

namespace Webas\Domain\Availability;

use Webas\Domain\Data\Data;
use Webas\Domain\Domain;
use Webas\Domain\Whois\Client as WhoisClient;

/**
 * Client
 *
 * @subpackage availability
 * @author     Florian Eckerstorfer <florian@eckerstorfer.co>
 * @copyright  2014 Florian Eckerstorfer
 * @license    http://opensource.org/licenses/MIT The MIT License
 */
class Client
{

    /** @var WhoisClient */
    private $whoisClient;

    /** @var Data */
    private $data;

    /** @var string */
    private $lastWhoisResult;

    /**
     * Constructor.
     *
     * @param WhoisClient $whoisClient WhoisClient object
     * @param Data $data Data object
     */
    public function __construct(WhoisClient $whoisClient, Data $data)
    {
        $this->whoisClient = $whoisClient;
        $this->data = $data;
    }

    /**
     * Returns the WhoisClient object.
     *
     * @return WhoisClient
     */
    public function getWhoisClient()
    {
        return $this->whoisClient;
    }

    /**
     * Returns the Data object.
     *
     * @return Data
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Returns if the given domain is available.
     *
     * @param Domain|string $domain Domain.
     * @return bool `true` if the domain is available, `false` if not.
     *
     * @throws AvailabilityException when no pattern exists for the given TLD.
     * @throws \Exception
     */
    public function isAvailable($domain)
    {
        if (false === ($domain instanceof Domain)) {
            $domain = Domain::create($domain);
        }

        $data = $this->data->getByTld($domain->getTld());
        $domainLength = mb_strlen($domain->getDomainNameNoTld());

        if (isset($data['lengthMin']) and $data['lengthMin'] > $domainLength) {
            return false;
            throw new \Exception(sprintf(
                'Domain name %s - length to small',
                $domain->getDomainName()
            ));
        }

        if (isset($data['lengthMax']) and $data['lengthMax'] < $domainLength) {
            return false;
            throw new \Exception(sprintf(
                'Domain name %s - length to big',
                $domain->getDomainName()
            ));
        }

        if (false === isset($data['patterns']['notRegistered'])) {
            throw new AvailabilityException(
                sprintf('No pattern exists to check availability of %s domains.', $domain->getTld())
            );
        }

        $this->lastWhoisResult = $this->getWhoisClient()->query($domain);

        if (preg_match($data['patterns']['notRegistered'], $this->lastWhoisResult)) {
            return true;
        }

        return false;
    }

    /**
     * Returns the WHOIS result from the last call to `isAvailable()`.
     *
     * @return string WHOIS result
     */
    public function getLastWhoisResult()
    {
        return $this->lastWhoisResult;
    }
}
