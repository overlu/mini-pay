## 安装

```Shell
$ composer require overlu/mini-pay
```

### 添加 service provider

```PHP
MiniPay\PayServiceProvider::class
```

### 配置文件

```Shell
$ php bin/artisan vendor:publish --provider="MiniPay\PayServiceProvider"
```

## 使用方法

### 支付宝

```PHP
use MiniPay\Facades\Pay;

$order = [
    'out_trade_no' => time(),
    'total_amount' => '1',
    'subject' => 'test subject - 测试',
];

return Pay::alipay()->web($order);/???

```

### 微信

```PHP
use Pay;

$order = [
    'out_trade_no' => time(),
    'body' => 'subject-测试',
    'total_fee'      => '1',
    'openid' => 'onkVf1FjWS5SBIixxxxxxxxx',
];

$result = Pay::wechat()->mp($order);

```

## License

MIT

> 感谢 **[yansongda/pay](https://github.com/yansongda/pay)** 提供了优秀的支付扩展
> 具体使用说明请传送至 [https://github.com/yansongda/pay](https://github.com/yansongda/pay)