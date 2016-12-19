<?php
/**
 * Created by PhpStorm.
 * User: chendong
 * Date: 16/7/2
 * Time: 20:32
 */

namespace cdcchen\yii\wechat;


use cdcchen\wechat\base\ApiException;
use cdcchen\wechat\qy\TokenClient as WechatTokenClient;

class TokenClient extends BaseClient
{
    /**
     * @param string $corpId
     * @param string $secret
     * @return null|Token
     * @throws ApiException
     */
    public function getDefaultToken($corpId, $secret)
    {
        if (empty($this->cache)) {
            return $this->getDefaultTokenFromApi($corpId, $secret);
        }

        $cacheKey = $this->getCacheKey([$corpId, $secret]);
        if (empty($this->getCacheData($cacheKey))) {
            $token = $this->getDefaultTokenFromApi($corpId, $secret);
            if ($token) {
                $this->setCacheData($cacheKey, $token, $token->expire);
            } else {
                throw new ApiException('Get access token error from original api to.');
            }
        }

        return $this->getCacheData($cacheKey);
    }

    /**
     * @param string $corpId
     * @param string $secret
     * @return null|Token
     * @throws ApiException
     */
    public function getProviderToken($corpId, $secret)
    {
        if (empty($this->cache)) {
            return $this->getProviderTokenFromApi($corpId, $secret);
        }

        $cacheKey = $this->getCacheKey(['provider', $corpId, $secret]);
        if (empty($this->getCacheData($cacheKey))) {
            $token = $this->getProviderTokenFromApi($corpId, $secret);
            if ($token) {
                $this->setCacheData($cacheKey, $token, $token->expire);
            } else {
                throw new ApiException('Get access token error from original api to.');
            }
        }

        return $this->getCacheData($cacheKey);
    }

    /**
     * @param string $corpId
     * @param string $secret
     * @return Token
     */

    private function getDefaultTokenFromApi($corpId, $secret)
    {
        $token = WechatTokenClient::getDefaultToken($corpId, $secret);
        return new Token([
            'corpId' => $corpId,
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
        $token = WechatTokenClient::getProviderToken($corpId, $secret);
        return new Token([
            'corpId' => $corpId,
            'value' => $token['provider_access_token'],
            'expire' => $token['expires_in'],
        ]);
    }
}