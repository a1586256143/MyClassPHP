(function() {
	var upload = function(ele, opt) {
		this.$element = ele, this.defaults = {
			// 所有的上传文件
			'allFiles': [] , 
			// 初始化的图片信息，可空，数据格式为 ['xxxx.png' , 'bbbb.png']
			'initData': [] , 
			// 初始化的图片ID信息，可空，数据格式为 [1 , 3] 
			'initCover': [] , 
			// 删除的图片ID数组，数据格式为 [1] 
			'deleteArray' : [] , 
			// 上传地址
			'uploadUrl': '' , 
			// 上传的图片存放在这个元素里面
			'pictureBox' : '.more-upload-content-box' , 
			// 删除按钮名称
			'deleteText' : '删除' , 
			// 开始上传按钮名称
			'uploadText' : '开始上传' , 
			// 清除所有按钮名称
			'clearText' : '清除所有' , 
			// 新上传图片隐藏域名称
			'new_cover_name' : 'new_cover_id' , 
			// 删除图片隐藏域名称
			'delete_cover_name' : 'delete_cover_id' , 
			// 提示信息存放的元素
			'tipBox' : '.more-upload-tips' , 
			// 全局提示信息
			'tips' : {
				'empty_pic' : '请选择图片后上传' , 
				'empty_upload_url' : '请设置上传地址' , 
				'success' : '上传成功，将不可更改！'
			}
		}, this.options = $.extend({}, this.defaults, opt)
	};

	upload.prototype = {
		/**
		 * 获取文件列表
		 * @return {[type]} [description]
		 */
		getFileList : function(){
			var newArray = [];
			$.each(this.options.allFiles , function(index , value){
				if(value != null || value != undefined){
					newArray.push(value);
				}
			})
			return newArray;
		} , 
		// 运行方法
		run: function() {
			this.buildTemplate();
			var selfThis = this;
			if(this.options.initData.length > 0){
				this.options.initData = JSON.parse(this.options.initData);
				this.options.initCovers = JSON.parse(this.options.initCovers);
				$.each(this.options.initData , function(index , value){
					var html = '<div class="more-upload-content-item more-upload-content-delete-' + index + '">' + 
									'<img src="' + value + '" alt="">' + 
									'<a href="javascript:void(0);" data-index="' + index + '">' + selfThis.options.deleteText + '</a>' + 
								'</div>';
					$(selfThis.options.pictureBox).append(html);
				})
			}

			// 选择文件
			$('.more-upload-button').change(function(e){
				selfThis.useFile(e , this , selfThis);
			});

			// 绑定删除事件
			$(document).on('click' , '.more-upload-content-item a' , function(){
				selfThis.removeItem(this , selfThis);
			})

			// 绑定清除全部事件
			$(document).on('click' , 'a.go_clearall' , function(){
				selfThis.removeAll(selfThis);
			})

			// 绑定上传事件
			$(document).on('click' , 'a.go_upload' , function(){
				selfThis.uploadFile(selfThis);
			})
		} , 
		// 生成模板
		buildTemplate : function(){
			var html = '<div class="more-upload">' + 
							'<div class="more-upload-top">' + 
								'<input type="file" class="form-control more-upload-button">' + 
							'</div>' + 
							'<div class="more-upload-content">' + 
								'<div class="more-upload-content-box">' + 
									
								'</div>' + 
							'</div>' + 
							'<div class="more-upload-tools">' + 
								'<a href="javascript:void(0);" class="btn btn-success go_upload">' + this.options.uploadText + '</a>' + 
								'<a href="javascript:void(0);" class="btn btn-danger go_clearall">' + this.options.clearText + '</a>' + 
							'</div>' + 
							'<div class="more-upload-tips">' + 
								
							'</div>' + 
							'<input type="hidden" name="' + this.options.new_cover_name + '">' + 
							'<input type="hidden" name="' + this.options.delete_cover_name + '" >' + 
						'</div>';
			this.$element.html(html);
		} , 
		// 选择文件
		useFile : function(e , fileThis , selfThis){
			var index = selfThis.options.allFiles.push(fileThis.files[0]);
			var reader = new FileReader();
			var files = e.target.files;
			reader.readAsDataURL(files[0]);
			var selfThis = this;
			reader.onload = function (e) {
				var data = this.result;
				var html = '<div class="more-upload-content-item more-upload-content-delete-' + index + '">' + 
								'<img src="' + data + '" alt="">' + 
								'<a href="javascript:void(0);" data-index="' + index + '">' + selfThis.options.deleteText + '</a>' + 
							'</div>';
				$(selfThis.options.pictureBox).append(html);
			}
		} , 
		// 移除元素
		removeItem : function(functionThis , selfThis){
			var index = $(functionThis).attr('data-index');
			delete selfThis.options.allFiles[index];
			$('.more-upload-content-delete-' + index).remove();
			selfThis.options.deleteArray.push(selfThis.options.initCovers[index]);
			$('input[name=delete_cover_id]').val(selfThis.options.deleteArray.join(','));
		} , 
		// 移除全部
		removeAll : function(selfThis){
			if(selfThis.options.initCovers){
				$.each(selfThis.options.initCovers , function(index , value){
					selfThis.options.deleteArray.push(value);
				})
			}
			selfThis.options.allFiles = [];
			$(selfThis.options.pictureBox).html('');
			$('input[name=' + selfThis.options.delete_cover_name + ']').val(selfThis.options.deleteArray.join(','));
		} , 
		// 开始上传文件
		uploadFile : function(selfThis){
			var formData = new FormData();
			var filelist = selfThis.getFileList();
			if(filelist.length < 1){
				$(selfThis.options.tipBox).html(selfThis.options.tips.empty_pic);
				return;
			}
			if(!selfThis.options.uploadUrl){
				$(selfThis.options.tipBox).html(selfThis.options.tips.empty_upload_url);
				return;
			}
			for(var i = 0 ; i < filelist.length ; i ++){
				if(filelist[i] != null || filelist[i] != undefined){
					formData.append("file" + i ,filelist[i]);
				}
			}
			$('.more-upload-tools').hide();
			$('.more-upload-content-item a').hide();
			$.ajax({
				type : "post",
				url : selfThis.options.uploadUrl,
				data : formData,
				processData : false,
				contentType : false,
				dataType : 'json' , 
				success:function(data){
					if(!data.errno){
						$(selfThis.options.tipBox).html(selfThis.options.tips.success);
						$('input[name=' + selfThis.options.new_cover_name + ']').val(data.data);
					}else{
						$(selfThis.options.tipBox).html(data.errmsg);
					}
				},
				error:function(e){
					$('.more-upload-tools').show();
				}
			});
		}
	}

	$.fn.moreUpload = function(options) {
		var uploadClass = new upload(this, options);
		return uploadClass.run()
	}
})();