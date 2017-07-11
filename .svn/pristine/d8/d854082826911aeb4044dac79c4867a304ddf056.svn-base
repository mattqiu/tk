<?php
$config = array (	
		//应用ID,您的APPID。
		'app_id' => "2016042201326184",

		//商户私钥，您的原始格式RSA私钥
		'merchant_private_key' => "MIIEowIBAAKCAQEAvESAUltHItjMer/6BMJGtaINS3Q5dot+KX6+6iPASRsVxJ6PRVyX40z4HSxT3Yc++Tw4h4qduwOvS8x1h5nJ3QA1jTC8WHf8teFBBTxi5mOTp9w+UBfYvrhBUoMOBKF+kKspWeYBky1LBuMLPTkYKtEEd2ce8KaLPjFOSXbE+kcNHVVOYG2sQQOVPclxepOeg/pBBK1BVuM2H9j9fqlooLF1bKNxCrwe4vC6p7fauEoIhYbeYi6VetAhSL2Y/8BDZko48RYEvzJ4UYCAu4kDWKAq+bd3Kvqmks4AMCxBmMpfR7zwjfRXL7dctUbHh0C15bDYA4UQUbm4nOmMY636/QIDAQABAoIBAG9YdCxBH8lUP1s5vzyhSgCSXxqJGpMp3Ovdsjv3+PlDs5Qd0s5K/OnJ09QtK0yReIUfQK+pI7A/daV2vuv74I98WC+w6zPHgwZuum3GURfBPMrCT3g/Iklp3/hU2i2S9a/KCyLIDZZl60Gfdj43TjvEcsfLjVj2ptOZhZudStJZvzHfazBAoKSAcAlQQTHrt2Hvz36pKMR/vEuv579Sj19Je/TK+ZCOvzNK0j4sdZyKnAlYbDTLaYF5X/jTjZs7GBXVeTf0gObXZiJ546YgxIXpaKOXK/SwAKB04jzp4Bf60xFR7Dy/fVOouJdiud8kFejJgoU+/dRhO+cobS03yWECgYEA558Q8zVPc+maHEW7iddy9O1j7E+f18Vxw0UqSCuWU9UY6nQd5ajruO3dpuMlSCnZYPc203ulMsFrUP3h4aBZs55AH8VXzBptzpKQ1ypjbby8IIZuBCUIrOLnt1IRtaeaNrR5NmokGJGeujGJhG41BTRMcqGIuAEVVcpGPFWk1rcCgYEA0BVJgDXEIBuXn3ntmrDVQQmRaFH6E9Pbb7bUn49z/qTcJ293jaF7VKp0Na7q5IBiOdaQyQerKQuyQ7qWWbLuzxM+d451ncDgQ7QNKIlZt83IkxvybZu2+RpU6NlfuzhJ7F2MV7YmaN39gDjYki8XtDNuJjJeZFb9JubmLU2RJ+sCgYEAnnBCyfsnlGSxVLGmrY3BRyWz+owlDRY00ZxYZfHa7RGR757qbJT2WhPsUSKD+C8YUaoJyPSFdoi0fjyM7Sgg9CtGNfVqL2XMw0ei6GxIg2JdVepfC1rB6nF0jAQJtTcm2FDvsXc9pcjGyRrltL8maZ7yxF8XBLdI/txU2yTCk1UCgYBf5I980EpT6aNUUSJYmYmNcVVbV/wBSy7jKsSNy9RO07bJTaswz+xANlVbsAJ7z1Z1BRawLiKIxeUfJUvIhpdbQqkU+8fkLVCsors1pSZ9eA4pDUYJkSsfXS0oKZTeeLBIh0v31++XgEYk4w2bQKyR1Om8HJY3c4cyeFI3KnnshQKBgFA/dOapIww0ccKj2Et6Q0vKIqyX7TMTpWxvbKW7soyDROWiw0Zft/xqHk/+4FFIgbyMaVuaR7Tz6euEXfKUob7f3wLB3/3TY4RWRqnd6vxT8Ko3mXC+LjgDMUXfkov0m52qDC0l6REwnBjtwONRLscvKgKZBhbUkRgxbRyvdzyF",
		
		//异步通知地址
//		'notify_url' => "http://工程公网访问地址/alipay.trade.wap.pay-PHP-UTF-8/notify_url.php",
//                'notify_url' => 'https://www.' . get_public_domain() . '/respond/do_notify_mobile?code=' . base64_encode('m_alipay'),
                'notify_url' => base_url('respond/do_notify_mobile?code=' . base64_encode('m_alipay')),
		
		//同步跳转
//		'return_url' => "http://www.tps7.com/qqq/return_url.php",
                'return_url' => base_url('respond/do_return_mobile?code=' . base64_encode('m_alipay')),

		//编码格式
		'charset' => "UTF-8",

		//签名方式
		'sign_type'=>"RSA2",

		//支付宝网关
		'gatewayUrl' => "https://openapi.alipay.com/gateway.do",

		//支付宝公钥,查看地址：https://openhome.alipay.com/platform/keyManage.htm 对应APPID下的支付宝公钥。
		'alipay_public_key' => "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAssReIjMami2NvR2MaXp8CDHhDnGym0KOHEcjJKqNP/9F5vPp899hnl+JqDnjrGNHdJRNFVx0z9e7DpNn0ZinuZj+OtlFch9g0xVKAYB0ORRbY9mgofyiX4KAefn7kpQ1bTYVt5gQNa/3TFSbL/W5NsLFsRqteFNacmwf03s59pvxnqTn0xo/fPiseQJW1GUTEjtYXg8T+XZblPYuHUY0j3uI1AKN8F3gJW1vkEJUvPGNT5AJTXpjjobP+82FTn/MACswojPL6j8haXEhcQesdDxyFdo9B/u/ZhP5fwFMKXTJvGKzZC16Wcd8LneNlnmW2nd0LhigPeSkRScKgPMxFQIDAQAB",
		
	
);