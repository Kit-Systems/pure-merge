<?php
class ModelCatalogReview extends Model {
	public function addReview($product_id, $data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "review SET author = '" . $this->db->escape($data['name']) . "', customer_id = '" . (int)$this->customer->getId() . "', product_id = '" . (int)$product_id . "', text = '" . $this->db->escape($data['text']) . "', rating = '" . (int)$data['rating'] . "', date_added = NOW()");

		$review_id = $this->db->getLastId();

		if (in_array('review', (array)$this->config->get('config_mail_alert'))) {
			$this->load->language('mail/review');
			$this->load->model('catalog/product');
			
			$product_info = $this->model_catalog_product->getProduct($product_id);

			$subject = sprintf($this->language->get('text_subject'), html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));

			$message  = $this->language->get('text_waiting') . "\n";
			$message .= sprintf($this->language->get('text_product'), html_entity_decode($product_info['name'], ENT_QUOTES, 'UTF-8')) . "\n";
			$message .= sprintf($this->language->get('text_reviewer'), html_entity_decode($data['name'], ENT_QUOTES, 'UTF-8')) . "\n";
			$message .= sprintf($this->language->get('text_rating'), $data['rating']) . "\n";
			$message .= $this->language->get('text_review') . "\n";
			$message .= html_entity_decode($data['text'], ENT_QUOTES, 'UTF-8') . "\n\n";

			$mail = new Mail($this->config->get('config_mail_engine'));
			$mail->parameter = $this->config->get('config_mail_parameter');
			$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
			$mail->smtp_username = $this->config->get('config_mail_smtp_username');
			$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
			$mail->smtp_port = $this->config->get('config_mail_smtp_port');
			$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

			$mail->setTo($this->config->get('config_email'));
			$mail->setFrom($this->config->get('config_email'));
			$mail->setSender(html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
			$mail->setSubject($subject);
			$mail->setText($message);
			$mail->send();

			// Send to additional alert emails
			$emails = explode(',', $this->config->get('config_mail_alert_email'));

			foreach ($emails as $email) {
				if ($email && filter_var($email, FILTER_VALIDATE_EMAIL)) {
					$mail->setTo($email);
					$mail->send();
				}
			}
		}
		
		//amo
                    //ПРЕДОПРЕДЕЛЯЕМЫЕ ПЕРЕМЕННЫЕ
                    $responsible_user_id = 7292136; //id ответственного по сделке, контакту, компании
                    //$lead_name = "Заказ №" . $orderData['order_id']; //Название добавляемой сделки
                    //$lead_status_id = '17009307'; //id этапа продаж, куда помещать сделку
                    $pipeline_id = '1934323';
                    //$pipeline_id = 'pipeline_961777_1267186131';
                   
                    $contact_name = html_entity_decode($data['name'], ENT_QUOTES, 'UTF-8'); //Название добавляемого контакта
                    $contact_phone = ''; //Телефон контакта
                    $contact_email = ''; //Емейл контакта
                    //$contact_message = $text;

                    $lead_status_id = '29041741';
                    $lead_name = 'Отзыв на сайте';

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
                            'status_id' => $lead_status_id, //id статуса
                            //'responsible_user_id' => $responsible_user_id, //id ответственного по сделке
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
                        //'responsible_user_id' => $responsible_user_id, //id ответственного
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
                                    'text' => "Товар - " . html_entity_decode($product_info['name'], ENT_QUOTES, 'UTF-8') . "\n Отзыв: " . html_entity_decode($data['text'], ENT_QUOTES, 'UTF-8') . "\n Оценка: " . $data['rating'],
                                    'note_type' => '4',
                                    //'responsible_user_id' => $responsible_user_id,
                                    //'created_by' => $responsible_user_id,
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
                        echo "kher";
                    }
                    //amo
	}

	public function getReviewsByProductId($product_id, $start = 0, $limit = 20) {
		if ($start < 0) {
			$start = 0;
		}

		if ($limit < 1) {
			$limit = 20;
		}

		$query = $this->db->query("SELECT r.review_id, r.author, r.rating, r.text, p.product_id, pd.name, p.price, p.image, r.date_added FROM " . DB_PREFIX . "review r LEFT JOIN " . DB_PREFIX . "product p ON (r.product_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE p.product_id = '" . (int)$product_id . "' AND p.date_available <= NOW() AND p.status = '1' AND r.status = '1' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY r.date_added DESC LIMIT " . (int)$start . "," . (int)$limit);

		return $query->rows;
	}

	public function getTotalReviewsByProductId($product_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "review r LEFT JOIN " . DB_PREFIX . "product p ON (r.product_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE p.product_id = '" . (int)$product_id . "' AND p.date_available <= NOW() AND p.status = '1' AND r.status = '1' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row['total'];
	}
}