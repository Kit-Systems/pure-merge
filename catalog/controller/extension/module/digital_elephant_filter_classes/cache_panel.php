<?php
class ControllerExtensionModuleDigitalElephantFilterClassesCachePanel extends Controller
{
    const CACHE_PREFIX  = 'DEF_panel_';
    const TOKEN = 'sdfs23dsf54d2';

    /**
     * @var Cache
     */
    private $cacheDEF = null;

    /**
     * @var ControllerExtensionModuleDigitalElephantFilterClassesPanelCategory
     */
    private $panelCategory = null;

    /**
     * @var ControllerExtensionModuleDigitalElephantFilterClassesPanelManufacturer
     */
    private $panelManufacturer = null;

    /**
     * @var ControllerExtensionModuleDigitalElephantFilterClassesPanelOption
     */
    private $panelOption = null;

    /**
     * @var ControllerExtensionModuleDigitalElephantFilterClassesPanelAttribute
     */
    private $panelAttribute = null;

    /**
     * @var ControllerExtensionModuleDigitalElephantFilterClassesPanelOCFilter
     */
    private $panelOCFilter = null;


    public function prototype()
    {
        return $this;
    }

    public function __construct($registry) {
        parent::__construct($registry);

        $long_time = 30000000;

        $this->cacheDEF = new Cache($this->config->get('cache_type'), $long_time);
        $this->panelCategory = $this->load->controller('extension/module/digital_elephant_filter_classes/panel_category/prototype');
        $this->panelManufacturer = $this->load->controller('extension/module/digital_elephant_filter_classes/panel_manufacturer/prototype');
        $this->panelOption = $this->load->controller('extension/module/digital_elephant_filter_classes/panel_option/prototype');
        $this->panelAttribute = $this->load->controller('extension/module/digital_elephant_filter_classes/panel_attribute/prototype');
        $this->panelOCFilter = $this->load->controller('extension/module/digital_elephant_filter_classes/panel_ocfilter/prototype');
    }

    public function caching()
    {
        if (empty($this->request->post['token']) || $this->request->post['token'] != self::TOKEN) {
            $this->response->setOutput('Permission denied');
            return;
        }

        if ($this->request->post['token']) {
            $this->load->model('extension/module/digital_elephant_filter');

            //post $settings
            $this->clear();
            $settings = $this->config->get('module_digital_elephant_filter_settings');
            $category_ids = $this->model_extension_module_digital_elephant_filter->getAllCategoryIds();
            if ($category_ids) {
                foreach ($category_ids as $category_id) {
                    $category_data = $this->panelCategory->getSectionGroups($settings, $category_id);
                    $this->cacheDEF->set(self::CACHE_PREFIX . 'cat_' . $category_id, $category_data);

                    $manufacturer_data = $this->panelManufacturer->getSectionGroups($settings, $category_id);
                    $this->cacheDEF->set(self::CACHE_PREFIX . 'man_' . $category_id, $manufacturer_data);

                    $option_data = $this->panelOption->getSectionGroups($settings, $category_id);
                    $this->cacheDEF->set(self::CACHE_PREFIX . 'opt_' . $category_id, $option_data);

                    $attribute_data = $this->panelAttribute->getSectionGroups($settings, $category_id);
                    $this->cacheDEF->set(self::CACHE_PREFIX . 'attr_' . $category_id, $attribute_data);

                    $ocfilter_data = $this->panelOCFilter->getSectionGroups($settings, $category_id);
                    $this->cacheDEF->set(self::CACHE_PREFIX . 'ocf_' . $category_id, $ocfilter_data);
                }
            }

            $this->response->setOutput('Cache completed');
        }
    }

    public function clear()
    {
        if (empty($this->request->post['token']) || $this->request->post['token'] != self::TOKEN) {
            $this->response->setOutput('Permission denied');
            return;
        }

        $path = [DIR_CACHE . 'cache.' . self::CACHE_PREFIX . '*'];
        while (count($path) != 0) {
            $next = array_shift($path);
            foreach (glob($next) as $file) {
                unlink($file);
            }
        }

        $this->response->setOutput('Clear completed');
    }
}