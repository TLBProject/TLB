<?php
/**
 * This class defines the global configuration to be used in the entire software
 * All the elements of this class are either private or protected
 * @category Global Configuration
 * @author Abhishek Sharma
 * @license Central Library - MNNIT Allahabad
 * @version 1.0.0
 */

class tlbConfig{
	var $host, $mysql_user, $mysql_passwd, $mysql_dbName;
	public $baseServer;
	
	public function __construct() {
		$this->host = "localhost";
		$this->mysql_user = "root";
		$this->mysql_passwd ="";
		//cAn1U2d03lT
		$this->mysql_dbName = "tlbnew";
		$this->baseServer = "/tlb/";
		date_default_timezone_set('Asia/Kolkata');
	}
}
?>