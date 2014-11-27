<?php

class Board extends CI_Controller {
     
    function __construct() {
    		// Call the Controller constructor
	    	parent::__construct();
	    	session_start();
    } 
          
    public function _remap($method, $params = array()) {
	    	// enforce access control to protected functions	
    		
    		if (!isset($_SESSION['user']))
   			redirect('account/loginForm', 'refresh'); //Then we redirect to the index page again
 	    	
	    	return call_user_func_array(array($this, $method), $params);
    }
    
    
    function index() {
		$user = $_SESSION['user'];
    		    	
	    	$this->load->model('user_model');
	    	$this->load->model('invite_model');
	    	$this->load->model('match_model');
	    	
	    	$user = $this->user_model->get($user->login);

	    	$invite = $this->invite_model->get($user->invite_id);
	    	
	    	if ($user->user_status_id == User::WAITING) {
	    		$player = 2;
	    		$invite = $this->invite_model->get($user->invite_id);
	    		$otherUser = $this->user_model->getFromId($invite->user2_id);
	    		$data['player']=$player;
	    		$data['matchstatus'] = 1;
	    	}
	    	
	    	else if ($user->user_status_id == User::PLAYING) {
	    		$match = $this->match_model->get($user->match_id);
	    		$data['matchstatus'] = $match->match_status_id;
	    		if ($match->user1_id == $user->id) {
	    			$player = 1;
	    			$otherUser = $this->user_model->getFromId($match->user2_id);
	    		}
	    		else  {
	    			$player = 2;
	    			$otherUser = $this->user_model->getFromId($match->user1_id);
	    		}
	    		$data['player']=$player;
	    	}
	    	
	    	
	    	$data['user']=$user;
	    	$data['otherUser']=$otherUser;
	    	
	    	switch($user->user_status_id) {
	    		case User::PLAYING:	
	    			$data['status'] = 'playing';
	    			break;
	    		case User::WAITING:
	    			$data['status'] = 'waiting';
	    			break;
	    	}
	    	
		$this->load->view('match/board',$data);
    }

 	function postMsg() {
 		$this->load->library('form_validation');
 		$this->form_validation->set_rules('msg', 'Message', 'required');
 		
 		if ($this->form_validation->run() == TRUE) {
 			$this->load->model('user_model');
 			$this->load->model('match_model');

 			$user = $_SESSION['user'];
 			 
 			$user = $this->user_model->getExclusive($user->login);
 			if ($user->user_status_id != User::PLAYING) {	
				$errormsg="Not in PLAYING state";
 				goto error;
 			}
 			
 			$match = $this->match_model->get($user->match_id);			
 			
 			$msg = $this->input->post('msg');
 			
 			if ($match->user1_id == $user->id)  {
 				$msg = $match->u1_msg == ''? $msg :  $match->u1_msg . "\n" . $msg;
 				$this->match_model->updateMsgU1($match->id, $msg);
 			}
 			else {
 				$msg = $match->u2_msg == ''? $msg :  $match->u2_msg . "\n" . $msg;
 				$this->match_model->updateMsgU2($match->id, $msg);
 			}
 				
 			echo json_encode(array('status'=>'success'));
 			 
 			return;
 		}
		
 		$errormsg="Missing argument";
 		
		error:
			echo json_encode(array('status'=>'failure','message'=>$errormsg));
 	}
 
	function getMsg() {
 		$this->load->model('user_model');
 		$this->load->model('match_model');
 			
 		$user = $_SESSION['user'];
 		 
 		$user = $this->user_model->get($user->login);
 		if ($user->user_status_id != User::PLAYING) {	
 			$errormsg="Not in PLAYING state";
 			goto error;
 		}
 		// start transactional mode  
 		$this->db->trans_begin();
 			
 		$match = $this->match_model->getExclusive($user->match_id);			
 			
 		if ($match->user1_id == $user->id) {
			$msg = $match->u2_msg;
 			$this->match_model->updateMsgU2($match->id,"");
 		}
 		else {
 			$msg = $match->u1_msg;
 			$this->match_model->updateMsgU1($match->id,"");
 		}

 		if ($this->db->trans_status() === FALSE) {
 			$errormsg = "Transaction error";
 			goto transactionerror;
 		}
 		
 		// if all went well commit changes
 		$this->db->trans_commit();
 		
 		echo json_encode(array('status'=>'success','message'=>$msg));
		return;
		
		transactionerror:
		$this->db->trans_rollback();
		
		error:
		echo json_encode(array('status'=>'failure','message'=>$errormsg));
 	}

 	function postState() {
 			
 			$this->load->model('user_model');
 			$this->load->model('match_model');
 	
 			$errormsg="Missing argument";
 			
 			$user = $_SESSION['user'];
 				
 			$user = $this->user_model->getExclusive($user->login);
 			if ($user->user_status_id != User::PLAYING) {
 				$errormsg="Not in PLAYING state";
 				goto error;
 			}
 	
 			$match = $this->match_model->get($user->match_id);
 	
 			$state = $this->input->post('data');
 	
 			$this->match_model->updateBoardState($match->id, $state);
 				
 			echo json_encode(array('status'=>'success'));
 				
 			return;
 			
 		error:
 		echo json_encode(array('status'=>'failure','message'=>$errormsg));
 	}
 	
 	function getState() {
 		$this->load->model('user_model');
 		$this->load->model('match_model');
 	
 		$user = $_SESSION['user'];
 	
 		$user = $this->user_model->get($user->login);
 		if ($user->user_status_id != User::PLAYING) {
 			$errormsg="Not in PLAYING state";
 			goto error;
 		}
 		// start transactional mode
 		$this->db->trans_begin();
 	
 		$match = $this->match_model->getExclusive($user->match_id);
 	
 		$state = $match->board_state;
 	
 		if ($this->db->trans_status() === FALSE) {
 			$errormsg = "Transaction error";
 			goto transactionerror;
 		}
 			
 		// if all went well commit changes
 		$this->db->trans_commit();
 			
 		echo json_encode(array('status'=>'success','state'=>$state));
 		return;
 	
 		transactionerror:
 		$this->db->trans_rollback();
 	
 		error:
 		echo json_encode(array('status'=>'failure','message'=>$errormsg));
 	}
 	
 	function postStatus() {
 	
 		$this->load->model('user_model');
 		$this->load->model('match_model');
 	
 		$errormsg="Missing argument";
 	
 		$user = $_SESSION['user'];
 			
 		$user = $this->user_model->getExclusive($user->login);
 		if ($user->user_status_id != User::PLAYING) {
 			$errormsg="Not in PLAYING state";
 			goto error;
 		}
 	
 		$match = $this->match_model->get($user->match_id);
 	
 		$status = $this->input->post('status');
 	
 		$this->match_model->updateMatchStatus($match->id, $status);
 			
 		echo json_encode(array('status'=>'success'));
 			
 		return;
 	
 		error:
 		echo json_encode(array('status'=>'failure','message'=>$errormsg));
 	}
 }

