<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>跳转提示</title>
	<style>
		div.box{width:35%;height:30%;margin:0 auto;font-size:25px;color:#000;font-weight:bold;}
		dl{padding:0px;margin:0px;width:100%;height:100%;border:1px solid #ccc;}
		dl dt{padding:0px;margin:0px;border-bottom:1px solid #ccc;line-height:50px;font-size:20px;text-align:center;background:#efefef;}
		dl dd{padding:0px;margin:0px;}
		dl dd.content{padding:0px;width:100%;line-height:25px;font-size:17px;text-align:center;text-indent:0px;margin:0px;padding:30px 0}
		dl dd p{padding:0px;margin:0px;font-size:14px;margin-bottom: 20px;text-align: center;font-weight: normal;}
		dl dd span{font-size:25px;}
		dl dd span#time{color:red;font-weight: normal;font-size:20px;display: inline-block;margin:0 5px;}
		
	</style>
</head>
<body>
	<div class="box">
		<dl style="">
			<dt>
				MyClass提示信息
			</dt>
			<dd class="content">
				<?php if ($param['status']): ?>
					<span>└(^o^)┘</span><?php echo $message; ?>
				<?php else: ?>
					<span>（⊙o⊙）</span><?php echo $message; ?>
				<?php endif; ?>
			</dd>
			<dd>
				<p>将在<span id="time"><?php echo $param['time']; ?></span>后跳转</p>
			</dd>
			<dd>
				<p><a href="<?php echo $param['url'] ? $param['url'] : 'javascript:history.back(-1);'; ?>" id="location">立即跳转</a>　<a href="javascript:void(0);" id="close">放弃跳转</a></p>
			</dd>
		</dl>
		<script>
			//找到时间DOM
			var obj = document.getElementById('time');
			//找到立即跳转DOM
			var hrefObj = document.getElementById('location');
			var interval = setInterval(function(){
				var content = -- obj.innerHTML;
				obj.innerHTML = content;
				if(content <= 0){
					clearInterval(interval);
					var href = '<?php echo $param['url']; ?>';
					//跳转
					if(!href){
						window.history.back(-1);
					}else{
						window.location.href = hrefObj.href;
					}
					
				}
			} , 1000)
			//放弃跳转
			var close = document.getElementById('close');
			close.onclick = function(){
				clearInterval(interval);
			}
		</script>
	</div>
</body>
</html>