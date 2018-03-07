<?php
/**
 * Created by DaiLinLIn.
 * User: Administrator
 * Date: 2017/1/4
 * Time: 19:26
 * for: app接口数据返回类
 */

namespace Org\Util;


class Response
{
    /**
     * 按综合通信方式输出数据
     * $code 状态码   200 成功    400 失败
     * $message 提示信息
     * $data 数据
     */
    public static function apiReturn($code,$msg="",$data=array(),$type='JSON') {
        $type=$type?$type:'JSON';
        $result=array(
            'code'=>$code,
            'msg'=>urlencode($msg),
            'data'=>$data,
        );
        switch (strtoupper($type)){
            case 'JSON' :
                // 返回JSON数据格式到客户端 包含状态信息
                header('Content-Type:application/json; charset=utf-8');
                exit(urldecode(json_encode($result,JSON_FORCE_OBJECT)));
            case 'XML'  :
                // 返回xml格式数据
                header('Content-Type:text/xml; charset=utf-8');
                exit(xml_encode($result));
            case 'JSONP':
                // 返回JSON数据格式到客户端 包含状态信息
                header('Content-Type:application/json; charset=utf-8');
                $handler  =   isset($_GET[C('VAR_JSONP_HANDLER')]) ? $_GET[C('VAR_JSONP_HANDLER')] : C('DEFAULT_JSONP_HANDLER');
                exit($handler.'('.json_encode($result).');');
            case 'EVAL' :
                // 返回可执行的js脚本
                header('Content-Type:text/html; charset=utf-8');
                exit($result);

        }
    }

    /**
     * 按json格式封装数据
     * $code 状态码
     * $message 提示信息
     * $data 数据
     */
    public static function json($code,$msg="",$data=array()){
        if(!is_numeric($code)){
            return '';
        }
        $result = array(
            'code'=>$code,
            'message'=>$msg,
            'data'=>$data
        );

        header('Content-Type:application/json; charset=utf-8');
        exit(json_encode($result,JSON_FORCE_OBJECT));
    }

    /**
     * 按xml格式封装数据
     * $code 状态码
     * $message 提示信息
     * $data 数据
     */
    public static function xml($code,$message,$data){
        if(!is_numeric($code)){
            return '';
        }
        $result = array(
            'code'=>$code,
            'message'=>$message,
            'data'=>$data
        );
        // 返回xml格式数据
        header('Content-Type:text/xml; charset=utf-8');
        exit(xml_encode($result));
    }

}