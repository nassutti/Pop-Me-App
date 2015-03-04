<?php defined('BASEPATH') OR exit('No direct script access allowed');

require_once( APPPATH .'controllers/include/autoload.php');


require_once( APPPATH . 'libraries/Parse/Internal/Encodable.php');
require_once( APPPATH . 'libraries/Parse/Internal/FieldOperation.php');
require_once( APPPATH . 'libraries/Parse/Internal/SetOperation.php');

require_once( APPPATH . 'libraries/Parse/ParseStorageInterface.php');
require_once( APPPATH . 'libraries/Parse/ParseSessionStorage.php');

require_once( APPPATH . 'libraries/Parse/ParseClient.php');
require_once( APPPATH . 'libraries/Parse/ParseObject.php');
require_once( APPPATH . 'libraries/Parse/ParseQuery.php');
require_once( APPPATH . 'libraries/Parse/ParseACL.php');
require_once( APPPATH . 'libraries/Parse/ParsePush.php');
require_once( APPPATH . 'libraries/Parse/ParseUser.php');
require_once( APPPATH . 'libraries/Parse/ParseInstallation.php');
require_once( APPPATH . 'libraries/Parse/ParseException.php');
require_once( APPPATH . 'libraries/Parse/ParseAnalytics.php');
require_once( APPPATH . 'libraries/Parse/ParseFile.php');
require_once( APPPATH . 'libraries/Parse/ParseCloud.php');


require_once( APPPATH . 'libraries/Parse/ParseRole.php');


use Parse\ParseClient;
use Parse\ParseObject;
use Parse\ParseQuery;
//use Parse\ParseRelation;
use Parse\ParseACL;
use Parse\ParsePush;
use Parse\ParseUser;
use Parse\ParseInstallation;
use Parse\ParseException;
use Parse\ParseAnalytics;
use Parse\ParseFile;
use Parse\ParseCloud;

use Parse\Internal\Encodable;
use Parse\Internal\FieldOperation;
use Parse\Internal\SetOperation;
use Parse\Role;
use Parse\ParseStorageInterface;
use Parse\ParseSessionStorage;

class Parse{

	private $CI;
  private $idclass;
	
	public function __construct()
	{
	    //parent::__construct();  //Si hubiera una clase padre
	    $this->CI = &get_instance();
	    $this->idclass = 1;
		  //mt_srand((double)microtime()*1000000);
		  $APPID = 'QievnEMnPVePl5IuiEkbK47iUNHaORE98a2tlgcx';
      $RESTKEY = '5nhxBdaWoobBSJETssRZkybUO0nMW3dcO47VsAgI';
      $MASTERKEY = '75zTvvoVsxwWHebxWavyX7xX2uHJ92tuR5BHBE6D';
      ParseClient::initialize( $APPID, $RESTKEY, $MASTERKEY );
	}
	



  public function find_user($fbId){
    try{
      $user = new ParseUser();
      
      $query = $user::query();
      $query->equalTo('fbId',$fbId); 
      $users = $query->find();
      return $users;

    } catch (ParseException $ex) {
      // Show the error message somewhere and let the user try again.
      echo "Error: " . $ex->getCode() . " " . $ex->getMessage();
    }
    catch (Exception $e) {
      echo $e->getMessage();
    }
    

     
  }

  public function insert_user($fbId, $token, $firstName, $expirate){
      $user = new ParseUser();

      $authData = array(
        'facebook' => array(
          'access_token' => $token,
          'expiration_date' => '2015-03-01T22:09:08.052Z',
          'id' => $fbId
        ));

      $json = json_encode($authData);
      
      $query = $user::query();
      $user->set("username", $firstName);  
      $user->set("firstName", $firstName);  
      $user->set("password", md5("password")); 
      $user->set('fbId', $fbId);
      $user->set('authData', $json);
      $user->set('WebAppInstalled', true);
     
      $user->set('email', $fbId."@popmeapp.com");
    try {
      $user->signUp();
      $object_id = $user->getObjectId();
    
      return $object_id;
    } catch (ParseException $ex) {
      // Show the error message somewhere and let the user try again.
      echo "Error: " . $ex->getCode() . " " . $ex->getMessage();
      return false;
    }
    catch (Exception $e) {
      echo $e->getMessage();
      return false;
    }
    

      
  }

   public function insert_pop($from, $to,$songId, $bkgId, $message){
    try {
      $obj = ParseObject::create('Pop');
      $obj->set('bkgId', $bkgId);
      $obj->set("from", $from[0]);

      $obj->set('message', $message);
      $obj->set('source', "web");
      $obj->set('songId', $songId);
      $obj->set("to", $to[0]);

      $obj->save();
      return $obj->getObjectId();
      
    } catch (ParseException $ex) {
      echo "Error: " . $ex->getCode() . " " . $ex->getMessage();
    }
    catch (Exception $e) {
      echo $e->getMessage();
    }
     
  }
  

}
