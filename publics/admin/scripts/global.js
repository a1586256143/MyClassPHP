var host = window.location.host;
var golbalUrl = window.location.protocol + '//' + host + '/public_admin/';
// var golbalUrl = 'http://apitest.jikeep.cn/index.php/Admin/'
var golbalIndex = 0;
/**
 * 全局js文件
 */
$(function() {
	//处理data-value属性
	$("select[data-value]").each(function() {
		$(this).val($(this).attr("data-value"));
	});
	//全局data-title属性
	$('[data-tips]').hover(function() {
		var content = $(this).attr('data-tips');
		if (!content) {
			return;
		}
		layer.tips(content, $(this), {
			tips: [2, '#3595CC'],
		});
	}, function() {
		layer.closeAll('tips');
	})
	//全局时间点击控件
	$('[data-export-show]').each(function() {
		var data = $(this).attr('data-export-show');
		if (data == 1) {
			$('button[name=button1]').show();
		} else {
			$('button[name=button1]').hide();
		}
	});
	var starttime = $('input[name=starttime]');
	var endtime = $('input[name=endtime]');
	$('.timeselect').click(function() {
		$('.timeselect').removeClass('active');
		$(this).addClass('active');
		var day1 = new Date();
		var type = parseInt($(this).attr('data-type'));
		switch (type) {
			case 1:
				//今天
				var resultDate = calcDate(day1);
				break;
			case 2:
				//昨天
				day1.setTime(day1.getTime() - (24 * 60 * 60 * 1000));
				break;
			case 3:
				//近2天
				day1.setTime(day1.getTime() - (24 * 60 * 60 * 2000));
				break;
			case 4:
				//近3天
				day1.setTime(day1.getTime() - (24 * 60 * 60 * 3000));
				break;
			case 5:
				//近7天
				day1.setTime(day1.getTime() - (24 * 60 * 60 * 7000));
				break;
			case 6:
				//近30天
				day1.setTime(day1.getTime() - (24 * 60 * 60 * 30000));
				break;
		}
		var resultDate = calcDate(day1);
		if (type == 3 || type == 4 || type == 5 || type == 6) {
			resultDate.endtime = mergeDate(new Date()) + '|23:59:59';
		}
		if (type == 7) {
			starttime.val('all');
			endtime.val('all');
			return;
		}
		starttime.val(resultDate.starttime);
		endtime.val(resultDate.endtime);
	})
	/**
	 * 合并时间
	 * @return {[type]} [description]
	 */
	function calcDate(time) {
		var dates = {
			starttime: '',
			endtime: ''
		};
		dates.starttime = mergeDate(time) + '|00:00:00';
		dates.endtime = mergeDate(time) + '|23:59:59';
		return dates;
	}
	/**
	 * 合并日期
	 * @param  {[type]} time [description]
	 * @return {[type]}      [description]
	 */
	function mergeDate(time) {
		var month = time.getMonth() + 1,
			day = time.getDate();
		if (month < 10) {
			month = "0" + month;
		}
		if (day < 10) {
			day = "0" + day;
		}
		return time.getFullYear() + '-' + month + '-' + day
	}
	//全局时间点击
	$('.layer-date').click(function() {
		var format = getdata($(this), 'format');
		if (!format) {
			format = 'YYYY-MM-DD|hh:mm:ss';
		}
		laydate({
			format: format,
			istime: true
		})
	})
	//全局时间点击时间结束
	/**
	 * 错误处理
	 * @return {[type]} [description]
	 */
	function error() {}
	/**
	 * 公共删除成功回调方法
	 * @param  {[type]} url [description]
	 * @return {[type]}     [description]
	 */
	function publicDeleteSuccess(url) {
		get(url, function(data) {
			yesAlert('删除成功');
			$('.count').html(parseInt($('.count').text()) - 1);
			$('.tr_' + data.msg).remove();
		}, error);
	}

	function updateSucccess(url) {
		get(url, function(data) {
			yesAlert('更新成功');
			setTimeout(function() {
				layer.closeAll();
			}, 1500);
		}, error);
	}
	// 快速定位
	$('.kybtn').click(function() {
		var content = $('.keyword').val();
		var select = getdata($(this), 'select');
		if (!content) {
			return;
		}
		$('select[name=' + select + '] option').each(function(index, value) {
			var text = $(value).text();
			if (text == content) {
				$('select[name=' + select + ']').val($(value).val());
			}
		});
	})
	// 公共点击保存
	$('.baseSave .save').click(function() {
		var formData = $('.baseSave').serialize();
		var url = getdata($(this), 'url');
		var location = getdata($(this), 'location');
		post(url, formData, function(data) {
			yesAlert(data.msg);
			setTimeout(function() {
				window.location.href = location;
			}, 2000)
		}, error);
	})
	// 公共表单验证
	$('.baseValidate .save').click(function() {
		var form = $('.baseValidate .required'),
			thisUrl = getdata($(this), 'url'),
			location = getdata($(this), 'location'),
			thisValidate = false;
		form.each(function(index, value) {
			if (thisValidate === false) {
				var thisValue = $(value).val();
				var thisTip = getdata($(value), 'tip');
				if(!thisTip){
					thisTip = '此项数据为必填';
				}
				if (thisValue === null || thisValue === '' || thisValue.trim() === '') {
					layer.tips(thisTip, $(this), {
						tips: [3, '#F9354C'],
					});
					$(value).focus();
					thisValidate = true;
					return;
				}
			}
		});
		// 验证开始
		if (thisValidate === false) {
			var formData = $('.baseValidate').serialize();
			post(thisUrl, formData, function(data) {
				yesAlert(data.msg);
				setTimeout(function() {
					window.location.href = location;
				}, 2000)
			}, error);
		}
	})
	//所有ajax-get方法
	$('.ajax-get').click(function() {
		var msg = getdata($(this), 'msg');
		var url = getdata($(this), 'url');
		var id = getdata($(this), 'id');
		var delItem = getdata($(this), 'delItem');
		var callback = getdata($(this), 'callback');
		var operMsg = getdata($(this), 'operMsg');
		//临时函数，处理全局函数
		function tmpFunction(data) {
			//data可能为object类型 或者 int类型
			var tmpData;
			if (typeof data != 'object') {
				//不是object类型自动转换string类型
				tmpData = '"' + url + '"';
			} else {
				//object类型自动转换json字符串
				tmpData = JSON.stringify(data);;
			}
			//调用回调函数
			eval(callback + '(' + tmpData + ')');
		}
		/**
		 * 成功处理
		 * @return {[type]} [description]
		 */
		function success() {
			yesAlert(operMsg);
			if (delItem) {
				$(delItem).remove();
			}
		}
		if (msg) {
			//开启弹窗时代
			if (callback) {
				//showConfirm里面没有get请求，所以需要写上get请求
				showConfirm(msg, tmpFunction, error);
			} else {
				showConfirm(msg, success, error);
			}
		} else {
			if (callback) {
				get(url, tmpFunction, error);
			} else {
				get(url, success, error);
			}
		}
	})
	//所有载入窗口方法
	$('.openWindow').click(function() {
		var title = getdata($(this), 'title');
		var width = getdata($(this), 'width');
		var height = getdata($(this), 'height');
		var url = getdata($(this), 'url');
		golbalIndex = layer.open({
			type: 2,
			content: url,
			area: [width + 'px', height + 'px'],
			title: title,
		})
	})
	//权限组start
	$('.saveGroup button').click(function() {
		if ($('input[name=title]').val() == '') {
			noAlert('请输入组名称');
			return;
		}
		var formData = $('.saveGroup').serialize();
		var url = golbalUrl + '/AdminGroup/saveGroup';
		post(url, formData, function(data) {
			yesAlert(data.msg);
			setTimeout(function() {
				window.location.href = golbalUrl + '/AdminGroup/index';
			}, 2000)
		}, error);
	})
	//权限组end
	//权限规则start
	$('.saveRule button').click(function() {
		if ($('input[name=title]').val() == '') {
			noAlert('请输入名称');
			return;
		}
		var formData = $('.saveRule').serialize();
		var url = golbalUrl + '/AdminRule/saveRule';
		post(url, formData, function(data) {
			yesAlert(data.msg);
			// setTimeout(function(){
			// 	window.location.href = golbalUrl + '/AdminRule/index';
			// } , 2000)
		}, error);
	})
	//权限规则end
	//管理员管理start
	$('.saveAdminUser button').click(function() {
		if ($('input[name=user]').val() == '') {
			noAlert('请输入用户名');
			return;
		}
		if ($('select[name=gid]').val() == null) {
			noAlert('请选择一个权限组');
			return;
		}
		var formData = $('.saveAdminUser').serialize();
		var url = golbalUrl + '/AdminUser/saveUser';
		post(url, formData, function(data) {
			yesAlert(data.msg);
			setTimeout(function() {
				window.location.href = golbalUrl + '/AdminUser/index';
			}, 2000)
		}, error);
	})
	//管理员管理end
})