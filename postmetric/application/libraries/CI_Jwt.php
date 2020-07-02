<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//https://github.com/hprasetyou/CI-Jwt-library

require_once APPPATH . 'third_party/JWT.php';

class Ci_jwt{
  public $key='SIRIBATHINARAMAKIRANPHANIKUMARI';
  public $header = '{"alg":"HS256","typ":"JWT"}';

  function jwt_encode($data,$keyval=null)
  {
	   $JWT = new JWT;
	  if($keyval==null)
		  $keyval = $this->key;
      $payload=json_encode($data);
     
      return $JWT->encode($this->header, $payload, $keyval);
  }
  function jwt_decode($token,$keyval=null)
  {
	   $JWT = new JWT;
	   if($keyval==null)
		  $keyval = $this->key; 
      return json_decode($JWT->decode($token, $keyval));
  }
  
  
   function jwt_web_encode($data)
  {
		$JWT = new JWT;
		$ci_instance =& get_instance(); 
		$keyval = $ci_instance->session->userdata("webapp_key"); 
		$payload=json_encode($data);
     
		return $JWT->encode($this->header, $payload, $keyval);
  }
  function jwt_web_decode($token,$keyval=null)
  {
		$JWT = new JWT;
		$ci_instance =& get_instance(); 
		$keyval = $ci_instance->session->userdata("webapp_key"); 
		return json_decode($JWT->decode($token, $keyval));
  }
}
