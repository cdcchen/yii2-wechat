<?php
/**
 * Created by PhpStorm.
 * User: chendong
 * Date: 16/7/2
 * Time: 18:26
 */

namespace cdcchen\yii\wechat;

use cdcchen\wechat\base\ApiException;
use cdcchen\wechat\qy\JsApiClient as WechatJsApiClient;

/**
 * Class JsApiClient
 * @package cdcchen\yii\wechat
 */
class JsApiClient extends BaseClient
{
    /**
     * @param string $accessToken
     * @param bool $group
     * @return mixed|Ticket
     * @throws ApiException
     */
    public function getJsApiTicket($accessToken, $group = false)
    {
        $cacheKey = $this->getCacheKey([$accessToken, (string)$group]);
        if (empty($this->getCacheData($cacheKey))) {
            $ticket = $this->getJsTicketFromApi($accessToken, $group);
            if ($ticket) {
                $this->setCacheData($cacheKey, $ticket, $ticket->expire);
            } else {
                throw new ApiException('Get access token error from original api to.');
            }
        }

        return $this->getCacheData($cacheKey);
    }

    /**
     * @param string $accessToken
     * @param $group $type
     * @return Ticket
     * @throws \InvalidArgumentException
     */
    private function getJsTicketFromApi($accessToken, $group = false)
    {
        if ($group) {
            return $this->getGroupTicketFromApi($accessToken);
        } else {
            return $this->getJsApiTicketFromApi($accessToken);
        }
    }

    /**
     * @param string $accessToken
     * @return Ticket
     */

    private function getJsApiTicketFromApi($accessToken)
    {
        $client = new WechatJsApiClient($accessToken);
        $ticket = $client->getJsApiTicket();
        return new Ticket([
            'accessToken' => $accessToken,
            'value' => $ticket['ticket'],
            'expire' => $ticket['expires_in'],
        ]);
    }

    /**
     * @param string $accessToken
     * @return Ticket
     */

    private function getGroupTicketFromApi($accessToken)
    {
        $client = new WechatJsApiClient($accessToken);
        $ticket = $client->getGroupTicket();
        return new Ticket([
            'accessToken' => $accessToken,
            'value' => $ticket['ticket'],
            'expire' => $ticket['expires_in'],
            'groupId' => $ticket['group_id'],
        ]);
    }
}