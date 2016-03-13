<div id="dv_login" >
    <form id="form_register">
			<div id="error" style="display:none;color:red;"></div>
			<table width="100%">
				<tr>
					<td>Name</td>
					<td><input type="text" name="name" required></td>
					<td id="name" class="error" ></td>
				</tr>
				<tr>
					<td>Email</td>
					<td><input type="email" name="email" required></td>
					<td id="email" class="error" ></td>
				</tr>
				<tr>
					<td>Password</td>
					<td><input type="password" name="password" required></td>
					<td id="password" class="error" ></td>
				</tr>
				<tr>
					<td>Confirm Password</td>
					<td><input type="password" name="password_confirm" required></td>
					<td id="password_confirm" class="error" ></td>
				</tr>
				<tr>
					<td></td>
					<td><input type="hidden" value="<?php echo $this->request->clientIp();?>" name="created_ip" /></td>
				</tr>
				<tr>

					<td></td>
					
					<td> <button id="register" class="btn btn-primary">Register</button> </td>
				</tr>

			</table>
			
		
    </form>


</div>

<script type="text/javascript">
	$('#register').click(function(event){
		event.preventDefault();
		var url = "<?php echo Router::url(array('controller' => 'users','action' => 'create_user'))?>";
		$.post( url, $('#form_register').serialize(),
			 function(data){
				if(data==1){
					$('#error').show();
					$('#error').empty().append('Successfully Saved');
					
				}else{
					var err = $.parseJSON(data);
					$.each(err,function( index , value ){
						$('#'+index).text(value).show();
					});
				}
		});
	});
	
</script>

