<?php
//����ӿ�
interface ContentInterface{
	//��ȡģ���ֶκ�����
    function getFields($ext);	
	
	//��ȡ��ʼ��html
	function get_html($ext);
	
	//�༭html	
	function edit_html($info);
}