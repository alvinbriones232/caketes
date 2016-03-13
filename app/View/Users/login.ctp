<div id="login_form" class="pull-right" >
	<div id="progbar-div" style="display:none;" class="alert alert-success" ></div>
	<form id="form_login" >
        <table class="table" width="100%" >
            <tbody>
                 <tr>
                    <td>Email</td>
                    <td><input type="email" name="email" required /></td>
                 </tr>
                 <tr>
                    <td>Password</td>
                    <td><input type="password" name="password" required /></td>
                </tr>
                <tr>
                    <td></td>
                    <td> <button id="btn-login" class="btn btn-primary"> Login </button> </td>
                </tr>
            </tbody>
        </table>
    </form>
 
   

</div>
<script type="text/javascript">
	$('#btn-login').click(function(event){
		event.preventDefault();
		var url = "<?php echo Router::url(array('controller' => 'users','action' => 'test_func'))?>";
		var goto_url = "<?php echo Router::url(array('controller' => 'users','action' => 'profile')) ?>";
		$.post( url, $('#form_login').serialize(),function(data){
			if(data){
				$('#progbar-div').show();
				$('#progbar-div').empty().append('Successfully Login');
					setTimeout(function(){
						window.location.href= goto_url;
				}, 1300);
				
			}else{
				$('#progbar-div').show();
				$('#progbar-div').empty().append('Invalid Login').removeClass('alert-success').addClass('alert-danger');
					setTimeout(function(){
						window.location.reload();
				}, 1300);
				
			}
		});
		
	});


</script>