<?php
class ControllerExtensionModuleDigitalElephantFilterClassesGetProduct extends Controller
{
    private $log = null;

    /**
     * @var ControllerExtensionModuleDigitalElephantFilterClassesHelperUrl
     */
    private $helperUrl = null;


    public function __construct($registry)
    {
        parent::__construct($registry);

        $this->log = new Log('DE-filter.2302.log');
        $this->helperUrl = $this->load->controller('extension/module/digital_elephant_filter_classes/helper_url/prototype');
    }


    public function prototype()
    {
        return $this;
    }


    public function index()
    {
        $data_url = $this->helperUrl->getUrlData();

        $this->loadModel();
        $this->loadLanguage();

        $data = $this->getText();
        $data += $this->getFixCoreNotice();

        $data_filter = $this->getFilterDataByUrl($data_url);

        $data['wishlist_products'] = $this->getWishlistProducts();

        $data['cart_products'] = $this->getCartProducts();

        $data['products'] = $this->getProducts($data_filter);

        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');

        if ($data['products']) {
            $this->response->setOutput($this->load->view('product/category', $data));
        }
    }

    protected function getFixCoreNotice()
    {
        $data['breadcrumbs'] = [];
        $data['column_left'] = '';
        $data['column_right'] = '';
        $data['content_top'] = '';
        $data['thumb'] = '';
        $data['categories'] = [];
        $data['text_compare'] = '';
        $data['compare'] = '';
        $data['pagination'] = '';
        $data['results'] = '';
        $data['content_bottom'] = '';
        $data['heading_title'] = '';
        $data['description'] = '';
        $data['text_sort'] = '';
        $data['text_limit'] = '';
        $data['sorts'] = [];
        $data['limits'] = [];
        $data['button_grid'] = '';
        $data['button_list'] = '';

        return $data;
    }


    protected function loadModel()
    {
        $this->load->model('extension/module/digital_elephant_filter');
        $this->load->model('catalog/product');
        $this->load->model('tool/image');
    }

    protected function loadLanguage()
    {
        $this->load->language('product/category');
        $this->load->language('extension/module/digital_elephant_filter');
    }

    public function getFilterDataByUrl($data_url) {
        $filter_data = array(
            'filter_sub_category'   => true,
            'filter_category_id'    => $data_url['category_id'],
            'ocfilter'              => $data_url['ocfilter'],
            'sub_categories'        => $data_url['sub_categories'],
            'manufacturers'         => $data_url['manufacturers'],
            'options'               => $data_url['option'],
            'attributes'            => $data_url['attribute'],
            'price'                 => $data_url['price'],
            'sort'                  => $data_url['sort'],
            'order'                 => $data_url['order'],
            'start'                 => ($data_url['page'] - 1) * $data_url['limit'],
            'limit'                 => $data_url['limit'],
        );

        return $filter_data;
    }

    private function getProducts($data_filter) {
        $results = $this->model_extension_module_digital_elephant_filter->getProducts($data_filter);

        $products = array();

        foreach ($results as $result) {

            $image_width = $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_width');
            $image_height = $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_height');

            /*if ($result['image'] && file_exists(DIR_IMAGE . $result['image'])) {
                $image = $this->model_tool_image->resize($result['image'], $image_width, $image_height);
            } else {
                $image = $this->model_tool_image->resize('placeholder.png', $image_width, $image_height);
            }*/
            if ($result['image']) {
                $image = $this->model_tool_image->resize($result['image'], $image_width, $image_height);
            } else {
                $image = $this->model_tool_image->resize('placeholder.png', $image_width, $image_height);
            }

            if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
                $price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
            } else {
                $price = false;
            }

            if ((float)$result['special']) {
                $special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
            } else {
                $special = false;
            }

            if ($this->config->get('config_tax')) {
                $tax = $this->currency->format((float)$result['special'] ? $result['special'] : $result['price'], $this->session->data['currency']);
            } else {
                $tax = false;
            }

            if ($this->config->get('config_review_status')) {
                $rating = (int)$result['rating'];
            } else {
                $rating = false;
            }

            $description = utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get('theme_' . $this->config->get('config_theme') . '_product_description_length')) . '..';

            $products[] = array(
                'product_id'  => $result['product_id'],
                'thumb'       => $image,
                'name'        => $result['name'],
                'description' => $description,
                'price'       => $price,
                'special'     => $special,
                'tax'         => $tax,
                'minimum'     => $result['minimum'] > 0 ? $result['minimum'] : 1,
                'rating'      => $result['rating'],
                'href'        => $this->url->link('product/product', 'path=' . $this->request->get['path'] . '&product_id=' . $result['product_id'])
            );
        }

        return $products;
    }


    private function getCartProducts() {
        $result = array();
        $cart_products = $this->cart->getProducts();
        if ($cart_products) {
            foreach ($cart_products as $p) {
                $result[] = $p['product_id'];
            }
        }

        return $result;
    }

    private function getWishlistProducts() {
        return isset($this->session->data['wishlist']) ? $this->session->data['wishlist'] : array();
    }

    private function getText() {
        $data = array();
        $data['text_tax'] = $this->language->get('text_tax');
        $data['button_cart'] = $this->language->get('button_cart');
        $data['button_wishlist'] = $this->language->get('button_wishlist');
        $data['button_compare'] = $this->language->get('button_compare');
        $data['button_continue'] = $this->language->get('button_continue');

        return $data;
    }

    public function getTotalProducts($data_filter) {
        $this->loadModel();
        return $this->model_extension_module_digital_elephant_filter->getTotalProducts($data_filter);
    }
}