<?php
namespace app\home\controller;

use EasyWeChat\Foundation\Application;
use EasyWeChat\Message\News;
use think\Controller;

class WeChat extends Controller
{
	private $_options = [
		/**
		 * Debug 模式，bool 值：true/false
		 *
		 * 当值为 false 时，所有的日志都不会记录
		 */
		'debug'  => true,
		/**
		 * 账号基本信息，请从微信公众平台/开放平台获取
		 */
		'app_id'  => 'wxcf5d7d2a25d0dc9f',         // AppID
		'secret'  => '32f7e05731c1d66d49a83c59b75e9706',     // AppSecret
		'token'   => 'nihao',          // Token
		'aes_key' => '',                    // EncodingAESKey，安全模式下请一定要填写！！！
		/**
		 * 日志配置
		 *
		 * level: 日志级别, 可选为：
		 *         debug/info/notice/warning/error/critical/alert/emergency
		 * permission：日志文件权限(可选)，默认为null（若为null值,monolog会取0644）
		 * file：日志文件位置(绝对路径!!!)，要求可写权限
		 */
		'log' => [
			'level'      => 'debug',
			'permission' => 0777,
			'file'       => '/www/wwwroot/twothink/runtime/log/easywechat.log',
		],
		/**
		 * OAuth 配置
		 *
		 * scopes：公众平台（snsapi_userinfo / snsapi_base），开放平台：snsapi_login
		 * callback：OAuth授权完成后的回调页地址
		 */
		'oauth' => [
			'scopes'   => ['snsapi_userinfo'],
			'callback' => '/examples/oauth_callback.php',
		],
		/**
		 * 微信支付
		 */
		'payment' => [
			'merchant_id'        => 'your-mch-id',
			'key'                => 'key-for-signature',
			'cert_path'          => 'path/to/your/cert.pem', // XXX: 绝对路径！！！！
			'key_path'           => 'path/to/your/key',      // XXX: 绝对路径！！！！
			// 'device_info'     => '013467007045764',
			// 'sub_app_id'      => '',
			// 'sub_merchant_id' => '',
			// ...
		],
		/**
		 * Guzzle 全局设置
		 *
		 * 更多请参考： http://docs.guzzlephp.org/en/latest/request-options.html
		 */
		'guzzle' => [
			'timeout' => 3.0, // 超时时间（秒）
			'verify' => false, // 关掉 SSL 认证（强烈不建议！！！）
		],
	];

	public function index()
	{
// ...
		$app = new Application($this->_options);
// 从项目实例中得到服务端应用实例。
		$server = $app->server;
		$server->setMessageHandler(function ($message) {
			switch ($message->MsgType) {
				case 'event':
					$this->event();
					return '收到事件消息';
					break;
				case 'text':
					return $this->text($message);
					break;
				case 'image':
					return '收到图片消息';
					break;
				case 'voice':
					return '收到语音消息';
					break;
				case 'video':
					return '收到视频消息';
					break;
				case 'location'://记录发送过来的位置信息
					return $this->location($message);
					break;
				case 'link':
					return '收到链接消息';
					break;
				// ... 其它消息
				default:
					return '收到其它消息';
					break;
			}
			// ...
		});
		$response = $server->serve();
		$response->send(); // Laravel 里请使用：return $response;
	}

	private function event()
	{

	}
	private function text($message)
	{
		$reg = '/\+/';
		if (preg_match($reg,$message->Content)==0){
			return "如需搜索请输入:搜索+搜索的内容...";
		}
		list($action,$keyword) = explode("+",$message->Content,2);
		switch ($action)
		{
			case "搜索":
				if ($keyword==""){
					return "搜索的格式为:搜索+XX...";
				}
				return $this->search($message,$keyword);
				break;
			default:
				return "没有这个操作哟!";
				break;
		}
	}
	private function image()
	{

	}
	private function voice()
	{

	}
	private function video()
	{

	}
	private function location($message)//接收地理坐标信息并保存
	{
		$Location_X = $message->Location_X;
		$Location_Y = $message->Location_Y;
		$FromUserName = $message->FromUserName;
		$redis = new \Redis();
		$redis->connect("127.0.0.1");
		$redis->set("Location_".$FromUserName,$Location_X.",".$Location_Y);
		return "已定位,请输入搜索内容.如:搜索+火锅";
	}
	private function link()
	{

	}
	private function other()
	{

	}
	private function search($message,$keyword)//百度搜索周边商家
	{
		$redis = new \Redis();
		$redis->connect("127.0.0.1");
		$location = $redis->get("Location_".$message->FromUserName);
		if ($location===false){
			return "请先将你的位置发送给我!  点击笑脸旁边,加号键里面的位置";
		}
		$ak = "P4qQ3SkwKxtXOX6lx3bDd15C1UYWvHmm";//自己的百度AK
		$url = "http://api.map.baidu.com/place/v2/search?query=$keyword&page_size=8&page_num=0&scope=2&location=$location&radius=2000&output=xml&ak=".$ak;
		$baiduObj = simplexml_load_file($url);
		$results = $baiduObj->results;
		if (count($results->result)==0){
			return "没有搜索到内容哦!";
		}
		$news = [];
		foreach ($results->result as $result){
			$item_url = $result->detail_info;//
			$news[] = new News([
				'title'       => (string)$result->name,
				'description' => '...',
				'url'         => (string)$item_url->detail_url,
				'image'       => '',
			]);
		}
		return $news;
	}
	public function setMenu()
	{
		$buttons = [
			[
				"name"       => "导航",
				"sub_button" => [
					[
						"type" => "view",
						"name" => "小区通知",
						"url"  => "http://tt.9ymc.top/home/index/article/change/inform"
					],
					[
						"type" => "view",
						"name" => "便民服务",
						"url"  => "http://tt.9ymc.top/home/index/article/change/sever"
					],
					[
						"type" => "view",
						"name" => "小区活动",
						"url" => "http://tt.9ymc.top/home/index/article/change/activity"
					],
					[
						"type" => "view",
						"name" => "小区租售",
						"url" => "http://tt.9ymc.top/home/index/rental"
					],
				],
			],
			[
				"name"       => "菜单",
				"sub_button" => [
					[
						"type" => "view",
						"name" => "首页",
						"url"  => "http://tt.9ymc.top/home/index/index"
					],
					[
						"type" => "view",
						"name" => "服务",
						"url"  => "http://tt.9ymc.top/home/index/fuwu"
					],
					[
						"type" => "view",
						"name" => "发现",
						"url"  => "http://tt.9ymc.top/home/index/fuwu"
					],
				],
				"type" => "click",
				"name" => "今日歌曲",
				"key"  => "V1001_TODAY_MUSIC"
			],
		];
		$app = new Application($this->_options);
		$menu = $app->menu;
		$menu->add($buttons);

	}
}
