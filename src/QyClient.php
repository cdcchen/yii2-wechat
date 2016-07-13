<?php
/**
 * Created by PhpStorm.
 * User: chendong
 * Date: 16/5/3
 * Time: 19:23
 */

namespace cdcchen\yii\wechat;


use cdcchen\wechat\base\ApiException;
use cdcchen\wechat\qy\AccessToken;
use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\caching\Cache;
use yii\di\Instance;

class QyClient extends Component
{
    /**
     * @var \yii\caching\Cache
     */
    public $cache;

    /**
     * @var AccessToken[]
     */
    private $accessTokens = [];

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
     * @param string $corp_id
     * @param Token $token
     * @param string $secret
     */
    public function setAccessToken($corp_id, $secret, Token $token)
    {
        $cacheKey = $this->getCacheKey($corp_id, $secret);
        $this->accessTokens[$cacheKey] = $token;
        $this->setAccessTokenToCache($corp_id, $secret, $token, $token->expire);
    }

    /**
     * @param string $corp_id
     * @param string $secret
     * @param string $group
     * @return string|null
     * @throws ApiException
     */
    public function getAccessToken($corp_id, $secret, $group = 'admin')
    {
        $cacheKey = $this->getCacheKey($corp_id, $secret);
        if (empty($this->accessTokens[$cacheKey])) {
            $token = $this->getAccessTokenFromCache($corp_id, $secret);
            if ($token === false) {
                $token = $this->getAccessTokenFromApi($corp_id, $secret, $group);
                if ($token) {
                    $this->setAccessToken($corp_id, $secret, $token);
                } else {
                    throw new ApiException('From original api to get access token error.');
                }
            }
            $this->accessTokens[$cacheKey] = $token;
        }

        return $this->accessTokens[$cacheKey];
    }

    /**
     * @param string $corp_id
     * @param string $secret
     * @return string|false
     */
    private function getAccessTokenFromCache($corp_id, $secret)
    {
        return $this->cache->get($this->getCacheKey($corp_id, $secret));
    }

    /**
     * @param $corp_id
     * @param $secret
     * @param $group
     * @return Token
     */
    private function getAccessTokenFromApi($corp_id, $secret, $group)
    {
        $token = AccessToken::fetch($corp_id, $secret);
        return new Token([
            'cropId' => $corp_id,
            'value' => $token['access_token'],
            'expire' => $token['expires_in'],
            'group' => $group,
        ]);
    }

    /**
     * @param string $corp_id
     * @param string $secret
     * @param Token $token
     * @param int $expire
     */
    private function setAccessTokenToCache($corp_id, $secret, Token $token, $expire)
    {
        $this->cache->set($this->getCacheKey($corp_id, $secret), $token, $expire);
    }

    /**
     * @param string $corp_id
     * @param string $secret
     * @return string
     */
    private function getCacheKey($corp_id, $secret)
    {
        return md5($corp_id . '_' . $secret);
    }
}