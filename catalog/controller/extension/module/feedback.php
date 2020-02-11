<?php
class ControllerExtensionModuleFeedback extends Controller {
	public function index() {
        $this->load->language('extension/module/feedback');
        
        if (isset($this->config->get('module_feedback_title')[$this->config->get('config_language_id')])) {
			$data['heading_title'] = html_entity_decode($this->config->get('module_feedback_title')[$this->config->get('config_language_id')], ENT_QUOTES, 'UTF-8');
		} else {
            $data['heading_title'] = '';
        }
        
        if (isset($this->config->get('module_feedback_main')[$this->config->get('config_language_id')])) {
			$data['text_main'] = html_entity_decode($this->config->get('module_feedback_main')[$this->config->get('config_language_id')], ENT_QUOTES, 'UTF-8');
        } else {
            $data['text_main'] = '';
        }
        
		$data['email_active'] = $this->config->get('module_feedback_email');

		// Captcha
        if (!$this->customer->isLogged() && $this->config->get('captcha_' . $this->config->get('config_captcha') . '_status') && in_array('feedback', (array)$this->config->get('config_captcha_page'))) {
            $data['captcha'] = $this->load->controller('extension/captcha/' . $this->config->get('config_captcha'), $this->error);
        } else {
            $data['captcha'] = '';
		}
		
		if ($this->customer->isLogged()) {
            $data['name'] = $this->customer->getFirstName() . '&nbsp;' . $this->customer->getLastName();
        } else {
            $data['name'] = '';
        }

        if ($this->customer->isLogged()) {
            $data['phone'] = $this->customer->getTelephone();
        } else {
            $data['phone'] = '';
        }
		
		if (($this->config->get('module_feedback_email') == 1) && $this->customer->isLogged()) {
            $data['email'] = $this->customer->getEmail();
        } elseif ($this->config->get('module_feedback_email') == 1) {
            $data['email'] = '';
        } else {
        }

		return $this->load->view('extension/module/feedback', $data);
	}

	public function write() {
        $this->load->language('extension/module/feedback');

        if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			$data['error_name'] =  $this->language->get('error_name');
            $data['error_phone'] =  $this->language->get('error_phone');
            $data['error_email'] =  $this->language->get('error_email');
            $data['error_enquiry'] =  $this->language->get('error_enquiry');
			$data['text_success'] = $this->language->get('text_success');
			
			if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 25)) {
                $json['error'] = $data['error_name'];
            }

            if ((utf8_strlen($this->request->post['phone']) < 3) || (utf8_strlen($this->request->post['phone']) > 25)) {
                $json['error'] = $data['error_phone'];
            }

            if ($this->config->get('module_feedback_email') == 1) {
            	if (!filter_var($this->request->post['email'], FILTER_VALIDATE_EMAIL)) {
	                $json['error'] = $data['error_email'];
	            }
            }

            if ((utf8_strlen($this->request->post['enquiry']) < 3) || (utf8_strlen($this->request->post['enquiry']) > 3000)) {
                $json['error'] = $data['error_enquiry'];
            }

            // Captcha
			if (!$this->customer->isLogged() && $this->config->get('captcha_' . $this->config->get('config_captcha') . '_status') && in_array('feedback', (array)$this->config->get('config_captcha_page'))) {
                $captcha = $this->load->controller('extension/captcha/' . $this->config->get('config_captcha') . '/validate');

                if ($captcha) {
                    $json['error'] = $captcha;
                }
			}
			
			if (!isset($json['error'])) {
				$json['success'] = $data['text_success'];
				
				$mail = new Mail($this->config->get('config_mail_engine'));
				$mail->parameter = $this->config->get('config_mail_parameter');
				$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
				$mail->smtp_username = $this->config->get('config_mail_smtp_username');
				$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
				$mail->smtp_port = $this->config->get('config_mail_smtp_port');
				$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');
	           

                $isVacancy = false;
                
                if ($this->request->post['source'] == 'Вакансия') {
                    $mail->setTo('hr@purebeauty.store');
                    $isVacancy = true;
                } else {
                    $mail->setTo($this->config->get('config_email'));
                    $isVacancy = false;
                }
                
				$mail->setFrom($this->config->get('config_email'));
				$mail->setSender(html_entity_decode($this->request->post['name'], ENT_QUOTES, 'UTF-8'));
				$mail->setSubject(html_entity_decode(sprintf("Новая заявка - ", $this->request->post['hidden']), ENT_QUOTES, 'UTF-8'));

                if($this->request->post['file']){
                  $this->load->model('tool/upload');
                  $upload_info = $this->model_tool_upload->getUploadByCode($this->request->post['file']);
                  $phyname = DIR_UPLOAD.$upload_info['filename'];
                  $temp_name = DIR_UPLOAD.$upload_info['name'];
                  copy($phyname,$temp_name);
                  $mail->AddAttachment($temp_name);
                }

				if ($this->config->get('module_feedback_email') == 1) {
					$mail->setReplyTo($this->request->post['email']);
					$mail->setText("Источник: " . $this->request->post['hidden'] . "\n От: " . $this->request->post['name'] . ' ' . $this->request->post['lname'] . " \n Телефон: " . $this->request->post['phone'] . "\n Email: " . $this->request->post['email'] . "\n Текст: " . $this->request->post['enquiry']);
				} else {
					$mail->setText("Источник: " . $this->request->post['hidden'] . "\n От: " . $this->request->post['name'] . ' ' . $this->request->post['lname'] . " \n Телефон: " . $this->request->post['phone'] . "\n Email: " . $this->request->post['email'] . "\n Текст: " . $this->request->post['enquiry']);
				}
				$mail->send();

                if(isset($temp_name)){
                    unlink( $temp_name );
                }

                
                if (!$isVacancy) {
                
                //amo
                    //ПРЕДОПРЕДЕЛЯЕМЫЕ ПЕРЕМЕННЫЕ
                    $responsible_user_id = 7292136; //id ответственного по сделке, контакту, компании
                    //$lead_name = "Заказ №" . $orderData['order_id']; //Название добавляемой сделки
                    //$lead_status_id = '17009307'; //id этапа продаж, куда помещать сделку
                    $pipeline_id = '1939621';
                    //$pipeline_id = 'pipeline_961777_1267186131';
                   
                    $contact_name = $this->request->post['name'] . " " . $this->request->post['lname']; //Название добавляемого контакта
                    $contact_phone = $this->request->post['phone']; //Телефон контакта
                    $contact_email = $this->request->post['email']; //Емейл контакта
                    //$contact_message = $text;

                    if ($this->request->post['enquiry'] == "Подписка на рассылку") {
                        $lead_status_id = '29081347';
                        $lead_name = "Подписка на рассылку";
                    } elseif ($this->request->post['enquiry'] == "Заявка на отсутствующий товар") {
                        $lead_status_id = '29088430';
                        $lead_name = $this->request->post['hidden'];
                    } else {
                        $lead_status_id = '29088433';
                        $lead_name = $this->request->post['hidden'];
                        $letter = "Интересуемая позиция - " . $this->request->post['hidden'] . "\nСопроводительное письмо: " . $this->request->post['enquiry'];
                    }

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
                    if ($this->request->post['enquiry'] == "Подписка на рассылку") {
                        
                    } elseif ($this->request->post['enquiry'] == "Заявка на отсутствующий товар") {
                        $data = array(
                            'add' => array(
                                0 => array(
                                    'element_id' => $lead_id,
                                    'element_type' => '2',
                                    'text' => "Интересующий товар - " . $this->request->post['hidden'],
                                    'note_type' => '4',
                                    'responsible_user_id' => $responsible_user_id,
                                    'created_by' => $responsible_user_id,
                                ),
                            ),
                        );
                    } else {
                        $data = array(
                            'add' => array(
                                0 => array(
                                    'element_id' => $lead_id,
                                    'element_type' => '2',
                                    'text' => $letter,
                                    'note_type' => '4',
                                    'responsible_user_id' => $responsible_user_id,
                                    'created_by' => $responsible_user_id,
                                ),
                            ),
                        );
                    }
                    #Формируем ссылку для запроса
                    $link = 'https://' . $subdomain . '.amocrm.ru/api/v2/notes';
                    // Нам необходимо инициировать запрос к серверу. Воспользуемся библиотекой cURL (поставляется в составе PHP). Подробнее о работе с этой библиотекой Вы можете прочитать в мануале. 
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
                    //Теперь мы можем обработать ответ, полученный от сервера. Это пример. Вы можете обработать данные своим способом. 
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
                        }

                    } catch (Exception $E) {
                        die('Ошибка: ' . $E->getMessage() . PHP_EOL . 'Код ошибки: ' . $E->getCode());
                    }
                    //amo
                }

			}
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
}