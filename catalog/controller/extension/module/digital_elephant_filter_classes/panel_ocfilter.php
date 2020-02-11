<?php
Class ControllerExtensionModuleDigitalElephantFilterClassesPanelOCFilter extends Controller
{
    /**
     * @var ControllerExtensionModuleDigitalElephantFilterClassesGetProduct
     */
    private $getProduct = null;

    /**
     * @var ControllerExtensionModuleDigitalElephantFilterClassesHelperUrl
     */
    private $helperUrl = null;

    public function prototype() {
        return $this;
    }

    public function __construct($registry) {
        parent::__construct($registry);
        $this->getProduct = $this->load->controller('extension/module/digital_elephant_filter_classes/get_product/prototype');
        $this->helperUrl = $this->load->controller('extension/module/digital_elephant_filter_classes/helper_url/prototype');
    }

    public function getSectionGroups($setting, $category_id)
    {
        //categories setting
        $section_groups = $this->cache->get('DEF_panel_ocf_' . $category_id);
        if (isset($setting['DEF_settings']['ocfilters']) && ($this->helperUrl->isAjaxRequest() || !$section_groups || !isset($setting['DEF_settings']['cache']['isset']))) {
            $args = array(
                'category_id' => $category_id,
                'filter_sub_category' => true
            );
            $filter_groups = $this->model_extension_module_digital_elephant_filter->getOCFilters($setting['DEF_settings'], $args);

            $filters_type_by_setting = $setting['DEF_settings']['ocfilters'];

            $section_groups = [];
            $sections = [];
            $this->load->model('tool/image');

            $currentUrlData = $this->helperUrl->getUrlData();
            foreach ($filter_groups as $filter_group) {

                $values = array();
                //if (!isset($filters_type_by_setting[$filter_id]['hide']) && count($filter['filter_values']) > 1) {
                if (!isset($filters_type_by_setting[$filter_group['group_id']]['hide'])) {

                    foreach ($filter_group['filter_values'] as $filter_value) {
                        //conversion of the interface

                        $input_label = $filter_value['name'];
                        $is_enable = true;
                        if (isset($setting['DEF_settings']['is_display_total'])) {
                            $url = $this->helperUrl->getUrlData();
                            $url['category_id'] = $category_id;
                            $url['ocfilter'][$filter_group['group_id']] = [];
                            $url['ocfilter'][$filter_group['group_id']][] = $filter_value['filter_id'];

                            $filter_data = $this->getProduct->getFilterDataByUrl($url);
                            $totalProducts = $this->getProduct->getTotalProducts($filter_data);

                            $input_label = $filter_value['name'] . ' (' . $totalProducts . ')';
                            $is_enable = (isset($totalProducts) && $totalProducts > 0);
                        }

                        $values[] = array(
                            'input_value' => $filter_value['filter_id'],
                            'input_name' => 'ocfilter',
                            'input_label' => $input_label,
                            'value_id' => $filter_value['filter_id'],
                            'is_enable' => $is_enable,
                            'is_active' =>  (!empty($currentUrlData['ocfilter'][$filter_group['group_id']]) && in_array($filter_value['filter_id'], $currentUrlData['ocfilter'][$filter_group['group_id']])),
                        );
                    }

                    //type
                    $type = '';
                    if (isset($filters_type_by_setting[$filter_group['group_id']]['type'])) {
                        $type = $filters_type_by_setting[$filter_group['group_id']]['type'];
                    }

                    //open
                    $opened = true;
                    if (isset($filters_type_by_setting[$filter_group['group_id']]['close'])) {
                        $opened = false;
                    }

                    $sections[] = array(
                        'id' => $filter_group['group_id'],
                        'name' => $filter_group['name'],
                        'values' => $values,
                        'type' => $type,
                        'open' => $opened,
                    );
                }
            }

            $section_groups[] = array(
                'group_name' => '',
                'sections' => $sections,
            );
        }

        return $section_groups;
    }
}
