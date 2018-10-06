/**
 * 回车事件
 * @param  {[type]} enterCallback [description]
 * @return {[type]}               [description]
 */
function enter(enterCallback){
	document.onkeydown = function(e){
		if(!e) e = window.event;//火狐中是 window.event
		if((e.keyCode || e.which) == 13){
			enterCallback();
		}
	}
}

/**
 * 显示微信图片
 * @return {[type]} [description]
 */
function showWechatImg(url){
	// return 'http://read.html5.qq.com/image?src=forum&q=5&r=0&imgflag=7&imageUrl=' + url;
	return url;
}

/**
 * 合并地址
 * @param  {[type]} url [description]
 * @return {[type]}     [description]
 */
function mergeUrl(url , module){
	var host = window.location.host;
	if(!module){
		module = 'public_admin';
	}
	return window.location.protocol + '//' + host + '/' + module + '/' + url;
}

/**
 * 跳转方法
 * @return {[type]} [description]
 */
function locationUrl(url , sleep){
	if(!sleep){
		sleep = 1;
	}
	sleep = sleep * 1000;
	setTimeout(function(){
		window.location.href = mergeUrl(url);
	} , sleep);
}

/**
 * 加载框
 * @return {[type]} [description]
 */
function loading(){
	var index = layer.load(1, {
		shade: [0.1,'#fff'] //0.1透明度的白色背景
	});
	return index;
}

/**
 * 显示弹窗
 * @return {[type]} [description]
 */
function showAlert(msg , icon){
	var index = layer.alert(msg , {icon: icon});
	setTimeout(function(){
		layer.close(index);
	} , 2000);
}

/**
 * 成功弹窗
 * @param  {[type]} msg [description]
 * @return {[type]}     [description]
 */
function yesAlert(msg){
	showAlert(msg , 1);
}

/**
 * 失败弹窗
 * @param  {[type]} msg [description]
 * @return {[type]}     [description]
 */
function noAlert(msg){
	showAlert(msg , 2);
}

/**
 * 显示确认框
 * @param  {[type]} msg [description]
 * @return {[type]}     [description]
 */
function showConfirm(msg , successCallback , errorCallback){
	if(!msg){
		msg = '真的要删除这条数据吗？';
	}
	layer.confirm(msg , {title : '提示' , icon : 3} , function(index){
		successCallback(index);
		closeLoading(index);
	} , function(){
		errorCallback();
		closeLoading();
	});
}

/**
 * 关闭加载框
 * @param  {[type]} index [description]
 * @return {[type]}       [description]
 */
function closeLoading(index){
	if(!index){
		layer.closeAll('loading');
	}else{
		layer.close(index);
	}
}

/**
 * post请求方法
 * @param  {[type]} url             [description]
 * @param  {[type]} data            [description]
 * @param  {[type]} successCallback [description]
 * @param  {[type]} errorCallback   [description]
 * @return {[type]}                 [description]
 */
function ajax(url , method , data , successCallback , errorCallback){
	var index = loading();
	$.ajax({
		url : url , 
		type : method, 
		data : data , 
		dataType : 'json' , 
		success : function(data){
			closeLoading(index);
			if(!data.status){
				noAlert(data.msg);
				return;
			}
			successCallback(data);
		} , 
		error : function(data){
			closeLoading(index);
			errorCallback(data);
		}
	})
}

/**
 * post请求方法
 * @param  {[type]} url             [description]
 * @param  {[type]} successCallback [description]
 * @param  {[type]} errorCallback   [description]
 * @return {[type]}                 [description]
 */
function post(url , data , successCallback , errorCallback){
	ajax(url , 'post' , data , successCallback , errorCallback);
}

/**
 * get请求方法
 * @param  {[type]} url             [description]
 * @param  {[type]} successCallback [description]
 * @param  {[type]} errorCallback   [description]
 * @return {[type]}                 [description]
 */
function get(url , successCallback , errorCallback){
	ajax(url , 'get' , '' , successCallback , errorCallback);
}

/**
 * 获取属性data
 * @return {[type]} [description]
 */
function getdata(attr , name){
	return attr.attr('data-' + name);
}

/**
 * 获取value
 * @return {[type]} [description]
 */
function getValue(attr){
	return $(attr).val();
}

/**
 * 是否为空
 * @return {Boolean} [description]
 */
function isNull(item , tip){
	if(item == ''){
		noAlert(tip);
	}
	return item;
}

/**
 * 开启弹窗
 * @return {[type]} [description]
 */
function openModal(item){
	item = '#' + item;
	$(item).modal('show');
}

/**
 * 关闭弹窗
 * @return {[type]} [description]
 */
function closeModal(item){
	item = '#' + item;
	$(item).modal('hide');
}

/**
 * 缩略图
 * @param  {[type]}   src      [description]
 * @param  {Function} callback [description]
 * @param  {[type]}   w        [description]
 * @param  {[type]}   h        [description]
 * @return {[type]}            [description]
 */
function resizeImage(src,callback,w,h){
    var canvas = document.createElement("canvas"),
        ctx = canvas.getContext("2d"),
        im = new Image();
        w = w || 0,
        h = h || 0;
    im.onload = function(){
        //为传入缩放尺寸用原尺寸
        !w && (w = this.width);
        !h && (h = this.height);
        //以长宽最大值作为最终生成图片的依据
        if(w !== this.width || h !== this.height){
            var ratio;
            if(w>h){
                ratio = this.width / w;
                h = this.height / ratio;
            }else if(w===h){
                if(this.width>this.height){
                    ratio = this.width / w;
                    h = this.height / ratio;
                }else{
                    ratio = this.height / h;
                    w = this.width / ratio;
                }
            }else{
                ratio = this.height / h;
                w = this.width / ratio;
            }
        }
        //以传入的长宽作为最终生成图片的尺寸
        // if(w>h){
        //     var offset = (w - h) / 2;
        //     canvas.width = canvas.height = w;
        //     ctx.drawImage(im,0,offset,w,h);
        // }else if(w<h){
        //     var offset = (h - w) / 2;
        //     canvas.width = canvas.height = h;
        //     ctx.drawImage(im,offset,0,w,h);
        // }else{
        //     canvas.width = canvas.height = h;
        //     ctx.drawImage(im,0,0,w,h);
        // }
        canvas.width = w;
        canvas.height = h;
        ctx.drawImage(im,0,0,w,h);
        callback(canvas.toDataURL("image/png"));
    }
    im.src = src;
}