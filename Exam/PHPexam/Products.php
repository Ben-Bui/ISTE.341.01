<?php
require_once("UpcDatabase.php");

 class Products
{
	
	public function getCount() {  
      $db = new UpcDatabase();
      $cnt = $db->getValue("Select count(*) from upc", null);
      $db->close();
	  return intval($cnt);
	}
	
	public function getUpcs($partialDescription) {
      $params = array();
      $params["desc"] = $partialDescription;
      $db = new UpcDatabase();
      $results = 
         $db->get("Select upccode from upc where description like :desc", $params);
      $db->close();
      
      $retval = array();
      foreach ($results as $row) {
         $retval[]=$row[0];
      }
      return $retval;
	}
}