$(function(){
	$(".trigger___3Dsd1").click(function(){
		var wid=$(".ant-layout-sider").width();
		if(wid>250){
			$(".ant-menu-submenu-selected .ant-menu-sub").addClass("ant-menu-hidden");
			$(".ant-layout-sider").css({
				"flex": "0 0 80px",
				"max-width":"80px", 
				"min-width":"80px",
				"width": "80px"			
			});		
			$(".ant-menu-submenu-title").unbind("click",menu_tab);
			$('.ant-menu-submenu-title').bind({'mouseenter':min_menu_tab,'mouseleave':min_menu_tab_leave,});	
		}else{
			$(".ant-menu-submenu-selected .ant-menu-sub").removeClass("ant-menu-hidden");
			$(".ant-layout-sider").css({
				"flex": "0 0 256px",
				"max-width":"256px", 
				"min-width":"256px",
				"width": "256px"			
			});	
			$('.ant-menu-submenu-title').bind('click',menu_tab);
			$('.ant-menu-submenu-title').unbind({'mouseenter':min_menu_tab,'mouseleave':min_menu_tab_leave,});	
		}		
		$(".ant-menu-root").toggleClass("ant-menu-inline-collapsed");
		$(".ant-menu-root").toggleClass("ant-menu-vertical");
		$(".ant-menu-root").toggleClass("ant-menu-inline");
		$(".ant-menu-submenu").toggleClass("ant-menu-submenu-inline");
		$(".ant-menu-submenu").toggleClass("ant-menu-submenu-vertical");
		
		
	})
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
			prehtm+='style="left: 84px; top: '+tophg+'px;" id="'+tag+'">';       
			//获取到菜单的内容
			prehtm += $(this).siblings(".ant-menu-sub").prop("outerHTML");
			prehtm+="</div></div>";
			if($("#"+tag).length>0){}else{$("body").append(prehtm);}
			$("#"+tag+">ul").toggleClass("ant-menu-hidden");	
			$("#"+tag+">ul").removeClass("ant-menu-inline");
			$("#"+tag+">ul").addClass("ant-menu-vertical");
			$(".ant-menu-submenu-placement-rightTop").bind({'mouseenter':delay_close,'mouseleave':delay_close_a,});
			
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
			//setTimeout(function(){
				$("#"+tag+">ul").toggleClass("ant-menu-hidden");
			//},200);
					
			$("#"+tag+">ul").removeClass("ant-menu-inline");
			$("#"+tag+">ul").addClass("ant-menu-vertical");
			$(".ant-menu-submenu-placement-rightTop").bind({'mouseenter':delay_close,'mouseleave':delay_close_a,});
			
		}		
	}
	function delay_close(){		
		$(this).find("ul").removeClass("ant-menu-hidden");		
	}	
	function delay_close_a(){		
		$(this).find("ul").addClass("ant-menu-hidden");		
	}
	$('.ant-menu-submenu-title').bind('click',menu_tab);	
	
})
