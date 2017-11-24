Webas/Domain
============

> Check availability of domain names and get WHOIS information.

Features
--------

- Check availability of domains
- Retrieve WHOIS information of domains
- Support for over 350 TLDs, including new generic TLDs like `.coffee` or `.sexy`
- Compatible with PHP ^7.0

Installation
------------

```shell
$ composer require webas/domain
```

Usage
-----

### Library

The library contains two main classes: `Whois\Client` and `Availability\Client` They require information about WHOIS servers and patterns to match available domains stored in `data/tld.json`.

#### Whois

```php
use Webas\Domain\Connection\ConnectionFactory;
use Webas\Domain\Data\DataLoader;
use Webas\Domain\Whois\Client;

$factory = new ConnectionFactory();
$dataLoader = new DataLoader();
$data = $dataLoader->load(__DIR__.'/data/tld.json');

$client = new Client($factory, $data);

echo $client->query($domainName);
```

#### Availability

To check the availability of a domain name the `Availability\Client` requires an instance of `Whois\Client`.

```php
use Webas\Domain\Connection\ConnectionFactory;
use Webas\Domain\Data\DataLoader;
use Webas\Domain\Whois\Client as WhoisClient;
use Webas\Domain\Availability\Client as AvailabilityClient;

$factory = new ConnectionFactory();
$dataLoader = new DataLoader();
$data = $dataLoader->load(__DIR__.'/data/tld.json');

$whoisClient = new WhoisClient($factory, $data);
$client = new AvailabilityClient($whoisClient, $data);

echo $client->isAvailable($domainName);
```

Changelog
---------

### Version 0.1 (24 Nov 2017)

- Initial release


Author
------

### [Jaroslav Petrusevic](http://www.webas.lt)

- [Twitter](http://twitter.com/huglester)

THANKS
-------

Special thanks for `cocur/domain` ! Awesome package.
