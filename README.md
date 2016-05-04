# yii2-wechat 组件使用说明

> 此组件的主要功能只有一个，就是自动管理access token的失效及获取，利用缓存的失效时间自动重新获取新的access token。


### 第一步：在components中加入组件配置：

```php
'wechat' => [
    'class' => 'cdcchen\yii\wechat\QyClient',
    'cache' => 'cache',
]
```
> 注：`cache` 属性为必需项。


### 第二步：在action代码中调用：

```php
/* @var \cdcchen\yii\wechat\QyClient $wechat */
$wechat = Yii::$app->get('wechat');

// 获取 Access Token
/* @var \cdcchen\yii\wechat\Token $token */
$token = $wechat->getAccessToken($corpId, $secret);
```

$`token` 的类型为 `\cdcchen\yii\wechat\Token`：

```php
cdcchen\yii\wechat\Token Object
(
    [cropId] => wx4affdfc26d62c294
    [value] => w2M2f2Tj4_-sGHrAv_kdi1__f61exwPUCnvvoJFQ0MPczYttF-22gZuYhV5GHQou
    [expire] => 7200
    [createdAt] => 1462331430
    [group] => admin
)
```