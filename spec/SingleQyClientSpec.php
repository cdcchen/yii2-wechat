<?php

namespace spec\cdcchen\yii\wechat;

use cdcchen\wechat\base\ResponseException;
use cdcchen\yii\wechat\SingleQyClient;
use PhpSpec\ObjectBehavior;
use yii\base\InvalidConfigException;

class SingleQyClientSpec extends ObjectBehavior
{
    private static $validConfig = [
        'corpId' => CORP_ID_VALID,
        'corpSecret' => CORP_SECRET,
        'chatSecret' => CHAT_SECRET,
        'providerSecret' => PROVIDER_SECRET,
    ];

    private static $invalidConfig = [
        'corpId' => CORP_ID_INVALID,
        'corpSecret' => CORP_SECRET,
        'chatSecret' => CHAT_SECRET,
        'providerSecret' => PROVIDER_SECRET,
    ];

    public function it_is_initializable()
    {
        $this->shouldHaveType(SingleQyClient::class);
        $this->beAnInstanceOf(SingleQyClient::class);
        $this->shouldBeAnInstanceOf(SingleQyClient::class);
    }

    public function it_is_initializable_throw()
    {
        $this->shouldThrow(InvalidConfigException::class)->during('__construct');
    }

    public function it_is_get_default_token_throw_invalid_config_exception()
    {
        $this->beConstructedWith(['corpId' => CORP_ID_VALID]);
        $this->shouldThrow(InvalidConfigException::class)->during('getDefaultToken');
    }

    public function it_is_get_default_token_throw_response_exception()
    {
        $this->beConstructedWith(static::$invalidConfig);
        $this->shouldThrow(ResponseException::class)->during('getDefaultToken');
    }

    public function ait_is_get_default_token()
    {
        $this->beConstructedWith(static::$validConfig);
        $this->getDefaultToken()->shouldBeString();
    }
}
