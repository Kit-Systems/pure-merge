<?php
class ControllerCheckoutSuccess extends Controller {
	public function index() {
		$this->load->language('checkout/success');


		if (isset($this->session->data['order_id'])) {

			$products = $this->cart->getProducts();

			$data['products'] = array();

			foreach ($this->cart->getProducts() as $product) {
				$recurring = '';

				if ($product['recurring']) {
					$frequencies = array(
						'day'        => $this->language->get('text_day'),
						'week'       => $this->language->get('text_week'),
						'semi_month' => $this->language->get('text_semi_month'),
						'month'      => $this->language->get('text_month'),
						'year'       => $this->language->get('text_year'),
					);

					if ($product['recurring']['trial']) {
						$recurring = sprintf($this->language->get('text_trial_description'), $this->currency->format($this->tax->calculate($product['recurring']['trial_price'] * $product['quantity'], $product['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']), $product['recurring']['trial_cycle'], $frequencies[$product['recurring']['trial_frequency']], $product['recurring']['trial_duration']) . ' ';
					}

					if ($product['recurring']['duration']) {
						$recurring .= sprintf($this->language->get('text_payment_description'), $this->currency->format($this->tax->calculate($product['recurring']['price'] * $product['quantity'], $product['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']), $product['recurring']['cycle'], $frequencies[$product['recurring']['frequency']], $product['recurring']['duration']);
					} else {
						$recurring .= sprintf($this->language->get('text_payment_cancel'), $this->currency->format($this->tax->calculate($product['recurring']['price'] * $product['quantity'], $product['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']), $product['recurring']['cycle'], $frequencies[$product['recurring']['frequency']], $product['recurring']['duration']);
					}
				}

				$data['products'][] = array(
					'cart_id'    => $product['cart_id'],
					'product_id' => $product['product_id'],
					'name'       => $product['name'],
					'model'      => $product['model'],
					'quantity'   => $product['quantity'],
					'subtract'   => $product['subtract'],
					'price'      => $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']),
					'total'      => $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')) * $product['quantity'], $this->session->data['currency']),
					'href'       => $this->url->link('product/product', 'product_id=' . $product['product_id'])
				);
			}
			

			if (isset($this->session->data['order_id'])) {
			    $this->load->model('checkout/order');

			    if($orderData = $this->model_checkout_order->getOrder($this->session->data['order_id'])){
			        $dataToCrm = "Заказ №" . $orderData['order_id'] . "\n";
					$dataToCrm .= "Покупатель: " . $orderData['payment_firstname'] . " " . $orderData['payment_lastname'] . "\n";
					$dataToCrm .= "Платежный адрес: " . $orderData['payment_country'] . ", " . $orderData['payment_zone'] . ", " . $orderData['payment_city'] . ", " . $orderData['payment_address_1'] . " " . $orderData['payment_address_2'] . "\n";
					$dataToCrm .= "Получатель: " . $orderData['shipping_firstname'] . " " . $orderData['shipping_lastname'] . "\n";
					$dataToCrm .= "Адрес доставки: " . $orderData['shipping_country'] . ", " . $orderData['shipping_zone'] . ", " . $orderData['shipping_city'] . ", " . $orderData['shipping_address_1'] . " " . $orderData['shipping_address_2'] . ", " . $orderData['shipping_postcode'] . "\n";
					$dataToCrm .= "&nbsp;\nТовары: \n";
					
					foreach ($data['products'] as $key => $value){
						foreach ($value as $key1 => $value1) {
							if ($key1 == 'name') {
								$dataToCrm .= $value1 . ", ";
							}
							if ($key1 == 'quantity') {
								$dataToCrm .= $value1 . "шт. x ";
							}
							if ($key1 == 'price') {
								$dataToCrm .= $value1 . "\n";
							}
							if ($key1 == 'total') {
								$dataToCrm .= "Сумма: " . $value1 . "\n &nbsp; \n";
							}
						}
						foreach ($value as $key1 => $value1) {
							if ($key1 == 'product_id') {
								$dataToCrm .= "ID товара: " . $value1 . ", ";
							}
							if ($key1 == 'model') {
								$dataToCrm .= "код: " . $value1 . ", ";
							}
							if ($key1 == 'href') {
								$dataToCrm .= "ссылка: " . $value1 . "\n";
							}
						}
					}

					$dataToCrm .= "Способ доставки: " . $orderData['shipping_method'] . "\n";
					$dataToCrm .= "Сумма к оплате (в тенге): " . $orderData['total'] . " KZT\n &nbsp; \n";

					$dataToCrm .= "Комментарий покупателя: " . $orderData['comment'] ;


					//amo
			        //ПРЕДОПРЕДЕЛЯЕМЫЕ ПЕРЕМЕННЫЕ
			        $responsible_user_id = 7292136; //id ответственного по сделке, контакту, компании
			        $lead_name = "Заказ №" . $orderData['order_id']; //Название добавляемой сделки
			        //$lead_status_id = '17009307'; //id этапа продаж, куда помещать сделку
			        $pipeline_id = '1939612';
			        //$pipeline_id = 'pipeline_961777_1267186131';
			       
			        $contact_name = $orderData['firstname'] . " " . $orderData['lastname']; //Название добавляемого контакта
			        $contact_phone = $orderData['telephone']; //Телефон контакта
			        $contact_email = $orderData['email']; //Емейл контакта
			        //$contact_message = $text;

			        //АВТОРИЗАЦИЯ
			        $user=array(
			            'USER_LOGIN'=>'melita.batsiyeva@purebeauty.store', #Ваш логин (электронная почта)
			            'USER_HASH'=>'320a3920265dcfc6b0851679960cd43ff3f03812' #Хэш для доступа к API (смотрите в профиле пользователя)
			        );
			        $subdomain='purebeautystore';
			        #Формируем ссылку для запроса
			        $link='https://'.$subdomain.'.amocrm.ru/private/api/auth.php?type=json';
			        $curl=curl_init(); #Сохраняем дескриптор сеанса cURL
			        #Устанавливаем необходимые опции для сеанса cURL
			        curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
			        curl_setopt($curl,CURLOPT_USERAGENT,'amoCRM-API-client/1.0');
			        curl_setopt($curl,CURLOPT_URL,$link);
			        curl_setopt($curl,CURLOPT_POST,true);
			        curl_setopt($curl,CURLOPT_POSTFIELDS,http_build_query($user));
			        curl_setopt($curl,CURLOPT_HEADER,false);
			        curl_setopt($curl,CURLOPT_COOKIEFILE,dirname(__FILE__).'/cookie.txt'); #PHP>5.3.6 dirname(__FILE__) -> __DIR__
			        curl_setopt($curl,CURLOPT_COOKIEJAR,dirname(__FILE__).'/cookie.txt'); #PHP>5.3.6 dirname(__FILE__) -> __DIR__
			        curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,0);
			        curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,0);
			        $out=curl_exec($curl); #Инициируем запрос к API и сохраняем ответ в переменную
			        $code=curl_getinfo($curl,CURLINFO_HTTP_CODE); #Получим HTTP-код ответа сервера
			        curl_close($curl);  #Завершаем сеанс cURL
			        $Response=json_decode($out,true);
			        //echo '<b>Авторизация:</b>'; echo '<pre>'; print_r($Response); echo '</pre>';
			        //ПОЛУЧАЕМ ДАННЫЕ АККАУНТА
			        $link='https://'.$subdomain.'.amocrm.ru/private/api/v2/json/accounts/current'; #$subdomain уже объявляли выше
			        $curl=curl_init(); #Сохраняем дескриптор сеанса cURL
			        #Устанавливаем необходимые опции для сеанса cURL
			        curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
			        curl_setopt($curl,CURLOPT_USERAGENT,'amoCRM-API-client/1.0');
			        curl_setopt($curl,CURLOPT_URL,$link);
			        curl_setopt($curl,CURLOPT_HEADER,false);
			        curl_setopt($curl,CURLOPT_COOKIEFILE,dirname(__FILE__).'/cookie.txt'); #PHP>5.3.6 dirname(__FILE__) -> __DIR__
			        curl_setopt($curl,CURLOPT_COOKIEJAR,dirname(__FILE__).'/cookie.txt'); #PHP>5.3.6 dirname(__FILE__) -> __DIR__
			        curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,0);
			        curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,0);
			        $out=curl_exec($curl); #Инициируем запрос к API и сохраняем ответ в переменную
			        $code=curl_getinfo($curl,CURLINFO_HTTP_CODE);
			        curl_close($curl);
			        $Response=json_decode($out,true);
			        $account=$Response['response']['account'];
			        //echo '<b>Данные аккаунта:</b>'; echo '<pre>'; print_r($Response); echo '</pre>';
			        //ПОЛУЧАЕМ СУЩЕСТВУЮЩИЕ ПОЛЯ
			        $amoAllFields = $account['custom_fields']; //Все поля
			        $amoConactsFields = $account['custom_fields']['contacts']; //Поля контактов
			        //echo '<b>Поля из амо:</b>'; echo '<pre>'; print_r($amoConactsFields); echo '</pre>';
			        //ФОРМИРУЕМ МАССИВ С ЗАПОЛНЕННЫМИ ПОЛЯМИ КОНТАКТА
			        //Стандартные поля амо:
			        $sFields = array_flip(array(
			                'PHONE', //Телефон. Варианты: WORK, WORKDD, MOB, FAX, HOME, OTHER
			                'EMAIL' //Email. Варианты: WORK, PRIV, OTHER
			            )
			        );
			        //Проставляем id этих полей из базы амо
			        foreach($amoConactsFields as $afield) {
			            if(isset($sFields[$afield['code']])) {
			                $sFields[$afield['code']] = $afield['id'];
			            }
			        }


			        //ДОБАВЛЯЕМ СДЕЛКУ
			        $leads['request']['leads']['add']=array(
			            array(
			                'name' => $lead_name,
			                //'status_id' => $lead_status_id, //id статуса
			                'responsible_user_id' => $responsible_user_id, //id ответственного по сделке
			                'pipeline_id' => $pipeline_id
			            )
			        );
			        $link='https://'.$subdomain.'.amocrm.ru/private/api/v2/json/leads/set';
			        $curl=curl_init(); #Сохраняем дескриптор сеанса cURL
			        #Устанавливаем необходимые опции для сеанса cURL
			        curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
			        curl_setopt($curl,CURLOPT_USERAGENT,'amoCRM-API-client/1.0');
			        curl_setopt($curl,CURLOPT_URL,$link);
			        curl_setopt($curl,CURLOPT_CUSTOMREQUEST,'POST');
			        curl_setopt($curl,CURLOPT_POSTFIELDS,json_encode($leads));
			        curl_setopt($curl,CURLOPT_HTTPHEADER,array('Content-Type: application/json'));
			        curl_setopt($curl,CURLOPT_HEADER,false);
			        curl_setopt($curl,CURLOPT_COOKIEFILE,dirname(__FILE__).'/cookie.txt'); #PHP>5.3.6 dirname(__FILE__) -> __DIR__
			        curl_setopt($curl,CURLOPT_COOKIEJAR,dirname(__FILE__).'/cookie.txt'); #PHP>5.3.6 dirname(__FILE__) -> __DIR__
			        curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,0);
			        curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,0);
			        $out=curl_exec($curl); #Инициируем запрос к API и сохраняем ответ в переменную
			        $code=curl_getinfo($curl,CURLINFO_HTTP_CODE);
			        $Response=json_decode($out,true);
			        //echo '<b>Новая сделка:</b>'; echo '<pre>'; print_r($Response); echo '</pre>';
			        if(is_array($Response['response']['leads']['add']))
			            foreach($Response['response']['leads']['add'] as $lead) {
			                $lead_id = $lead["id"]; //id новой сделки
			            };
			        //ДОБАВЛЯЕМ СДЕЛКУ - КОНЕЦ


			        //ДОБАВЛЕНИЕ КОНТАКТА
			        $contact = array(
			            'name' => $contact_name,
			            'linked_leads_id' => array($lead_id), //id сделки
			            'responsible_user_id' => $responsible_user_id, //id ответственного
			            'custom_fields'=>array(
			                array(
			                    'id' => $sFields['PHONE'],
			                    'values' => array(
			                        array(
			                            'value' => $contact_phone,
			                            'enum' => 'MOB'
			                        )
			                    )
			                ),
			                array(
			                    'id' => $sFields['EMAIL'],
			                    'values' => array(
			                        array(
			                            'value' => $contact_email,
			                            'enum' => 'WORK'
			                        )
			                    )
			                )
			            )
			        );
			        $set['request']['contacts']['add'][]=$contact;
			        #Формируем ссылку для запроса
			        $link='https://'.$subdomain.'.amocrm.ru/private/api/v2/json/contacts/set';
			        $curl=curl_init(); #Сохраняем дескриптор сеанса cURL
			        #Устанавливаем необходимые опции для сеанса cURL
			        curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
			        curl_setopt($curl,CURLOPT_USERAGENT,'amoCRM-API-client/1.0');
			        curl_setopt($curl,CURLOPT_URL,$link);
			        curl_setopt($curl,CURLOPT_CUSTOMREQUEST,'POST');
			        curl_setopt($curl,CURLOPT_POSTFIELDS,json_encode($set));
			        curl_setopt($curl,CURLOPT_HTTPHEADER,array('Content-Type: application/json'));
			        curl_setopt($curl,CURLOPT_HEADER,false);
			        curl_setopt($curl,CURLOPT_COOKIEFILE,dirname(__FILE__).'/cookie.txt'); #PHP>5.3.6 dirname(__FILE__) -> __DIR__
			        curl_setopt($curl,CURLOPT_COOKIEJAR,dirname(__FILE__).'/cookie.txt'); #PHP>5.3.6 dirname(__FILE__) -> __DIR__
			        curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,0);
			        curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,0);
			        $out=curl_exec($curl); #Инициируем запрос к API и сохраняем ответ в переменную
			        $code=curl_getinfo($curl,CURLINFO_HTTP_CODE);
			        //CheckCurlResponse($code);
			        $Response=json_decode($out,true);
			        //ДОБАВЛЕНИЕ КОНТАКТА - КОНЕЦ


			        //ДОБАВЛЕНИЕ ПРИМЕЧАНИЯ
			        $data = array(
					    'add' => array(
					        0 => array(
					            'element_id' => $lead_id,
					            'element_type' => '2',
					            'text' => $dataToCrm,
					            'note_type' => '4',
					            'responsible_user_id' => $responsible_user_id,
					            'created_by' => $responsible_user_id,
					        ),
					    ),
					);
					#Формируем ссылку для запроса
					$link = 'https://' . $subdomain . '.amocrm.ru/api/v2/notes';
					/* Нам необходимо инициировать запрос к серверу. Воспользуемся библиотекой cURL (поставляется в составе PHP). Подробнее о
					работе с этой
					библиотекой Вы можете прочитать в мануале. */
					$curl = curl_init(); #Сохраняем дескриптор сеанса cURL
					#Устанавливаем необходимые опции для сеанса cURL
					curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
					curl_setopt($curl, CURLOPT_USERAGENT, 'amoCRM-API-client/1.0');
					curl_setopt($curl, CURLOPT_URL, $link);
					curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
					curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
					curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
					curl_setopt($curl, CURLOPT_HEADER, false);
					curl_setopt($curl, CURLOPT_COOKIEFILE, dirname(__FILE__) . '/cookie.txt'); #PHP>5.3.6 dirname(__FILE__) -> __DIR__
					curl_setopt($curl, CURLOPT_COOKIEJAR, dirname(__FILE__) . '/cookie.txt'); #PHP>5.3.6 dirname(__FILE__) -> __DIR__
					curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
					curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
					$out = curl_exec($curl); #Инициируем запрос к API и сохраняем ответ в переменную
					$code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
					/* Теперь мы можем обработать ответ, полученный от сервера. Это пример. Вы можете обработать данные своим способом. */
					$code = (int) $code;
					$errors = array(
					    301 => 'Moved permanently',
					    400 => 'Bad request',
					    401 => 'Unauthorized',
					    403 => 'Forbidden',
					    404 => 'Not found',
					    500 => 'Internal server error',
					    502 => 'Bad gateway',
					    503 => 'Service unavailable',
					);
					try
					{
					    #Если код ответа не равен 200 или 204 - возвращаем сообщение об ошибке
					    if ($code != 200 && $code != 204) {
					        throw new Exception(isset($errors[$code]) ? $errors[$code] : 'Undescribed error', $code);
					        echo "200 204";
					    }

					} catch (Exception $E) {
					    die('Ошибка: ' . $E->getMessage() . PHP_EOL . 'Код ошибки: ' . $E->getCode());
					}
			        //amo
			    }
			}

			







			$this->cart->clear();

			unset($this->session->data['shipping_method']);
			unset($this->session->data['shipping_methods']);
			unset($this->session->data['payment_method']);
			unset($this->session->data['payment_methods']);
			unset($this->session->data['guest']);
			unset($this->session->data['comment']);
			unset($this->session->data['order_id']);
			unset($this->session->data['coupon']);
			unset($this->session->data['reward']);
			unset($this->session->data['voucher']);
			unset($this->session->data['vouchers']);
			unset($this->session->data['totals']);
		}

		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_basket'),
			'href' => $this->url->link('checkout/cart')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_checkout'),
			'href' => $this->url->link('checkout/checkout', '', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_success'),
			'href' => $this->url->link('checkout/success')
		);

		if ($this->customer->isLogged()) {
			$data['text_message'] = sprintf($this->language->get('text_customer'), $this->url->link('account/account', '', true), $this->url->link('account/order', '', true), $this->url->link('account/download', '', true), $this->url->link('information/contact'));
		} else {
			$data['text_message'] = sprintf($this->language->get('text_guest'), $this->url->link('information/contact'));
		}

		$data['continue'] = $this->url->link('common/home');

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('common/success', $data));
	}
}