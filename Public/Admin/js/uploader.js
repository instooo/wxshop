String.prototype.trim = function (char, type) {
  if (char) {
    if (type == 'left') {
      return this.replace(new RegExp('^\\'+char+'+', 'g'), '');
    } else if (type == 'right') {
      return this.replace(new RegExp('\\'+char+'+$', 'g'), '');
    }
    return this.replace(new RegExp('^\\'+char+'+|\\'+char+'+$', 'g'), '');
  }
  return this.replace(/^\s+|\s+$/g, '');
};

//upload('#picker-down','name');
function upload(id,uploadname){
	var name_str = uploadname;
	uploadname = WebUploader.create({
		pick: {
			id: id,				
			multiple: false			
		},
		formData: {},		
		compress:false,
		auto: false,		 
		fileVal: 'sourceFile',      // 设置文件上传域的name
		disableGlobalDnd: true,     // 禁掉全局的拖拽功能。这样不会出现图片拖进页面的时候，把图片打开。
		accept: {
			title: '源文件支持格式',
			extensions: 'gif,jpg,jpeg,bmp,png,mp4,swf,flv'
		},
		swf: '/Public/webuploader/Uploader.swf',
		server: '/upload/uploadImage_do',
		threads: 1,
		fileNumLimit: 1,
		fileSizeLimit: 1.5 * 1024 * 1024,           // 300 M
		fileSingleSizeLimit: 1.5 * 1024 * 1024,     // 300 M
		duplicate: false
	});	
	
	// 文件加入队列时触发
	uploadname.on('filesQueued', function(file) {		
		// 打点，开始上传
		timeMark = new Date().getTime();
		uploadname.upload();		
		addPreviewFile(file);
		
	});	
	// 文件加入队列报错时触发
	uploadname.on('error', function(code) {
		var errMsg = '';
		switch (code) {
			case 'Q_EXCEED_NUM_LIMIT':
				errMsg = "预览图只能上传一张";
				break;
			case 'Q_EXCEED_SIZE_LIMIT':
				errMsg = "预览图大小超出限制（2M）";
				break;
			case 'Q_TYPE_DENIED':
				errMsg = "预览图只支持jpg、png、gif格式";
				break;
			case 'F_DUPLICATE':
				errMsg = "预览图已经在上传当中，请勿重复添加";
				break;
			case 'F_EXCEED_SIZE':
				errMsg = "预览图大小超出限制（2M）";
		}
		$("[data-tag='preview']").hide();
		$("[data-name='preview']").html("<i></i>" + errMsg).show();			
	});	

	// 文件上传过程当中触发
	uploadname.on('uploadProgress', function(file, percentage) {
		var $li = $('#preview_' + file.id);		
		// 进度条延迟展示，异步获取statusText
		setTimeout(function(){
			// 文件上传被拒绝
			if (file.statusText == 'abort') {
				// 比如服务器出现问题
				// 提示错误信息
				$li.find('.preview-progress').html(
					'<p class="progress-line" style="width: 100%"></p>' +
					'<p class="word-notice">服务器异常，上传中断，请重新上传</p>'
				);
				// 移除上传文件
				uploadname.cancelFile(file);
				uploadname.removeFile(file, true);
				// 移除上传文本框
				$li.fadeOut(3000, function(){
					$(this).remove();
				});
				// 清除input文本
				$("input[name='"+name_str+"']").val('');
			} else {
				$li.find('.word-notice').html('上传中<span class="progress-percent"></span>');
				$li.find('.progress-percent').text(parseInt(percentage * 100) + '%');
				$li.find('.progress-line').css('width', percentage * 100 + '%');
			}
		}, 10);
		// 上传过程当中删除图片
		$li.on('click', '.position-ab', function() {
			$li.find('.preview-progress').html(
				'<p class="progress-line" style="width: 100%"></p>' +
				'<p class="word-notice">删除成功</p>'
			);
			uploadname.cancelFile(file);
			uploadname.removeFile(file, true);
			// 移除上传文本框
			$li.fadeOut(3000, function(){
				$(this).remove();
			});
			// 清除input文本
			$("input[name='"+name_str+"']").val('');
		});
	});

	// 文件上传成功后触发
	uploadname.on('uploadSuccess', function(file, response) {
		var $li = $('#preview_' + file.id);
		if (response.status == false) {       // 服务器验证失败
			// 提示错误信息
			$("[data-tag='preview']").hide();
			$("[data-name='preview']").html("<i></i>" + response.content).show();
			// 提示上传失败
			$li.find('.preview-progress').html(
				'<p class="progress-line" style="width: 100%"></p>' +
				'<p class="word-notice">上传失败</p>'
			);
			// 移除上传文件
			uploadname.removeFile(file, true);
			// 移除上传文本框
			$li.fadeOut(3000, function(){
				$(this).remove();
			});
			// 清除input文本
			$("input[name='"+name_str+"']").val('');
		} else {                // 服务器验证成功
			var yltName = response.content;
			$("input[name='"+name_str+"']").val(yltName);
			// 提示上传成功，延迟200ms防止抖动
			setTimeout(function(){
				$li.find('.preview-progress').html(
						'<p class="progress-line" style="width: 100%"></p>' +
						'<p class="word-notice">上传成功</p>'
				);
				$(".notice").html('');
			}, 200);
			// 上传成功之后点击删除图片
			$li.on('click', '.position-ab', function(){
				$.ajax({
					type : "POST",
					url  : "/upload/deleteFile",
					data : {"yltName": yltName},
					dataType : 'json',
					success  : function(res) {
						if (res.status == true) {   // 删除成功
							$li.find('.preview-progress').html(
								'<p class="progress-line" style="width: 100%"></p>' +
								'<p class="word-notice">删除成功</p>'
							);
							// 移除上传文件
							uploadname.removeFile(file, true);
							// 移除上传文本框
							$li.fadeOut(3000, function(){
								$(this).remove();
							});
							// 清除input文本
							$("input[name='"+name_str+"']").val('');
						} else {    // 删除失败

						}
					}
				});
			});
		}
	});
	// 不管成功或者失败，文件上传完成时触发
	uploadname.on('uploadComplete', function(file){
		// 重置打点+计数
		timeMark  = new Date().getTime();
		frequency = -1;
	});		
	
	// 添加源文件
	function addPreviewFile(file) {
		// 缩略图大小
		var thumbnailWidth  = 172;
		var thumbnailHeight = 131;
		
		// 生成缩略图		
		$("input[name='"+name_str+"']").closest('.layui-form-item').find('.wait-upload').html(
			'<div class="preview-small imgWrap fl" id="preview_' + file[0]['id'] + '">' +
				'<i class="position-ab"></i>' +
				'<div class="preview-progress">' +
					'<p class="progress-line" style="width: 0%"></p>' +
					'<p class="word-notice">等待中' +
						'<span class="progress-percent"></span>' +
					'</p>' +
				'</div>' +
			'</div>'
		);
		var $li = $('#preview_' + file[0]['id']);
		// 生成缩略图
		uploadname.makeThumb(file, function(error, src) {
			if (error) {
				$li.text('不能预览');
			} else {
				$li.css('background', 'url(' + src + ') no-repeat center center');
			}
		}, thumbnailWidth, thumbnailHeight);
		// 取消上传
		$li.on('click', '.position-ab', function() {
			$li.find('.preview-progress').html(
				'<p class="progress-line" style="width: 100%"></p>' +
				'<p class="word-notice">删除成功</p>'
			);
			uploadname.cancelFile(file);
			uploadname.removeFile(file, true);
			// 移除上传文本框
			$li.fadeOut(3000, function(){
				$(this).remove();
			});
			// 清除input文本
			$("input[name='detail_preview_src']").val('');
		});
	}
}


function upload_many(id,uploadname,num){
	var name_str = uploadname;
	uploadname = WebUploader.create({
		pick: {
			id: id,				
			multiple: true			
		},
		formData: {},
		compress: false,
		auto: false,		 
		fileVal: 'sourceFile',      // 设置文件上传域的name
		disableGlobalDnd: true,     // 禁掉全局的拖拽功能。这样不会出现图片拖进页面的时候，把图片打开。
		accept: {
			title: '源文件支持格式',
			extensions: 'gif,jpg,jpeg,bmp,png,mp4,swf,flv'
		},
		swf: '/Public/webuploader/Uploader.swf',
		server: '/upload/uploadImage_do',
		threads: num,
		fileNumLimit: num,
		fileSizeLimit: 30 * 1024 * 1024,           // 300 M
		fileSingleSizeLimit: 30 * 1024 * 1024,     // 300 M
		duplicate: true
	});	
	
	// 文件加入队列时触发
	uploadname.on('filesQueued', function(file) {		
		// 打点，开始上传
		timeMark = new Date().getTime();
		uploadname.upload();		
		addPreviewFile(file);
	});	
	// 文件加入队列报错时触发
	uploadname.on('error', function(code) {
		var errMsg = '';
		switch (code) {
			case 'Q_EXCEED_NUM_LIMIT':
				errMsg = "预览图只能上传一张";
				break;
			case 'Q_EXCEED_SIZE_LIMIT':
				errMsg = "预览图大小超出限制（2M）";
				break;
			case 'Q_TYPE_DENIED':
				errMsg = "预览图只支持jpg、png、gif格式";
				break;
			case 'F_DUPLICATE':
				errMsg = "预览图已经在上传当中，请勿重复添加";
				break;
			case 'F_EXCEED_SIZE':
				errMsg = "预览图大小超出限制（2M）";
		}
		$("[data-tag='preview']").hide();
		$("[data-name='preview']").html("<i></i>" + errMsg).show();			
	});	

	// 文件上传过程当中触发
	uploadname.on('uploadProgress', function(file, percentage) {
		var $li = $('#preview_' + file.id);
		// 进度条延迟展示，异步获取statusText
		setTimeout(function(){
			// 文件上传被拒绝
			if (file.statusText == 'abort') {
				// 比如服务器出现问题
				// 提示错误信息
				$li.find('.preview-progress').html(
					'<p class="progress-line" style="width: 100%"></p>' +
					'<p class="word-notice">服务器异常，上传中断，请重新上传</p>'
				);
				// 移除上传文件
				uploadname.cancelFile(file);
				uploadname.removeFile(file, true);
				// 移除上传文本框
				$li.fadeOut(3000, function(){
					$(this).remove();
				});
			} else {
				$li.find('.word-notice').html('上传中<span class="progress-percent"></span>');
				$li.find('.progress-percent').text(parseInt(percentage * 100) + '%');
				$li.find('.progress-line').css('width', percentage * 100 + '%');
			}
		}, 10);
		// 上传过程当中删除图片
		$li.on('click', '.position-ab', function() {
			$li.find('.preview-progress').html(
				'<p class="progress-line" style="width: 100%"></p>' +
				'<p class="word-notice">删除成功</p>'
			);
			uploadname.cancelFile(file);
			uploadname.removeFile(file, true);
			// 移除上传文本框
			$li.fadeOut(3000, function(){
				$(this).remove();
			});			
		});
	});

	// 文件上传成功后触发
	uploadname.on('uploadSuccess', function(file, response) {		
		var $li = $('#preview_' + file.id);
		if (response.status == false) {       // 服务器验证失败
			// 提示错误信息
			$("[data-tag='preview']").hide();
			$("[data-name='preview']").html("<i></i>" + response.content).show();
			// 提示上传失败
			$li.find('.preview-progress').html(
				'<p class="progress-line" style="width: 100%"></p>' +
				'<p class="word-notice">上传失败</p>'
			);
			// 移除上传文件,重队列中取消
			uploadname.removeFile(file, true);
			// 移除上传文本框
			$li.fadeOut(3000, function(){
				$(this).remove();
			});			
		} else {                // 服务器验证成功
			var yltName = response.content;
			var nowval = $("input[name='"+name_str+"']").val();
			$("input[name='"+name_str+"']").val(nowval+"|"+yltName);				
			$li.attr("data-src",yltName);
			// 提示上传成功，延迟200ms防止抖动
			setTimeout(function(){
				$li.find('.preview-progress').html(
						'<p class="progress-line" style="width: 100%"></p>' +
						'<p class="word-notice">上传成功</p>'
				);
				$(".notice").html('');
			}, 200);
			// 上传成功之后点击删除图片
			$li.on('click', '.position-ab', function(){					
				// 清除input文本
				var datasrc=$(this).parents(".preview-small").attr('data-src');	
				
				$.ajax({
					type : "POST",
					url  : "/upload/deleteFile",
					data : {"yltName": yltName},
					dataType : 'json',
					success  : function(res) {
						if (res.status == true) {   // 删除成功
							$li.find('.preview-progress').html(
								'<p class="progress-line" style="width: 100%"></p>' +
								'<p class="word-notice">删除成功</p>'
							);
						
							// 移除上传文件
							uploadname.removeFile(file, true);
							// 移除上传文本框
							$li.fadeOut(3000, function(){
								$(this).remove();
							});
							var nowval = $("input[name='"+name_str+"']").val();							
							var new_val = nowval.replace(datasrc,'');							
							new_val = new_val.replace("||",'|');						
							$("input[name='"+name_str+"']").val(trim(new_val,"|"));		
						} else { 
    
						}
					}
				});
			});
		}
	});
	 //不管成功或者失败，文件上传完成时触发
	uploadname.on('uploadComplete', function(file){
		// 重置打点+计数
		timeMark  = new Date().getTime();
		frequency = -1;
	});		
	
	
	function count(o){
		var t = typeof o;
		if(t == 'object'){
			var n = 0;
			for(var i in o){
				n++;
			}
		}
		return n;
	}
	
	function writeObj(obj){ 
		var description = ""; 
		for(var i in obj){   
			var property=obj[i];   
			description+=i+" = "+property+"\n";  
		}   
		console.log(description); 
	} 

	// 添加源文件
	function addPreviewFile(file) {
		var length = count(file);
		// 缩略图大小
		var thumbnailWidth  = 172;
		var thumbnailHeight = 131;
		var test= {};
		for(var j=0;j<length;j++){
			// 生成缩略图		
			$("input[name='"+name_str+"']").closest('.layui-form-item').find('.wait-upload').append(
				'<div class="preview-small imgWrap fl" id="preview_' + file[j]['id'] + '">' +
					'<i class="position-ab"></i>' +
					'<div class="preview-progress">' +
						'<p class="progress-line" style="width: 0%"></p>' +
						'<p class="word-notice">等待中' +
							'<span class="progress-percent"></span>' +
						'</p>' +
					'</div>' +
				'</div>'
			);	
			var $li = $('#preview_' + file[j]['id']);
			getThunmb($li,file[j]);			
			// 取消上传
			$li.on('click', '.position-ab', function() {
				$(this).parents(".preview-small").find('.preview-progress').html(
					'<p class="progress-line" style="width: 100%"></p>' +
					'<p class="word-notice">删除成功</p>'
				);
				uploadname.cancelFile(file);
				uploadname.removeFile(file, true);
				// 移除上传文本框
				$(this).parents(".preview-small").fadeOut(200, function(){
					$(this).remove();
				});
			});	
		}
	}
	function getThunmb(id,file){
			var thumbnailWidth  = 172;
			var thumbnailHeight = 131;
		// 生成缩略图
			uploadname.makeThumb(file, function(error, src) {
				id.css('background', 'url('+src+') no-repeat center center');
			}, thumbnailWidth, thumbnailHeight);
	}
}

$(function(){
	$(".havepic").click(function(){
		// 清除input文本
		var datasrc=$(this).parents(".preview-small").attr('data-src');
		var nowval = $(this).parents(".layui-form-item").find("input").val();		
		var new_val = nowval.replace(datasrc,'');
		new_val = new_val.replace("||",'|');
		$(this).parents(".layui-form-item").find("input").val(new_val);			
		// 移除上传文本框
		$(this).parents(".preview-small").fadeOut(500, function(){
			$(this).remove();
		});
	})

})