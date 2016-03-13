<?php

class User extends AppModel {
	
	
    
	public $validate = array(
        'name' => array(
            'nonEmpty' => array(
                'rule' => array('notEmpty'),
                'message' => 'A name is required',
				'allowEmpty' => false
            ),
			'between' => array( 
				'rule' => array('between', 5, 20), 
				'required' => true, 
				'message' => 'Name must be between 5 to 20 characters'
			),
			'alphaNumericDashUnderscore' => array(
				'rule'    => array('alphaNumericDashUnderscore'),
				'message' => 'Name can only be letters, numbers and underscores'
			),
        ),
        'password' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'A password is required'
            ),
			'min_length' => array(
				'rule' => array('minLength', '6'),  
				'message' => 'Password must have a mimimum of 6 characters'
			),
			'alphaNumeric' => array(
                'rule'     => 'alphaNumeric',
                'required' => true,
                'message'  => 'Letters and numbers only'
            ),
        ),
		
		'password_confirm' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'Please confirm your password'
            ),
			 'equaltofield' => array(
				'rule' => array('equaltofield','password'),
				'message' => 'Both passwords must match.'
			)
        ),
		'current_password' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'Current password is required'
            ),
             'equaltofield' => array(
                'rule' => array('equalToCurrentPassword'),
                'message' => 'Incorrect password'
            )
        ),
		
		'email' => array(
			'required' => array(
				'rule' => array('email', true),    
				'message' => 'Please provide a valid email address.'    
			),
			 'unique' => array(
				'rule'    => array('isUniqueEmail'),
				'message' => 'This email is already in use',
			),
			'between' => array( 
				'rule' => array('between', 6, 250), 
				'message' => 'Usernames must be between 6 to 60 characters'
			)
		),
         'gender' => array(
            'valid' => array(
                'rule' => array('inList', array('1', '2')),
                'message' => 'Please enter a valid role',
                'allowEmpty' => false
            )
        ),
        'birtdate'=>array(
            'nonEmpty' => array(
                'rule' => array('notEmpty'),
                'message' => 'Birthdate is required',
				'allowEmpty' => false
            ),
            
        ),
        'hobby'=>array(
            'nonEmpty' => array(
                'rule' => array('notEmpty'),
                'message' => 'Hobby is required',
				'allowEmpty' => false
            ),
            
        ),
		'message'=>array(
			'nonEmpty'=>array(
				'rule'=>array('notEmpty'),
				'message'=>'Message is required',
				'allowEmpty'=>false
			),
			
		)
        
       
		
		

		
    );
	
		/**
	 * Before isUniqueUsername
	 * @param array $options
	 * @return boolean
	 */
	/*function isUniqueUsername($check) {

		$username = $this->find(
			'first',
			array(
				'fields' => array(
					'User.id',
					'User.username'
				),
				'conditions' => array(
					'User.username' => $check['username']
				)
			)
		);

		if(!empty($username)){
			if($this->data[$this->alias]['id'] == $username['User']['id']){
				return true; 
			}else{
				return false; 
			}
		}else{
			return true; 
		}
    }*/

	/**
	 * Before isUniqueEmail
	 * @param array $options
	 * @return boolean
	 */
	function isUniqueEmail($check) {

		$email = $this->find(
			'first',
			array(
				'fields' => array(
					'User.email'
				),
				'conditions' => array(
					'User.email' => $check['email']
				)
			)
		);

		if(!empty($email)){
			if($this->data[$this->alias]['email'] == $email['User']['email']){
				return false; 
			}else{
				return true; 
			}
		}else{
			return true; 
		}
    }
	function equalToCurrentPassword($check) {
 
        $user = $this->find(
            'first',
            array(
                'fields' => array(
                    'User.password'
                ),
                'conditions' => array(
                    'User.id' => $this->data[$this->alias]['id']
                )
            )
        );

        if(!empty($user)){
            if(   AuthComponent::password( $check['current_password']) != $user['User']['password'] ){
                return false;
            }else{
                return true;
            }
        }else{
            return false;
        }

    }
	
	public function alphaNumericDashUnderscore($check) {
        // $data array is passed using the form field name as the key
        // have to extract the value to make the function generic
        $value = array_values($check);
        $value = $value[0];

        return preg_match('/^[a-zA-Z0-9_ \-]*$/', $value);
    }
	
	public function equaltofield($check,$otherfield) 
    { 
        //get name of field 
        $fname = ''; 
        foreach ($check as $key => $value){ 
            $fname = $key; 
            break; 
        } 
        return $this->data[$this->name][$otherfield] === $this->data[$this->name][$fname]; 
    } 

	/**
	 * Before Save
	 * @param array $options
	 * @return boolean
	 */
	 public function beforeSave($options = array()) {
		// hash our password
		if (isset($this->data[$this->alias]['password'])) {
			$this->data[$this->alias]['password'] = AuthComponent::password($this->data[$this->alias]['password']);
		}
		
		
	
		// fallback to our parent
		return parent::beforeSave($options);
	}

}

?>