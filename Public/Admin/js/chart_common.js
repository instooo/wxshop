$(".filter_show").click(function(){
	$(".filter_a").toggleClass("ant-menu-hidden");
});
layui.use('laydate', function(){
  var laydate = layui.laydate;  
  //执行一个laydate实例
  laydate.render({
	elem: '#start_time' //指定元素
  });
  //常规用法
	laydate.render({
	  elem: '#end_time'
	});
});