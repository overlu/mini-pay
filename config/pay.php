<?php
/**
 * This file is part of Mini.
 * @auth lupeng
 */
declare(strict_types=1);

use Yansongda\Pay\Pay;

return [
    'alipay' => [
        'default' => [
            // 必填-支付宝分配的 app_id
            'app_id' => env('ALIPAY_APP_ID', ''),
            // 必填-应用私钥 字符串或路径
            'app_secret_cert' => env('ALIPAY_APP_SECRET_CERT', ''),
            // 必填-应用公钥证书 路径
            'app_public_cert_path' => env('ALIPAY_APP_PUBLIC_CERT_PATH', ''),
            // 必填-支付宝公钥证书 路径
            'alipay_public_cert_path' => env('ALIPAY_ALIPAY_PUBLIC_CERT_PATH', ''),
            // 必填-支付宝根证书 路径
            'alipay_root_cert_path' => env('ALIPAY_ALIPAY_ROOT_CERT_PATH', ''),
            'return_url' => env('ALIPAY_RETURN_URL', 'https://xxx/alipay/return'),
            'notify_url' => env('ALIPAY_NOTIFY_URL', 'https://xxx/alipay/notify'),
            // 选填-服务商模式下的服务商 id，当 mode 为 Pay::MODE_SERVICE 时使用该参数
            'service_provider_id' => env('ALIPAY_SERVICE_PROVIDER_ID', ''),
            // 选填-默认为正常模式。可选为： MODE_NORMAL, MODE_SANDBOX, MODE_SERVICE
            'mode' => env('ALIPAY_MODE', Pay::MODE_NORMAL),
        ],
    ],
    'wechat' => [
        'default' => [
            // 必填-商户号，服务商模式下为服务商商户号
            // 可在 https://pay.weixin.qq.com/ 账户中心->商户信息 查看
            'mch_id' => env('WECHAT_MCH_ID', ''),
            // 选填-v2商户私钥
            'mch_secret_key_v2' => env('WECHAT_MCH_SECRET_KEY_V2', ''),
            // 必填-v3 商户秘钥
            // 即 API v3 密钥(32字节，形如md5值)，可在 账户中心->API安全 中设置
            'mch_secret_key' => env('WECHAT_MCH_SECRET_KEY', ''),
            // 必填-商户私钥 字符串或路径
            // 即 API证书 PRIVATE KEY，可在 账户中心->API安全->申请API证书 里获得
            // 文件名形如：apiclient_key.pem
            'mch_secret_cert' => env('WECHAT_MCH_SECRET_CERT', ''),
            // 必填-商户公钥证书路径
            // 即 API证书 CERTIFICATE，可在 账户中心->API安全->申请API证书 里获得
            // 文件名形如：apiclient_cert.pem
            'mch_public_cert_path' => env('WECHAT_MCH_PUBLIC_CERT_PATH', ''),
            // 必填-微信回调url
            // 不能有参数，如?号，空格等，否则会无法正确回调
            'notify_url' => env('WECHAT_NOTIFY_URL', 'https://xxx/wechat/notify'),
            // 选填-公众号 的 app_id
            // 可在 mp.weixin.qq.com 设置与开发->基本配置->开发者ID(AppID) 查看
            'mp_app_id' => env('WECHAT_MP_APP_ID', ''),
            // 选填-小程序 的 app_id
            'mini_app_id' => env('WECHAT_MINI_APP_ID', ''),
            // 选填-app 的 app_id
            'app_id' => env('WECHAT_APP_ID', ''),
            // 选填-合单 app_id
            'combine_app_id' => env('WECHAT_COMBINE_APP_ID', ''),
            // 选填-合单商户号
            'combine_mch_id' => env('WECHAT_COMBINE_MCH_ID', ''),
            // 选填-服务商模式下，子公众号 的 app_id
            'sub_mp_app_id' => env('WECHAT_SUB_MP_APP_ID', ''),
            // 选填-服务商模式下，子 app 的 app_id
            'sub_app_id' => env('WECHAT_SUB_APP_ID', ''),
            // 选填-服务商模式下，子小程序 的 app_id
            'sub_mini_app_id' => env('WECHAT_SUB_MINI_APP_ID', ''),
            // 选填-服务商模式下，子商户id
            'sub_mch_id' => env('WECHAT_SUB_MCH_ID', ''),
            // 选填-微信平台公钥证书路径, optional，强烈建议 php-fpm 模式下配置此参数
            'wechat_public_cert_path' => [
//                '45F59D4DABF31918AFCEC556D5D2C6E376675D57' => __DIR__.'/Cert/wechatPublicKey.crt',
            ],
            // 选填-默认为正常模式。可选为： MODE_NORMAL, MODE_SERVICE
            'mode' => env('WECHAT_MODE', Pay::MODE_NORMAL),
        ]
    ],
    'unipay' => [
        'default' => [
            // 必填-商户号
            'mch_id' => env('UNIPAY_MCH_ID', ''),
            // 必填-商户公私钥
            'mch_cert_path' => env('UNIPAY_MCH_CERT_PATH', ''),
            // 必填-商户公私钥密码
            'mch_cert_password' => env('UNIPAY_MCH_CERT_PASSWORD', '000000'),
            // 必填-银联公钥证书路径
            'unipay_public_cert_path' => env('UNIPAY_UNIPAY_PUBLIC_CERT_PATH', ''),
            // 必填
            'return_url' => env('UNIPAY_RETURN_URL', ''),
            // 必填
            'notify_url' => env('UNIPAY_NOTIFY_URL', ''),
        ],
    ],
    'http' => [ // optional
        'timeout' => 5.0,
        'connect_timeout' => 5.0,
        // 更多配置项请参考 [Guzzle](https://guzzle-cn.readthedocs.io/zh_CN/latest/request-options.html)
    ],
    // optional，默认 warning；日志路径为：sys_get_temp_dir().'/logs/yansongda.pay.log'
    'logger' => [
        'enable' => false,
        'file' => runtime_path('logs/pay.log'),
        'level' => 'debug',
        'type' => 'daily', // optional, 可选 daily.
        'max_file' => 30,
    ],
];
