<?php
class Data_model extends CI_Model {

	function __construct() {
		parent::__construct();


	}


	function insert_user($data) {
		$this -> db -> insert('user', $data);
		return !$this -> db -> affected_rows() == 0;
	}

	function insert_message($data) {
		$this -> db -> insert('message', $data);
		return !$this -> db -> affected_rows() == 0;
	}


	function find_user_by_id($user_id){
		$this->db->where('usr_facebookid', $usuario_id);
		$query = $this -> db -> get('usr');

		if ($query -> num_rows() > 0) {
			return $query -> result();
		} else {
			//show_error('Database is empty!');
		}
	}

    function find_activity_by_id($activity_id){
       return $this -> db -> get_where('message', array('msg_id' => $activity_id));
    }


	function find_background(){
	 $query = $this -> db -> get('background');

        if ($query -> num_rows() > 0) {
            return $query -> result();
        } else {
            //show_error('Database is empty!');
        }
	}

    function find_background_by_id($id){
        return $this -> db -> get_where('background', array('background_id' => $id));
        
    }

    function history_sent(){
        $this->db->limit(20);
        $this->db->order_by('msg_id', 'DESC');
        $this->db->where('msg_user', $_SESSION['user_id']);
        $query = $this->db->get('message');

        if ($query -> num_rows() > 0) {
            return $query -> result();
        } else {
            //show_error('Database is empty!');
        }
    }

    function history_received(){
        $this->db->limit(20);
        $this->db->order_by('msg_id', 'DESC');
        $this->db->where('msg_friend', $_SESSION['user_id']);
        $query = $this->db->get('message');
        
        if ($query -> num_rows() > 0) {
            return $query -> result();
        } else {
            //show_error('Database is empty!');
        }
    }

    function update_message($data, $message_id){
        $this -> db -> where('msg_id', $message_id);
        $this -> db -> update('message', $data);
        //return !$this -> db -> affected_rows() == 0;
        if($this -> db -> affected_rows() > 0){
            return true;
        }else{
            return false;
        }
    }

     function unread_messages(){
        $this->db->limit(20);
        $this->db->order_by('msg_id', 'DESC');
        $this->db->where('msg_friend', $_SESSION['user_id']);
        $this->db->where('msg_status', 'Unread');
        $query = $this->db->get('message');
        
        if ($query -> num_rows() > 0) {
            return $query -> result();
        } else {
            //show_error('Database is empty!');
        }
    }



    
	

}
?>