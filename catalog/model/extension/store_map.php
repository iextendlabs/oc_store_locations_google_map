<?php
class ModelExtensionStoreMap extends Model {
	public function getStoreLocations() {

		return $this->db->query("SELECT * FROM ".DB_PREFIX."store_locations")->rows;

	}
}