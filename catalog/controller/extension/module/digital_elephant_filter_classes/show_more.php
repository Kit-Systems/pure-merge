<?php
class ControllerExtensionModuleDigitalElephantFilterClassesShowMore extends Controller
{
    /**
     * @var ControllerExtensionModuleDigitalElephantFilterClassesHelperUrl
     */
    private $helperUrl = null;

    /**
     * @var ControllerExtensionModuleDigitalElephantFilterClassesGetProduct
     */
    private $getProduct = null;


    public function prototype()
    {
        return $this;
    }

    public function __construct($registry)
    {
        parent::__construct($registry);
        $this->helperUrl = $this->load->controller('extension/module/digital_elephant_filter_classes/helper_url/prototype');
        $this->getProduct = $this->load->controller('extension/module/digital_elephant_filter_classes/get_product/prototype');
    }

    public function checkToRender()
    {
        $url_data = $this->helperUrl->getUrlData();
        if (isset($this->request->get['ajax_digital_elephant_filter'])) {
            $data_filter = $this->getProduct->getFilterDataByUrl($url_data);
            $totalProducts = $this->getProduct->getTotalProducts($data_filter);
        } else {

            $this->load->model('catalog/product');
            $args = array(
                'filter_category_id' => $url_data['category_id'],
                'filter_sub_category' => false,
            );

            $totalProducts = $this->model_catalog_product->getTotalProducts($args);
        }

        $endPage = ceil($totalProducts/$url_data['limit']);

        return ($totalProducts && ($endPage != $url_data['page']));
    }

    public function ajaxCheckToRender()
    {
        $json['success'] = $this->checkToRender();
        echo json_encode($json);
    }
}