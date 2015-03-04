<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Friends extends CI_Controller {
	var $pop;
	
	/***************************************************************************************************
	* CONSTRUCT
	/***************************************************************************************************/
	public function __construct()
	{
		session_start();
		parent::__construct();
		
		$this->load->database();
		$this->load->library('Facebook');

		$this->load->model('data_model');

		$this->pop = new Facebook();
		

		if(!isset($_SESSION['fb_token'])){
			$me = $this->pop->me();
			$_SESSION['user_id']      = $me['id'];
			$_SESSION['user_name']    = $me['first_name'];
			$_SESSION['user_picture'] = $me['picture']->data->url;
			
		}else{
			//redirect($this->pop->get_login_url());
		}		
	}

	/***************************************************************************************************/
	/***************************************************************************************************/
	public function index()
	{
		redirect('friends/greet_a_friend');	
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
	public function choose_a_song($param = false, $genre = ""){

		if($param){
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

				$data['albums'] = json_decode(
				    file_get_contents('https://itunes.apple.com/search?term='.str_replace("-", "+", $genre).'&entity=song'));
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
	public function customize_greeting_card($param = 0){

		//si esta seteada la cancion, el parametro recibido es le id de amigo
		if(isset($_SESSION['greeting_resource'])){
			//Pide el amigo
			$this->get_friend($param);
		}else{
			//Sino, pide la cancion
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
				'msg_datetime'   => date('Y-m-d H:i'));

				$inserted = $this->data_model->insert_greeting($greeting);
				if($inserted){
					$data['message'] = "<h2>Congratulations!</h2> Your Pop to ".$_SESSION['friend_name']." was successfully sent.";
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
			$header['title'] = "Friends";
		}
		
		
		$friends = $this->pop->get_birthdays();

		//echo $today = date("m/d/y"); 

		//echo $today = date("M, d");
		$today_friends = array();
		foreach ($friends as $value) {
			//echo substr($value->birthday, 0, 5);
			if((substr($value->birthday, 0, 5)) == (date("m/d"))){
				array_push($today_friends, $value);
			}
		}


		$data['friends'] = $today_friends;
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

				 foreach ($history as $value) {
		        	$friend = $this->find_friend($value->msg_friend);
		        	$value->msg_friend = $friend->first_name;
		        	$value->msg_picture = $friend->picture->data->url;
		        	
		        }
			break;

			case 'received':
				$header['title'] = "Received";
				$history = $this->data_model->history_received();    

				foreach ($history as $value) {
		        	$friend = $this->find_friend($value->msg_user);
		        	$value->msg_friend = $friend->first_name;
		        	$value->msg_picture = $friend->picture->data->url;
		        	
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
		$request = new FacebookRequest(
		  $session,
		  'DELETE',
		  '/me/permissions/'
		);
		$response = $request->execute();
		$graphObject = $response->getGraphObject();
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
		var_dump($friends);
	}

	/***************************************************************************************************/
	/***************************************************************************************************/
	public function notificar(){
		$friends = $this->pop->notificar();
		var_dump($friends);
	}


	/***************************************************************************************************/
	/***************************************************************************************************/
	private function get_song($id, $return = false){
		$song = json_decode(file_get_contents('https://itunes.apple.com/lookup?id='.$id));
		if($return){
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
		$profile = $this->pop->get_profile($friend_id);
		$music   = $this->pop->get_music($friend_id);

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
	public function find_friend($param){
		
		foreach ($_SESSION['friends'] as $value) {
			
			if($value->id == $param){
				return $value;
			}
		}
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

					$data['greeting_song'] = $resource->results[0]->trackName;
					$data['greeting_artist'] = $resource->results[0]->artistName;
					$data['greeting_background'] = $background->background_image;
					$data['greeting_cover'] = str_replace("100x100", "600x600", $resource->results[0]->artworkUrl100);
					$data['greeting_preview'] = $resource->results[0]->previewUrl;
					$data['greeting_message'] = $greeting_card->msg_text;
					
					$header['title'] = "";
					switch($option){
						case 'sent':
							$friend = $this->find_friend($greeting_card->msg_friend);
							$data['user_name'] = $_SESSION['user_name']; 
							$data['user_picture'] = $_SESSION['user_picture']; 
							$header['title'] = "Greet ".$friend->first_name;
							break;

						case 'received': 
							 
							
							$friend = $this->find_friend($greeting_card->msg_user);
							$data['user_name'] = $friend->first_name;
							$data['user_picture'] = $friend->picture->data->url; 
							$header['title'] = "Greeting card from ".$friend->first_name;
							break;
					}
					$resource = $this->get_song($greeting_card->msg_resource);
					
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

	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */