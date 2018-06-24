<?php

namespace App\Models;
use Joomla\Input\Input;
use Joomla\Model\AbstractDatabaseModel;
use Joomla\Database\DatabaseDriver;
use Joomla\Application\Joomla\Application;
use Joomla\Session\Session;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use App\Models\ProductsModel;
use App\Models\ShoppingcartdetailsModel;


class DefaultModel extends AbstractDatabaseModel
{
	protected $input;
	protected $app;
	protected $session;
	protected $user;
	protected $limitstart   = 0;
	protected $limit        = 100;
	public function __construct(Input $input, DatabaseDriver $db)
	{
		parent::__construct($db);
		$this->session = new Session();
		$this->input = $input;
	}

	
	public function insert($table, $columns, $data)
	{
		if($this->input->getMethod()=='POST'){
			$query = $this->db->getQuery(true)
				->insert($this->db->qn($table))
				->columns($this->db->qn($columns))
				->values(implode(",",$this->db->q($data)));
			$this->db->setQuery($query);
			$result = $this->db->execute();
			if($result){
				$query = $this->db->getQuery(true)
					->select('LAST_INSERT_ID() as id')
					->from($table);
				$this->db->setQuery($query);
				$result = $this->db->loadObject();
			}
			$result->success = true;
			return $result;
		}else{
			$result = new \stdClass();
			$result->success = false;
			return false;
		}
			
	}
	
	public function update($table, $fields, $conditions)
	{
		if($this->input->getMethod()=='POST'){
			$query = $this->db->getQuery(true)
			->update($this->db->qn($table))
			->set($fields)
			->where($conditions);
			$this->db->setQuery($query);
			$result = $this->db->execute();
			return $result;
		}else{
			$result = new \stdClass();
			$result->success = false;
			return false;
		}
	}
	
	public function validate_user_email($user_email)
	{
		$query = $this->db->getQuery(true)
			->select('u.id, u.last_name, u.first_name, u.email, u.password')
			->from('#__ddc_users as u')
			->where('(u.email=' . $this->db->quote($user_email). ') And (activation = 0) And (block = 0)');
		$this->db->setQuery($query);
		$result = $this->db->loadObject();
		
		return $result;
		 
	}
	
	public function getItemById($id1 = null, $id2 = null, $id3 = null, $id4 = null)
	{
		$query = $this->db->getQuery(true);
			
		$query = $this->_buildQuery();
		$this->_buildWhere($query, $id1, $id2, $id3, $id4);
		$this->db->setQuery($query);
		return $this->db->setQuery($query)->loadObject();
	}
	
	public function getItemByAlias($alias)
	{
		$query = $this->db->getQuery(true);
			
		$query = $this->_buildQuery();
		$this->_buildWhere($query, $alias);
		$this->db->setQuery($query);

		return $this->db->setQuery($query)->loadObject();
	}
	
	public function listItems($id1 = null, $id2 = null, $id3 = null, $id4 = null)
  	{
  		$query = $this->db->getQuery(true);
  		$query = $this->_buildQuery();
  		$this->_buildWhere($query, $id1, $id2, $id3, $id4);
  
  		return $this->db->setQuery($query, $this->limitstart, $this->limit)->loadObjectList();
 	}
  
  	protected function _getList($query, $limitstart = 0, $limit = 0)
  	{
  		$result = $this->db->setQuery($query, $limitstart, $limit)->loadObjectList();
  
  		return $result;
  	}
  	
  	
  	public function getDistance($lat1, $lon1, $lat2, $lon2, $unit) {
  	
  		$theta = $lon1 - $lon2;
  		$dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
  		$dist = acos($dist);
  		$dist = rad2deg($dist);
  		$miles = $dist * 60 * 1.1515;
  		$unit = strtoupper($unit);
  	
  		if ($unit == "K") {
  			return ($miles * 1.609344);
  		} else if ($unit == "N") {
  			return ($miles * 0.8684);
  		} else {
  			return $miles;
  		}
  	}
  	
  	public function getProductPrice($id=null)
  	{
  		$model = new ProductsModel($this->input, $this->db);
  		$item = $model->getItemById((int)$id);
  	
  		$unitPrice = $item->product_price;
  		$weight = $item->product_weight;
  		$priceWeightBased = $model->getpartjsonfield($item->product_params,'price_weight_based');
  		$weightUOM = $item->product_weight_uom;
  		if($priceWeightBased == 1)
  		{
  			if($weightUOM=='grams')
  			{
  				$factor = $weight/1000;
  				$unitPrice = $item->product_price*$factor;
  			}
  			if($weightUOM=='kg')
  			{
  				$factor = $weight/1;
  				$unitPrice = $item->product_price*$factor;
  			}
  			if($weightUOM=='ounce')
  			{
  				$factor = $weight/35.27396;
  				$unitPrice = $item->product_price*$factor;
  			}
  		}
  		return $unitPrice;
  	}
  	public function getpartjsonfield($string,$part)
  	{
  		$prod_params = json_decode($string, true);
  		$item = (string)$prod_params[$part];
  		return $item;
  	}
  	public function randStrGen($len){
  		$result = "";
  		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
  		$charArray = str_split($chars);
  		for($i = 0; $i < $len; $i++){
  			$randItem = array_rand($charArray);
  			$result .= "".$charArray[$randItem];
  		}
  		return $result;
  	}
  	
  	public function getDelDates($sh_id = null)
  	{
  		$model = new ShoppingcartdetailsModel($this->input, $this->db);
  		$date = date('w');
  		$stdprice = "3.99";
  		$nxdprice = "7.49";
  		$tmdprice = "8.99";
  		$today = strtotime('today');
  		if($date == 6)
  		{
  			$day1 = 60*60*24*2;
  			$day2 = 60*60*24*3;
  			$day3 = 60*60*24*4;
  			$day4 = 60*60*24*5;
  			$day5 = 60*60*24*6;
  		}
  		elseif($date == 5)
  		{
  			$day1 = 60*60*24*1;
  			$day2 = 60*60*24*3;
  			$day3 = 60*60*24*4;
  			$day4 = 60*60*24*5;
  			$day5 = 60*60*24*6;
  		}
  		elseif($date == 4)
  		{
  			$day1 = 60*60*24*1;
  			$day2 = 60*60*24*2;
  			$day3 = 60*60*24*4;
  			$day4 = 60*60*24*5;
  			$day5 = 60*60*24*6;
  		}
  		elseif($date == 3)
  		{
  			$day1 = 60*60*24*1;
  			$day2 = 60*60*24*2;
  			$day3 = 60*60*24*3;
  			$day4 = 60*60*24*5;
  			$day5 = 60*60*24*6;
  		}
  		elseif($date == 2)
  		{
  			$day1 = 60*60*24*1;
  			$day2 = 60*60*24*2;
  			$day3 = 60*60*24*3;
  			$day4 = 60*60*24*4;
  			$day5 = 60*60*24*6;
  		}
  		else
  		{
  			$day1 = 60*60*24*1;
  			$day2 = 60*60*24*2;
  			$day3 = 60*60*24*3;
  			$day4 = 60*60*24*4;
  			$day5 = 60*60*24*5;
  		}
  		$del_date = Date('D. d M. Y',$day3+$today);
  		$day1_date = Date('Y-m-d',$day1+$today);
  		$day2_date = Date('Y-m-d',$day2+$today);
  		$day3_date = Date('Y-m-d',$day3+$today);
  		$day4_date = Date('Y-m-d',$day4+$today);
  		$day5_date = Date('Y-m-d',$day5+$today);
  		$deldates = array("day"=>$date,"standard"=>$day3_date,"stdprice"=>$stdprice,"nextday"=>$day1_date,"nxdprice"=>$nxdprice,"timed"=>array("days"=>array($day1_date,$day2_date,$day3_date,$day4_date,$day5_date),"times"=>array("08:00 - 10:00","10:00 - 12:00","12:00 - 14:00","14:00 - 16:00","16:00 - 18:00","18:00 - 20:00")),"tmdprice"=>$tmdprice);
  		
  		return $deldates;
	}
	
	public function setLocation($postcode)
	{
		if($postcode==null){
			$item = new \stdClass();
			$item->msg = 'Sorry, the location cannot be found';
			$item->searchterm = $postcode;
			return $item;
		}
		$query = $this->db->getQuery(TRUE);
		$query->select('o.town, o.postcode')
			->from('#__ddc_outcodes as o');
		if($this->isValidPostCodeFormat($postcode)):
			$query->where('(o.postcode LIKE "%'.$this->getDistrict($postcode).'%")');
		else:
			$query->where('(o.town LIKE "%'.$postcode.'%")');
		endif;
		$this->db->setQuery($query);
		if($item = $this->db->loadObject()){
			$item->msg = 'Location updated';
		}
		else{
			$item = new \stdClass();
			$item->msg = 'Sorry, the location cannot be found';
		}
		$item->searchterm = $postcode;
		$ip = $_SERVER['REMOTE_ADDR'];
		$now = date('Y-m-d H:i:s');
		//write log file
		$file = JPATH_ROOT.'/media/com_ddcshopbox/postcodelogger.txt';
		$txt = (string)$now."; ".$postcode."; ".$ip;
		$myfile = file_put_contents($file, $txt.PHP_EOL , FILE_APPEND | LOCK_EX);
		return $item;
	}

	/**
   * Calculates the great-circle distance between two points, with
   * the Haversine formula.
   * @param float $latitudeFrom Latitude of start point in [deg decimal]
   * @param float $longitudeFrom Longitude of start point in [deg decimal]
   * @param float $latitudeTo Latitude of target point in [deg decimal]
   * @param float $longitudeTo Longitude of target point in [deg decimal]
   * @param float $earthRadius Mean earth radius in [m]
   * @return float Distance between points in [m] (same as earthRadius)
   */
  public function haversineGreatCircleDistance($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 6371000)
  {
  	// convert from degrees to radians
  	$latFrom = deg2rad($latitudeFrom);
  	$lonFrom = deg2rad($longitudeFrom);
  	$latTo = deg2rad($latitudeTo);
  	$lonTo = deg2rad($longitudeTo);
  
  	$latDelta = $latTo - $latFrom;
  	$lonDelta = $lonTo - $lonFrom;
  
  	$angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) + cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
  	return $angle * $earthRadius;
  }
  
  public function isValidPostCodeFormat($postcode){
  
  	// return whether the postcode is in a valid format
  	return preg_match('/^\s*(([A-Z]{1,2})[0-9][0-9A-Z]?)\s*(([0-9])[A-Z]{2})\s*$/', strtoupper($postcode));
  
  }

  /* Returns the district for a postcode - for example, SW1A for SW1A 0AA - or
   * false if the postcode was not in a valid format. The parameter is:
   *
   * $postcode - the postcode whose district should be returned
   */
  	public function getDistrict($postcode){
  
		// parse the postcode and return the district
		$parts = self::parse($postcode);
		return (count($parts) > 0 ? $parts[1] : false);

	}

	/* Parses a postcode and returns an array with the following components:
   *
   * 1 - the outward code
   * 2 - the area from the outward code
   * 3 - the inward code
   * 4 - the sector from the inward code
   *
   * The parameter is:
   *
   * $postcode - the postcode to parse
   */
  	private static function parse($postcode){
		// parse the postcode and return the result
		preg_match('/^\s*(([A-Z]{1,2})[0-9][0-9A-Z]?)\s*(([0-9])[A-Z]{2})\s*$/', strtoupper($postcode), $matches);
		return $matches;
	}

  public function savePostcodes(){
	$file = fopen(JPATH_ROOT."/media/upload/postcodes.csv",'r');
	$line = fgets($file);
	$headers = explode(',',trim($line));
	$table = '#__ddc_outcodes';
	for($i=0;$i<12000;$i++) {
		if(!feof($file)){
			$line = fgetcsv($file);
			if(count($line)>1){
				$this->insert($table, $headers, $line);
			}
		}
		else{
			break;
		}
	}
	fclose($file);
	return $headers;
  }

  	public function sendEmail($mailto, $toname='', $subject, $body, $mailfrom='admin@ushbub.co.uk', $fromname='Ushbub')
	{
		
		//Create a new PHPMailer instance
		$mail = new PHPMailer;
		//Tell PHPMailer to use SMTP
		$mail->isSMTP();
		//Enable SMTP debugging
		// 0 = off (for production use)
		// 1 = client messages
		// 2 = client and server messages
		$mail->SMTPDebug = 0;
		//Set the hostname of the mail server
		$mail->Host = '185.182.58.19';
		//Set the SMTP port number - likely to be 25, 465 or 587
		$mail->Port = 25;
		//Set the encryption system to use - ssl (deprecated) or tls
		//$mail->SMTPSecure = 'tls';
		$mail->SMTPAuth = true;
		//$mail->SMTPSecure = false;
		//Username to use for SMTP authentication
		$mail->Username = 'darryau63';
		//Password to use for SMTP authentication
		$mail->Password = 'khvex0f8';
		//Set who the message is to be sent from
		$mail->setFrom($mailfrom,$fromname);
		//Set an alternative reply-to address
		//$mail->addReplyTo('replyto@example.com', 'First Last');
		//Set who the message is to be sent to
		$mail->addAddress($mailto, $toname);
		//Set the subject line
		$mail->Subject = $subject;
		//Read an HTML message body from an external file, convert referenced images to embedded,
		//convert HTML into a basic plain-text alternative body
		$mail->msgHTML($body);
		//Replace the plain text body with one created manually
		//$mail->AltBody = 'This is a plain-text message body';
		//Attach an image file
		//$mail->addAttachment('images/phpmailer_mini.png');
		//send the message, check for errors
		if (!$mail->send()) {
			$result = array("success" => false, "msg" => "Mail not sent", "error" => $mail->ErrorInfo);
		} else {
			$result = array("success" => true, "msg" => "Mail sent");
		};
		return $result;
	}

}