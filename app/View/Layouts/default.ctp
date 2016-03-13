<!DOCTYPE html>
<html>
<head>
	<?php echo $this->Html->charset(); ?>
	<title>
		Test Site
	</title>
	<?php
		

		echo $this->Html->meta('icon');
		echo $this->Html->css('bootstrap/bootstrap.css');
		echo $this->Html->css('custom');
        //echo $this->Html->css('cake.generic');
		echo $this->Html->script('jquery1.11');
		echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->fetch('script');
		$current_url = Router::url( $this->here, false );
		$a =explode('/',$current_url);
		$b=end($a);

	?>
</head>
<body>
	<div class="container">
		<div class="header dv_header">
			<!-- header goes here -->
            <div id="navbarCollapse" class="collapse navbar-collapse">
                 <ul class="nav navbar-nav">
                    <li class="active"> <?php echo $this->Html->link('Login', array('action' => 'login','controller'=>'users')); ?> </li>
                    <li><?php echo $this->Html->link('Register', array('action' => 'index','controller'=>'users')); ?></li>
					<?php if($is_loggedIn==true):?>
					 <li><?php echo $this->Html->link('Profile', array('action' => 'profile','controller'=>'users')); ?></li>
					 <li><?php echo $this->Html->link('Message', array('action' => 'index','controller'=>'messages')); ?></li>
					 <?php if($b=='edit'):?>
					 <li> <a href="#" data-toggle="modal" data-target="#myModal" >Change Password</a> </li>
					 <?php endif;?>
					 <li><?php echo $this->Html->link('Logout', array('action' => 'logout','controller'=>'users')); ?></li>
					<?php endif;?>
                </ul>
				
            
            </div>
            
           
            
		</div>
		<div id="content">

			<?php echo $this->Session->flash(); ?>

			<?php echo $this->fetch('content'); ?>
		</div>
		<div id="footer">
			<!-- footer goes here -->
		</div>
	</div>
	<?php //echo $this->element('sql_dump'); ?>
</body>
    <?php echo $this->Html->script('bootstrap.min');  ?>
</html>
