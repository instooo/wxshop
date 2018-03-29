<?php
/**
 * Created by Notepad++.
 * User: dengxiaolong
 * Date: 2018/03/21
 * Time: 11:08
 */

namespace Admin\Logic\Common;
class Charts {
	/*柱状图
	*接受option格式
	*/	
	public static function zhuzhuang($datalist,$title,$xfield,$yfield,$cur="￥"){	
		$arr = array();
		$arr['title']['text'] = $title;
		$arr['tooltip']=(object)array();
		$arr['yAxis']= (object)array();		
		$arr['series']['name']="safa";
		$arr['series']['type']='bar';		
		foreach($datalist as $key=>$val){			
			$arr['xAxis']['data'][$val[$xfield]]=$val[$xfield];
			$arr['series']['data'][$val[$xfield]]+=$val[$yfield];
			$sum+=$val[$yfield];
		}	
		$arr['title']['text'] = $title."(总计：".$sum."$cur)";
		$arr['xAxis']['data'] = 	array_values($arr['xAxis']['data']);
		$arr['series']['data'] = array_values($arr['series']['data']);		
		return json_encode($arr);
	}
	//柱状图接受数组形式
	public static function zhuzhuang_duo($datalist,$title,$xfield,$yfield,$switch,$cur=array("￥","个"),$type="bar"){	
		$arr = array();	
		if(empty($switch)){
			foreach($datalist as $key=>$val){
				foreach($yfield as $v){	
					$tmpyfield[$v][$val[$xfield]]+=$val[$v];
					$tmpsum[$v]+=$val[$v];
				}
			}	
		}else{			
			foreach($datalist as $key=>$val){
				foreach($yfield as $v){	
					$tmpyfield[$v][$switch[$val[$xfield]]]+=$val[$v];
					$tmpsum[$v]+=$val[$v];
				}
			}	
		}		
		foreach($yfield as $key=>$val){				
			$arr[$val]['tooltip']=(object)array();
			if(!isMobile()){
				if($xfield!=="date"){
					arsort($tmpyfield[$val]);				
				}
				$arr[$val]['xAxis']['data']= array_keys($tmpyfield[$val]);
				$arr[$val]['yAxis'] = (object)array();	
			}else{
				if($xfield!=="date"){
					asort($tmpyfield[$val]);				
				}
				$arr[$val]['yAxis']['data']= array_keys($tmpyfield[$val]);
				//$arr[$val]['xAxis'] = (object)array();
				$arr[$val]['xAxis']['axisLabel']['interval'] =0; 
				$arr[$val]['xAxis']['axisLabel']['rotate'] =45;
				$arr[$val]['xAxis']['axisLabel']['margin'] =2;
				$arr[$val]['grid']['left']="3%";
				$arr[$val]['grid']['right']="4%";
				$arr[$val]['grid']['containLabel']=true;
				$arr[$val]['grid']['y2']=10;
			}			
			$arr[$val]['series']['name']=$title[$key];
			$arr[$val]['series']['type']=$type;
			$arr[$val]['series']['label']['normal']=array("show"=>true,"position"=>"inside");
			$arr[$val]['title']['show'] = true;	
			$arr[$val]['title']['text'] = $title[$key]	."(总计：".$tmpsum[$val]."$cur[$key])";				
			$arr[$val]['series']['data'] = array_values($tmpyfield[$val]);
			$arr[$val]['sum'] = $tmpsum[$val];
		}			
		return $arr;
	}	
	
	//饼图接受数组形式
	// tooltip : {
    //    trigger: 'item',
    //    formatter: "{a} <br/>{b} : {c} ({d}%)"
    //},
	public static function bingzhuang_duo($datalist,$title,$xfield,$yfield,$switch,$cur=array("￥","个")){	
		$arr = array();	
		if(empty($switch)){
			foreach($datalist as $key=>$val){
				foreach($yfield as $v){	
					$tmpyfield[$v][$val[$xfield]]['value']+=$val[$v];
					$tmpyfield[$v][$val[$xfield]]['name']=$val[$xfield];
					$tmpsum[$v]+=$val[$v];
				}
			}		
		}else{			
			foreach($datalist as $key=>$val){
				foreach($yfield as $v){	
					$tmpyfield[$v][$val[$xfield]]['value']+=$val[$v];
					$tmpyfield[$v][$val[$xfield]]['name']=$switch[$val[$xfield]];
					$tmpsum[$v]+=$val[$v];
				}
			}	
		}				
		foreach($yfield as $key=>$val){	
			$arr[$val]['tooltip']['trigger']='item';
			$arr[$val]['tooltip']['formatter']='{b} : {c} ({d}%)';
			$arr[$val]['title']['text'] = $title[$key]	."(总计：".$tmpsum[$val]."$cur[$key])";
			$arr[$val]['series']['name']=$title[$key];
			$arr[$val]['series']['type']='pie';	
			$arr[$val]['series']['radius']="55%";
			$arr[$val]['series']['data'] = array_values($tmpyfield[$val]);
		}		
		return $arr;
	}
	
	
	
}