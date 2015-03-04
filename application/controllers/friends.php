<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Friends extends CI_Controller {
	var $pop;
	var $parser;
	
	/***************************************************************************************************
	* CONSTRUCT
	/***************************************************************************************************/
	public function __construct()
	{
		
		parent::__construct();
		
		$this->load->database();
		$this->load->library('Facebook');
		$this->load->library('parse');

		$this->parser = new Parse();
		$this->pop = new Facebook();

		$this->load->model('data_model');

		if(!isset($_SESSION)){
		    session_start();
		}
	}

	/***************************************************************************************************/
	/***************************************************************************************************/
	public function index()
	{
		$this->load->view('login_view');

	}

	public function init(){
		$signed_request = $this->input->post('signed_request');
		if( $signed_request != null){

			//Verifica que todos los permisios esten aceptados,
			//si hay por lo menos un permiso rechazado lo manda al Permission Dialogue
			$permissions = $this->pop->get_permissions();

			$permissions_url = "https://www.facebook.com/dialog/oauth?client_id=688837397870228&redirect_uri=https://apps.facebook.com/688837397870228/friends/init&auth_type=rerequest&scope=public_profile,email,user_birthday,user_likes,user_friends";
			$permissions_ok = 1;
			try {
				if(sizeof($permissions)>0){
					foreach ($permissions as $value) {
						if($value->status == "declined"){
							 $permissions_ok = 0;
						}

						
					}
					}
			} catch (Exception $e) {
				$e->getMessage();
			}
		
			

				
			//if(isset($this->session->userdata['fb_token'])){
			if(isset($this->session->userdata['fb_token'])){
				//echo "entra";
				$me = $this->pop->me();
				if($me == false){
					//Logout
					redirect('friends/index');
				}else{

					$_SESSION['user_id']      = $me['id'];
					$_SESSION['user_name']    = $me['first_name'];
					$_SESSION['user_picture'] = $me['picture']->data->url;

					if(!isset($_SESSION['parse_id'])){

						$usuario = $this->find_user($_SESSION['user_id']);

						if(sizeof($usuario) == 0){
							//If Parse User doesn't exists, insert it.


							$this->insert_user();

							$usuario = $this->find_user($_SESSION['user_id']);							
							$_SESSION['parse_id'] = $usuario[0]->getObjectId();
							
						}else{
							//Parse User Found
							$_SESSION['parse_id'] = $usuario[0]->getObjectId();
						}
					}
					
					if($permissions_ok == 0){
						echo "<script language=javascript>window.open('$permissions_url', '_parent', '')</script>";
					}else{
						$user = $this->parse->find_user($me['id']);
						
						if($user[0]->status != true){
							redirect('friends/landing');
						}else{
							redirect('friends/greet_a_friend');
						}
						
					}
					
					
				}
				
				
				//
				
			}else{
				unset($_SESSION);
				
				
				//redirect('friends/landing');
				redirect('friends/index');
				//echo "no funciona";
			}	

		}else{
			$url = "https://apps.facebook.com/688837397870228/";
			redirect($url, 'refresh');
		}
		
	}

	/****************************************************************************************************
	* GEET A FRIEND
	*
	* Shows all the friends able to be greet using the application.
	*
	* @param = false by default, if it's true the greeting data on $_SESSION will be deleted
	* @greeting_resource = 0 by default, gives the iTunes Resource ID in FLOW 2
	/***************************************************************************************************/
	public function greet_a_friend($param = false, $greeting_resource = 0)
	{
		//$this->pop->delete();
		
		$header['messages'] = $this->data_model->unread_messages();
			
		//If the Song was already chosen, unset all the related data on session.
		if($param){
			unset($_SESSION['greeting_artist']);
			unset($_SESSION['greeting_album']);
			unset($_SESSION['greeting_song']);
			unset($_SESSION['greeting_cover']);
			unset($_SESSION['greeting_resource']);
			unset($_SESSION['greeting_preview']);
			unset($_SESSION['greeting_message']);
			unset($_SESSION['greeting_background']);
			unset($_SESSION['greeting_background_id']);
		}

		if($greeting_resource != 0){
			$this->get_song($greeting_resource);
		}

		if(isset($_SESSION['greeting_resource'])){
			$header['title'] = "Choose a friend to pop";
		}else{
			$header['title'] = "Friends";
		}
		
		$this -> form_validation -> set_rules('search-box', 'Search', 'trim|required|xss_clean');

		if ($this -> form_validation -> run() == FALSE) {
			
	    	//$data['friends'] = $this->pop->get_friends();
			$_SESSION['friends'] = $this->pop->get_friends();
			
			$this->load->view('header', $header);
			$this->load->view('options_view');
			$this->load->view('navigation_view');
			$this->load->view('search_view');
			$this->load->view('friends_view');
			$this->load->view('footer');
		}else{
			
			//$data['friends'] = $this->pop->get_friends();
			$friends = array();
			foreach ($_SESSION['friends'] as $value) {
				//var_dump($value);
				$found = strpos(mb_strtolower($value->first_name), mb_strtolower($this->input->post('search-box')));
				if($found !== false){
					array_push($friends, $value);
				}
			}

			$data['friends'] = $friends;
			
			$this->load->view('header', $header);
			$this->load->view('options_view');
			$this->load->view('navigation_view');
			$this->load->view('search_view');
			$this->load->view('friends_filter_view', $data);
			$this->load->view('footer');
		}
		
	}

	/****************************************************************************************************
	* FRIEND PROFILE
	* @friend_id = 0 by default, if it's true the greeting data on $_SESSION will be deleted
	/***************************************************************************************************/
	public function friend_profile($friend_id = 0)
	{
		if($friend_id != 0){

			$this->get_friend($friend_id);	
			$header['title'] = "Geet ".$_SESSION['friend_name'];

			//Loading views
			$this->load->view('header', $header);
			$this->load->view('navigation_view');
			$this->load->view('greeting_profile_view');
			$this->load->view('footer');
		}else{
			redirect('friends');
		}
		
	}


	/***************************************************************************************************/
	/***************************************************************************************************/
	public function choose_a_song($param = "false", $genre = ""){
		

		if($param == "true"){
			
			unset($_SESSION['friend_id']);
			unset($_SESSION['friend_name']);
			unset($_SESSION['friend_picture']);

		}
		
		$this -> form_validation -> set_rules('search-box', 'Search Term', 'trim|required|xss_clean');

		if(isset($_SESSION['friend_name'])){
				$header['title'] = "Choose a song to ".$_SESSION['friend_name'];
			}else{
				$header['title'] = "Discover more music";
			}

		if ($this -> form_validation -> run() == FALSE) {
			$data['albums'] = array();
			$data['search'] = false;
			if($genre != ""){
				$genre = str_replace("-", "+", $genre);
				$genre = str_replace("&", "and", $genre);
				$data['albums'] = json_decode(
				    file_get_contents('https://itunes.apple.com/search?term='.$genre.'&entity=song'));
				$data['search'] = true;
			}else{
				$data['albums'] = json_decode(
				    file_get_contents('https://itunes.apple.com/us/rss/topsongs/limit=50/json'));
			}
			

			

			$this->load->view('header', $header);
			$this->load->view('search_songs_view');
			$this->load->view('navigation_view');			
			$this->load->view('albums_view', $data);
			$this->load->view('footer');
		}else{
			$term = mb_strtolower(str_replace(" ", "+", $this->input->post('search-box')));
			$term = str_replace("&", "and", $term);
			$data['albums'] = json_decode(
				    file_get_contents('https://itunes.apple.com/search?term='.$term.'&entity=song'));

			$data['search'] = true;

			$this->load->view('header', $header);
			$this->load->view('search_songs_view');
			$this->load->view('navigation_view');
			$this->load->view('albums_view', $data);
			$this->load->view('footer');
		}
	}

	/***************************************************************************************************/
	/***************************************************************************************************/
	public function song_preview($param = 0){
		if($param != 0){
			
				//Sino, pide la cancion
			$data['song'] = $this->get_song($param, true);
			


			$header['title'] = "Song preview";

			$this->load->view('header', $header);
			$this->load->view('navigation_view');
			$this->load->view('song_preview_view', $data);
			$this->load->view('footer');

		}else{
			redirect('friends/greet_a_friend');
		}
	}


	/***************************************************************************************************/
	/***************************************************************************************************/
	public function customize_greeting_card($param = 0){

		//si esta seteada la cancion, el parametro recibido es le id de amigo
		if(!isset($_SESSION['user_friend'])){
			//Pide el amigo
			//echo "entro a buscar al amigo";
			$this->get_friend($param);
		}

		if(!isset($_SESSION['greeting_resource'])){
			//Sino, pide la cancion
			//echo "entro a buscar la cancion";
			
			$this->get_song($param);
		}

		//remplazar variable $resource_id por la de session
		$this -> form_validation -> set_rules('message', 'Message', 'trim|required|xss_clean');


	    if ($this -> form_validation -> run() == FALSE) {
	    	$header['title'] = "Customize your greeting card";

			$this->load->view('header', $header);
			$this->load->view('navigation_view');
			$this->load->view('greeting_compose_view');
			$this->load->view('footer');
			
	    } else {

	    	$_SESSION['greeting_message'] = $this->input->post('message');
	    	$url = "friends/background_greeting_card/";
	    	redirect($url);
	    }
		
	}

	/***************************************************************************************************/
	/***************************************************************************************************/
	public function background_greeting_card(){
		
		$header['title'] = "Choose a ".$_SESSION['friend_name']." song";

		$data['background'] = $this->data_model->find_background();

		$this->load->view('header', $header);
		$this->load->view('navigation_view');
		$this->load->view('greeting_background_view', $data);
		$this->load->view('footer');
		
	}

	/***************************************************************************************************/
	/***************************************************************************************************/
	public function preview_greeting_card(){
		
		$header['title'] = "Preview Greeting Card";

		$this->load->view('header', $header);
		$this->load->view('navigation_view');
		$this->load->view('greeting_preview_view');
		$this->load->view('footer');
	
	}

	/***************************************************************************************************/
	/***************************************************************************************************/
	public function pop_song(){

		if(!isset($_SESSION['friend_id'])){
			redirect('friends', 'refresh');
		}
		try {
			$greeting = array(
				'msg_user'       => $_SESSION['user_id'],
				'msg_friend'     => $_SESSION['friend_id'],
				'msg_resource'   => $_SESSION['greeting_resource'],
				'msg_text'       => $_SESSION['greeting_message'],
				'msg_background' => $_SESSION['greeting_background_id'],
				'msg_cover' 	 => $_SESSION['greeting_cover'],
				'msg_datetime'   => date('Y-m-d H:i'));

				$inserted = $this->data_model->insert_greeting($greeting);
				if($inserted){
					
					if($this->notificar($_SESSION['friend_id'])){
						//Notifico bien

						$object_me = $this->find_user($_SESSION['user_id']);
						$object_friend = $this->find_user($_SESSION['friend_id']);
						

						$this->insert_pop($object_me, $object_friend, $_SESSION['greeting_resource'], $_SESSION['greeting_background_id'], $_SESSION['greeting_message']);

						


						$data['message'] = "<h2>Congratulations!</h2> Your Pop to ".$_SESSION['friend_name']." was successfully sent.";
					}else{
						//No pudo notificar
						$data['message'] = "<h2>Sorry!</h2> Your Pop to ".$_SESSION['friend_name']." was successfully sent, but we couldn't send the Facebook notification.";
					}
				}else{
					$data['message'] = "<h2>Sorry!</h2> Your Pop to ".$_SESSION['friend_name']." couldn't be successfully sent.";
				}

			} catch (Exception $e) {
				
			}
			$header['back'] = false;
			$header['title'] = "Pop song to ".$_SESSION['friend_name'];		
			$this->load->view('header', $header);
			$this->load->view('navigation_view');
			$this->load->view('greeting_congratulations_view', $data);
			$this->load->view('footer');

			if($inserted){
				unset($_SESSION['greeting_artist']);
				unset($_SESSION['greeting_album']);
				unset($_SESSION['greeting_song']);
				unset($_SESSION['greeting_cover']);
				unset($_SESSION['greeting_resource']);
				unset($_SESSION['greeting_preview']);
				unset($_SESSION['greeting_message']);
				unset($_SESSION['greeting_background']);
				unset($_SESSION['greeting_background_id']);

				unset($_SESSION['friend_id']);
				unset($_SESSION['friend_name']);
				unset($_SESSION['friend_picture']);
				unset($_SESSION['friend_artist_list']);
				unset($_SESSION['friend_genres_list']);

			}
	}

	/***************************************************************************************************/
	/***************************************************************************************************/
	public function events($param = false, $greeting_resource = 0){
		//If the Song was already chosen, unset all the related data on session.
		if($param){
			unset($_SESSION['greeting_artist']);
			unset($_SESSION['greeting_album']);
			unset($_SESSION['greeting_song']);
			unset($_SESSION['greeting_cover']);
			unset($_SESSION['greeting_resource']);
			unset($_SESSION['greeting_preview']);
			unset($_SESSION['greeting_message']);
			unset($_SESSION['greeting_background']);
			unset($_SESSION['greeting_background_id']);
		}

		if($greeting_resource != 0){
			$this->get_song($greeting_resource);
		}

		if(isset($_SESSION['greeting_resource'])){
			$header['title'] = "Choose a friend to pop";
		}else{
			$header['title'] = "Events";
		}
		
		$today = date("m/d");
		$friends = $this->pop->get_birthdays();

		$friends = $this->sort_friends_by_birthday($friends);


		$today_events = $this->today_events($friends);
		$week_events = $this->week_events($friends);
		$month_events = $this->month_events($friends);

		
		$data['today_events'] = $today_events;
		$data['week_events'] = $week_events;
		$data['month_events'] = $month_events;

		$this->load->view('header', $header);
		$this->load->view('options_view');
		$this->load->view('navigation_view');
		$this->load->view('events_view', $data);
		$this->load->view('footer');
	}

		/***************************************************************************************************/
	/***************************************************************************************************/
	public function activities($param){


		$history = null;
		switch($param){
			case 'sent':
				$header['title'] = "Sent";
				$history = $this->data_model->history_sent();    

				if($history != null){
					foreach ($history as $value) {
			        	$friend = $this->find_friend($value->msg_friend);
			        	$value->msg_friend = $friend->first_name;
			        	$value->msg_picture = $friend->picture->data->url;
		        	
		        	}
				}else{
					$history = array();
				}
				 
			break;

			case 'received':
				$header['title'] = "Received";
				$history = $this->data_model->history_received();    
				if($history != null){
					foreach ($history as $value) {
						if($value->msg_user == '1383647635275649'){
							$value->msg_friend = "Pop Me App";
		        			$value->msg_picture = "https://popmeapps.com/images/avatar.jpg";
						}else{
							$friend = $this->find_friend($value->msg_user);
		        			$value->msg_friend = $friend->first_name;
		        			$value->msg_picture = $friend->picture->data->url;
						}
		        		
		        	
		        	}
				}else{
					$history = array();
				}
				
			break;


		}
	
	    $data['history'] = $history;

        $header['title'] = "Activities";
		
		$this->load->view('header', $header);
		$this->load->view('navigation_view');
		$this->load->view('activity_view', $data);
		$this->load->view('footer');
	}

	/***************************************************************************************************/
	/***************************************************************************************************/
	public function delete(){
	$this->pop->delete();
	}

	/***************************************************************************************************/
	/***************************************************************************************************/
	public function request($image, $id){
		$_SESSION['greeting_background'] = $image;
		$_SESSION['greeting_background_id'] = $id;

		
	}

	/***************************************************************************************************/
	/***************************************************************************************************/
	public function invite(){
		$friends = $this->pop->invite();
	}

	/***************************************************************************************************/
	/***************************************************************************************************/
	public function get_song($id, $return = false){
		$song = json_decode(file_get_contents('https://itunes.apple.com/lookup?id='.$id));
		if($return == true){
			return $song;
			

		}

		$_SESSION['greeting_artist'] = $song->results[0]->artistName;
		$_SESSION['greeting_album'] = $song->results[0]->collectionName;
		$_SESSION['greeting_song'] = $song->results[0]->trackName;
		$_SESSION['greeting_cover'] = str_replace("100x100", "600x600", $song->results[0]->artworkUrl100);
		$_SESSION['greeting_resource'] = $id;
		$_SESSION['greeting_preview'] = $song->results[0]->previewUrl;
	}

	/***************************************************************************************************/
	/***************************************************************************************************/
	private function get_friend($friend_id){
		// Get profile and music by separete
		if(isset($_SESSION['friend_id'])){
			$profile = $this->pop->get_profile($_SESSION['friend_id']);
			$music   = $this->pop->get_music($_SESSION['friend_id']);
		}else{
			$profile = $this->pop->get_profile($friend_id);
			$music   = $this->pop->get_music($friend_id);
		}
		


		// Create empty array to artists and genres
		$artist_list = array();
		$genres_list = array();

		// Music iteration, separating artist and genres
		foreach ($music['data'] as $value) {
			if(($value->category == "Musical genre")||($value->category == "Interest")){
				array_push($genres_list, $value);
			}else{
				array_push($artist_list, $value);	
			}	
		}

		// Clean data to the view
		$_SESSION['friend_id'] = $profile['id'];
		$_SESSION['friend_name'] = $profile['first_name'];
		$_SESSION['friend_picture'] = $profile['picture']->data->url;
		$_SESSION['friend_artist_list'] = $artist_list;
		$_SESSION['friend_genres_list'] = $genres_list;


		
	}

	/***************************************************************************************************/
	/***************************************************************************************************/
	private function find_friend($param){
		foreach ($_SESSION['friends'] as $value) {
			if($value->id == $param){
				return $value;
			}
		}
	}


	/***************************************************************************************************/
	/***************************************************************************************************/
	private function notificar($friend_id){
		try {
			$this->pop->notificar($friend_id);
		} catch (Exception $e) {
			return false;
			
		}
			
		
		
		return true;
	}




	/***************************************************************************************************/
	/***************************************************************************************************/
	public function view_activity($activity_id = 0, $option = null){
		if($activity_id != 0){
			$greeting_card = $this->data_model->find_activity_by_id($activity_id)->row();
			if($greeting_card != null){
				if($option != null){
					$resource = $this->get_song($greeting_card->msg_resource, true);
					$friend = array();

					$background = $this->data_model->find_background_by_id($greeting_card->msg_background)->row();
					
					if(isset($resource->results[0])){
						$data['greeting_song'] = $resource->results[0]->trackName;
						$data['greeting_artist'] = $resource->results[0]->artistName;
						$data['greeting_cover'] = str_replace("100x100", "600x600", $resource->results[0]->artworkUrl100);
						$data['greeting_preview'] = str_replace('http', 'https', $resource->results[0]->previewUrl);

					}else{
						$data['greeting_song'] = "Great musical news";
						$data['greeting_artist'] = "";
						$data['greeting_cover'] = "https://popmeapps.com/images/default_cover.png";
						$data['greeting_preview'] = "";
					}
					
					$data['greeting_background'] = $background->background_image;
					
					$data['greeting_message'] = $greeting_card->msg_text;

					if($greeting_card->msg_status == "Unread"){
						$this->read_message($greeting_card->msg_id);
					}

					
					$header['title'] = "";
					switch($option){
						case 'sent':
							$friend = $this->find_friend($greeting_card->msg_friend);
							$data['user_name'] = $_SESSION['user_name']; 
							$data['user_picture'] = $_SESSION['user_picture']; 
							$header['title'] = "Greet ".$friend->first_name;
							break;

						case 'received': 
							 
							if($greeting_card->msg_user != '1383647635275649'){
								$friend = $this->find_friend($greeting_card->msg_user);
								$data['user_name'] = $friend->first_name;
								$data['user_picture'] = $friend->picture->data->url; 
								$header['title'] = "Greeting card from ".$friend->first_name;
							}else{
								$data['user_name'] = "Pop Me App";
								$data['user_picture'] = "https://popmeapps.com/images/avatar.jpg"; 
								$header['title'] = "Notification from Pop Me App";
							}
							break;


					}

					
					//$resource = $this->get_song($greeting_card->msg_resource);
					
					$this->load->view('header', $header);
					$this->load->view('navigation_view');
					$this->load->view('greeting_card_view', $data);
					$this->load->view('footer');
				}else{
					redirect('friends', 'refresh');
				}

			}else{
				redirect('friends', 'refresh');
			}
		}else{
			redirect('friends', 'refresh');
		}
		

	}


	/***************************************************************************************************/
	/***************************************************************************************************/
	public function landing(){

		$this->load->view('landing_view');
	}


	/***************************************************************************************************/
	/***************************************************************************************************/

	private function find_user($fbId){
		
		 $usuario = $this->parser->find_user($fbId);
		 
		 return $usuario;
		 
	}

	/***************************************************************************************************/
	/***************************************************************************************************/

	private function insert_user(){
		
		try {

			$session = $this->pop->helper->getSession();
			$token = $session->getAccessToken();
			$expires = $token->getExpiresAt();


		 	$this->parser->insert_user($_SESSION['user_id'], $this->session->userdata['fb_token'], $_SESSION['user_name'], $expires->format('Y-m-d H:i:s'));
		 	} catch (Exception $e) {
		 		echo $e->getMessage();
		 	}
		 	
	}

	/***************************************************************************************************/
	/***************************************************************************************************/

	private function insert_pop($from, $to, $songId, $bkgId, $message){
		
		try {
			$object_id = $this->parse->insert_pop($from, $to, $songId, $bkgId, $message);
			if($object_id != null){
				return $object_id;
			}else{
				return null;
			}
		} catch (Exception $e) {
			$e->getMessage();
		}
	}

	/***************************************************************************************************/
	/***************************************************************************************************/

	private function today_events($friends){
		$today = date("m/d");
		$results = array();
		foreach ($friends as $value) {
			//echo substr($value->birthday, 0, 5);
			if(isset($value->birthday)){
				$birthday = substr($value->birthday, 0, 5);
			if($birthday == $today){
				array_push($results, $value);
			}
			}
			
		}

		return $results;
	}

	/***************************************************************************************************/
	/***************************************************************************************************/

	private function week_events($friends){
		$current_month = date("m");
		$results = array();

		$date_monday = date("d",strtotime('monday this week'));    
		$date_sunday = date("d",strtotime("sunday this week"));    

		foreach ($friends as $value) {
			if(isset($value->birthday)){
			//Get the birthday month
			$birthday_month = substr($value->birthday, 0, 2);


			if($birthday_month == $current_month){
				$birthday = substr($value->birthday, 3, 5);

				settype($date_monday, 'integer');
				settype($date_sunday, 'integer');
				settype($birthday, 'integer');

				if(($date_monday <= $birthday)&&($birthday <= $date_sunday)){
					array_push($results, $value);
				}
				
			}
			}
			
		}
		return $results;

	}

	/***************************************************************************************************/
	/***************************************************************************************************/

	private function month_events($friends){
		$current_month = date("m");

        $results = array();
		foreach ($friends as $value) {

			if(isset($value->birthday)){
			//Get the birthday month
			$birthday = substr($value->birthday, 0, 2);
			
			if($birthday == $current_month){
				array_push($results, $value);
				
			}
			}
		}

		return $results;
	}

	private function sort_friends_by_birthday($friends){

		//Cleaning birthday
		foreach ($friends as $value) {
			
			if(isset($value->birthday)){
				$value->birthday = substr($value->birthday, 0, 5);
				$value->date = strtotime($value->birthday);
			}
			
			
			
			

		}

		usort($friends, array($this,"mifuncion"));

		return $friends;


	}

	private function mifuncion($a, $b){
		if((isset($a->date)) && (isset($b->date))){
			if ($a->date == $b->date) {
	        	return 0;
	    	}
	    	return ($a->date < $b->date) ? -1 : 1;
		}
		

	}

	private function read_message($message_id = 0){
		if($message_id != 0){
			//Verificar si el mensaje esta leido
			
			try {
				$data = array('msg_status' => "Read");
				$this->data_model->update_message($data, $message_id);

				return true;
			} catch (Exception $e) {
				return false;
			}
						
					
		}else{
			return false;
		}
	}


	

private function dashboard(){
/*
	try {

			$session = $this->pop->helper->getSession();
			$token = $session->getAccessToken();
			$expires = $token->getExpiresAt();


		 		$usuario = $this->parser->insert_user($_SESSION['user_id'], $this->session->userdata['fb_token'], $_SESSION['user_name'], $expires->format('Y-m-d H:i:s'));
		 		var_dump($usuario);
		 	} catch (Exception $e) {
		 		echo $e->getMessage();
		 	}
		 	*/
}

	

	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */