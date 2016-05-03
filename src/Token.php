<?php
/**
 * Created by PhpStorm.
 * User: chendong
 * Date: 16/5/3
 * Time: 19:17
 */

namespace cdcchen\yii\wechat;


use yii\base\Object;

class Token extends Object
{
    public $cropId;
    public $value;
    public $expire;
    public $createdAt;
    public $group;

    public function __construct(array $config)
    {
        parent::__construct($config);
        $this->createdAt = time();
    }

    public function hasExpired()
    {
        return (time() - $this->createdAt) >= $this->expire;
    }
}