<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require(APPPATH.'libraries/REST_Controller.php'); 
class Servidor extends REST_Controller {

	public function parse_get(){

		$this->load->model('data_model');
		
		
		$token = $this->get('token');

		$security = "iCZPuA3eNgj30eFh5ykvY8ikSr3nGqoZK60kZXWxUL2OWrsuy42pcUEu5OJ1";

		if($token != $security){
			 $this->response(NULL, 404);
		}else{

			$msg_user       = $this->get('from');
			$msg_friend     = $this->get('to');
			$msg_text       = $this->get('message');
			$msg_resource   = $this->get('song');
			$msg_background = $this->get('background');
			$msg_cover      = $this->get('cover');
			$msg_status     = "Unread";
			$msg_datetime   = date('Y-m-d H:i');


			$data = array(
				'msg_user'     	 => $msg_user, 
				'msg_friend'   	 => $msg_friend, 
				'msg_text'     	 => $msg_text, 
				'msg_resource' 	 => $msg_resource,
				'msg_background' => $msg_background,
				'msg_cover'      => $msg_cover,
				'msg_status'     => $msg_status,
				'msg_datetime'   => $msg_datetime
				);


				  
		    if($this->data_model->insert_greeting($data))
		    {
		    	
		    	if($this->notificar($msg_friend)){
		    		$this->response("true", 200); // 200 being the HTTP response code	
		    	}else{
		    		$this->response(NULL, 404);
		    	}		        
		    }else{
		        $this->response(NULL, 404);
		    }

		}

	}


	

	


}