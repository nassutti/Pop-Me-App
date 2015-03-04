<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
if ( session_status() == PHP_SESSION_NONE ) {
session_start();
}
 
// Autoload the required files
require_once( APPPATH . 'libraries/facebook/FacebookRedirectLoginHelper.php');

require_once( APPPATH . 'libraries/facebook/FacebookSession.php');
require_once( APPPATH . 'libraries/facebook/FacebookRequest.php');


require_once( APPPATH . 'libraries/facebook/HttpClients/FacebookHttpable.php');
require_once( APPPATH . 'libraries/facebook/HttpClients/FacebookCurl.php' );
require_once( APPPATH . 'libraries/facebook/HttpClients/FacebookCurlHttpClient.php');

require_once( APPPATH . 'libraries/facebook/Entities/AccessToken.php');
require_once( APPPATH . 'libraries/facebook/Entities/SignedRequest.php');

require_once(  APPPATH .'libraries/facebook/FacebookResponse.php' );
require_once(  APPPATH .'libraries/facebook/FacebookSDKException.php' );
require_once(  APPPATH .'libraries/facebook/FacebookRequestException.php' );
require_once(  APPPATH .'libraries/facebook/FacebookAuthorizationException.php' );
require_once(  APPPATH .'libraries/facebook/GraphObject.php' );
require_once( APPPATH . 'libraries/facebook/GraphSessionInfo.php');
require_once( APPPATH . 'libraries/facebook/FacebookServerException.php');
require_once( APPPATH . 'libraries/facebook/FacebookPermissionException.php');

require_once( APPPATH . 'libraries/facebook/FacebookPermissionException.php');
require_once( APPPATH . 'libraries/facebook/FacebookSignedRequestFromInputHelper.php');
require_once( APPPATH . 'libraries/facebook/FacebookCanvasLoginHelper.php');



 
use Facebook\FacebookSession;
use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookCanvasLoginHelper;
use Facebook\FacebookRequest;
use Facebook\FacebookResponse;
use Facebook\FacebookSDKException;
use Facebook\FacebookRequestException;
use Facebook\FacebookAuthorizationException;
use Facebook\GraphObject;

use Facebook\Entities\AccessToken;
use Facebook\Entities\SignedRequest; 

use Facebook\FacebookHttpable;
use Facebook\FacebookCurlHttpClient;
use Facebook\FacebookCurl;

use Facebook\GraphSessionInfo;
use Facebook\FacebookServerException;
use Facebook\FacebookPermissionException;
use Facebook\FacebookSignedRequestFromInputHelper;
 
class Facebook {
	var $ci;
	var $helper;
	var $conexion;
	var $permissions;
	 
	public function __construct() {
		$this->ci =& get_instance();
		$this->permissions = $this->ci->config->item('permissions', 'facebook');
		 
		// Initialize the SDK
		FacebookSession::setDefaultApplication( $this->ci->config->item('api_id', 'facebook'), $this->ci->config->item('app_secret', 'facebook') );
		 
		// Create the login helper and replace REDIRECT_URI with your URL
		// Use the same domain you set for the apps 'App Domains'
		// e.g. $helper = new FacebookRedirectLoginHelper( 'http://mydomain.com/redirect' );
		$this->helper = new FacebookCanvasLoginHelper();




		//Si existe en session el token
		if ( $this->ci->session->userdata('fb_token') ) {

			//Recupera la conexion con el token existente
			$this->conexion = new FacebookSession( $this->ci->session->userdata('fb_token') );

			// Validate the access_token to make sure it's still valid
			try {
				if ( ! $this->conexion->validate() ) {
					$this->conexion = null;
				}
			}catch (FacebookAuthorizationException $e){
				$this->conexion = null;

			}catch ( Exception $e ) {
				// Catch any exceptions
				$this->conexion = null;

			}


		} else {
			// Si no existe el token en conexion, crea una sesion nueva a partir del helper.
			try {
				$this->conexion = $this->helper->getSession();


			} catch( FacebookRequestException $ex ) {

				$ex;
				// When Facebook returns an error
			} catch( Exception $ex ) {
				// When validation fails or other local issues
				print_r($ex);
			}
		}

	
		//Si la conexion carga por primera vez
		if ( $this->conexion ) {
	
			//Almacena el token
			$this->ci->session->set_userdata( 'fb_token', $this->conexion->getToken() );
			//Crea la conexion con ese token
			$this->conexion = new FacebookSession( $this->conexion->getToken() );

				
				
		}
			


	}
	 


	/**
	* Returns the login URL.
	*/
	public function get_login_url() {
		return $this->helper->getLoginUrl( $this->permissions );
	}
	 
	/**
	* Returns the current user's info as an array.
	*/
	public function get_user() {
		try {
			if ( $this->conexion ) {
				/**
				* Retrieve User’s Profile Information
				*/
				// Graph API to request user data
				$request = ( new FacebookRequest( $this->conexion, 'GET', '/me' ) )->execute();
				 
				// Get response as an array
				$user = $request->getGraphObject()->asArray();
		 
				return $user;
			}
			
		}catch( FacebookRequestException $ex ) {

				$ex;
				// When Facebook returns an error
				return false;
		} catch (Exception $e) {
			return false;	
		}
		
	}

	/***************************************************************************************************/
	/***************************************************************************************************/
	public function get_permissions() {
		if ( $this->conexion ) {
			try {
				/**
				* Retrieve User’s Profile Information
				*/
				// Graph API to request user data
				$request = ( new FacebookRequest( $this->conexion, 'GET', '/me/permissions' ) )->execute();
				 
				// Get response as an array
				$user = $request->getGraphObject()->asArray();
				 
				return $user;
			}catch( FacebookRequestException $ex ) {

				$ex;
				// When Facebook returns an error
				return false;
			}  catch (Exception $e) {
				return $e;
			}
			
		}
		return false;
	}

	/***************************************************************************************************/
	/***************************************************************************************************/
	public function me() {
		try {
			if ( $this->conexion ) {
				/**
				* Retrieve User’s Profile Information
				*/
				// Graph API to request user data
				$request = ( new FacebookRequest( $this->conexion, 'GET', '/me?fields=id,first_name,picture.width(200).type(square).height(200)' ) )->execute();
				 
				// Get response as an array
				$user = $request->getGraphObject()->asArray();
				 
				return $user;
			}
			return false;
		}catch( FacebookRequestException $ex ) {

				$ex;
				return false;
				// When Facebook returns an error
		}  catch (Exception $e) {
			return false;
		}
		
	}

	/***************************************************************************************************/
	/***************************************************************************************************/
	public function get_music($facebook_id) {
		try {
			if ( $this->conexion ) {
				/**
				* Retrieve User’s Profile Information
				*/
				// Graph API to request user data
				$request = ( new FacebookRequest( $this->conexion, 'GET', '/'.$facebook_id.'/music' ) )->execute();
				 
				// Get response as an array
				$result = $request->getGraphObject()->asArray();
				 
				return $result;
			}
			return false;
		}catch( FacebookRequestException $ex ) {

				$ex;
				return false;
				// When Facebook returns an error
		} catch (Exception $e) {
			return false;
		}
		
	}

	/***************************************************************************************************/
	/***************************************************************************************************/
	public function get_profile($facebook_id) {
		try {
			if ( $this->conexion ) {
			
				$request = ( new FacebookRequest( $this->conexion, 'GET', '/'.$facebook_id.'?fields=id,first_name,picture.width(200).type(square).height(200)' ) )->execute();
		        $result= $request->getGraphObject()->asArray();
		         
				return $result;
			}
			return false;
		}catch( FacebookRequestException $ex ) {

				$ex;
				return false;
				// When Facebook returns an error
		} catch (Exception $e) {
			return false;
			
		}
		
	}

	/***************************************************************************************************/
	/***************************************************************************************************/
	public function get_friends() {
		try {
			if ( $this->conexion ) {
			
				$request = ( new FacebookRequest( $this->conexion, 'GET', '/me/friends?fields=id,first_name,picture.width(200).type(square).height(200)' ) )->execute();
		        $amigos = $request->getGraphObject()->asArray();
		         
				return $amigos['data'];
			}
			return false;
		}catch( FacebookRequestException $ex ) {

				$ex;
				return false;
				// When Facebook returns an error
		} catch (Exception $e) {
			return false;
			
		}
		
	}

	/***************************************************************************************************/
	/***************************************************************************************************/
	public function get_birthdays() {
		try {
			if ( $this->conexion ) {
			
				$request = ( new FacebookRequest( $this->conexion, 'GET', '/me/friends?fields=id,first_name,picture.width(200).type(square).height(200),birthday' ) )->execute();
		        $amigos = $request->getGraphObject()->asArray();
		         
				return $amigos['data'];
			}
			return false;
		} catch( FacebookRequestException $ex ) {

				$ex;
				return false;
				// When Facebook returns an error
		} catch (Exception $e) {
			return false;
		}
		
	}

	

	/***************************************************************************************************/
	/***************************************************************************************************/
	public function delete(){
		try {
			if ( $this->conexion ) {
				$request = (new FacebookRequest($this->conexion, 'DELETE', '/me/permissions/'))->execute();
				$resultado = $request->getGraphObject()->asArray();
		         
				return $resultado;
			}
			return false;
		}catch( FacebookRequestException $ex ) {

				$ex;
				return false;
				// When Facebook returns an error
		} catch (Exception $e) {
			return false;
			
		}
		
		
	}

	/***************************************************************************************************/
	/***************************************************************************************************/
	public function get_token() {
		if ( $this->conexion ) {
			try {
				$request = ( new FacebookRequest( $this->conexion, 'GET', '/oauth/access_token?client_id={688837397870228}&client_secret={222a9974c9a324b4148fb4c01a430d64}&grant_type=client_credentials') )->execute();
	        
	         
				return $request;
			}catch( FacebookRequestException $ex ) {

				$ex;
				return false;
				// When Facebook returns an error
		}  catch (Exception $e) {
				return $e;
			}	
			
		}
		return false;
	}

	/***************************************************************************************************/
	/***************************************************************************************************/
	public function notificar($friend_id){
		if ($this->conexion ) {
			try {
				$this->conexion = FacebookSession::newAppSession();
				$request = new FacebookRequest($this->conexion, 'POST', '/'.$friend_id.'/notifications?access_token=688837397870228|222a9974c9a324b4148fb4c01a430d64&href=https://popmeapps.com/friends&template=You%20have%20a%20new%20message!');
    			$response = $request->execute();
    			// get response
    			$graphObject = $response->getGraphObject()->asArray();
    			
    			return true;

				
			}catch( FacebookRequestException $ex ) {

				$ex;
				return false;
				// When Facebook returns an error
		}  catch (FacebookRequestException  $e) {
				echo "Exception occured, code: " . $e->getCode();
    			echo " with message: " . $e->getMessage();

			}
			
		}else{

			return false;
		}
		
		
	}

	/***************************************************************************************************/
	/***************************************************************************************************/
	function parse_signed_request($signed_request) {
	  list($encoded_sig, $payload) = explode('.', $signed_request, 2); 

	  $secret = $this->ci->config->item('app_secret', 'facebook'); // Use your app secret here

	  // decode the data
	  $sig = base64_url_decode($encoded_sig);
	  $data = json_decode(base64_url_decode($payload), true);

	  // confirm the signature
	  $expected_sig = hash_hmac('sha256', $payload, $secret, $raw = true);
	  if ($sig !== $expected_sig) {
	    error_log('Bad Signed JSON signature!');
	    return null;
	  }

	  return $data;
	}

	function base64_url_decode($input) {
	  return base64_decode(strtr($input, '-_', '+/'));
	}

	function dashboard(){
		echo "<pre>";
		var_dump($_conexion);
		echo "</pre>";
	}


	function debug_to_console( $data ) {

    if ( is_array( $data ) )
        $output = "<script>console.log( 'Debug Objects: " . implode( ',', $data) . "' );</script>";
    else
        $output = "<script>console.log( 'Debug Objects: " . $data . "' );</script>";

    echo $output;
}


}