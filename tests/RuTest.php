<?php

namespace Webas\Domain\Test;

class RuTest extends GenericTest
{

    /**
     * @test
     * @expectedException Exception
     */
    public function it_tests_length_min()
    {
        $this->client->isAvailable('1.ru');
    }

    /**
     * @test
     * @expectedException Exception
     */
    public function it_tests_length_max()
    {
        $this->client->isAvailable('23412341234123412341234182394123049123490812340912834091283409182.ru');
    }
}
