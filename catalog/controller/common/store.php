<?php
class ControllerCommonStore extends Controller {
	public function index() {
		$this->load->language('common/language');

		$data['action'] = $this->url->link('common/store/store', '', isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1')));

		if (isset($this->session->data['store_id'])) {
			$data['code'] = $this->session->data['store_id'];
		} else {
			$data['code'] = $this->config->get('config_store_id');
		}

		$this->load->model('setting/store');

		$data['stores'] = array();
		$city = $this->db->query("select * from " . DB_PREFIX . "setting where store_id = '0' and  `key` = 'config_zone_id' ");
		$default_city = $this->db->query("select * from " . DB_PREFIX . "zone where zone_id = ".(int) $city->row['value']." ");

		$data['stores'][] = array(
				'store_id' => 0,
				'name'     => $default_city->row['name'],
				'url'      => HTTP_SERVER.substr( $_SERVER['REQUEST_URI'], 1)
		);

		$results = $this->model_setting_store->getStores();

		foreach ($results as $result) {
			$city = $this->db->query("select * from " . DB_PREFIX . "setting where store_id = '".(int) $result['store_id']."' and  `key` = 'config_zone_id' ");
			$city = $this->db->query("select * from " . DB_PREFIX . "zone where zone_id = ".(int) $city->row['value']." ");

			$data['stores'][] = array(
				'store_id' => $result['store_id'],
				'url' => $result['url'].substr( $_SERVER['REQUEST_URI'], 1),
				'name' => $city->row['name']
				);
		}

		if (!isset($this->request->get['route'])) {
			$data['redirect'] = $this->url->link('common/home');
		} else {
			$url_data = $this->request->get;
			$route = $url_data['route'];
			unset($url_data['route']);
			$url = '';
			if ($url_data) {
				$url = '&' . urldecode(http_build_query($url_data, '', '&'));
			}

	    $old_config_city = $this->config->get('config_city');

        if (isset($this->session->data['store_id'])) {
	        $old_store_id = $this->session->data['store_id'];
	        $this->session->data['store_id'] = 0;
	        $this->config->set('config_city','');
        } else {
	        $old_store_id = false;
        }

		$data['redirect'] = $this->url->link($route, $url, isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1')));


		if ($old_store_id) {
	        $this->session->data['store_id'] = $old_store_id;
        }


        $this->config->set('config_city', $old_config_city);
		}

		return $this->load->view('common/store', $data);
	}

	public function store() {

		if (isset($this->request->post['code'])) {
			if ($this->request->post['code'] == 0) {
				unset($this->session->data['store_id']);
				unset($this->session->data['store_city']);
			} else {
				$this->session->data['store_id'] = $this->request->post['code'];
			}
		}

		if (isset($this->request->post['redirect'])) {
			$this->response->redirect($this->request->post['redirect']);
		} else {
			$this->response->redirect($this->url->link('common/home'));
		}
	}
}
