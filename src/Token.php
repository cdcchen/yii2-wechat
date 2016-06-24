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
 * Class Token
 * @package cdcchen\yii\wechat
 */
class Token extends Object
{
    /**
     * @var string
     */
    public $corpId;
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
     * Token constructor.
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
}