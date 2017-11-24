<?php

require_once 'vendor/autoload.php';

use Webas\Domain\Connection\ConnectionFactory;
use Webas\Domain\Data\DataLoader;
use Webas\Domain\Whois\Client as WhoisClient;
use Webas\Domain\Availability\Client as AvailabilityClient;

$factory = new ConnectionFactory();
$dataLoader = new DataLoader();
$data = $dataLoader->load(__DIR__.'/data/tld.json');

$whoisClient = new WhoisClient($factory, $data);
$client = new AvailabilityClient($whoisClient, $data);

var_dump($client->isAvailable('my.shop'));