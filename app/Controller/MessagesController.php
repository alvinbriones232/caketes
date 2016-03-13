<?php

	class MessagesController extends AppController
	{
		public $uses = array('User','Message');
		public function index()
		{
			$session = $this->Session->read('User')['info']['User'];
			$this->set('session',$session);
			$text_from  = $this->Message->query("SELECT messages.from_id,messages.to_id,messages.content,users.id,users.name,users.image FROM messages LEFT JOIN users ON messages.from_id=users.id WHERE messages.to_id=".$session['id']."");
			$this->set('text_from',$text_from);
		}
		public function recipients()
		{
			$this->layout = false;
			$this->autoRender = false;
			if( $this->request->is('ajax')){
				$userdata   = $this->Session->read('User')['info']['User'];
				$query    = $this->request->data['q'];
				$searchTerm = array("User.name LIKE" => "%".$query."%","User.id !="=>$userdata['id']);
				$recipient  = $this->User->find('all',array('conditions' => $searchTerm ));
				$json_data  = array();
				foreach($recipient as $index => $value){
					$id = $value['User']['id'];

					$data = array(
							'id'    =>(int) $value['User']['id'],
							'text'  => $value['User']['name']
					); 
					array_push( $json_data , $data );
				}
				echo json_encode($json_data);
			}else{
				throw new NotFoundException("Page cannot be Found", 1);
			}
		}
		public function send_message()
		{
			$this->autoRender = false;
			$user = $this->Session->read('User')['info']['User'];
			unset( $this->User->validate['name'] );
			unset( $this->User->validate['password'] );
			if($this->request->is('ajax'))
			{
				if( $this->User->validates() ){
					$aRdata = array(
						'parent_id'=>$this->request->data['id'].$user['id'],
						'from_id'=>$user['id'],
						'to_id'=>$this->request->data['id'],
						'content'=>$this->request->data['message'],
						'created'=>date('Y-m-d H:i:s')
				
					);
					$this->Message->save($aRdata);
					echo true;
				}else{
					$err = $this->User->validationErrors;
					echo json_encode($err , true);
				}
				
				
				
				
				
			}
			
		}
		public function get_conversation()
		{
			$this->autoRender = false;
			$user = $this->Session->read('User')['info']['User'];
			if($this->request->is('ajax'))
			{
			    $parent_id=$this->request->data('to_id').$this->request->data('from_id');
				$parent_id2 = $this->request->data('from_id').$this->request->data('to_id');
				$reply_to = $this->request->data('from_id');
				$conversation = $this->Message->query("SELECT messages.*,users.id as user_id,users.name FROM messages LEFT JOIN users ON messages.from_id=users.id WHERE messages.parent_id=".$parent_id." OR messages.parent_id=".$parent_id2." ORDER BY messages.created DESC LIMIT 3");
				
				
				$data='';
				
				foreach($conversation as $converse){
					$id = $converse['messages']['id'];
					if($converse['users']['name'] == $user['name']):
						$data 	= "<div class='conversations' id='con$id'> Me:";
					else:
						$data   = "<div class='conversations' id='con$id' >".$converse['users']['name'].":";
					endif;
						$data  .= $converse['messages']['content']." <a href='#' onclick='delete_message($id)'><span class='glyphicon glyphicon-remove pull-right'></span></a> ";
						$data  .= "<input type='hidden' class='reply_to' value='$reply_to'>";
						$data  .= "</div><br/> <input type='hidden' id='identifier' value='$parent_id' > <input type='hidden' id='identifier2' value='$parent_id2' >";
						echo $data;
					
				}
				
				
				
			}
		}
		
		public function message_reply()
		{
			$this->autoRender = false;
			$user = $this->Session->read('User')['info']['User'];
			if($this->request->is('ajax'))
			{
				$aRdata = array(
						'parent_id'=>$this->request->data['reply_to'].$user['id'],
						'from_id'=>$user['id'],
						'to_id'=>$this->request->data['reply_to'],
						'content'=>$this->request->data['reply'],
						'created'=>date('Y-m-d H:i:s')
				
				);
				$this->Message->save($aRdata);
				$parent_id= $this->request->data['reply_to'].$user['id'];
				$parent_id2= $user['id'].$this->request->data['reply_to'];
				$reply_to = $user['id'];
				$conversation = $this->Message->query("SELECT messages.*,users.id as user_id,users.name FROM messages LEFT JOIN users ON messages.from_id=users.id WHERE messages.parent_id=".$parent_id." OR messages.parent_id=".$parent_id2." ORDER BY messages.created DESC LIMIT 3");
				foreach($conversation as $converse){
					$id = $converse['messages']['id'];
					if($converse['users']['name'] == $user['name']):
						$data 	= "<div class='conversations' id='con$id'> Me:";
					else:
						$data   = "<div class='conversations' id='con$id' >".$converse['users']['name'].":";
					endif;
						$data  .= $converse['messages']['content']." <a href='#' onclick='delete_message($id)'><span class='glyphicon glyphicon-remove pull-right'></span></a> ";
						$data  .= "<input type='hidden' class='reply_to' value='$reply_to'>";
						$data  .= "</div><br/> <input type='hidden' id='identifier' value='$parent_id' > <input type='hidden' id='identifier2' value='$parent_id2' >";
						echo $data;
					
				}
				
			}
			
		}
		
		public function showMore()
		{
			$this->autoRender = false;
			if($this->request->is('ajax'))
			{
				$user = $this->Session->read('User')['info']['User'];
				$identifier = $this->request->data['id'];
				$identifier2 = $this->request->data['id2'];
				$msg     = $this->Message->query("SELECT messages.*,users.id as user_id,users.name FROM messages LEFT JOIN users ON messages.from_id=users.id WHERE parent_id = ".$identifier." OR parent_id= ".$identifier2."   ORDER BY created DESC LIMIT 3,10");
				//var_dump($msg);
				$data='';
				
				foreach($msg as $converse){
					$id = $converse['messages']['id'];
					if($converse['users']['name'] == $user['name']):
						$data 	= "<div class='conversations' id='con$id'> Me:";
					else:
						$data   = "<div class='conversations' id='con$id' >".$converse['users']['name'].":";
					endif;
						$data  .= $converse['messages']['content']." <a href='#' onclick='delete_message($id)'><span class='glyphicon glyphicon-remove pull-right'></span></a> ";
						$data  .= "</div><br/> <input type='hidden' id='identifier' value='$identifier' > <input type='hidden' id='identifier2' value='$identifier2' >";
						echo $data;
					
				}
				
			}
		}
		public function delete_message()
		{
			$this->autoRender = false;
			if($this->request->is('ajax'))
			{
				$this->Message->delete($this->request->data('id'));
				echo true;
			}
		}
		
	}



?>