$(function(){	
	$('.ant-menu-submenu-title').bind('click',menu_tab);
	is_iphone();
	
})
 function close_left(){
	 $(".ant-layout-sider").toggle();
	 $(".ant-layout-sider").css("position","absolute");
	 $(".drawer-bg").remove();
 }
 
function is_iphone(){		
		var sUserAgent = navigator.userAgent.toLowerCase();    
		var bIsIpad = sUserAgent.match(/ipad/i) == "ipad";    
		var bIsIphoneOs = sUserAgent.match(/iphone os/i) == "iphone os";    
		var bIsMidp = sUserAgent.match(/midp/i) == "midp";    
		var bIsUc7 = sUserAgent.match(/rv:1.2.3.4/i) == "rv:1.2.3.4";    
		var bIsUc = sUserAgent.match(/ucweb/i) == "ucweb";    
		var bIsAndroid = sUserAgent.match(/android/i) == "android";    
		var bIsCE = sUserAgent.match(/windows ce/i) == "windows ce";    
		var bIsWM = sUserAgent.match(/windows mobile/i) == "windows mobile";    
		if (bIsIpad || bIsIphoneOs || bIsMidp || bIsUc7 || bIsUc || bIsAndroid || bIsCE || bIsWM ){	
			fangda();
			$(".ant-layout-sider").hide();
			$(".trigger___3Dsd1").unbind("click",caidan_a);
			$(".trigger___3Dsd1").bind("click",caidan_b);			
		}else{				
			$(".ant-layout-sider").show();
			$(".ant-layout-sider").css("position","static");
			$(".trigger___3Dsd1").unbind("click",caidan_b);
			$(".trigger___3Dsd1").bind("click",caidan_a);
		}   
		$(".drawer-bg").remove();
}
function caidan_b(){
	$(".ant-layout-sider").toggle();
	$(".ant-layout-sider").css("position","absolute");
	$("body").append('<div class="drawer-bg" style="opacity: .3;" onclick="close_left()"></div>');
}
function caidan_a(){
	var wid=$(".ant-layout-sider").width();
	if(wid>250){
		shuoxiao();			
	}else{
		fangda();
	}		
	$(".ant-menu-root").toggleClass("ant-menu-inline-collapsed");
	$(".ant-menu-root").toggleClass("ant-menu-vertical");
	$(".ant-menu-root").toggleClass("ant-menu-inline");
	$(".ant-menu-submenu").toggleClass("ant-menu-submenu-inline");
	$(".ant-menu-submenu").toggleClass("ant-menu-submenu-vertical");
}
function shuoxiao(){
		$(".ant-menu-submenu-selected .ant-menu-sub").addClass("ant-menu-hidden");
		$(".ant-layout-sider").css({
			"flex": "0 0 80px",
			"max-width":"80px", 
			"min-width":"80px",
			"width": "80px"			
		});		
		$(".ant-menu-submenu-title").unbind("click",menu_tab);
		$(".ant-menu-submenu-title").unbind({'mouseenter':min_menu_tab,'mouseleave':min_menu_tab_leave,});
		$('.ant-menu-submenu-title').bind({'mouseenter':min_menu_tab,'mouseleave':min_menu_tab_leave});	
}
function fangda(){
	$(".ant-menu-submenu-selected .ant-menu-sub").removeClass("ant-menu-hidden");
	$(".ant-layout-sider").css({
		"flex": "0 0 256px",
		"max-width":"256px", 
		"min-width":"256px",
		"width": "256px"			
	});		
	$('.ant-menu-submenu-title').unbind({'mouseenter':min_menu_tab,'mouseleave':min_menu_tab_leave});	
	$('.ant-menu-submenu-title').unbind('click',menu_tab);	
	$('.ant-menu-submenu-title').bind('click',menu_tab);		
}
//菜单的展开和收缩
function menu_tab(){		
	$(this).parent().siblings().find(".ant-menu-sub").addClass("ant-menu-hidden");
	$(this).parent().siblings().removeClass("ant-menu-submenu-selected");	
	$(this).siblings("ul").toggleClass("ant-menu-hidden");
	$(this).parent().toggleClass("ant-menu-submenu-selected");	
}
//菜单缩小后的操作
function min_menu_tab(){
	var tophg= ($(this).parent().index()+1)*44+40;	
	var tag=$(this).siblings("ul").attr("data_tag");		
	if(typeof(tag)!="undefined"){
		var prehtm='<div style="position: absolute; top: 0px; left: 0px; width: 100%;"><div>';
		prehtm+='<div class="ant-menu-submenu ant-menu-submenu-popup ant-menu-dark ant-menu-submenu-placement-rightTop"';
		prehtm+='style="left: 81px; top: '+tophg+'px;" id="'+tag+'">';       
		//获取到菜单的内容
		prehtm += $(this).siblings(".ant-menu-sub").prop("outerHTML");
		prehtm+="</div></div>";
		if($("#"+tag).length>0){}else{$("body").append(prehtm);}
		$("#"+tag+">ul").removeClass("ant-menu-hidden");	
		$("#"+tag+">ul").removeClass("ant-menu-inline");
		$("#"+tag+">ul").addClass("ant-menu-vertical");
		$(".ant-menu-submenu-placement-rightTop").bind({'mouseenter':delay_close,'mouseleave':delay_close_a});
		
	}		
}	
function min_menu_tab_leave(){
	var tophg= ($(this).parent().index()+1)*44+40;	
	var tag=$(this).siblings("ul").attr("data_tag");		
	if(typeof(tag)!="undefined"){
		var prehtm='<div style="position: absolute; top: 0px; left: 0px; width: 100%;"><div>';
		prehtm+='<div class="ant-menu-submenu ant-menu-submenu-popup ant-menu-dark ant-menu-submenu-placement-rightTop"';
		prehtm+='style="left: 84px; top: '+tophg+'px;" id="'+tag+'">';       
		//获取到菜单的内容
		prehtm += $(this).siblings(".ant-menu-sub").prop("outerHTML");
		prehtm+="</div></div>";
		if($("#"+tag).length>0){}else{$("body").append(prehtm);}						
		$("#"+tag+">ul").addClass("ant-menu-hidden");
		$("#"+tag+">ul").removeClass("ant-menu-inline");
		$("#"+tag+">ul").addClass("ant-menu-vertical");
		$(".ant-menu-submenu-placement-rightTop").bind({'mouseenter':delay_close,'mouseleave':delay_close_a});		
	}		
}
function delay_close(){					
	$(this).find("ul").removeClass("ant-menu-hidden");
}	
function delay_close_a(){			
	$(this).find("ul").addClass("ant-menu-hidden");		
}	
$(window).resize(function() {
  is_iphone();  
});	