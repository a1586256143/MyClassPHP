<?php $this->display("/Users/colin/Public/www/MyClassPHP/views/master/header.html") ?>
<div class="container">
	<div class="jumbotron">
		<h1>MyClassPHP 3.0</h1>
		<p>
			Although difference between 2.4 and 3.0 version is bigger, I think can completely, the latest MyClassPHP in 2.4 a lot of changes have been made on the basis of, including structure, model, controller, routing, middleware, CSRF, etc., I believe that 3.0 will bring you different feeling
		</p>
		<p>
			2.4和3.0之间虽然相差版本较大，我认为完全可以，最新的MyClassPHP在2.4的基础上做了很大的改动，包含 结构、模型、控制器、路由、中间件、CSRF等，我相信3.0会给你带来不一样的感觉
		</p>
		<p>
			<a class="btn btn-lg btn-primary" target="_blank" href="http://lebook.me/book/142769" role="button">View docs &raquo;</a>
		</p>
	</div>
	<?php $name = 'member'; ?>
	<!-- <?php $_list_ = M($name)->field('*')->limit(1,2)->select();?><?php foreach ($_list_ as $name => $list): ?>
		<?php echo $name; ?>-<?php echo dump($list) ?> <br>
	<?php endforeach; ?> -->
</div>
<?php $this->display("/Users/colin/Public/www/MyClassPHP/views/master/bottom.html") ?>