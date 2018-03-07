<?php
/**
 * API接口基类
 * @author DaiLinLin
 * @config
 * return array(
		//'配置项'=>'配置值'
		"appid"=>"test",
		'appsecert'=> 'test',
		'appip'=>'',//允许访问的IP 为空不限制
		);
 */

namespace Api\Controller;
use Org\Util\Response;
use Think\Controller;


class ApiController extends Controller {


	/**
	 * 获取秘钥
	 * @param $gid
	 * @return bool
	 */
	protected function getSecret($gid){
		$game = M('game')->where(array('gid'=>$gid,'status'=>1))->find();

		if($game){
			return $game['secret'];
		}else{
			Response::apiReturn('406','游戏不存在');
		}
	}

	protected function getChannel($channel){
		$channel = M('channel')->where(array('short_name'=>$channel,'status'=>1))->find();
		if($channel){
			return $channel;
		}else{
			return 0;
		}
	}

    /**
     * 点击量统计
     */
    public function click() {
        $clickdata = array();
        $clickdata['cps_name'] = trim(htmlspecialchars($_REQUEST['cps_name']));
        if (!$clickdata['cps_name']) {
            $this->ajaxReturn(array('ret'=>-1,'msg'=>'渠道标识缺失'), 'JSONP');
        }
        $clickdata['ip'] = $clickdata['ip'] = get_client_ip(0, true);
        $clickdata['addtime'] = time();
        $clickdata['os'] = 'h5';
        $channel = M('channel')->where(array('short_name'=>$clickdata['cps_name']))->find();
        if (!$channel) {
            $this->ajaxReturn(array('ret'=>-2,'msg'=>'渠道标识不存在'), 'JSONP');
        }
        $click_log = M('click_log');
        /*
        $logmap = array();
        $logmap['cps_name'] = $clickdata['cps_name'];
        $logmap['addtime'] = array('gt', time()-10);
        $log = $click_log->where($logmap)->order('addtime desc')->select();
        $flag = false;
        foreach ($log as $val) {
            if ($val['ip'] == $clickdata['ip']) {
                $flag = true;
                break;
            }
        }
        if ($flag) {
            $this->ajaxReturn(array('ret'=>-3,'msg'=>'重复数据'), 'JSONP');
        }
        */
        $res = $click_log->add($clickdata);
        if (!$res) {
            $this->ajaxReturn(array('ret'=>-4,'msg'=>'数据保存失败'), 'JSONP');
        }
        $this->ajaxReturn(array('ret'=>0,'msg'=>'success'), 'JSONP');
    }

    /**
     * 页面按钮点击统计
     */
    public function clickPosition() {
        C('VAR_JSONP_HANDLER', 'jsonpCallback');
        $clickdata = array();
        $clickdata['position'] = trim(htmlspecialchars($_REQUEST['position']));
        $clickdata['cps_name'] = trim(htmlspecialchars($_REQUEST['cps_name']));
        if (!$clickdata['position'] || !$clickdata['cps_name']) {
            $this->ajaxReturn(array('ret'=>-1,'msg'=>'参数缺失'), 'JSONP');
        }
        $clickdata['ip'] = get_client_ip(0, true);
        $clickdata['addtime'] = time();

        $channel = M('channel')->where(array('short_name'=>$clickdata['cps_name']))->find();
        if (!$channel) {
            $this->ajaxReturn(array('ret'=>-2,'msg'=>'渠道标识不存在'), 'JSONP');
        }
        $res = M('web_click_log')->add($clickdata);
        if (!$res) {
            $this->ajaxReturn(array('ret'=>-4,'msg'=>'数据保存失败'), 'JSONP');
        }
        $this->ajaxReturn(array('ret'=>0,'msg'=>'success'), 'JSONP');
    }

}
	
