<?php

namespace Webas\Domain\Whois;

use Webas\Domain\Connection\ConnectionException;
use Webas\Domain\Connection\ConnectionFactory;
use Webas\Domain\Data\Data;
use Webas\Domain\Data\DataException;
use Webas\Domain\Domain;

/**
 * Client
 *
 * @package    cocur/domain
 * @subpackage whois
 * @author     Florian Eckerstorfer <florian@eckerstorfer.co>
 * @copyright  2014 Florian Eckerstorfer
 * @license    http://opensource.org/licenses/MIT The MIT License
 */
class Client
{
    /** @var ConnectionFactory */
    private $factory;

    /** @var Data */
    private $data;

    /**
     * Constructor.
     *
     * @param ConnectionFactory $factory
     * @param Data $data
     *
     * @codeCoverageIgnore
     */
    public function __construct(ConnectionFactory $factory, Data $data)
    {
        $this->factory = $factory;
        $this->data = $data;
    }

    /**
     * Queries the WHOIS server for the given domain name.
     *
     * @param Domain|string $domain Domain name.
     *
     * @return string Result of the WHOIS server.
     *
     * @throws WhoisException when the TLD does not exist.
     * @throws WhoisException when the connection to the WHOIS server failed.
     * @throws QuotaExceededException if the quota for the WHOIS server is exceeded.
     */
    public function query($domain)
    {
        if (false === $domain instanceof Domain) {
            $domain = Domain::create($domain);
        }
        $tld = $domain->getTld();

        try {
            $data = $this->data->getByTld($tld);
        } catch (DataException $e) {
            throw new WhoisException(sprintf('The TLD "%s" does not exist.', $tld), 0, $e);
        }

        $connection = $this->factory->createStreamConnection();
        try {
            $connection->open($data['whoisServer'], 43);
            $connection->write(sprintf("%s\r\n", $domain->getDomainName()));
            $whois = $connection->read();
            $connection->close();
        } catch (ConnectionException $e) {
            throw new WhoisException(sprintf('Could not query WHOIS for "%s".', $domain->getDomainName()), 0, $e);
        }

        if (0 === strlen(trim($whois))) {
            throw new WhoisException(sprintf('Retrieved empty WHOIS for "%s".', $domain->getDomainName()));
        }

        if (true === isset($data['patterns']['quotaExceeded']) &&
            preg_match($data['patterns']['quotaExceeded'], $whois)) {
            throw new QuotaExceededException(sprintf('Quota exceeded for WHOIS server "%s".', $data['whoisServer']));
        }
        if (true === isset($data['patterns']['waitPeriod']) &&
            true === isset($data['waitPeriod']) &&
            preg_match($data['patterns']['waitPeriod'], $whois)) {
            sleep($data['waitPeriod']);

            return $this->query($domain);
        }

        return $whois;
    }
}
