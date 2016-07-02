<?php
/**
 * Created by PhpStorm.
 * User: chendong
 * Date: 16/5/3
 * Time: 19:17
 */

namespace cdcchen\yii\wechat;


use yii\base\Object;

/**
 * Class Ticket
 * @package cdcchen\yii\wechat
 */
class Ticket extends Object
{
    /**
     * @var string
     */
    public $accessToken;
    /**
     * @var string
     */
    public $value;
    /**
     * @var int
     */
    public $expire;
    /**
     * @var int
     */
    public $createdAt;

    /**
     * @var string
     */
    public $groupId;

    /**
     * Ticket constructor.
     * @param array $config
     */
    public function __construct(array $config)
    {
        parent::__construct($config);
        $this->createdAt = time();
    }

    /**
     * @return bool
     */
    public function hasExpired()
    {
        return (time() - $this->createdAt) >= $this->expire;
    }

    /**
     * @return bool
     */
    public function isGroup()
    {
        return !empty($this->groupId);
    }
}