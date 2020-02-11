<?php
date_default_timezone_set('UTC');
class anyDSVXLSSettingVersion {
    private $data = array();
    private $path_oc_version;
    private $language;
    private $db;
    private $yml = array(
        'params_as_column'=>1,
        'group_attribute_name'=>'Характеристики',
        'max_nodes_to_write'=>1000,
        'features_detail'=>1,
        'delimeter_option_value'=>'####',
        'attribute_whis_group'=>0,
        'option_value_by_column'=>'name_column',
        'offers'=>array(),
        'cache_offers'=>array(),
        'first_row'=>array(),
        'memory_usage'=>0,
        'max_exe_time'=>0,
        'count_rows'=>0,
        'delete_columns'=>array(),
        'attribute_columns'=>array(),
        'option_columns'=>array(),
        'option_type'=>'select',
        'categories'=>array(),
        'delete_by_category_id'=>array(),
        'delete_by_vendor'=>array(),
        'set_products_by_category_id' => array(),
        'category_path'=>array()
    );
    private $yml_cache = array(
            'status'=>0,
            'count_nodes'=>1000,
            'offers_file_name_prefix'=>'oanydsvxlsym_yml_cache_',
            'categories_file_name_prefix'=>'oanydsvxlsym_yml_cats_cache_',
            'offers_file_name_prefix_csv'=>'oanydsvxlsym_csv_'
    );
    private $settings = array(
        'count_group_id_box'=>3,
        'max_curl_timeout'=>5000,
        'actions_with_data_group'=>array(
            'category_mapping' => 1,
            'action_before_import' => 1,
            'composite_id' => 1
        ),
        'edition'=>array(
            'version_host'=>'anycsv-dsv-xls-yml.ocext',
            'extension'=>'csvxls_pe',
            'version'=>'6.1.0.0',
            'import_formats' => array(
                'csv',
                'dsv',
                'xls',
                'xlsx',
                'yml'
            ),
            'export_formats' => array(
                'csv',
                'dsv'
            )
        ),
        'functional'=>array(
            'automatization_by_link'=>1,
            'unzip'=>1,
            'pricing'=>1,
            'stocking'=>1,
            'currency_convert'=>1,
            'log'=>1,
            'log_on_email'=>1,
            'smart_exchange'=>1,
            'yml_to_dsv'=>1,
            'export_to_xls'=>1,
            'order_export'=>1,
            'self_column' => 1,
            'php_after_import' => 1
        ),
    );
    
    private $specification = array(
        'name' => "YML",
        'id_specification' => "YML___1_0_0_0",
        'version' => "1_0_0_0",
        'specification_valid_text'=>'yml_catalog',
        'check_valid_status'=>FALSE
    );
    
    public function getSpecifications() {
        
        $others = $this->getSuppliersFeedSource();
        
        $specification[$this->specification['id_specification']] = $this->specification;
        
        foreach ($others as $other) {
            
            $specification[$other['id_specification']] = $other;
            
        }
        
        return $specification;
        
    }

    public function __construct($registry,$path_oc_version,$language,$load,$db=array()) {
        $this->registry = $registry;
        $this->language = $language;
        $this->load = $load;
        $this->path_oc_version = $path_oc_version;
        $this->db = $db;
        $this->setSetings();
    }
    
    public function setSetings() {
        foreach ($this->settings as $key => $value) {
            $this->data[$key] = $value;
        }
    }
    
    public function get($key) {
            return (isset($this->data[$key]) ? $this->data[$key] : null);
    }
    
    public function getData() {
            return $this->data;
    }

    public function set($key, $value) {
            $this->data[$key] = $value;
    }
    
    public function getIConvCodes(){
        $codes = array('Windows 1251'=>'cp1251','UTF-8'=>'UTF-8','Windows 1252'=>'cp1252');
        return $codes;
    }
    
    public function getSqlWhereOperators() {
        $operators = array('&lt;'=>'&lt;','≤'=>'≤','='=>'=','≥'=>'≥','&gt;'=>'&gt;','≠'=>'≠','±'=>'±','like_left'=>'Содержит префикс слева','like_right'=>'Содержит префикс справа','like'=>'Содержит','not_like_left'=>'Не содержит префикс слева' ,'not_like_right'=>'Не содержит префикс справа','not_like'=>'Не содержит');
        return $operators;
    }

    public function getSettingVersionSettings(){
        return $this->settings;
    }

    public function getGeneralSettingsFields(){
        $this->load->language($this->path_oc_version.'/csv_ocext_dmpro');
        $general_setting = array(
                'product'=>array(
                    'name'=>$this->language->get('entry_types_data_general_setting_product'),
                    'additinal_settings'=>array(
                        'quantity_default'  => array('element'=>'input','placeholder'=>$this->language->get('type_data_general_setting_quantity_default')),
                        'dis_by_quan'  => array('element'=>'input','default_value'=>'0','placeholder'=>$this->language->get('type_data_general_setting_dis_by_quan'),'export'=>0),
                        'skip_by_quan'  => array('element'=>'input','default_value'=>'0','placeholder'=>'Пропускать товар (при импорте не импортировать, при экспорте не экспортировать), если количество больше или равно'),
                        'status_enable'  => array('element'=>'select','placeholder'=>$this->language->get('type_data_general_setting_status_enable'),'export'=>0),
                        'stock_status_id_by_quantity'  => array('element'=>'select','placeholder'=>$this->language->get('type_data_general_setting_stock_status_id_by_quantity'), 'data-original-title'=>$this->language->get('type_data_general_setting_stock_status_id_by_quantity_data-original-title'),'import'=>1,'export'=>0),
                        'stock_status_id_by_price'  => array('element'=>'select','placeholder'=>$this->language->get('type_data_general_setting_stock_status_id_by_price'), 'data-original-title'=>$this->language->get('type_data_general_setting_stock_status_id_by_price_data-original-title'),'import'=>1,'export'=>0),
                        'seo_url_generator'  => array('element'=>'select','placeholder'=>$this->language->get('type_data_general_setting_seo_url_generator'),'export'=>0),
                        'categories_filter'  => array('element'=>'select','import'=>0,'placeholder'=>$this->language->get('type_data_general_setting_categories_filter')),
                        'manufacturer_filter'  => array('element'=>'select','import'=>0,'placeholder'=>$this->language->get('type_data_general_setting_manufacturer_filter')),
                        'prodict_id_from_filter'  => array('element'=>'input','placeholder'=>$this->language->get('type_data_general_setting_prodict_id_from_filter'),'import'=>0),
                        'prodict_id_to_filter'  => array('element'=>'input','placeholder'=>$this->language->get('type_data_general_setting_prodict_id_to_filter'),'import'=>0),
                        'related_data_column'  => array('element'=>'select','placeholder'=>$this->language->get('type_data_general_setting_related_data_column'), 'data-original-title'=>$this->language->get('type_data_general_setting_related_data_column_data-original-title'),'import'=>0),
                        'no_csv_headers'  => array('element'=>'select','placeholder'=>$this->language->get('type_data_general_setting_no_csv_headers'), 'data-original-title'=>$this->language->get('type_data_general_setting_no_csv_headers_data-original-title'),'import'=>0),
                        'skip_by_no_image'  => array('element'=>'select','placeholder'=>$this->language->get('type_data_general_setting_skip_by_no_image'), 'data-original-title'=>$this->language->get('type_data_general_setting_skip_by_no_image_data-original-title'),'import'=>1,'export'=>0),
                        'image_upload_curl'  => array('element'=>'select','placeholder'=>$this->language->get('type_data_general_setting_image_upload_curl'), 'data-original-title'=>$this->language->get('type_data_general_setting_image_upload_curl_data-original-title'),'import'=>1,'export'=>0),
                        'image_new_dir'  => array('element'=>'select','placeholder'=>$this->language->get('type_data_general_setting_image_new_dir'), 'data-original-title'=>$this->language->get('type_data_general_setting_image_new_dir_data-original-title'),'import'=>1,'export'=>0),
                        'image_crop'  => array('element'=>'select','placeholder'=>$this->language->get('type_data_general_setting_image_crop'), 'data-original-title'=>$this->language->get('type_data_general_setting_image_crop_data-original-title'),'import'=>1,'export'=>1),
                        'export_where_rules'  => array('element'=>'custom','placeholder'=>'Отбор по значению в данных товара', 'data-original-title'=>'','import'=>0,'export'=>1),
                    )
                ),
                'order_product'=>array(
                    'name'=>$this->language->get('entry_types_data_general_setting_order'),
                    'additinal_settings'=>array(
                        'order_id_from_filter'  => array('element'=>'input','placeholder'=>$this->language->get('type_data_general_setting_order_id_from_filter'),'import'=>0),
                        'order_id_to_filter'  => array('element'=>'input','placeholder'=>$this->language->get('type_data_general_setting_order_id_to_filter'),'import'=>0),
                        'date_from_filter'  => array('element'=>'input','placeholder'=>$this->language->get('type_data_general_setting_date_from_filter'),'import'=>0),
                        'date_to_filter'  => array('element'=>'input','placeholder'=>$this->language->get('type_data_general_setting_date_to_filter'),'import'=>0),
                        'today_filter'  => array('element'=>'select','placeholder'=>$this->language->get('type_data_general_setting_today_filter'), 'data-original-title'=>$this->language->get('type_data_general_setting_today_filter-original-title'),'import'=>0),
                        'send_to_email'  => array('element'=>'input','placeholder'=>$this->language->get('type_data_general_setting_send_to_email'), 'data-original-title'=>'','import'=>0),
                        'related_data_column'  => array('element'=>'select','placeholder'=>$this->language->get('type_data_general_setting_related_data_column'), 'data-original-title'=>$this->language->get('type_data_general_setting_related_data_column_data-original-title'),'import'=>0),
                        'no_csv_headers'  => array('element'=>'select','placeholder'=>$this->language->get('type_data_general_setting_no_csv_headers'), 'data-original-title'=>$this->language->get('type_data_general_setting_no_csv_headers_data-original-title'),'import'=>0),
                        'export_where_rules'  => array('element'=>'custom','placeholder'=>'Отбор по значению в товаре в заказе', 'data-original-title'=>'','import'=>0,'export'=>1),
                        'export_where_product_rules'  => array('element'=>'custom','placeholder'=>'Отбор по значению в данных в карточке товара', 'data-original-title'=>'','import'=>0,'export'=>1),
                        'export_where_order_rules'  => array('element'=>'custom','placeholder'=>'Отбор по значению в данных заказа', 'data-original-title'=>'','import'=>0,'export'=>1),
                    )
                ),
                'manufacturer'=>array(
                    'name'=>$this->language->get('entry_types_data_general_setting_manufacturer'),
                    'additinal_settings'=>array(
                        'seo_url_generator'  => array('element'=>'select','placeholder'=>$this->language->get('type_data_general_setting_seo_url_generator'),'export'=>0),
                        'image_upload_curl'  => array('element'=>'select','placeholder'=>$this->language->get('type_data_general_setting_image_upload_curl'), 'data-original-title'=>$this->language->get('type_data_general_setting_image_upload_curl_data-original-title'),'import'=>1,'export'=>0),
                        'export_where_rules'  => array('element'=>'custom','placeholder'=>'Отбор по полю в базе данных', 'data-original-title'=>'','import'=>0,'export'=>1),
                    )
                ),
                'category'=>array(
                    'name'=>$this->language->get('entry_types_data_general_setting_category'),
                    'additinal_settings'=>array(
                        'status_enable'  => array('element'=>'select','placeholder'=>$this->language->get('type_data_general_setting_status_enable'),'export'=>0),
                        'seo_url_generator'  => array('element'=>'select','placeholder'=>$this->language->get('type_data_general_setting_seo_url_generator'),'export'=>0),
                        'image_upload_curl'  => array('element'=>'select','placeholder'=>$this->language->get('type_data_general_setting_image_upload_curl'), 'data-original-title'=>$this->language->get('type_data_general_setting_image_upload_curl_data-original-title'),'import'=>1,'export'=>0),
                        'export_where_rules'  => array('element'=>'custom','placeholder'=>'Отбор по полю в базе данных', 'data-original-title'=>'','import'=>0,'export'=>1),
                        
                    )
                ),
                'review'=>array(
                    'name'=>$this->language->get('entry_types_data_general_setting_review'),
                    'additinal_settings'=>array(
                        'status_enable'  => array('element'=>'select','placeholder'=>$this->language->get('type_data_general_setting_status_enable'),'export'=>0),
                        'export_where_rules'  => array('element'=>'custom','placeholder'=>'Отбор по полю в базе данных', 'data-original-title'=>'','import'=>0,'export'=>1),
                    )
                ),
            );
        return $general_setting;
    }
    
    public function writeXls($csv_rows_result,$first_write,$odmpro_tamplate_data,$start,$limit){
        
        //$file_name_and_path = $odmpro_tamplate_data['export_file_name_xls'];
        /*
         * 
         * 
         * include_once DIR_SYSTEM.'PHPExcelOcext/Classes/PHPExcel.php';
        include_once DIR_SYSTEM.'PHPExcelOcext/Classes/PHPExcel/Writer/Excel5.php';
        include_once DIR_SYSTEM.'PHPExcelOcext/Classes/PHPExcel/Writer/PDF.php';
        include_once DIR_SYSTEM.'PHPExcelOcext/Classes/PHPExcel/Writer/PDF/DomPDF.php';
        
        $rendererName = PHPExcel_Settings::PDF_RENDERER_DOMPDF;
        $rendererLibrary = 'domPDF0.6.0beta3';
        $rendererLibraryPath = dirname(__FILE__). 'libs/classes/dompdf' . $rendererLibrary;
        PHPExcel_Settings::setPdfRenderer($rendererName,$rendererLibraryPath);
         * 
         * 
         * 
        */
        
        if(file_exists(DIR_SYSTEM.'PHPExcelOcext/Classes/PHPExcel.php')){
            if(!class_exists('PHPExcel')){
                include_once DIR_SYSTEM.'PHPExcelOcext/Classes/PHPExcel.php';
                include_once DIR_SYSTEM.'PHPExcelOcext/Classes/PHPExcel/Writer/Excel5.php';
            }
        }else{
            $reslut['errors'] = "Не найдены PHPExcel Classes. Создание файла заказа невозможно";
            return $reslut;
        }
        
        $file_name = $odmpro_tamplate_data['export_file_name_xls'];
        
        if($odmpro_tamplate_data['file_name_write_time_xls']){
            
            $file_name.= date("Y-m-d_H:i:s");
            
        }
        
        $file_name .= '.xlsx';
        
        $path_parts=array();
        if($file_name){
            $path_parts = explode('/', $file_name);
        }
        if($path_parts){
            foreach ($path_parts as $key => $value) {
                if(!$value){
                    unset($path_parts[$key]);
                }
            }
        }
        
        $file_name = rawurldecode($path_parts[count($path_parts)-1]);

        unset($path_parts[count($path_parts)-1]);
        
        $path = '';
        if($path_parts){
            $path = implode('/', $path_parts);
        }
        
        $dir_name = DIR_IMAGE;
        
        if($path){

            $path_parts = explode('/', $path);

            foreach ($path_parts as $new_dir_name) {

                $dir_name .= $new_dir_name.'/';

                if(!file_exists($dir_name)){

                    mkdir($dir_name,0777);

                }

            }

        }
        
        $file_name_and_path = $dir_name.$file_name;
        
        if($first_write){
            
            $xls = new PHPExcel();
            
        }elseif(file_exists($file_name_and_path)){
            
            $xls = PHPExcel_IOFactory::load($file_name_and_path);
            
        }
        
        $xls->setActiveSheetIndex(0);
        
        $sheet = $xls->getActiveSheet();
        
        $columns = array();
        
        $start += 1;
        
        if($first_write && $csv_rows_result){
            
            if(isset($csv_rows_result[0])){
                
                foreach ($csv_rows_result[0] as $column_name => $tmp){
                
                    $columns[] = $column_name;

                }

                unset($csv_rows_result[0]);
                
            }
            
            $c = 0;
        
            foreach($columns as $column_value){

                $sheet->setCellValueByColumnAndRow($c,$start,$column_value);

                $c++;

            }
            
            $start++;
            
        }
        
        foreach($csv_rows_result as $csv_row_result){
            
            $c = 0;
            
            foreach($csv_row_result as $csv_cell){
                
                if(is_array($csv_cell)){
                    
                    foreach($csv_cell as $csv_cell_2){
                        
                        $sheet->setCellValueByColumnAndRow($c,$start++,$csv_cell_2);
                        
                    }
                    
                    $c++;
                    
                }else{
                    
                    $sheet->setCellValueByColumnAndRow($c,$start,$csv_cell);

                    $c++;
                    
                }
                
            }
            
            $start++;
            
        }
        
        $objWriter = PHPExcel_IOFactory::createWriter($xls, 'Excel2007');
        
        $objWriter->save($file_name_and_path);
        
        $reslut['success'] = "Файл успешно записан";
        
        return $reslut;
        
    }
    
    public function getAbstractFields(){
        $this->load->language($this->path_oc_version.'/csv_ocext_dmpro');
        /*
         * Поля, которых нет в базе или те, которым нужно добавить дополнительные настройки
         * Название полей, с раширенными настройками, должны быть переопределены на уникальные в 'field', чтобы избежать прямого импорта без предварительной обработки
         */
        $abstract_field = array(
                'product'=>array(
                    'image'=>array(
                        'name'=>$this->language->get('entry_abstract_field_product_image'),
                        'field'=>'image_advanced',
                        'additinal_settings'=>array(
                            'image_upload'=>array('element'=>'select'),
                            'image_new_path'=>array('element'=>'input','placeholder'=>$this->language->get('entry_type_data_column_delimiter_image_new_path'),'data-original-title'=>$this->language->get('entry_type_data_column_delimiter_image_new_path_data-original-title'),'export'=>0),
                            'image_new_name'=>array('element'=>'select','data-original-title'=>$this->language->get('entry_type_data_column_image_new_name'),'export'=>0)
                        )
                    ),
                    'images'=>array(
                        'name'=>$this->language->get('entry_abstract_field_product_images'),
                        'field'=>'images',
                        'additinal_settings'=>array(
                            'delimeter'=>array('element'=>'input','placeholder'=>$this->language->get('entry_type_data_column_delimiter_images'),'data-original-title'=>$this->language->get('entry_type_data_column_delimiter')),
                            'image_upload'=>array('element'=>'select'),
                            'image_new_path'=>array('element'=>'input','placeholder'=>$this->language->get('entry_type_data_column_delimiter_image_new_path'),'data-original-title'=>$this->language->get('entry_type_data_column_delimiter_image_new_path_data-original-title'),'export'=>0),
                            'image_new_name'=>array('element'=>'select','data-original-title'=>$this->language->get('entry_type_data_column_image_new_name'),'export'=>0),
                            'first_image_main'=>array('element'=>'select','data-original-title'=>$this->language->get('entry_type_data_column_first_image_main'),'export'=>0),
                            'first_image_add'=>array('element'=>'select','data-original-title'=>$this->language->get('entry_type_data_column_first_image_add'),'import'=>0)
                        )
                    ),
                    'manufacturer_name'=>array(
                        'name'=>$this->language->get('entry_abstract_field_product_manufacturer_name'),
                        'field'=>'manufacturer_name',
                    ),
                    'category_whis_path'=>array(
                        'field'=>'category_whis_path',
                        'name'=>$this->language->get('entry_abstract_field_category_whis_path'),
                        'additinal_settings'=>array(
                            'delimeter'=>array('element'=>'input','placeholder'=>$this->language->get('entry_type_data_column_delimiter_category_whis_path'),'data-original-title'=>$this->language->get('entry_type_data_column_delimiter')),
                            'delimeter_2'=>array('element'=>'input','placeholder'=>$this->language->get('entry_type_data_column_delimeter_2_category_whis_path'),'data-original-title'=>$this->language->get('entry_type_data_column_delimeter_2'),'export'=>0),
                            'main_category'=>array('element'=>'select','data-original-title'=>$this->language->get('entry_type_data_column_main_category'),'export'=>0),
                            'all_product_category'=>array('element'=>'select','data-original-title'=>$this->language->get('entry_type_data_column_all_product_category'),'export'=>0),
                        )
                    ),
                    'category_id'=>array(
                        'name'=>$this->language->get('entry_abstract_field_product_category_id'),
                        'field'=>'category_id',
                        'additinal_settings'=>array(
                            'main_category'=>array('element'=>'select','data-original-title'=>$this->language->get('entry_type_data_column_main_category'),'export'=>0),
                        )
                    ),
                    'category_name_and_parent_level'=>array(
                        'name'=>$this->language->get('entry_abstract_field_product_category_name_and_parent_level'),
                        'field'=>'category_name_and_parent_level',
                        'additinal_settings'=>array(
                            'main_category'=>array('element'=>'select','data-original-title'=>$this->language->get('entry_type_data_column_main_category'),'export'=>0),
                            'parent_level'=>array('element'=>'input','placeholder'=>$this->language->get('entry_type_data_column_parent_level'),'data-original-title'=>$this->language->get('entry_type_data_column_parent_level-data-original-title'),'default_value'=>'0'),
                            'parent_category_id'=>array('element'=>'select','data-original-title'=>$this->language->get('entry_type_data_column_parent_category_id-data-original-title')),
                            'all_product_category'=>array('element'=>'select','data-original-title'=>$this->language->get('entry_type_data_column_all_product_category'),'export'=>0),
                        )
                    ),
                    'price'=>array(
                        'name'=>$this->language->get('entry_abstract_field_product_price'),
                        'field'=>'price_advanced',
                        'additinal_settings'=>array(
                            'price_rate'=>array('element'=>'input','placeholder'=>$this->language->get('entry_type_data_column_price_rate')),
                            'price_delta'=>array('element'=>'input','placeholder'=>$this->language->get('entry_type_data_column_price_delta')),
                            'price_around'=>array('element'=>'select'),
                            'price_range'=> array('element'=>'input','range'=>5,'data-original-title'=>$this->language->get('entry_type_data_column_range_price-data-original-title'))
                        ),
                    ),
                    'quantity'=>array(
                        'name'=>$this->language->get('entry_abstract_field_product_quantity'),
                        'field'=>'quantity_advanced', 
                        'additinal_settings'=>array(
                            'quantity_update'=>array('element'=>'input','placeholder'=>$this->language->get('entry_type_data_column_quantity_update')),
                            'quantity_group'=>array('element'=>'input','group'=>5,'data-original-title'=>$this->language->get('entry_type_data_column_quantity_group-data-original-title'))
                        )
                    ),
                    'seo_url'=>array(
                        'name'=>$this->language->get('entry_abstract_field_product_seo_url'),
                        'field'=>'seo_url'
                    ),
                    'seo_url_aut'=>array(
                        'name'=>$this->language->get('entry_abstract_field_product_seo_url_aut'),
                        'field'=>'seo_url_aut',
                        'import'=>0
                    ),
                    'url_whis_params'=>array(
                        'name'=>$this->language->get('entry_abstract_field_product_url_whis_params'),
                        'field'=>'url_whis_params',
                        'import'=>0
                    )
                ),
                'product_image'=>array(
                    'image'=>array(
                        'name'=>$this->language->get('entry_abstract_field_product_image'),
                        'field'=>'image_advanced',
                        'additinal_settings'=>array(
                            'image_upload'=>array('element'=>'select'),
                            'image_new_path'=>array('element'=>'input','placeholder'=>$this->language->get('entry_type_data_column_delimiter_image_new_path'),'data-original-title'=>$this->language->get('entry_type_data_column_delimiter_image_new_path_data-original-title'),'export'=>0),
                            'image_new_name'=>array('element'=>'select','data-original-title'=>$this->language->get('entry_type_data_column_image_new_name'),'export'=>0)
                        )
                    ),
                    'images'=>array(
                        'name'=>$this->language->get('entry_abstract_field_product_images'),
                        'field'=>'images',
                        'additinal_settings'=>array(
                            'delimeter'=>array('element'=>'input','placeholder'=>$this->language->get('entry_type_data_column_delimiter_images'),'data-original-title'=>$this->language->get('entry_type_data_column_delimiter')),
                            'image_upload'=>array('element'=>'select'),
                            'image_new_path'=>array('element'=>'input','placeholder'=>$this->language->get('entry_type_data_column_delimiter_image_new_path'),'data-original-title'=>$this->language->get('entry_type_data_column_delimiter_image_new_path_data-original-title'),'export'=>0),
                            'image_new_name'=>array('element'=>'select','data-original-title'=>$this->language->get('entry_type_data_column_image_new_name'),'export'=>0),
                            'first_image_main'=>array('element'=>'select','data-original-title'=>$this->language->get('entry_type_data_column_first_image_main'),'export'=>0),
                            'first_image_add'=>array('element'=>'select','data-original-title'=>$this->language->get('entry_type_data_column_first_image_add'),'import'=>0)
                        )
                    )
                ),
                'product_attribute'=>array(
                    'text'=>array(
                        'name'=>$this->language->get('entry_abstract_field_product_attribute_value'),
                        'field'=>'attribute_value',
                        'additinal_settings'=>array(
                            'attribute_group_id___attribute_id'=>array('element'=>'select'),
                            'attribute_name_field'=>array('element'=>'select'),
                            'attribute_group_id'=>array('element'=>'select')
                        )
                    ),
                    'attribute_values'=>array(
                        'name'=>$this->language->get('entry_abstract_field_product_attribute_values'),
                        'field'=>'attribute_values',
                        'additinal_settings'=>array(
                            'delimeter'=>array('element'=>'input','placeholder'=>$this->language->get('entry_type_data_column_delimiter_attribute_values'),'default_value'=>'|','data-original-title'=>$this->language->get('entry_type_data_column_delimiter')),
                            'attribute_name_field'=>array('element'=>'select'),
                            'attribute_group_id'=>array('element'=>'select')
                        ),
                        'help'=>$this->language->get('attribute_values_whis_attribute_values_help')
                    ),
                    'attribute_values_whis_attrubute_name'=>array(
                        'name'=>$this->language->get('entry_abstract_field_product_attribute_values_whis_attrubute_name'),
                        'field'=>'attribute_values_whis_attrubute_name',
                        'additinal_settings'=>array('attribute_group_id'=>array('element'=>'select'),'delimiter_2'=>array('element'=>'input','placeholder'=>$this->language->get('entry_type_data_column_delimiter_2'),'default_value'=>'---'),'delimiter_1'=>array('element'=>'input','placeholder'=>$this->language->get('entry_type_data_column_delimiter_1'),'default_value'=>'|')),
                        'help'=>$this->language->get('attribute_values_whis_attrubute_name_help')
                    ),
                    'attribute_values_whis_attrubute_name_and_group_name'=>array(
                        'name'=>$this->language->get('entry_abstract_field_product_attribute_values_whis_attrubute_name_and_group_name'),
                        'field'=>'attribute_values_whis_attrubute_name_and_group_name',
                        'help'=>$this->language->get('attribute_values_whis_attrubute_group_name_help'),
                        'additinal_settings'=>array(
                            'delimiter_2'=>array('element'=>'input','placeholder'=>$this->language->get('entry_type_data_column_delimiter_2'),'default_value'=>'---'),'delimiter_1'=>array('element'=>'input','placeholder'=>$this->language->get('entry_type_data_column_delimiter_1'),'default_value'=>'|'),
                            'attribute_group_id'=>array('element'=>'select','import'=>0)
                        )
                    )
                ),
                'product_related'=>array(
                    'relate_by_product_id'=>array(
                        'name'=>$this->language->get('entry_abstract_field_product_relate_by_product_id'),
                        'field'=>'relate_by_product_id',
                        'additinal_settings'=>array('delimeter'=>array('element'=>'input','placeholder'=>$this->language->get('entry_type_data_column_delimiter_relate_by_product_id'),'data-original-title'=>$this->language->get('entry_type_data_column_delimiter'))),
                    ),
                    'relate_by_sku'=>array(
                        'name'=>$this->language->get('entry_abstract_field_product_relate_by_sku'),
                        'field'=>'relate_by_sku',
                        'additinal_settings'=>array('delimeter'=>array('element'=>'input','placeholder'=>$this->language->get('entry_type_data_column_delimiter_relate_by_sku'),'data-original-title'=>$this->language->get('entry_type_data_column_delimiter'))),
                    )
                ),
                'product_filter'=>array(
                    'filter_name'=>array(
                        'name'=>$this->language->get('entry_abstract_field_product_filter_name'),
                        'field'=>'filter_name',
                        'additinal_settings'=>array('filter_group_id'=>array('element'=>'select'))
                    ),
                    /*
                     * у фильтров нет значений, только названия
                     */
                    'filter_values_whis_filter_name'=>array(
                        'name'=>$this->language->get('entry_abstract_field_product_filter_values_whis_filter_name'),
                        'field'=>'filter_values_whis_filter_name',
                        'additinal_settings'=>array('filter_group_id'=>array('element'=>'select')),
                        'help'=>$this->language->get('filter_values_whis_filter_name_help'),
                        'additinal_settings'=>array('filter_group_id'=>array('element'=>'select'),'delimeter'=>array('element'=>'input','placeholder'=>$this->language->get('entry_type_data_column_delimiter_default'),'default_value'=>'|','data-original-title'=>$this->language->get('entry_type_data_column_delimiter')))
                
                    ),
                    'filter_values_whis_filter_name_and_group_name'=>array(
                        'name'=>$this->language->get('entry_abstract_field_product_filter_values_whis_filter_name_and_group_name'),
                        'field'=>'filter_values_whis_filter_name_and_group_name',
                        'help'=>$this->language->get('filter_values_whis_filter_group_name_help'),
                        'additinal_settings'=>array(
                            'delimiter_2'=>array('element'=>'input','placeholder'=>$this->language->get('entry_type_data_column_delimiter_2'),'default_value'=>'---'),'delimiter_1'=>array('element'=>'input','placeholder'=>$this->language->get('entry_type_data_column_delimiter_1'),'default_value'=>'|'),
                            'filter_group_id'=>array('element'=>'select','import'=>0)
                        )
                    )
                ),
                'product_option_value'=>array(
                    'option_value_option_value_name'=>array(
                        'name'=>$this->language->get('entry_abstract_field_product_option_value_option_value_name'),
                        'field'=>'option_value_option_value_name',
                        'help'=>$this->language->get('entry_abstract_field_product_option_value_option_value_name_help'),
                        'additinal_settings'=>array(
                            'option_id'=>array('element'=>'select'),
                            'quantity_default'=>array('element'=>'select','data-original-title'=>$this->language->get('entry_type_data_column_quantity_default_data-original-title'),'export'=>0),
                            'price_default'=>array('element'=>'select','data-original-title'=>$this->language->get('entry_type_data_column_price_default_data-original-title'),'export'=>0),
                            'price_rate'=>array('element'=>'input','placeholder'=>$this->language->get('entry_type_data_column_price_rate'),'export'=>0),
                            'price_delta'=>array('element'=>'input','placeholder'=>$this->language->get('entry_type_data_column_price_delta'),'export'=>0),
                            'price_around'=>array('element'=>'select','export'=>0),
                            'price_whis_delta'=>array('element'=>'select','data-original-title'=>$this->language->get('entry_type_data_column_price_whis_delta_data-original-title'),'export'=>0),
                            'price_range'=> array('element'=>'input','range'=>5,'data-original-title'=>$this->language->get('entry_type_data_column_range_price-data-original-title')),
                            'required_default'=>array('element'=>'select','export'=>0),
                            'subtract_default'=>array('element'=>'select','export'=>0),
                        )
                    ),
                    'option_value_option_microdata_1'=>array(
                        'name'=>$this->language->get('entry_abstract_field_product_option_value_option_microdata_1'),
                        'field'=>'option_value_option_microdata_1',
                        'help'=>$this->language->get('entry_abstract_field_product_option_value_option_microdata_1_help'),
                        'additinal_settings'=>array(
                                'delimeter'=>array('element'=>'input','placeholder'=>$this->language->get('entry_type_data_column_delimiter_default'),'data-original-title'=>$this->language->get('entry_type_data_column_delimiter'),'default_value'=>'|'),
                                'delimiter_2'=>array('element'=>'input','placeholder'=>$this->language->get('data-original-title_delimeter_2'),'data-original-title'=>$this->language->get('data-original-title_delimeter_2'),'default_value'=>'---'),
                                'price_whis_delta'=>array('element'=>'select','data-original-title'=>$this->language->get('entry_type_data_column_price_whis_delta_data-original-title')),
                                'price_rate'=>array('element'=>'input','placeholder'=>$this->language->get('entry_type_data_column_price_rate'),'export'=>0),
                                'price_delta'=>array('element'=>'input','placeholder'=>$this->language->get('entry_type_data_column_price_delta'),'export'=>0),
                                'price_range'=> array('element'=>'input','range'=>5,'data-original-title'=>$this->language->get('entry_type_data_column_range_price-data-original-title')),
                                'price_around'=>array('element'=>'select','export'=>0),
                                'option_id'=>array('element'=>'select',"import"=>0),
                                'column_whis_product_option_value_code'=>array('data-original-title'=>$this->language->get('entry_type_data_column_whis_product_option_value_code'),'element'=>'input','placeholder'=>$this->language->get('entry_type_data_column_whis_product_option_value_code'),'export'=>0),
                                'column_whis_option_value_code'=>array('data-original-title'=>$this->language->get('entry_type_data_column_whis_option_value_code'),'element'=>'input','placeholder'=>$this->language->get('entry_type_data_column_whis_option_value_code'),'export'=>0),
                        )
                    ),
                    'option_value_option_microdata_2'=>array(
                        'name'=>$this->language->get('entry_abstract_field_product_option_value_option_microdata_2'),
                        'field'=>'option_value_option_microdata_2',
                        'help'=>$this->language->get('entry_abstract_field_product_option_value_option_microdata_2_help'),
                        'additinal_settings'=>array(
                            'delimeter'=>array('element'=>'input','placeholder'=>$this->language->get('entry_type_data_column_delimiter_default'),'data-original-title'=>$this->language->get('entry_type_data_column_delimiter'),'default_value'=>'|'),
                            'delimiter_2'=>array('element'=>'input','placeholder'=>$this->language->get('data-original-title_delimeter_2'),'data-original-title'=>$this->language->get('data-original-title_delimeter_2'),'default_value'=>'---'),
                            'price_whis_delta'=>array('element'=>'select','data-original-title'=>$this->language->get('entry_type_data_column_price_whis_delta_data-original-title')),
                            'price_rate'=>array('element'=>'input','placeholder'=>$this->language->get('entry_type_data_column_price_rate'),'export'=>0),
                            'price_delta'=>array('element'=>'input','placeholder'=>$this->language->get('entry_type_data_column_price_delta'),'export'=>0),
                            'price_range'=> array('element'=>'input','range'=>5,'data-original-title'=>$this->language->get('entry_type_data_column_range_price-data-original-title')),
                            'price_around'=>array('element'=>'select','export'=>0),
                            'option_id'=>array('element'=>'select',"import"=>0),
                            'column_whis_product_option_value_code'=>array('data-original-title'=>$this->language->get('entry_type_data_column_whis_product_option_value_code'),'element'=>'input','placeholder'=>$this->language->get('entry_type_data_column_whis_product_option_value_code'),'export'=>0),
                            'column_whis_option_value_code'=>array('data-original-title'=>$this->language->get('entry_type_data_column_whis_option_value_code'),'element'=>'input','placeholder'=>$this->language->get('entry_type_data_column_whis_option_value_code'),'export'=>0),
                        )
                    ),
                    'option_value_option_microdata_4'=>array(
                        'name'=>$this->language->get('entry_abstract_field_product_option_value_option_microdata_4'),
                        'field'=>'option_value_option_microdata_4',
                        'help'=>$this->language->get('entry_abstract_field_product_option_value_option_microdata_4_help'),
                        'additinal_settings'=>array(
                            'option_id___option_value_id'=>array('element'=>'select'),
                            'delimeter'=>array('element'=>'input','placeholder'=>$this->language->get('entry_type_data_column_delimiter_default'),'data-original-title'=>$this->language->get('entry_type_data_column_delimiter'),'default_value'=>'|'),
                            'price_whis_delta'=>array('element'=>'select','data-original-title'=>$this->language->get('entry_type_data_column_price_whis_delta_data-original-title')),
                            'price_rate'=>array('element'=>'input','placeholder'=>$this->language->get('entry_type_data_column_price_rate'),'export'=>0),
                            'price_delta'=>array('element'=>'input','placeholder'=>$this->language->get('entry_type_data_column_price_delta'),'export'=>0),
                            'price_range'=> array('element'=>'input','range'=>5,'data-original-title'=>$this->language->get('entry_type_data_column_range_price-data-original-title')),
                            'price_around'=>array('element'=>'select','export'=>0),
                            'quantity_default'=>array('element'=>'select','data-original-title'=>$this->language->get('entry_type_data_column_quantity_default_data-original-title'),'export'=>0),
                            'required_default'=>array('element'=>'select','export'=>0),
                            'subtract_default'=>array('element'=>'select','export'=>0),
                        )
                    ),
                    'option_value_option_microdata_5'=>array(
                        'name'=>$this->language->get('entry_abstract_field_product_option_value_option_microdata_5'),
                        'field'=>'option_value_option_microdata_5',
                        'help'=>$this->language->get('entry_abstract_field_product_option_value_option_microdata_5_help'),
                        'additinal_settings'=>array(
                            'delimiter_1'=>array('element'=>'input','placeholder'=>$this->language->get('entry_type_data_column_delimiter_1'),'default_value'=>';',"export"=>0),
                            'delimiter_2'=>array('element'=>'input','placeholder'=>$this->language->get('entry_type_data_column_delimiter_2'),'default_value'=>'-',"export"=>0),
                            'option_id'=>array('element'=>'select',"export"=>0),
                            'price_whis_delta'=>array('element'=>'select','data-original-title'=>$this->language->get('entry_type_data_column_price_whis_delta_data-original-title')),
                            'price_rate'=>array('element'=>'input','placeholder'=>$this->language->get('entry_type_data_column_price_rate'),'export'=>0),
                            'price_delta'=>array('element'=>'input','placeholder'=>$this->language->get('entry_type_data_column_price_delta'),'export'=>0),
                            'price_range'=> array('element'=>'input','range'=>5,'data-original-title'=>$this->language->get('entry_type_data_column_range_price-data-original-title')),
                            'price_around'=>array('element'=>'select','export'=>0),
                        )
                    )
                ),
                'product_assortiment_value'=>array(
                    'assortiment_values_by_article'=>array(
                        'name'=>$this->language->get('entry_abstract_field_product_assortiment_value_assortiment_values_by_article'),
                        'field'=>'assortiment_values_by_article',
                        'help'=>$this->language->get('entry_abstract_field_product_assortiment_value_assortiment_values_by_article_help'),
                        'additinal_settings'=>array(
                            'product_assortiment_name_article'=>array('element'=>'select','data-original-title'=>$this->language->get('entry_type_data_column_product_assortiment_name_article'),'export'=>0),
                            'option_value_name_field_1'=>array('element'=>'select','data-original-title'=>$this->language->get('entry_type_data_column_product_assortiment_option_value_name_field-data-original-title'),'export'=>0,'box-title'=>sprintf($this->language->get('entry_type_data_column_product_assortiment_option_value_data_box-title'),1)),
                            'option_id_for_field_1'=>array('element'=>'select','data-original-title'=>$this->language->get('entry_type_data_column_product_assortiment_option_id-data-original-title'),'export'=>0,'box-title'=>sprintf($this->language->get('entry_type_data_column_product_assortiment_option_value_data_box-title'),1)),
                            'price_default'=>array('element'=>'select','data-original-title'=>$this->language->get('entry_type_data_column_price_default_data-original-title'),'export'=>0,'box-title'=>sprintf($this->language->get('entry_type_data_column_product_assortiment_option_value_data_box-title'),1)),
                            'price_rate'=>array('element'=>'input','placeholder'=>$this->language->get('entry_type_data_column_price_rate'),'export'=>0,'box-title'=>sprintf($this->language->get('entry_type_data_column_product_assortiment_option_value_data_box-title'),1)),
                            'price_delta'=>array('element'=>'input','placeholder'=>$this->language->get('entry_type_data_column_price_delta'),'export'=>0,'box-title'=>sprintf($this->language->get('entry_type_data_column_product_assortiment_option_value_data_box-title'),1)),
                            'price_range'=> array('element'=>'input','range'=>5,'data-original-title'=>$this->language->get('entry_type_data_column_range_price-data-original-title')),
                            'price_around'=>array('element'=>'select','export'=>0,'box-title'=>sprintf($this->language->get('entry_type_data_column_product_assortiment_option_value_data_box-title'),1)),
                            'price_whis_delta'=>array('element'=>'select','data-original-title'=>$this->language->get('entry_type_data_column_price_whis_delta_data-original-title'),'export'=>0,'box-title'=>sprintf($this->language->get('entry_type_data_column_product_assortiment_option_value_data_box-title'),1)),
                            //'price_rrp'=>array('element'=>'select','data-original-title'=>$this->language->get('entry_type_data_column_rrp'),'export'=>0,'box-title'=>sprintf($this->language->get('entry_type_data_column_product_assortiment_option_value_data_box-title'),1)),
                            //'price_purchase_price'=>array('element'=>'select','data-original-title'=>$this->language->get('entry_type_data_column_purchase_price'),'export'=>0,'box-title'=>sprintf($this->language->get('entry_type_data_column_product_assortiment_option_value_data_box-title'),1)),
                            
                            'option_value_name_field_2'=>array('element'=>'select','data-original-title'=>$this->language->get('entry_type_data_column_product_assortiment_option_value_name_field-data-original-title'),'export'=>0,'box-title'=>sprintf($this->language->get('entry_type_data_column_product_assortiment_option_value_data_box-title'),2)),
                            'option_id_for_field_2'=>array('element'=>'select','data-original-title'=>$this->language->get('entry_type_data_column_product_assortiment_option_id-data-original-title'),'export'=>0,'box-title'=>sprintf($this->language->get('entry_type_data_column_product_assortiment_option_value_data_box-title'),2)),
                            'option_value_name_field_3'=>array('element'=>'select','data-original-title'=>$this->language->get('entry_type_data_column_product_assortiment_option_value_name_field-data-original-title'),'export'=>0,'box-title'=>sprintf($this->language->get('entry_type_data_column_product_assortiment_option_value_data_box-title'),3)),
                            'option_id_for_field_3'=>array('element'=>'select','data-original-title'=>$this->language->get('entry_type_data_column_product_assortiment_option_id-data-original-title'),'export'=>0,'box-title'=>sprintf($this->language->get('entry_type_data_column_product_assortiment_option_value_data_box-title'),3)),
                            'option_value_name_field_4'=>array('element'=>'select','data-original-title'=>$this->language->get('entry_type_data_column_product_assortiment_option_value_name_field-data-original-title'),'export'=>0,'box-title'=>sprintf($this->language->get('entry_type_data_column_product_assortiment_option_value_data_box-title'),4)),
                            'option_id_for_field_4'=>array('element'=>'select','data-original-title'=>$this->language->get('entry_type_data_column_product_assortiment_option_id-data-original-title'),'export'=>0,'box-title'=>sprintf($this->language->get('entry_type_data_column_product_assortiment_option_value_data_box-title'),4)),
                            'option_value_name_field_5'=>array('element'=>'select','data-original-title'=>$this->language->get('entry_type_data_column_product_assortiment_option_value_name_field-data-original-title'),'export'=>0,'box-title'=>sprintf($this->language->get('entry_type_data_column_product_assortiment_option_value_data_box-title'),5)),
                            'option_id_for_field_5'=>array('element'=>'select','data-original-title'=>$this->language->get('entry_type_data_column_product_assortiment_option_id-data-original-title'),'export'=>0,'box-title'=>sprintf($this->language->get('entry_type_data_column_product_assortiment_option_value_data_box-title'),5)),
                            'required_default'=>array('element'=>'select','export'=>0,'box-title'=>$this->language->get('entry_type_data_column_product_assortiment_option_value_quantity_box-title')),
                            'subtract_default'=>array('element'=>'select','export'=>0,'box-title'=>$this->language->get('entry_type_data_column_product_assortiment_option_value_quantity_box-title')),
                            'quantity_default'=>array('element'=>'select','data-original-title'=>$this->language->get('entry_type_data_column_quantity_default_data-original-title'),'export'=>0,'box-title'=>$this->language->get('entry_type_data_column_product_assortiment_option_value_quantity_box-title')),
                            'image_upload'=>array('element'=>'select','box-title'=>$this->language->get('entry_type_data_column_product_assortiment_option_value_image_box-title')),
                            'image_new_path'=>array('element'=>'input','placeholder'=>$this->language->get('entry_type_data_column_delimiter_image_new_path'),'data-original-title'=>$this->language->get('entry_type_data_column_delimiter_image_new_path_data-original-title'),'export'=>0,'box-title'=>$this->language->get('entry_type_data_column_product_assortiment_option_value_image_box-title')),
                            'image_new_name'=>array('element'=>'select','data-original-title'=>$this->language->get('entry_type_data_column_image_new_name'),'export'=>0,'box-title'=>$this->language->get('entry_type_data_column_product_assortiment_option_value_image_box-title'))
                        )
                    ),
                    /*
                     * 
                     * 
                     * 
                     * 
                    'assortiment_price_and_quantity_by_article'=>array(
                        'name'=>$this->language->get('entry_abstract_field_product_assortiment_price_and_quantity_by_article'),
                        'field'=>'assortiment_price_and_quantity_by_article',
                        'help'=>$this->language->get('entry_abstract_field_product_assortiment_price_and_quantity_by_article_help'),
                        'additinal_settings'=>array(
                            'product_assortiment_name_article'=>array('element'=>'select','data-original-title'=>$this->language->get('entry_type_data_column_product_assortiment_name_article'),'export'=>0),
                            'option_value_name_field_1'=>array('element'=>'select','data-original-title'=>$this->language->get('entry_type_data_column_product_assortiment_option_value_name_field-data-original-title_2'),'export'=>0,'box-title'=>$this->language->get('entry_type_data_column_product_assortiment_option_value_data_box-title_2')),
                            'price_default'=>array('element'=>'select','data-original-title'=>$this->language->get('entry_type_data_column_price_default_data-original-title'),'export'=>0,'box-title'=>$this->language->get('entry_type_data_column_product_assortiment_option_value_data_box-title_2')),
                            'price_rate'=>array('element'=>'input','placeholder'=>$this->language->get('entry_type_data_column_price_rate'),'export'=>0,'box-title'=>$this->language->get('entry_type_data_column_product_assortiment_option_value_data_box-title_2')),
                            'price_delta'=>array('element'=>'input','placeholder'=>$this->language->get('entry_type_data_column_price_delta'),'export'=>0,'box-title'=>$this->language->get('entry_type_data_column_product_assortiment_option_value_data_box-title_2')),
                            'price_around'=>array('element'=>'select','export'=>0,'box-title'=>$this->language->get('entry_type_data_column_product_assortiment_option_value_data_box-title_2')),
                            'price_whis_delta'=>array('element'=>'select','data-original-title'=>$this->language->get('entry_type_data_column_price_whis_delta_data-original-title'),'export'=>0,'box-title'=>$this->language->get('entry_type_data_column_product_assortiment_option_value_data_box-title_2')),
                            //'price_rrp'=>array('element'=>'select','data-original-title'=>$this->language->get('entry_type_data_column_rrp'),'export'=>0,'box-title'=>sprintf($this->language->get('entry_type_data_column_product_assortiment_option_value_data_box-title'),1)),
                            //'price_purchase_price'=>array('element'=>'select','data-original-title'=>$this->language->get('entry_type_data_column_purchase_price'),'export'=>0,'box-title'=>sprintf($this->language->get('entry_type_data_column_product_assortiment_option_value_data_box-title'),1)),
                            
                            'required_default'=>array('element'=>'select','export'=>0,'box-title'=>$this->language->get('entry_type_data_column_product_assortiment_option_value_quantity_box-title')),
                            'subtract_default'=>array('element'=>'select','export'=>0,'box-title'=>$this->language->get('entry_type_data_column_product_assortiment_option_value_quantity_box-title')),
                            'quantity_default'=>array('element'=>'select','data-original-title'=>$this->language->get('entry_type_data_column_quantity_default_data-original-title'),'export'=>0,'box-title'=>$this->language->get('entry_type_data_column_product_assortiment_option_value_quantity_box-title')),
                        )
                    )
                    *
                     * 
                     * 
                     * 
                    */
                ),
                'product_special'=>array(
                    'price'=>array(
                        'name'=>$this->language->get('entry_abstract_field_product_price'),
                        'field'=>'price_advanced',
                        'additinal_settings'=>array(
                            'price_rate'=>array('element'=>'input','placeholder'=>$this->language->get('entry_type_data_column_price_rate')),
                            'price_delta'=>array('element'=>'input','placeholder'=>$this->language->get('entry_type_data_column_price_delta')),
                            'price_range'=> array('element'=>'input','range'=>5,'data-original-title'=>$this->language->get('entry_type_data_column_range_price-data-original-title')),
                            'price_around'=>array('element'=>'select'))
                    )
                ),
                'product_discount'=>array(
                    'price'=>array(
                        'name'=>$this->language->get('entry_abstract_field_product_price'),
                        'field'=>'price_advanced',
                        'additinal_settings'=>array(
                            'price_rate'=>array('element'=>'input','placeholder'=>$this->language->get('entry_type_data_column_price_rate')),
                            'price_delta'=>array('element'=>'input','placeholder'=>$this->language->get('entry_type_data_column_price_delta')),
                            'price_range'=> array('element'=>'input','range'=>5,'data-original-title'=>$this->language->get('entry_type_data_column_range_price-data-original-title')),
                            'price_around'=>array('element'=>'select'))
                    )
                ),
                'attribute'=>array(
                    'attribute_group_name'=>array(
                        'name'=>$this->language->get('entry_abstract_field_product_attribute_group_name'),
                        'field'=>'attribute_group_name',
                    ),
                    'attrubute_name'=>array(
                        'name'=>$this->language->get('entry_abstract_field_product_attribute_name'),
                        'field'=>'attrubute_name',
                        'additinal_settings'=>array('attribute_group_id'=>array('element'=>'select')),
                    ),
                    'attribute_values_whis_attrubute_name_and_group_name'=>array(
                        'name'=>$this->language->get('entry_abstract_field_product_attribute_values_whis_attrubute_name_and_group_name'),
                        'field'=>'attribute_values_whis_attrubute_name_and_group_name',
                    ),
                ),
                'option_value'=>array(
                    'option_value_option_name'=>array(
                        'name'=>$this->language->get('entry_abstract_field_product_option_value_option_name'),
                        'field'=>'option_value_option_name'
                    ),
                    'option_value_option_value_name'=>array(
                        'name'=>$this->language->get('entry_abstract_field_product_option_value_option_value_name'),
                        'field'=>'option_value_option_value_name',
                        'additinal_settings'=>array('option_id'=>array('element'=>'select'))
                    ),
                    
                    'option_value_option_type'=>array(
                        'name'=>$this->language->get('entry_abstract_field_product_option_value_option_type'),
                        'field'=>'option_value_option_type',
                        'additinal_settings'=>array('option_id'=>array('element'=>'select'))
                    ),
                    'option_value_option_microdata_3'=>array(
                        'name'=>$this->language->get('entry_abstract_field_product_option_value_option_microdata_3'),
                        'field'=>'option_value_option_microdata_3',
                        'help'=>$this->language->get('entry_abstract_field_product_option_value_option_microdata_3_help'),
                        'additinal_settings'=>array('delimeter'=>array('element'=>'input','placeholder'=>$this->language->get('entry_type_data_column_delimiter_default'),'data-original-title'=>$this->language->get('entry_type_data_column_delimiter'),'default_value'=>'|'))
                    ),
                    'image'=>array(
                        'name'=>$this->language->get('entry_abstract_field_product_image'),
                        'field'=>'image_advanced',
                        'additinal_settings'=>array(
                            'option_id'=>array('element'=>'select'),
                            'image_upload'=>array('element'=>'select'),
                            'image_new_path'=>array('element'=>'input','placeholder'=>$this->language->get('entry_type_data_column_delimiter_image_new_path'),'data-original-title'=>$this->language->get('entry_type_data_column_delimiter_image_new_path_data-original-title'),'export'=>0),
                            'image_new_name'=>array('element'=>'select','data-original-title'=>$this->language->get('entry_type_data_column_image_new_name'),'export'=>0)
                        )
                    ),
                    'sort_order'=>array(
                        'name'=>'',
                        'field'=>'sort_order_advanced',
                        'additinal_settings'=>array('option_id'=>array('element'=>'select'))
                    ),
                    'option_value_id'=>array(
                        'name'=>'',
                        'field'=>'option_value_id_advanced',
                        'additinal_settings'=>array('option_id'=>array('element'=>'select'))
                    )
                ),
                'option_value_description'=>array(
                    'name'=>array(
                        'name'=>'',
                        'field'=>'name_advanced',
                        'additinal_settings'=>array('option_id'=>array('element'=>'select'))
                    ),
                    'language_id'=>array(
                        'name'=>'',
                        'field'=>'language_id_advanced',
                        'additinal_settings'=>array('option_id'=>array('element'=>'select'))
                    ),
                    'option_value_id'=>array(
                        'name'=>'',
                        'field'=>'option_value_id_advanced',
                        'additinal_settings'=>array('option_id'=>array('element'=>'select'))
                    )
                ),
                'category'=>array(
                    'image'=>array(
                        'name'=>$this->language->get('entry_abstract_field_category_image'),
                        'field'=>'image_advanced',
                        'additinal_settings'=>array(
                            'image_upload'=>array('element'=>'select'),
                            'image_new_path'=>array('element'=>'input','placeholder'=>$this->language->get('entry_type_data_column_delimiter_image_new_path'),'data-original-title'=>$this->language->get('entry_type_data_column_delimiter_image_new_path_data-original-title'),'export'=>0),
                            'image_new_name'=>array('element'=>'select','data-original-title'=>$this->language->get('entry_type_data_column_image_new_name'),'export'=>0)
                        )
                    ),
                    'category_whis_path'=>array(
                        'field'=>'category_whis_path',
                        'name'=>$this->language->get('entry_abstract_field_category_whis_path'),
                        'additinal_settings'=>array(
                            'delimeter'=>array('element'=>'input','placeholder'=>$this->language->get('entry_type_data_column_delimiter_category_whis_path'),'data-original-title'=>$this->language->get('entry_type_data_column_delimiter')),
                        )
                    ),
                    'seo_url'=>array(
                        'name'=>$this->language->get('entry_abstract_field_product_seo_url'),
                        'field'=>'seo_url'
                    ),
                    'category_name_and_parent_level'=>array(
                        'name'=>$this->language->get('entry_abstract_field_product_category_name_and_parent_level'),
                        'field'=>'category_name_and_parent_level',
                        'additinal_settings'=>array(
                            'parent_level'=>array('element'=>'input','placeholder'=>$this->language->get('entry_type_data_column_parent_level'),'data-original-title'=>$this->language->get('entry_type_data_column_parent_level-data-original-title'),'default_value'=>'0'),
                            'parent_category_id'=>array('element'=>'select','data-original-title'=>$this->language->get('entry_type_data_column_parent_category_id-data-original-title'))
                        )
                    )
                ),
                'filter'=>array(
                    'filter_group_name'=>array(
                        'name'=>$this->language->get('entry_abstract_field_product_filter_group_name'),
                        'field'=>'filter_group_name',
                    ),
                    'filter_values_whis_filter_name_and_group_name'=>array(
                        'name'=>$this->language->get('entry_abstract_field_product_filter_values_whis_filter_name_and_group_name'),
                        'field'=>'filter_values_whis_filter_name_and_group_name',
                    ),
                    'filter_name'=>array(
                        'name'=>$this->language->get('entry_abstract_field_product_filter_name'),
                        'field'=>'filter_name',
                        'additinal_settings'=>array('filter_group_id'=>array('element'=>'select'))
                    ),
                ),
                'manufacturer'=>array(
                    'image'=>array(
                        'name'=>$this->language->get('entry_abstract_field_manufacturer_image'),
                        'field'=>'image_advanced',
                        'additinal_settings'=>array(
                            'image_upload'=>array('element'=>'select'),
                            'image_new_path'=>array('element'=>'input','placeholder'=>$this->language->get('entry_type_data_column_delimiter_image_new_path'),'data-original-title'=>$this->language->get('entry_type_data_column_delimiter_image_new_path_data-original-title'),'export'=>0),
                            'image_new_name'=>array('element'=>'select','data-original-title'=>$this->language->get('entry_type_data_column_image_new_name'),'export'=>0)
                        )
                    ),
                    'seo_url'=>array(
                        'name'=>$this->language->get('entry_abstract_field_product_seo_url'),
                        'field'=>'seo_url'
                    )
                ),
                'order_product'=>array(
                    'order_option_microdata_1'=>array(
                        'name'=>"Опции товара в одной ячейке: <br>Название опции:значение у товара в заказе",
                        'field'=>'order_option_microdata_1',
                        'additinal_settings'=>array()
                    )
                ),
                'abstract_field_for_all_data'=>array(
                    'column_request'=>array(
                        'name'=>$this->language->get('entry_abstract_field_identificator'),
                        'field'=>'column_request',
                        'additinal_settings'=>array('column_request'=>array('element'=>'select','data-original-title'=>$this->language->get('entry_type_data_column_request_data-original-title'),'export'=>0))
                    )
                ),
                'identificator'=>array(
                    'identificator'=>array(
                        'name'=>$this->language->get('entry_abstract_field_identificator'),
                        'field'=>'identificator',
                        'additinal_settings'=>array('identificator_insert'=>array('element'=>'select','export'=>0),'identificator_type'=>array('element'=>'select'))
                    )
                )
            );
        
            return $abstract_field;
    }
    
    public function getAdvancedSettings($additinal_column,$odmpro_tamplate_data,$type_process='',$type_data='',$params=array()){
        
        $methods = get_class_methods('anyDSVXLSSettingVersion');
        
        $result = '';
        
        if(in_array('get_'.$additinal_column.'_advanced_settings_veiw', $methods)){
            
            $result = $this->{'get_'.$additinal_column.'_advanced_settings_veiw'}($additinal_column,$odmpro_tamplate_data,$type_process,$type_data,$params);
            
        }
        
        return $result;
        
    }
    
    public function getSuppliersFeedSource(){
        
        $result = array();
        
        if(file_exists(DIR_SYSTEM.'library/vendor/ocext/suppliers/')){
            
            $files = scandir(DIR_SYSTEM.'library/vendor/ocext/suppliers/');
            
            foreach($files as $file_name){
                
                if(strstr($file_name, '.php') && is_file(DIR_SYSTEM.'library/vendor/ocext/suppliers/'.$file_name)){
                    
                    require_once DIR_SYSTEM.'library/vendor/ocext/suppliers/'.$file_name;
                    
                    $class = str_replace(array('_','.php'), '', $file_name);
                
                    $supplier_feed_source = new $class($this->registry,$this->path_oc_version,$this->language,$this->load);
                    
                    $specification = $supplier_feed_source->getSpec();
                    
                    $result[$specification['id_specification']] = $specification;
                    
                }
                
            }
            
        }
        
        return $result;
        
    }
    
    private function get_image_crop_advanced_settings_veiw($additinal_column_base,$odmpro_tamplate_data,$type_process,$type_data){
        
        $data['advanced_settings'] = array();
        
        $data['title_advanced_settings_row'] = "Обработка изображения";
        
        $data['type_process'] = $type_process;
        
        $advanced_settings = array(
            'image_no_update_last_image'=>array(
                'element' => 'select',
                'placeholder' => "При скачивании картинок, обновлять картинки на этом сайте, если они уже скачивались. Если выключено, то картинки не будут скачиваться второй раз, ранее загруженная картинка останется не перезаписанной",
                'import'=>1,
                'export'=>0
            ),
            'image_max_width'=>array(
                'element' => 'input',
                'placeholder' => "Максимальная ширина",
                'import'=>1,
                'export'=>1
            ),
            'image_max_height'=>array(
                'element' => 'input',
                'placeholder' => "Максимальная высота",
                'import'=>1,
                'export'=>1
            ),
            'image_crop_left'=>array(
                'element' => 'input',
                'placeholder' => "Отрезать слева, px",
                'import'=>1,
                'export'=>1
            ),
            'image_crop_right'=>array(
                'element' => 'input',
                'placeholder' => "Отрезать справа, px",
                'import'=>1,
                'export'=>1
            ),
            'image_crop_top'=>array(
                'element' => 'input',
                'placeholder' => "Отрезать сверху, px",
                'import'=>1,
                'export'=>1
            ),
            'image_crop_bottom'=>array(
                'element' => 'input',
                'placeholder' => "Отрезать снизу, px",
                'import'=>1,
                'export'=>1
            ),
            'image_only_new'=>array(
                'element' => 'select',
                'placeholder' => "Обработывать только те, которые скачиваются с удаленных сайтов (еще отсутствуют на этом сайте). Если выключено, то при каждом импорте картинки на сайте будут меняться в соответствии с заданными параметрами",
                'import'=>1,
                'export'=>0
            )
        );
        
        $j = 0;
        
        foreach ($advanced_settings as  $additinal_column=>$additinal_column_param) {
            
            $data['advanced_settings'][$type_data][$j]['help'] = '';

            $data['advanced_settings'][$type_data][$j]['onchange'] = '';

            if(isset($additinal_column_param['onchange']) && $additinal_column_param['onchange']){

                $data['advanced_settings'][$type_data][$j]['onchange'] = $additinal_column_param['onchange'];

            }

            $data['advanced_settings'][$type_data][$j]['style'] = '';

            if(isset($additinal_column_param['style']) && $additinal_column_param['style']){

                $data['advanced_settings'][$type_data][$j]['style'] = $additinal_column_param['style'];

            }

            $data['advanced_settings'][$type_data][$j]['hide_this_additinal_data'] = 0;

            if(isset($additinal_column_param['export']) && !$additinal_column_param['export'] && $type_process=='export'){

                $data['advanced_settings'][$type_data][$j]['hide_this_additinal_data'] = 1;

            }

            if(isset($additinal_column_param['import']) && !$additinal_column_param['import'] && $type_process=='import'){

                $data['advanced_settings'][$type_data][$j]['hide_this_additinal_data'] = 1;

            }

            $data['advanced_settings'][$type_data][$j]['class'] = '';

            if(isset($additinal_column_param['class']) && $additinal_column_param['class']){

                $data['advanced_settings'][$type_data][$j]['class'] = $additinal_column_param['class'];

            }

            $data['advanced_settings'][$type_data][$j]['id'] = '';

            if(isset($additinal_column_param['id']) && $additinal_column_param['id']){

                $data['advanced_settings'][$type_data][$j]['id'] = $additinal_column_param['id'];

            }

            $data['advanced_settings'][$type_data][$j]['name'] = 'odmpro_tamplate_data[type_data_general_settings]['.$type_data.']['.$additinal_column.']';

            $data['advanced_settings'][$type_data][$j]['placeholder'] = '';

            if(isset($additinal_column_param['placeholder']) && $additinal_column_param['placeholder']){

                $data['advanced_settings'][$type_data][$j]['placeholder'] = $additinal_column_param['placeholder'];

            }

            $data['advanced_settings'][$type_data][$j]['data-original-title'] = '';

            if(isset($additinal_column_param['data-original-title']) && $additinal_column_param['data-original-title']){

                $data['advanced_settings'][$type_data][$j]['data-original-title'] = $additinal_column_param['data-original-title'];

            }

            $data['advanced_settings'][$type_data][$j]['element'] = $additinal_column_param['element'];

            if($data['advanced_settings'][$type_data][$j]['element']=='input'){

                $data['advanced_settings'][$type_data][$j]['type'] = 'text';

                if(isset($additinal_column_param['type']) && $additinal_column_param['type']){

                    $data['advanced_settings'][$type_data][$j]['type'] = $additinal_column_param['type'];

                }

                if(isset($odmpro_tamplate_data['type_data_general_settings'][$type_data][$additinal_column]) && $odmpro_tamplate_data['type_data_general_settings'][$type_data][$additinal_column]){

                    $data['advanced_settings'][$type_data][$j]['value'] = $odmpro_tamplate_data['type_data_general_settings'][$type_data][$additinal_column];

                }else{

                    $data['advanced_settings'][$type_data][$j]['value'] = '';

                    if(isset($additinal_column_param['default_value']) && $additinal_column_param['default_value']){

                        $data['advanced_settings'][$type_data][$j]['value'] = $additinal_column_param['default_value'];

                    }

                }

            }
            /*
             * У select'ов свой набор options
             */
            elseif($data['advanced_settings'][$type_data][$j]['element']=='select' && $additinal_column=='image_only_new'){

                $data['advanced_settings'][$type_data][$j]['element'] = 'select';
                
                for($i=0;$i<2;$i++){

                    $data['advanced_settings'][$type_data][$j]['options'][$i]['value'] = $i;

                    $data['advanced_settings'][$type_data][$j]['options'][$i]['text'] = $this->language->get('type_data_general_setting_endis_'.$i);

                    if(isset($odmpro_tamplate_data['type_data_general_settings'][$type_data][$additinal_column]) && $odmpro_tamplate_data['type_data_general_settings'][$type_data][$additinal_column]==$i){

                        $data['advanced_settings'][$type_data][$j]['options'][$i]['selected'] = 'selected=""';

                    }else{

                        $data['advanced_settings'][$type_data][$j]['options'][$i]['selected'] = '';

                    }

                }

            }
            elseif($data['advanced_settings'][$type_data][$j]['element']=='select' && $additinal_column=='image_no_update_last_image'){

                $data['advanced_settings'][$type_data][$j]['element'] = 'select';
                
                for($i=0;$i<2;$i++){

                    $data['advanced_settings'][$type_data][$j]['options'][$i]['value'] = $i;

                    $data['advanced_settings'][$type_data][$j]['options'][$i]['text'] = $this->language->get('type_data_general_setting_endis_'.$i);

                    if(isset($odmpro_tamplate_data['type_data_general_settings'][$type_data][$additinal_column]) && $odmpro_tamplate_data['type_data_general_settings'][$type_data][$additinal_column]==$i){

                        $data['advanced_settings'][$type_data][$j]['options'][$i]['selected'] = 'selected=""';

                    }else{

                        $data['advanced_settings'][$type_data][$j]['options'][$i]['selected'] = '';

                    }

                }

            }
            
            
            
            $j++;
            
        }
        
        $result = $this->anydsvxls_view('image_crop_advanced_settings.tpl',$data);
        
        return $result;
        
    }
    
    public function getLastLogData() {
        
        $file_name_tasks = "smart_exchange_tasks";
        
        $tasks = $this->getCache($file_name_tasks);
        
        $result = array();
        
        foreach ($tasks as $task_id => $task) {
            
            $last_log_data = explode(', ', $task['last_log_data']);
            
            $result[] = array(
                'task_id' => $task['task_id'],
                'msg' => implode('<br>', $last_log_data),
            );
            
        }
        
        return $result;
        
    }
    
    public function getSmartExchangeCheckConnectStatus(){
        
        $file_name_smart_exchange_check_connect = "smart_exchange_check_connect";
        
        $smart_exchange_check_connect = $this->getCache($file_name_smart_exchange_check_connect);
        
        $delta_last_time = 0;
        
        $max_exe_time = 0;
        
        $memory_usage = 0;
        
        if($smart_exchange_check_connect){
            
            $last_time = end($smart_exchange_check_connect);
            
            $delta_last_time = time()-$last_time['time_start'];
            
            $max_exe_time = $last_time['time_start']-$last_time['time_finish'];
            
            $memory_usage = $last_time['memory_usage'];
            
            if(!$max_exe_time){
                
                $max_exe_time = 'менее 1';
                
            }
            
        }
        
        ksort($smart_exchange_check_connect);
        
        $delta_last_times = 0;
        
        $delta_last_times_av = 0;
        
        if(count($smart_exchange_check_connect)>1){
            
            foreach ($smart_exchange_check_connect as $key => $value) {
            
                if(isset($smart_exchange_check_connect[$key-1])){

                    $delta_last_times += $value['time_finish']-$smart_exchange_check_connect[$key-1]['time_finish'];

                }

            }
            
            $delta_last_times_av = $delta_last_times/count($smart_exchange_check_connect);
            
        }
        
        $color = 'gray';
        
        $message = 'Не включен';
        
        //if()
        
        if($smart_exchange_check_connect && $delta_last_time<60){
            
            $color = 'limegreen';
        
            $message = 'Включен, последний отклик: '.$delta_last_time.' сек. назад, время работы скрипта: ~'.$max_exe_time.' сек., интервал между вызовами: ~'.$delta_last_times_av.' сек., затраты ОЗУ: '.$memory_usage;
            
        }elseif($smart_exchange_check_connect && $delta_last_time<120){
            
            $color = 'orange';
        
            $message = 'Вероятно работает. Последний отклик: '.$delta_last_time.' сек., время работы скрипта: ~'.$max_exe_time.' сек., интервал между вызовами: ~'.$delta_last_times_av.' сек., затраты ОЗУ: '.$memory_usage;
            
        }elseif($smart_exchange_check_connect && $delta_last_time<600){
            
            $color = 'orangered';
        
            $message = 'Вероятно не работает. Последний отклик: '.$delta_last_time.' сек., время работы скрипта: ~'.$max_exe_time.' сек., интервал между вызовами: ~'.$delta_last_times_av.' сек., затраты ОЗУ: '.$memory_usage;
            
        }elseif($smart_exchange_check_connect && $delta_last_time>=600){
            
            $color = 'red';
        
            $message = 'Вероятно отключен. Последний отклик: '.$delta_last_time.' сек., время работы скрипта: ~'.$max_exe_time.' сек., интервал между вызовами: ~'.$delta_last_times_av.' сек., затраты ОЗУ: '.$memory_usage;
            
        }
        
        $reslut = "<div style='background:".$color."; color:white; border-radius:5px; padding:5px;'>".$message."</div>";
        
        return $reslut;

    }
    
    private function get_yml_setting_advanced_settings_veiw($setting_name,$odmpro_tamplate_data,$type_process,$type_data,$params){
        
        $data = array();
        
        $data['tamplate_data_selected'] = $odmpro_tamplate_data;
        
        $data['suppliers_feed_source'] = $this->getSuppliersFeedSource(); 
        
        return $this->anydsvxls_view('yml_setting_advanced_settings.tpl',$data);
        
    }
    
    private function get_php_after_import_advanced_settings_veiw($setting_name,$odmpro_tamplate_data,$type_process,$type_data,$params){
        
        $data = array();
        
        $data['tamplate_data_selected'] = $odmpro_tamplate_data;
        
        return $this->anydsvxls_view('php_after_import.tpl',$data); 
        
    }
    
    private function get_export_to_xls_one_set_advanced_settings_veiw($setting_name,$odmpro_tamplate_data,$type_process,$type_data,$params) {
        
        $data = array();
        
        $data['tamplate_data_selected'] = $odmpro_tamplate_data;
        
        if(!isset($data['tamplate_data_selected']['export_file_name_xls'])){
            
            $data['tamplate_data_selected']['export_file_name_xls'] = 'xls_export';
            
        }
        
        return $this->anydsvxls_view('export_to_xls_one_set.tpl',$data);
        
    }
    
    public function getSqlWhereLogic() {
        $operators = array('OR'=>'Или','AND'=>'И');
        return $operators;
    }
    
    private function get_export_where_rules_advanced_settings_veiw($setting_name,$odmpro_tamplate_data,$type_process,$type_data,$params) {
        
        $data = array();
        
        $data['tamplate_data_selected'] = $odmpro_tamplate_data;
        
        $data['operators'] = $this->getSqlWhereOperators();
        
        $data['columns'] = $this->getColumnsByTable($type_data);
        
        $data['logics'] = $this->getSqlWhereLogic();
        
        $data['type_data'] = $type_data;
        
        return $this->anydsvxls_view($setting_name.'.tpl',$data);
        
    }
    
    public function showTable($table,$prefix) {
        
        $query = $query = $this->db->query('SHOW TABLES from `'.DB_DATABASE.'` like "'.$prefix.$table.'" ');
        
        if($query->num_rows){
            
            return TRUE;
            
        }else{
            
            return FALSE;
            
        }
        
    }
    
    public function getColumnsByTable($table) {
        
        $result = array();
        
        if($this->showTable($table, DB_PREFIX)){
            
            $columns = $this->db->query('SHOW COLUMNS FROM `' . DB_PREFIX . $table."` " );
            
            foreach ($columns->rows as $column) {
                
                $result[$column['Field']] = $column['Field'];
                
            }
            
        }
        
        return $result;
        
    }
    
    
    public function getSelfColumn($odmpro_tamplate_data,$types_data,$type_process){
        
        $result = $this->getAdvancedSettings('self_column', $odmpro_tamplate_data,$type_process,'',array('types_data'=>$types_data));
        
        return $result;
        
    }
    
    public function getTypesDataSelfColumns($odmpro_tamplate_data,$type_process,$type_data,$columns,$self_column_id,$export_columns){
        
        $result = $this->getAdvancedSettings('self_column_types_data_column', $odmpro_tamplate_data,$type_process,$type_data,array('self_column_id'=>$self_column_id,'columns'=>$columns,'export_columns'=>$export_columns));
        
        return $result;
        
    }
    
    private function get_self_column_types_data_column_advanced_settings_veiw($setting_name,$odmpro_tamplate_data,$type_process,$type_data,$params) {
        
        $data = array();
        
        $this->load->language($this->path_oc_version.'/csv_ocext_dmpro');
        
        foreach ($params['columns'] as $key_value_type_data => $value_value_type_data) {

            $data['types_data_option_group_name_'.$key_value_type_data] = $this->language->get('types_data_option_group_name_'.$key_value_type_data);

        }
        
        $data['entry_select'] = $this->language->get('entry_select');
                
        $data['entry_odmpro_delimiter'] = $this->language->get('entry_odmpro_delimiter');

        $data['text_column_type_data_column_image_upload'] = $this->language->get('text_column_type_data_column_image_upload');

        $data['text_column_type_data_column_image_upload_no'] = $this->language->get('text_column_type_data_column_image_upload_no');
        
        $data['tamplate_data_selected'] = $odmpro_tamplate_data;
        
        $data['type_process'] = $type_process;
        
        $data['type_data'] = $type_data;
        
        $data['field'] = $params['self_column_id'];
        
        $data['columns'] = $params['columns'];
        
        $data['export_columns'] = $params['export_columns'];
        
        $data['operators'] = array('&lt;'=>'&lt;','≤'=>'≤','='=>'=','≥'=>'≥','&gt;'=>'&gt;','≠'=>'≠','±'=>'±','like'=>'Содержит','not_like'=>'Не содержит');;
        
        $fields = $odmpro_tamplate_data['type_data'];
        
        $self_column = array();
        
        if(isset($odmpro_tamplate_data['self_column'])){
            
            $self_column = $odmpro_tamplate_data['self_column'];
            
        }
        
        foreach ($fields as $field => $type_data) {
            
            if(isset($self_column[$field])){
                $data['fields'][$field] = $self_column[$field]['import_self_column_name'];
                
            }else{
                $data['fields'][$field] = $field;
            }
            
            
        }
        
        
        
        echo $this->anydsvxls_view($setting_name.'.tpl',$data);
        
    }
    
    private function get_self_column_advanced_settings_veiw($setting_name,$odmpro_tamplate_data,$type_process='import',$type_data,$params) {
        
        $data = array();
        
        $data['tamplate_data_selected'] = $odmpro_tamplate_data;
        
        $data['types_data'] = $params['types_data'];
        
        $data['type_process'] = $type_process;
        
        $this->load->language($this->path_oc_version.'/csv_ocext_dmpro');
        
        $data['text_type_data_category'] = sprintf($this->language->get('text_type_data_category'),$this->language->get('text_type_data_type_proccess_'.$type_process));
        $data['text_type_data_manufacturer'] = sprintf($this->language->get('text_type_data_manufacturer'),$this->language->get('text_type_data_type_proccess_'.$type_process));
        $data['text_type_data_product'] = sprintf($this->language->get('text_type_data_product'),$this->language->get('text_type_data_type_proccess_'.$type_process));
        $data['text_type_data_information'] = sprintf($this->language->get('text_type_data_information'),$this->language->get('text_type_data_type_proccess_'.$type_process));
        $data['text_type_data_filter'] = sprintf($this->language->get('text_type_data_filter'),$this->language->get('text_type_data_type_proccess_'.$type_process));
        $data['text_type_data_filter_group'] = sprintf($this->language->get('text_type_data_filter_group'),$this->language->get('text_type_data_type_proccess_'.$type_process));
        $data['text_type_data_option'] = sprintf($this->language->get('text_type_data_option'),$this->language->get('text_type_data_type_proccess_'.$type_process));
        $data['text_type_data_option_value'] = sprintf($this->language->get('text_type_data_option_value'),$this->language->get('text_type_data_type_proccess_'.$type_process));
        $data['text_type_data_attribute_group'] = sprintf($this->language->get('text_type_data_attribute_group'),$this->language->get('text_type_data_type_proccess_'.$type_process));
        $data['text_type_data_attribute'] = sprintf($this->language->get('text_type_data_attribute'),$this->language->get('text_type_data_type_proccess_'.$type_process));
        $data['text_type_data_review'] = sprintf($this->language->get('text_type_data_review'),$this->language->get('text_type_data_type_proccess_'.$type_process));
        $data['text_type_data_order_product'] = sprintf($this->language->get('text_type_data_order_product'),$this->language->get('text_type_data_type_proccess_'.$type_process));
        
        return $this->anydsvxls_view($setting_name.'.tpl',$data);
        
    }

    private function get_smart_exchange_advanced_settings_veiw($setting_name,$odmpro_tamplate_data,$type_process,$type_data,$params){
        
        $data = array();
        
        $data['path_oc_version_feed'] = str_replace('module', 'feed', $this->path_oc_version);
        
        $data['odmpro_update_csv_smart_exchange_link'] = $odmpro_tamplate_data['odmpro_update_csv_smart_exchange_link'];
        
        $data['odmpro_update_csv_link'] = $odmpro_tamplate_data['odmpro_update_csv_link'];
        
        $data['server_data_time'] = date('Y-m-d G:i:s',  time());
        
        $data['odmpro_update_csv_smart_exchange_link']['token'] = time();
            
        $data['odmpro_update_csv_smart_exchange_link']['max_count_tasks'] = 0;

        $data['odmpro_update_csv_smart_exchange_link']['max_count_self_starts'] = 0;

        $data['odmpro_update_csv_smart_exchange_link']['max_count_log_rows'] = 500;

        $data['odmpro_update_csv_smart_exchange_link']['max_interval_start_exchange'] = 240;

        $data['odmpro_update_csv_smart_exchange_link']['max_count_tasks_to_profile'] = 10;
        
        $data['odmpro_update_csv_smart_exchange_link']['max_curl_timeout'] = $this->settings['max_curl_timeout']/1000;
        
        if(isset($odmpro_tamplate_data['odmpro_update_csv_smart_exchange_link']['token'])){
            
            $data['odmpro_update_csv_smart_exchange_link'] = $odmpro_tamplate_data['odmpro_update_csv_smart_exchange_link'];
            
        }
        
        $data['library_vendor_ocext'] = DIR_SYSTEM . 'library/vendor/ocext/ocext_smart_exchange.php';
        
        $data['max_count_tasks_to_profile'] = 5;
        
        if(isset($data['odmpro_update_csv_smart_exchange_link']['max_count_tasks_to_profile']) && $data['odmpro_update_csv_smart_exchange_link']['max_count_tasks_to_profile']!==''){
            
            $data['max_count_tasks_to_profile'] = (int)$data['odmpro_update_csv_smart_exchange_link']['max_count_tasks_to_profile'];
            
        }
        
        $data['smart_exchange_check_connect_status'] = $this->getSmartExchangeCheckConnectStatus();
        
        $result['link'] = $this->anydsvxls_view('smart_exchange_advanced_settings_link.tpl',$data);
        
        $file_name_smart_exchange_log = "smart_exchange_log";
        
        $smart_exchange_log = $this->getCache($file_name_smart_exchange_log);
        
        foreach( $data['odmpro_update_csv_link'] as $link_data ){
            
            $data['link_data'] = $link_data;
            
            if(!isset($data['link_data']['max_task_timeout_action'])){
                    
                $data['link_data']['max_task_timeout_action'] = 0;

                $data['link_data']['max_task_timeout'] = 240;
                
                $data['link_data']['asynchronous_status'] = 0;
                
                $data['link_data']['email_notice'] = '';
                
                $data['link_data']['finish_email_notice'] = 0;
                
                $data['link_data']['start_email_notice'] = 0;
                
                $data['link_data']['timeout_email_notice'] = 0;
                
                $data['link_data']['timestart'] = array();
                
                $data['link_data']['type_process'] = 0;
                
                $data['link_data']['task_id'] = 0;

            }else{
                
                foreach ($data['link_data']['timestart'] as $key_tr => $timestart_row) {
                    
                    $find = array('00','01','02','03','04','05','06','07','08','09');

                    $replace_m = array(0,1,2,3,4,5,6,7,8,9);

                    $replace_d = array(7,1,2,3,4,5,6);

                    $replace_h = array(24,1,2,3,4,5,6,7,8,9);

                    $task_d = (int)str_replace($find, $replace_d, $timestart_row['d']);

                    $task_h = (int)str_replace($find, $replace_h, $timestart_row['h']);

                    $task_m = (int)str_replace($find, $replace_m, $timestart_row['m']);

                    $task_id = $task_h.'-'.$task_m.'-'.$task_d.'-'.$link_data['id'].'-'.$link_data['tamplate_data_id'];
                    
                    $last_log_data = array();
                    
                    $count_log_message = 10;
                    
                    $count_log_message_this = 0;
                    
                    if(isset($smart_exchange_log[$task_id]) && $task_h && $task_d){
                        
                        krsort($smart_exchange_log[$task_id]);
                        
                        foreach ($smart_exchange_log[$task_id] as $log_message) {
                            
                            if($count_log_message_this<$count_log_message){
                                
                                $log_message_parts = explode(', ',$log_message);
                                
                                $last_log_data[] = implode('<br>', $log_message_parts);
                                
                            }
                            
                            $count_log_message_this++;
                            
                        }
                        
                    }
                    
                    if($task_d && $task_h){
                        
                        $data['link_data']['timestart'][$key_tr]['last_log_data'] = implode('<hr style="margin-top:3px;margin-bottom:5px;background:black !important">', $last_log_data);
                    
                        $data['link_data']['timestart'][$key_tr]['task_id'] = $task_id;
                        
                    }elseif(!$task_d && !$task_h){
                        
                        $data['link_data']['timestart'][$key_tr]['last_log_data'] = '';
                    
                        $data['link_data']['timestart'][$key_tr]['task_id'] = 0;
                        
                    }else{
                        
                        $data['link_data']['timestart'][$key_tr]['last_log_data'] = implode('<hr style="margin-top:3px;margin-bottom:5px;background:black !important">', $last_log_data);
                    
                        $data['link_data']['timestart'][$key_tr]['task_id'] = $task_id;
                        
                    }
                    
                }
                
                
                
            }
            
            $result['setting'][$link_data['id']] = $this->anydsvxls_view('smart_exchange_advanced_settings.tpl',$data);
            
        }
        
        return $result;
        
    }
    
    public function anydsvxls_view($template_file,$data) {
        
        $file = DIR_SYSTEM.'library/vendor/ocext/anydsvxls_view/' . $template_file;
        
        $output = '';

        if (is_file($file)) {
            
                extract($data);

                ob_start();

                require($file);

                $output = ob_get_clean();
                
        }
        
        return $output;
        
    }
    
    public function updateSmartExchange($post) {
        
        if(isset($post['odmpro_update_csv_smart_exchange_link'])){
            
            $smart_exchange_setting = $post['odmpro_update_csv_smart_exchange_link'];
            
            $token = '';
            
            if(isset($smart_exchange_setting['token'])){
                
                $token = trim($smart_exchange_setting['token']);
                
            }
            
            if($token){
                
                $file_name_smart_exchange_setting = "smart_exchange_setting";
                
                $this->unlinkFiles(array($file_name_smart_exchange_setting));
                
                $this->writeCache($file_name_smart_exchange_setting, $post);
                
            }
            
        }
        
    }
    
    public function writeLog($filename,$message) {
        
        $handle = fopen(DIR_SYSTEM . 'library/vendor/ocext/cache/'. $filename, 'a');

        fwrite($handle, date('Y-m-d G:i:s') . ' - ' . print_r($message, true) . "\n");
        
        fclose($handle);
        
        if(file_exists(DIR_SYSTEM . 'library/vendor/ocext/cache/'. $filename)){
            
            return TRUE;
            
        }else{
            
            return FALSE;
            
        }
        
    }
            
    public function writeCache($filename,$array,$cache_sub_dir='') {
        
        $handle = fopen(DIR_SYSTEM . 'library/vendor/ocext/cache/'.$cache_sub_dir. $filename, 'w+');

        fwrite( $handle, json_encode($array) );
        
        fclose($handle);
        
        if(file_exists(DIR_SYSTEM . 'library/vendor/ocext/cache/'.$cache_sub_dir. $filename)){
            
            return TRUE;
            
        }else{
            
            return FALSE;
            
        }
        
    }
    
    public function getCache($filename,$unlink=FALSE,$cache_sub_dir='') {
            
        $cache = array();

        if(file_exists(DIR_SYSTEM . 'library/vendor/ocext/cache/'.$cache_sub_dir.$filename)){

            $cache = json_decode(file_get_contents(DIR_SYSTEM . 'library/vendor/ocext/cache/'.$cache_sub_dir.$filename),TRUE);

            if($unlink){
                unlink(DIR_SYSTEM . 'library/vendor/ocext/cache/'.$cache_sub_dir.$filename);
            }

        }
        
        if(!is_array($cache)){
            
            $cache = array();
            
        }

        return $cache;

    }
    
    public function unlinkFiles($cache_files=array(),$other_path='') {
        
            if(!$other_path){
                
                $files = scandir(DIR_SYSTEM . 'library/vendor/ocext/cache/');
                
                $other_path = DIR_SYSTEM . 'library/vendor/ocext/cache/';
                
            }else{
                
                $files = scandir($other_path);
                
            }
            
            foreach($files as $file_name){
                
                foreach($cache_files as $cache_file){
                 
                    if(strstr($file_name, $cache_file)){
                        
                        unlink($other_path.$file_name);
                        
                    }
                    
                }
                
            }
            
        }
        
        
    public function getActionTask($task_id_needle,$action_status_id_needle) {
            
        $file_name_smart_exchange_setting = "smart_exchange_setting";

        $smart_exchange_setting = $this->getCache($file_name_smart_exchange_setting);
        
        $file_name_tasks = "smart_exchange_tasks";
        
        $file_name_smart_exchange_log = "smart_exchange_log";
        
        $smart_exchange_log = $this->getCache($file_name_smart_exchange_log);
        
        $tasks = $this->getCache($file_name_tasks);
        
        foreach ($smart_exchange_setting['odmpro_update_csv_link'] as $id => $odmpro_update_csv_link) {
            
            $timestart = $odmpro_update_csv_link['timestart'];

            $find = array('00','01','02','03','04','05','06','07','08','09');

            $replace_m = array(0,1,2,3,4,5,6,7,8,9);

            $replace_d = array(7,1,2,3,4,5,6);

            $replace_h = array(24,1,2,3,4,5,6,7,8,9);

            $this_d = (int)date('N');

            if($this_d==0){

                $this_d = 7;

            }

            $this_d = (int)str_replace($find, $replace_d, $this_d);

            $this_h = (int)date('G');

            if($this_h==0){

                $this_h = 24;

            }

            $this_h = (int)str_replace($find, $replace_h, $this_h);

            $this_m = date('i');

            $this_m = (int)str_replace($find, $replace_m, $this_m);

            foreach ($timestart as $timestart_row) {

                $task_d = (int)str_replace($find, $replace_d, $timestart_row['d']);

                $task_h = (int)str_replace($find, $replace_h, $timestart_row['h']);

                $task_m = (int)str_replace($find, $replace_m, $timestart_row['m']);
                
                $task_id = $task_h.'-'.$task_m.'-'.$task_d.'-'.$id.'-'.$odmpro_update_csv_link['tamplate_data_id'];

                if( $task_id_needle == $task_id ){

                    $action_status_id = $action_status_id_needle;
                    
                    $last_log_data = '';
                    
                    if($action_status_id==1){
                        
                        $last_log_data = date('Y-m-d G:i:s')." В очередь поставлена задача: ".$odmpro_update_csv_link['type_process'];
                        
                        $tasks[$task_id] = array(
                            'task_id' => $task_id,
                            'action_status_id' => $action_status_id,
                            'setting_id' => $id,
                            'data_mod' => time(),
                            'data_add' => time(),
                            'last_log_data' => $last_log_data,
                            'tamplate_data_id'  => $odmpro_update_csv_link['tamplate_data_id'],
                            'token'  => $odmpro_update_csv_link['token'],
                            'type_process'  => $odmpro_update_csv_link['type_process'],
                            'asynchronous_status'  => $odmpro_update_csv_link['asynchronous_status'],
                            'start_email_notice'  => $odmpro_update_csv_link['start_email_notice'],
                            'timeout_email_notice'  => $odmpro_update_csv_link['timeout_email_notice'],
                            'finish_email_notice'  => $odmpro_update_csv_link['finish_email_notice'],
                            'email_notice'  => trim($odmpro_update_csv_link['email_notice']),
                            'max_task_timeout' => trim($odmpro_update_csv_link['max_task_timeout']),
                            'max_task_timeout_action' => $odmpro_update_csv_link['max_task_timeout_action']
                        );
                        
                    }elseif($action_status_id==3){
                        
                        $last_log_data = date('Y-m-d G:i:s')." Остановлена задача: ".$odmpro_update_csv_link['type_process'];
                        
                        $tasks[$task_id] = array(
                            'task_id' => $task_id,
                            'action_status_id' => $action_status_id,
                            'setting_id' => $id,
                            'data_mod' => time(),
                            'data_add' => time(),
                            'last_log_data' => $last_log_data,
                            'tamplate_data_id'  => $odmpro_update_csv_link['tamplate_data_id'],
                            'token'  => $odmpro_update_csv_link['token'],
                            'type_process'  => $odmpro_update_csv_link['type_process'],
                            'asynchronous_status'  => $odmpro_update_csv_link['asynchronous_status'],
                            'start_email_notice'  => $odmpro_update_csv_link['start_email_notice'],
                            'timeout_email_notice'  => $odmpro_update_csv_link['timeout_email_notice'],
                            'finish_email_notice'  => $odmpro_update_csv_link['finish_email_notice'],
                            'email_notice'  => trim($odmpro_update_csv_link['email_notice']),
                            'max_task_timeout' => trim($odmpro_update_csv_link['max_task_timeout']),
                            'max_task_timeout_action' => $odmpro_update_csv_link['max_task_timeout_action']
                        );
                        
                    }
                    
                    if($last_log_data){
                        
                        $smart_exchange_log[$task_id][] = $last_log_data;

                        $this->writeCache($file_name_smart_exchange_log, $smart_exchange_log);

                        $this->writeCache($file_name_tasks, $tasks);
                        
                        return $last_log_data.'. Дождитесь сообщения о результате в он-лайн логе. Процесс может занять некоторое время';
                        
                    }

                }

            }

        }

    }
        
        /*
         * action_status_id: 1 - new, 2 - next, 3 - finish, 4 - disable, 5 - send task to exchange controller, 7 - send task to exchange model, 8 - send task to exchange model
         */
        
    public function smartExchangeTask($exchange_link_token) {
        
        $debug = 0;
        
        $file_name_smart_exchange_check_connect = "smart_exchange_check_connect";
        
        $smart_exchange_check_connect = $this->getCache($file_name_smart_exchange_check_connect);
        
        $check_connect_key = count($smart_exchange_check_connect);
        
        if($check_connect_key>4){
            
            foreach ($smart_exchange_check_connect as $check_connect_key => $check_connect_value) {
            
                if(!$check_connect_key){
                    
                    unset($smart_exchange_check_connect[count($smart_exchange_check_connect)-1]);
                    
                    unset($smart_exchange_check_connect[$check_connect_key]);
                    
                }else{
                    
                    $smart_exchange_check_connect[$check_connect_key-1] = $check_connect_value;
                    
                }

            }
            
        }
        
        $smart_exchange_check_connect[$check_connect_key]['time_start'] = time();
        
        $file_name_smart_exchange_setting = "smart_exchange_setting";

        $smart_exchange_setting = $this->getCache($file_name_smart_exchange_setting);
        
        if($debug){
            $this->writeLog('debug_log.log',$smart_exchange_setting);
        }
        
        $file_name_smart_exchange_log = "smart_exchange_log";
        
        $smart_exchange_log = $this->getCache($file_name_smart_exchange_log);
        
        $file_name_smart_exchange_queue = "smart_exchange_queue";
        
        $smart_exchange_queue = $this->getCache($file_name_smart_exchange_queue);
        
        $file_name_tasks = "smart_exchange_tasks";
        
        $tasks = $this->getCache($file_name_tasks);
        
        $unset_tasks = $tasks;
        
        $notice_tasks = array();
        
        if(isset($smart_exchange_setting['odmpro_update_csv_link'])){
            
            $max_count_tasks = 0;
            
            if(isset($smart_exchange_setting['odmpro_update_csv_smart_exchange_link']['max_count_tasks']) && $smart_exchange_setting['odmpro_update_csv_smart_exchange_link']['max_count_tasks']!==''){
                
                $max_count_tasks = (int)$smart_exchange_setting['odmpro_update_csv_smart_exchange_link']['max_count_tasks'];
                
            }

            if(isset($smart_exchange_setting['odmpro_update_csv_smart_exchange_link']['max_curl_timeout']) && $smart_exchange_setting['odmpro_update_csv_smart_exchange_link']['max_curl_timeout']!=='' && $smart_exchange_setting['odmpro_update_csv_smart_exchange_link']['max_curl_timeout']<600){

                $this->settings['max_curl_timeout'] = $smart_exchange_setting['odmpro_update_csv_smart_exchange_link']['max_curl_timeout']*1000;

            }
            
            $max_interval_start_exchange = 4;
            
            if(isset($smart_exchange_setting['odmpro_update_csv_smart_exchange_link']['max_interval_start_exchange']) && $smart_exchange_setting['odmpro_update_csv_smart_exchange_link']['max_interval_start_exchange']!==''){
                
                $max_interval_start_exchange = round((int)($smart_exchange_setting['odmpro_update_csv_smart_exchange_link']['max_interval_start_exchange']/60),0);
                
            }
            
            $max_count_log_rows = 500;
            
            if(isset($smart_exchange_setting['odmpro_update_csv_smart_exchange_link']['max_count_log_rows']) && $smart_exchange_setting['odmpro_update_csv_smart_exchange_link']['max_count_log_rows']!==''){
                
                $max_count_log_rows = (int)$smart_exchange_setting['odmpro_update_csv_smart_exchange_link']['max_count_log_rows'];
                
            }
            
            foreach ($smart_exchange_log as $task_id => $smart_exchange_log_rows) {
                
                if(count($smart_exchange_log[$task_id])>$max_count_log_rows){
                    
                    $smart_exchange_log[$task_id] = array();
                    
                    $smart_exchange_log[$task_id][] = "Лог очищен по настройке автоочищения, количество записей превысило: ".$max_count_log_rows;
                    
                }
                
            }
            
            $new_tasks = array();
            
            foreach ($smart_exchange_setting['odmpro_update_csv_link'] as $id => $odmpro_update_csv_link) {
                
                $status = (int)$odmpro_update_csv_link['status'];
                
                $last_log_data = date('Y-m-d G:i:s')." Основной профиль автообновления выключен".PHP_EOL;
                
                $timestart = $odmpro_update_csv_link['timestart'];
                    
                $find = array('00','01','02','03','04','05','06','07','08','09');

                $replace_m = array(0,1,2,3,4,5,6,7,8,9);

                $replace_d = array(7,1,2,3,4,5,6);

                $replace_h = array(24,1,2,3,4,5,6,7,8,9);

                $this_d = (int)date('N');

                if($this_d==0){

                    $this_d = 7;

                }

                $this_d = (int)str_replace($find, $replace_d, $this_d);

                $this_h = (int)date('G');

                if($this_h==0){

                    $this_h = 24;

                }

                $this_h = (int)str_replace($find, $replace_h, $this_h);

                $this_m = date('i');

                $this_m = (int)str_replace($find, $replace_m, $this_m);
                
                if($debug){
                    $this->writeLog('debug_log.log',array($this_d,$this_h,$this_m));
                }
                
                if($status){
                    
                    foreach ($timestart as $timestart_row) {
                        
                        $task_d = (int)str_replace($find, $replace_d, $timestart_row['d']);
                        
                        $task_h = (int)str_replace($find, $replace_h, $timestart_row['h']);
                        
                        $task_m = (int)str_replace($find, $replace_m, $timestart_row['m']);
                        
                        if( ($task_d && $task_h) ){
                            
                            $task_id = $task_h.'-'.$task_m.'-'.$task_d.'-'.$id.'-'.$odmpro_update_csv_link['tamplate_data_id'];
                        
                            unset($unset_tasks[$task_id]);
                            
                            //если по времени пора, пишем в потенциальные задачи

                            if((int)$task_d===$this_d && (int)$task_h===$this_h && $this_m>=$task_m && $this_m<=($task_m+$max_interval_start_exchange) && $odmpro_update_csv_link['type_process']){

                                $new_tasks[$task_id] = array(
                                    'task_id' => $task_id,
                                    'action_status_id' => 1,
                                    'setting_id' => $id,
                                    'data_mod' => time(),
                                    'data_add' => time(),
                                    'last_log_data' => date('Y-m-d G:i:s')." В очередь поставлена задача: ".$odmpro_update_csv_link['type_process'].PHP_EOL,
                                    'tamplate_data_id'  => $odmpro_update_csv_link['tamplate_data_id'],
                                    'token'  => $odmpro_update_csv_link['token'],
                                    'type_process'  => $odmpro_update_csv_link['type_process'],
                                    'asynchronous_status'  => $odmpro_update_csv_link['asynchronous_status'],
                                    'start_email_notice'  => $odmpro_update_csv_link['start_email_notice'],
                                    'timeout_email_notice'  => $odmpro_update_csv_link['timeout_email_notice'],
                                    'finish_email_notice'  => $odmpro_update_csv_link['finish_email_notice'],
                                    'email_notice'  => trim($odmpro_update_csv_link['email_notice']),
                                    'max_task_timeout' => trim($odmpro_update_csv_link['max_task_timeout']),
                                    'max_task_timeout_action' => $odmpro_update_csv_link['max_task_timeout_action']
                                );

                            }else{
                                
                                $new_tasks[$task_id] = array(
                                    'task_id' => $task_id,
                                    'action_status_id' => 3,
                                    'setting_id' => $id,
                                    'data_mod' => time(),
                                    'data_add' => time(),
                                    'last_log_data' => date('Y-m-d G:i:s')." Ожидает времени запуска задача по: ".$odmpro_update_csv_link['type_process'].PHP_EOL,
                                    'tamplate_data_id'  => $odmpro_update_csv_link['tamplate_data_id'],
                                    'token'  => $odmpro_update_csv_link['token'],
                                    'type_process'  => $odmpro_update_csv_link['type_process'],
                                    'asynchronous_status'  => $odmpro_update_csv_link['asynchronous_status'],
                                    'start_email_notice'  => $odmpro_update_csv_link['start_email_notice'],
                                    'timeout_email_notice'  => $odmpro_update_csv_link['timeout_email_notice'],
                                    'finish_email_notice'  => $odmpro_update_csv_link['finish_email_notice'],
                                    'email_notice'  => trim($odmpro_update_csv_link['email_notice']),
                                    'max_task_timeout' => trim($odmpro_update_csv_link['max_task_timeout']),
                                    'max_task_timeout_action' => $odmpro_update_csv_link['max_task_timeout_action']
                                );
                                
                            }
                            
                        }
                        
                    }
                    
                }else{
                    
                    //выключаем все задачи, при выключенном профиле автообмена
                    
                    foreach ($timestart as $timestart_row) {
                        
                        $task_d = (int)str_replace($find, $replace_d, $timestart_row['d']);
                        
                        $task_h = (int)str_replace($find, $replace_h, $timestart_row['h']);
                        
                        $task_m = (int)str_replace($find, $replace_m, $timestart_row['m']);
                        
                        if( ($task_d && $task_h) ){

                            $task_id = $task_h.'-'.$task_m.'-'.$task_d.'-'.$id.'-'.$odmpro_update_csv_link['tamplate_data_id'];

                            unset($unset_tasks[$task_id]);
                            
                            $new_tasks[$task_id] = array(
                                'task_id' => $task_id,
                                'action_status_id' => 4,
                                'setting_id' => $id,
                                'data_mod' => time(),
                                'data_add' => time(),
                                'last_log_data' => $last_log_data,
                                'tamplate_data_id'  => $odmpro_update_csv_link['tamplate_data_id'],
                                'token'  => $odmpro_update_csv_link['token'],
                                'type_process'  => $odmpro_update_csv_link['type_process'],
                                'asynchronous_status'  => $odmpro_update_csv_link['asynchronous_status'],
                                'start_email_notice'  => $odmpro_update_csv_link['start_email_notice'],
                                'timeout_email_notice'  => $odmpro_update_csv_link['timeout_email_notice'],
                                'finish_email_notice'  => $odmpro_update_csv_link['finish_email_notice'],
                                'email_notice'  => trim($odmpro_update_csv_link['email_notice']),
                                'max_task_timeout' => trim($odmpro_update_csv_link['max_task_timeout']),
                                'max_task_timeout_action' => $odmpro_update_csv_link['max_task_timeout_action']
                            );
                            
                        }
                        
                    }
                    
                }
                
            }
            
            foreach ($unset_tasks as $task_id => $tmp) {
                unset($tasks[$task_id]);
            }
            
            if($debug){
                $this->writeLog('debug_log.log',$new_tasks);
            }
            
            krsort($new_tasks);
            
            //проверяем потенциальные задачи на актуальность, с учетом того, что уже делается по обмену
            
            foreach ($new_tasks as $task_id => $task) {
                
                //если задача на импорт, она должна отсутствовать в очереди и должна отсутствовать в задачах, или присутствовать в задачах со статусом завершена или выключена
                
                if($task['action_status_id'] == 1 && !isset($smart_exchange_queue[$task_id]) && (!isset($tasks[$task_id]) || (isset($tasks[$task_id]) && ($tasks[$task_id]['action_status_id']==3 || $tasks[$task_id]['action_status_id']==4) ) ) ){
                        
                    $smart_exchange_queue[$task_id] = $task;

                }
                
                //если задача на импорт и уже есть в истории, то обновляем дату изменений в истории
                
                elseif($task['action_status_id'] == 1 && isset ($smart_exchange_queue[$task_id])  && (!isset($tasks[$task_id]) || (isset($tasks[$task_id]) && ($tasks[$task_id]['action_status_id']==3 || $tasks[$task_id]['action_status_id']==4) ) )   ){

                    //$last_log_data = date('Y-m-d G:i:s')." Повторная запись очереди".PHP_EOL;
                    /////обновляем очередь, только дату изменений
                    $smart_exchange_queue[$task_id]['data_mod'] = time();

                }
                
                //если задача на выключение, то её не должно быть в очереди, или она должна быть в очереди, но статус отличный от 4, и эта задача еще не становилась с этим статусом в задачах
                
                elseif($task['action_status_id'] == 4 &&  (!isset ($smart_exchange_queue[$task_id]) || $smart_exchange_queue[$task_id]['action_status_id']!=4 ) && (!isset ($tasks[$task_id]) || $tasks[$task_id]['action_status_id']!=4 )  ){

                    if(!isset ($tasks[$task_id])){
                        
                        $tasks[$task_id] = $task;
                        
                    }
                    
                    $last_log_data = date('Y-m-d G:i:s')." Основной профиль автообновления выключен".PHP_EOL;
                    
                    $tasks[$task_id]['last_log_data'] = $last_log_data;
                    
                    $smart_exchange_log[$task_id][] = $last_log_data;
                    
                    $smart_exchange_queue[$task_id] = $task;
                    
                    //эта задача больше не подымается, по этому об отключении пишем в лог здесь
                    
                    $this->writeCache($file_name_smart_exchange_log, $smart_exchange_log);

                }
                
                //если задача в режиме ожидания и ранее это не указывалось, её нет в очереди и нет в задачах
                
                elseif($task['action_status_id'] == 3 &&  (!isset ($smart_exchange_queue[$task_id]) ) && ( !isset ($tasks[$task_id]) || $tasks[$task_id]['action_status_id'] == 4 )  ){

                    if(!$task['type_process']){
                        
                        $task['type_process'] = "Не выбран процесс обмена (импорт или экспорт)";
                        
                    }
                    
                    $last_log_data = date('Y-m-d G:i:s')." Ожидает времени запуска задача по: ".$task['type_process'].PHP_EOL;
                    
                    if(!isset ($tasks[$task_id])){
                        
                        $tasks[$task_id] = $task;
                        
                    }
                    
                    $tasks[$task_id]['last_log_data'] = $last_log_data;
                    
                    $tasks[$task_id]['action_status_id'] = 3;
                    
                    $smart_exchange_log[$task_id][] = $last_log_data;
                    
                    //эта задача больше не подымается, по этому об отключении пишем в лог здесь
                    
                    $this->writeCache($file_name_smart_exchange_log, $smart_exchange_log);

                }
                
            }
            
            krsort($new_tasks);
            
        }
        
        if($debug){
            $this->writeLog('debug_log.log', $smart_exchange_queue);
        }
        
        //var_dump($tasks);exit();
        
        //Если в очереди не пусто, смотрим, что пора передавать в задачи
        
        foreach ($smart_exchange_queue as $task_id => $task) {
            
            //выключение всегда передаем
            
            if($task['action_status_id']==4){
                
                $tasks[$task_id] = $task;
                
                unset($smart_exchange_queue[$task_id]);
                
            }
            
            //новая задача на импорт передается, если задач еще не было, или была эта задача, но она уже не со статусом 1
            
            elseif($task['action_status_id']==1){
                
                if(!$tasks || !isset($tasks[$task_id]) || (isset($tasks[$task_id]) && $tasks[$task_id]['action_status_id']!=1 && $tasks[$task_id]['action_status_id']!=2 && $tasks[$task_id]['action_status_id']!=7 && $tasks[$task_id]['action_status_id']!=8 && $tasks[$task_id]['action_status_id']!=5) ){
                    
                    $count_actions = 0;
                    
                    foreach ($tasks as $act) {
                        
                        if($act['action_status_id']==1 || $act['action_status_id']==2 || $act['action_status_id']==5 || $act['action_status_id']==7 || $act['action_status_id']==8){
                            
                            $count_actions++;
                            
                        }
                        
                    }
                    
                    //если в данный момент уже есть задачи в работе, то проверяем разрешение на параллельную работу, в противном случае, задача остается в очереди
                    
                    if($count_actions <= $max_count_tasks || $act['asynchronous_status']){
                        
                        $tasks[$task_id] = $task;
                        
                        unset($smart_exchange_queue[$task_id]);
                        
                        if($task['email_notice'] && $task['start_email_notice']){

                            $notice_tasks[$task['email_notice']][] = array(
                                "sbj" => date('Y-m-d G:i:s')." В очередь поставлена задача ".$task['type_process'],
                                "msg" => date('Y-m-d G:i:s')." В очередь поставлена задача ".$task['type_process'].". Номер задачи (ЧЧ-ММ-Дн.Н-ID) ".$task_id.", номер автообновления (token): ".$task['setting_id']." (".$task['token'].") , номер профиля: ".$task['tamplate_data_id']
                            );

                        }
                        
                    }
                    
                }
                
            }
            
        }
        
        if($debug){
            $this->writeLog('debug_log.log', $tasks);
        }
        
        //задачи обновлены, обновляем файл задач и историю
        
        $write_tasks = $this->writeCache($file_name_tasks, $tasks);
        
        $this->writeCache($file_name_smart_exchange_queue, $smart_exchange_queue);
        
        //после обновления файла, начало обмена
        
        if($write_tasks){
            
            foreach ($tasks as $task_id => $task) {
            
                //задача, продолжения обмена
                
                if($task['action_status_id']==2){

                    $last_log_data = date('Y-m-d G:i:s')." 1. Продолжение обработки по задаче ".$task['type_process']." (".$task['start']."/".$task['total']."), URL: ".html_entity_decode($task['url']).". Номер задачи (ЧЧ-ММ-Дн.Н-ID) ".$task_id.", номер автообновления (token): ".$task['setting_id']." (".$task['token'].") , номер профиля: ".$task['tamplate_data_id'];

                    $tasks[$task_id]['last_log_data'] = $last_log_data;
                    
                    $tasks[$task_id]['action_status_id'] = 5;

                    $smart_exchange_log[$task_id][] = $last_log_data;
                    
                    $this->writeCache($file_name_smart_exchange_log, $smart_exchange_log);
                    
                    $write_tasks = $this->writeCache($file_name_tasks, $tasks);
                    
                    $url = html_entity_decode($task['url']);
                    
                    $header = $this->curl_get_contents($url);
                    
                    $last_log_data = date('Y-m-d G:i:s')." 1.2 Ответ сервера header: ".  json_encode($header);
                    
                    $smart_exchange_log = $this->getCache($file_name_smart_exchange_log);
                    
                    $smart_exchange_log[$task_id][] = $last_log_data;
                    
                    $this->writeCache($file_name_smart_exchange_log, $smart_exchange_log);
                    
                    if(!$header){
                        
                        /*
                         * 
                        $last_log_data = date('Y-m-d G:i:s')." Задача принудительно остановлена, не удалось соедениться с сервером по URL: ".$url.". Номер задачи (ЧЧ-ММ-Дн.Н-ID) ".$task_id.", номер автообновления (token): ".$task['setting_id']." (".$task['token'].") , номер профиля: ".$task['tamplate_data_id'];

                        $tasks[$task_id]['last_log_data'] = $last_log_data;

                        $tasks[$task_id]['action_status_id'] = 3;

                        $smart_exchange_log[$task_id][] = $last_log_data;

                        $this->writeCache($file_name_smart_exchange_log, $smart_exchange_log);

                        $write_tasks = $this->writeCache($file_name_tasks, $tasks);
                        
                        */
                        
                    }elseif(isset($header['error']) && $header['error']!==''){
                        
                        $last_log_data = date('Y-m-d G:i:s')." Задача принудительно остановлена, по причине: ".$header['error'].". Номер задачи (ЧЧ-ММ-Дн.Н-ID) ".$task_id.", номер автообновления (token): ".$task['setting_id']." (".$task['token'].") , номер профиля: ".$task['tamplate_data_id'];

                        $tasks[$task_id]['last_log_data'] = $last_log_data;

                        $tasks[$task_id]['action_status_id'] = 3;

                        $smart_exchange_log[$task_id][] = $last_log_data;

                        $this->writeCache($file_name_smart_exchange_log, $smart_exchange_log);

                        $write_tasks = $this->writeCache($file_name_tasks, $tasks);
                        
                    }elseif(isset($header['error']) && $header['error']!==''){
                        
                        $last_log_data = date('Y-m-d G:i:s')." Задача принудительно остановлена, по причине: ".$header['error'].". Номер задачи (ЧЧ-ММ-Дн.Н-ID) ".$task_id.", номер автообновления (token): ".$task['setting_id']." (".$task['token'].") , номер профиля: ".$task['tamplate_data_id'];

                        $tasks[$task_id]['last_log_data'] = $last_log_data;

                        $tasks[$task_id]['action_status_id'] = 3;

                        $smart_exchange_log[$task_id][] = $last_log_data;

                        $this->writeCache($file_name_smart_exchange_log, $smart_exchange_log);

                        $write_tasks = $this->writeCache($file_name_tasks, $tasks);
                        
                    }elseif (strstr($header, 'HTTP/1.1 404')) {

                        $last_log_data = date('Y-m-d G:i:s')." Задача принудительно остановлена, по причине ошибки 404 по URL: ".$url.". Номер задачи (ЧЧ-ММ-Дн.Н-ID) ".$task_id.", номер автообновления (token): ".$task['setting_id']." (".$task['token'].") , номер профиля: ".$task['tamplate_data_id'];

                        $tasks[$task_id]['last_log_data'] = $last_log_data;

                        $tasks[$task_id]['action_status_id'] = 3;

                        $smart_exchange_log[$task_id][] = $last_log_data;

                        $this->writeCache($file_name_smart_exchange_log, $smart_exchange_log);

                        $write_tasks = $this->writeCache($file_name_tasks, $tasks);

                    }elseif(strstr($header, 'Smart-Exchange: 200')){

                        //

                    }

                }
                
                //задача, которая сейчас в работе, провеяем допустимое время ожидания по задаче
                
                elseif($task['action_status_id']==5 || $task['action_status_id']==7 || $task['action_status_id']==8){
                    
                    $max_task_timeout = (int)$task['max_task_timeout'];
                    
                    $task_timeout = (int)(time()-$task['data_mod']);
                    
                    if($max_task_timeout && $task_timeout>$max_task_timeout){
                        
                        if($task['max_task_timeout_action'] && $task['max_task_timeout_action']==1){
                            
                            $last_log_data = date('Y-m-d G:i:s')." 1. Повторная попытка продолжение обработки, после превышения ожидания (включена настройка запуска повторно с места остановки) по задаче ".$task['type_process']." (".$task['start']."/".$task['total']."). Номер задачи (ЧЧ-ММ-Дн.Н-ID) ".$task_id.", номер автообновления (token): ".$task['setting_id']." (".$task['token'].") , номер профиля: ".$task['tamplate_data_id'];

                            $tasks[$task_id]['last_log_data'] = $last_log_data;
                            
                            $tasks[$task_id]['action_status_id'] = 2;

                            $smart_exchange_log[$task_id][] = $last_log_data;

                            $this->writeCache($file_name_smart_exchange_log, $smart_exchange_log);

                            $write_tasks = $this->writeCache($file_name_tasks, $tasks);
                            
                            $url = html_entity_decode($task['url']);

                            $header = $this->curl_get_contents($url);
                            
                            $last_log_data = date('Y-m-d G:i:s')." 1.2 Ответ сервера header: ".  json_encode($header);
                            
                            $smart_exchange_log = $this->getCache($file_name_smart_exchange_log);
                            
                            $smart_exchange_log[$task_id][] = $last_log_data;
                    
                            $this->writeCache($file_name_smart_exchange_log, $smart_exchange_log);
                    
                            if(!$header){

                                $last_log_data = date('Y-m-d G:i:s')." Задача принудительно остановлена, не удалось соедениться с сервером по URL: ".$url.". Номер задачи (ЧЧ-ММ-Дн.Н-ID) ".$task_id.", номер автообновления (token): ".$task['setting_id']." (".$task['token'].") , номер профиля: ".$task['tamplate_data_id'];

                                $tasks[$task_id]['last_log_data'] = $last_log_data;

                                $tasks[$task_id]['action_status_id'] = 3;

                                $smart_exchange_log[$task_id][] = $last_log_data;

                                $this->writeCache($file_name_smart_exchange_log, $smart_exchange_log);

                                $write_tasks = $this->writeCache($file_name_tasks, $tasks);

                            }elseif(isset($header['error']) && $header['error']!==''){

                                $last_log_data = date('Y-m-d G:i:s')." Задача принудительно остановлена, по причине: ".$header['error'].". Номер задачи (ЧЧ-ММ-Дн.Н-ID) ".$task_id.", номер автообновления (token): ".$task['setting_id']." (".$task['token'].") , номер профиля: ".$task['tamplate_data_id'];

                                $tasks[$task_id]['last_log_data'] = $last_log_data;

                                $tasks[$task_id]['action_status_id'] = 3;

                                $smart_exchange_log[$task_id][] = $last_log_data;

                                $this->writeCache($file_name_smart_exchange_log, $smart_exchange_log);

                                $write_tasks = $this->writeCache($file_name_tasks, $tasks);

                            }elseif(isset($header['error']) && $header['error']!==''){

                                $last_log_data = date('Y-m-d G:i:s')." Задача принудительно остановлена, по причине: ".$header['error'].". Номер задачи (ЧЧ-ММ-Дн.Н-ID) ".$task_id.", номер автообновления (token): ".$task['setting_id']." (".$task['token'].") , номер профиля: ".$task['tamplate_data_id'];

                                $tasks[$task_id]['last_log_data'] = $last_log_data;

                                $tasks[$task_id]['action_status_id'] = 3;

                                $smart_exchange_log[$task_id][] = $last_log_data;

                                $this->writeCache($file_name_smart_exchange_log, $smart_exchange_log);

                                $write_tasks = $this->writeCache($file_name_tasks, $tasks);

                            }elseif (strstr($header, 'HTTP/1.1 404')) {

                                $last_log_data = date('Y-m-d G:i:s')." Задача принудительно остановлена, по причине ошибки 404 по URL: ".$url.". Номер задачи (ЧЧ-ММ-Дн.Н-ID) ".$task_id.", номер автообновления (token): ".$task['setting_id']." (".$task['token'].") , номер профиля: ".$task['tamplate_data_id'];

                                $tasks[$task_id]['last_log_data'] = $last_log_data;

                                $tasks[$task_id]['action_status_id'] = 3;

                                $smart_exchange_log[$task_id][] = $last_log_data;

                                $this->writeCache($file_name_smart_exchange_log, $smart_exchange_log);

                                $write_tasks = $this->writeCache($file_name_tasks, $tasks);

                            }elseif(strstr($header, 'Smart-Exchange: 200')){

                                //

                            }
                            
                        }elseif($task['max_task_timeout_action'] && $task['max_task_timeout_action']==3){
                            
                            $last_log_data = date('Y-m-d G:i:s')." Задача остановлена после превышения ожидания (включена настройка остановки задачи, если превышено ожидание) по задаче ".$task['type_process']." (".$task['start']."/".$task['total']."). Номер задачи (ЧЧ-ММ-Дн.Н-ID) ".$task_id.", номер автообновления (token): ".$task['setting_id']." (".$task['token'].") , номер профиля: ".$task['tamplate_data_id'];

                            $tasks[$task_id]['last_log_data'] = $last_log_data;
                            
                            $tasks[$task_id]['action_status_id'] = 3;

                            $smart_exchange_log[$task_id][] = $last_log_data;

                            $this->writeCache($file_name_smart_exchange_log, $smart_exchange_log);

                            $write_tasks = $this->writeCache($file_name_tasks, $tasks);
                            
                        }
                        
                        if($task['email_notice'] && $task['max_task_timeout']){

                            $notice_tasks[$task['email_notice']][] = array(
                                "sbj" => date('Y-m-d G:i:s')." Превышено время ожидания по выполнению задачи ".$task['type_process'],
                                "msg" => date('Y-m-d G:i:s')." Превышено время ожидания по выполнению задачи ".$task['type_process'].". Номер задачи (ЧЧ-ММ-Дн.Н-ID) ".$task_id.", номер автообновления (token): ".$task['setting_id']." (".$task['token'].") , номер профиля: ".$task['tamplate_data_id'].PHP_EOL."Запись в логе: ".$last_log_data
                            );

                        }
                        
                    }
                    
                }
                
                //новая задача импорта
                
                elseif($task['action_status_id']==1){
                    
                    $url = HTTP_SERVER.'index.php?route='.$smart_exchange_setting['odmpro_update_csv_smart_exchange_link']['path_oc_version_feed'].'/odmpro_update_csv_link&'.$task['type_process'].'=1&token='.$task['token'].'&task_id='.$task_id;

                    if($task['type_process']=='export'){
                        
                       $url .= '&first_row=1';
                        
                    }
                    
                    $last_log_data = date('Y-m-d G:i:s')." 1. Задача обработки стартует для ".$task['type_process'].", URL: ".$url.". Номер задачи (ЧЧ-ММ-Дн.Н-ID) ".$task_id.", номер автообновления (token): ".$task['setting_id']." (".$task['token'].") , номер профиля: ".$task['tamplate_data_id'];

                    $tasks[$task_id]['last_log_data'] = $last_log_data;
                    
                    $tasks[$task_id]['action_status_id'] = 5;
                    
                    $tasks[$task_id]['url'] = $url;

                    $smart_exchange_log[$task_id][] = $last_log_data;

                    $this->writeCache($file_name_smart_exchange_log, $smart_exchange_log);

                    $write_tasks = $this->writeCache($file_name_tasks, $tasks);
                    
                    if($write_tasks){
                    
                        $header = $this->curl_get_contents($url);
                        
                        $last_log_data = date('Y-m-d G:i:s')." 1.2 Ответ сервера header: ".  json_encode($header);
                        
                        $smart_exchange_log = $this->getCache($file_name_smart_exchange_log);
                            
                        $smart_exchange_log[$task_id][] = $last_log_data;
                    
                        $this->writeCache($file_name_smart_exchange_log, $smart_exchange_log);

                        if(!$header){

                            /*
                            
                            $last_log_data = date('Y-m-d G:i:s')." Задача принудительно остановлена, не удалось соедениться с сервером по URL: ".$url.". Номер задачи (ЧЧ-ММ-Дн.Н-ID) ".$task_id.", номер автообновления (token): ".$task['setting_id']." (".$task['token'].") , номер профиля: ".$task['tamplate_data_id'];

                            $tasks[$task_id]['last_log_data'] = $last_log_data;

                            $tasks[$task_id]['action_status_id'] = 3;

                            $smart_exchange_log[$task_id][] = $last_log_data;

                            $this->writeCache($file_name_smart_exchange_log, $smart_exchange_log);

                            $write_tasks = $this->writeCache($file_name_tasks, $tasks);
                            
                            */

                        }elseif(isset($header['error']) && $header['error']!==''){

                            $last_log_data = date('Y-m-d G:i:s')." Задача принудительно остановлена, по причине: ".$header['error'].". Номер задачи (ЧЧ-ММ-Дн.Н-ID) ".$task_id.", номер автообновления (token): ".$task['setting_id']." (".$task['token'].") , номер профиля: ".$task['tamplate_data_id'];

                            $tasks[$task_id]['last_log_data'] = $last_log_data;

                            $tasks[$task_id]['action_status_id'] = 3;

                            $smart_exchange_log[$task_id][] = $last_log_data;

                            $this->writeCache($file_name_smart_exchange_log, $smart_exchange_log);

                            $write_tasks = $this->writeCache($file_name_tasks, $tasks);

                        }elseif(isset($header['error']) && $header['error']!==''){

                            $last_log_data = date('Y-m-d G:i:s')." Задача принудительно остановлена, по причине: ".$header['error'].". Номер задачи (ЧЧ-ММ-Дн.Н-ID) ".$task_id.", номер автообновления (token): ".$task['setting_id']." (".$task['token'].") , номер профиля: ".$task['tamplate_data_id'];

                            $tasks[$task_id]['last_log_data'] = $last_log_data;

                            $tasks[$task_id]['action_status_id'] = 3;

                            $smart_exchange_log[$task_id][] = $last_log_data;

                            $this->writeCache($file_name_smart_exchange_log, $smart_exchange_log);

                            $write_tasks = $this->writeCache($file_name_tasks, $tasks);

                        }elseif (strstr($header, 'HTTP/1.1 404')) {

                            $last_log_data = date('Y-m-d G:i:s')." Задача принудительно остановлена, по причине ошибки 404 по URL: ".$url.". Номер задачи (ЧЧ-ММ-Дн.Н-ID) ".$task_id.", номер автообновления (token): ".$task['setting_id']." (".$task['token'].") , номер профиля: ".$task['tamplate_data_id'];

                            $tasks[$task_id]['last_log_data'] = $last_log_data;

                            $tasks[$task_id]['action_status_id'] = 3;

                            $smart_exchange_log[$task_id][] = $last_log_data;

                            $this->writeCache($file_name_smart_exchange_log, $smart_exchange_log);

                            $write_tasks = $this->writeCache($file_name_tasks, $tasks);

                        }elseif (strstr($header, 'HTTP/1.1 503')) {

                            $last_log_data = date('Y-m-d G:i:s')." Задача принудительно остановлена, по причине ошибки 503 по URL: ".$url.". Номер задачи (ЧЧ-ММ-Дн.Н-ID) ".$task_id.", номер автообновления (token): ".$task['setting_id']." (".$task['token'].") , номер профиля: ".$task['tamplate_data_id'];

                            $tasks[$task_id]['last_log_data'] = $last_log_data;

                            $tasks[$task_id]['action_status_id'] = 3;

                            $smart_exchange_log[$task_id][] = $last_log_data;

                            $this->writeCache($file_name_smart_exchange_log, $smart_exchange_log);

                            $write_tasks = $this->writeCache($file_name_tasks, $tasks);

                        }elseif (strstr($header, 'HTTP/1.1 500') || strstr($header, 'HTTP/1.1 504')) {

                            $last_log_data = date('Y-m-d G:i:s')." Задача принудительно остановлена, по причине ошибки 500 или 504 по URL: ".$url.". Номер задачи (ЧЧ-ММ-Дн.Н-ID) ".$task_id.", номер автообновления (token): ".$task['setting_id']." (".$task['token'].") , номер профиля: ".$task['tamplate_data_id'];

                            $tasks[$task_id]['last_log_data'] = $last_log_data;

                            $tasks[$task_id]['action_status_id'] = 3;

                            $smart_exchange_log[$task_id][] = $last_log_data;

                            $this->writeCache($file_name_smart_exchange_log, $smart_exchange_log);

                            $write_tasks = $this->writeCache($file_name_tasks, $tasks);

                        }elseif(strstr($header, 'Smart-Exchange: 200')){

                            //

                        }
                        //HTTP/1.1 503
                    }

                }

            }
            
        }
        
        $smart_exchange_check_connect[$check_connect_key]['time_finish'] = time();
        
        $smart_exchange_check_connect[$check_connect_key]['memory_usage'] = round((memory_get_usage()/1024/1024),3).'Mb';
        
        ksort($smart_exchange_check_connect);
        
        $this->writeCache($file_name_smart_exchange_check_connect, $smart_exchange_check_connect);
        
        if($debug){
            $this->writeLog('debug_log.log', $notice_tasks);
        }
        
        if($notice_tasks){
            
            require_once DIR_SYSTEM.'library/mail.php';
            
            $mail = new Mail();
            
            $mail->setFrom('smart-exchange-notice@'.str_replace(array('https://','http://','/'), array(''), HTTP_SERVER));
            
            $mail->setSender('smart-exchange-notice');
            
            foreach ($notice_tasks as $email_notice => $notices) {
                
                $mail->setTo('');
            
                $email_notice_parts = explode(',', $email_notice);
                
                $email_notice = array();
                
                foreach ($email_notice_parts as $email_notice_part) {
                    
                    $email_notice_part = trim($email_notice_part);
                    
                    if(filter_var($email_notice_part, FILTER_VALIDATE_EMAIL)){
                        
                        $email_notice[] = $email_notice_part;
                        
                    }
                    
                }
                
                if($email_notice){
                    
                    for($e=0;$e<count($email_notice);$e++){
                        
                        $mail->setTo($email_notice[$e]);
                        
                        for($n=0;$n<count($notices);$n++){
                            
                            $mail->setSubject('smart-exchange-notice: '.$notices[$n]['sbj']);
                            
                            $mail->setText($notices[$n]['msg']);
                            
                            $mail->send();
                            
                            sleep(5);
                            
                        }
                        
                    }
                    
                }

            }
            
        }
        
    }

    function curl_get_contents($url) {
        if(function_exists('curl_version')){
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HEADER, 1);
            curl_setopt($ch, CURLOPT_NOBODY, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $this->settings['max_curl_timeout']/1000);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT_MS, $this->settings['max_curl_timeout']);
            $output = curl_exec($ch);
            curl_close($ch);
            return $output;
        }else{
            $output['error'] = 'На хостинге выключено расширение CURL. Без данное расширения невозможно завершить задачу';
        }
        return $output;
    }
    
    public function getArrayByDelimeterAndString($string,$delimeter='|',$trim=TRUE){
     
        $result = array();
        
        $parts = explode($delimeter,$string);
            
        foreach ($parts as $part) {
            
            if($trim){
                
                $part = trim(ltrim($part));
                
            }
            
            if($part!==''){
                
                $result[$part] = $part;
                
            }
            
        }
        
        return $result;
        
    }
    
    public function getNamePathSupplierCaregories($parent_id,$name,$delimeter='/',$field='name',$first_delimeter=''){
        
        if(!isset($this->yml['categories'][$parent_id])){
            
            return $first_delimeter.$name;
            
        }else{
            
            $name = $this->yml['categories'][$parent_id][$field].$delimeter.$name;
            
            return $this->getNamePathSupplierCaregories($this->yml['categories'][$parent_id]['parent_id'],$name,$delimeter,$field,$first_delimeter);
            
        }
        
    }

    public function supplierFeedSourceToDSV($filename,$template_data,$class){
        
        $supplier_feed_source = new $class($this->registry,$this->path_oc_version,$this->language,$this->load,array());
        
        return $supplier_feed_source->feedToDSV($filename,$template_data);
        
    }

    public function ymlToDSV($filename,$template_data){
        
        if(isset($template_data['supplier_feed_source']) && $template_data['supplier_feed_source']){
            
            $parts = explode('___', $template_data['supplier_feed_source']);
            
            $class = 'anyDSVXLS'.$parts[0];
            
            return $this->supplierFeedSourceToDSV($filename,$template_data,$class);
            
        }
        
        $result = array('error'=>'','file_upload'=>'');
        
        $tags = array('yml_catalog'=>array('shop'=>array('offers'=>array('offer'))));
        
        //$tags = array('yml_catalog'=>array('shop'=>array('categories'=>array('category'))));
        
        $tags_new_names = array(
            'yml_catalog'=>'yml_catalog',
            'shop'=>'shop',
            'offers'=>'offers',
            'offer'=>'offer',
            'categories'=>'categories',
            'category'=>'category',
        );
        
        $this->getYMLCategories($filename,$template_data, array('yml_catalog'=>array('shop'=>array('categories'=>array('category')))), 1, $tags_new_names, 0, 100000,  '', 0);
        
        $this->yml['delete_by_vendor'] = $this->getArrayByDelimeterAndString($template_data['anyyml_delete_by_vendor']);
        
        $option_columns = $this->getArrayByDelimeterAndString($template_data['anyyml_option_columns']);
        
        $attribute_columns = $this->getArrayByDelimeterAndString($template_data['anyyml_attribute_columns']);
        
        $delete_columns = $this->getArrayByDelimeterAndString($template_data['anyyml_delete_columns']);
        
        $this->yml['delete_columns'] = $delete_columns;
        
        $this->yml['attribute_columns'] = $attribute_columns;
        
        $this->yml['option_columns'] = $option_columns;
        
        $this->yml['option_value_by_column'] = $template_data['anyyml_option_value_by_column'];
        
        $this->yml['option_type'] = $template_data['anyyml_option_type'];
        
        $this->yml['params_as_column'] = $template_data['anyyml_params_as_column'];
        
        if($template_data['anyyml_max_curl_timeout'] && $template_data['anyyml_max_curl_timeout']!=='' && $template_data['anyyml_max_curl_timeout']<600){
            
            $this->settings['max_curl_timeout'] = $template_data['anyyml_max_curl_timeout']*1000;
            
        }
        
        $this->yml['option_whis_image'] = array();
        
        if(isset($template_data['anyyml_option_whis_image'])){
            
            $this->yml['option_whis_image'] = $this->getArrayByDelimeterAndString($template_data['anyyml_option_whis_image']);
            
        }
        
        $this->yml['select_params_as_column'] = $this->getArrayByDelimeterAndString($template_data['anyyml_select_params_as_column']);
        
        $this->yml['delete_params'] = $this->getArrayByDelimeterAndString($template_data['anyyml_delete_params']);
        
        $this->yml['start_offer'] = (int)$template_data['anyyml_start_offer'];
        
        if($this->yml['start_offer']==1){
            
            $this->yml['start_offer'] = 0;
            
        }
        
        $this->yml['limit_offer'] = trim($template_data['anyyml_limit_offer']);
        
        if($this->yml['limit_offer']===''){
            
            $this->yml['limit_offer'] = 1000000;
            
        }else{
            
            $this->yml['limit_offer'] = (int)$this->yml['limit_offer'];
            
        }
        
        $result = $this->getCSVFromYML($filename,$template_data, $tags, 1, $tags_new_names, $this->yml['start_offer'], $this->yml['limit_offer'],  '', 0);
        
        if($result['file_upload'] && is_string($result['file_upload']) && is_file(DIR_DOWNLOAD.$result['file_upload'])){
            
            $result['file_upload'] = $result['file_upload'];
            
        }
        
        return $result;
        
    }

    public function getYMLCategories($filename,$template_data,$tags,$count_nodes=1,$tags_new_names=array(),$start=0,$limit=100000,$file_name_cach_prefix,$selected_setting_id=''){
        
        //var_dump($template_data);exit();  
        
        $myXML = new XMLReader();
        
        $this->yml['time_start'] = time();
        
        $myXML->open($filename);
        
        $result = array('file'=>array(),'error'=>array());
        
        $inner_xml = '';
                                    
        $count_subnodes = 0;
        
        $this_num_row = 0;
        
        $first_write = 1;
        
        while ($myXML->read()) {
            
            $tag = $myXML->name;
            
            $outer_xml = '';
            
            $outer_xml_close = '';
            
            $tag_name = $tag;
                                            
            if(isset($tags_new_names[$tag])){

                $tag_name = $tags_new_names[$tag];

            }
            
            if ($myXML->nodeType == XMLReader::ELEMENT && isset($tags[$tag])) {
                
                while($myXML->read()) { 

                    $tag2 = $myXML->name;
                    
                    $tag_name = $tag;
                    
                    if(isset($tags_new_names[$tag])){
                        
                        $tag_name = $tags_new_names[$tag];
                        
                    }
                    
                    $tag_name2 = $tag2;
                                            
                    if(isset($tags_new_names[$tag2])){

                        $tag_name2 = $tags_new_names[$tag2];

                    }
                    
                    $outer_xml = '<'.$tag_name.'>';
                    
                    $outer_xml_close = '</'.$tag_name.'>';
                    
                    if ($myXML->nodeType == XMLReader::ELEMENT && isset($tags[$tag][$tag2])) {
                        
                        while($myXML->read()) {
                        
                            $tag3 = $myXML->name;
                            
                            $tag_name2 = $tag2;
                    
                            if(isset($tags_new_names[$tag2])){

                                $tag_name2 = $tags_new_names[$tag2];

                            }
                            
                            $tag_name3 = $tag3;
                                            
                            if(isset($tags_new_names[$tag3])){

                                $tag_name3 = $tags_new_names[$tag3];

                            }
                            
                            $outer_xml = '<'.$tag_name.'>'.'<'.$tag_name2.'>';
                            
                            $outer_xml_close = '</'.$tag_name2.'>'.'</'.$tag_name.'>';

                            if ($myXML->nodeType == XMLReader::ELEMENT && isset($tags[$tag][$tag2][$tag3])) {
                                
                                while($myXML->read()) {
                                    
                                    $tag4 = $myXML->name;
                                    
                                    $tag_name3 = $tag3;
                    
                                    if(isset($tags_new_names[$tag3])){

                                        $tag_name3 = $tags_new_names[$tag3];

                                    }
                                    
                                    $tag_name4 = $tag4;
                                            
                                    if(isset($tags_new_names[$tag4])){

                                        $tag_name4 = $tags_new_names[$tag4];

                                    }
                                    
                                    $outer_xml = '<'.$tag_name.'>'.'<'.$tag_name2.'>'.'<'.$tag_name3.'>';
                                    
                                    $outer_xml_close = '</'.$tag_name3.'>'.'</'.$tag_name2.'>'.'</'.$tag_name.'>';
                                    
                                    if ($myXML->nodeType == XMLReader::ELEMENT && isset($tags[$tag][$tag2][$tag3][$tag4])) {
                                        
                                        while($myXML->read()) {
                                            
                                            $tag5 = $myXML->name;
                                            
                                            $tag_name4 = $tag4;
                    
                                            if(isset($tags_new_names[$tag4])){

                                                $tag_name4 = $tags_new_names[$tag4];

                                            }
                                            
                                            $tag_name5 = $tag5;
                                            
                                            if(isset($tags_new_names[$tag5])){

                                                $tag_name5 = $tags_new_names[$tag5];

                                            }
                                             
                                            $outer_xml = '<'.$tag_name.'>'.'<'.$tag_name2.'>'.'<'.$tag_name3.'>'.'<'.$tag_name4.'>';
                                            
                                            $outer_xml_close = '</'.$tag_name4.'>'.'</'.$tag_name3.'>'.'</'.$tag_name2.'>'.'</'.$tag_name.'>';
                                            
                                            if ($myXML->nodeType == XMLReader::ELEMENT && isset($tags[$tag][$tag2][$tag3][$tag4][$tag5])) {
                                                
                                                $result['error'] = 'out of 5 nodes - выход за пять вложенных узлов';
                                                
                                            }
                                            elseif(in_array($tag5, $tags[$tag][$tag2][$tag3][$tag4])){
                                        
                                                if ($myXML->nodeType == XMLReader::ELEMENT){
                                            
                                                    $xml_as_string = $myXML->readInnerXml();

                                                    if($xml_as_string){
                                                        
                                                        $count_subnodes++;

                                                        $inner_xml .= '<'.$tag_name5.'>'.$xml_as_string.'</'.$tag_name5.'>';

                                                        if($count_subnodes===$count_nodes){

                                                            $xml_cache = $outer_xml.$inner_xml.$outer_xml_close;

                                                            $as_file_xml_cache = $file_name_cach_prefix.count($result['as_files']).'.xml';

                                                            $result['as_files'][$as_file_xml_cache] = DIR_CACHE.$as_file_xml_cache;

                                                            $this->writeXMLCache($as_file_xml_cache,$xml_cache);

                                                            $count_subnodes = 0;

                                                            $xml_cache = '';

                                                            $inner_xml = '';

                                                        }

                                                    }

                                                }

                                            }
                                            
                                        }
                                        
                                    }
                                    
                                    elseif(in_array($tag4, $tags[$tag][$tag2][$tag3])){
                                        
                                        if ($myXML->nodeType == XMLReader::ELEMENT){
                                            
                                            $xml_as_string = $myXML->readInnerXml();

                                            if($xml_as_string){
                                                
                                                $count_subnodes++;
                                                
                                                $inner_xml .= '<'.$tag_name4;
                                                
                                                $readOuterXml = $myXML->readOuterXml();
                                    
                                                if($readOuterXml && is_string($readOuterXml)){
                                                    
                                                    $xml_as_sxml = (array)simplexml_load_string($readOuterXml);

                                                    if(isset($xml_as_sxml['@attributes'])){

                                                        foreach ($xml_as_sxml['@attributes'] as $attribute_name => $attribute_value) {

                                                            $inner_xml .= ' '.$attribute_name.'="'.htmlspecialchars($attribute_value,ENT_QUOTES).'"';

                                                        }

                                                    }
                                                }
                                                
                                                $inner_xml .= '>'.$xml_as_string.'</'.$tag_name4.'>'.PHP_EOL;

                                                if($count_subnodes===$count_nodes){
                                                    
                                                    if($inner_xml && $tag_name4=='category'){
                                                        
                                                        $category_row = simplexml_load_string($inner_xml);
                                                        
                                                        $parent_id = 0;

                                                        $category_id = 0;

                                                        $name = '';

                                                        $attributes = (array)$category_row->attributes();

                                                        if(isset($attributes['@attributes']['id'])){

                                                            $category_id = trim((string)$attributes['@attributes']['id']);

                                                        }

                                                        if(isset($attributes['@attributes']['parentId'])){

                                                            $parent_id = trim((string)$attributes['@attributes']['parentId']);

                                                        }
                                                        
                                                        if(!$parent_id){
                                                            
                                                            $parent_id = 0;
                                                            
                                                        }
                                                        
                                                        if(isset($attributes['@attributes']['name'])){

                                                            $name = trim((string)$attributes['@attributes']['name']);

                                                        }else{
                                                            
                                                            $name = trim((string)$category_row);
                                                            
                                                        }
                                                        
                                                        if($name){
                                                            
                                                            $this->yml['categories'][$category_id] = array(
                                                                'category_id'  => $category_id,
                                                                'parent_id'  => $parent_id,
                                                                'name' => $name,
                                                            );
                                                            
                                                        }
                                                        
                                                    }

                                                    $xml_cache = $outer_xml.$inner_xml.$outer_xml_close;
                                                    
                                                    $count_subnodes = 0;
                                                    
                                                    $xml_cache = '';
                                                    
                                                    $inner_xml = '';

                                                }

                                            }
                                            
                                        }
                                        
                                    }
                                    
                                }

                            }
                            
                            elseif(in_array($tag3, $tags[$tag][$tag2])){
                                        
                                if ($myXML->nodeType == XMLReader::ELEMENT){
                                            
                                    $xml_as_string = $myXML->readInnerXml();

                                    if($xml_as_string){

                                        $count_subnodes++;

                                        $inner_xml .= '<'.$tag_name3.'>'.$xml_as_string.'</'.$tag_name3.'>'.PHP_EOL;;

                                        if($count_subnodes===$count_nodes){

                                            $xml_cache = $outer_xml.$inner_xml.$outer_xml_close;

                                            //$as_file_xml_cache = $file_name_cach_prefix.count($result['as_files']).'.xml';

                                            //$result['as_files'][$as_file_xml_cache] = DIR_CACHE.$as_file_xml_cache;

                                            //$this->writeXMLCache($as_file_xml_cache,$xml_cache);

                                            $count_subnodes = 0;

                                            $xml_cache = '';

                                            $inner_xml = '';

                                        }

                                    }

                                }

                            }
                            
                        }
                        
                    }
                    elseif(in_array($tag2, $tags[$tag])){
                        
                        if ($myXML->nodeType == XMLReader::ELEMENT){
                                            
                            $xml_as_string = $myXML->readInnerXml();

                            if($xml_as_string){

                                $count_subnodes++;

                                $inner_xml .= '<'.$tag_name2.'>'.$xml_as_string.'</'.$tag_name2.'>'.PHP_EOL;

                                if($count_subnodes===$count_nodes){

                                    $xml_cache = $outer_xml.$inner_xml.$outer_xml_close;

                                    $as_file_xml_cache = $file_name_cach_prefix.count($result['as_files']).'.xml';

                                    $result['as_files'][$as_file_xml_cache] = DIR_CACHE.$as_file_xml_cache;

                                    //$this->writeXMLCache($as_file_xml_cache,$xml_cache);

                                    $count_subnodes = 0;

                                    $xml_cache = '';

                                    $inner_xml = '';

                                }

                            }

                        }

                    }

                }
                
            }elseif(in_array($tag, $tags)){

                if ($myXML->nodeType == XMLReader::ELEMENT){
                                            
                    $xml_as_string = $myXML->readInnerXml();

                    if($xml_as_string){

                        $count_subnodes++;

                        $inner_xml .= '<'.$tag_name.'>'.$xml_as_string.'</'.$tag_name.'>'.PHP_EOL;

                        if($count_subnodes===$count_nodes){

                            $xml_cache = $outer_xml.$inner_xml.$outer_xml_close;

                            $as_file_xml_cache = $file_name_cach_prefix.count($result['as_files']).'.xml';

                            $result['as_files'][$as_file_xml_cache] = DIR_CACHE.$as_file_xml_cache;

                            //$this->writeXMLCache($as_file_xml_cache,$xml_cache);

                            $count_subnodes = 0;

                            $xml_cache = '';

                            $inner_xml = '';

                        }

                    }

                }

            }
            
        }
        
        if($count_subnodes && $inner_xml!==''){
            
            $xml_cache = $outer_xml.$inner_xml.$outer_xml_close;

            //$as_file_xml_cache = $file_name_cach_prefix.count($result['as_files']).'.xml';

            //$result['as_files'][$as_file_xml_cache] = DIR_CACHE.$as_file_xml_cache;

            //$this->writeXMLCache($as_file_xml_cache,$xml_cache);

            $count_subnodes = 0;

        }
        
        $myXML->close();
        
        $this->yml['delete_by_category_id'] = $this->getArrayByDelimeterAndString($template_data['anyyml_delete_by_category_id']);
        
        $this->yml['set_products_by_category_id'] = $this->getArrayByDelimeterAndString($template_data['anyyml_set_products_by_category_id']);
        
        foreach ($this->yml['categories'] as $category_id => $category) {
            
            if(!$this->yml['delete_by_category_id'] || !in_array($category_id, $this->yml['delete_by_category_id'])/* && (!$this->yml['set_products_by_category_id'] || in_array($category_id, $this->yml['set_products_by_category_id']))*/ ){
                
                $this->yml['category_path'][$category_id] = $this->getNamePathSupplierCaregories($category['parent_id'], $category['name'],'/','name');
                
                
                
            }
            
            $this->yml['category_path_by_category_id'][$category_id] = $this->getNamePathSupplierCaregories($category['parent_id'], $category['category_id'],'____','category_id','____');
            
        }
        
        $this->yml['categories'] = array();
        
        return $result;
        
    }

    public function getCSVFromYML($filename,$template_data,$tags,$count_nodes=1,$tags_new_names=array(),$start=0,$limit=100000,$file_name_cach_prefix,$selected_setting_id=''){
        
        $offers_file_name_prefix = '';
        
        if($this->yml_cache['status']){

            $offers_file_name_prefix = $template_data['id'].'-'.$this->yml_cache['offers_file_name_prefix'];
            
            $this->unlinkFiles(array($offers_file_name_prefix),DIR_SYSTEM . 'library/vendor/ocext/cache/yml_cache/');
            
        }
        
        $myXML = new XMLReader();
        
        $this->file_name_on_xml = $filename;
        
        $myXML->open($filename);
        
        $result = array('file'=>array(),'error'=>array());
        
        $inner_xml = '';
                                    
        $count_subnodes = 0;
        
        $this_num_row = 0;
        
        $first_write = 1;
        
        while ($myXML->read()) {
            
            $tag = $myXML->name;
            
            $outer_xml = '';
            
            $outer_xml_close = '';
            
            $tag_name = $tag;
                                            
            if(isset($tags_new_names[$tag])){

                $tag_name = $tags_new_names[$tag];

            }
            
            if ($myXML->nodeType == XMLReader::ELEMENT && isset($tags[$tag])) {
                
                while($myXML->read()) { 

                    $tag2 = $myXML->name;
                    
                    $tag_name = $tag;
                    
                    if(isset($tags_new_names[$tag])){
                        
                        $tag_name = $tags_new_names[$tag];
                        
                    }
                    
                    $tag_name2 = $tag2;
                                            
                    if(isset($tags_new_names[$tag2])){

                        $tag_name2 = $tags_new_names[$tag2];

                    }
                    
                    $outer_xml = '<'.$tag_name.'>';
                    
                    $outer_xml_close = '</'.$tag_name.'>';
                    
                    if ($myXML->nodeType == XMLReader::ELEMENT && isset($tags[$tag][$tag2])) {
                        
                        while($myXML->read()) {
                        
                            $tag3 = $myXML->name;
                            
                            $tag_name2 = $tag2;
                    
                            if(isset($tags_new_names[$tag2])){

                                $tag_name2 = $tags_new_names[$tag2];

                            }
                            
                            $tag_name3 = $tag3;
                                            
                            if(isset($tags_new_names[$tag3])){

                                $tag_name3 = $tags_new_names[$tag3];

                            }
                            
                            $outer_xml = '<'.$tag_name.'>'.'<'.$tag_name2.'>';
                            
                            $outer_xml_close = '</'.$tag_name2.'>'.'</'.$tag_name.'>';

                            if ($myXML->nodeType == XMLReader::ELEMENT && isset($tags[$tag][$tag2][$tag3])) {
                                
                                while($myXML->read()) {
                                    
                                    $tag4 = $myXML->name;
                                    
                                    $tag_name3 = $tag3;
                    
                                    if(isset($tags_new_names[$tag3])){

                                        $tag_name3 = $tags_new_names[$tag3];

                                    }
                                    
                                    $tag_name4 = $tag4;
                                            
                                    if(isset($tags_new_names[$tag4])){

                                        $tag_name4 = $tags_new_names[$tag4];

                                    }
                                    
                                    $outer_xml = '<'.$tag_name.'>'.'<'.$tag_name2.'>'.'<'.$tag_name3.'>';
                                    
                                    $outer_xml_close = '</'.$tag_name3.'>'.'</'.$tag_name2.'>'.'</'.$tag_name.'>';
                                    
                                    if ($myXML->nodeType == XMLReader::ELEMENT && isset($tags[$tag][$tag2][$tag3][$tag4])) {
                                        
                                        while($myXML->read()) {
                                            
                                            $tag5 = $myXML->name;
                                            
                                            $tag_name4 = $tag4;
                    
                                            if(isset($tags_new_names[$tag4])){

                                                $tag_name4 = $tags_new_names[$tag4];

                                            }
                                            
                                            $tag_name5 = $tag5;
                                            
                                            if(isset($tags_new_names[$tag5])){

                                                $tag_name5 = $tags_new_names[$tag5];

                                            }
                                             
                                            $outer_xml = '<'.$tag_name.'>'.'<'.$tag_name2.'>'.'<'.$tag_name3.'>'.'<'.$tag_name4.'>';
                                            
                                            $outer_xml_close = '</'.$tag_name4.'>'.'</'.$tag_name3.'>'.'</'.$tag_name2.'>'.'</'.$tag_name.'>';
                                            
                                            if ($myXML->nodeType == XMLReader::ELEMENT && isset($tags[$tag][$tag2][$tag3][$tag4][$tag5])) {
                                                
                                                $result['error'] = 'out of 5 nodes - выход за пять вложенных узлов';
                                                
                                            }
                                            elseif(in_array($tag5, $tags[$tag][$tag2][$tag3][$tag4])){
                                        
                                                if ($myXML->nodeType == XMLReader::ELEMENT){
                                            
                                                    $xml_as_string = $myXML->readInnerXml();

                                                    if($xml_as_string){
                                                        
                                                        $count_subnodes++;

                                                        $inner_xml .= '<'.$tag_name5.'>'.$xml_as_string.'</'.$tag_name5.'>';

                                                        if($count_subnodes===$count_nodes){

                                                            $xml_cache = $outer_xml.$inner_xml.$outer_xml_close;

                                                            $as_file_xml_cache = $file_name_cach_prefix.count($result['as_files']).'.xml';

                                                            $result['as_files'][$as_file_xml_cache] = DIR_CACHE.$as_file_xml_cache;

                                                            $this->writeXMLCache($as_file_xml_cache,$xml_cache);

                                                            $count_subnodes = 0;

                                                            $xml_cache = '';

                                                            $inner_xml = '';

                                                        }

                                                    }

                                                }

                                            }
                                            
                                        }
                                        
                                    }
                                    
                                    elseif(in_array($tag4, $tags[$tag][$tag2][$tag3])){
                                        
                                        if ($myXML->nodeType == XMLReader::ELEMENT){
                                            
                                            $xml_as_string = $myXML->readInnerXml();

                                            if($xml_as_string){
                                                
                                                $count_subnodes++;
                                                
                                                $inner_xml .= '<'.$tag_name4;
                                                
                                                $readOuterXml = $myXML->readOuterXml();
                                    
                                                if($readOuterXml && is_string($readOuterXml)){
                                                    
                                                    $xml_as_sxml = (array)simplexml_load_string($readOuterXml);

                                                    if(isset($xml_as_sxml['@attributes'])){

                                                        foreach ($xml_as_sxml['@attributes'] as $attribute_name => $attribute_value) {

                                                            $inner_xml .= ' '.$attribute_name.'="'.$attribute_value.'"';

                                                        }

                                                    }
                                                }
                                                
                                                $inner_xml .= '>'.$xml_as_string.'</'.$tag_name4.'>'.PHP_EOL;

                                                if($count_subnodes===$count_nodes){
                                                    
                                                    if($inner_xml && $tag_name4=='offer'){
                                                        
                                                        $offer_row = simplexml_load_string($inner_xml);
                                                        
                                                        $delete_by_vendor = $this->yml['delete_by_vendor'];
                                                        
                                                        $delete_by_category_id = $this->yml['delete_by_category_id'];
                                                        
                                                        $set_products_by_category_id = $this->getArrayByDelimeterAndString($template_data['anyyml_set_products_by_category_id']);
                                                        
                                                        $skip = FALSE;
                                                        
                                                        if(isset($offer_row->vendor) && ($delete_by_vendor && in_array(trim((string)$offer_row->vendor), $delete_by_vendor)) ){

                                                            $skip = TRUE;

                                                        }
                                                        
                                                        $skip_by_set_cat = FALSE;
                                                        
                                                        $basic_category_path = '';
                                                        
                                                        if(isset($offer_row->categoryId)){
                                                            
                                                            if($set_products_by_category_id){
                                                                
                                                                $skip_by_set_cat = TRUE;
                                                                
                                                            }
                                                            
                                                            foreach ($offer_row->categoryId as $categoryId) {
                                                                
                                                                $category_path_by_category_id = array();

                                                                if(isset($this->yml['category_path_by_category_id'][trim((string)$categoryId)])){
                                                                        
                                                                    $category_path_by_category_id = $this->getArrayByDelimeterAndString($this->yml['category_path_by_category_id'][trim((string)$categoryId)],'____');
                                                                    
                                                                }
                                                                
                                                                if($delete_by_category_id && in_array(trim((string)$categoryId), $delete_by_category_id)){
                                                                    
                                                                    $skip = TRUE;
                                                                    
                                                                }
                                                                
                                                                if($skip_by_set_cat && $set_products_by_category_id && $category_path_by_category_id){
                                                                    
                                                                    $category_path_by_category_id_new = $category_path_by_category_id;
                                                                    
                                                                    foreach($set_products_by_category_id as $set_products_by_category_id_key => $set_products_by_category_id_value){
                                                                        
                                                                        unset($category_path_by_category_id[$set_products_by_category_id_key]);
                                                                        
                                                                    }
                                                                    
                                                                    if($category_path_by_category_id_new != $category_path_by_category_id){
                                                                        
                                                                        $skip_by_set_cat = FALSE;
                                                                        
                                                                        if(isset($this->yml['category_path'][trim((string)$categoryId)])){
                                                                            
                                                                            $basic_category_path = $this->yml['category_path'][trim((string)$categoryId)];
                                                                            
                                                                        }
                                                                        
                                                                    }
                                                                    
                                                                }
                                                                
                                                            }

                                                        }
                                                        
                                                        if(!$skip && !$skip_by_set_cat){
                                                            
                                                            $offer_row->basic_category_path = $basic_category_path;
                                                            
                                                            if($start<=$this_num_row && $this_num_row<($start+$limit)){
                                                                $this->getDsvRowFromOfferNode($offer_row,FALSE,$offers_file_name_prefix);
                                                            }else{
                                                                $this->getDsvRowFromOfferNode($offer_row,TRUE,$offers_file_name_prefix);
                                                            }

                                                            if( $this->yml['memory_usage'] <  memory_get_usage() ){

                                                                $this->yml['memory_usage'] = memory_get_usage();

                                                            }

                                                            if(count($this->yml['offers']) == $this->yml['max_nodes_to_write']){

                                                                if( $this->yml['memory_usage'] <  memory_get_usage() ){

                                                                    $this->yml['memory_usage'] = memory_get_usage();

                                                                }

                                                                $result['file_upload'] = $this->getCSVFormObj($template_data,$filename,$first_write);

                                                                $this->yml['offers'] = array();

                                                                $first_write = 0;

                                                            }

                                                            if( $this->yml['memory_usage'] <  memory_get_usage() ){

                                                                $this->yml['memory_usage'] = memory_get_usage();

                                                            }

                                                            $this_num_row++;

                                                            $this->yml['count_rows'] += 1;
                                                            
                                                        }
                                                        
                                                    }

                                                    $xml_cache = $outer_xml.$inner_xml.$outer_xml_close;
                                                    
                                                    $count_subnodes = 0;
                                                    
                                                    $xml_cache = '';
                                                    
                                                    $inner_xml = '';

                                                }

                                            }
                                            
                                        }
                                        
                                    }
                                    
                                }

                            }
                            
                            elseif(in_array($tag3, $tags[$tag][$tag2])){
                                        
                                if ($myXML->nodeType == XMLReader::ELEMENT){
                                            
                                    $xml_as_string = $myXML->readInnerXml();

                                    if($xml_as_string){

                                        $count_subnodes++;

                                        $inner_xml .= '<'.$tag_name3.'>'.$xml_as_string.'</'.$tag_name3.'>'.PHP_EOL;;

                                        if($count_subnodes===$count_nodes){

                                            $xml_cache = $outer_xml.$inner_xml.$outer_xml_close;

                                            //$as_file_xml_cache = $file_name_cach_prefix.count($result['as_files']).'.xml';

                                            //$result['as_files'][$as_file_xml_cache] = DIR_CACHE.$as_file_xml_cache;

                                            //$this->writeXMLCache($as_file_xml_cache,$xml_cache);

                                            $count_subnodes = 0;

                                            $xml_cache = '';

                                            $inner_xml = '';

                                        }

                                    }

                                }

                            }
                            
                        }
                        
                    }
                    elseif(in_array($tag2, $tags[$tag])){
                        
                        if ($myXML->nodeType == XMLReader::ELEMENT){
                                            
                            $xml_as_string = $myXML->readInnerXml();

                            if($xml_as_string){

                                $count_subnodes++;

                                $inner_xml .= '<'.$tag_name2.'>'.$xml_as_string.'</'.$tag_name2.'>'.PHP_EOL;

                                if($count_subnodes===$count_nodes){

                                    $xml_cache = $outer_xml.$inner_xml.$outer_xml_close;

                                    $as_file_xml_cache = $file_name_cach_prefix.count($result['as_files']).'.xml';

                                    $result['as_files'][$as_file_xml_cache] = DIR_CACHE.$as_file_xml_cache;

                                    //$this->writeXMLCache($as_file_xml_cache,$xml_cache);

                                    $count_subnodes = 0;

                                    $xml_cache = '';

                                    $inner_xml = '';

                                }

                            }

                        }

                    }

                }
                
            }elseif(in_array($tag, $tags)){

                if ($myXML->nodeType == XMLReader::ELEMENT){
                                            
                    $xml_as_string = $myXML->readInnerXml();

                    if($xml_as_string){

                        $count_subnodes++;

                        $inner_xml .= '<'.$tag_name.'>'.$xml_as_string.'</'.$tag_name.'>'.PHP_EOL;

                        if($count_subnodes===$count_nodes){

                            $xml_cache = $outer_xml.$inner_xml.$outer_xml_close;

                            $as_file_xml_cache = $file_name_cach_prefix.count($result['as_files']).'.xml';

                            $result['as_files'][$as_file_xml_cache] = DIR_CACHE.$as_file_xml_cache;

                            //$this->writeXMLCache($as_file_xml_cache,$xml_cache);

                            $count_subnodes = 0;

                            $xml_cache = '';

                            $inner_xml = '';

                        }

                    }

                }

            }
            
        }
        
        if($count_subnodes && $inner_xml!==''){
            
            $xml_cache = $outer_xml.$inner_xml.$outer_xml_close;

            //$as_file_xml_cache = $file_name_cach_prefix.count($result['as_files']).'.xml';

            //$result['as_files'][$as_file_xml_cache] = DIR_CACHE.$as_file_xml_cache;

            //$this->writeXMLCache($as_file_xml_cache,$xml_cache);

            $count_subnodes = 0;

        }
        
        $myXML->close();
        
        if($this->yml_cache['status'] && $this->yml['offers']){

            $offers_cache_file_name = $offers_file_name_prefix.count($this->yml['cache_offers']);

            $this->writeCache($offers_cache_file_name, $this->yml['offers'],'yml_cache/');

            $this->yml['offers'] = array();

            $this->yml['cache_offers'][$offers_cache_file_name] = $offers_cache_file_name;

        }
        
        $this->yml['time_finish'] = time();
        
        $this->yml['category_path'] = array();
        
        $result['file_upload'] = array();
        
        if($this->yml['cache_offers']){
            
            $this->unlinkFiles(array($this->yml_cache['offers_file_name_prefix_csv'].md5($filename)),DIR_DOWNLOAD);
            
            foreach ($this->yml['cache_offers'] as $cache_offers_file) {
                
                $offers_file_name_prefix_csv = count($result['file_upload']).'_'.$this->yml_cache['offers_file_name_prefix_csv'];
                
                $this->yml['offers'] = $this->getCache($cache_offers_file,TRUE,'yml_cache/');
                
                $result['file_upload'][] = $this->getCSVFormObj($template_data,$filename,$first_write,$offers_file_name_prefix_csv);
                
            }
            
        }else{
            
            $result['file_upload'] = $this->getCSVFormObj($template_data,$filename,$first_write);
            
        }
        
        $this->yml['max_exe_time'] = $this->yml['time_finish'] - $this->yml['time_start'];
        
        $result['count_rows'] = $this->yml['count_rows'];
        
        $result['max_exe_time'] = $this->yml['max_exe_time'];
        
        $result['memory_usage'] = $this->yml['memory_usage'];
        
        return $result;
        
    }
    
    private function getCSVFormObj($odmpro_tamplate_data,$filename,$first_write=1,$prefix=''){
        
        $first_row = array();
        
        $new_file_name = $prefix.md5($filename);
        
        $csv_delimiter = $odmpro_tamplate_data['csv_delimiter'];

        $csv_enclosure = $odmpro_tamplate_data['csv_enclosure'];
        
        $csv_escape = $odmpro_tamplate_data['csv_escape'];

        $encoding = $odmpro_tamplate_data['encoding'];
        
        $offers_writed = 0;
        
        $csv_rows = array();
                    
        foreach ($this->yml['offers'] as $offer) {

            $csv_row = array();

            if($first_write && !$first_row){

                $first_row = $this->yml['first_row'];

                $csv_rows[] = $first_row;

            }

            foreach ($this->yml['first_row'] as $csv_column_name) {

                if(isset($offer[$csv_column_name])){

                    if(!is_string($offer[$csv_column_name])){
                        if(!$offer[$csv_column_name]){
                            $offer[$csv_column_name] = '';
                        }else{
                            $offer[$csv_column_name] = json_encode($offer[$csv_column_name]);
                        }
                    }

                    $csv_row[] = $offer[$csv_column_name];

                }else{

                    $csv_row[] = '';

                }

            }

            $csv_rows[] = $csv_row;

            $offers_writed++;

        }

        $file_name = $this->writeCsv($csv_rows,$first_write,$csv_delimiter,$csv_enclosure,$csv_escape,$encoding,$new_file_name,array());
        
        return $file_name;
        
    }
    
    public function writeCsv($data,$first_write,$csv_delimiter,$csv_enclosure,$csv_escape,$encoding,$file_and_path,$log_data=array()) {
        
        $file_name_and_path = $file_and_path.'.csv';
        
        $file_name_and_path_array = explode('/', trim($file_name_and_path));
            
        $path_array = array();

        for ($i=0;$i<(count($file_name_and_path_array)-1);$i++) {

            $path_array[] = $file_name_and_path_array[$i];

        }

        $file_name = end($file_name_and_path_array);
        
        $write_path = DIR_DOWNLOAD;
        
        if($path_array){
            
            foreach ($path_array as $dir) {
                
                $write_path .= $dir.'/';
                
                if(!file_exists($write_path)){

                    mkdir($write_path,0777);

                }
                
            }
            
        }
        
        if(!file_exists($write_path)){
            
            return;
            
        }
        
        if(!file_exists($write_path.$file_name)){
            
            $handle = fopen($write_path.$file_name, "a+"); 
            
            fclose($handle);
            
        }
        
        //Открываем
        if($first_write){
            
            $handle = fopen($write_path.$file_name, "w+");
        }else{
            $handle = fopen($write_path.$file_name, "a+");
        }
        
        
        if(!$handle){
            
            return;
        }
        
        $csv_delimiter = html_entity_decode(trim($csv_delimiter));
        
        $csv_enclosure = html_entity_decode(trim($csv_enclosure));
        
        foreach ($data as $num_row => $csv_row) {
            
            $value = '';
            
            $num_columns = count($csv_row);
            
            $this_num_column = 1;
            
            foreach ($csv_row as $row) {
                
                $row = (string)html_entity_decode($row);
                
                if($this_num_column<$num_columns){
                    $value .= $row.$csv_delimiter;
                }else{
                    $value .= $row;
                }
                $this_num_column++;
                
            }
            
            unset($data[$num_row]);
        
            fputcsv($handle, explode($csv_delimiter, $value), $csv_delimiter,$csv_enclosure);
            
        }
        
        fclose($handle);
        
        return $file_name;
    }


    private function getDsvRowFromOfferNode($offer_row,$only_first_row=FALSE,$offers_file_name_prefix){
        
            $attributes_offer = (array)$offer_row->attributes();
            
            $id = '';
            
            $group_id = '';

            if(isset($attributes_offer['@attributes']['id'])){

                $id = $attributes_offer['@attributes']['id'];

            }

            if(!$id && isset($offer_row->{'Ид'})){

                $id = (string)$offer_row->{'Ид'};

            }
            
            if(!$id && isset($offer_row->{'id'})){

                $id = (string)$offer_row->{'id'};

            }
            
            if(!$id && isset($offer_row->{'code'})){

                $id = (string)$offer_row->{'code'};

            }
            
            if(!$group_id && isset($attributes_offer['@attributes']['group_id'])){

                $group_id = (string)$attributes_offer['@attributes']['group_id'];

            }
            
            $quantity_on_option = 0;
            
            if(isset($attributes_offer['@attributes']) && $attributes_offer['@attributes']){

                foreach ($attributes_offer['@attributes'] as $attributes_offer_name => $attributes_offer_value) {

                    if($attributes_offer_name=='available' && (string)$attributes_offer_value=='true'){

                        $quantity_on_option = 100;
                    
                    }
                }
                
            }

            $country_name = '';

            $params = array();

            $pictures = array();

            $param_whis_balance = array('остаток','количество','наличие');
            
            $write_param_as_columns = array('Количество','Наличие',"Артикул");
            
            $offer_additional_colums = array();
            
            if(isset($offer_row->{'Атрибуты'})){

                $offer_additional_colums['Атрибуты'] = str_replace(array(': ', ' | '), array(':', '|'),(string)$offer_row->{'Атрибуты'});

            }
            
            if(isset($offer_row->{'Категория'})){

                $offer_additional_colums['Категория'] = str_replace(array(' / '), array('/'),(string)$offer_row->{'Категория'});

            }
            
            

            $size = '';

            $available_status = '';

            $available_balance = '';

            $description = '';

            $sale_price = '';

            $wholesale_price = '';

            $name = '';

            $price = '';
            
            $supplier_trademark_id = '';
            
            if(isset($offer_row->vendor)){

                $supplier_trademark_id = trim((string)$offer_row->vendor);

            }
            
            if(isset($offer_row->properties)){

                if(!isset($this->yml['properties/param'])){
                    
                    $xml = new XMLReader();
                    
                    $xml->open($this->file_name_on_xml);
                    
                    while ($xml->read()) {
            
                        $tag = $xml->name;
                        
                        if($tag=='properties'){
                            
                            while ($xml->read()) {
                                
                                $tag = $xml->name;
                                
                                if ($xml->nodeType == XMLReader::ELEMENT){
                                    
                                    if($tag == 'param'){
                                        
                                        if($xml->hasAttributes){
                                            
                                            $this_atr_id = 0;
                                            
                                            while($xml->moveToNextAttribute()){
                            
                                                if($xml->name=='id'){

                                                    $this_atr_id = $xml->value;

                                                }elseif($xml->name=='name'){

                                                    $this->yml['properties/param'][$this_atr_id] = $xml->value;

                                                }

                                            }
                                            
                                        }
                                        
                                    }
                                    
                                }
                                
                            }
                            
                            break;
                            
                        }
                        
                    }
                    
                }

            }
            
            if(isset($offer_row->Vendor) && !$supplier_trademark_id){

                $supplier_trademark_id = trim((string)$offer_row->Vendor);

            }

            $supplier_country_id = '';

            if(isset($offer_row->country_of_origin)){

                $supplier_country_id = trim((string)$offer_row->country_of_origin);

            }

            if(isset($offer_row->LastCountry)){

                $supplier_country_id = trim((string)$offer_row->LastCountry);

            }
            
            if(isset($offer_row->picture)){

                foreach ($offer_row->picture as $picture){

                    $pictures[(string)$picture] = (string)$picture;

                }

                for($p=0;$p<5;$p++){
                    if(isset($offer_row->{'picture'.$p}) && $offer_row->{'picture'.$p}){
                        $picture = (string)$offer_row->{'picture'.$p};
                        if($picture){
                            $pictures[(string)$picture] = (string)$picture;
                        }
                    }
                }

            }
            
            if(isset($offer_row->pictures)){
                
                foreach ($offer_row->pictures as $pictures_xml) {
                    
                    foreach ($pictures_xml->picture as $picture){
                        
                        $picture_as_attr = (array)$picture->attributes();
                        
                        if(isset($picture_as_attr['@attributes']['url'])){
                            
                            $pictures[(string)$picture_as_attr['@attributes']['url']] = (string)$picture_as_attr['@attributes']['url'];
                            
                        }else{
                            
                            $pictures[(string)$picture] = (string)$picture;
                            
                        }

                    }
                    
                }

            }
            
            if(isset($offer_row->image)){

                foreach ($offer_row->image as $picture){
                    
                    $picture = (string)$picture;
                    
                    if($picture){
                        
                        $pictures[ $picture ] = $picture;
                        
                    }

                }
                
            }
            
            if(isset($offer_row->Image)){

                foreach ($offer_row->Image as $picture){
                    
                    $picture = (array)$picture->attributes();
                    
                    if(isset($picture['@attributes']['url']) && $picture['@attributes']['url']){
                        
                        $pictures[ $picture['@attributes']['url'] ] = $picture['@attributes']['url'];
                        
                    }

                }
                
            }

            if(isset($offer_row->param) && !$supplier_country_id){

                foreach ($offer_row->param as $yml_param) {

                    $attributes = (array)$yml_param->attributes();

                    if(isset($attributes['@attributes']['name']) && ($attributes['@attributes']['name']=='Страна' || $attributes['@attributes']['name']=='Страна производства' || $attributes['@attributes']['name']=='Страна-производитель')){

                        $supplier_country_id = trim((string)$yml_param);

                    }
                    
                    if(isset($attributes['@attributes']['name']) && in_array($attributes['@attributes']['name'], $write_param_as_columns)){

                        $offer_additional_colums[$attributes['@attributes']['name']] = trim((string)$yml_param);

                    }
                    
                    //$write_param_as_columns

                }

            }

            if(!$supplier_country_id && isset($offer_row->country)){

                $supplier_country_id = trim((string)$offer_row->country);

            }
            
            if(isset($offer_row->outlets)){
                
                $sum_instock_outlet = 0;
                
                foreach($offer_row->outlets as $outlet){
                    
                    foreach($outlet as $outlet_this){
                        
                        $attributes_outlet = (array)$outlet_this->attributes();
                        
                        $sum_instock_outlet += (int)$attributes_outlet['@attributes']['instock'];
                        
                    }
                    
                }
                
            }
            
            $params_as_column = array();
            
            $option_columns = array();
            
            $select_params_as_column = array();
            
            $delete_params = $this->yml['delete_params'];
            
            $sizes = array();

            if(isset($offer_row->param)){

                foreach ($offer_row->param as $yml_param) {

                    $attributes = (array)$yml_param->attributes();

                    if(isset($attributes['@attributes']['name']) && $attributes['@attributes']['name']=="Размер"){

                        $size = (string)$yml_param;
                        
                        $size_parts = explode('х',$size);
                        
                        foreach($size_parts as $num_size => $size_part){
                            
                            $sizes['size_'.$num_size] = $size_part;
                            
                        }

                    }

                    if(isset($attributes['@attributes']['name']) && in_array(mb_strtolower($attributes['@attributes']['name']),$param_whis_balance)){

                        $available_balance = (int)$available_balance;

                    }

                    $special_params = array('props','sert','images');
                    
                    foreach ($attributes['@attributes'] as $attribute_name => $attribute_value) {

                        if($attribute_name=='name' && !in_array((string)$attribute_value, $special_params)){

                            $param_value = htmlspecialchars(str_replace(array('<![CDATA[ ',' ]]>'),array('',''),   strip_tags(html_entity_decode(trim((string)$yml_param)))),ENT_QUOTES);

                            $param_value_parts = explode(PHP_EOL,$param_value);

                            $param_value = implode(' ',$param_value_parts);

                            $attribute_value = trim((string)$attribute_value);

                            $unit = '';

                            if(isset($attributes['@attributes']['unit'])){

                                $unit .= ', '.$attributes['@attributes']['unit'];

                            }

                            $param_title = (string)$attribute_value.$unit;

                            $param_group_title = '';

                            $param_image = '';

                            if($attribute_value!==''){

                                $param_value = str_replace(array("'"),array("&#039;"), $param_value);

                                $param_title = str_replace(array("'"),array("&#039;"), $param_title); 

                                if($this->yml['params_as_column']){

                                    $params_as_column[$attribute_value] = (string)$param_value;

                                }
                                
                                if($this->yml['select_params_as_column'] && in_array($attribute_value, $this->yml['select_params_as_column'])){
                                    
                                    $select_params_as_column[$attribute_value] = (string)$param_value;
                                    
                                }

                                $params_tag = 'param';

                                $params_tag_attribute_name = $attribute_name;

                                $params_tag_attribute_value = (string)$attribute_value.$unit;

                                $supplier_params_id_this = md5($params_tag.'_'.$params_tag_attribute_name.'_'.$params_tag_attribute_value);

                                $param_group_title = $this->yml['group_attribute_name'];
                                
                                if($this->yml['option_columns'] && in_array($param_title, $this->yml['option_columns'])){
                                    
                                    $option_columns[] = $param_title;
                                    
                                }elseif(!$this->yml['option_columns']){
                                    
                                    $option_columns[] = $param_title;
                                    
                                }

                                $params_this = array('title'=>$param_title,'quantity'=>$quantity_on_option,'group_title'=>$param_group_title,'value'=>$param_value,'image'=>$param_image,'feature_code'=>'','product_feature_code'=>'');
                                
                                
                                ////1 - без кодов, 2 - код опции, 3 - код опции и код товара, 4 - код товара, без кода опции
                                if($this->yml['features_detail']==2 || $this->yml['features_detail']==3){

                                    $params_this['feature_code'] = md5($param_group_title.$param_title.$param_value.$param_image);

                                }

                                if($this->yml['features_detail']==4 || $this->yml['features_detail']==3){

                                    $params_this['product_feature_code'] = $id;

                                }
                                
                                $params[] = $params_this;
                                
                                //array('title'=>$param_title,'group_title'=>$param_group_title,'value'=>$param_value,'image'=>$param_image,'product_feature_code'=>$id,'feature_code'=>md5($param_group_title.$param_title.$param_value.$param_image));


                                //$params[] = array('title'=>$param_title,'group_title'=>$param_group_title,'value'=>$param_value,'image'=>$param_image,'feature_code'=>md5($param_group_title.$param_title.$param_value.$param_image),'product_feature_code'=>$id);

                            }

                        }elseif($attribute_name=='name' && in_array((string)$attribute_value, $special_params)){

                            if((string)$attribute_value=='images' || (string)$attribute_value=='sert'){
                                
                                if(isset($yml_param->variant)){

                                    foreach($yml_param->variant as $variant){

                                        $pictures[(string)$variant] = (string)$variant;

                                    }

                                }
                                
                            }elseif((string)$attribute_value=='props'){
                                
                                if(isset($yml_param->variant)){

                                    foreach($yml_param->variant as $variant){

                                        $variant_parts = explode('=',(string)$variant);

                                        if(isset($variant_parts[1])){

                                            $params_this = array('title'=>$variant_parts[0],'quantity'=>$quantity_on_option,'group_title'=>$this->yml['group_attribute_name'],'value'=>$variant_parts[1],'image'=>'','feature_code'=>'','product_feature_code'=>'');

                                            $params[] = $params_this;

                                        }

                                    }

                                }
                                
                            }

                        }

                    }

                    if(isset($attributes_offer['@attributes']['available'])){

                        $offer_row->available_status = $attributes_offer['@attributes']['available'];

                    }

                }

                unset($offer_row->param);

            }
            elseif(isset($offer_row->properties)){
                
                foreach ($offer_row->properties as $properties) {
                    
                    foreach ($properties->param as $yml_param) {

                        $attributes = (array)$yml_param->attributes();
                        
                        if( isset($attributes['@attributes']['id']) && isset( $this->yml['properties/param'][$attributes['@attributes']['id']] )){
                            
                            $attributes['@attributes']['name'] = $this->yml['properties/param'][$attributes['@attributes']['id']];
                            
                        }

                        if(isset($attributes['@attributes']['name']) && $attributes['@attributes']['name']=="Размер"){

                            $size = (string)$yml_param;

                            $size_parts = explode('х',$size);

                            foreach($size_parts as $num_size => $size_part){

                                $sizes['size_'.$num_size] = $size_part;

                            }

                        }

                        if(isset($attributes['@attributes']['name']) && in_array(mb_strtolower($attributes['@attributes']['name']),$param_whis_balance)){

                            $available_balance = (int)$available_balance;

                        }

                        $special_params = array('props','sert','images');

                        foreach ($attributes['@attributes'] as $attribute_name => $attribute_value) {

                            if($attribute_name=='name' && !in_array((string)$attribute_value, $special_params)){

                                $param_value = htmlspecialchars(str_replace(array('<![CDATA[ ',' ]]>'),array('',''),   strip_tags(html_entity_decode(trim((string)$yml_param)))),ENT_QUOTES);

                                $param_value_parts = explode(PHP_EOL,$param_value);

                                $param_value = implode(' ',$param_value_parts);

                                $attribute_value = trim((string)$attribute_value);

                                $unit = '';

                                if(isset($attributes['@attributes']['unit'])){

                                    $unit .= ', '.$attributes['@attributes']['unit'];

                                }

                                $param_title = (string)$attribute_value.$unit;

                                $param_group_title = '';

                                $param_image = '';

                                if($attribute_value!==''){

                                    $param_value = str_replace(array("'"),array("&#039;"), $param_value);

                                    $param_title = str_replace(array("'"),array("&#039;"), $param_title); 

                                    if($this->yml['params_as_column']){

                                        $params_as_column[$attribute_value] = (string)$param_value;

                                    }

                                    if($this->yml['select_params_as_column'] && in_array($attribute_value, $this->yml['select_params_as_column'])){

                                        $select_params_as_column[$attribute_value] = (string)$param_value;

                                    }

                                    $params_tag = 'param';

                                    $params_tag_attribute_name = $attribute_name;

                                    $params_tag_attribute_value = (string)$attribute_value.$unit;

                                    $supplier_params_id_this = md5($params_tag.'_'.$params_tag_attribute_name.'_'.$params_tag_attribute_value);

                                    $param_group_title = $this->yml['group_attribute_name'];

                                    if($this->yml['option_columns'] && in_array($param_title, $this->yml['option_columns'])){

                                        $option_columns[] = $param_title;

                                    }elseif(!$this->yml['option_columns']){

                                        $option_columns[] = $param_title;

                                    }

                                    $params_this = array('title'=>$param_title,'quantity'=>$quantity_on_option,'group_title'=>$param_group_title,'value'=>$param_value,'image'=>$param_image,'feature_code'=>'','product_feature_code'=>'');


                                    ////1 - без кодов, 2 - код опции, 3 - код опции и код товара, 4 - код товара, без кода опции
                                    if($this->yml['features_detail']==2 || $this->yml['features_detail']==3){

                                        $params_this['feature_code'] = md5($param_group_title.$param_title.$param_value.$param_image);

                                    }

                                    if($this->yml['features_detail']==4 || $this->yml['features_detail']==3){

                                        $params_this['product_feature_code'] = $id;

                                    }

                                    $params[] = $params_this;

                                    //array('title'=>$param_title,'group_title'=>$param_group_title,'value'=>$param_value,'image'=>$param_image,'product_feature_code'=>$id,'feature_code'=>md5($param_group_title.$param_title.$param_value.$param_image));


                                    //$params[] = array('title'=>$param_title,'group_title'=>$param_group_title,'value'=>$param_value,'image'=>$param_image,'feature_code'=>md5($param_group_title.$param_title.$param_value.$param_image),'product_feature_code'=>$id);

                                }

                            }elseif($attribute_name=='name' && in_array((string)$attribute_value, $special_params)){

                                if((string)$attribute_value=='images' || (string)$attribute_value=='sert'){

                                    if(isset($yml_param->variant)){

                                        foreach($yml_param->variant as $variant){

                                            $pictures[(string)$variant] = (string)$variant;

                                        }

                                    }

                                }elseif((string)$attribute_value=='props'){

                                    if(isset($yml_param->variant)){

                                        foreach($yml_param->variant as $variant){

                                            $variant_parts = explode('=',(string)$variant);

                                            if(isset($variant_parts[1])){

                                                $params_this = array('title'=>$variant_parts[0],'quantity'=>$quantity_on_option,'group_title'=>$this->yml['group_attribute_name'],'value'=>$variant_parts[1],'image'=>'','feature_code'=>'','product_feature_code'=>'');

                                                $params[] = $params_this;

                                            }

                                        }

                                    }

                                }

                            }

                        }

                        if(isset($attributes_offer['@attributes']['available'])){

                            $offer_row->available_status = $attributes_offer['@attributes']['available'];

                        }

                    }
                    
                }
                
                unset($offer_row->properties);
                
            }
            
            $variant_names = array();
            
            if(isset($offer_row->attributes)){
                
                foreach ($offer_row->attributes as $attributes_tag) {
                    
                    foreach ($attributes_tag->attribute as $yml_param) {
                        
                        $attributes = (array)$yml_param->attributes();
                        
                        $variant_names[$attributes['@attributes']['id']] = (string)$yml_param->name;
                        
                    }
                    
                }
                
            }
            
            if(isset($offer_row->variants)){
                
                foreach ($offer_row->variants as $variants) {
                    
                    foreach ($variants->variant as $yml_param) {

                        $attributes = (array)$yml_param->attributes();
                        
                        foreach ($yml_param->attribute_values as $attribute_values) {
                            
                            $attribute_values_attributes = (array)$attribute_values->attribute_value->attributes();
                            
                            $option_name = $variant_names[$attribute_values_attributes['@attributes']['id']];
                            
                            $option_value_name = (string)$attribute_values->attribute_value->value;
                            
                            $option_value_image = (string)$attribute_values->attribute_value->value_image;
                            
                            $option_value_price = (string)$yml_param->recommended_price;
                            
                            $option_value_quantity = 0;
                            
                            if((string)$yml_param->availability_status=='В наличии'){
                                
                                $option_value_quantity = 100;
                                
                            }
                            
                            $params_this = array('title'=>$option_name,'quantity'=>$option_value_quantity,'price'=>$option_value_price,'group_title'=>$option_name,'value'=>$option_value_name,'picture'=>$option_value_image,'feature_code'=>'','product_feature_code'=>'');
                            
                            $params[] = $params_this;
                            
                            if($this->yml['option_columns'] && in_array($option_name, $this->yml['option_columns'])){

                                $option_columns[] = $option_name;
                                
                            }
                            
                        }

                    }
                    
                }
                
                unset($offer_row->variants);
                
            }
            
            if(isset($offer_row->country_of_origin)){

                    $params_this = array('title'=>"Страна производитель",'group_title'=>'','value'=>(string)$offer_row->country_of_origin,'image'=>'','feature_code'=>'','product_feature_code'=>'');

                    $params[] = $params_this;

            }
            
            $column_as_params_this = array(
                    'author'=>"Автор",'publisher'=>"Издатель",'series'=>"Серия",'year'=>"Год",'ISBN'=>"ISBN",'cover'=>"Обложка",'pages'=>"Количество страниц",'size'=>"Размер"
            );

            foreach($column_as_params_this as $column_as_params_t_name => $column_as_params_o_name){

                    if(isset($offer_row->{$column_as_params_t_name})){

                            $column_as_params_t_value = trim((string)$offer_row->{$column_as_params_t_name});

                            if($column_as_params_t_value){

                                     $params_this = array('title'=>$column_as_params_o_name,'group_title'=>'','value'=>$column_as_params_t_value,'image'=>'','feature_code'=>'','product_feature_code'=>'');

                                    $params[] = $params_this;

                            }

                    }

            }

            if(isset($offer_row->count)){

                $available_balance = (int)$offer_row->count;

            }

            if($available_balance ==='' && isset($offer_row->quantity_in_stock)){

                $available_balance = (int)$offer_row->quantity_in_stock;

            }

            if(isset($offer_row->name)){

                $name = (string)$offer_row->name;

            }
            
            if(!$name && isset($offer_row->title)){

                $name = (string)$offer_row->title;

            }

            if(!$name && isset($offer_row->model)){

                $name = (string)$offer_row->model;

            }

            if(!$name && isset($offer_row->{'Наименование'})){

                $name = (string)$offer_row->{'Наименование'};

            }

            if(isset($offer_row->oldprice)){

                $sale_price = (float)$offer_row->price;

                $price = (float)$offer_row->oldprice;

            }elseif(isset($offer_row->price)){

                $price = (float)$offer_row->price;

            }

            if(isset($offer_row->base_price)){

                $price = (float)$offer_row->base_price;

            }

            if(!$country_name && $supplier_country_id){

                $country_name = $supplier_country_id;

            }

            if(isset($offer_row->description)){
                
                if(strstr((string)$offer_row->description, '[CDATA[')){
                    
                    $description = trim(html_entity_decode(str_replace(array('&lt;![CDATA[',']]&gt;',']]>', ' ]]&gt;'), array('','','',''), (string)$offer_row->description)));
                    
                }else{
                    
                    $description = nl2br((string)$offer_row->description);
                    
                }
                
                $description = str_replace('Описание=', '', $description);

            }

            
            
            

            $this->yml['offers'][$id] = array();
            
            if(isset($attributes_offer['@attributes']) && $attributes_offer['@attributes']){

                foreach ($attributes_offer['@attributes'] as $attributes_offer_name => $attributes_offer_value) {

                    if($attributes_offer_name=='available'){

                        $this->yml['offers'][$id]['available_status'] = (string)$attributes_offer_value;

                        $this->yml['offers'][$id]['available_balance'] = $available_balance;

                    }else{

                        $this->yml['offers'][$id][$attributes_offer_name] = (string)$attributes_offer_value;

                    }

                }

            }
            
            $categories = array();
            
            if(isset($offer_row->categoryId)){

                foreach ($offer_row->categoryId as $categoryId) {

                    if(isset($this->yml['category_path'][trim((string)$categoryId)])){
                        
                        $categories[] = $this->yml['category_path'][trim((string)$categoryId)];
                        
                    }

                }

            }elseif(isset($offer_row->categories)){

                foreach ($offer_row->categories as $category_xml) {
                    
                    if(isset($category_xml->category)){
                    
                        foreach ($category_xml->category as $category_xml_2) {
                            
                            $cat_attributes = (array)$category_xml_2->attributes();

                            if(isset($cat_attributes['@attributes']['id'])){

                                if(isset($this->yml['category_path'][trim((string)$cat_attributes['@attributes']['id'])])){

                                    $categories[] = $this->yml['category_path'][trim((string)$cat_attributes['@attributes']['id'])];

                                }

                            }
                            
                        }
                        
                    }
                    
                }
                
                unset($offer_row->categories);

            }
            
            if(isset($offer_row->models)){
                
                foreach ($offer_row->models as $models_xml) {
                    
                    if(isset($models_xml->model)){
                        
                        foreach ($models_xml->model as $models_xml_2) {
                            
                            $mod_attributes = (array)$models_xml_2->attributes();
                            
                            if(isset($mod_attributes['@attributes'])){
                                
                                foreach($mod_attributes['@attributes'] as $name_mod => $val_mod){
                                    
                                    $offer_additional_colums['mod_'.$name_mod] = $val_mod;
                                    
                                }
                                
                            }
                            
                        }
                        
                    }
                    
                }
                
                unset($offer_row->models);
                
            }
            
            //categories
            
            if($select_params_as_column){
                
                $params_as_column = array();
                
            }

            if($sizes){
                
                $this->yml['offers'][$id] = array_merge($this->yml['offers'][$id],$sizes);
                
            }
            
            $pictures_tmp = $pictures;
            
            $pictures = array();
            
            foreach($pictures_tmp as $pictures_this){
                
                if(strstr($pictures_this, '_norm_')){

                    $pictures_this = str_replace('_norm_', '_max_', $pictures_this);

                }
                
                $pictures[$pictures_this] = $pictures_this;
                
            }
            
            $this->yml['offers'][$id] = array_merge($this->yml['offers'][$id],(array)$offer_row, $params_as_column, $select_params_as_column, $offer_additional_colums);
            
            $this->yml['offers'][$id]['params'] = $params;
            
            $this->yml['offers'][$id]['id'] = $id;

            $this->yml['offers'][$id]['pictures'] = implode(',', $pictures);

            $this->yml['offers'][$id]['categories'] = implode('---', $categories);

            $this->yml['offers'][$id]['description'] = $description;

            $this->yml['offers'][$id]['sale_price'] = $sale_price;

            $this->yml['offers'][$id]['wholesale_price'] = $wholesale_price;

            $this->yml['offers'][$id]['price'] = $price;

            $this->yml['offers'][$id]['size'] = $size;

            $this->yml['offers'][$id]['name'] = $name;

            $this->yml['offers'][$id]['vendor'] = $supplier_trademark_id;
            
            if(isset($sum_instock_outlet)){
                
                $this->yml['offers'][$id]['sum_instock_outlet'] = $sum_instock_outlet;
                
            }

            $this->yml['offers'][$id]['country_name'] = $country_name;

            if(!isset($this->yml['offers'][$id]['available_balance'])){
                $this->yml['offers'][$id]['available_balance'] = $available_balance;
            }

            if(!isset($this->yml['offers'][$id]['available_status'])){
                $this->yml['offers'][$id]['available_status'] = $available_status;
            }

            if(!isset($this->yml['offers'][$id]['url'])){
                $this->yml['offers'][$id]['url'] = '#';
            }
            
            $options_microdata_1 = $this->getVariationsArrayWhisString($this->yml['offers'][$id],$option_columns,$price,$available_balance,  current($pictures));
            
            if($options_microdata_1){

                if(isset($this->yml['option_value_by_column']) && $this->yml['option_value_by_column']==='name_column'){

                    $variations_array_whis_string_temp = $options_microdata_1;

                    foreach($variations_array_whis_string_temp as $variation_array_option_name => $variation_array){

                        $variations_array_whis_string = array();

                        foreach($variation_array as $variation_array_name => $variation_array_array){

                            $variations_array_whis_string[] = implode('|',$variation_array_array);

                        }

                        $this->yml['offers'][$id]['options_'.$variation_array_option_name] = implode('---',$variations_array_whis_string);

                    }

                }
                elseif(isset($this->yml['option_value_by_column']) && $this->yml['option_value_by_column']==='value_column'){

                    $variations_array_whis_string_temp = $options_microdata_1;

                    $variations_array_whis_string = array();

                    $v = 0;

                    foreach($variations_array_whis_string_temp as $variation_array_option_name => $variation_array){

                        foreach($variation_array as $variation_array_name => $variation_array_array){

                            $this->yml['offers'][$id]['options_'.$v] = implode('|',$variation_array_array);

                            $v++;

                        }

                    }

                }
                else{

                    $variations_array_whis_string_temp = $options_microdata_1;

                    $variations_array_whis_string = array();

                    foreach($variations_array_whis_string_temp as $variation_array){

                        foreach($variation_array as $variation_array_name => $variation_array_array){

                            $variations_array_whis_string[] = implode('|',$variation_array_array);

                        }

                    }

                    $this->yml['offers'][$id]['options'] = implode('---',$variations_array_whis_string);

                }

            }
            
            $this->yml['offers'][$id]['attributes'] = $this->getAttributes($this->yml['offers'][$id],$delete_params);
            
            $unsets = array('@attributes','picture','params','delivery-options');
            
            if($this->yml['delete_columns']){
                
                $unsets = array_merge($unsets,$this->yml['delete_columns']);
                
            }
            
            foreach ($unsets as $name) {
                
                unset($this->yml['offers'][$id][$name]);
                
            }
            
            foreach ($this->yml['offers'][$id] as $first_row_name => $tmp) {

                $this->yml['first_row'][$first_row_name] = $first_row_name;

            }
            
            if($only_first_row){
                
                unset($this->yml['offers'][$id]);
                
            }
            elseif($this->yml_cache['status'] && $this->yml_cache['count_nodes'] && $this->yml_cache['count_nodes']==count($this->yml['offers'])){
                
                $offers_cache_file_name = $offers_file_name_prefix.count($this->yml['cache_offers']);
                
                $this->writeCache($offers_cache_file_name, $this->yml['offers'],'yml_cache/');
                
                $this->yml['offers'] = array();
                
                $this->yml['cache_offers'][$offers_cache_file_name] = $offers_cache_file_name;
                
                
            }
        
    }
    
    public function getAttributes($supplier_product,$delete_params=array()) {
        
        $features = array();
        
        $attribute_columns = $this->yml['attribute_columns'];
        
        if(isset($supplier_product['params']) && $supplier_product['params']){
            
            foreach($supplier_product['params'] as $feature){

                $feature_name = $feature['title'];

                $feature_value = $feature['value'];

                $feature_group_title = $feature['group_title'];
                
                if($feature_value!=='' && $feature_name!=='' && (!$attribute_columns || in_array($feature_name, $attribute_columns)) ){
                    
                    if($delete_params && !in_array($feature_name, $delete_params) || !$delete_params){

                        if($this->yml['attribute_whis_group']){
                        
                        if($feature_group_title===''){
                            
                                $feature_group_title = $this->yml['group_attribute_name'];

                            }

                            $features[] = $feature_group_title.'---'.$feature_name.'---'.$feature_value;

                        }else{

                            $features[] = $feature_name.'---'.$feature_value;

                        }

                    }
                    
                }

            }

        }

        $features_as_string = implode('|', $features);
        
        return $features_as_string;
        
    }
    
    public function getVariationsArrayWhisString($supplier_product,$option_columns,$recommended_retail_price,$availability_balance,$image){
        
        $variations_array_whis_string = array();

        $column_this = '';

        $type_this = $this->yml['option_type'];

        $subtract_this = 0;

        $required_this = 0;

        $option_name_this = '';
        
        $image_this = '';

        if($type_this=='image'){
            
            $image_this = $image;
            
        }
        
        $price_this = $recommended_retail_price;

        $quantity_this = $availability_balance;

        $product_feature_code_this = $supplier_product['id'];

        $feature_code_this = '';

        foreach ($supplier_product['params'] as $option_value_this_row) {

            $option_name_this = $option_value_this_row['title'];

            $column_this = $option_name_this;

            if(isset($option_value_this_row['picture']) && $option_value_this_row['picture'] && $type_this=='image'){

                $image_this = $option_value_this_row['picture'];

            }
            
            if(in_array($column_this, $this->yml['option_whis_image'])){
                
                if(isset($option_value_this_row['picture']) && $option_value_this_row['picture']){

                    $image_this = $option_value_this_row['picture'];

                }else{
                    
                    if(is_array($supplier_product['pictures'])){
                        
                        $image_this = key($supplier_product['pictures']);
                        
                    }elseif(is_string($supplier_product['pictures'])){
                        
                        $image_this = $supplier_product['pictures'];
                        
                    }
                    
                }
                
            }

            if(isset($option_value_this_row['product_feature_code']) && $option_value_this_row['product_feature_code']!==''){

                $product_feature_code_this = $option_value_this_row['product_feature_code'];

            }

            if(isset($option_value_this_row['feature_code']) && $option_value_this_row['feature_code']!==''){

                $feature_code_this = $option_value_this_row['feature_code'];

            }

            if(isset($option_value_this_row['quantity']) && $option_value_this_row['quantity']!==''){

                $quantity_this = $option_value_this_row['quantity'];

            }

            if(isset($option_value_this_row['price']) && $option_value_this_row['price']!==''){

                $price_this = $option_value_this_row['price'];

            }
			
			if($image_this){
				
				$image_parts = explode(',',$image_this);
				
				$image = trim($image_parts[0]);
				
				$image_this = $image;
				
			}

            if($option_value_this_row['title'] && in_array($column_this, $option_columns)){

                $option_value_this = (string)trim($option_value_this_row['value']);

                $option_value_this_parts = explode($this->yml['delimeter_option_value'],$option_value_this);

                foreach($option_value_this_parts as $option_value_this){

                    $variations_array = array(
                        $type_this,$option_name_this,$option_value_this,$required_this,$quantity_this,$subtract_this,'+',$price_this,'+',0,'+',0,$image_this
                    );

                    if($this->yml['features_detail']==2 || $this->yml['features_detail']==3){

                        $variations_array[] = $feature_code_this;

                    }

                    if($this->yml['features_detail']==4 || $this->yml['features_detail']==3){

                        $variations_array[] = $product_feature_code_this;

                    }

                    if(!isset($variations_array_whis_string[$option_name_this])){

                        $variations_array_whis_string[$option_name_this]['variations__0'] = $variations_array;

                    }else{

                        $variations_array_whis_string[$option_name_this]['variations__'.count($variations_array_whis_string[$option_name_this])] = $variations_array;

                    }

                }

            }

        }
        
        return $variations_array_whis_string;
        
    }
    
}