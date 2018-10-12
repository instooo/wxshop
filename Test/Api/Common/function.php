<?php

/**
 * post json
 * @param $url
 * @param $data
 * @return mixed
 */
function post_json($url, $data) {
    $curl = curl_init($url);
    if (class_exists ( '\CURLFile' )) {//php5.5跟php5.6中的CURLOPT_SAFE_UPLOAD的默认值不同
        curl_setopt ( $curl, CURLOPT_SAFE_UPLOAD, true );
    }
    curl_setopt($curl, CURLOPT_HEADER, 0);//过滤头部
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);//获取的信息以文件流的形式返回，而不是直接输出。
    curl_setopt($curl,CURLOPT_POST,true); // post传输数据
    curl_setopt($curl,CURLOPT_POSTFIELDS,$data);// post传输数据
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
    $responseText = curl_exec($curl);
    curl_close($curl);

    return $responseText;
}