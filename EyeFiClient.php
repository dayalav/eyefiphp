<?php
/** 
 * EyeFiClientPHP - A simple EyeFi client that works without cURL.
 *
 * @author     David Ayala <davidayalavilluendas@gmail.com>
 * @copyright  David Ayala 2015
 * @version    1.0
 * @license    See LICENSE https://github.com/dayalav/eyefiphp/#license
 *
 */
 
class EyeFiClient {
	
	const API_URL = "https://api.eyefi.com/3/";

	private $appParams;	
	private $accessToken;
	private $locale;
	private $useCurl;
	
	function __construct ($app_params, $locale = "es"){
		$this->appParams = $app_params;
		if(empty($app_params['access_token']))
			throw new EyeFiException("Access Token is empty!");
		
		$this->locale = $locale;
		$this->accessToken = $this->appParams['access_token'];
		
		$this->useCurl = function_exists('curl_init');
	}
	
    /** 
	 * Sets whether to use cURL if its available or PHP HTTP wrappers otherwise
	 * 
	 * @access public
	 * @return boolean Whether to actually use cURL (always false if not installed)
	 */	
	public function SetUseCUrl($use_it)
	{
		return ($this->useCurl = ($use_it && function_exists('curl_init')));
	}
	
	// ##################################################
	// API Functions

	// ##################################################
	// Events
	
	public function GetUserEvents($eventid=null)
	{
		if(empty($eventid)){
			return $this->apiCall("events", "GET");
		}else{
			return $this->apiCall("events/$eventid", "GET");
		}
	}
	
	public function GetUserEventsFiles($eventid=null)
	{
		if(!empty($eventid)){
			return $this->apiCall("events/$eventid/files", "GET");
		}else{
			throw new EyeFiException("EventId is necessary");
		}
	}
	
	public function UpdateUserEvents($eventid=null, $name='')
	{
		if(!empty($eventid)){
			return $this->apiCall("events/$eventid", "PUT");
		}else{
			throw new EyeFiException("EventId is necessary");
		}
	}
	// Events
	// ##################################################

	
	
	// ##################################################
	// Albums

	public function GetAlbums($albumId=null)
	{
		if(empty($albumId)){
			return $this->apiCall("albums", "GET");
		}else{
			return $this->apiCall("albums/$albumId", "GET");
		}
	}
	
	public function NewAlbum($name=null)
	{
		if(!empty($name)){
			return $this->apiCall("albums", "POST", compact('name'));
		}else{
			throw new EyeFiException("Album Name is necessary");
		}
	}

	public function UpdateAlbum($albumId=null, $name='', $privacy=0)
	{
		if(!empty($name)){
			return $this->apiCall("albums/$albumId", "PUT", compact('albumId','name','privacy'));
		}else{
			throw new EyeFiException("albumId is necessary");
		}
	}
	
	public function DeleteAlbum($albumId=null)
	{
		if(!empty($albumId)){
			return $this->apiCall("albums/$albumId", "DELETE");
		}else{
			throw new EyeFiException("AlbumId is necessary");
		}
	}
	
	public function AddFilestoAlbum($albumId=null, $files=array())
	{
		if(!empty($albumId)){
			if(count($files)==0){
				throw new EyeFiException("FilesId are necessary");
			}else{
				$fileBasic="[ { 'id': 2028759276 }]";
				return $this->apiCall("albums/$albumId/files", "POST", compact('albumId','fileBasic'));
			}
		}else{
			throw new EyeFiException("AlbumId is necessary");
		}
	}
	
	// Albums
	// ##################################################
	

	// ##################################################
	// Files

	public function GetFiles($fileId=null)
	{
		if(empty($fileId)){
			return $this->apiCall("files", "GET");
		}else{
			return $this->apiCall("files/$fileId", "GET");
		}
	}
	
	public function NewFile($file=null)
	{
		if(!empty($file)){
			return $this->apiCall("files", "POST", compact('file'));
		}else{
			throw new EyeFiException("File is necessary");
		}
	}
	
	public function DeleteFile($fileId=null)
	{
		if(!empty($fileId)){
			return $this->apiCall("files/$fileId", "DELETE");
		}else{
			throw new EyeFiException("FileId is necessary");
		}
	}
	
	public function AddFileTag($fileId=null, $tags=array())
	{
		if(!empty($fileId)){
			if(count($tags)==0){
				throw new EyeFiException("TagsId are necessary");
			}else{
				$TagBasic="";
				foreach($tags as $k=>$v){
					$TagBasic.="{'id': $v, 'name': 'Produce'}";
				}
				//return $TagBasic;
				return $this->apiCall("files/$fileId/tags", "POST", compact('fileId','TagBasic'));
			}
		}else{
			throw new EyeFiException("fileId is necessary");
		}
	}
		
	public function GetFileTags($fileId=null)
	{
		if(!empty($fileId)){
			return $this->apiCall("files/$fileId/tags", "GET");
		}else{
			throw new EyeFiException("FileId is necessary");
		}
	}

	public function RemoveFileTag($fileId=null, $tagId=null)
	{
		if(!empty($fileId)){
			if(!empty($tagId)){
				return $this->apiCall("files/$fileId/tags/$tagId", "DELETE", compact('fileId','tagId'));
			}else{
				throw new EyeFiException("TagId is necessary");
			}
		}else{
			throw new EyeFiException("FileId is necessary");
		}
	
	}
	
	
	public function SearchFile($favorite=null, $edited=null, $raw=null, $in_trash=false, $has_geodata=null, $geo_lat=null, $geo_lon=null,
								$geo_distance=null, $album_ids=null, $event_ids=null, $tag_ids=null, $camera=null, $date_from=null, 
								$date_to=null, $created_from=null, $created_to=null, $page=1, $per_page=10, $sort='date', $order='desc')
	{
	
		return $this->apiCall("search/files", "GET", compact('favorite', 'edited', 'raw', 'in_trash', 'has_geodata', 'geo_lat', 'geo_lon',
																'geo_distance', 'album_ids', 'event_ids', 'tag_ids', 'camera', 'date_from',
																'date_to', 'created_from', 'created_to', 'page', 'per_page', 'sort', 'order'));
	}
	
	// Files
	// ##################################################

	
	
	// ##################################################
	// Tags
	public function GetTags($tagId=null)
	{
		if(empty($tagId)){
			return $this->apiCall("tags", "GET");
		}else{
			return $this->apiCall("tags/$tagId", "GET");
		}
	}
	
	public function UpdateTag($tagId=null, $name='')
	{
		if(!empty($tagId)){
			if(!empty($name)){
				return $this->apiCall("tags/$tagId", "PUT", compact('tagId','name'));
			}else{
				throw new EyeFiException("name is necessary");
			}
		}else{
			throw new EyeFiException("tagId is necessary");
		}
	}
	
	public function DeleteTag($tagId=null)
	{
		if(!empty($tagId)){
			return $this->apiCall("tags/$tagId", "DELETE");
		}else{
			throw new EyeFiException("TagId is necessary");
		}
	}
	// Tags	
	// ##################################################	
	
	function createCurl($url, $http_context)
	{
		$ch = curl_init($url);
		
		$curl_opts = array(
				CURLOPT_HEADER => false, // exclude header from output
				//CURLOPT_MUTE => true, // no output!
				CURLOPT_RETURNTRANSFER => true, // but return!
				CURLOPT_SSL_VERIFYPEER => false,
		);
		
		$curl_opts[CURLOPT_CUSTOMREQUEST] = $http_context['method']; 
		
		if(!empty($http_context['content'])) {
			$curl_opts[CURLOPT_POSTFIELDS] =& $http_context['content'];
			if(defined("CURLOPT_POSTFIELDSIZE"))
				$curl_opts[CURLOPT_POSTFIELDSIZE] = strlen($http_context['content']);
		}
		
		$curl_opts[CURLOPT_HTTPHEADER] = array_map('trim',explode("\n",$http_context['header']));
		
		curl_setopt_array($ch, $curl_opts);
		return $ch;
	}
	
	static private $_curlHeadersRef;
	static function _curlHeaderCallback($ch, $header)
	{
		self::$_curlHeadersRef[] = trim($header);
		return strlen($header);
	}
	
	static function &execCurlAndClose($ch, &$out_response_headers = null)
	{
		if(is_array($out_response_headers)) {
			self::$_curlHeadersRef =& $out_response_headers;
			curl_setopt($ch, CURLOPT_HEADERFUNCTION, array(__CLASS__, '_curlHeaderCallback'));
		}
		$res = curl_exec($ch);
		$err_no = curl_errno($ch);
		$err_str = curl_error($ch);
		curl_close($ch);
		if($err_no || $res === false) {
			throw new EyeFiException("cURL-Error ($err_no): $err_str");
		}
		
		return $res;
	}
	
	private function createRequestContext($url, $method, &$content=null, $oauth_token=-1)
	{
		$method = strtoupper($method);
		$http_context = array('method'=>$method, 'header'=> '');
		
		if(!empty($content)) {
			$post_vars = ($method != "PUT" && preg_match("/^[a-z][a-z0-9_]*=/i", substr($content, 0, 32)));
			$http_context['header'] .= "Content-Length: ".strlen($content)."\r\n";
			$http_context['header'] .= "Content-Type: application/".($post_vars?"x-www-form-urlencoded":"octet-stream")."\r\n";			
			$http_context['content'] =& $content;			

		} elseif($method == "POST") {
			// make sure that content-length is always set when post request (otherwise some wrappers fail!)
			$http_context['content'] = "";
			$http_context['header'] .= "Content-Length: 0\r\n";
		}
		

		// check for query vars in url and add them to oauth parameters (and remove from path)
		$path = $url;
		$query = strrchr($url,'?');
		if(!empty($query)) {
			$path = substr($url, 0, -strlen($query));
		}
		
		$http_context['header'] .= "Authorization: Bearer ".$this->accessToken."\r\n";
		
		return $this->useCurl ? $this->createCurl($url, $http_context) : stream_context_create(array('http'=>$http_context));
	}
	
	private function authCall($path, $request_token=null)
	{
		$url = $this->cleanUrl(self::API_URL.$path);
		$dummy = null;
		$context = $this->createRequestContext($url, "POST", $dummy, $request_token);
		
		$contents = $this->useCurl ? self::execCurlAndClose($context) : file_get_contents($url, false, $context);
		$data = array();
		parse_str($contents, $data);
		return $data;
	}
	
	private static function checkForError($resp)
	{
		if(!empty($resp->error))
			throw new EyeFiException($resp->error);		
		return $resp;
	}
	
	
	private function apiCall($path, $method, $params=array())
	{
		$url = $this->cleanUrl(self::API_URL.$path);
		$content = http_build_query(array_merge(array('locale'=>$this->locale), $params),'','&');
		
		if($method == "GET" || $method == "PUT") {
			$url .= "?".$content;
			$content = null;
		}
		
		$context = $this->createRequestContext($url, $method, $content);
		$json = $this->useCurl ? self::execCurlAndClose($context) : file_get_contents($url, false, $context);
		//if($json === false)
//			throw new EyeFiException();
		$resp = json_decode($json);
		return self::checkForError($resp);		
	}
	

	function cleanUrl($url) {
		$p = substr($url,0,8);
		$url = str_replace('//','/', str_replace('\\','/',substr($url,8)));
		$url = rawurlencode($url);
		$url = str_replace('%2F', '/', $url);
		return $p.$url;
	}
}

class EyeFiException extends Exception {
	
	public function __construct($err = null, $isDebug = FALSE) 
	{
		if(is_null($err)) {
			$el = error_get_last();
			$this->message = $el['message'];
			$this->file = $el['file'];
			$this->line = $el['line'];
		} else
			$this->message = $err;
		self::log_error($err);
		if ($isDebug)
		{
			self::display_error($err, TRUE);
		}
	}
	
	public static function log_error($err)
	{
		error_log($err, 0);		
	}
	
	public static function display_error($err, $kill = FALSE)
	{
		print_r($err);
		if ($kill === FALSE)
		{
			die();
		}
	}
}
