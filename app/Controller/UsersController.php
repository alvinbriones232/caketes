<?php

    class UsersController extends AppController
    {
		public $uses = array('User');
		public function beforeFilter() {
        parent::beforeFilter();
		App::uses('File', 'Utility');
		$this->Auth->allow(array('login','index','test_func','create_user','change_pass')); 
		
		}
		
        public function index()
        {
           //show the index.ctp only othing more
        }
		public function create_user()
		{
			
			$this->autoRender=false;
			if ($this->request->is('ajax')) {
				
                $this->User->create();
                if ($this->User->save($this->request->data)) {
                    echo true;
                }else{
					$err = $this->User->validationErrors;
					echo json_encode($err , true);
				} 
            }
		}
		public function login()
        {
            //use to show the view file only nothing more
        }
		public function test_func()
		{
			$this->autoRender=false;
			if($this->request->is('ajax')){
				$email = $this->request->data['email'];
				$password = $this->request->data['password'];
				$userdata = $this->User->findByEmailAndPassword( $email , AuthComponent::password( $password ) );
				
				if($userdata){
					$this->Session->write('User.info', $userdata);
					$this->Auth->login( $userdata);
					$data = ['last_login_time' => date('Y-m-d H:i:s'), 'id' => $userdata['User']['id'] ] ;
					unset( $this->User->validate['name'] );
					$this->User->set( $data );
					$this->User->save( $data );
					$ardata = array(
						'email'=>$userdata['User']['email']
						
					);
					$this->set('data',$ardata);
					echo true;
				}else{
					echo false;
				}
			
			}
			
		}
		
		public function profile()
		{
			$userId = $this->Session->read('User')['info']['User'];
			$userdata = $this->User->findById( $userId['id'] );
			$this->set('userdata' ,$userdata['User']);
		}
		
		public function edit()
		{
			$userId = $this->Session->read('User')['info']['User'];
			$userdata = $this->User->findById( $userId['id'] );
			$this->set('userdata' ,$userdata['User']);
		}
		
		public function save_edit_basic()
		{
			$this->autoRender=false;
			$userId = $this->Session->read('User')['info']['User'];
			$userdata = $this->User->findById( $userId['id'] );
			
			if($this->request->is('ajax'))
			{
				
				unset( $this->User->validate['password'] );
				$this->request->data['id'] = $userdata['User']['id'];
				$this->User->set( $this->request->data );

				if($this->User->validates()){
					echo true;
					$this->request->data['modified'] = date('Y-m-d H:i:s');
				}else{
					$err = $this->User->validationErrors;
					echo json_encode($err , true);
				}

				$this->User->save($this->request->data);
			}
		}
		
		public function upload()
		{
			$this->autoRender=false;
			$userId = $this->Session->read('User')['info']['User'];
			$userdata = $this->User->findById( $userId['id'] );

			$MAX_FILES_SIZE 	= null;
			$ALLOW_EXTENSIONS 	= null;
			$UPLOAD_PATH 		= null;
			$OVERRIDE 			= false;
			$ALLOW_DELETE		= true;

			#Set email notification, to send an email on upload finish
			$EMAIL_TO 	= null;
			$EMAIL_FROM = null;
			#deny extension by default for security reason
			$DENY_EXT = array('php','php3', 'php4', 'php5', 'phtml', 'exe', 'pl', 'cgi', 'html', 'htm', 'js', 'asp', 'aspx', 'bat', 'sh', 'cmd');
			/*
			 * function that runs on the end, customize here insert to db, or other action todo on the end of upload
			 * name can be customized
			 */
			$FINISH_FUNCTION = 'success';
			App::import('Vendor', 'upload');

			$uploader = new RealAjaxUploader($DENY_EXT);

			if(isset($MAX_FILES_SIZE) && $MAX_FILES_SIZE) 		$uploader->setMaxFileSize($MAX_FILES_SIZE);
			if(isset($ALLOW_EXTENSIONS) && $ALLOW_EXTENSIONS) 	$uploader->setAllowExt($ALLOW_EXTENSIONS);
			if(isset($UPLOAD_PATH) && $UPLOAD_PATH) 			$uploader->setUploadPath($UPLOAD_PATH);

			//register email send on file complete
			if(isset($EMAIL_TO) && $EMAIL_TO) 					$uploader->setEmail($EMAIL_TO, $EMAIL_FROM);//the email will be send after file upload

			//register a callback function on file complete
			if(isset($FINISH_FUNCTION) && $FINISH_FUNCTION)
			{
				$uploader->onFinish($FINISH_FUNCTION);//set name of external function to be called on finish upload

				$filename__ = $uploader->getFile_name();
				$data = ['image' => $filename__ , 'id' => $userdata['User']['id'] ] ;
				unset( $this->User->validate['name'] );
				unset( $this->User->validate['password'] );
				unset( $this->User->validate['password_current'] );

				$this->User->set( $data );

				if( $this->User->validates() ){
					echo true;
					$data['modified'] = date('Y-m-d H:i:s');
					
				}else{
					$err = $this->User->validationErrors;
					echo json_encode($err , true);
				}

				if($userdata['User']['image'] ){

					unlink( "./img/user-profile-img/".$userdata['User']['image'] );
				}

				$this->User->save( $data ) ;
				
			} 	

			//check request, this check if file already exits only, depends from javascript part requests
			if(isset($_REQUEST['ax-check-file']))
			{
				$uploader->header();
				echo $uploader->_checkFileExists() ? 'yes': 'no';
			}
			elseif( isset($_REQUEST['ax-delete-file']) && $ALLOW_DELETE)
			{
				$uploader->header();
				echo $uploader->deleteFile();
			}
			else
			{
				$uploader->uploadFile();
				
			}
			
			if($this->User->validates()==true)
			{
				$this->redirect(array('controller'=>'users','action'=>'edit'));
			}
		}
		
		public function change_password()
		{
			$this->autoRender=false;
			$userId = $this->Session->read('User')['info']['User'];
			$userdata = $this->User->findById( $userId['id'] );
			if($this->request->is('ajax'))
			{
				unset( $this->User->validate['name'] );
				$this->request->data['id'] = $userdata['User']['id'];
					
				$this->User->set( $this->request->data );
				if( $this->User->validates() ){
					echo true;
					$this->request->data['modified'] = date('Y-m-d H:i:s');
				}else{
					$err = $this->User->validationErrors;
					echo json_encode($err , true);
				}

				$this->User->save($this->request->data);
				
			}
		}
		
		public function deletePhoto()
		{
			$this->autoRender=false;
			if($this->request->is('ajax'))
			{
				$this->User->id= $this->request->data['id'];
				$image = $this->request->data['image'];
				$this->User->saveField('image',NULL);
				
				$file = new File(WWW_ROOT . 'img/user-profile-img/'.$image, false, 0777);
				if($file->delete()) {
				  echo true;
				}
				
			}
		}
		
		public function logout(){
			$this->layout =false;
			$this->autoRender = false;

			$this->Auth->logout();
			$this->Session->delete('User');
			$this->Session->destroy();
			$this->redirect('/');
			
		}
    }



?>