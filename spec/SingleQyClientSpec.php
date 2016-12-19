<?php

namespace spec\cdcchen\yii\wechat;

use cdcchen\wechat\base\ResponseException;
use cdcchen\wechat\qy\AgentClient;
use cdcchen\wechat\qy\BatchClient;
use cdcchen\wechat\qy\ChatClient;
use cdcchen\wechat\qy\DefaultClient;
use cdcchen\wechat\qy\DepartmentClient;
use cdcchen\wechat\qy\LoginClient;
use cdcchen\wechat\qy\MaterialClient;
use cdcchen\wechat\qy\MediaClient;
use cdcchen\wechat\qy\MessageClient;
use cdcchen\wechat\qy\OAuthClient;
use cdcchen\wechat\qy\ServerClient;
use cdcchen\wechat\qy\ShakeClient;
use cdcchen\wechat\qy\TagClient;
use cdcchen\wechat\qy\UserClient;
use cdcchen\yii\wechat\SingleQyClient;
use cdcchen\yii\wechat\Ticket;
use cdcchen\yii\wechat\Token;
use PhpSpec\ObjectBehavior;
use yii\base\InvalidConfigException;

class SingleQyClientSpec extends ObjectBehavior
{
    private static $validConfig = [
        'corpId' => CORP_ID_VALID,
        'corpSecret' => CORP_SECRET,
        'chatSecret' => CHAT_SECRET,
        'providerSecret' => PROVIDER_SECRET,
        //        'cache' => [
        //            'class' => 'yii\caching\FileCache',
        //            'cachePath' => CACHE_PATH,
        //        ],
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

    public function it_should_throw_invalid_config_exception1()
    {
        $this->shouldThrow(InvalidConfigException::class)->during('__construct');
    }

    public function it_should_throw_invalid_config_exception2()
    {
        $this->beConstructedWith(['corpId' => CORP_ID_VALID]);
        $this->shouldThrow(InvalidConfigException::class)->during('getDefaultToken');
    }

    public function it_should_throw_response_exception()
    {
        $this->beConstructedWith(static::$invalidConfig);
        $this->shouldThrow(ResponseException::class)->during('getDefaultToken');
        $this->shouldThrow(ResponseException::class)->during('getProviderToken');
    }

    public function it_is_get_default_token()
    {
        $this->beConstructedWith(static::$validConfig);
        $this->shouldNotThrow(\Exception::class)->during('getDefaultToken');
        $this->getDefaultToken()->shouldHaveType(Token::class);
    }

    public function it_is_get_js_api_ticket()
    {
        $this->beConstructedWith(static::$validConfig);
        $this->shouldNotThrow(\Exception::class)->during('getJsApiTicket');
        $this->getJsApiTicket()->shouldHaveType(Ticket::class);
    }

    public function it_is_get_chat_token()
    {
        $this->beConstructedWith(static::$validConfig);
        $this->shouldNotThrow(\Exception::class)->during('getChatToken');
        $this->getChatToken()->shouldHaveType(Token::class);
    }

    public function it_is_default_client()
    {
        $this->beConstructedWith(static::$validConfig);
        $this->getDefaultClient()->shouldHaveType(DefaultClient::class);
    }

    public function it_is_agent_client()
    {
        $this->beConstructedWith(static::$validConfig);
        $this->getAgentClient()->shouldHaveType(AgentClient::class);
    }

    public function it_is_batch_client()
    {
        $this->beConstructedWith(static::$validConfig);
        $this->getBatchClient()->shouldHaveType(BatchClient::class);
    }

    public function it_is_department_client()
    {
        $this->beConstructedWith(static::$validConfig);
        $this->getDepartmentClient()->shouldHaveType(DepartmentClient::class);
    }

    public function it_is_user_client()
    {
        $this->beConstructedWith(static::$validConfig);
        $this->getUserClient()->shouldHaveType(UserClient::class);
    }

    public function it_is_login_client()
    {
        $this->beConstructedWith(static::$validConfig);
        $this->getLoginClient()->shouldHaveType(LoginClient::class);
    }

    public function it_is_media_client()
    {
        $this->beConstructedWith(static::$validConfig);
        $this->getMediaClient()->shouldHaveType(MediaClient::class);
    }

    public function it_is_material_client()
    {
        $this->beConstructedWith(static::$validConfig);
        $this->getMaterialClient()->shouldHaveType(MaterialClient::class);
    }

    public function it_is_message_client()
    {
        $this->beConstructedWith(static::$validConfig);
        $this->getMessageClient()->shouldHaveType(MessageClient::class);
    }

    public function it_is_oauth_client()
    {
        $this->beConstructedWith(static::$validConfig);
        $this->getOAuthClient()->shouldHaveType(OAuthClient::class);
    }

    public function it_is_server_client()
    {
        $this->beConstructedWith(static::$validConfig);
        $this->getServerClient()->shouldHaveType(ServerClient::class);
    }

    public function it_is_shake_client()
    {
        $this->beConstructedWith(static::$validConfig);
        $this->getShakeClient()->shouldHaveType(ShakeClient::class);
    }

    public function it_is_tag_client()
    {
        $this->beConstructedWith(static::$validConfig);
        $this->getTagClient()->shouldHaveType(TagClient::class);
    }

    public function it_is_chat_client()
    {
        $this->beConstructedWith(static::$validConfig);
        $this->getChatClient()->shouldHaveType(ChatClient::class);
    }
}
