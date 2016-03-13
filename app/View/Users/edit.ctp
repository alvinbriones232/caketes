<?php  
	
	echo $this->Html->css('upload/style.css');
	echo $this->Html->script('uploader/ajaxupload-min.js');
	/*echo $this->Html->script('datepicker/zebra_datepicker.js');
	echo $this->Html->script('datepicker/zebra_datepicker.src.js');
	echo $this->Html->css('datepicker/default.css');*/
	echo $this->Html->css('datepicker2/datepicker.css');
	echo $this->Html->script('datepicker2/bootstrap-datepicker.js');
	
?>

	<style>
		.error_msg{
			color:red;
			font-size:12px;
		}
		
	</style>
	
	<div id="sub-container">
		<div class="panel panel-primary" id="basic_info">
			  <div class="panel-heading">
					<h3 class="panel-title">Basic Information</h3>
			  </div>
			  <form id="form_update_basic">
				  <div class="panel-body">
					<div class="input-group" >
						<div id="name" class="error_msg" ></div>
						<span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
						<input type="text" id="name" name="name" class="form-control" value="<?php echo $userdata['name']?>" required>
					</div>
					<div class="input-group" >
						<div id="birthdate" class="error_msg" ></div>
						<span class="input-group-addon"><span class="glyphicon glyphicon-gift"></span></span>
						<input type="text" readonly id="birthdate-dp" name="birthdate" class="form-control" value="<?php echo $userdata['birthdate']?>" required>
					</div>
					<div class="input-group" >
						<div id="hobby" class="error_msg" ></div>
						<textarea name="hobby" id="hobby-textarea" value="<?php echo $userdata['hobby']?>" style="resize:none;" placeholder="Enter your hobbies here" required ><?php echo $userdata['hobby']?></textarea>
					</div>
					<div class="input-group" >
						<div id="gender" class="error_msg" ></div>
						<label style="margin-right:100px;">Gender</label> 	
						<label>Male</label>
						<input  type="radio" name="gender" value="1" <?php echo ($userdata['gender']=='1') ? 'checked':null ?>/>
						<label>Female</label>
						<input  type="radio" name="gender" value="2" <?php echo ($userdata['gender']=='2') ? 'checked':null ?> />
					</div><br/>
					<div class="input-group">
					 <button class="btn btn-primary" id="btn-edit-basic-info">Save</button>
					</div>
				  </div>

			  </form>
		</div>

		<div class="panel panel-primary pull-right" id="change_profile">
			<div class="panel-heading">
					<h3 class="panel-title">Change Profile Picture</h3>
			</div>
			<div class="panel-body">
				<div id="uploader_div"></div>
				
					
				<?php if( !isset($userdata['image']) ):?>
					<center>
						<?php echo $this->Html->image("user-profile-img/default.png",["class"=>"image-profile clearfix","width"=>"150px","height"=>"150px"]); ?>
					</center>
				<?php else:?>
					<center>
						<div style="width:200px; height:200px;" >
							<a class="boxclose" id="boxclose" onclick="deletePhoto('<?php echo $userdata['id']?>','<?php echo $userdata['image']?>')" ></a>
							<?php echo $this->Html->image("user-profile-img/".$userdata['image'],["class"=>"image-profile clearfix","width"=>"150px","height"=>"150px"]); ?>
						</div>
						
					</center>
					
			 <?php endif;?>
			</div>
		</div>
		
	</div>


	<!-- Modal -->
	<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	  <div class="modal-dialog">
		<div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
			<h4 class="modal-title" id="myModalLabel">Change Password</h4>
		  </div>
		  <div class="modal-body">
			<form id="change_password" >
			 <table width='100%' >
				<tr>
					<td>Current Password</td>
					<td id="current_password" class='error_msg'></td>
					<td><input type="password" name="current_password" required /></td>
				</tr>
				<tr>
					<td>New Password</td>
					<td id="password" class='error_msg'></td>
					<td><input type="password" name="password" required /></td>
				</tr>
				<tr>
					<td>Confirm Password</td>
					<td id="password_confirm" class='error_msg' ></td>
					<td><input type="password" name="password_confirm" required /></td>
				</tr>
			 </table>
			</form>
		  </div>
		  <div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			<button type="button" class="btn btn-primary" id='change_pass'>Save changes</button>
		  </div>
		</div>
	  </div>
	</div>





<script type="text/javascript">
		$('#uploader_div').ajaxupload({
			url:'<?php echo $this->Html->url('/', true)?>users/upload',
			remotePath:'img/user-profile-img/',
			autoStart:true,
			maxFiles:1,
			allowExt:['jpg','png','gif','jpeg'],
			success: function(response){
					window.location.reload();
			},
		});
</script>
	
	


<script type="text/javascript">
	/*$('#birthdate-dp').Zebra_DatePicker({
	    format: 'Y-m-d',
	    view: 'years'
	});*/
	 $( "#birthdate-dp" ).datepicker({
		viewMode: 'years',
		format: 'yyyy-mm-dd'
		
		});
	$(function(){
		$('#btn-edit-basic-info').click(function(e){
			e.preventDefault();
			var url = "<?php echo Router::url(array('controller' => 'users','action' => 'save_edit_basic'))?>";
			$.post( url,
				  $('#form_update_basic').serialize(),
				  function(data){
						if(data==1){
							alert('INFORMATION HAS BEEN MODIFIED');
							window.location.reload();
						}else{
							var err = $.parseJSON( data );
							$.each(err,function( index , value ){
								$('#'+index).text(value).show();
							});
						}
					
			});
		});
		
		$('#change_pass').click(function(e){
			e.preventDefault();
			var url = "<?php echo Router::url(array('controller' => 'users','action' => 'change_password'))?>";
			$.post(url, $('#change_password').serialize(),
				  function(data){
					if(data==1)
					{
						alert('Succesfully change password!');
						window.location.reload();
					}else{
						var err = $.parseJSON( data );
						$.each(err,function( index , value ){
							$('#'+index).text(value).show();
						});
					}
			});
		});
	});
	function deletePhoto(id,image)
	{
		var url = "<?php echo Router::url(array('controller' => 'users','action' => 'deletePhoto'))?>";
		$.post( url, { "id":id,"image":image },function(data){
			if(data==1)
			{
				window.location.reload();
			}
		});
	}
	
</script>

