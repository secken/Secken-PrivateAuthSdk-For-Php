# Secken Private Manage Cloud Server SDK For Php

## 简介（Description）
Secken-PrivateAuthSdk-For-Php是Secken官方提供了一套用于和洋葱验证服务交互的SDK组件，通过使用它，您可以简化集成Secken服务的流程并降低开发成本。

密码就要大声说出来，开启无密时代，让密码下岗

洋葱是一个基于云和用户生物特征的身份验证服务。网站通过集成洋葱，可以快速实现二维码登录，或在支付、授权等关键业务环节使用指纹、声纹或人脸识别功能，从而彻底抛弃传统的账号密码体系。对个人用户而言，访问集成洋葱服务的网站将无需注册和记住账号密码，直接使用生物特征验证提高了交易安全性，无需担心账号被盗。洋葱还兼容Google验证体系，支持国内外多家网站的登录令牌统一管理。

【联系我们】

官网：https://www.yangcong.com

微信：yangcongAPP

微信群：http://t.cn/RLGDwMJ

QQ群：154697540

微博：http://weibo.com/secken

帮助：https://www.yangcong.com/help

合作：010-64772882 / market@secken.com

支持：support@secken.com

帮助文档：https://www.yangcong.com/help

项目地址：https://github.com/secken/Secken-PrivateAuthSdk-For-Php

洋葱SDK产品服务端SDK主要包含四个方法：
* 获取二维码的方法（GetYangAuthQrCode），用于获取二维码内容和实现绑定。
* 请求推送验证的方法（AskYangAuthPush），用于发起对用户的推送验证操作。
* 查询事件结果的方法（CheckYangAuthResult），用于查询二维码登录或者推送验证的结果。

## 安装使用（Install & Get Started）

To install Secken-PrivateAuthSdk-For-Php, Import these packages

```
include_once 'secken_private.class.php';
```
## 更新发布（Update & Release Notes）

```
【1.0.0】更新内容：
1、完成了Php语言三大接口封装。
```

## 要求和配置（Require & Config）
```
$power_id     = 'ubfjVKuV7HHKuGFYwyHG';
$power_key    = 'Q0eYeCju5wg9qSXHvEkkdSwhnqoHvaRO';
$secken_api = new secken_private("spc.secken.net", $power_id, $power_key);
```

## 获取二维码内容并发起验证事件（Get YangAuth QrCode）
```
$ret  = $secken_api->getQrCode();
if ( $secken_api->getCode() != 200 ){
	$arr = array(
		'status'=> $secken_api->getCode(),
		'description' => $secken_api->getMessage(),
		);
	$json = json_encode($arr);
	echo $json;
} else {
	$json = json_encode($ret);
	echo $json;
}
```


|    状态码   | 		状态详情 		  |
|:----------:|:-----------------:|
|  200       |       成功         |
|  400       |       上传参数错误  |
|  403       |       签名错误                |
|  404       |       应用不存在                |
|  407       |       请求超时                |
|  500       |       系统错误                |
|  609       |       ip地址被禁                |

## 查询验证事件的结果（Check YangAuth Result）
```
$event_id = $_GET["eid"];
$ret  = $secken_api->getResult($event_id);
if ( $secken_api->getCode() != 200 ){
	$arr = array(
		'status'=> $secken_api->getCode(),
		'description' => $secken_api->getMessage(),
		);
	$json = json_encode($arr);
	echo $json;
} else {
	$json = json_encode($ret);
	echo $json;
}
```
CheckYangAuthResult接口包含一个必传参数，EventId。

|    状态码   | 		状态详情 		  |
|:----------:|:-----------------:|
|  200       |       成功         |
|  201       |       事件已被处理                |
|  400       |       上传参数错误  |
|  403       |       签名错误                |
|  404       |       应用不存在                |
|  407       |       请求超时                |
|  500       |       系统错误                |
|  601       |       用户拒绝                |
|  602       |       用户还未操作                |
|  604       |       事件不存在                |
|  606       |       callback已被设置                |
|  609       |       ip地址被禁                |

## 发起推送验证事件（Ask YangAuth Push）
```
$username = $_GET["username"];
$ret = $secken_api->askPushAuth($username);
if ( $secken_api->getCode() != 200 ){
	$arr = array(
		'status'=> $secken_api->getCode(),
		'description' => $secken_api->getMessage(),
		);
	$json = json_encode($arr);
	echo $json;
} else {
	$json = json_encode($ret);
	echo $json;
}
```
AskYangAuthPush接口包含两个必传参数：AuthType、UserId；两个可选参数：ActionType、ActionDetail。  

|    状态码   | 		状态详情 		  |
|:----------:|:-----------------:|
|  200       |       成功         |
|  400       |       上传参数错误  |
|  403       |       签名错误                |
|  404       |       应用不存在                |
|  407       |       请求超时                |
|  500       |       系统错误                |
|  608       |       验证token不存在           |
|  609       |       ip地址被禁                |