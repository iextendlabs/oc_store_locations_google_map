<?php
class ControllerExtensionModuleStoreLocations extends Controller {

	public function index() {

		$this->load->language('extension/module/store_map');
		
		$this->load->model('extension/store_map');

		$this->document->setTitle($this->language->get('text_stores'));

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_stores'),
			'href' => $this->url->link('extension/module/store_locations', '', true)
		);
		
		$results = $this->model_extension_store_map->getStoreLocations();

		foreach ($results as $key => $result) {

			$data['stores'][$key] = array(
				'name' 		  	=> $result['name'],
				'description' 	=> $result['description'],
				'latitude' 		=> $result['latitude'],
				'longitude' 	=> $result['longitude'],
				'geocode'       => implode(',' , (array($result['latitude'], $result['longitude']))),
				'geocode_hl'    => $this->config->get('config_language'),
			);

		}

		// google map API KEY
		if($this->config->get('module_store_map_api_key')){
			$data['map_api_key'] = $this->config->get('module_store_map_api_key');
		}else{
			$data['map_api_key'] = '';
		}

		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('extension/module/store_locations', $data));
	}

}