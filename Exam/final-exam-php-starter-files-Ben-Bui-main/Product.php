<?php
require_once("UpcDatabase.php");

 class Product
{

	private $description;
	private $upc;

	public function __construct($upc = "") {
		$this->upc = $upc;
		if ($upc != "") {
			$this->fetch();
		}
	}

   public function setDescription($descr) {$this->description = $descr;}
   public function getDescription() {return $this->description;}

   public function setUpc($upc) {$this->upc = $upc;}
   public function getUpc() {return $this->upc;}

	private function fetch()  {
      $params = array();
      $params["upc"]= $this->upc;     
      $db = new UpcDatabase();
      $this->description = $db->getValue("Select description from upc where upccode= :upc", $params);
      $db->close();
	}
}