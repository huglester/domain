<?php

namespace Webas\Domain;

/**
 * Domain
 *
 * @author    Florian Eckerstorfer <florian@eckerstorfer.co>
 * @copyright 2014 Florian Eckerstorfer
 * @license   http://opensource.org/licenses/MIT The MIT License
 */
class Domain
{
    /** @var string */
    private $domainName;

    /**
     * Creates a new instance of Domain.
     *
     * @param string $domainName Domain name.
     *
     * @return Domain Domain object.
     */
    public static function create($domainName = null)
    {
        return new self($domainName);
    }

    /**
     * Constructor.
     *
     * @param string $domainName Domain name.
     */
    public function __construct($domainName = null)
    {
        if (null !== $domainName) {
            $this->setDomainName($domainName);
        }
    }

    /**
     * Sets the domain name.
     *
     * @param string $domainName Domain name.
     *
     * @return Domain
     */
    public function setDomainName($domainName)
    {
        $this->domainName = $domainName;

        return $this;
    }

    /**
     * Returns the domain name.
     *
     * @return string Domain name.
     */
    public function getDomainName()
    {
        return $this->domainName;
    }

    public function getDomainNameNoTld()
    {
        $domain = rtrim($this->getDomainName(), $this->getTld());

        return rtrim($domain, '.');
    }

    /**
     * Returns the TLD of the domain name.
     *
     * @return string TLD of the domain name.
     */
    public function getTld()
    {
        return preg_replace('/(.*)\.([a-z]+)$/', '$2', $this->domainName);
    }
}
