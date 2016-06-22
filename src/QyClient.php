<?php
/**
 * Created by PhpStorm.
 * User: chendong
 * Date: 16/5/3
 * Time: 19:23
 */

namespace cdcchen\yii\wechat;


use cdcchen\wechat\base\ApiException;
use cdcchen\wechat\qy\TokenClient;
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
     * default access token
     */
    const TOKEN_TYPE_DEFAULT = 'default';
    /**
     * provider access token
     */
    const TOKEN_TYPE_PROVIDER = 'provider';

    /**
     * @var \yii\caching\Cache
     */
    public $cache;

    /**
     * @var Token[]
     */
    private $accessTokens = [];

    /**
     * @var string
     */
    private $_cacheKey;

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
     * @param Token $token
     * @param string $secret
     */
    protected function setAccessToken($corpId, $secret, Token $token)
    {
        $cacheKey = $this->getCacheKey($corpId, $secret);
        $this->accessTokens[$cacheKey] = $token;
        $this->setAccessTokenToCache($corpId, $secret, $token);
    }

    /**
     * @param string $corpId
     * @param string $secret
     * @return null|string
     * @throws ApiException
     */
    public function getDefaultToken($corpId, $secret)
    {
        return $this->getAccessToken($corpId, $secret, self::TOKEN_TYPE_DEFAULT);
    }

    /**
     * @param $corpId
     * @param $secret
     * @return null|string
     * @throws ApiException
     */
    public function getProviderToken($corpId, $secret)
    {
        return $this->getAccessToken($corpId, $secret, self::TOKEN_TYPE_PROVIDER);
    }

    /**
     * @param string $corpId
     * @param string $secret
     * @param string $type
     * @return Token|string|null
     * @throws ApiException
     */
    private function getAccessToken($corpId, $secret, $type)
    {
        $cacheKey = $this->getCacheKey($corpId, $secret);
        if (empty($this->accessTokens[$cacheKey])) {
            $token = $this->getAccessTokenFromCache($corpId, $secret);
            if ($token === false) {
                $token = $this->getAccessTokenFromApi($corpId, $secret, $type);
                if ($token) {
                    $this->setAccessToken($corpId, $secret, $token);
                } else {
                    throw new ApiException('From original api to get access token error.');
                }
            }
            $this->accessTokens[$cacheKey] = $token;
        }

        return $this->accessTokens[$cacheKey];
    }

    /**
     * @param string $corpId
     * @param string $secret
     * @return Token|string|false
     */
    private function getAccessTokenFromCache($corpId, $secret)
    {
        $cacheKey = $this->getCacheKey($corpId, $secret);
        return $this->cache->get($cacheKey);
    }

    /**
     * @param string $corpId
     * @param string $secret
     * @param string $type
     * @return Token
     * @throws \InvalidArgumentException
     */
    private function getAccessTokenFromApi($corpId, $secret, $type)
    {
        if ($type === self::TOKEN_TYPE_DEFAULT) {
            return $this->getDefaultTokenFromApi($corpId, $secret);
        } elseif ($type === self::TOKEN_TYPE_PROVIDER) {
            return $this->getProviderTokenFromApi($corpId, $secret);
        } else {
            throw new \InvalidArgumentException("$type is a not valid token type.");
        }
    }

    /**
     * @param string $corpId
     * @param string $secret
     * @return Token
     */

    private function getDefaultTokenFromApi($corpId, $secret)
    {
        $token = TokenClient::getDefaultToken($corpId, $secret);
        return new Token([
            'cropId' => $corpId,
            'value' => $token['access_token'],
            'expire' => $token['expires_in'],
        ]);
    }

    /**
     * @param string $corpId
     * @param string $secret
     * @return Token
     */

    private function getProviderTokenFromApi($corpId, $secret)
    {
        $token = TokenClient::getDefaultToken($corpId, $secret);
        return new Token([
            'cropId' => $corpId,
            'value' => $token['provider_access_token'],
            'expire' => $token['expires_in'],
        ]);
    }

    /**
     * @param string $corpId
     * @param string $secret
     * @param Token $token
     */
    private function setAccessTokenToCache($corpId, $secret, Token $token)
    {
        $cacheKey = $this->getCacheKey($corpId, $secret);
        $this->cache->set($cacheKey, $token, $token->expire);
    }

    /**
     * @param string $corpId
     * @param string $secret
     * @return string
     */
    private function getCacheKey($corpId, $secret)
    {
        if ($this->_cacheKey === null) {
            $this->_cacheKey = md5($corpId . '-' . $secret);
        }

        return $this->_cacheKey;
    }
}