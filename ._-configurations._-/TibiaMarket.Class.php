<?php 

class TibiaMarket{
	private $db;

	public function __construct($database) {
		$this->db = $database;
	}

	public function date(){
		return date('Y-m-d');
	}

	public function time(){
		return date('H:i:s');
	}

	public function dateTime(){
		return date('Y-m-d H:i:s');
	}

	public function userIP(){
		return $_SERVER['REMOTE_ADDR'];	
	}

	public function hashPW($pw){
		return password_hash($pw, PASSWORD_BCRYPT, array("cost" => 11));
	}

	public function hashVerify($pw, $hash){
		return password_verify($pw, $hash);
	}

	public function token(){
		return md5(uniqid('auth', true));
	}
	
	public function addLog($log){
		$query = $this->db->prepare("INSERT INTO `logs` (`id`, `log`) VALUES(NULL, ?)");
		$query->BindValue(1, $log);
		$query->execute();	
	}
	
	public function sessionName(){
		return "tibiamarketnet";
	}

	public function isLogged(){
		$sess = $this->getSession();
		if(!$sess){
			return false;
		}else{
			$query = $this->db->prepare("SELECT * FROM `users` WHERE `lghash` = ?");
			$query->BindValue(1, $sess);
			$query->execute();
	
			if($query->rowCount() == 1){
				return true;
			}else{
				return false;
			}
		}
	}
	public function addhttp($url) {
		if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
			$url = "http://" . $url;
		}
		return $url;
	}

	public function setSession($hash, $check){
		if($check == "1"){
			session_destroy();
			ini_set('session.cookie_lifetime', 86400);
			ini_set('session.gc_maxlifetime', 86400);
			session_start();
			$_SESSION[$this->sessionName()] = $hash;
		}else{
			$_SESSION[$this->sessionName()] = $hash;
			
		}
		return true;
	}

	public function getSession(){
		if(isset($_SESSION[$this->sessionName()])){
			if(empty($_SESSION[$this->sessionName()])){
				return false;
			}else{
				return $_SESSION[$this->sessionName()];
			}			
		}else{
			return false;
		}
	}


	public function userData(){
		$sess = $this->getSession();
		$query = $this->db->prepare("SELECT * FROM `users` WHERE `lghash` = ?");
		$query->BindValue(1, $sess);
		$query->execute();

		return $query->fetch(PDO::FETCH_ASSOC);
	}

	public function userTable($uid, $table){
		$query = $this->db->prepare("SELECT * FROM `users` WHERE `id` = ?");
		$query->BindValue(1, $uid);
		$query->execute();
		$qf = $query->fetch(PDO::FETCH_ASSOC);
		return $qf[$table];
	}

	public function itemTable($id, $table){
		$query = $this->db->prepare("SELECT * FROM `items` WHERE `id` = ?");
		$query->BindValue(1, $id);
		$query->execute();
		$qf = $query->fetch(PDO::FETCH_ASSOC);
		return $qf[$table];
	}
	public function itemImageTable($id, $table){
		$query = $this->db->prepare("SELECT * FROM `items` WHERE `id` = ?");
		$query->BindValue(1, $id);
		$query->execute();
		$qf = $query->fetch(PDO::FETCH_ASSOC);
		return $qf[$table];
	}

	public function worldTable($id, $table){
		$query = $this->db->prepare("SELECT * FROM `worlds` WHERE `id` = ?");
		$query->BindValue(1, $id);
		$query->execute();
		$qf = $query->fetch(PDO::FETCH_ASSOC);
		return $qf[$table];
	}
	
	public function newsTable($author){
		$query = $this->db->prepare("SELECT * FROM `news` WHERE `author` = ? ORDER BY ABS(DATEDIFF('timestamp', NOW())) LIMIT 5");
		$query->BindValue(1, $author);
		$query->execute();
		$result = $query->fetch(PDO::FETCH_ASSOC);
		return $result;
	}

	public function characterTable($id, $table){
		$query = $this->db->prepare("SELECT * FROM `characters` WHERE `id` = ?");
		$query->BindValue(1, $id);
		$query->execute();
		$qf = $query->fetch(PDO::FETCH_ASSOC);
		return $qf[$table];
	}

	public function auctionTable($id, $table){
		$query = $this->db->prepare("SELECT * FROM `auctions` WHERE `id` = ?");
		$query->BindValue(1, $id);
		$query->execute();
		$qf = $query->fetch(PDO::FETCH_ASSOC);
		return $qf[$table];
	}
	
	public function getAvgItemPrice($id){
		$query = $this->db->prepare("SELECT * FROM `auctions` WHERE `item` = ? && `status` >= ?");
		$query->BindValue(1, $id);
		$query->BindValue(2, 1);
		$query->execute();
		
		$totals = array();
		if($query->rowCount() > 0){
			while($result = $query->fetch(PDO::FETCH_ASSOC)){
				$getLastBid = $odb->prepare("SELECT * FROM `bids` WHERE `auctionid` = ? ORDER BY `id` DESC LIMIT 1");
				$getLastBid->BindValue(1, $result['id']);
				$getLastBid->execute();
				
				$totals[] = $getLastBid['bid'];
			}
			$average = array_sum($totals)/count($totals);
			return $average;
		}else{
			return "Not enough information.";
		}
	}
	
	public function getTotAucforItem($id){
		$query = $this->db->prepare("SELECT * FROM `auctions` WHERE `item` = ? && `status` = ?");
		$query->BindValue(1, $id);
		$query->BindValue(2, '0');
		$query->execute();
		
		return $query->rowCount();
	}

	public function redirect($url, $written){
		if($written){
			die('<meta http-equiv="Refresh" content="0.1; url=' . $url . '"">');
		}else{
			Header("Location:" . $url);
			exit();
		}
	}
	

	public function email_exists($email){
		$query = $this->db->prepare("SELECT * FROM `users` WHERE `email` = ?");
		$query->BindValue(1, $email);
		$query->execute();
		$count = $query->rowCount();

		if($count > 0){
			return true;
		}else{
			return false;
		}
	}

	public function username_exists($email){
		$query = $this->db->prepare("SELECT * FROM `users` WHERE `username` = ?");
		$query->BindValue(1, $email);
		$query->execute();
		$count = $query->rowCount();

		if($count > 0){
			return true;
		}else{
			return false;
		}
	}

	public function userIDByUsername($username){
		$query = $this->db->prepare("SELECT * FROM `users` WHERE `username` = ?");
		$query->BindValue(1, $username);
		$query->execute();
		$qf = $query->fetch(PDO::FETCH_ASSOC);
		return $qf['id'];
	}

	public function characterPage($name){
		$namereplace = str_replace(" ", "+", $name);
		$url = "https://secure.tibia.com/community/?subtopic=characters&name=" . $namereplace;
		return $this->downloadString($url);
	}

	public function downloadString($url){
		// last version of chrome
		$userAgent = "Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2228.0 Safari/537.36";
		
		$curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_TIMEOUT, 5);
        curl_setopt($curl, CURLOPT_USERAGENT, $userAgent); 
        $response = curl_exec($curl);
        curl_close($curl);

        return $response;
	}
	
}