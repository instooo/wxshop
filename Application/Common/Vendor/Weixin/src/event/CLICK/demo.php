<?php
	$textTpl = $this->xml();
	$responsContent="抱歉，暂时没有您要查看的内容~";
	$resultStr = sprintf($textTpl, $FromUserName, $toUserName, $time,$responsContent);
	echo $resultStr;
?>