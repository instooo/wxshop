<?php
/**
 * Created by DaLinLin.
 * User: Administrator
 * Date: 2017/1/4
 * Time: 17:01
 */

/**验证sign
 * @param array $data
 * @param $sign
 * @param array $notValidate
 * @param bool $ksort
 * @return bool
 */
function validate_sign($data=array(),$sign,$notValidate=array(),$ksort=true){
    if(!isset($data)||$sign==''){
        return false;
    }

    if(isset($notValidate)){
        foreach($notValidate as $value){
            unset($data[$value]);
        }
    }

    //数组进行升序排序
    $ksort?ksort($data): krsort($data);
    if(md5(implode('',$data))!=$sign){
        return false;
    }else{
        return true;
    }

}
