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
use yii\caching\Cache;
use yii\di\Instance;

class Client extends Component
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

        if ($this->cache !== null) {
            $this->cache = Instance::ensure($this->cache, Cache::className());
        }
    }

    /**
     * @param string $corp_id
     * @param Token $token
     * @param string $group
     */
    public function setAccessToken($corp_id, $group = 'admin', Token $token)
    {
        $this->accessTokens[$corp_id] = $token;
        $this->setAccessTokenToCache($corp_id, $group, $token->value, $token->expire);
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
        if (empty($this->accessTokens[$corp_id])) {
            $token = $this->getAccessTokenFromCache($corp_id, $group);
            if ($token === false) {
                $token = $this->getAccessTokenFromApi($corp_id, $secret, $group);
                if ($token) {
                    $this->setAccessToken($corp_id, $token);
                } else {
                    throw new ApiException('From original api to get access token error.');
                }
            }
            $this->accessTokens[$corp_id] = $token;
        }

        return $this->accessTokens[$corp_id];
    }

    /**
     * @param string $corp_id
     * @param string $group
     * @return string|false
     */
    private function getAccessTokenFromCache($corp_id, $group = 'admin')
    {
        return $this->cache->get($this->buildCacheKey($corp_id, $group));
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
            'expire' => $token['expire'],
            'group' => $group,
        ]);
    }

    /**
     * @param string $corp_id
     * @param string $group
     * @param string $token
     * @param int $expire
     */
    private function setAccessTokenToCache($corp_id, $group = 'admin', $token, $expire)
    {
        $this->cache->set($this->buildCacheKey($corp_id, $group), $token, $expire);
    }

    /**
     * @param string $corp_id
     * @param string $group
     * @return string
     */
    private function buildCacheKey($corp_id, $group)
    {
        return $corp_id . '-' . $group;
    }
}