<?php
class ControllerSaleOrder extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('sale/order');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('sale/order');

		$this->getList();
	}

	public function add() {
		$this->load->language('sale/order');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('sale/order');

		$this->getForm();
	}

	public function edit() {
		$this->load->language('sale/order');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('sale/order');

		$this->getForm();
	}
	
	public function delete() {
		$this->load->language('sale/order');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->session->data['success'] = $this->language->get('text_success');

		$url = '';

		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}

		if (isset($this->request->get['filter_customer'])) {
			$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_order_status'])) {
			$url .= '&filter_order_status=' . $this->request->get['filter_order_status'];
		}
	
		if (isset($this->request->get['filter_order_status_id'])) {
			$url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
		}
			
		if (isset($this->request->get['filter_total'])) {
			$url .= '&filter_total=' . $this->request->get['filter_total'];
		}

		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}

		if (isset($this->request->get['filter_date_modified'])) {
			$url .= '&filter_date_modified=' . $this->request->get['filter_date_modified'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$this->response->redirect($this->url->link('sale/order', 'user_token=' . $this->session->data['user_token'] . $url, true));
	}
			
	protected function getList() {
		if (isset($this->request->get['filter_order_id'])) {
			$filter_order_id = $this->request->get['filter_order_id'];
		} else {
			$filter_order_id = '';
		}

		if (isset($this->request->get['filter_customer'])) {
			$filter_customer = $this->request->get['filter_customer'];
		} else {
			$filter_customer = '';
		}

		if (isset($this->request->get['filter_order_status'])) {
			$filter_order_status = $this->request->get['filter_order_status'];
		} else {
			$filter_order_status = '';
		}
		
		if (isset($this->request->get['filter_order_status_id'])) {
			$filter_order_status_id = $this->request->get['filter_order_status_id'];
		} else {
			$filter_order_status_id = '';
		}
		
		if (isset($this->request->get['filter_total'])) {
			$filter_total = $this->request->get['filter_total'];
		} else {
			$filter_total = '';
		}

		if (isset($this->request->get['filter_date_added'])) {
			$filter_date_added = $this->request->get['filter_date_added'];
		} else {
			$filter_date_added = '';
		}

		if (isset($this->request->get['filter_date_modified'])) {
			$filter_date_modified = $this->request->get['filter_date_modified'];
		} else {
			$filter_date_modified = '';
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'o.order_id';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'DESC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}

		if (isset($this->request->get['filter_customer'])) {
			$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_order_status'])) {
			$url .= '&filter_order_status=' . $this->request->get['filter_order_status'];
		}
	
		if (isset($this->request->get['filter_order_status_id'])) {
			$url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
		}
			
		if (isset($this->request->get['filter_total'])) {
			$url .= '&filter_total=' . $this->request->get['filter_total'];
		}

		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}

		if (isset($this->request->get['filter_date_modified'])) {
			$url .= '&filter_date_modified=' . $this->request->get['filter_date_modified'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('sale/order', 'user_token=' . $this->session->data['user_token'] . $url, true)
		);

		$data['invoice'] = $this->url->link('sale/order/invoice', 'user_token=' . $this->session->data['user_token'], true);
		$data['shipping'] = $this->url->link('sale/order/shipping', 'user_token=' . $this->session->data['user_token'], true);
		$data['add'] = $this->url->link('sale/order/add', 'user_token=' . $this->session->data['user_token'] . $url, true);
		$data['delete'] = str_replace('&amp;', '&', $this->url->link('sale/order/delete', 'user_token=' . $this->session->data['user_token'] . $url, true));

		$data['orders'] = array();

		$filter_data = array(
			'filter_order_id'        => $filter_order_id,
			'filter_customer'	     => $filter_customer,
			'filter_order_status'    => $filter_order_status,
			'filter_order_status_id' => $filter_order_status_id,
			'filter_total'           => $filter_total,
			'filter_date_added'      => $filter_date_added,
			'filter_date_modified'   => $filter_date_modified,
			'sort'                   => $sort,
			'order'                  => $order,
			'start'                  => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                  => $this->config->get('config_limit_admin')
		);

		$order_total = $this->model_sale_order->getTotalOrders($filter_data);

		$results = $this->model_sale_order->getOrders($filter_data);


		/* dpd */
		
		$this->load->model('extension/shipping/dpd');
		
		/* dpd */

		

		foreach ($results as $result) {
			$data['orders'][] = array(
				'order_id'      => $result['order_id'],


		/* dpd */
		
		
		'dpd_id'      => !empty($dpd = $this->model_extension_shipping_dpd->getOrderDpdID($result['order_id'])) ? $dpd['dpd_id'] : '',
		
		/* dpd */

		
				'customer'      => $result['customer'],
				'order_status'  => $result['order_status'] ? $result['order_status'] : $this->language->get('text_missing'),
				'total'         => $this->currency->format($result['total'], $result['currency_code'], $result['currency_value']),
				'date_added'    => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'date_modified' => date($this->language->get('date_format_short'), strtotime($result['date_modified'])),
				'shipping_code' => $result['shipping_code'],
				'view'          => $this->url->link('sale/order/info', 'user_token=' . $this->session->data['user_token'] . '&order_id=' . $result['order_id'] . $url, true),
				'edit'          => $this->url->link('sale/order/edit', 'user_token=' . $this->session->data['user_token'] . '&order_id=' . $result['order_id'] . $url, true)
			);
		}

		$data['user_token'] = $this->session->data['user_token'];

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}
		
		if (isset($this->request->post['selected'])) {
			$data['selected'] = (array)$this->request->post['selected'];
		} else {
			$data['selected'] = array();
		}

		$url = '';

		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}

		if (isset($this->request->get['filter_customer'])) {
			$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_order_status'])) {
			$url .= '&filter_order_status=' . $this->request->get['filter_order_status'];
		}
		
		if (isset($this->request->get['filter_order_status_id'])) {
			$url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
		}
			
		if (isset($this->request->get['filter_total'])) {
			$url .= '&filter_total=' . $this->request->get['filter_total'];
		}

		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}

		if (isset($this->request->get['filter_date_modified'])) {
			$url .= '&filter_date_modified=' . $this->request->get['filter_date_modified'];
		}

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_order'] = $this->url->link('sale/order', 'user_token=' . $this->session->data['user_token'] . '&sort=o.order_id' . $url, true);
		$data['sort_customer'] = $this->url->link('sale/order', 'user_token=' . $this->session->data['user_token'] . '&sort=customer' . $url, true);
		$data['sort_status'] = $this->url->link('sale/order', 'user_token=' . $this->session->data['user_token'] . '&sort=order_status' . $url, true);
		$data['sort_total'] = $this->url->link('sale/order', 'user_token=' . $this->session->data['user_token'] . '&sort=o.total' . $url, true);
		$data['sort_date_added'] = $this->url->link('sale/order', 'user_token=' . $this->session->data['user_token'] . '&sort=o.date_added' . $url, true);
		$data['sort_date_modified'] = $this->url->link('sale/order', 'user_token=' . $this->session->data['user_token'] . '&sort=o.date_modified' . $url, true);

		$url = '';

		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}

		if (isset($this->request->get['filter_customer'])) {
			$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_order_status'])) {
			$url .= '&filter_order_status=' . $this->request->get['filter_order_status'];
		}
		
		if (isset($this->request->get['filter_order_status_id'])) {
			$url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
		}
			
		if (isset($this->request->get['filter_total'])) {
			$url .= '&filter_total=' . $this->request->get['filter_total'];
		}

		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}

		if (isset($this->request->get['filter_date_modified'])) {
			$url .= '&filter_date_modified=' . $this->request->get['filter_date_modified'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('sale/order', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($order_total - $this->config->get('config_limit_admin'))) ? $order_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total, ceil($order_total / $this->config->get('config_limit_admin')));

		$data['filter_order_id'] = $filter_order_id;
		$data['filter_customer'] = $filter_customer;
		$data['filter_order_status'] = $filter_order_status;
		$data['filter_order_status_id'] = $filter_order_status_id;
		$data['filter_total'] = $filter_total;
		$data['filter_date_added'] = $filter_date_added;
		$data['filter_date_modified'] = $filter_date_modified;

		$data['sort'] = $sort;
		$data['order'] = $order;

		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		// API login
		$data['catalog'] = $this->request->server['HTTPS'] ? HTTPS_CATALOG : HTTP_CATALOG;
		
		// API login
		$this->load->model('user/api');

		$api_info = $this->model_user_api->getApi($this->config->get('config_api_id'));

		if ($api_info && $this->user->hasPermission('modify', 'sale/order')) {
			$session = new Session($this->config->get('session_engine'), $this->registry);
			
			$session->start();
					
			$this->model_user_api->deleteApiSessionBySessonId($session->getId());
			
			$this->model_user_api->addApiSession($api_info['api_id'], $session->getId(), $this->request->server['REMOTE_ADDR']);
			
			$session->data['api_id'] = $api_info['api_id'];

			$data['api_token'] = $session->getId();
		} else {
			$data['api_token'] = '';
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('sale/order_list', $data));
	}
		
	public function getForm() {
		$data['text_form'] = !isset($this->request->get['order_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

		$url = '';

		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}

		if (isset($this->request->get['filter_customer'])) {
			$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_order_status'])) {
			$url .= '&filter_order_status=' . $this->request->get['filter_order_status'];
		}
		
		if (isset($this->request->get['filter_order_status_id'])) {
			$url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
		}
			
		if (isset($this->request->get['filter_total'])) {
			$url .= '&filter_total=' . $this->request->get['filter_total'];
		}

		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}

		if (isset($this->request->get['filter_date_modified'])) {
			$url .= '&filter_date_modified=' . $this->request->get['filter_date_modified'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('sale/order', 'user_token=' . $this->session->data['user_token'] . $url, true)
		);

		$data['cancel'] = $this->url->link('sale/order', 'user_token=' . $this->session->data['user_token'] . $url, true);

		$data['user_token'] = $this->session->data['user_token'];

		if (isset($this->request->get['order_id'])) {
			$order_info = $this->model_sale_order->getOrder($this->request->get['order_id']);
		}

		if (!empty($order_info)) {
			$data['order_id'] = $this->request->get['order_id'];
			$data['store_id'] = $order_info['store_id'];
			$data['store_url'] = $this->request->server['HTTPS'] ? HTTPS_CATALOG : HTTP_CATALOG;

			$data['customer'] = $order_info['customer'];
			$data['customer_id'] = $order_info['customer_id'];
			$data['customer_group_id'] = $order_info['customer_group_id'];
			$data['firstname'] = $order_info['firstname'];
			$data['lastname'] = $order_info['lastname'];
			$data['email'] = $order_info['email'];
			$data['telephone'] = $order_info['telephone'];
			$data['account_custom_field'] = $order_info['custom_field'];

			$this->load->model('customer/customer');

			$data['addresses'] = $this->model_customer_customer->getAddresses($order_info['customer_id']);

			$data['payment_firstname'] = $order_info['payment_firstname'];
			$data['payment_lastname'] = $order_info['payment_lastname'];
			$data['payment_company'] = $order_info['payment_company'];
			$data['payment_address_1'] = $order_info['payment_address_1'];
			$data['payment_address_2'] = $order_info['payment_address_2'];
			$data['payment_city'] = $order_info['payment_city'];
			$data['payment_postcode'] = $order_info['payment_postcode'];
			$data['payment_country_id'] = $order_info['payment_country_id'];
			$data['payment_zone_id'] = $order_info['payment_zone_id'];
			$data['payment_custom_field'] = $order_info['payment_custom_field'];
			$data['payment_method'] = $order_info['payment_method'];
			$data['payment_code'] = $order_info['payment_code'];

			$data['shipping_firstname'] = $order_info['shipping_firstname'];
			$data['shipping_lastname'] = $order_info['shipping_lastname'];
			$data['shipping_company'] = $order_info['shipping_company'];
			$data['shipping_address_1'] = $order_info['shipping_address_1'];
			$data['shipping_address_2'] = $order_info['shipping_address_2'];
			$data['shipping_city'] = $order_info['shipping_city'];
			$data['shipping_postcode'] = $order_info['shipping_postcode'];
			$data['shipping_country_id'] = $order_info['shipping_country_id'];
			$data['shipping_zone_id'] = $order_info['shipping_zone_id'];
			$data['shipping_custom_field'] = $order_info['shipping_custom_field'];
			$data['shipping_method'] = $order_info['shipping_method'];
			$data['shipping_code'] = $order_info['shipping_code'];

			// Products
			$data['order_products'] = array();

			$products = $this->model_sale_order->getOrderProducts($this->request->get['order_id']);

			foreach ($products as $product) {
				$data['order_products'][] = array(
					'product_id' => $product['product_id'],
					'name'       => $product['name'],
					'model'      => $product['model'],
					'option'     => $this->model_sale_order->getOrderOptions($this->request->get['order_id'], $product['order_product_id']),
					'quantity'   => $product['quantity'],
					'price'      => $product['price'],
					'total'      => $product['total'],
					'reward'     => $product['reward']
				);
			}

			// Vouchers
			$data['order_vouchers'] = $this->model_sale_order->getOrderVouchers($this->request->get['order_id']);

			$data['coupon'] = '';
			$data['voucher'] = '';
			$data['reward'] = '';

			$data['order_totals'] = array();

			$order_totals = $this->model_sale_order->getOrderTotals($this->request->get['order_id']);

			foreach ($order_totals as $order_total) {
				// If coupon, voucher or reward points
				$start = strpos($order_total['title'], '(') + 1;
				$end = strrpos($order_total['title'], ')');

				if ($start && $end) {
					$data[$order_total['code']] = substr($order_total['title'], $start, $end - $start);
				}
			}

			$data['order_status_id'] = $order_info['order_status_id'];
			$data['comment'] = $order_info['comment'];
			$data['affiliate_id'] = $order_info['affiliate_id'];
			$data['affiliate'] = $order_info['affiliate_firstname'] . ' ' . $order_info['affiliate_lastname'];
			$data['currency_code'] = $order_info['currency_code'];
		} else {
			$data['order_id'] = 0;
			$data['store_id'] = 0;
			$data['store_url'] = $this->request->server['HTTPS'] ? HTTPS_CATALOG : HTTP_CATALOG;
			
			$data['customer'] = '';
			$data['customer_id'] = '';
			$data['customer_group_id'] = $this->config->get('config_customer_group_id');
			$data['firstname'] = '';
			$data['lastname'] = '';
			$data['email'] = '';
			$data['telephone'] = '';
			$data['customer_custom_field'] = array();

			$data['addresses'] = array();

			$data['payment_firstname'] = '';
			$data['payment_lastname'] = '';
			$data['payment_company'] = '';
			$data['payment_address_1'] = '';
			$data['payment_address_2'] = '';
			$data['payment_city'] = '';
			$data['payment_postcode'] = '';
			$data['payment_country_id'] = '';
			$data['payment_zone_id'] = '';
			$data['payment_custom_field'] = array();
			$data['payment_method'] = '';
			$data['payment_code'] = '';

			$data['shipping_firstname'] = '';
			$data['shipping_lastname'] = '';
			$data['shipping_company'] = '';
			$data['shipping_address_1'] = '';
			$data['shipping_address_2'] = '';
			$data['shipping_city'] = '';
			$data['shipping_postcode'] = '';
			$data['shipping_country_id'] = '';
			$data['shipping_zone_id'] = '';
			$data['shipping_custom_field'] = array();
			$data['shipping_method'] = '';
			$data['shipping_code'] = '';

			$data['order_products'] = array();
			$data['order_vouchers'] = array();
			$data['order_totals'] = array();

			$data['order_status_id'] = $this->config->get('config_order_status_id');
			$data['comment'] = '';
			$data['affiliate_id'] = '';
			$data['affiliate'] = '';
			$data['currency_code'] = $this->config->get('config_currency');

			$data['coupon'] = '';
			$data['voucher'] = '';
			$data['reward'] = '';
		}

		// Stores
		$this->load->model('setting/store');

		$data['stores'] = array();

		$data['stores'][] = array(
			'store_id' => 0,
			'name'     => $this->language->get('text_default')
		);

		$results = $this->model_setting_store->getStores();

		foreach ($results as $result) {
			$data['stores'][] = array(
				'store_id' => $result['store_id'],
				'name'     => $result['name']
			);
		}

		// Customer Groups
		$this->load->model('customer/customer_group');

		$data['customer_groups'] = $this->model_customer_customer_group->getCustomerGroups();

		// Custom Fields
		$this->load->model('customer/custom_field');

		$data['custom_fields'] = array();

		$filter_data = array(
			'sort'  => 'cf.sort_order',
			'order' => 'ASC'
		);

		$custom_fields = $this->model_customer_custom_field->getCustomFields($filter_data);

		foreach ($custom_fields as $custom_field) {
			$data['custom_fields'][] = array(
				'custom_field_id'    => $custom_field['custom_field_id'],
				'custom_field_value' => $this->model_customer_custom_field->getCustomFieldValues($custom_field['custom_field_id']),
				'name'               => $custom_field['name'],
				'value'              => $custom_field['value'],
				'type'               => $custom_field['type'],
				'location'           => $custom_field['location'],
				'sort_order'         => $custom_field['sort_order']
			);
		}

		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		$this->load->model('localisation/country');

		$data['countries'] = $this->model_localisation_country->getCountries();

		$this->load->model('localisation/currency');

		$data['currencies'] = $this->model_localisation_currency->getCurrencies();

		$data['voucher_min'] = $this->config->get('config_voucher_min');

		$this->load->model('sale/voucher_theme');

		$data['voucher_themes'] = $this->model_sale_voucher_theme->getVoucherThemes();

		// API login
		$data['catalog'] = $this->request->server['HTTPS'] ? HTTPS_CATALOG : HTTP_CATALOG;
		
		// API login
		$this->load->model('user/api');

		$api_info = $this->model_user_api->getApi($this->config->get('config_api_id'));

		if ($api_info && $this->user->hasPermission('modify', 'sale/order')) {
			$session = new Session($this->config->get('session_engine'), $this->registry);
			
			$session->start();
					
			$this->model_user_api->deleteApiSessionBySessonId($session->getId());
			
			$this->model_user_api->addApiSession($api_info['api_id'], $session->getId(), $this->request->server['REMOTE_ADDR']);
			
			$session->data['api_id'] = $api_info['api_id'];

			$data['api_token'] = $session->getId();
		} else {
			$data['api_token'] = '';
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('sale/order_form', $data));
	}



		/* Подключение */
	public function getOpencartConfig (){
		require DIR_SYSTEM . 'dpd/src/autoload.php';
		$options = array(
			'SOURCE_NAME'					=> 'OpenCart',
			'UPLOAD_DIR'					=> DIR_IMAGE . 'dpd/',
			'KLIENT_NUMBER'   => $this->config->get('shipping_dpd_russian_number'),
			'KLIENT_KEY'      => $this->config->get('shipping_dpd_russian_auth'),
			'KLIENT_CURRENCY' => $this->config->get('shipping_dpd_russian_currency'),
			'IS_TEST'         => $this->config->get('shipping_dpd_test') ? true : false,
		);
		
		$options = array_merge($options, [
			'DB' => [
				'DSN'      => 'mysql:host=' . DB_HOSTNAME . ';dbname=' . DB_DATABASE,
				'USERNAME' => DB_USERNAME,
				'PASSWORD' => DB_PASSWORD,
				'DRIVER'   => null,
				'PDO'      => null,
			]
		]);
		
		$config  = new \Ipol\DPD\Config\Config($options);
		
		return $config;
	}
	/* Подключение */
	
	/* Список dpd доставок */
	public function getTariffList(){ 
		
		$config  = $this->getOpencartConfig();
		$shipment = new \Ipol\DPD\Shipment($config);
		$tariffs = $shipment->calculator()->TariffList();
		
		return $tariffs;
	}
	/* Список dpd доставок */
	
	/* Список терминалов отправителя */
	public function getTerminalSender($city_id){
		$config  = $this->getOpencartConfig();
		$terminalTable  = \Ipol\DPD\DB\Connection::getInstance($config)->getTable('terminal');
		
		$items = $terminalTable->find([
			'where' => 'LOCATION_ID = "' . $city_id . '" AND PARCEL_SHOP_TYPE = "ПВП" ORDER BY NAME',
		])->fetchAll();
		
		return $items;
	}
	/* Список терминалов отправителя */
	
	/* Терминал получателя */
	public function getTerminalRecOne($id){
		$config  = $this->getOpencartConfig();
		$terminalTable  = \Ipol\DPD\DB\Connection::getInstance($config)->getTable('terminal');
		
		$items = $terminalTable->find([
			'where' => 'ID = "' . $id . '"',
		])->fetchAll();
		
		return $items;
	}
	/* Терминал получателя */
	
	/* Список терминалов получателя */
	public function getTerminalRec($city_id){
		$config  = $this->getOpencartConfig();
		$terminalTable  = \Ipol\DPD\DB\Connection::getInstance($config)->getTable('terminal');
		
		$items = $terminalTable->find([
			'where' => 'LOCATION_ID = "' . $city_id . '" ORDER BY NAME',
		])->fetchAll();
		
		return $items;
	}
	/* Список терминалов получателя */
	
	/* Рекалькулейт */
	public function recalculate(){
		$json = array();
		
		$this->load->model('sale/order');
		$this->load->language('extension/shipping/dpd');
		$this->load->model('extension/shipping/dpd');
		
		if (isset($this->request->post['order_id'])) {
			$order_id = $this->request->post['order_id'];
		}else{
			$order_id = 0;
		}
		
		$order_info = $this->model_sale_order->getOrder($order_id);
		
		/* Entry */
		$json['entry_order_dpd_status'] = $this->language->get('entry_order_dpd_status');
		$json['entry_order_price_delivery'] = $this->language->get('entry_order_price_delivery');
		$json['entry_order_price_products'] = $this->language->get('entry_order_price_products');
		$json['entry_order_price_npp'] = $this->language->get('entry_order_price_npp');
		$json['entry_order_id_ocart'] = $this->language->get('entry_order_id_ocart');
		$json['entry_order_id_dpd'] = $this->language->get('entry_order_id_dpd');
		$json['entry_send_to_dpd'] = $this->language->get('entry_send_to_dpd');
		$json['entry_payment_method_delivery'] = $this->language->get('entry_payment_method_delivery');
		$json['entry_delivery_method'] = $this->language->get('entry_delivery_method');
		$json['entry_variable_delivery'] = $this->language->get('entry_variable_delivery');
		$json['entry_date_shipment'] = $this->language->get('entry_date_shipment');
		$json['entry_dpd_transit_interval'] = $this->language->get('entry_dpd_transit_interval');
		$json['entry_delivery_time_interval'] = $this->language->get('entry_delivery_time_interval');
		$json['entry_weight_shipment'] = $this->language->get('entry_weight_shipment');
		$json['entry_dimensions_shipment'] = $this->language->get('entry_dimensions_shipment');
		$json['entry_volume_shipment'] = $this->language->get('entry_volume_shipment');
		$json['entry_quantity_places'] = $this->language->get('entry_quantity_places');
		$json['entry_content_sender'] = $this->language->get('entry_content_sender');
		$json['entry_paid'] = $this->language->get('entry_paid');
		$json['entry_total_oc'] = $this->language->get('entry_total_oc');
		$json['entry_payment_npp'] = $this->language->get('entry_payment_npp');
		$json['entry_contact_face']		= $this->language->get('entry_contact_face');
		$json['entry_name_company']		= $this->language->get('entry_name_company');
		$json['entry_phone_sender']		= $this->language->get('entry_phone_sender');
		$json['entry_email_sender']		= $this->language->get('entry_email_sender');
		$json['entry_pass']		= $this->language->get('entry_pass');
		$json['entry_h_address']		= $this->language->get('entry_h_address');
		$json['entry_city_sender']		= $this->language->get('entry_city_sender');
		$json['entry_terminal_sender']		= $this->language->get('entry_terminal_sender');
		$json['entry_street_sender']	= $this->language->get('entry_street_sender');
		$json['entry_ab_street_sender']	= $this->language->get('entry_ab_street_sender');	
		$json['entry_house_sender']	= $this->language->get('entry_house_sender');
		$json['entry_corp_sender']	= $this->language->get('entry_corp_sender');
		$json['entry_structure_sender']	= $this->language->get('entry_structure_sender');
		$json['entry_poss_sender']	= $this->language->get('entry_poss_sender');
		$json['entry_office_sender']	= $this->language->get('entry_office_sender');
		$json['entry_apart_sender']	= $this->language->get('entry_apart_sender');
		$json['entry_city_rec']	= $this->language->get('entry_city_rec');
		$json['entry_name_product']	= $this->language->get('entry_name_product');
		$json['entry_quantity_product']	= $this->language->get('entry_quantity_product');
		$json['entry_declared_product']	= $this->language->get('entry_declared_product');
		$json['entry_npp_product']	= $this->language->get('entry_npp_product');
		$json['entry_declared_check']	= $this->language->get('entry_declared_check');
		$json['entry_declared_value']	= $this->language->get('entry_declared_value');
		$json['entry_npp_check']	= $this->language->get('entry_npp_check');
		$json['entry_npp_value']	= $this->language->get('entry_npp_value');
		$json['entry_val_cargo']	= $this->language->get('entry_val_cargo');
		$json['entry_weekend_delivery']	= $this->language->get('entry_weekend_delivery');
		$json['entry_condition']	= $this->language->get('entry_condition');
		$json['entry_loading_unloading']	= $this->language->get('entry_loading_unloading');
		$json['entry_return_doc']	= $this->language->get('entry_return_doc');
		$json['entry_wait_address']	= $this->language->get('entry_wait_address');
		$json['entry_order_mail']	= $this->language->get('entry_order_mail');
		$json['entry_h_alert']		= $this->language->get('entry_h_alert');
		$json['entry_comment_order']	= $this->language->get('entry_comment_order');
				
		/* Help */
		$json['help_contact_face']		= $this->language->get('help_contact_face');
		$json['help_name_company']		= $this->language->get('help_name_company');
		$json['help_ab_street_sender']	= $this->language->get('help_ab_street_sender');
		$json['help_val_cargo']	= $this->language->get('help_val_cargo');
		$json['help_weekend_delivery']	= $this->language->get('help_weekend_delivery');
		$json['help_loading_unloading']	= $this->language->get('help_loading_unloading');
		$json['help_return_doc']	= $this->language->get('help_return_doc');
		$json['help_order_mail']	= $this->language->get('help_order_mail');
				
		/* Text */
		$json['text_edit_dpd'] = $this->language->get('text_edit_dpd');	
		$json['text_detail_order'] = $this->language->get('text_detail_order');	
		$json['text_dimensions_warning'] = $this->language->get('text_dimensions_warning');	
		$json['text_sum_currency_order'] = $this->language->get('text_sum_currency_order');
		$json['text_price_product_for_1'] = $this->language->get('text_price_product_for_1');
		$json['text_composition_order'] = $this->language->get('text_composition_order');
		$json['text_option'] = $this->language->get('text_option');
		$json['text_doc_print'] = $this->language->get('text_doc_print');
				
		/* Tabs */
		$json['tab_order'] = $this->language->get('tab_order');	
		$json['tab_sender'] = $this->language->get('tab_sender');	
		$json['tab_recipient'] = $this->language->get('tab_recipient');	
		$json['tab_payment'] = $this->language->get('tab_payment');	
		$json['tab_option'] = $this->language->get('tab_option');	
		$json['tab_doc'] = $this->language->get('tab_doc');
		
		$json['warning_weekend_delivery'] = $this->language->get('warning_weekend_delivery');
				
		/* Варианты доставки */
		$json['variable_delivery_arr'] = array();
		$json['variable_delivery_arr'][0]['value'] = '0/0';	
		$json['variable_delivery_arr'][0]['name'] = 'Дверь - Дверь';	
		$json['variable_delivery_arr'][1]['value'] = '0/1';			
		$json['variable_delivery_arr'][1]['name'] = 'Дверь - Терминал';
		$json['variable_delivery_arr'][2]['value'] = '1/0';
		$json['variable_delivery_arr'][2]['name'] = 'Терминал - Дверь';
		$json['variable_delivery_arr'][3]['value'] = '1/1';
		$json['variable_delivery_arr'][3]['name'] = 'Терминал - Терминал';
				
		/*  DPD Transit Time Interval */
		$json['dpd_transit_interval'] = array();
		$json['dpd_transit_interval'][0]['value'] = '9-18';
		$json['dpd_transit_interval'][0]['name'] = 'в любое время с 09:00 до 18:00';
		$json['dpd_transit_interval'][1]['value'] = '9-13';
		$json['dpd_transit_interval'][1]['name'] = 'с 09:00 до 13:00';
		$json['dpd_transit_interval'][2]['value'] = '13-18';
		$json['dpd_transit_interval'][2]['name'] = 'с 13:00 до 18:00';
				
		/* Delivery time interval */
		$json['delivery_time_interval'] = array();
		$json['delivery_time_interval'][0]['value'] = '9-18';
		$json['delivery_time_interval'][0]['name'] = 'в любое время с 09:00 до 18:00';
		$json['delivery_time_interval'][1]['value'] = '9-14';
		$json['delivery_time_interval'][1]['name'] = 'с 09:00 до 14:00';
		$json['delivery_time_interval'][2]['value'] = '13-18';
		$json['delivery_time_interval'][2]['name'] = 'с 13:00 до 18:00';
		$json['delivery_time_interval'][3]['value'] = '18-22';
		$json['delivery_time_interval'][3]['name'] = 'с 18:00 да 22:00 (оплачивается дополнительно)';
				
		/* Waiting on address */
		$json['waiting_address'] = array();
		$json['waiting_address'][0]['value'] = 0;
		$json['waiting_address'][0]['name'] = '- Не установленно -';
		$json['waiting_address'][1]['value'] = 'ПРИМ';
		$json['waiting_address'][1]['name'] = 'С примеркой';
		$json['waiting_address'][2]['value'] = 'ПРОС';
		$json['waiting_address'][2]['name'] = 'Простая';
		$json['waiting_address'][3]['value'] = 'РАБТ';
		$json['waiting_address'][3]['name'] = 'С проверкой работоспособности';
				
		/* Службы доставки */
		$tariffs = $this->getTariffList();
			
		foreach($tariffs as $key => $tariff){
			$json['tariffs'][] = array(
				'value'	=>	$key,
				'name'	=>	$tariff
			);
		}
				
		$json['dpd_orders'] = array();
				
		/* Значения из настроек */
		$json['shipping_dpd_payment_method_delivery'] = $this->config->get('shipping_dpd_payment_method_delivery');
		$json['shipping_dpd_transit_interval_dpd'] = $this->config->get('shipping_dpd_transit_interval_dpd');
		$json['shipping_dpd_delivery_time_interval'] = $this->config->get('shipping_dpd_delivery_time_interval');
		$json['shipping_dpd_tariff_default'] = $this->config->get('shipping_dpd_tariff_default');
		$json['shipping_dpd_weight'] = $this->config->get('shipping_dpd_weight')/1000;
		$json['shipping_dpd_length'] = $this->config->get('shipping_dpd_length')/10;
		$json['shipping_dpd_width'] = $this->config->get('shipping_dpd_width')/10;
		$json['shipping_dpd_height'] = $this->config->get('shipping_dpd_height')/10;
		$json['shipping_dpd_quantity_places'] = $this->config->get('shipping_dpd_quantity_places');
		$json['shipping_dpd_content_sender'] = $this->config->get('shipping_dpd_content_sender');
		$json['shipping_dpd_contact_face'] = $this->config->get('shipping_dpd_contact_face');
		$json['shipping_dpd_name_company'] = $this->config->get('shipping_dpd_name_company');
		$json['shipping_dpd_phone_sender'] = $this->config->get('shipping_dpd_phone_sender');
		$json['shipping_dpd_email_sender'] = $this->config->get('shipping_dpd_email_sender');
		$json['shipping_dpd_city_id'] = $this->config->get('shipping_dpd_city_id');
		$json['shipping_dpd_pass'] = $this->config->get('shipping_dpd_pass');
		$json['shipping_dpd_terminal_sender'] = $this->config->get('shipping_dpd_terminal_sender');
		$json['shipping_dpd_street_sender'] = $this->config->get('shipping_dpd_street_sender');
		$json['shipping_dpd_ab_street_sender'] = $this->config->get('shipping_dpd_ab_street_sender');
		$json['shipping_dpd_house_sender'] = $this->config->get('shipping_dpd_house_sender');
		$json['shipping_dpd_corp_sender'] = $this->config->get('shipping_dpd_corp_sender');
		$json['shipping_dpd_structure_sender'] = $this->config->get('shipping_dpd_structure_sender');
		$json['shipping_dpd_poss_sender'] = $this->config->get('shipping_dpd_poss_sender');
		$json['shipping_dpd_office_sender'] = $this->config->get('shipping_dpd_office_sender');
		$json['shipping_dpd_apart_sender'] = $this->config->get('shipping_dpd_apart_sender');
		$json['shipping_dpd_pass_rec'] = $this->config->get('shipping_dpd_pass_rec');
		$json['shipping_dpd_val_cargo'] = $this->config->get('shipping_dpd_val_cargo');
		$json['shipping_dpd_weekend_delivery'] = $this->config->get('shipping_dpd_weekend_delivery');
		$json['shipping_dpd_condition'] = $this->config->get('shipping_dpd_condition');
		$json['shipping_dpd_loading_unloading'] = $this->config->get('shipping_dpd_loading_unloading');
		$json['shipping_dpd_return_doc'] = $this->config->get('shipping_dpd_return_doc');
		$json['shipping_dpd_wait_address'] = $this->config->get('shipping_dpd_wait_address');
		$json['shipping_dpd_order_mail'] = $this->config->get('shipping_dpd_order_mail');

		/* DPD orders */
		
		$dpd = $this->model_extension_shipping_dpd->getOrderDpd($order_id);	
				
		$json['dpd_orders'] = array(
			'order_id'					=> isset($dpd['order_id']) ? $dpd['order_id'] : $order_id,
			'dpd_id'					=> (isset($dpd['dpd_id']) && !empty($dpd['dpd_id'])) ? $dpd['dpd_id'] : '',
			'status_dpd'				=> (isset($dpd['status_dpd']) && ($dpd['status_dpd'] !== '')) ? $dpd['status_dpd'] : 'NEW',	
			'payment_method_delivery'	=> isset($dpd['payment_method_delivery']) ? $dpd['payment_method_delivery'] : $json['shipping_dpd_payment_method_delivery'],
			'shipping_method'			=> isset($dpd['shipping_method']) ? $dpd['shipping_method'] : $json['shipping_dpd_tariff_default'],
			'shipping_variable'			=> isset($dpd['shipping_variable']) ? $dpd['shipping_variable'] : '0/0',
			'date_shipment'				=> isset($dpd['date_shipment']) ? $dpd['date_shipment'] : date("d.m.Y H:i:s"),
			'transit_interval_dpd'		=> isset($dpd['transit_interval_dpd']) ? $dpd['transit_interval_dpd'] : $json['shipping_dpd_transit_interval_dpd'],
			'delivery_time_interval'	=> isset($dpd['delivery_time_interval']) ? $dpd['delivery_time_interval'] : $json['shipping_dpd_delivery_time_interval'],	
			'weight'					=> isset($dpd['weight']) ? $dpd['weight'] : $json['shipping_dpd_weight'],	
			'width'						=> isset($dpd['width']) ? $dpd['width'] : $json['shipping_dpd_width'],
			'height'					=> isset($dpd['height']) ? $dpd['height'] : $json['shipping_dpd_height'],
			'length'					=> isset($dpd['length']) ? $dpd['length'] : $json['shipping_dpd_length'],
			'volume'					=> isset($dpd['volume']) ? $dpd['volume'] : 0.004,	
			'quantity_places'			=> isset($dpd['quantity_places']) ? $dpd['quantity_places'] : $json['shipping_dpd_quantity_places'],
			'content_sender'			=> isset($dpd['content_sender']) ? $dpd['content_sender'] : $json['shipping_dpd_content_sender'],
			'contact_face'				=> isset($dpd['contact_face']) ? $dpd['contact_face'] : $json['shipping_dpd_contact_face'],
			'name_company'				=> isset($dpd['name_company']) ? $dpd['name_company'] : $json['shipping_dpd_name_company'],	
			'phone_sender'				=> isset($dpd['phone_sender']) ? $dpd['phone_sender'] : $json['shipping_dpd_phone_sender'],
			'email_sender'				=> isset($dpd['email_sender']) ? $dpd['email_sender'] : $json['shipping_dpd_email_sender'],	
			'pass'						=> isset($dpd['pass']) ? $dpd['pass'] : $json['shipping_dpd_pass'],	
			'city_sender_id'			=> isset($json['city_sender']) ? $json['city_sender'] : $this->config->get('shipping_dpd_city_id'),
			'street_sender'				=> isset($dpd['street_sender']) ? $dpd['street_sender'] : $json['shipping_dpd_street_sender'],
			'ab_street_sender'			=> isset($dpd['ab_street_sender']) ? $dpd['ab_street_sender'] : $json['shipping_dpd_ab_street_sender'],
			'house_sender'				=> isset($dpd['house_sender']) ? $dpd['house_sender'] : $json['shipping_dpd_house_sender'],	
			'corp_sender'				=> isset($dpd['corp_sender']) ? $dpd['corp_sender'] : $json['shipping_dpd_corp_sender'],	
			'structure_sender'			=> isset($dpd['structure_sender']) ? $dpd['structure_sender'] : $json['shipping_dpd_structure_sender'],	
			'poss_sender'				=> isset($dpd['poss_sender']) ? $dpd['poss_sender'] : $json['shipping_dpd_poss_sender'],
			'office_sender'				=> isset($dpd['office_sender']) ? $dpd['office_sender'] : $json['shipping_dpd_office_sender'],	
			'apart_sender'				=> isset($dpd['apart_sender']) ? $dpd['apart_sender'] : $json['shipping_dpd_apart_sender'],	
			'terminal_sender'			=> isset($dpd['terminal_sender']) ? $dpd['terminal_sender'] : $json['shipping_dpd_terminal_sender'],
			'contact_face_rec'			=> isset($dpd['contact_face_rec']) ? $dpd['contact_face_rec'] : '',	
			'name_company_rec'			=> isset($dpd['name_company_rec']) ? $dpd['name_company_rec'] : '',	
			'phone_rec'					=> isset($dpd['phone_rec']) ? $dpd['phone_rec'] : '',	
			'email_rec'					=> isset($dpd['email_rec']) ? $dpd['email_rec'] : '',
			'pass_rec'					=> isset($dpd['pass_rec']) ? $dpd['pass_rec'] : $json['shipping_dpd_pass_rec'],	
			'city_rec_id'				=> isset($dpd['city_rec_id']) ? $dpd['city_rec_id'] : $this->config->get('shipping_dpd_city_id'),
			'street_rec'				=> (isset($dpd['street_rec']) && !empty($dpd['street_rec'])) ? $dpd['street_rec'] : $order_info['shipping_address_1'],
			'ab_street_rec'				=> isset($dpd['ab_street_rec']) ? $dpd['ab_street_rec'] : '',
			'house_rec'					=> isset($dpd['house_rec']) ? $dpd['house_rec'] : '',
			'corp_rec'					=> isset($dpd['corp_rec']) ? $dpd['corp_rec'] : '',	
			'structure_rec'				=> isset($dpd['structure_rec']) ? $dpd['structure_rec'] : '',	
			'poss_rec'					=> isset($dpd['poss_rec']) ? $dpd['poss_rec'] : '',	
			'office_rec'				=> isset($dpd['office_rec']) ? $dpd['office_rec'] : '',
			'apart_rec'					=> isset($dpd['apart_rec']) ? $dpd['apart_rec'] : '',
			'terminal_rec'				=> isset($dpd['terminal_rec']) ? $dpd['terminal_rec'] : '',	
			'comment_rec'				=> isset($dpd['comment_rec']) ? $dpd['comment_rec'] : '',	
			'val_cargo'					=> isset($dpd['val_cargo']) ? $dpd['val_cargo'] : $json['shipping_dpd_val_cargo'],
			'weekend_delivery'			=> isset($dpd['weekend_delivery']) ? $dpd['weekend_delivery'] : $json['shipping_dpd_weekend_delivery'],
			'temperature'				=> isset($dpd['temperature']) ? $dpd['temperature'] : $json['shipping_dpd_condition'],
			'loading_unloading'			=> isset($dpd['loading_unloading']) ? $dpd['loading_unloading'] : $json['shipping_dpd_loading_unloading'],
			'return_doc'				=> isset($dpd['return_doc']) ? $dpd['return_doc'] : $json['shipping_dpd_return_doc'],	
			'wait_address'				=> isset($dpd['wait_address']) ? $dpd['wait_address'] : $json['shipping_dpd_wait_address'],
			'order_mail'				=> isset($dpd['order_mail']) ? $dpd['order_mail'] : $json['shipping_dpd_order_mail'],
			'declared_check'			=> isset($dpd['declared_check']) ? $dpd['declared_check'] : '',	
			'declared_value'			=> isset($dpd['declared_value']) ? round($dpd['declared_value'],2) : '',
			'order_npp_check'			=> isset($dpd['order_npp_check']) ? $dpd['order_npp_check'] : '',	
			'order_npp_value'			=> isset($dpd['order_npp_value']) ? round($dpd['order_npp_value'],2) : '',
			'products'					=> isset($dpd['products']) ? unserialize($dpd['products']) : '',
		);
		
		/* Вариант доставки */
		if (isset($this->request->post['variable_delivery'])){
			/* Вывод отправителя и покупателя */
			list($json['selfpickup'],$json['selfdelivery']) = explode('/', $this->request->post['variable_delivery']);
		}else{
			list($json['selfpickup'],$json['selfdelivery']) = explode('/', $json['dpd_orders']['shipping_variable']);
		}
		
		if($json['selfpickup'] == 1){
			/* Способы оплаты доставки */
			$json['payment_method_delivery_arr'] = array();
			$json['payment_method_delivery_arr'][0]['value'] = 0;	
			$json['payment_method_delivery_arr'][0]['name'] = 'У отправителя по безналичному расчёту';	
			$json['payment_method_delivery_arr'][1]['value'] = 'ОУП';			
			$json['payment_method_delivery_arr'][1]['name'] = 'Оплата у получателя наличными';
		}else{		
			/* Способы оплаты доставки */
			$json['payment_method_delivery_arr'] = array();
			$json['payment_method_delivery_arr'][0]['value'] = 0;	
			$json['payment_method_delivery_arr'][0]['name'] = 'У отправителя по безналичному расчёту';	
			$json['payment_method_delivery_arr'][1]['value'] = 'ОУП';			
			$json['payment_method_delivery_arr'][1]['name'] = 'Оплата у получателя наличными';
			$json['payment_method_delivery_arr'][2]['value'] = 'OUO';
			$json['payment_method_delivery_arr'][2]['name'] = 'Оплата у отправителя наличными';
		}
		
		/* Ставки НДС */
		$json['NDS'] = array();
		$json['NDS'][0]['value'] = 'Без НДС';
		$json['NDS'][0]['name'] = 'Без НДС';	
		$json['NDS'][1]['value'] = '0';			
		$json['NDS'][1]['name'] = '0%';
		$json['NDS'][2]['value'] = '10';
		$json['NDS'][2]['name'] = '10%';
		$json['NDS'][3]['value'] = '20';
		$json['NDS'][3]['name'] = '20%';
		
		if(isset($this->request->post['weight_shipment'])){
			$json['weight'] = $this->request->post['weight_shipment'];
		}else{
			$json['weight'] = $json['dpd_orders']['weight'];
		}
		
		if(isset($this->request->post['height'])){
			if($this->request->post['height'] != ''){
				$json['height'] = $this->request->post['height'];
			}else{
				$json['height'] = 0;
			}
		}else{
			$json['height'] = $json['dpd_orders']['height'];
		}
		
		if(isset($this->request->post['length'])){
			if($this->request->post['length'] != ''){
				$json['length'] = $this->request->post['length'];
			}else{
				$json['length'] = 0;
			}
		}else{
			$json['length'] = $json['dpd_orders']['length'];
		}
		
		if(isset($this->request->post['width'])){
			if($this->request->post['width'] != ''){
				$json['width'] = $this->request->post['width'];
			}else{
				$json['width'] = 0;
			}
		}else{
			$json['width'] = $json['dpd_orders']['width'];
		}
		
		if(isset($this->request->post['volume'])){
			$json['volume'] = $this->request->post['volume'];
		}else{
			$json['volume'] = $json['dpd_orders']['volume'];
		}
		
		if(isset($this->request->post['terminal_rec'])){
			$json['terminal_rec'] = $this->request->post['terminal_rec'];
		}else{
			$json['terminal_rec'] = $json['dpd_orders']['terminal_rec'];
		}
		
		if(isset($this->request->post['terminal_sender'])){
			$json['terminal_sender'] = $this->request->post['terminal_sender'];
		}else{
			$json['terminal_sender'] = $json['dpd_orders']['terminal_sender'];
		}
		
		if($json['selfdelivery'] == 1){
			if(isset($this->request->post['terminal_rec'])){
				$valid_rec = $this->getTerminalRecOne($this->request->post['terminal_rec']);
			}else{
				$valid_rec = $this->getTerminalRecOne($json['dpd_orders']['terminal_rec']);
			}
			if(!empty($valid_rec)){
				if($valid_rec[0]['IS_LIMITED'] == 'Y'){
					if(isset($this->request->post['weight_shipment'])){
						if($this->request->post['weight_shipment'] > $valid_rec[0]['LIMIT_MAX_WEIGHT']){
							$json['not']['rec'] = 'Габариты посылки превышают макс. габариты терминала назначения';
						}
					}else{
						if($json['dpd_orders']['weight'] > $valid_rec[0]['LIMIT_MAX_WEIGHT']){
							$json['not']['rec'] = 'Габариты посылки превышают макс. габариты терминала назначения';
						}
					}
					
					if(isset($this->request->post['height'])){
						if($this->request->post['height'] > $valid_rec[0]['LIMIT_MAX_HEIGHT']){
							$json['not']['rec'] = 'Габариты посылки превышают макс. габариты терминала назначения';
						}
					}else{
						if($json['dpd_orders']['height'] > $valid_rec[0]['LIMIT_MAX_HEIGHT']){
							$json['not']['rec'] = 'Габариты посылки превышают макс. габариты терминала назначения';
						}
					}
					
					if(isset($this->request->post['length'])){
						if($this->request->post['length'] > $valid_rec[0]['LIMIT_MAX_LENGTH']){
							$json['not']['rec'] = 'Габариты посылки превышают макс. габариты терминала назначения';
						}
					}else{
						if($json['dpd_orders']['length'] > $valid_rec[0]['LIMIT_MAX_LENGTH']){
							$json['not']['rec'] = 'Габариты посылки превышают макс. габариты терминала назначения';
						}
					}
					
					if(isset($this->request->post['width'])){
						if($this->request->post['width'] > $valid_rec[0]['LIMIT_MAX_WIDTH']){
							$json['not']['rec'] = 'Габариты посылки превышают макс. габариты терминала назначения';
						}
					}else{
						if($json['dpd_orders']['width'] > $valid_rec[0]['LIMIT_MAX_WIDTH']){
							$json['not']['rec'] = 'Габариты посылки превышают макс. габариты терминала назначения';
						}
					}
					
					if(isset($this->request->post['volume'])){
						if($this->request->post['volume'] > $valid_rec[0]['LIMIT_MAX_VOLUME']){
							$json['not']['rec'] = 'Габариты посылки превышают макс. габариты терминала назначения';
						}
					}else{
						if($json['dpd_orders']['volume'] > $valid_rec[0]['LIMIT_MAX_VOLUME']){
							$json['not']['rec'] = 'Габариты посылки превышают макс. габариты терминала назначения';
						}
					}
				}
			}
		}
		
		if($json['selfpickup'] == 1){
			if(isset($this->request->post['terminal_sender'])){
				$valid_send = $this->getTerminalRecOne($this->request->post['terminal_sender']);
			}else{
				$valid_send = $this->getTerminalRecOne($json['dpd_orders']['terminal_sender']);
			}
			if(!empty($valid_send)){
				if($valid_send[0]['IS_LIMITED'] == 'Y'){
					if(isset($this->request->post['weight_shipment'])){
						if($this->request->post['weight_shipment'] > $valid_send[0]['LIMIT_MAX_WEIGHT']){
							$json['not']['send'] = 'Габариты посылки превышают макс. габариты терминала назначения';
						}
					}else{
						if($json['dpd_orders']['weight'] > $valid_send[0]['LIMIT_MAX_WEIGHT']){
							$json['not']['send'] = 'Габариты посылки превышают макс. габариты терминала назначения';
						}
					}
					
					if(isset($this->request->post['height'])){
						if($this->request->post['height'] > $valid_send[0]['LIMIT_MAX_HEIGHT']){
							$json['not']['send'] = 'Габариты посылки превышают макс. габариты терминала назначения';
						}
					}else{
						if($json['dpd_orders']['height'] > $valid_send[0]['LIMIT_MAX_HEIGHT']){
							$json['not']['send'] = 'Габариты посылки превышают макс. габариты терминала назначения';
						}
					}
					
					if(isset($this->request->post['length'])){
						if($this->request->post['length'] > $valid_send[0]['LIMIT_MAX_LENGTH']){
							$json['not']['send'] = 'Габариты посылки превышают макс. габариты терминала назначения';
						}
					}else{
						if($json['dpd_orders']['length'] > $valid_send[0]['LIMIT_MAX_LENGTH']){
							$json['not']['send'] = 'Габариты посылки превышают макс. габариты терминала назначения';
						}
					}
					
					if(isset($this->request->post['width'])){
						if($this->request->post['width'] > $valid_send[0]['LIMIT_MAX_WIDTH']){
							$json['not']['send'] = 'Габариты посылки превышают макс. габариты терминала назначения';
						}
					}else{
						if($json['dpd_orders']['width'] > $valid_send[0]['LIMIT_MAX_WIDTH']){
							$json['not']['send'] = 'Габариты посылки превышают макс. габариты терминала назначения';
						}
					}
					
					if(isset($this->request->post['volume'])){
						if($this->request->post['volume'] > $valid_send[0]['LIMIT_MAX_VOLUME']){
							$json['not']['send'] = 'Габариты посылки превышают макс. габариты терминала назначения';
						}
					}else{
						if($json['dpd_orders']['volume'] > $valid_send[0]['LIMIT_MAX_VOLUME']){
							$json['not']['send'] = 'Габариты посылки превышают макс. габариты терминала назначения';
						}
					}
				}
			}
		}
		
		if(isset($this->request->post['payment_method_delivery'])){
			$json['payment_method_delivery'] = $this->request->post['payment_method_delivery'];
		}else{
			$json['payment_method_delivery'] = $json['dpd_orders']['payment_method_delivery'];
		}
		
		if(isset($this->request->post['variable_delivery'])){
			$json['variable_delivery'] = $this->request->post['variable_delivery'];
		}else{
			$json['variable_delivery'] = $json['dpd_orders']['shipping_variable'];
		}
		
		if(isset($this->request->post['transit_interval'])){
			$json['transit_interval'] = $this->request->post['transit_interval'];
		}else{
			$json['transit_interval'] = $json['dpd_orders']['transit_interval_dpd'];
		}
		
		if(isset($this->request->post['time_interval'])){
			$json['time_interval'] = $this->request->post['time_interval'];
		}else{
			$json['time_interval'] = $json['dpd_orders']['delivery_time_interval'];
		}
		
		if(isset($this->request->post['quantity_places'])){
			$json['quantity_places'] = $this->request->post['quantity_places'];
		}else{
			$json['quantity_places'] = $json['dpd_orders']['quantity_places'];
		}
		
		if(isset($this->request->post['content_sender'])){
			$json['content_sender'] = $this->request->post['content_sender'];
		}else{
			$json['content_sender'] = $json['dpd_orders']['content_sender'];
		}
		
		if(isset($this->request->post['contact_face'])){
			$json['contact_face'] = $this->request->post['contact_face'];
		}else{
			$json['contact_face'] = $json['dpd_orders']['contact_face'];
		}
		
		if(isset($this->request->post['name_company'])){
			$json['name_company'] = $this->request->post['name_company'];
		}else{
			$json['name_company'] = $json['dpd_orders']['name_company'];
		}
		
		if(isset($this->request->post['phone_sender'])){
			$json['phone_sender'] = $this->request->post['phone_sender'];
		}else{
			$json['phone_sender'] = $json['dpd_orders']['phone_sender'];
		}
		
		if(isset($this->request->post['email_sender'])){
			$json['email_sender'] = $this->request->post['email_sender'];
		}else{
			$json['email_sender'] = $json['dpd_orders']['email_sender'];
		}
		
		if(isset($this->request->post['pass'])){
			$json['pass'] = $this->request->post['pass'];
		}else{
			$json['pass'] = $json['dpd_orders']['pass'];
		}
		
		if(isset($this->request->post['street_sender'])){
			$json['street_sender'] = $this->request->post['street_sender'];
		}else{
			$json['street_sender'] = $json['dpd_orders']['street_sender'];
		}
		
		if(isset($this->request->post['ab_street_sender'])){
			$json['ab_street_sender'] = $this->request->post['ab_street_sender'];
		}else{
			$json['ab_street_sender'] = $json['dpd_orders']['ab_street_sender'];
		}
		
		if(isset($this->request->post['house_sender'])){
			$json['house_sender'] = $this->request->post['house_sender'];
		}else{
			$json['house_sender'] = $json['dpd_orders']['house_sender'];
		}
		
		if(isset($this->request->post['corp_sender'])){
			$json['corp_sender'] = $this->request->post['corp_sender'];
		}else{
			$json['corp_sender'] = $json['dpd_orders']['corp_sender'];
		}
		
		if(isset($this->request->post['structure_sender'])){
			$json['structure_sender'] = $this->request->post['structure_sender'];
		}else{
			$json['structure_sender'] = $json['dpd_orders']['structure_sender'];
		}
		
		if(isset($this->request->post['poss_sender'])){
			$json['poss_sender'] = $this->request->post['poss_sender'];
		}else{
			$json['poss_sender'] = $json['dpd_orders']['poss_sender'];
		}
		
		if(isset($this->request->post['office_sender'])){
			$json['office_sender'] = $this->request->post['office_sender'];
		}else{
			$json['office_sender'] = $json['dpd_orders']['office_sender'];
		}
		
		if(isset($this->request->post['apart_sender'])){
			$json['apart_sender'] = $this->request->post['apart_sender'];
		}else{
			$json['apart_sender'] = $json['dpd_orders']['apart_sender'];
		}
		
		if(isset($this->request->post['contact_face_rec'])){
			$json['contact_face_rec'] = $this->request->post['contact_face_rec'];
		}else{
			$json['contact_face_rec'] = $json['dpd_orders']['contact_face_rec'];
		}
		
		if(isset($this->request->post['name_company_rec'])){
			$json['name_company_rec'] = $this->request->post['name_company_rec'];
		}else{
			$json['name_company_rec'] = $json['dpd_orders']['name_company_rec'];
		}
		
		if(isset($this->request->post['phone_rec'])){
			$json['phone_rec'] = $this->request->post['phone_rec'];
		}else{
			$json['phone_rec'] = $json['dpd_orders']['phone_rec'];
		}
		
		if(isset($this->request->post['email_rec'])){
			$json['email_rec'] = $this->request->post['email_rec'];
		}else{
			$json['email_rec'] = $json['dpd_orders']['email_rec'];
		}
		
		if(isset($this->request->post['pass_rec'])){
			$json['pass_rec'] = $this->request->post['pass_rec'];
		}else{
			$json['pass_rec'] = $json['dpd_orders']['pass_rec'];
		}
		
		if(isset($this->request->post['street_rec'])){
			$json['street_rec'] = $this->request->post['street_rec'];
		}else{
			$json['street_rec'] = $json['dpd_orders']['street_rec'];
		}
		
		if(isset($this->request->post['ab_street_rec'])){
			$json['ab_street_rec'] = $this->request->post['ab_street_rec'];
		}else{
			$json['ab_street_rec'] = $json['dpd_orders']['ab_street_rec'];
		}
		
		if(isset($this->request->post['house_rec'])){
			$json['house_rec'] = $this->request->post['house_rec'];
		}else{
			$json['house_rec'] = $json['dpd_orders']['house_rec'];
		}
		
		if(isset($this->request->post['corp_rec'])){
			$json['corp_rec'] = $this->request->post['corp_rec'];
		}else{
			$json['corp_rec'] = $json['dpd_orders']['corp_rec'];
		}
		
		if(isset($this->request->post['structure_rec'])){
			$json['structure_rec'] = $this->request->post['structure_rec'];
		}else{
			$json['structure_rec'] = $json['dpd_orders']['structure_rec'];
		}
		
		if(isset($this->request->post['poss_rec'])){
			$json['poss_rec'] = $this->request->post['poss_rec'];
		}else{
			$json['poss_rec'] = $json['dpd_orders']['poss_rec'];
		}
		
		if(isset($this->request->post['office_rec'])){
			$json['office_rec'] = $this->request->post['office_rec'];
		}else{
			$json['office_rec'] = $json['dpd_orders']['office_rec'];
		}
		
		if(isset($this->request->post['apart_rec'])){
			$json['apart_rec'] = $this->request->post['apart_rec'];
		}else{
			$json['apart_rec'] = $json['dpd_orders']['apart_rec'];
		}
		
		if(isset($this->request->post['comment_rec'])){
			$json['comment_rec'] = $this->request->post['comment_rec'];
		}else{
			$json['comment_rec'] = $json['dpd_orders']['comment_rec'];
		}
		
		if(isset($this->request->post['val_cargo'])){
			$json['val_cargo'] = 1;
		}elseif(isset($this->request->post['shipping_method'])){
			$json['val_cargo'] = 0;
		}else{
			$json['val_cargo'] = $json['dpd_orders']['val_cargo'];
		}
		
		if(isset($this->request->post['weekend_delivery'])){
			$json['weekend_delivery'] = $this->request->post['weekend_delivery'];
		}elseif(isset($this->request->post['shipping_method'])){
			$json['weekend_delivery'] = 0;
		}else{
			$json['weekend_delivery'] = $json['dpd_orders']['weekend_delivery'];
		}
		
		if(isset($this->request->post['condition'])){
			$json['condition'] = $this->request->post['condition'];
		}elseif(isset($this->request->post['shipping_method'])){
			$json['condition'] = 0;
		}else{
			$json['condition'] = $json['dpd_orders']['temperature'];
		}
		
		if(isset($this->request->post['loading_unloading'])){
			$json['loading_unloading'] = $this->request->post['loading_unloading'];
		}elseif(isset($this->request->post['shipping_method'])){
			$json['loading_unloading'] = 0;
		}else{
			$json['loading_unloading'] = $json['dpd_orders']['loading_unloading'];
		}
		
		if(isset($this->request->post['return_doc'])){
			$json['return_doc'] = $this->request->post['return_doc'];
		}elseif(isset($this->request->post['shipping_method'])){
			$json['return_doc'] = 0;
		}else{
			$json['return_doc'] = $json['dpd_orders']['return_doc'];
		}
		
		if(isset($this->request->post['wait_address'])){
			$json['wait_address'] = $this->request->post['wait_address'];
		}elseif(isset($this->request->post['shipping_method'])){
			$json['wait_address'] = 0;
		}else{
			$json['wait_address'] = $json['dpd_orders']['wait_address'];
		}
		
		if(isset($this->request->post['order_mail'])){
			$json['order_mail'] = $this->request->post['order_mail'];
		}else{
			$json['order_mail'] = $json['dpd_orders']['order_mail'];
		}
		
		/* Статус заказа */
		if($order_info['order_status_id'] == 5){
			$json['paid'] = true;
		}else{
			$json['paid'] = false;
		}
		
		/* Товары */
		$productsPrice = 0;
		$order_npp_value = 0;
		$declared_value = 0;
		$productsPriceForItem = 0;
		
		if (isset($this->request->post['product'])) {

			foreach ($this->request->post['product'] as $key => $product) {
				$info_product_dpd = $this->db->query("SELECT * FROM " . DB_PREFIX . "product WHERE product_id = '" . $product['product_id'] . "'");
					
				if($info_product_dpd->row['length'] > 0){
					$length = $info_product_dpd->row['length'];
				}else{
					$length = $this->config->get('shipping_dpd_length');
				}
								
				if($info_product_dpd->row['width'] > 0){
					$width = $info_product_dpd->row['width'];
				}else{
					$width = $this->config->get('shipping_dpd_width');
				}
								
				if($info_product_dpd->row['height'] > 0){
					$height = $info_product_dpd->row['height'];
				}else{
					$height = $this->config->get('shipping_dpd_height');
				}
								
				if($info_product_dpd->row['weight'] > 0){
					$weight = $info_product_dpd->row['weight'];
				}else{
					$weight = $this->config->get('shipping_dpd_weight');
				}
						
				$json['order_products'][] = array(
					'product_id' 		=> $product['product_id'],
					'order_product_id'  => $product['order_product_id'],
					'NAME'       		=> $product['name'],
					'option'     		=> $this->model_sale_order->getOrderOptions($order_id, $product['order_product_id']),
					'QUANTITY'   		=> $product['quantity'],
					'PRICE'      		=> round($product['price'],2),
					'VAT_RATE' 			=> $product['VAT_RATE'] != 0 ? $product['VAT_RATE'] : 'Без НДС',
					'order_npp_value'   => isset($product['order_npp_value']) ? round($product['order_npp_value'],2) : round($product['price'],2),
					'declared_value'    => isset($product['declared_value']) ? round($product['declared_value'],2) : round($product['price'],2),
					'total'     		=> $product['price']*$product['quantity'],
					'WEIGHT'   			=> $weight, 
					'DIMENSIONS' => [
					'LENGTH' => $length,
					'WIDTH'  => $width, 
					'HEIGHT' => $height, 
					]
				);
				
				if(isset($this->request->post['order_npp_check'])){
					$json['order_npp_check'] = true;
					if(isset($product['order_npp_value'])){
						$order_npp_value += round($product['order_npp_value'],2)*$product['quantity'];
					}else{
						$order_npp_value += round($product['price'],2)*$product['quantity'];
					}
				}else{
					$json['order_npp_check'] = false;
				}
				
				$productsPrice += round($product['price'],2)*$product['quantity'];
				
				
				if(isset($this->request->post['declared_check'])){
					$json['declared_check'] = true;
					if(isset($product['declared_value'])){
						$declared_value += round($product['declared_value'],2)*$product['quantity'];
						$productsPriceForItem += round($product['declared_value'],2)*$product['quantity'];
					}else{
						$declared_value += round($product['price'],2)*$product['quantity'];
						$productsPriceForItem += round($product['price'],2)*$product['quantity'];
					}
				}else{
					$json['declared_check'] = false;
					$productsPriceForItem += round($product['price'],2)*$product['quantity'];
				}
			}
			
			$json['total_product_price'] = $this->currency->format($this->tax->calculate($productsPrice, 0, $this->config->get('config_tax')), $order_info['currency_code'], $order_info['currency_value']);
		
			$json['productsPrice'] = $productsPrice;
		}elseif(!empty($json['dpd_orders']['products'])){
			/* Товары */
			$productsPrice = 0;
			$order_npp_value = 0;
			$declared_value = 0;
			$productsPriceForItem = 0;
					
			foreach($json['dpd_orders']['products'] as $product){
				$info_product_dpd = $this->db->query("SELECT * FROM " . DB_PREFIX . "product WHERE product_id = '" . $product['product_id'] . "'");
					
				if($info_product_dpd->row['length'] > 0){
					$length = $info_product_dpd->row['length'];
				}else{
					$length = $this->config->get('shipping_dpd_length');
				}
							
				if($info_product_dpd->row['width'] > 0){
					$width = $info_product_dpd->row['width'];
				}else{
					$width = $this->config->get('shipping_dpd_width');
				}
						
				if($info_product_dpd->row['height'] > 0){
					$height = $info_product_dpd->row['height'];
				}else{
					$height = $this->config->get('shipping_dpd_height');
				}
							
				if($info_product_dpd->row['weight'] > 0){
					$weight = $info_product_dpd->row['weight'];
				}else{
					$weight = $this->config->get('shipping_dpd_weight');
				}
					
				$json['order_products'][] = array(
					'product_id' => $product['product_id'],
					'order_product_id' => $product['order_product_id'],
					'NAME'       => $product['NAME'],
					'option'     => $product['option'],
					'QUANTITY'   => $product['QUANTITY'],
					'VAT_RATE' 	 => $product['VAT_RATE'],
					'PRICE'      => round($product['PRICE'],2),
					'order_npp_value'   => round($product['order_npp_value'],2),
					'declared_value'    => round($product['declared_value'],2),
					'total'      => round($product['total'],2),
					'WEIGHT'   => $product['WEIGHT'], // вес, граммы,
					'DIMENSIONS' => $product['DIMENSIONS'],
				);
				
				if($json['dpd_orders']['order_npp_check']){
					$json['order_npp_check'] = true;
					if($product['order_npp_value'] > 0){
						$order_npp_value += round($product['order_npp_value'],2);
					}else{
						$order_npp_value += round($product['PRICE'],2)*$product['QUANTITY'];
					}
				}else{
					$json['order_npp_check'] = false;
				}
				
				$productsPrice += round($product['PRICE'],2)*$product['QUANTITY'];
				
				
				if($json['dpd_orders']['declared_check']){
					$json['declared_check'] = true;
					if($json['dpd_orders']['declared_value'] > 0){
						$declared_value += round($json['dpd_orders']['declared_value'],2);
						$productsPriceForItem += round($json['dpd_orders']['declared_value'],2);
					}else{
						$declared_value += round($product['PRICE'],2)*$product['QUANTITY'];
						$productsPriceForItem += round($product['PRICE'],2)*$product['QUANTITY'];
					}
				}else{
					$json['declared_check'] = false;
					$productsPriceForItem += round($product['PRICE'],2)*$product['QUANTITY'];
				}
			}
			
			$json['total_product_price'] = $this->currency->format($this->tax->calculate($productsPrice, 0, $this->config->get('config_tax')), $order_info['currency_code'], $order_info['currency_value']);
		
			$json['productsPrice'] = $productsPrice;
		}else{
			
			/* Товары */
			$productsPrice = 0;
			$order_npp_value = 0;
			$declared_value = 0;
			$productsPriceForItem = 0;
			$products = $this->model_sale_order->getOrderProducts($order_id);
				
			foreach ($products as $product) {
				$info_product_dpd = $this->db->query("SELECT * FROM " . DB_PREFIX . "product WHERE product_id = '" . $product['product_id'] . "'");
					
				if($info_product_dpd->row['length'] > 0){
					$length = $info_product_dpd->row['length'];
				}else{
					$length = $this->config->get('shipping_dpd_length');
				}
							
				if($info_product_dpd->row['width'] > 0){
					$width = $info_product_dpd->row['width'];
				}else{
					$width = $this->config->get('shipping_dpd_width');
				}
						
				if($info_product_dpd->row['height'] > 0){
					$height = $info_product_dpd->row['height'];
				}else{
					$height = $this->config->get('shipping_dpd_height');
				}
							
				if($info_product_dpd->row['weight'] > 0){
					$weight = $info_product_dpd->row['weight'];
				}else{
					$weight = $this->config->get('shipping_dpd_weight');
				}
				
				if ($this->config->get('config_tax') != 0) {
					$tax = $product['tax'] != 0 ? 20 : 0;
					$price = $product['tax'] + $product['price'];
				}else{
					$tax = 'Без НДС';
					$price = $product['price'];
				}
				
				$json['order_products'][] = array(
					'product_id' => $product['product_id'],
					'order_product_id' => $product['order_product_id'],
					'NAME'       => $product['name'],
					'option'     => $this->model_sale_order->getOrderOptions($order_id, $product['order_product_id']),
					'QUANTITY'   => $product['quantity'],
					'VAT_RATE' 	 => $tax,
					'PRICE'      => round($price,2),
					'order_npp_value'   => round($price,2),
					'declared_value'    => round($price,2),
					'total'      => round($product['total'],2),
					'WEIGHT'   => $weight, 
					'DIMENSIONS' => [
					'LENGTH' => $length, 
					'WIDTH'  => $width, 
					'HEIGHT' => $height,
					]
				);
				
				if($json['dpd_orders']['order_npp_check']){
					$json['order_npp_check'] = true;
					if($json['dpd_orders']['order_npp_value'] > 0){
						$order_npp_value += round($json['dpd_orders']['order_npp_value'],2);
					}else{
						$order_npp_value += round($price,2)*$product['quantity'];
					}
				}else{
					$json['order_npp_check'] = false;
				}
				
				$productsPrice += round($price,2)*$product['quantity'];
				
				
				if($json['dpd_orders']['declared_check']){
					$json['declared_check'] = true;
					if($json['dpd_orders']['declared_value'] > 0){
						$declared_value += round($json['dpd_orders']['declared_value']);
						$productsPriceForItem += round($json['dpd_orders']['declared_value'],2);
					}else{
						$declared_value += round($price,2)*$product['quantity'];
						$productsPriceForItem += round($price,2)*$product['quantity'];
					}
				}else{
					$json['declared_check'] = false;
					$productsPriceForItem += round($price,2)*$product['quantity'];
				}
			}
			
			$json['total_product_price'] = $this->currency->format($this->tax->calculate($productsPrice, 0, $this->config->get('config_tax')), $order_info['currency_code'], $order_info['currency_value']);
		
			$json['productsPrice'] = $productsPrice;
		}
		
		/* Группа покупателя */
		$personeTypeId = $order_info['customer_group_id'];
				 
		/* Платёжная система */
		if($this->config->get('shipping_dpd_comission_for_product_'.$personeTypeId)){
			if($this->config->get('shipping_dpd_bind_payment_' . $personeTypeId)){
				if(is_array($this->config->get('shipping_dpd_bind_payment_' . $personeTypeId))){
					$i = 1;
					foreach($this->config->get('shipping_dpd_bind_payment_' . $personeTypeId) as $key => $mthd){	
						$COMMISSION_NPP_PAYMENT[$personeTypeId][$i] = $mthd;
						$i++;
					}
				}else{
					if($this->config->get('shipping_dpd_bind_payment_' . $personeTypeId)){
						$COMMISSION_NPP_PAYMENT[$personeTypeId][] = $this->config->get('shipping_dpd_bind_payment_' . $personeTypeId);
					}
				}
			}
		}else{
			$COMMISSION_NPP_PAYMENT[$personeTypeId][] = array();
		}
		
		if($this->config->get('shipping_dpd_comission_for_collection_'.$personeTypeId)){
			$COMMISSION_NPP_CHECK = [
				$personeTypeId => $this->config->get('shipping_dpd_comission_for_collection_'.$personeTypeId) ? true : false,
			];
		}else{
			$COMMISSION_NPP_CHECK = [];
		}
				
		if($this->config->get('shipping_dpd_comission_for_product_'.$personeTypeId)){
			$COMMISSION_NPP_PERCENT = [
				$personeTypeId => $this->config->get('shipping_dpd_comission_for_product_'.$personeTypeId),
			];
		}else{
			$COMMISSION_NPP_PERCENT = [];
		}
				
		if($this->config->get('shipping_dpd_min_sum_comission_'.$personeTypeId)){
			$COMMISSION_NPP_MINSUM = [
				$personeTypeId => $this->config->get('shipping_dpd_min_sum_comission_'.$personeTypeId),
			];
		}else{
			$COMMISSION_NPP_MINSUM = [];
		}
				
		if($this->config->get('shipping_dpd_not_payment_'.$personeTypeId)){
			$COMMISSION_NPP_DEFAULT = [
				$personeTypeId => $this->config->get('shipping_dpd_not_payment_'.$personeTypeId),
			];
		}else{
			$COMMISSION_NPP_DEFAULT = [];
		}
		
		require DIR_SYSTEM . 'dpd/src/autoload.php';
		
		$options = array(
			'SOURCE_NAME'					=> 'OpenCart',
			'UPLOAD_DIR'					=> DIR_IMAGE . 'dpd/',
			'KLIENT_NUMBER'   				=> $this->config->get('shipping_dpd_russian_number'),
			'KLIENT_KEY'      				=> $this->config->get('shipping_dpd_russian_auth'),
			'KLIENT_CURRENCY' 				=> $order_info['currency_code'] ? $order_info['currency_code'] : $this->config->get('shipping_dpd_russian_currency'),
			'API_DEF_COUNTRY' 				=> $this->config->get('shipping_dpd_account_default') ? $this->config->get('shipping_dpd_account_default') : 'RU',
			'IS_TEST'         				=> $this->config->get('shipping_dpd_test') ? true : false,
			'KLIENT_NUMBER_KZ' 				=> $this->config->get('shipping_dpd_kazahstan_number'),
			'KLIENT_KEY_KZ'  				=> $this->config->get('shipping_dpd_kazahstan_auth'),
			'KLIENT_CURRENCY_KZ' 			=> $order_info['currency_code'] ? $order_info['currency_code'] : $this->config->get('shipping_dpd_kazahstan_currency'),
			'KLIENT_NUMBER_BY' 				=> $this->config->get('shipping_dpd_belarus_number'),
			'KLIENT_KEY_BY'   				=> $this->config->get('shipping_dpd_belarus_auth'),
			'KLIENT_CURRENCY_BY' 			=> $order_info['currency_code'] ? $order_info['currency_code'] : $this->config->get('shipping_dpd_belarus_currency'),
			'WEIGHT' 						=> $this->config->get('shipping_dpd_weight'),
			'LENGTH'						=> $this->config->get('shipping_dpd_length'),
			'WIDTH'  						=> $this->config->get('shipping_dpd_width'),
			'HEIGHT' 						=> $this->config->get('shipping_dpd_height'),
			'TARIFF_OFF' 					=> $this->config->get('shipping_dpd_not_calculate'),
			'DEFAULT_TARIFF_CODE'			=> $this->config->get('shipping_dpd_tariff_default'),
			'DEFAULT_TARIFF_THRESHOLD'		=> $this->config->get('shipping_dpd_max_for_default'),
			'COMMISSION_NPP_CHECK'      	=> $COMMISSION_NPP_CHECK,
			'COMMISSION_NPP_PERCENT'      	=> $COMMISSION_NPP_PERCENT,
			'COMMISSION_NPP_MINSUM'      	=> $COMMISSION_NPP_MINSUM,
			'COMMISSION_NPP_PAYMENT'		=> $COMMISSION_NPP_PAYMENT,
			'COMMISSION_NPP_DEFAULT'		=> $COMMISSION_NPP_DEFAULT,
		);
		
		$options = array_merge($options, [
			'DB' => [
				'DSN'      => 'mysql:host=' . DB_HOSTNAME . ';dbname=' . DB_DATABASE,
				'USERNAME' => DB_USERNAME,
				'PASSWORD' => DB_PASSWORD,
				'DRIVER'   => null,
				'PDO'      => null,
			]
		]);
				
		$config  = new \Ipol\DPD\Config\Config($options);
		$shipment = new \Ipol\DPD\Shipment($config);
		
		$shipment->setSelfPickup($json['selfpickup']);
		$shipment->setSelfDelivery($json['selfdelivery']);
				
		$params['SelfPickup'] = $json['selfpickup'];
		
		/* Дата отгрузки */
		if(!empty($this->request->post['date_shipment'])){
			if(date('Y-m-d', strtotime($this->request->post['date_shipment'])) == true){
				$pickupDate = date('Y-m-d', strtotime($this->request->post['date_shipment']));
				$json['date_shipment'] = date('d.m.Y', strtotime($this->request->post['date_shipment']));
			}else{
				$pickupDate = date("Y-m-d");
				$json['date_shipment'] = date("d.m.Y");
			}
		}elseif($json['dpd_orders']['date_shipment'] !== '0000-00-00 00:00:00'){
			list($date_shipment, $time_shipment) = explode(' ', $json['dpd_orders']['date_shipment']);
			$pickupDate = date('Y-m-d', strtotime($date_shipment));
			$json['date_shipment'] = date('d.m.Y', strtotime($date_shipment));
		}else{
			$json['date_shipment'] = '';
			$pickupDate = '';
		}
		
		$table   = \Ipol\DPD\DB\Connection::getInstance($config)->getTable('location');
		
		/* Город отправителя */
		if(isset($this->request->post['city_id_sender'])){
			$city_dpd_sender = $table->find([
				'where' => 'CITY_ID = :city_id',
				'limit' => '0,1',
				'bind'  => [
					':city_id' => $this->request->post['city_id_sender']
				]
			])->fetchAll();
		}else{
			$city_dpd_sender = $table->find([
				'where' => 'CITY_ID = :city_id',
				'limit' => '0,1',
				'bind'  => [
					':city_id' => $json['dpd_orders']['city_sender_id']
				]
			])->fetchAll();
		}
		
		if($city_dpd_sender){
					
			/* Город */
			$json['city_sender'] = $city_dpd_sender[0]['COUNTRY_NAME'] . ', ' . $city_dpd_sender[0]['CITY_NAME'];
			$json['city_id_sender'] = $city_dpd_sender[0]['CITY_ID'];
				
			/* Список терминалов отправителя */
			
			$json['terminal_sender_arr'] = $this->getTerminalSender($json['city_id_sender']);
		}
		
		/* Город получателя */
		if(isset($this->request->post['city_id_rec'])){
			$city_dpd_rec = $table->find([
				'where' => 'CITY_ID = :city_id',
				'limit' => '0,1',
				'bind'  => [
					':city_id' => $this->request->post['city_id_rec']
				]
			])->fetchAll();
		}else{
			$city_dpd_rec = $table->find([
				'where' => 'CITY_ID = :city_id',
				'limit' => '0,1',
				'bind'  => [
					':city_id' => $json['dpd_orders']['city_rec_id']
				]
			])->fetchAll();
		}
		
		if($city_dpd_rec){
			/* Город */
			$json['city_rec'] = $city_dpd_rec[0]['COUNTRY_NAME'] . ', ' . $city_dpd_rec[0]['CITY_NAME'];
			$json['city_id_rec'] = $city_dpd_rec[0]['CITY_ID'];
				
		}
		
		if($city_dpd_rec){
			$shipment->setReceiver($city_dpd_rec[0]['COUNTRY_NAME'],$city_dpd_rec[0]['REGION_NAME'], $city_dpd_rec[0]['CITY_NAME']);
			$params['receiver'] = $city_dpd_rec[0]['COUNTRY_NAME'] . $city_dpd_rec[0]['REGION_NAME'] . $city_dpd_rec[0]['CITY_NAME'];
		}else{
			$params['receiver'] = 'Россия' . 'Москва' . 'Москва';
			$shipment->setReceiver('Россия', 'Москва', 'Москва');
		}
		
		if($city_dpd_sender){
			$shipment->setSender($city_dpd_sender[0]['COUNTRY_NAME'],$city_dpd_sender[0]['REGION_NAME'], $city_dpd_sender[0]['CITY_NAME']);
			$params['sender'] = $city_dpd_sender[0]['COUNTRY_NAME'] . $city_dpd_sender[0]['REGION_NAME'] . $city_dpd_sender[0]['CITY_NAME'];
		}else{
			$params['sender'] = 'Россия' . 'Москва' . 'Москва';
			$shipment->setSender('Россия', 'Москва', 'Москва');
		}
		
		if($json['declared_check'] == true){
			$shipment->setDeclaredValue(true);
			$params['DeclaredValue'] = $shipment->getDeclaredValue();
		}
		
		$shipment->setItems($json['order_products'], $productsPriceForItem);
		
		/* Цена */
		$shipment->setPrice($productsPriceForItem);
		$params['price'] = $shipment->getPrice();
		
		if(isset($this->request->post['weight_shipment'])){
			$shipment->setWeight($this->request->post['weight_shipment']);
		}else{
			$shipment->setWeight($json['dpd_orders']['weight']);
		}
		
		$shipment->setHeight($json['height']);
		$shipment->setHeight($json['length']);
		$shipment->setHeight($json['width']);
		
		/* Габариты & вес */
		$params['Width'] =  $shipment->getWidth();
		$params['Height'] =  $shipment->getHeight();
		$params['Length'] =  $shipment->getLength();
		$params['Weight'] =  $shipment->getWeight();
		
		if($this->config->get('shipping_dpd_comission_for_product_'.$personeTypeId) && $json['order_npp_check'] == true){
			if(isset($order_info['payment_code'])){
				$payment_code = $order_info['payment_code'];
				if($this->config->get('shipping_dpd_bind_payment_' . $personeTypeId)){
					if(is_array($this->config->get('shipping_dpd_bind_payment_' . $personeTypeId))){
						foreach($this->config->get('shipping_dpd_bind_payment_' . $personeTypeId) as $mthd){
							if($mthd == $payment_code){
								$paySystemId = $payment_code;
								$shipment->setPaymentMethod($personeTypeId, $paySystemId);
							}
						}
					}else{
						if($this->config->get('shipping_dpd_bind_payment_' . $personeTypeId) == $payment_code){
							$paySystemId = $payment_code;
							$shipment->setPaymentMethod($personeTypeId, $paySystemId);
						}
					}
				}
			}
		}
		
		/* Наложенный платёж */
		$params['npp_payment'] = $shipment->isPaymentOnDelivery();
				
		# Currency
		$params['currencyTo'] = $order_info['currency_code'];
		$params['currencyFrom'] = $order_info['currency_code'];
		
		$converter =  new \Ipol\DPD\Currency\Converter();		
		$calc = $shipment->calculator();
		$calc->setCurrencyConverter($converter);
		
		if(isset($this->request->post['shipping_method'])){
			$params['shipping_method'] = $this->request->post['shipping_method'];
			$json['shipping_method'] = $this->request->post['shipping_method'];
		}else{
			$params['shipping_method'] = $json['dpd_orders']['shipping_method'];
			$json['shipping_method'] =  $json['dpd_orders']['shipping_method'];
		}
		
		$params['SelfDelivery'] = $json['selfdelivery'];
		
		$json['params'] = $params;
		
		if(empty($json['weight'])){
			$json['not']['calculate'] = "Не указан вес!";
		}
					
		if(empty($json['height']) or empty($json['length']) or empty($json['width'])){
			$json['not']['calculate'] =  "Не верно указаны габариты!";
		}
		
		if(($json['volume'] == '') or ($json['volume'] == 0)){
			$json['not']['calculate'] =  "Не верно указаны габариты!";
		}
		
		try{
			if($json['selfdelivery'] == 0){
				$cache = 'dpd.shipping.calculateDoor.' . md5(implode('', $params));
				if (! $json['tariff'] = $this->cache->get($cache)) {
					$json['tariff'] = $calc->calculateWithTariff($params['shipping_method'],$params['currencyFrom']);
					$this->cache->set($cache, $json['tariff']);
				}
			}elseif($json['selfdelivery'] == 1){
				$cache = 'dpd.shipping.calculateTerminal.' . md5(implode('', $params));
				if (! $json['tariff'] = $this->cache->get($cache)) {
					$json['tariff'] = $calc->calculateWithTariff($params['shipping_method'],$params['currencyFrom']);
					$this->cache->set($cache, $json['tariff']);
				}
				
				/* Список терминалов отправителя */
				$json['terminal_rec_arr'] = \Ipol\DPD\DB\Connection::getInstance($config)->getTable('terminal')->findModels([
					'where' => 'LOCATION_ID = :location',
					'bind'  => ['location' => $shipment->getReceiver()['CITY_ID']],
				]);

				$json['terminal_rec_arr'] = array_filter($json['terminal_rec_arr'], function($terminal) use ($shipment) {
					return $terminal->checkShipment($shipment);
				});
			}
			
			/* Наценка */
			$json['price_delivery_markup'] = $json['tariff']['COST'];
			$json['price_delivery'] = $json['tariff']['COST'];
					
			if($json['selfdelivery'] == 0){
				if(($this->config->get('shipping_dpd_markup_type_door') == 1) && ($this->config->get('shipping_dpd_markup_door') > 0)){
					$percent = $json['tariff']['COST']*$this->config->get('shipping_dpd_markup_door')/100;
					$json['price_delivery_markup'] = $percent + $json['tariff']['COST'];
				}elseif(($this->config->get('shipping_dpd_markup_type_door') == 0) && ($this->config->get('shipping_dpd_markup_door') > 0)){
					$json['price_delivery_markup'] = $this->config->get('shipping_dpd_markup_door') + $json['tariff']['COST'];
				}
			}elseif($json['selfdelivery'] == 1){
				if(($this->config->get('shipping_dpd_markup_type_terminal') == 1) && ($this->config->get('shipping_dpd_markup_terminal') > 0)){
					$percent = $json['tariff']['COST']*$this->config->get('shipping_dpd_markup_terminal')/100;
					$json['price_delivery_markup'] = $percent + $json['tariff']['COST'];
				}elseif(($this->config->get('shipping_dpd_markup_type_terminal') == 0) && ($this->config->get('shipping_dpd_markup_terminal') > 0)){
					$json['price_delivery_markup'] = $this->config->get('shipping_dpd_markup_terminal') + $json['tariff']['COST'];
				}
			}
			
			$json['total_declared_product_price'] = $this->currency->format($this->tax->calculate($declared_value, 0, $this->config->get('config_tax')), $order_info['currency_code'], $order_info['currency_value']);
			
			$info_shipping_total = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_total WHERE order_id = '" . $order_id . "' AND code = 'shipping'");
			$json['price_delivery_total'] = $this->currency->format($this->tax->calculate(round($info_shipping_total->row['value'],2), 0, $this->config->get('config_tax')), $order_info['currency_code'], $order_info['currency_value']);
			
			if(isset($this->request->post['quantity_delivery']) && isset($this->request->post['declared_delivery']) && ($json['payment_method_delivery'] !== 'ОУП')){
				$json['declared_value']	= $declared_value + ($this->request->post['quantity_delivery']*$this->request->post['declared_delivery']);
				$json['total_declared_product_price'] = $this->currency->format($this->tax->calculate($declared_value + ($this->request->post['quantity_delivery']*round($this->request->post['declared_delivery'])), 0, $this->config->get('config_tax')), $order_info['currency_code'], $order_info['currency_value']);
				$json['price_oc_delivery'] = $this->request->post['quantity_delivery']*$this->request->post['declared_delivery'];
			}else{
				$json['price_oc_delivery'] = $declared_value - $productsPrice;
				$json['declared_value']	= $declared_value;
			}
			
			if(isset($this->request->post['quantity_delivery']) && isset($this->request->post['npp_delivery'])){
				$json['price_declared']	= $this->request->post['quantity_delivery']*$this->request->post['npp_delivery'];
				$json['price_declared_delivery'] = round($this->request->post['npp_delivery'],2);
			}else{
				$json['price_declared_delivery'] = round($info_shipping_total->row['value'],2);
				$json['price_declared']	= round($info_shipping_total->row['value'],2);
			}
					
			if($json['payment_method_delivery'] == 'ОУП'){
				$json['order_npp_value'] = $order_npp_value;
				$json['total_npp_product_price'] = $this->currency->format($this->tax->calculate($order_npp_value, 0, $this->config->get('config_tax')), $order_info['currency_code'], $order_info['currency_value']);
			}else{
				$json['order_npp_value'] = $order_npp_value + $json['price_declared'];
				$json['total_npp_product_price'] = $this->currency->format($this->tax->calculate($order_npp_value + $json['price_declared'], 0, $this->config->get('config_tax')), $order_info['currency_code'], $order_info['currency_value']);
			}
			
			$json['price_delivery_markup'] = $this->currency->format($this->tax->calculate($json['price_delivery_markup'], 0, $this->config->get('config_tax')), $order_info['currency_code'], $order_info['currency_value']);
			
			$data['entry_order_price_delivery'] = $this->language->get('entry_order_price_delivery');
			$data['entry_order_price_npp'] = $this->language->get('entry_order_price_npp');
			
			$json['price_delivery_top'] = $data['entry_order_price_delivery'] . ' ' . $json['price_delivery_markup'];
			$json['price_delivery_markup_top'] = $data['entry_order_price_npp'] . ' ' . $json['price_delivery_total'];
		
			$order = \Ipol\DPD\DB\Connection::getInstance($config)->getTable('order')->getByOrderId($order_id, true);
			
			if(isset($order['INVOICE_FILE'])){
				if(file_exists(DIR_IMAGE . '..' . $order['INVOICE_FILE'])){
					$json['invoice'] = $order['INVOICE_FILE'];
				}
			}
			
			if(isset($order['LABEL_FILE'])){
				if(file_exists(DIR_IMAGE . '..' . $order['LABEL_FILE'])){
					$json['label'] = $order['LABEL_FILE'];
				}
			}
			
			if(isset($this->request->post['invoice'])){
				//try{
					$invoice = $order->dpd()->getInvoiceFile();
					if($invoice->isSuccess() == true){
						$order->save();
					}else{
						$json['err_inv'] = $invoice->getErrorMessages();
					}
				//}catch (\Exception $e) {
				//	$json['err_inv'] = $e->getMessage();
				//}
			}
			
			if(isset($this->request->post['label'])){
				//try{
					if($this->request->post['label_count'] > 0){
						$label_count = $this->request->post['label_count'];
					}else{
						$label_count = 1;
					}
					
					$label_format = $this->request->post['label_format'];
					$label_format_size = $this->request->post['label_format_size'];
					
					$label = $order->dpd()->getLabelFile($label_count, $label_format, $label_format_size);
					
					if($label->isSuccess() == true){
						$order->save();
					}else{
						$json['err_label'] = $label->getErrorMessages();
					}
				//}catch (\Exception $e) {
					//$json['err_label'] = $e->getMessage();
				//}
			}
		
			/* Номер заявки */
			$order->orderId = $order_id;
						
			/* Статус */
			if(empty($dpd['status_dpd'])){
				$order->orderStatus = 'NEW';
			}else{
				$order->orderStatus = $dpd['status_dpd'];
			}

			/* указываем тариф отправки */
			$order->serviceCode = $json['shipping_method'];
						
			/* если не использовать объект отправки, так же необходимо указать вариант доставки */
			$order->serviceVariant = ['SELF_PICKUP' => $json['selfpickup'], 'SELF_DELIVERY' =>  $json['selfdelivery']];
					
			/* Дата и время забора transit_interval */
			$order->pickupDate = $pickupDate;
			$order->pickupTimePeriod = $json['transit_interval'];
						
			/* Интервал времени доставки груза */
			$order->deliveryTimePeriod = $json['time_interval'];
						
			/* Способ оплаты доставки */
			$order->paymentType = $json['payment_method_delivery'];
						
			/* Опции */
			
			$order->cargoRegistered = 'N';
			$order->dvd = ($json['weekend_delivery']) ? 'Y' : 'N';
			$order->trm = ($json['condition']) ? 'Y' : 'N';
			$order->prd = ($json['loading_unloading']) ? 'Y' : 'N';
			$order->vdo = ($json['return_doc']) ? 'Y' : 'N';
				
			if($json['wait_address'] != 0){
				$order->ogd = $json['wait_address'];
			}
			
			$order->currency = $params['currencyFrom'];
						
			/* Наложенный */
				if($json['order_npp_check'] == 1){
				$order->npp = "Y";
			}else{
				$order->npp = "N";
			}
			
			if($json['declared_check'] == 1){
				$order->useCargoValue = 'Y'; 
			}else{
				$order->useCargoValue = 'N'; 
			}
			
			$order->cargoCategory = $json['content_sender'];
			
			foreach($order->unitLoads as $row){
				if($row['ID'] == 'DELIVERY'){
					$json['tax_delivery'] = $row['VAT'];
				}
			}
			
			if(isset($this->request->post['tax_delivery'])){
				$json['tax_delivery'] = $this->request->post['tax_delivery'];
			}elseif(!isset($json['tax_delivery'])){
				$json['tax_delivery'] = $this->config->get('config_tax');
			}
			
			foreach($json['order_products'] as $product){
				$npp_and_cargo[] = array(
					'ID'		=> $product['product_id'],
					'NAME'		=> $product['NAME'],
					'QUANTITY'	=> $product['QUANTITY'],
					'VAT_RATE'	=> $product['VAT_RATE'],
					'CARGO'		=> $product['declared_value'],
					'NPP'		=> $product['order_npp_value'],
				);
			}
			
			$order->unitLoads = $npp_and_cargo;
			
			$order->cargoNumPack = $json['quantity_places'];

			/* Данные отправителя */
			$order->senderName = $json['contact_face'];
			$order->senderFio = $json['name_company'];
			$order->senderPhone = $json['phone_sender'];
			$order->senderEmail = $json['email_sender'];
			
			if(($json['pass'] == 1) or ($json['pass'] == 'on')){
				$order->senderNeedPass = 'Y';
			}else{
				$order->senderNeedPass = 'N';
			}
				
			if(empty($json['contact_face']) or empty($json['name_company']) or empty($json['phone_sender']) or empty($json['email_sender'])){
				$json['not']['sender'] = 'Заполните данные отправителя';
			}
						
			if($json['selfpickup'] == false){
				if(empty($json['street_sender'])){
					$json['not']['sender'] = 'Заполните данные отправителя';
				}
				$order->senderStreet = $json['street_sender'];
				$order->senderStreetabbr = $json['ab_street_sender'];
				$order->senderKorpus = $json['corp_sender'];
				$order->senderHouse = $json['house_sender'];
				$order->senderStr = $json['structure_sender'];
				$order->senderVlad = $json['poss_sender'];
				if(!empty($json['office_sender'])){
					$order->senderOffice = $json['office_sender'];
				}else{
					$order->senderFlat = $json['apart_sender'];
				}
			}else{
				$order->senderTerminalCode = $json['terminal_sender'];
			}
					
				
			/* Данные получателя */
			$order->receiverName = $json['contact_face_rec'];
			$order->receiverFio =  $json['name_company_rec'];
			$order->receiverPhone =  $json['phone_rec'];
			$order->receiverEmail =  $json['email_rec'];
			
			if(($json['pass_rec'] == 1) or ($json['pass_rec'] == 'on')){
				$order->receiverNeedPass = 'Y';
			}else{
				$order->receiverNeedPass = 'N';
			}
				
			if(empty($json['contact_face_rec']) or empty($json['name_company_rec']) or empty($json['phone_rec']) or empty($json['email_rec'])){
				$json['not']['receiver'] = 'Заполните данные получателя';
			}
					
			$order->receiverComment = $json['comment_rec'];
				
			if($json['selfdelivery'] == false){
				if(empty($json['street_rec'])){
					$json['not']['receiver'] = 'Заполните данные получателя';
				}
				$order->receiverStreet = $json['street_rec'];
				$order->receiverStreetabbr = $json['ab_street_rec'];
				$order->receiverKorpus = $json['corp_rec'];
				$order->receiverHouse = $json['house_rec'];
				$order->receiverStr = $json['structure_rec'];
				$order->receiverVlad = $json['poss_rec'];
				if(!empty($json['office_rec'])){
					$order->receiverOffice = $json['office_rec'];
				}else{
					$order->receiverFlat = $json['apart_rec'];
				}
			}else{
				$order->receiverTerminalCode = $json['terminal_rec'];
			}
					
			$order->setShipment($shipment);
			
			$check_status = $order->dpd()->checkStatus();
			if($check_status->isSuccess() == true){
				if(is_object($check_status)) {
					$fromOrder = (array)$check_status;
					$fromOrder = array_values($fromOrder);
					if(!empty($fromOrder[2]['ORDER_NUM'])){
						$this->db->query("UPDATE `" . DB_PREFIX . "dpd_order` SET dpd_id = '" . $fromOrder[2]['ORDER_NUM'] . "', status_dpd = '" . $fromOrder[2]['ORDER_STATUS'] . "' WHERE order_id = '" . $order_id . "'");
						$json['dpd_orders']['status_dpd'] = $fromOrder[2]['ORDER_STATUS'];
						$json['dpd_orders']['dpd_id'] = $fromOrder[2]['ORDER_NUM'];
					}
				}
			}
			
			/* Стастус заказа */
			$dpd_statuses = \Ipol\DPD\DB\Order\Model::StatusList($config);
			
			foreach($dpd_statuses as $k => $row){
				if($json['dpd_orders']['status_dpd'] == $k){
					$json['status_dpd'] = $row;
				}
			}
		} catch (\Exception $e) {
			$json['price_delivery_markup'] = $this->currency->format($this->tax->calculate(0, 0, $this->config->get('config_tax')), $order_info['currency_code'], $order_info['currency_value']);

			$info_shipping_total = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_total WHERE order_id = '" . $order_id . "' AND code = 'shipping'");
			$json['price_delivery_total'] = $this->currency->format($this->tax->calculate(round($info_shipping_total->row['value'],2), 0, $this->config->get('config_tax')), $order_info['currency_code'], $order_info['currency_value']);
			if(!empty($json['not']['calculate'])){
				$json['not']['calculate'] = $json['not']['calculate'];
			}else{
				$json['not']['calculate'] = $e->getMessage();
			}
		}
		
		if(isset($this->request->post['type'])){			
			try{
				
				if(!empty($pickupDate)){
					if(strtotime($pickupDate) < (date('Y-m-d'))){
						$json['not']['cargo'] = "Не верно указана дата отгрузки!";
					}
				}else{
					$json['not']['cargo'] = "Не указана дата отгрузки!";
				}
				
				if(empty($json['weight'])){
					$json['not']['cargo'] = "Не указан вес!";
				}
					
				if(empty($json['height']) or empty($json['length']) or empty($json['width'])){
					$json['not']['cargo'] = "Не верно указаны габариты!";
				}
				
				if(empty($json['quantity_places'])){
					$json['not']['cargo'] = "Не указано количество грузомест!";
				}
				
				if(empty($json['content_sender'])){
					$json['not']['cargo'] = "Не указано содержимое отправки!";
				}
				
				if(empty($json['contact_face'])){
					$json['not']['cargo'] = "Не указано контактное лицо отправителя!";
				}
				
				if(empty($json['name_company'])){
					$json['not']['cargo'] = "Не указано имя отправителя!";
				}
				
				if(empty($json['phone_sender'])){
					$json['not']['cargo'] = "Не указан телефон отправителя!";
				}
				
				if(empty($json['content_sender'])){
					$json['not']['email_sender'] = "Не указан Email отправителя!";
				}
				
				if(empty($json['contact_face'])){
					$json['not']['cargo'] = "Не указано контактное лицо получателя!";
				}
				
				if(empty($json['name_company'])){
					$json['not']['cargo'] = "Не указано имя получателя!";
				}
				
				if(empty($json['phone_sender'])){
					$json['not']['cargo'] = "Не указан телефон получателя!";
				}
				
				if(empty($json['content_sender'])){
					$json['not']['email_sender'] = "Не указан Email получателя!";
				}
				
				if(!isset($json['not'])){
					
					if(($json['val_cargo'] == 1) && ($json['declared_check'] == false)){
						$json['not']['cargo'] = 'Ценный груз должен быть с "Объявленной ценностью"!';
					}
					
					if(($json['order_npp_check'] == true) && ($json['declared_check'] == false)){
						$json['not']['cargo'] = 'Наложенный платеж должен быть с "Объявленной ценностью"!';
					}
					
					$check_status = $order->dpd()->checkStatus();
					if(is_object($check_status)) {
						$check_status = (array)$check_status;
						$check_status = array_values($check_status);
					}
					
					if(($check_status[2]['ORDER_STATUS'] == 'NEW') or ($dpd['status_dpd'] == 'Canceled') or ($check_status[2]['ORDER_STATUS'] == 'OrderError')){
						$order->dpd()->setCurrencyConverter($converter);
						$result = $order->dpd()->create();
						if($result->isSuccess() == true){
							$order->save();
							if(is_object($result)) {
								$result = (array)$result;
								$result = array_values($result);
							}
							
							if(isset($result[2]['ORDER_STATUS'])){
								$json['status'] = $result[2]['ORDER_STATUS'];
							}
							
							if(empty($result[2])){
								$json['err']['order'] = 'Не известная ошибка!';
							}
							
							if(!empty($dpd)){
								$this->model_extension_shipping_dpd->editDpdOrder($json, $order_id);
							}else{
								$this->model_extension_shipping_dpd->addDpdOrder($json, $order_id);
							}
						}else{
							$errors = $result->getErrorMessages();
							$json['err']['order'] = $errors[0];
						}
						
					}elseif($check_status[2]['ORDER_STATUS']){
						$order->dpd()->setCurrencyConverter($converter);
						$result = $order->dpd()->cancel();
						if($result->isSuccess() == true){
							$order->save();
							if(is_object($result)) {
								$result = (array)$result;
								$result = array_values($result);
							}
							
							$json['status'] = 'Canceled';
							
							if(!empty($dpd)){
								$this->model_extension_shipping_dpd->editDpdOrder($json, $order_id);
							}else{
								$this->model_extension_shipping_dpd->addDpdOrder($json, $order_id);
							}
						}else{
							$errors = $result->getErrorMessages();
							$json['err']['order'] = $errors[0];
						}
					}else{
						$json['status'] = 'NEW';
						$json['err']['order'] = 'Не известная ошибка!';
					}
				}
			}catch (\Exception $e) {
				$json['not']['cargo'] = $json['not']['cargo'];
			}
		}
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));	
	}
	/* Рекалькулейт */

		
	public function info() {
		$this->load->model('sale/order');

		if (isset($this->request->get['order_id'])) {
			$order_id = $this->request->get['order_id'];
		} else {
			$order_id = 0;
		}

		$order_info = $this->model_sale_order->getOrder($order_id);

		if ($order_info) {
			$this->load->language('sale/order');

			$this->document->setTitle($this->language->get('heading_title'));



		/* dpd */
		$this->document->addStyle('view/javascript/dpd/dpd.css');
			
		$this->load->language('extension/shipping/dpd');
		$this->load->model('extension/shipping/dpd');
			
		if($this->config->get('shipping_dpd_button')){
			$data['button_dpd'] = 1;
		}else{
			$dpd_validate = $this->model_extension_shipping_dpd->getOrderDpd($order_id);
			if(!empty($dpd_validate)){
				$data['button_dpd'] = 1;
			}
		}
		/* dpd */

		
			$data['text_ip_add'] = sprintf($this->language->get('text_ip_add'), $this->request->server['REMOTE_ADDR']);
			$data['text_order'] = sprintf($this->language->get('text_order'), $this->request->get['order_id']);

			$url = '';

			if (isset($this->request->get['filter_order_id'])) {
				$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
			}

			if (isset($this->request->get['filter_customer'])) {
				$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_order_status'])) {
				$url .= '&filter_order_status=' . $this->request->get['filter_order_status'];
			}
			
			if (isset($this->request->get['filter_order_status_id'])) {
				$url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
			}
			
			if (isset($this->request->get['filter_total'])) {
				$url .= '&filter_total=' . $this->request->get['filter_total'];
			}

			if (isset($this->request->get['filter_date_added'])) {
				$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
			}

			if (isset($this->request->get['filter_date_modified'])) {
				$url .= '&filter_date_modified=' . $this->request->get['filter_date_modified'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$data['breadcrumbs'] = array();

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_home'),
				'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
			);

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('sale/order', 'user_token=' . $this->session->data['user_token'] . $url, true)
			);

			$data['shipping'] = $this->url->link('sale/order/shipping', 'user_token=' . $this->session->data['user_token'] . '&order_id=' . (int)$this->request->get['order_id'], true);
			$data['invoice'] = $this->url->link('sale/order/invoice', 'user_token=' . $this->session->data['user_token'] . '&order_id=' . (int)$this->request->get['order_id'], true);
			$data['edit'] = $this->url->link('sale/order/edit', 'user_token=' . $this->session->data['user_token'] . '&order_id=' . (int)$this->request->get['order_id'], true);
			$data['cancel'] = $this->url->link('sale/order', 'user_token=' . $this->session->data['user_token'] . $url, true);

			$data['user_token'] = $this->session->data['user_token'];

			$data['order_id'] = $this->request->get['order_id'];

			$data['store_id'] = $order_info['store_id'];
			$data['store_name'] = $order_info['store_name'];
			
			if ($order_info['store_id'] == 0) {
				$data['store_url'] = $this->request->server['HTTPS'] ? HTTPS_CATALOG : HTTP_CATALOG;
			} else {
				$data['store_url'] = $order_info['store_url'];
			}

			if ($order_info['invoice_no']) {
				$data['invoice_no'] = $order_info['invoice_prefix'] . $order_info['invoice_no'];
			} else {
				$data['invoice_no'] = '';
			}

			$data['date_added'] = date($this->language->get('date_format_short'), strtotime($order_info['date_added']));

			$data['firstname'] = $order_info['firstname'];
			$data['lastname'] = $order_info['lastname'];

			if ($order_info['customer_id']) {
				$data['customer'] = $this->url->link('customer/customer/edit', 'user_token=' . $this->session->data['user_token'] . '&customer_id=' . $order_info['customer_id'], true);
			} else {
				$data['customer'] = '';
			}

			$this->load->model('customer/customer_group');

			$customer_group_info = $this->model_customer_customer_group->getCustomerGroup($order_info['customer_group_id']);

			if ($customer_group_info) {
				$data['customer_group'] = $customer_group_info['name'];
			} else {
				$data['customer_group'] = '';
			}

			$data['email'] = $order_info['email'];
			$data['telephone'] = $order_info['telephone'];

			$data['shipping_method'] = $order_info['shipping_method'];
			$data['payment_method'] = $order_info['payment_method'];

			// Payment Address
			if ($order_info['payment_address_format']) {
				$format = $order_info['payment_address_format'];
			} else {
				$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
			}

			$find = array(
				'{firstname}',
				'{lastname}',
				'{company}',
				'{address_1}',
				'{address_2}',
				'{city}',
				'{postcode}',
				'{zone}',
				'{zone_code}',
				'{country}'
			);

			$replace = array(
				'firstname' => $order_info['payment_firstname'],
				'lastname'  => $order_info['payment_lastname'] . "  " . $order_info['telephone'],
				'company'   => $order_info['payment_company'],
				'address_1' => $order_info['payment_address_1'],
				'address_2' => $order_info['payment_address_2'],
				'city'      => $order_info['payment_city'],
				'postcode'  => $order_info['payment_postcode'],
				'zone'      => $order_info['payment_zone'],
				'zone_code' => $order_info['payment_zone_code'],
				'country'   => $order_info['payment_country']
			);

			$data['payment_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));

			// Shipping Address
			if ($order_info['shipping_address_format']) {
				$format = $order_info['shipping_address_format'];
			} else {
				$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
			}

			$find = array(
				'{firstname}',
				'{lastname}',
				'{company}',
				'{address_1}',
				'{address_2}',
				'{city}',
				'{postcode}',
				'{zone}',
				'{zone_code}',
				'{country}'
			);

			$replace = array(
				'firstname' => $order_info['shipping_firstname'],
				'lastname'  => $order_info['shipping_lastname'],
				'company'   => $order_info['shipping_company'],
				'address_1' => $order_info['shipping_address_1'],
				'address_2' => $order_info['shipping_address_2'],
				'city'      => $order_info['shipping_city'],
				'postcode'  => $order_info['shipping_postcode'],
				'zone'      => $order_info['shipping_zone'],
				'zone_code' => $order_info['shipping_zone_code'],
				'country'   => $order_info['shipping_country']
			);

			$data['shipping_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));

			// Uploaded files
			$this->load->model('tool/upload');

			$data['products'] = array();

			$products = $this->model_sale_order->getOrderProducts($this->request->get['order_id']);

			foreach ($products as $product) {
				$option_data = array();

				$options = $this->model_sale_order->getOrderOptions($this->request->get['order_id'], $product['order_product_id']);

				foreach ($options as $option) {
					if ($option['type'] != 'file') {
						$option_data[] = array(
							'name'  => $option['name'],
							'value' => $option['value'],
							'type'  => $option['type']
						);
					} else {
						$upload_info = $this->model_tool_upload->getUploadByCode($option['value']);

						if ($upload_info) {
							$option_data[] = array(
								'name'  => $option['name'],
								'value' => $upload_info['name'],
								'type'  => $option['type'],
								'href'  => $this->url->link('tool/upload/download', 'user_token=' . $this->session->data['user_token'] . '&code=' . $upload_info['code'], true)
							);
						}
					}
				}

				$data['products'][] = array(
					'order_product_id' => $product['order_product_id'],
					'product_id'       => $product['product_id'],
					'name'    	 	   => $product['name'],
					'model'    		   => $product['model'],
					'option'   		   => $option_data,
					'quantity'		   => $product['quantity'],
					'price'    		   => $this->currency->format($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $order_info['currency_code'], $order_info['currency_value']),
					'total'    		   => $this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value']),
					'href'     		   => $this->url->link('catalog/product/edit', 'user_token=' . $this->session->data['user_token'] . '&product_id=' . $product['product_id'], true)
				);
			}

			$data['vouchers'] = array();

			$vouchers = $this->model_sale_order->getOrderVouchers($this->request->get['order_id']);

			foreach ($vouchers as $voucher) {
				$data['vouchers'][] = array(
					'description' => $voucher['description'],
					'amount'      => $this->currency->format($voucher['amount'], $order_info['currency_code'], $order_info['currency_value']),
					'href'        => $this->url->link('sale/voucher/edit', 'user_token=' . $this->session->data['user_token'] . '&voucher_id=' . $voucher['voucher_id'], true)
				);
			}

			$data['totals'] = array();

			$totals = $this->model_sale_order->getOrderTotals($this->request->get['order_id']);

			foreach ($totals as $total) {
				$data['totals'][] = array(
					'title' => $total['title'],
					'text'  => $this->currency->format($total['value'], $order_info['currency_code'], $order_info['currency_value'])
				);
			}

			$data['comment'] = nl2br($order_info['comment']);

			$this->load->model('customer/customer');

			$data['reward'] = $order_info['reward'];

			$data['reward_total'] = $this->model_customer_customer->getTotalCustomerRewardsByOrderId($this->request->get['order_id']);

			$data['affiliate_firstname'] = $order_info['affiliate_firstname'];
			$data['affiliate_lastname'] = $order_info['affiliate_lastname'];

			if ($order_info['affiliate_id']) {
				$data['affiliate'] = $this->url->link('customer/customer/edit', 'user_token=' . $this->session->data['user_token'] . '&customer_id=' . $order_info['affiliate_id'], true);
			} else {
				$data['affiliate'] = '';
			}

			$data['commission'] = $this->currency->format($order_info['commission'], $order_info['currency_code'], $order_info['currency_value']);

			$this->load->model('customer/customer');

			$data['commission_total'] = $this->model_customer_customer->getTotalTransactionsByOrderId($this->request->get['order_id']);

			$this->load->model('localisation/order_status');

			$order_status_info = $this->model_localisation_order_status->getOrderStatus($order_info['order_status_id']);

			if ($order_status_info) {
				$data['order_status'] = $order_status_info['name'];
			} else {
				$data['order_status'] = '';
			}

			$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

			$data['order_status_id'] = $order_info['order_status_id'];

			$data['account_custom_field'] = $order_info['custom_field'];

			// Custom Fields
			$this->load->model('customer/custom_field');

			$data['account_custom_fields'] = array();

			$filter_data = array(
				'sort'  => 'cf.sort_order',
				'order' => 'ASC'
			);

			$custom_fields = $this->model_customer_custom_field->getCustomFields($filter_data);

			foreach ($custom_fields as $custom_field) {
				if ($custom_field['location'] == 'account' && isset($order_info['custom_field'][$custom_field['custom_field_id']])) {
					if ($custom_field['type'] == 'select' || $custom_field['type'] == 'radio') {
						$custom_field_value_info = $this->model_customer_custom_field->getCustomFieldValue($order_info['custom_field'][$custom_field['custom_field_id']]);

						if ($custom_field_value_info) {
							$data['account_custom_fields'][] = array(
								'name'  => $custom_field['name'],
								'value' => $custom_field_value_info['name']
							);
						}
					}

					if ($custom_field['type'] == 'checkbox' && is_array($order_info['custom_field'][$custom_field['custom_field_id']])) {
						foreach ($order_info['custom_field'][$custom_field['custom_field_id']] as $custom_field_value_id) {
							$custom_field_value_info = $this->model_customer_custom_field->getCustomFieldValue($custom_field_value_id);

							if ($custom_field_value_info) {
								$data['account_custom_fields'][] = array(
									'name'  => $custom_field['name'],
									'value' => $custom_field_value_info['name']
								);
							}
						}
					}

					if ($custom_field['type'] == 'text' || $custom_field['type'] == 'textarea' || $custom_field['type'] == 'file' || $custom_field['type'] == 'date' || $custom_field['type'] == 'datetime' || $custom_field['type'] == 'time') {
						$data['account_custom_fields'][] = array(
							'name'  => $custom_field['name'],
							'value' => $order_info['custom_field'][$custom_field['custom_field_id']]
						);
					}

					if ($custom_field['type'] == 'file') {
						$upload_info = $this->model_tool_upload->getUploadByCode($order_info['custom_field'][$custom_field['custom_field_id']]);

						if ($upload_info) {
							$data['account_custom_fields'][] = array(
								'name'  => $custom_field['name'],
								'value' => $upload_info['name']
							);
						}
					}
				}
			}

			// Custom fields
			$data['payment_custom_fields'] = array();

			foreach ($custom_fields as $custom_field) {
				if ($custom_field['location'] == 'address' && isset($order_info['payment_custom_field'][$custom_field['custom_field_id']])) {
					if ($custom_field['type'] == 'select' || $custom_field['type'] == 'radio') {
						$custom_field_value_info = $this->model_customer_custom_field->getCustomFieldValue($order_info['payment_custom_field'][$custom_field['custom_field_id']]);

						if ($custom_field_value_info) {
							$data['payment_custom_fields'][] = array(
								'name'  => $custom_field['name'],
								'value' => $custom_field_value_info['name'],
								'sort_order' => $custom_field['sort_order']
							);
						}
					}

					if ($custom_field['type'] == 'checkbox' && is_array($order_info['payment_custom_field'][$custom_field['custom_field_id']])) {
						foreach ($order_info['payment_custom_field'][$custom_field['custom_field_id']] as $custom_field_value_id) {
							$custom_field_value_info = $this->model_customer_custom_field->getCustomFieldValue($custom_field_value_id);

							if ($custom_field_value_info) {
								$data['payment_custom_fields'][] = array(
									'name'  => $custom_field['name'],
									'value' => $custom_field_value_info['name'],
									'sort_order' => $custom_field['sort_order']
								);
							}
						}
					}

					if ($custom_field['type'] == 'text' || $custom_field['type'] == 'textarea' || $custom_field['type'] == 'file' || $custom_field['type'] == 'date' || $custom_field['type'] == 'datetime' || $custom_field['type'] == 'time') {
						$data['payment_custom_fields'][] = array(
							'name'  => $custom_field['name'],
							'value' => $order_info['payment_custom_field'][$custom_field['custom_field_id']],
							'sort_order' => $custom_field['sort_order']
						);
					}

					if ($custom_field['type'] == 'file') {
						$upload_info = $this->model_tool_upload->getUploadByCode($order_info['payment_custom_field'][$custom_field['custom_field_id']]);

						if ($upload_info) {
							$data['payment_custom_fields'][] = array(
								'name'  => $custom_field['name'],
								'value' => $upload_info['name'],
								'sort_order' => $custom_field['sort_order']
							);
						}
					}
				}
			}

			// Shipping
			$data['shipping_custom_fields'] = array();

			foreach ($custom_fields as $custom_field) {
				if ($custom_field['location'] == 'address' && isset($order_info['shipping_custom_field'][$custom_field['custom_field_id']])) {
					if ($custom_field['type'] == 'select' || $custom_field['type'] == 'radio') {
						$custom_field_value_info = $this->model_customer_custom_field->getCustomFieldValue($order_info['shipping_custom_field'][$custom_field['custom_field_id']]);

						if ($custom_field_value_info) {
							$data['shipping_custom_fields'][] = array(
								'name'  => $custom_field['name'],
								'value' => $custom_field_value_info['name'],
								'sort_order' => $custom_field['sort_order']
							);
						}
					}

					if ($custom_field['type'] == 'checkbox' && is_array($order_info['shipping_custom_field'][$custom_field['custom_field_id']])) {
						foreach ($order_info['shipping_custom_field'][$custom_field['custom_field_id']] as $custom_field_value_id) {
							$custom_field_value_info = $this->model_customer_custom_field->getCustomFieldValue($custom_field_value_id);

							if ($custom_field_value_info) {
								$data['shipping_custom_fields'][] = array(
									'name'  => $custom_field['name'],
									'value' => $custom_field_value_info['name'],
									'sort_order' => $custom_field['sort_order']
								);
							}
						}
					}

					if ($custom_field['type'] == 'text' || $custom_field['type'] == 'textarea' || $custom_field['type'] == 'file' || $custom_field['type'] == 'date' || $custom_field['type'] == 'datetime' || $custom_field['type'] == 'time') {
						$data['shipping_custom_fields'][] = array(
							'name'  => $custom_field['name'],
							'value' => $order_info['shipping_custom_field'][$custom_field['custom_field_id']],
							'sort_order' => $custom_field['sort_order']
						);
					}

					if ($custom_field['type'] == 'file') {
						$upload_info = $this->model_tool_upload->getUploadByCode($order_info['shipping_custom_field'][$custom_field['custom_field_id']]);

						if ($upload_info) {
							$data['shipping_custom_fields'][] = array(
								'name'  => $custom_field['name'],
								'value' => $upload_info['name'],
								'sort_order' => $custom_field['sort_order']
							);
						}
					}
				}
			}

			$data['ip'] = $order_info['ip'];
			$data['forwarded_ip'] = $order_info['forwarded_ip'];
			$data['user_agent'] = $order_info['user_agent'];
			$data['accept_language'] = $order_info['accept_language'];

			// Additional Tabs
			$data['tabs'] = array();

			if ($this->user->hasPermission('access', 'extension/payment/' . $order_info['payment_code'])) {
				if (is_file(DIR_CATALOG . 'controller/extension/payment/' . $order_info['payment_code'] . '.php')) {
					$content = $this->load->controller('extension/payment/' . $order_info['payment_code'] . '/order');
				} else {
					$content = '';
				}

				if ($content) {
					$this->load->language('extension/payment/' . $order_info['payment_code']);

					$data['tabs'][] = array(
						'code'    => $order_info['payment_code'],
						'title'   => $this->language->get('heading_title'),
						'content' => $content
					);
				}
			}

			$this->load->model('setting/extension');

			$extensions = $this->model_setting_extension->getInstalled('fraud');

			foreach ($extensions as $extension) {
				if ($this->config->get('fraud_' . $extension . '_status')) {
					$this->load->language('extension/fraud/' . $extension, 'extension');

					$content = $this->load->controller('extension/fraud/' . $extension . '/order');

					if ($content) {
						$data['tabs'][] = array(
							'code'    => $extension,
							'title'   => $this->language->get('extension')->get('heading_title'),
							'content' => $content
						);
					}
				}
			}
			
			// The URL we send API requests to
			$data['catalog'] = $this->request->server['HTTPS'] ? HTTPS_CATALOG : HTTP_CATALOG;
			
			// API login
			$this->load->model('user/api');

			$api_info = $this->model_user_api->getApi($this->config->get('config_api_id'));

			if ($api_info && $this->user->hasPermission('modify', 'sale/order')) {
				$session = new Session($this->config->get('session_engine'), $this->registry);
				
				$session->start();
				
				$this->model_user_api->deleteApiSessionBySessonId($session->getId());
				
				$this->model_user_api->addApiSession($api_info['api_id'], $session->getId(), $this->request->server['REMOTE_ADDR']);
				
				$session->data['api_id'] = $api_info['api_id'];

				$data['api_token'] = $session->getId();
			} else {
				$data['api_token'] = '';
			}

			$data['header'] = $this->load->controller('common/header');
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['footer'] = $this->load->controller('common/footer');

			$this->response->setOutput($this->load->view('sale/order_info', $data));
		} else {
			return new Action('error/not_found');
		}
	}
	
	protected function validate() {
		if (!$this->user->hasPermission('modify', 'sale/order')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
	
	public function createInvoiceNo() {
		$this->load->language('sale/order');

		$json = array();

		if (!$this->user->hasPermission('modify', 'sale/order')) {
			$json['error'] = $this->language->get('error_permission');
		} elseif (isset($this->request->get['order_id'])) {
			if (isset($this->request->get['order_id'])) {
				$order_id = $this->request->get['order_id'];
			} else {
				$order_id = 0;
			}

			$this->load->model('sale/order');

			$invoice_no = $this->model_sale_order->createInvoiceNo($order_id);

			if ($invoice_no) {
				$json['invoice_no'] = $invoice_no;
			} else {
				$json['error'] = $this->language->get('error_action');
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function addReward() {
		$this->load->language('sale/order');

		$json = array();

		if (!$this->user->hasPermission('modify', 'sale/order')) {
			$json['error'] = $this->language->get('error_permission');
		} else {
			if (isset($this->request->get['order_id'])) {
				$order_id = $this->request->get['order_id'];
			} else {
				$order_id = 0;
			}

			$this->load->model('sale/order');

			$order_info = $this->model_sale_order->getOrder($order_id);

			if ($order_info && $order_info['customer_id'] && ($order_info['reward'] > 0)) {
				$this->load->model('customer/customer');

				$reward_total = $this->model_customer_customer->getTotalCustomerRewardsByOrderId($order_id);

				if (!$reward_total) {
					$this->model_customer_customer->addReward($order_info['customer_id'], $this->language->get('text_order_id') . ' #' . $order_id, $order_info['reward'], $order_id);
				}
			}

			$json['success'] = $this->language->get('text_reward_added');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function removeReward() {
		$this->load->language('sale/order');

		$json = array();

		if (!$this->user->hasPermission('modify', 'sale/order')) {
			$json['error'] = $this->language->get('error_permission');
		} else {
			if (isset($this->request->get['order_id'])) {
				$order_id = $this->request->get['order_id'];
			} else {
				$order_id = 0;
			}

			$this->load->model('sale/order');

			$order_info = $this->model_sale_order->getOrder($order_id);

			if ($order_info) {
				$this->load->model('customer/customer');

				$this->model_customer_customer->deleteReward($order_id);
			}

			$json['success'] = $this->language->get('text_reward_removed');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function addCommission() {
		$this->load->language('sale/order');

		$json = array();

		if (!$this->user->hasPermission('modify', 'sale/order')) {
			$json['error'] = $this->language->get('error_permission');
		} else {
			if (isset($this->request->get['order_id'])) {
				$order_id = $this->request->get['order_id'];
			} else {
				$order_id = 0;
			}

			$this->load->model('sale/order');

			$order_info = $this->model_sale_order->getOrder($order_id);

			if ($order_info) {
				$this->load->model('customer/customer');

				$affiliate_total = $this->model_customer_customer->getTotalTransactionsByOrderId($order_id);

				if (!$affiliate_total) {
					$this->model_customer_customer->addTransaction($order_info['affiliate_id'], $this->language->get('text_order_id') . ' #' . $order_id, $order_info['commission'], $order_id);
				}
			}

			$json['success'] = $this->language->get('text_commission_added');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function removeCommission() {
		$this->load->language('sale/order');

		$json = array();

		if (!$this->user->hasPermission('modify', 'sale/order')) {
			$json['error'] = $this->language->get('error_permission');
		} else {
			if (isset($this->request->get['order_id'])) {
				$order_id = $this->request->get['order_id'];
			} else {
				$order_id = 0;
			}

			$this->load->model('sale/order');

			$order_info = $this->model_sale_order->getOrder($order_id);

			if ($order_info) {
				$this->load->model('customer/customer');

				$this->model_customer_customer->deleteTransactionByOrderId($order_id);
			}

			$json['success'] = $this->language->get('text_commission_removed');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function history() {
		$this->load->language('sale/order');

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$data['histories'] = array();

		$this->load->model('sale/order');

		$results = $this->model_sale_order->getOrderHistories($this->request->get['order_id'], ($page - 1) * 10, 10);

		foreach ($results as $result) {
			$data['histories'][] = array(
				'notify'     => $result['notify'] ? $this->language->get('text_yes') : $this->language->get('text_no'),
				'status'     => $result['status'],
				'comment'    => nl2br($result['comment']),
				'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added']))
			);
		}

		$history_total = $this->model_sale_order->getTotalOrderHistories($this->request->get['order_id']);

		$pagination = new Pagination();
		$pagination->total = $history_total;
		$pagination->page = $page;
		$pagination->limit = 10;
		$pagination->url = $this->url->link('sale/order/history', 'user_token=' . $this->session->data['user_token'] . '&order_id=' . $this->request->get['order_id'] . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($history_total) ? (($page - 1) * 10) + 1 : 0, ((($page - 1) * 10) > ($history_total - 10)) ? $history_total : ((($page - 1) * 10) + 10), $history_total, ceil($history_total / 10));

		$this->response->setOutput($this->load->view('sale/order_history', $data));
	}

	public function invoice() {
		$this->load->language('sale/order');

		$data['title'] = $this->language->get('text_invoice');

		if ($this->request->server['HTTPS']) {
			$data['base'] = HTTPS_SERVER;
		} else {
			$data['base'] = HTTP_SERVER;
		}

		$data['direction'] = $this->language->get('direction');
		$data['lang'] = $this->language->get('code');

		$this->load->model('sale/order');

		$this->load->model('setting/setting');

		$data['orders'] = array();

		$orders = array();

		if (isset($this->request->post['selected'])) {
			$orders = $this->request->post['selected'];
		} elseif (isset($this->request->get['order_id'])) {
			$orders[] = $this->request->get['order_id'];
		}

		foreach ($orders as $order_id) {
			$order_info = $this->model_sale_order->getOrder($order_id);

			if ($order_info) {
				$store_info = $this->model_setting_setting->getSetting('config', $order_info['store_id']);

				if ($store_info) {
					$store_address = $store_info['config_address'];
					$store_email = $store_info['config_email'];
					$store_telephone = $store_info['config_telephone'];
					$store_fax = $store_info['config_fax'];
				} else {
					$store_address = $this->config->get('config_address');
					$store_email = $this->config->get('config_email');
					$store_telephone = $this->config->get('config_telephone');
					$store_fax = $this->config->get('config_fax');
				}

				if ($order_info['invoice_no']) {
					$invoice_no = $order_info['invoice_prefix'] . $order_info['invoice_no'];
				} else {
					$invoice_no = '';
				}

				if ($order_info['payment_address_format']) {
					$format = $order_info['payment_address_format'];
				} else {
					$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
				}

				$find = array(
					'{firstname}',
					'{lastname}',
					'{company}',
					'{address_1}',
					'{address_2}',
					'{city}',
					'{postcode}',
					'{zone}',
					'{zone_code}',
					'{country}'
				);

				$replace = array(
					'firstname' => $order_info['payment_firstname'],
					'lastname'  => $order_info['payment_lastname'] . "  " . $order_info['telephone'],
					'company'   => $order_info['payment_company'],
					'address_1' => $order_info['payment_address_1'],
					'address_2' => $order_info['payment_address_2'],
					'city'      => $order_info['payment_city'],
					'postcode'  => $order_info['payment_postcode'],
					'zone'      => $order_info['payment_zone'],
					'zone_code' => $order_info['payment_zone_code'],
					'country'   => $order_info['payment_country']
				);

				$payment_address = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));

				if ($order_info['shipping_address_format']) {
					$format = $order_info['shipping_address_format'];
				} else {
					$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
				}

				$find = array(
					'{firstname}',
					'{lastname}',
					'{company}',
					'{address_1}',
					'{address_2}',
					'{city}',
					'{postcode}',
					'{zone}',
					'{zone_code}',
					'{country}'
				);

				$replace = array(
					'firstname' => $order_info['shipping_firstname'],
					'lastname'  => $order_info['shipping_lastname'],
					'company'   => $order_info['shipping_company'],
					'address_1' => $order_info['shipping_address_1'],
					'address_2' => $order_info['shipping_address_2'],
					'city'      => $order_info['shipping_city'],
					'postcode'  => $order_info['shipping_postcode'],
					'zone'      => $order_info['shipping_zone'],
					'zone_code' => $order_info['shipping_zone_code'],
					'country'   => $order_info['shipping_country']
				);

				$shipping_address = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));

				$this->load->model('tool/upload');

				$product_data = array();

				$products = $this->model_sale_order->getOrderProducts($order_id);

				foreach ($products as $product) {
					$option_data = array();

					$options = $this->model_sale_order->getOrderOptions($order_id, $product['order_product_id']);

					foreach ($options as $option) {
						if ($option['type'] != 'file') {
							$value = $option['value'];
						} else {
							$upload_info = $this->model_tool_upload->getUploadByCode($option['value']);

							if ($upload_info) {
								$value = $upload_info['name'];
							} else {
								$value = '';
							}
						}

						$option_data[] = array(
							'name'  => $option['name'],
							'value' => $value
						);
					}

					$product_data[] = array(
						'name'     => $product['name'],
						'model'    => $product['model'],
						'option'   => $option_data,
						'quantity' => $product['quantity'],
						'price'    => $this->currency->format($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $order_info['currency_code'], $order_info['currency_value']),
						'total'    => $this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value'])
					);
				}

				$voucher_data = array();

				$vouchers = $this->model_sale_order->getOrderVouchers($order_id);

				foreach ($vouchers as $voucher) {
					$voucher_data[] = array(
						'description' => $voucher['description'],
						'amount'      => $this->currency->format($voucher['amount'], $order_info['currency_code'], $order_info['currency_value'])
					);
				}

				$total_data = array();

				$totals = $this->model_sale_order->getOrderTotals($order_id);

				foreach ($totals as $total) {
					$total_data[] = array(
						'title' => $total['title'],
						'text'  => $this->currency->format($total['value'], $order_info['currency_code'], $order_info['currency_value'])
					);
				}

				$data['orders'][] = array(
					'order_id'	       => $order_id,
					'invoice_no'       => $invoice_no,
					'date_added'       => date($this->language->get('date_format_short'), strtotime($order_info['date_added'])),
					'store_name'       => $order_info['store_name'],
					'store_url'        => rtrim($order_info['store_url'], '/'),
					'store_address'    => nl2br($store_address),
					'store_email'      => $store_email,
					'store_telephone'  => $store_telephone,
					'store_fax'        => $store_fax,
					'email'            => $order_info['email'],
					'telephone'        => $order_info['telephone'],
					'shipping_address' => $shipping_address,
					'shipping_method'  => $order_info['shipping_method'],
					'payment_address'  => $payment_address,
					'payment_method'   => $order_info['payment_method'],
					'product'          => $product_data,
					'voucher'          => $voucher_data,
					'total'            => $total_data,
					'comment'          => nl2br($order_info['comment'])
				);
			}
		}

		$this->response->setOutput($this->load->view('sale/order_invoice', $data));
	}

	public function shipping() {
		$this->load->language('sale/order');

		$data['title'] = $this->language->get('text_shipping');

		if ($this->request->server['HTTPS']) {
			$data['base'] = HTTPS_SERVER;
		} else {
			$data['base'] = HTTP_SERVER;
		}

		$data['direction'] = $this->language->get('direction');
		$data['lang'] = $this->language->get('code');

		$this->load->model('sale/order');

		$this->load->model('catalog/product');

		$this->load->model('setting/setting');

		$data['orders'] = array();

		$orders = array();

		if (isset($this->request->post['selected'])) {
			$orders = $this->request->post['selected'];
		} elseif (isset($this->request->get['order_id'])) {
			$orders[] = $this->request->get['order_id'];
		}

		foreach ($orders as $order_id) {
			$order_info = $this->model_sale_order->getOrder($order_id);

			// Make sure there is a shipping method
			if ($order_info && $order_info['shipping_code']) {
				$store_info = $this->model_setting_setting->getSetting('config', $order_info['store_id']);

				if ($store_info) {
					$store_address = $store_info['config_address'];
					$store_email = $store_info['config_email'];
					$store_telephone = $store_info['config_telephone'];
				} else {
					$store_address = $this->config->get('config_address');
					$store_email = $this->config->get('config_email');
					$store_telephone = $this->config->get('config_telephone');
				}

				if ($order_info['invoice_no']) {
					$invoice_no = $order_info['invoice_prefix'] . $order_info['invoice_no'];
				} else {
					$invoice_no = '';
				}

				if ($order_info['shipping_address_format']) {
					$format = $order_info['shipping_address_format'];
				} else {
					$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
				}

				$find = array(
					'{firstname}',
					'{lastname}',
					'{company}',
					'{address_1}',
					'{address_2}',
					'{city}',
					'{postcode}',
					'{zone}',
					'{zone_code}',
					'{country}'
				);

				$replace = array(
					'firstname' => $order_info['shipping_firstname'],
					'lastname'  => $order_info['shipping_lastname'],
					'company'   => $order_info['shipping_company'],
					'address_1' => $order_info['shipping_address_1'],
					'address_2' => $order_info['shipping_address_2'],
					'city'      => $order_info['shipping_city'],
					'postcode'  => $order_info['shipping_postcode'],
					'zone'      => $order_info['shipping_zone'],
					'zone_code' => $order_info['shipping_zone_code'],
					'country'   => $order_info['shipping_country']
				);

				$shipping_address = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));

				$this->load->model('tool/upload');

				$product_data = array();

				$products = $this->model_sale_order->getOrderProducts($order_id);

				foreach ($products as $product) {
					$option_weight = '';

					$product_info = $this->model_catalog_product->getProduct($product['product_id']);

					if ($product_info) {
						$option_data = array();

						$options = $this->model_sale_order->getOrderOptions($order_id, $product['order_product_id']);

						foreach ($options as $option) {
							if ($option['type'] != 'file') {
								$value = $option['value'];
							} else {
								$upload_info = $this->model_tool_upload->getUploadByCode($option['value']);

								if ($upload_info) {
									$value = $upload_info['name'];
								} else {
									$value = '';
								}
							}

							$option_data[] = array(
								'name'  => $option['name'],
								'value' => $value
							);

							$product_option_value_info = $this->model_catalog_product->getProductOptionValue($product['product_id'], $option['product_option_value_id']);

							if ($product_option_value_info) {
								if ($product_option_value_info['weight_prefix'] == '+') {
									$option_weight += $product_option_value_info['weight'];
								} elseif ($product_option_value_info['weight_prefix'] == '-') {
									$option_weight -= $product_option_value_info['weight'];
								}
							}
						}

						$product_data[] = array(
							'name'     => $product_info['name'],
							'model'    => $product_info['model'],
							'option'   => $option_data,
							'quantity' => $product['quantity'],
							'location' => $product_info['location'],
							'sku'      => $product_info['sku'],
							'upc'      => $product_info['upc'],
							'ean'      => $product_info['ean'],
							'jan'      => $product_info['jan'],
							'isbn'     => $product_info['isbn'],
							'mpn'      => $product_info['mpn'],
							'weight'   => $this->weight->format(($product_info['weight'] + (float)$option_weight) * $product['quantity'], $product_info['weight_class_id'], $this->language->get('decimal_point'), $this->language->get('thousand_point'))
						);
					}
				}

				$data['orders'][] = array(
					'order_id'	       => $order_id,
					'invoice_no'       => $invoice_no,
					'date_added'       => date($this->language->get('date_format_short'), strtotime($order_info['date_added'])),
					'store_name'       => $order_info['store_name'],
					'store_url'        => rtrim($order_info['store_url'], '/'),
					'store_address'    => nl2br($store_address),
					'store_email'      => $store_email,
					'store_telephone'  => $store_telephone,
					'email'            => $order_info['email'],
					'telephone'        => $order_info['telephone'],
					'shipping_address' => $shipping_address,
					'shipping_method'  => $order_info['shipping_method'],
					'product'          => $product_data,
					'comment'          => nl2br($order_info['comment'])
				);
			}
		}

		$this->response->setOutput($this->load->view('sale/order_shipping', $data));
	}
}
