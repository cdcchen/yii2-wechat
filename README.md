# yii2-wechat 组件使用说明

### [1.x 文档请移步到此 >>>](/cdcchen/yii2-wechat/wiki/1.x-%E6%96%87%E6%A1%A3%E8%AF%B7%E7%A7%BB%E6%AD%A5%E5%88%B0%E6%AD%A4----)


## 第一步：在components中加入组件配置：

```php
'qyWechat' => [
    'class' => 'cdcchen\yii\wechat\QyClient',
    'cache' => 'cache',
]
```

> 注：cache属性为必需项。


## 第二步：在action代码中调用：

```php
/* @var \cdcchen\yii\wechat\QyClient $wechat */
$wechat = Yii::$app->get('qyWechat');
```

获取【主动消息接口的 Access Token】

```
/* @var \cdcchen\yii\wechat\Token $token */
$token = $wechat->getDefaultToken($corpId, $secret);
```

获取【获取应用提供商凭证 Access Token】

```
/* @var \cdcchen\yii\wechat\Token $token */
$token = $wechat->getProviderToken($corpId, $secret);
```


$token 的类型为 \cdcchen\yii\wechat\Token：

```php
cdcchen\yii\wechat\Token Object
(
    [cropId] => wx4affdfc26d62c294
    [value] => w2M2f2Tj4_-sGHrAv_kdi1__f61exwPUCnvvoJFQ0MPczYttF-22gZuYhV5GHQou
    [expire] => 7200
    [createdAt] => 1462331430
)
```

## 第三步：调用具体接口

以获取微信回调服务器IP列表为例：

```php
use cdcchen\wechat\qy\ServerClient;
$client = new ServerClient($accessToken);
$data = $client->getCallbackIP();
```