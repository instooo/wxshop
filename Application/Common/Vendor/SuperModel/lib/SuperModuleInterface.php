<?php
//����ӿ�
interface SuperModuleInterface{
	/**
     * �����������
     *
     * �κ�һ�����඼���и÷�������ӵ��һ������
     *@param array $target ���Ŀ�꣬������һ���������Լ�������
     */
    public function activate(array $target);
}