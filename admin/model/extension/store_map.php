<?php
class ModelExtensionStoreMap extends Model {
	public function getStoreLocations() {

		return $this->db->query("SELECT * FROM ".DB_PREFIX."store_locations")->rows;

	}

	public function getStoreLocation($store_id) {

		return $this->db->query("SELECT * FROM ".DB_PREFIX."store_locations WHERE id = ".$store_id."")->row;

	}

	public function saveStoreLocations($data = array()){

		$existing_location = $this->db->query("SELECT * FROM ".DB_PREFIX."store_locations WHERE latitude = ".$data['latitude']." AND longitude = ".$data['longitude']." ");

		// // insert if not exist already
		if(!$existing_location->num_rows){
			$this->db->query("INSERT INTO ".DB_PREFIX."store_locations SET name = '".$data['name']."', description = '".$this->db->escape($data['description'])."', latitude = '".$this->db->escape($data['latitude'])."', longitude = '".$this->db->escape($data['longitude'])."' ");
		}
	}

	public function updateStoreLocations($data = array(), $id){
		
		$this->db->query("UPDATE ".DB_PREFIX."store_locations SET name = '".$data['name']."', description = '".$this->db->escape($data['description'])."', latitude = '".$this->db->escape($data['latitude'])."', longitude = '".$this->db->escape($data['longitude'])."' WHERE id = ".$id." ");
	}

	public function deleteStoreLocations($id = 0 ) {

		$this->db->query("DELETE FROM ".DB_PREFIX."store_locations WHERE id = ".$id."");

	}
}