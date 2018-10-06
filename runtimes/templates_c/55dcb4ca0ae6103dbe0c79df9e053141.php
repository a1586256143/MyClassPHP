<?php $this->display("/Users/colin/Public/www/MyClassPHP/views/master/header.html") ?>
<style>
	input,select{margin-bottom: 20px;outline: none;}
	select{width:100%;}
</style>
<div class="container">
	<div class="panel panel-default">
		<div class="panel-heading">
			Login
		</div>
		<div class="panel-body">
			<?php echo \system\Form::openForm('Index/user' , 'post' , array('name' => 'formnAME' , 'class' => 'form-class')); ?>
			    <?php echo \system\Form::inputText('username' , '' , array('placeholder' => '请输入用户名' , 'class' => 'form-control')); ?>
			    <?php echo \system\Form::inputPass('password' , '' , array('placeholder' => '请输入密码' , 'class' => 'form-control')); ?>
			    <?php echo \system\Form::select(array(0 => '男' , 1 => '女') , 'sex' , 0 , array('class' => 'form-control')); ?>
			    <?php echo \system\Form::submitButton('submit' , '提交' , array('class' => 'btn btn-success')); ?>
			<?php echo \system\Form::closeForm(); ?>
		</div>
	</div>
</div>
<?php $this->display("/Users/colin/Public/www/MyClassPHP/views/master/bottom.html") ?>