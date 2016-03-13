<div class="well" >
	<button class="btn btn-primary" id="btn-edit"> <span class="glyphicon glyphicon-edit"></span>Edit</button>
	<div id="profile-pic" >
		<center>
		<?php if ( empty($userdata['image']) ): ?>
    		<?php echo $this->Html->image("user-profile-img/default.png",["class"=>"img-circle img-responsive clearfix","width"=>"200px","height"=>"200px"]); ?>
		<?php else:?>
			<?php echo $this->Html->image("user-profile-img/".$userdata['image'],["class"=>"img-circle img-responsive clearfix","width"=>"200px","height"=>"200px"]); ?>
		<?php endif;?>
		<h2> <?php echo $userdata['name'];?> </h2>
		
		<div class="well well-sm">
			<h2>HOBBIES</h2>
			<?php echo $userdata['hobby'];?>
		</div>
	
		</center>
		
	</div>
	<div id="profile-data" >
		<div class="alert-info" style="position:center;" >  <h3><center>Basic Information</center></h3></div>
		<table id="information" width="100%">
			<tr>
				<td>Email</td>
				<td> <?php echo $userdata['email'];?> </td>
			</tr>
			<tr>
				<td>Birthdate</td>
				<td> <?php echo date('M d,Y',strtotime($userdata['birthdate']));?> </td>
			</tr>
			<tr>
				<td>Joined On</td>
				<td> <?php echo date('M d,Y' ,strtotime($userdata['created']));?> </td>
			</tr>
			<tr>
				<td>Gender</td>
				<td> <?php echo ($userdata['gender']==1)?'Male' : 'Female' ;?> </td>
			</tr>
			
		
		</table>
		
	</div>
</div>
<script type="text/javascript">
	
	$('#btn-edit').click(function(e){
		e.preventDefault();
		var url = "<?php echo Router::url(array('controller' => 'users','action' => 'edit'))?>";
		window.location.href=url;
	});

</script>