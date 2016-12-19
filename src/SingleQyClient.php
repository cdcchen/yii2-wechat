<?php
/**
 * Created by PhpStorm.
 * User: chendong
 * Date: 16/5/3
 * Time: 19:23
 */

namespace cdcchen\yii\wechat;


use cdcchen\wechat\qy\AgentClient;
use cdcchen\wechat\qy\BatchClient;
use cdcchen\wechat\qy\ChatClient;
use cdcchen\wechat\qy\DefaultClient;
use cdcchen\wechat\qy\DepartmentClient;
use cdcchen\wechat\qy\LoginClient;
use cdcchen\wechat\qy\MaterialClient;
use cdcchen\wechat\qy\MediaClient;
use cdcchen\wechat\qy\MenuClient;
use cdcchen\wechat\qy\MessageClient;
use cdcchen\wechat\qy\OAuthClient;
use cdcchen\wechat\qy\ServerClient;
use cdcchen\wechat\qy\ShakeClient;
use cdcchen\wechat\qy\TagClient;
use cdcchen\wechat\qy\UserClient;
use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\caching\Cache;
use yii\di\Instance;

/**
 * Class SingleQyClient
 * @package cdcchen\yii\wechat
 */
class SingleQyClient extends Component
{
    /**
     * @var string
     */
    public $corpId;
    /**
     * @var string
     */
    public $corpSecret;
    /**
     * @var string
     */
    public $chatSecret;
    /**
     * @var string
     */
    public $providerSecret;

    /**
     * @var \yii\caching\Cache|null
     */
    public $cache;

    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {
        parent::init();

        if (empty($this->corpId)) {
            throw new InvalidConfigException('SingleQyClient::corpId and SingleQyClient::secret must be set.');
        }

        if (!empty($this->cache)) {
            $this->cache = Instance::ensure($this->cache, Cache::className());
        }
    }

    /**
     * @param bool $group
     * @param string|null $secret
     * @return Ticket|null
     */
    public function getJsApiTicket($group = false, $secret = null)
    {
        $client = (new JsApiClient())->setCache($this->cache);
        $token = $this->getDefaultToken($secret);
        return $client->getJsApiTicket($token->value, $group);
    }

    /**
     * @param string|null $secret
     * @return Token|null
     * @throws InvalidConfigException
     */
    public function getDefaultToken($secret = null)
    {
        $secret = $secret ?: $this->corpSecret;
        if (empty($secret)) {
            throw new InvalidConfigException('Corp secret is required.');
        }

        $client = (new TokenClient())->setCache($this->cache);
        return $client->getDefaultToken($this->corpId, $secret);
    }

    /**
     * @return Token|null
     * @throws InvalidConfigException
     */
    public function getChatToken()
    {
        if (empty($this->chatSecret)) {
            throw new InvalidConfigException('SingleQyClient::chatSecret must be set.');
        }

        $client = (new TokenClient())->setCache($this->cache);
        return $client->getDefaultToken($this->corpId, $this->chatSecret);
    }

    /**
     * @return Token|null
     * @throws InvalidConfigException
     */
    public function getProviderToken()
    {
        if (empty($this->providerSecret)) {
            throw new InvalidConfigException('SingleQyClient::providerSecret must be set.');
        }

        $client = (new TokenClient())->setCache($this->cache);
        return $client->getProviderToken($this->corpId, $this->corpSecret);
    }

    /**
     * @return DefaultClient
     */
    public function getDefaultClient()
    {
        return new DefaultClient($this->getDefaultToken()->value);
    }

    /**
     * @return AgentClient
     */
    public function getAgentClient()
    {
        return new AgentClient($this->getDefaultToken()->value);
    }

    /**
     * @return BatchClient
     */
    public function getBatchClient()
    {
        return new BatchClient($this->getDefaultToken()->value);
    }

    /**
     * @return DepartmentClient
     */
    public function getDepartmentClient()
    {
        return new DepartmentClient($this->getDefaultToken()->value);
    }

    /**
     * @return UserClient
     */
    public function getUserClient()
    {
        return new UserClient($this->getDefaultToken()->value);
    }

    /**
     * @return LoginClient
     */
    public function getLoginClient()
    {
        return new LoginClient($this->getDefaultToken()->value);
    }

    /**
     * @return MediaClient
     */
    public function getMediaClient()
    {
        return new MediaClient($this->getDefaultToken()->value);
    }

    /**
     * @return MaterialClient
     */
    public function getMaterialClient()
    {
        return new MaterialClient($this->getDefaultToken()->value);
    }

    /**
     * @return MenuClient
     */
    public function getMenuClient()
    {
        return new MenuClient($this->getDefaultToken()->value);
    }

    /**
     * @return MessageClient
     */
    public function getMessageClient()
    {
        return new MessageClient($this->getDefaultToken()->value);
    }

    /**
     * @return OAuthClient
     */
    public function getOAuthClient()
    {
        return new OAuthClient($this->getDefaultToken()->value);
    }

    /**
     * @return ServerClient
     */
    public function getServerClient()
    {
        return new ServerClient($this->getDefaultToken()->value);
    }

    /**
     * @return ShakeClient
     */
    public function getShakeClient()
    {
        return new ShakeClient($this->getDefaultToken()->value);
    }

    /**
     * @return TagClient
     */
    public function getTagClient()
    {
        return new TagClient($this->getDefaultToken()->value);
    }

    /**
     * @return ChatClient
     */
    public function getChatClient()
    {
        return new ChatClient($this->getChatToken()->value);
    }
}