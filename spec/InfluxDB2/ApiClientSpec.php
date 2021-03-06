<?php

namespace spec\InfluxDB2;

use InfluxDB2\ApiClient;
use InfluxDB2\WriteClient;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use InfluxDB2Generated\Configuration;

class ApiClientSpec extends ObjectBehavior
{
    function it_supports_creation_with_a_configuration()
    {
        $configuration = new Configuration();
        $configuration->setHost("my_host:9999");
        $configuration->setAccessToken("my_access_token");

        $this->beConstructedWith($configuration);

        $this->shouldHaveType(ApiClient::class);
        $this->getConfig()->shouldEqual($configuration);
    }

    function it_supports_creation_from_environment()
    {
        $configuration = new Configuration();
        $configuration->setHost("my_host:9999");
        $configuration->setUsername("user");
        $configuration->setPassword("password");

        putenv("INFLUXDB_CLIENT_HOST=my_host:9999");
        putenv("INFLUXDB_CLIENT_USERNAME=user");
        putenv("INFLUXDB_CLIENT_PASSWORD=password");

        $this->beConstructedThrough("createFromEnvironment", []);

        $this->shouldHaveType(ApiClient::class);
        $this->getConfig()->shouldBeLike($configuration);
    }

    function it_supports_creation_with_credentials()
    {
        $configuration = new Configuration();
        $configuration->setHost("my_host:9999");
        $configuration->setUsername("username");
        $configuration->setPassword("password");

        $this->beConstructedThrough("createWithCredentials", ["my_host:9999", "username", "password"]);

        $this->shouldHaveType(ApiClient::class);
        $this->getConfig()->shouldBeLike($configuration);
    }

    function it_creates_a_write_client()
    {
        $configuration = new Configuration();
        $configuration->setHost("my_host:9999");
        $configuration->setAccessToken("my_access_token");

        $this->beConstructedWith($configuration);

        $this->getWriteClient()->shouldHaveType(WriteClient::class);
        $this->getWriteClient()->getConfig()->shouldBeLike($configuration);
    }
}
