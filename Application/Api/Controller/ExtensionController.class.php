<?php
/**
 * Created by DaiLinLin.
 * User: Administrator
 * Date: 2017/1/3
 * Time: 20:05
 * for: 媒体广告相关的接口
 */

namespace Api\Controller;


use Org\Util\Response;
use Think\Exception;

class ExtensionController extends ApiController
{
    /**
     * 落地页点击API
     */
    public function click(){
        $data['cps_name'] = $_REQUEST['cpsname'];
        if(in_array(null,$data)){
            die;
        }
        $data['os'] = $_REQUEST['os'];
        if(!($data['os']=='android'||$data['os']=='ios')){
            die;
        }
        $data['addtime'] = time();
        $data['ip'] = get_client_ip(0,true);

        $flag = $this->is_Illegal();
        if($flag){
            $result = M('click_log','mygame_','DB_CONFIG_TONGJI')->data($data)->add();
        }


    }


    //判断来源是否合法
    function is_Illegal(){
        $flag=0;
        do{
            try{
                foreach(C('CHANNELFROM') as $value){
                    if(strpos($_SERVER['HTTP_REFERER'], $value)>0){
                        $flag=1;
                        break;
                    }
                }

            }catch(Exception $e){
                $flag=0;
                break;
            }
        }while(0);
        return $flag;
    }

    /**
     * 今日头条app广告点击统计
     */
    public function app_click(){
		$data['gid']=$_REQUEST['gid'];
        $data['adid']=$_REQUEST['adid'];
        $data['cid']=$_REQUEST['cid'];
        $data['imei']=$_REQUEST['imei'];
        $data['mac']=$_REQUEST['mac'];
        $data['androidid']=$_REQUEST['aandroidid'];
        $data['os']=$_REQUEST['os'];
        $data['timestamp']=$_REQUEST['timestamp'];
        $data['callback_url']=$_REQUEST['callback_url'];
        $data['addtime'] = time();			
		if($data['gid'] &&  $data['adid'] && $data['cid'] && $data['imei'] && $data['mac'] && $data['androidid'] && $data['timestamp'] && $data['callback_url']){
			$result = M('click_jrtt_app')->data($data)->add();
		}
        echo $result?'success':'error';
    }

    /**
     * 广点通app广告点击
     */
    public function gdt_click() {
        $return_data = array();
        do{
            $data['gid']=$_REQUEST['gid'];
            $data['muid']=$_REQUEST['muid'];
            $data['click_time']=$_REQUEST['click_time'];
            $data['click_id']=$_REQUEST['click_id'];
            $data['appid']=$_REQUEST['appid'];
            $data['advertiser_id']=$_REQUEST['advertiser_id'];
            $data['app_type']=$_REQUEST['app_type'];
            //$data['conv_type']=strtoupper($_REQUEST['conv_type']);

            if(in_array(null,$data)){
                $return_data = array('ret'=>-1,'msg'=>'The lack of necessary parameters');
                break;
            }
            $result = M('click_gdt_app')->data($data)->add();
            if($result){
                $return_data = array('ret'=>0,'msg'=>'ok');
                break;
            }else {
                $return_data = array('ret'=>-2,'msg'=>'fail');
                break;
            }
        }while(0);
        header('Content-Type:application/json; charset=utf-8');
		
        exit(json_encode($return_data));
    }

    /**
     * 陌陌广告点击
     */
    public function momo_click() {
        $data['gid'] = $_REQUEST['gid'];
        $data['click_time'] = $_REQUEST['ts'];
        $data['os'] = strtolower($_REQUEST['os']);
		
		$osArr = array(
			1	=>	'ios',
			2	=>	'android',
		);
        if ($osArr[$data['os']] == 'android') {
            $data['muid'] = strtolower($_REQUEST['imei']);
        }elseif ($osArr[$data['os']] == 'ios') {
            $data['muid'] = strtolower(md5($_REQUEST['idfa']));
        }
		$data['os'] = $osArr[$data['os']];
        $data['callback'] = $_REQUEST['callback'];

        if(in_array(null,$data)){
            header('Content-Type:application/json; charset=utf-8');
            exit(json_encode(array('ret'=>-1,'msg'=>'The lack of necessary parameters'),JSON_FORCE_OBJECT));
        }
        $result = M('click_momo_app')->data($data)->add();
        if($result){
            header('Content-Type:application/json; charset=utf-8');
            exit(json_encode(array('ret'=>0,'msg'=>'ok'),JSON_FORCE_OBJECT));
        }
    }

    /**
     * UC广告点击
     */
    public function uc_click() {
        $data['gid'] = $_REQUEST['gid'];
        $data['click_time'] = $_REQUEST['ts'];
        $data['callback'] = $_REQUEST['callback'];
        if (!$data['gid'] || !$data['click_time'] || !$data['callback']) {
            $this->ajaxReturn(array('ret'=>-1,'msg'=>'参数不全'), 'JSON');
        }
        $idfa = $_REQUEST['idfa'];
        $imei = $_REQUEST['imei'];
        if ($imei) {
            $data['muid'] = $imei;
            $data['os'] = 'android';
        }elseif ($idfa) {
            $data['muid'] = $idfa;
            $data['os'] = 'ios';
        }else {
            $this->ajaxReturn(array('ret'=>-2,'msg'=>'设备标识未知'), 'JSON');
        }
        $data['media'] = 'uc';
        if (!M('click_third_app')->add($data)) {
            $this->ajaxReturn(array('ret'=>-3,'msg'=>'数据保存失败'), 'JSON');
        }
        $this->ajaxReturn(array('ret'=>0,'msg'=>'数据保存成功'), 'JSON');
    }

    /**
     * wead转化
     */
    public function wead() {
		//file_get_contents('http://hd.7477.com/test/saveRequest?'.http_build_query($_REQUEST));
        $data['gid'] = $_REQUEST['gid'];
        $data['click_time'] = $_REQUEST['time'];
        $data['callback'] = urldecode($_REQUEST['callback']);
        if (!$data['gid'] || !$data['click_time'] || !$data['callback']) {
            $this->ajaxReturn(array('ret'=>-1,'msg'=>'参数不全'), 'JSON');
        }
        $idfa = $_REQUEST['idfa'];
        $imei = $_REQUEST['imei'];
        if ($imei && $imei != '__IMEIMD5__') {
            $data['muid'] = $imei;
            $data['os'] = 'android';
        }elseif ($idfa && $idfa != '__IDFAMD5__') {
            $data['muid'] = $idfa;
            $data['os'] = 'ios';
        }else {
            $this->ajaxReturn(array('ret'=>-2,'msg'=>'设备标识未知'), 'JSON');
        }
        $data['media'] = 'wead';
        if (!M('click_third_app')->add($data)) {
            $this->ajaxReturn(array('ret'=>-3,'msg'=>'数据保存失败'), 'JSON');
        }
        $this->ajaxReturn(array('ret'=>0,'msg'=>'数据保存成功'), 'JSON');
    }

    /**
     * 百度转化上报
     */
    public function baidu() {
        $data['gid'] = $_REQUEST['gid'];
        $data['advertiser_id'] = trim(htmlspecialchars($_REQUEST['userid']));
        $data['click_time'] = time();
        $data['callback'] = $_REQUEST['callback_url'];
        if (!$data['gid'] || !$data['advertiser_id'] || !$data['callback']) {
            $this->ajaxReturn(array('ret'=>-1,'msg'=>'参数不全'), 'JSON');
        }
        $idfa = $_REQUEST['idfa'];
        $imei = $_REQUEST['imei_md5'];
        if ($imei) {
            $data['muid'] = strtoupper($imei);
            $data['os'] = 'android';
        }elseif ($idfa) {
            $data['muid'] = strtoupper(md5($idfa));
            $data['os'] = 'ios';
        }else {
            $this->ajaxReturn(array('ret'=>-2,'msg'=>'设备标识未知'), 'JSON');
        }
        $data['media'] = 'baidu';
        if (!M('click_third_app')->add($data)) {
            $this->ajaxReturn(array('ret'=>-3,'msg'=>'数据保存失败'), 'JSON');
        }
        $this->ajaxReturn(array('ret'=>0,'msg'=>'数据保存成功'), 'JSON');
    }


}