<?php
class ControllerExtensionModuleStoreMap extends Controller {
	private $error = array();

	public function install()
	{

		$this->db->query("CREATE TABLE IF NOT EXISTS `".DB_PREFIX."store_locations` (
          	`id` int(11) NOT NULL AUTO_INCREMENT,
		  	`name` varchar(255) NOT NULL,
		  	`description` varchar(255) NOT NULL,
		  	`latitude` varchar(20) NOT NULL,
		  	`longitude` varchar(20) NOT NULL,
		  	PRIMARY KEY (`id`)
        )");

	}

	public function index() {

		$this->install();

		$this->load->language('extension/module/store_map');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		$this->load->model('extension/store_map');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {

			$this->model_setting_setting->editSetting('module_store_map', $this->request->post);
			
			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/module/store_map', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['action'] = $this->url->link('extension/module/store_map', 'user_token=' . $this->session->data['user_token'], true);

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);

		if (isset($this->request->post['module_store_map_status'])) {
			$data['module_store_map_status'] = $this->request->post['module_store_map_status'];
		} else {
			$data['module_store_map_status'] = $this->config->get('module_store_map_status');
		}

		if (isset($this->request->post['module_store_map_api_key'])) {
			$data['map_api_key'] = $this->request->post['module_store_map_api_key'];
		} else {
			$data['map_api_key'] = $this->config->get('module_store_map_api_key');
		}


		$results = $this->model_extension_store_map->getStoreLocations();

		foreach ($results as $key => $result) {

			$data['stores'][$key] = array(
				'id' 			=> $result['id'],
				'name' 		  	=> $result['name'],
				'description' 	=> $result['description'],
				'latitude' 		=> $result['latitude'],
				'longitude' 	=> $result['longitude']
			);

		}

		if(isset($this->request->get['id'])){

			$data['store'] = $this->model_extension_store_map->getStoreLocation($this->request->get['id']);

		}

		$data['add_location_url'] = $this->url->link('extension/module/store_map/save', 'user_token=' . $this->session->data['user_token'], true);

		$data['edit_location_url'] = $this->url->link('extension/module/store_map', 'user_token=' . $this->session->data['user_token'], true);

		$data['update_location_url'] = $this->url->link('extension/module/store_map/update', 'user_token=' . $this->session->data['user_token'], true);

		$data['delete_location_url'] = $this->url->link('extension/module/store_map/delete', 'user_token=' . $this->session->data['user_token'], true);


		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/store_map', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/store_map')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

	public function save()
	{
		$this->load->model('extension/store_map');

		$this->model_extension_store_map->saveStoreLocations($this->request->post);

		$this->response->redirect($this->url->link('extension/module/store_map', 'user_token=' . $this->session->data['user_token'], true));
	}


	public function update()
	{
		$id = $this->request->get['id'];

		$this->load->model('extension/store_map');

		$this->model_extension_store_map->updateStoreLocations($this->request->post, $id);

		$this->response->redirect($this->url->link('extension/module/store_map', 'user_token=' . $this->session->data['user_token'], true));
	}

	public function delete()
	{
		$id = $this->request->get['id'];

		$this->load->model('extension/store_map');

		$this->model_extension_store_map->deleteStoreLocations($id);

		$this->response->redirect($this->url->link('extension/module/store_map', 'user_token=' . $this->session->data['user_token'], true));
	}
}