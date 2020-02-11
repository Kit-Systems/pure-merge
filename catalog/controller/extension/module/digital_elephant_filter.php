<?php
/**
 * Filter v3.0
 * Last update 29.10.2018(d.m.y)
 *
 * Support
 * Site: digital-elephant.com.ua
 * Mail: studio@digital-elephant.com.ua
 *
 * Created by Digital Elephant
 */

/**
 * Render filter panel
 * Class ControllerExtensionModuleDigitalElephantFilter
 */
class ControllerExtensionModuleDigitalElephantFilter extends Controller
{
    /**
     * @var ControllerExtensionModuleDigitalElephantFilterClassesSeo
     */
    private $seo = null;

    /**
     * @var ControllerExtensionModuleDigitalElephantFilterClassesPanel
     */
    private $panel = null;


    public function __construct($registry)
    {
        parent::__construct($registry);
        $this->seo = $this->load->controller('extension/module/digital_elephant_filter_classes/seo/prototype');
        $this->panel = $this->load->controller('extension/module/digital_elephant_filter_classes/panel/prototype');
    }

    public function index()
    {
        $setting = $this->config->get('module_digital_elephant_filter_settings');

        if (!empty($rendered_panel = $this->panel->render($setting))) {
            $this->setStyle();
            $this->setScript();
            $this->runSEO($setting);
            return $rendered_panel;
        }
    }

    protected function setScript()
    {
        $this->document->addScript(HTTP_SERVER . '/catalog/view/javascript/jquery/ui/jquery-ui-slider.min.js');
        $this->document->addScript(HTTP_SERVER . '/catalog/view/javascript/digital_elephant_filter/main.js');
        $this->document->addScript(HTTP_SERVER . '/catalog/view/javascript/digital_elephant_filter/controller.js');
        $this->document->addScript(HTTP_SERVER . '/catalog/view/javascript/digital_elephant_filter/classes/helper.js');
        $this->document->addScript(HTTP_SERVER . '/catalog/view/javascript/digital_elephant_filter/classes/slider_price.js');
        $this->document->addScript(HTTP_SERVER . '/catalog/view/javascript/digital_elephant_filter/classes/url_private.js');
        $this->document->addScript(HTTP_SERVER . '/catalog/view/javascript/digital_elephant_filter/classes/url.js');
        $this->document->addScript(HTTP_SERVER . '/catalog/view/javascript/digital_elephant_filter/classes/pagination.js');
        $this->document->addScript(HTTP_SERVER . '/catalog/view/javascript/digital_elephant_filter/classes/show_more.js');
        $this->document->addScript(HTTP_SERVER . '/catalog/view/javascript/digital_elephant_filter/classes/quantity_products.js');
        $this->document->addScript(HTTP_SERVER . '/catalog/view/javascript/digital_elephant_filter/classes/sync.js');
        $this->document->addScript(HTTP_SERVER . '/catalog/view/javascript/digital_elephant_filter/classes/container_products.js');
        $this->document->addScript(HTTP_SERVER . '/catalog/view/javascript/digital_elephant_filter/classes/panel.js');
        $this->document->addScript(HTTP_SERVER . '/catalog/view/javascript/digital_elephant_filter/classes/limit.js');
        $this->document->addScript(HTTP_SERVER . '/catalog/view/javascript/digital_elephant_filter/classes/sort.js');
    }

    protected function setStyle()
    {
        $this->document->addStyle(HTTP_SERVER . '/catalog/view/javascript/jquery/ui/jquery-ui.min.css');
        $this->document->addStyle(HTTP_SERVER . '/catalog/view/theme/default/stylesheet/digital_elephant_filter.css');
    }

    private function runSEO($setting) {
        if (isset($setting['DEF_settings']['seo']['is_keywords']) && isset($this->request->get['ajax_digital_elephant_filter'])) {
            $this->seo->setMetaKeywords();
        }
    }
}