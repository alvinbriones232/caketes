<?php
	echo $this->Html->css('select2');
	echo $this->Html->script('select2');
?>

<div class="well">
	
	<div id="create_message" class="panel panel-primary" id="basic_info">
		  <div class="panel-heading">
				<h3 class="panel-title">Messages</h3>
		  </div>
		  <div class="panel-body">
			  <a href="#" data-toggle="modal" data-target="#composeModal" ><span class="glyphicon glyphicon-envelope"></span>Compose Mesage</a>
		 	  
			  <div id="message_lists" >
				  <div class="panel-body">
				  	<div id="contacts" class="pull-left" >
						<h3>Text From</h3>
						<?php $dummy=''; foreach($text_from as $txt):?>
							<?php if($txt['users']['name'] != $dummy):?>
								<?php if(isset($txt['users']['image'])):?>
									<?php echo $this->Html->image("user-profile-img/".$txt['users']['image'],["width"=>"100px","height"=>"100px"]); ?><br/>
								<?php else:?>
									<?php echo $this->Html->image("user-profile-img/default.png",["width"=>"100px","height"=>"100px"]); ?><br/>
								<?php endif;?>
								<label>Name:</label><a href="#" onclick="conversation('<?php echo $txt['messages']['from_id'];?>','<?php echo $txt['messages']['to_id'];?>')" ><?php echo $txt['users']['name'];?></a> <br/>
								
							<?php endif;?>
							<?php $dummy=$txt['users']['name'];?>
							
						<?php endforeach;?>
					</div>
					<!-- CONVERSATIONS HERE! -->
					<div id="conversations" class="pull-right" >
					  <h3>Conversations</h3>
					  <div class="well well-small">
						  <textarea id="reply" style="width:450px; height:100px; resize:none;"></textarea><br/>
						  <button id="btn-reply" class="btn btn-success" style="display:none;" >reply</button>
						  <div id="converse"></div>
						  <div id="try"></div>
						  <div id="showMore" style="display:none;" > <center> <a href="#">Show More</a> </center> </div>
						  
					 </div>
					</div>
				  </div>
			  </div>
		
		 </div>

	</div>
	
	
	<!-- Modal -->
	<div class="modal fade" id="composeModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	  <div class="modal-dialog">
		<div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
			<h4 class="modal-title" id="myModalLabel">Compose New Message</h4>
		  </div>
		  <div class="modal-body">
			<form id="new_message" >
			 <table width='100%' >
				<tr>
					<td>Recipient</td>
					<td><input type="text" name="recipient" id="recipient" placeholder="Please select a recipient" required /></td>
					
				</tr>
				<tr>
					<td>Message</td>
					<td><textarea name="message" id="message" placeholder="Message" style="width:500px; height:150px; resize:none;" required ></textarea></td>
				</tr>
				
			 </table>
			</form>
		  </div>
		  <div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			<button type="button" class="btn btn-primary" id='send'>Send Message</button>
		  </div>
		</div>
	  </div>
	</div>
		
	

</div>

<script type="text/javascript">
	$('#recipient').select2({
		minimumInputLength:1,
		width: '100%',
		multiple: false,
		ajax:{
			url:'<?php echo Router::url(array('controller' => 'messages','action' => 'recipients'))?>',
			datatype:'json',
			type:'post',
			data: function( term , page ){
				return { q:term };
			},
			results:function( data , page){
				return{ results : data };
			}
		}
		
	});

</script>
<script type="text/javascript">
	$(function(){
		$('#send').click(function(e){
			e.preventDefault();
			var url = "<?php echo Router::url(array('controller' => 'messages','action' => 'send_message'))?>";
			$.post(url,{ "id":$('#recipient').val(),"message":$('#message').val() },function(data){
				if(data==1){
					alert('Message Sent !');
					$('#message').val('');
					window.location.reload();
				}else{
					//var err = $.parseJSON(data);
					
				}
				
			});
			
		});
		
		$('#btn-reply').click(function(e){
			e.preventDefault();
			var url = "<?php echo Router::url(array('controller' => 'messages','action' => 'message_reply'))?>";
			$.post( url, { "reply_to":$('.reply_to').val(), "reply":$('#reply').val() },function(data){
				$('#converse').empty().append(data);
				$('#reply').val('');
			});	
			
		});
		
		$('#showMore').click(function(e){
			e.preventDefault();
			var url = "<?php echo Router::url(array('controller' => 'messages','action' => 'showMore'))?>";
			$.post( url, { "id":$('#identifier').val(),"id2":$('#identifier2').val() },function(data){
				$('#try').empty().append(data);
			});
		});
		
	
		
		
	});
	function conversation(from_id,to_id)
	{
		var url = "<?php echo Router::url(array('controller' => 'messages','action' => 'get_conversation'))?>";
		$.post( url,{ "from_id":from_id, "to_id":to_id },function(data){
				$('#btn-reply').show();
				$('#showMore').show();
				$('#try').empty();
				$('#converse').empty().append(data);
			
		});
	}
	function delete_message(id)
	{
		var url = "<?php echo Router::url(array('controller' => 'messages','action' => 'delete_message'))?>";
		var url2 = "<?php echo Router::url(array('controller' => 'messages','action' => 'new_data'))?>";
		$.post( url, {"id":id},function(data){
				$('#con'+id).remove();
		});
	}

</script>