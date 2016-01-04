<?php
/**
 * Server SDK for PHP
 * SDK Server Api v1.0
 *
 * 洋葱开放API文档
 * https://www.yangcong.com/api
 *
 **/

class secken_private {

	//应用id
	private $power_id = '';

	//应用Key
	private $power_key = '';

	//api请求地址
	private $base_url			= 'http://spc.secken.net/api/access/';

	const HTTP_PROTOCOL 		= 'http://';

	const HTTP_SUFFIX	 		= '/api/access/';

	//获取通过洋葱进行验证的二维码
	const QRCODE_FOR_AUTH       = 'qrcode_for_auth';

	//根据 event_id 查询详细事件信息
	const EVENT_RESULT          = 'event_result';

	//请求洋葱推送验证
	const REALTIME_AUTH         = 'realtime_authorization';

	//复验洋葱验证结果
	const CHECK_AUTHTOKEN       = 'query_auth_token';

	/**
	 * 错误码
	 * @var array
	 */
	private $errorCode = array(
		200 => '请求成功',
		201 => '通知用户，等待用户操作',
		400 => '请求参数格式错误',
		403 => '请求签名错误',
		404 => '请求API不存在',
		406 => '不在应用白名单里',
		407 => '请求超时',
		500 => '系统错误',
		501 => '获取二维码错误',
		601 => '用户拒绝授权',
		602 => '等待用户响应',
		603 => '等待用户响应超时',
		604 => '用户或event_id不存在',
		605 => '用户未开启该验证类型',
		606 => '已使用回调函数获取结果，不支持直接查询',
		607 => '用户不存在',
	);

	/**
	 * 初始化
	 */
	public function __construct($host, $power_id, $power_key) {
		$this->power_id   = $power_id;
		$this->power_key  = $power_key;
		$this->BASE_URL = self::HTTP_PROTOCOL . $host . self::HTTP_SUFFIX;
	}

	public function getQrCode() {
		$data   = array();
		$data   = array(
			'power_id'    => $this->power_id
		);

		$data['signature'] = $this->getSignature($data);

		$url    = $this->gen_get_url(self::QRCODE_FOR_AUTH, $data);
		$ret    = $this->request($url);

		return $this->prettyRet($ret);
	}

	public function askPushAuth($username) {
		$data   = array();
		$data   = array(
			'power_id'	=> $this->power_id,
			'username'	=> $username,
		);

		$data['signature'] = $this->getSignature($data);

		$url    = $this->base_url . self::REALTIME_AUTH;

		$ret    = $this->request($url, 'POST', $data);

		return $this->prettyRet($ret);
	}

	public function getResult($event_id) {
		$data   = array();
		$data   = array(
			'power_id'    => $this->power_id,
			'event_id'  => $event_id
		);

		$data['signature'] = $this->getSignature($data);

		$url    = $this->gen_get_url(self::EVENT_RESULT, $data);
		$ret    = $this->request($url);

		return $this->prettyRet($ret);
	}

	/**
	 * 生成签名
	 * @param
	 * params  Array  要签名的参数
	 * @return String 签名的MD5串
	 */
	private function getSignature($params) {
		ksort($params);
		$str = '';

		foreach ( $params as $key => $value ) {
			$str .= "$key=$value";
		}

		return sha1($str . $this->power_key);
	}

	/**
	 * 返回错误消息
	 * @return string
	 */
	public function getMessage() {
		return $this->message;
	}

	/**
	 * 返回错误码
	 * @return string
	 */
	public function getCode() {
		return $this->code;
	}

	/**
	 * 处理返回信息
	 * @return Mix
	 */
	private function prettyRet($ret) {
		if ( is_string($ret) ) {
			return $ret;
		}

		$this->code = isset($ret['status'])? $ret['status'] : false;

		if(isset($this->errorCode[$this->code])){
			$this->message = $this->errorCode[$this->code];
		}else{
			$this->message = isset($ret['description']) ? $ret['description'] : 'UNKNOW ERROR';
		}

		return $ret;
	}


	/**
	 * 生成请求连接，用于发起GET请求
	 * @param
	 * action_url    String    请求api地址
	 * data          Array     请求参数
	 * @return String
	 **/
	private function gen_get_url($action_url, $data) {
		return $this->base_url . $action_url. '?' . http_build_query($data);
	}


	/**
	 * 发送HTTP请求到洋葱服务器
	 * @param
	 * url      String  API 的 URL 地址
	 * method   Sting   HTTP方法，POST | GET
	 * data     Array   发送的参数，如果 method 为 GET，留空即可
	 * @return  Mix
	 **/
	private function request($url, $method = 'GET', $data = array()) {
		if ( !function_exists('curl_init') ) {
			die('Need to open the curl extension');
		}

		if ( !$url || !in_array($method, array('GET', 'POST')) ) {
			return false;
		}

		$ci = curl_init();

		curl_setopt($ci, CURLOPT_URL, $url);
		curl_setopt($ci, CURLOPT_USERAGENT, 'Server SDK for PHP v1.0 (yangcong.com)');
		curl_setopt($ci, CURLOPT_HEADER, FALSE);
		curl_setopt($ci, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ci, CURLOPT_TIMEOUT, 30);
		curl_setopt($ci, CURLOPT_SSL_VERIFYPEER, false);

		if ( $method == 'POST' ) {
			curl_setopt($ci, CURLOPT_POST, TRUE);
			curl_setopt($ci, CURLOPT_POSTFIELDS, http_build_query($data));
		}

		$response   = curl_exec($ci);

		if ( curl_errno($ci) ) {
			return curl_error($ci);
		}

		$ret    = json_decode($response, true);
		if ( !$ret ) {
			return 'response is error, can not be json decode: ' . $response;
		}

		return $ret;
	}

}
