<?php

class ControllerExtensionPaymentwooppayAdditional extends Controller
{
	public function index()
	{
		$data['button_confirm'] = $this->language->get('button_confirm');
		$data['button_confirm_action'] = $this->url->link('extension/payment/wooppayAdditional/invoice', '', 'SSL');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/wooppayAdditional')) {
			return $this->load->view($this->config->get('config_template') . '/template/payment/wooppayAdditional', $data);
		} else {
			return $this->load->view('extension/payment/wooppayAdditional', $data);
		}
	}

	private function login()
	{
		$client = new WooppaySoapClient($this->config->get('payment_wooppayAdditional_url'));
		$login_request = new CoreLoginRequest();
		$login_request->username = $this->config->get('payment_wooppayAdditional_merchant');
		$login_request->password = $this->config->get('payment_wooppayAdditional_password');
		return $client->login($login_request) ? $client : false;
	}

	public function invoice()
	{
		$this->load->model('checkout/order');
		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

		try {
			if ($client = $this->login()) {
				$prefix = trim($this->config->get('payment_wooppayAdditional_prefix'));
				$invoice_request = new CashCreateInvoiceByServiceRequest();
				$invoice_request->referenceId = $prefix . $order_info['order_id'];
				$invoice_request->backUrl = $this->url->link('checkout/success');
				$invoice_request->requestUrl = str_replace('&amp;', '&', $this->url->link('extension/payment/wooppayAdditional/callback', 'order=' . $order_info['order_id'] . '&key=' . md5($order_info['order_id']), 'SSL'));
				$invoice_request->addInfo = 'Оплата заказа №' . $order_info['order_id'];
				$invoice_request->amount = (float)$this->currency->format($order_info['total'], 'KZT', '', false);
				$invoice_request->serviceName = $this->config->get('payment_wooppayAdditional_service');
				$invoice_request->deathDate = gmdate('Y-m-d H:i:s', time() + 22500);
				$invoice_request->description = 'Оплата заказа №' . $order_info['order_id'];
				$invoice_request->userEmail = $order_info['email'];
				$invoice_request->userPhone = $order_info['telephone'];
				$invoice_data = $client->createInvoice($invoice_request);
				$this->model_checkout_order->addOrderHistory($order_info['order_id'], $this->config->get('payment_wooppayAdditional_order_processing_status_id'));
				$this->load->model('extension/payment/wooppayAdditional');

				$this->model_extension_payment_wooppayAdditional->addTransaction(['order_id' => $order_info['order_id'], 'wooppay_transaction_id' => $invoice_data->response->operationId]);
				$this->response->redirect($invoice_data->response->operationUrl);
			}
		} catch (Exception $e) {
			$this->log->write(sprintf('Wooppay exception : %s order id (%s)', $e->getMessage(), $this->session->data['order_id']));
		}
		$this->response->redirect($this->url->link('checkout/failure', '', 'SSL'));
	}

	public function callback()
	{
		if ($this->request->get['key'] == md5($this->request->get['order'])) {
			try {
				if ($client = $this->login()) {
					$this->load->model('extension/payment/wooppayAdditional');
					$operationId = $this->model_extension_payment_wooppayAdditional->getTransactionRow($this->request->get['order']);
					if ($operationId) {
						$operationdata_request = new CashGetOperationDataRequest();
						$operationdata_request->operationId = array($operationId['wooppay_transaction_id']);
						$operation_data = $client->getOperationData($operationdata_request);
						if (!isset($operation_data->response->records[0]->status) || empty($operation_data->response->records[0]->status)) {
							exit();
						}
						if ($operation_data->response->records[0]->status == WooppayOperationStatus::OPERATION_STATUS_DONE) {
							$this->load->model('checkout/order');


							/* AVIS INTEGRATION */
							require('./avis.php');

							$order_query = $this->db->query("SELECT *, (SELECT os.name FROM `" . DB_PREFIX . "order_status` os WHERE os.order_status_id = o.order_status_id AND os.language_id = o.language_id) AS order_status FROM `" . DB_PREFIX . "order` o WHERE o.order_id = '" . (int)$this->request->get['order'] . "'");
							$guid = getGUID($order_query->row['shipping_city']);
							if(!$guid)
							{
								$guid = $default_gorod_guid;
							}
							$guidAlm = getGUID('Алматы');
							$options = array(
								'login' => 'Web2',
								'password' => '231642200',
							);
							$client1 = new SoapClient('https://tc.avislogistics.kz/workbase/ws/WebServiceRemotePoint.1cws?wsdl', $options);
							$res = $client1->SozdatAdresnuyuTochku(array('Login' => 'Пьюр Бьюти', 'Password' => 'dc226569-7b5b-4993-b23c-558300c51b19',
								'AdresnayaTochka' => array(
						
								'Naimenovaniye' => 'order_'.$this->request->get['order'],
								'Kod' => 'AT_' . $this->request->get['order'],
								'Gorod_GUID' => $guid,
								'Indeks' => '',
								'Ulitsa' => $order_query->row['shipping_address_1'],
								'Dom' => '-',
								'Korpus' => '',
								'KvartiraOfis' => '',
								'Telefony' => $order_query->row['telephone'],
								'Dopolnitelno' => ''
						
							)));
							$codeAtClient = $res->return->AdresnayaTochka_Kod;
							$codeAtPure = 'PureBeautyAddressPoint';
							$resF = $client1->SozdatCWB(array('Login' => 'Пьюр Бьюти', 'Password' => 'dc226569-7b5b-4993-b23c-558300c51b19',
								'InformatsiyaCWB' => array(

								'NomerCWB_Kliyent' => $order_query->row['order_id'],
								'VidTarifa_GUID' => 'd63c7d77-8ab3-11e5-80e5-0cc47a30d628',
								'SkhemaDostavki_GUID' => 'f79e5e89-5e33-11e8-a22f-002590877a24',
								'Soderzhimoye_GUID' => 'd63c7d7b-8ab3-11e5-80e5-0cc47a30d628',
								'KolichestvoMest' => 1,
								'VesObyem' => 0,
								'VesFakticheskiy' => 1,
								'NaimenovaniyePoluchatelya' => $order_query->row['payment_firstname'] . ' ' . $order_query->row['payment_lastname'],
								'KontaktnoyeLitsoPoluchatelya' => $order_query->row['shipping_firstname'] . ' ' . $order_query->row['shipping_lastname'],
								'AdresPoluchatelya_KodAT' => $codeAtClient,
								//Заполняется либо KodAT либо все реквизиты адресной точки
								//AdresPoluchatelya_Predstavleniye
								//Заполняется либо AdresPoluchatelya_Predstavleniye  либо все реквизиты адресной точки
								//'AdresPoluchatelya_Gorod_GUID' => $guid,
								//'AdresPoluchatelya_Indeks' => '',
								//'AdresPoluchatelya_Ulitsa' => $order_query->row['shipping_address_1'],
								//'AdresPoluchatelya_Dom' => '',
								//'AdresPoluchatelya_Korpus' => '',
								//'AdresPoluchatelya_KvartiraOfis' => '',
								//'AdresPoluchatelya_Dopolnitelno' => '',
								//'AdresPoluchatelya_Shirota' => '',
								//'AdresPoluchatelya_Dolgota' => '',

								'TelefonyPoluchatelya' => $order_query->row['telephone'],

								'AdresOtpravitelya_KodAT' => $codeAtPure,
								//'AdresOtpravitelya_Gorod_GUID' => $guidAlm,
								//'AdresOtpravitelya_Indeks' => '050000',
								//'AdresOtpravitelya_Ulitsa' => 'пр. Назарбаева',
								//'AdresOtpravitelya_Dom' => '137',
								//'AdresOtpravitelya_Korpus' => '',
								//'AdresOtpravitelya_KvartiraOfis' => '',
								//'AdresOtpravitelya_Dopolnitelno' => '',
								//'AdresOtpravitelya_Shirota' => '',
								//'AdresOtpravitelya_Dolgota' => '',

								'TelefonyOtpravitelya' => '+7(707)7770159',
								'SummaNalozhennogoPlatezha' => '',
								'OpisaniyeSoderzhimogo' => 'косметика',
								'SpetsialnyyeInstruktsii' => ''

							)));

							$kod_avis = $resF->return->NomerCWB_Avis;
							
							$comment = 'Заказ оплачен через Wooppay. Ваш код avislogistics ('. $kod_avis .')';

							$this->model_checkout_order->addOrderHistory($this->request->get['order'], $this->config->get('payment_wooppayAdditional_order_success_status_id'), $comment, TRUE);

							/* AVIS INTEGRATION END */


							/*$this->model_checkout_order->addOrderHistory($this->request->get['order'], $this->config->get('payment_wooppayAdditional_order_success_status_id'));
							$order = $this->model_checkout_order->getOrder($this->request->get['order']);
							if ($order['order_status_id'] != $this->config->get('payment_wooppay_order_success_status_id')) {
								$this->log->write('Did not work out to change order status, order id = ' . $this->request->get['order']);
							} else {
								echo json_encode(['data' => 1]);
							}*/
						} else {
							$this->log->write(sprintf('Wooppay callback : счет не оплачен (%s) order id (%s)', $operation_data->response->records[0]->status,
								$this->request->get['order']));
						}
					} else {
						$this->log->write(sprintf('Wooppay order not found : %s order id (%s)', $this->request->get['order'], $this->request->get['order']));
					}
				}
			} catch (Exception $e) {
				$this->log->write(sprintf('Wooppay exception : %s order id (%s)', $e->getMessage(), $this->request->get['order']));
			}
		} else {
			$this->log->write('Wooppay callback : неверный key или order : ' . print_r($_REQUEST, true));
		}
	}
}

class WooppaySoapClient
{

	private $c;

	public function __construct($url, $options = array())
	{
		try {
			$this->c = new SoapClient($url, $options);
		} catch (Exception $e) {
			throw new WooppaySoapException($e->getMessage());
		}
		if (empty($this->c)) {
			throw new WooppaySoapException('Cannot create instance of Soap client');
		}
	}

	/**
	 * @param $method
	 * @param $data
	 * @return WooppaySoapResponse
	 * @throws BadCredentialsException
	 * @throws UnsuccessfulResponseException
	 * @throws WooppaySoapException
	 */
	public function __call($method, $data)
	{
		try {

			$response = $this->c->$method($data[0]);
		} catch (Exception $e) {
			throw new WooppaySoapException($e->getMessage());
		}
		$response = new WooppaySoapResponse($response);
		switch ($response->error_code) {
			case 0:
				return $response;
				break;
			case 5:
				throw new BadCredentialsException();
				break;
			default:
				throw new UnsuccessfulResponseException('Error code ' . $response->error_code);
		}

	}

	public function login(CoreLoginRequest $data)
	{
		$response = $this->core_login($data);

		if (isset($response->response->session)) {
			$this->c->__setCookie('session', $response->response->session);
			return true;
		} else {
			return false;
		}
	}

	public function getOperationData(CashGetOperationDataRequest $data)
	{
		return $this->cash_getOperationData($data);
	}

	public function createInvoice(CashCreateInvoiceByServiceRequest $data)
	{
		return $this->cash_createInvoiceByService($data);
	}

	public function getLastDialog()
	{
		return array('req' => $this->c->__getLastRequest(), 'res' => $this->c->__getLastResponse());
	}
}

class CoreLoginRequest
{
	/**
	 * @var string $username
	 * @soap
	 */
	public $username;
	/**
	 * @var string $password
	 * @soap
	 */
	public $password;
	/**
	 * @var string $captcha
	 * @soap
	 */
	public $captcha = null;
}

class CashGetOperationDataRequest
{
	/**
	 * @var $operationId array
	 */
	public $operationId;

}

class CashCreateInvoiceRequest
{
	/**
	 * @var string $referenceId
	 * @soap
	 */
	public $referenceId;
	/**
	 * @var string $backUrl
	 * @soap
	 */
	public $backUrl;
	/**
	 * @var string $requestUrl
	 * @soap
	 */
	public $requestUrl = '';
	/**
	 * @var string $addInfo
	 * @soap
	 */
	public $addInfo;
	/**
	 * @var float $amount
	 * @soap
	 */
	public $amount;
	/**
	 * @var string $deathDate
	 * @soap
	 */
	public $deathDate;
	/**
	 * @var int $serviceType
	 * @soap
	 */
	public $serviceType = null;
	/**
	 * @var string $description
	 * @soap
	 */
	public $description = '';
	/**
	 * @var int $orderNumber
	 * @soap
	 */
	public $orderNumber = null;
	/**
	 * @var string $userEmail
	 * @soap
	 */
	public $userEmail = null;
	/**
	 * @var string $userPhone
	 * @soap
	 */
	public $userPhone = null;
}

class CashCreateInvoiceExtendedRequest extends CashCreateInvoiceRequest
{
	/**
	 * @var string $userEmail
	 * @soap
	 */
	public $userEmail = '';
	/**
	 * @var string $userPhone
	 * @soap
	 */
	public $userPhone = '';
}

class CashCreateInvoiceExtended2Request extends CashCreateInvoiceExtendedRequest
{
	/**
	 * @var int $cardForbidden
	 * @soap
	 */
	public $cardForbidden;
}

class CashCreateInvoiceByServiceRequest extends CashCreateInvoiceExtended2Request
{
	/**
	 * @var string $serviceName
	 * @soap
	 */
	public $serviceName;
}

class WooppaySoapResponse
{

	public $error_code;
	public $response;

	public function __construct($response)
	{

		if (!is_object($response)) {
			throw new BadResponseException('Response is not an object');
		}

		if (!isset($response->error_code)) {
			throw new BadResponseException('Response do not contains error code');
		}
		$this->error_code = $response->error_code;

		if (!property_exists($response, 'response')) {
			throw new BadResponseException('Response do not contains response body');
		}
		$this->response = $response->response;
	}
}

class WooppayOperationStatus
{
	/**
	 * Новая
	 */
	const OPERATION_STATUS_NEW = 1;
	/**
	 * На рассмотрении
	 */
	const OPERATION_STATUS_CONSIDER = 2;
	/**
	 * Отклонена
	 */
	const OPERATION_STATUS_REJECTED = 3;
	/**
	 * Проведена
	 */
	const OPERATION_STATUS_DONE = 4;
	/**
	 * Сторнирована
	 */
	const OPERATION_STATUS_CANCELED = 5;
	/**
	 * Сторнирующая
	 */
	const OPERATION_STATUS_CANCELING = 6;
	/**
	 * Удалена
	 */
	const OPERATION_STATUS_DELETED = 7;
	/**
	 * На квитовании
	 */
	const OPERATION_STATUS_KVITOVANIE = 4;
	/**
	 * На ожидании подверждения или отказа мерчанта
	 */
	const OPERATION_STATUS_WAITING = 9;
}

class WooppaySoapException extends Exception
{
}

class BadResponseException extends WooppaySoapException
{
}

class UnsuccessfulResponseException extends WooppaySoapException
{
}

class BadCredentialsException extends UnsuccessfulResponseException
{
}

?>