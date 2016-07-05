<?php
/**
 * Created by PhpStorm.
 * User: chendong
 * Date: 16/5/3
 * Time: 19:23
 */

namespace cdcchen\yii\wechat;


use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\caching\Cache;
use yii\di\Instance;

/**
 * Class QyClient
 * @package cdcchen\yii\wechat
 */
class QyClient extends Component
{

    /**
     * @var \yii\caching\Cache
     */
    public $cache;

    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {
        parent::init();

        if (empty($this->cache)) {
            throw new InvalidConfigException('QyClient::cache property is required.');
        }

        $this->cache = Instance::ensure($this->cache, Cache::className());
    }

    /**
     * @param string $corpId
     * @param string $secret
     * @param bool $group
     * @return null|Ticket
     * @throws \cdcchen\wechat\base\ApiException
     */
    public function getJsApiTicket($corpId, $secret, $group = false)
    {
        $client = (new JsApiClient())->setCache($this->cache);
        $token = $this->getDefaultToken($corpId, $secret);
        return $client->getJsApiTicket($token->value, $group);
    }

    /**
     * @param string $corpId
     * @param string $secret
     * @return Token|null
     * @throws \cdcchen\wechat\base\ApiException
     */
    public function getDefaultToken($corpId, $secret)
    {
        $client = (new TokenClient())->setCache($this->cache);
        return $client->getDefaultToken($corpId, $secret);
    }

    /**
     * @param string $corpId
     * @param string $secret
     * @return Token|null
     * @throws \cdcchen\wechat\base\ApiException
     */
    public function getProviderToken($corpId, $secret)
    {
        $client = (new TokenClient())->setCache($this->cache);
        return $client->getProviderToken($corpId, $secret);
    }
}