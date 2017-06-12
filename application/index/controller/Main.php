<?php
namespace app\index\controller;

class Main
{
	//默认主页
    public function index()
    {
		return view('public/main');
    }
	
	public function top(){
		return view('public/top');
	}
	
	public function left(){
		return view('public/left');
	}
}
