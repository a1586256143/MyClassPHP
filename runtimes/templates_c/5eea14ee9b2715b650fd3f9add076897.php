<?php $this->display("/Users/colin/Public/www/MyClassPHP/views/master/header.html") ?>
<div class="container">
	<div class="panel panel-default">
		<div class="panel-heading">
			Register
		</div>
		<div class="panel-body">
			<?php echo \system\Form::openForm("public/register" , 'post'); ?>
				<div class="form-group">
					<label for="" class="label-control">User</label>
					<?php echo \system\Form::inputText('username' , '' , array('placeholder' => 'Enter Username' , 'class' => 'form-control')); ?>
				</div>
				<div class="form-group">
					<label for="" class="label-control">Pass</label>
					<?php echo \system\Form::inputPass('password' , '' , array('placeholder' => 'Enter Password' , 'class' => 'form-control')); ?>
				</div>
				<div class="form-group">
					<?php echo \system\Form::button('button' , '' , 'Register' , array('class' => 'btn btn-primary')); ?>
					&nbsp;<a href="/public/login">to Login</a>
				</div>
			<?php echo \system\Form::closeForm(); ?>
		</div>
	</div>
</div>
<?php $this->display("/Users/colin/Public/www/MyClassPHP/views/master/bottom.html") ?>