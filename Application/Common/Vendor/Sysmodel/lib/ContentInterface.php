<?php
//����ӿ�
interface ContentInterface{
	//��ȡģ���ֶκ�����
    function getFields();	
	
	//��ȡ��ʼ��html
	function get_html();
	
	//�༭html	
	function edit_html($info);
}