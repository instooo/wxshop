<?php
/**
 * Created by DaiLinLin.
 * User: api 验证帮助类
 * Date: 2017/1/5
 * Time: 11:22
 */

namespace Org\Util;


class ApiHelper
{
    /**
     * 验证请求时间是否超时，默认30秒
     * @param $time 请求时间
     * @param int $differ 超时时间差
     * @return bool
     */
    public static function timeout($time,$differ=30){

        if(time()-$time>$differ){
            Response::apiReturn(-406,'请求时间失效');
        }else{
           return true;
        }
    }

    /**
     * 验证必要参数
     * @param array $data 验证数据
     * @param array $ignore 忽略数据 array('data1','data2')
     * @return bool
     */
    public static function mustParams($data=array(),$ignore=array()){

        if(!isset($data)){
            Response::apiReturn(404,'系统错误');
        }

        if(isset($ignore)){
            foreach($ignore as $value){
                unset($data[$value]);
            }
        }

        if(in_array(null,$data)){
            Response::apiReturn(-101,'参数不全');
        }else{
            return true;
        }
    }

    /**
     * 验证sign
     * @param array $data 加密参数
     * @param $sign
     * @param array $notValidate 不参与验证的数据array('data1','data2')
     * @param bool $ksort sign参数按照数组排序方式加密
     * @return bool
     */
    public static function validateSign($data=array(),$sign,$notValidate=array(),$ksort=true){
        if(!isset($data)||$sign==''){
            Response::apiReturn(404,'系统错误');
        }

        if(isset($notValidate)){
            foreach($notValidate as $value){
                unset($data[$value]);
            }
        }

        if(isset($data['secret'])){
            $secret = $data['secret'];
            unset($data['secret']);
        }

        //数组进行升序排序
        $ksort?ksort($data): krsort($data);
        $data['secret'] = $secret;
        if(md5(implode('',$data))!=$sign){
            Response::apiReturn(-102,'sign错误');
        }else{
            return true;
        }
    }

    /**去除数组的某些元素
     * @param array $data 原始数组
     * @param array $unset 去除元素 array('data1','data2')
     * @return array
     */
    public static function dataUnset($data=array(),$unset=array()){
        if(!is_array($data)||!isset($data)){
            return $data;
        }

        if(isset($unset)){
            foreach($unset as $value){
                unset($data[$value]);
            }
        }

        return $data;
    }
}