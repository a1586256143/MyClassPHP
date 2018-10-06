$(function(){

	function enterCallback(){
		$('.button-login').click();
	}

	enter(enterCallback);

	/**
	 * 后台登录js
	 * @author Colin [amcolin@126.com]
	 */
	$('.button-login').click(function(){
		var user = $('input[name=name]').val();
		var pass = $('input[name=password]').val();
		var token = $('input[name=_token]').val();
		var module = $(this).attr('data-module');
		module = module ? module : 'public_admin';
		if(!user){
			layer.msg('用户名不能为空');
			return;
		}
		if(!pass){
			layer.msg('密码不能为空');
			return;
		}
		function success(data){
			window.location.href = '/index.php/' + data.msg;
		}

		function error(){}

		var param = {user : user , pass : pass , _token : token};
		post(mergeUrl('loginpost' , module) , param , success , error);
	})
})