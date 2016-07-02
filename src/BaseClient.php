<?php
/**
 * Created by PhpStorm.
 * User: chendong
 * Date: 16/7/2
 * Time: 19:30
 */

namespace cdcchen\yii\wechat;


use yii\base\Object;
use yii\caching\Cache;

/**
 * Class BaseClient
 * @package cdcchen\yii\wechat
 */
abstract class BaseClient extends Object
{
    /**
     * @var \yii\caching\Cache
     */
    protected $cache;

    /**
     * @var array
     */
    protected $data = [];

    /**
     * @param Cache $cache
     * @return $this
     */
    public function setCache(Cache $cache)
    {
        $this->cache = $cache;
        return $this;
    }

    /**
     * @param $key
     * @param $value
     * @param $duration
     * @return bool
     */
    protected function setCacheData($key, $value, $duration)
    {
        $this->data[$key] = $value;
        return $this->cache->set($key, $value, $duration);
    }

    /**
     * @param string $key
     * @return mixed
     */
    protected function getCacheData($key)
    {
        $value = isset($this->data[$key]) ? $this->data[$key] : null;
        return $value ?: $this->cache->get($key);
    }

    /**
     * @param string|array $parts
     * @return string
     */
    protected function getCacheKey($parts)
    {
        $parts = (array)$parts;
        return md5(join('_', $parts));
    }
}