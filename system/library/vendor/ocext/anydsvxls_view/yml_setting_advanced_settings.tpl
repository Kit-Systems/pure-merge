<div id="tab_anyyml_import" class="tab-pane" >
    <label style="color: red">
        Включить импорт из формата?
    </label>
    <select  onchange="$('#tab_anyxls_import select').val(0)" name="odmpro_tamplate_data[anyyml_yml_upload]" class="form-control">
            <?php if(isset($tamplate_data_selected['anyyml_yml_upload']) && $tamplate_data_selected['anyyml_yml_upload']){ ?>
                <option value="1" selected="" >Включено</option>
                <option value="0" >Выключено</option>
            <?php }else{ ?>
                <option value="1"  >Включено</option>
                <option value="0" selected="">Выключено</option> 
            <?php } ?>
    </select>
    
    <?php if(isset($suppliers_feed_source) && $suppliers_feed_source){ ?>
    
        <label style="margin-top: 10px;">
            Исходная спецификация
        </label>
        <select  name="odmpro_tamplate_data[supplier_feed_source]" class="form-control">
            
            <option value="0"  >Не выбрано</option>
            
        <?php foreach($suppliers_feed_source as $source_id => $source){ ?>
    
            
        
                <?php if(isset($tamplate_data_selected['supplier_feed_source']) && $tamplate_data_selected['supplier_feed_source']==$source_id){ ?>
                    <option value="<?php echo $source_id ?>" selected="" ><?php echo $source['name'] ?></option>
                <?php }else{ ?>
                    <option value="<?php echo $source_id ?>" ><?php echo $source['name'] ?></option>
                <?php } ?>
    
        <?php } ?>        
        </select>
    
    <?php } ?>
    
    
    <label style="margin-top: 10px;">
        Время ожидания ответа при старте задачи (рекомендуется не более 30), в секундах
    </label>
    
    <?php if(isset($tamplate_data_selected['anyyml_max_curl_timeout']) && $tamplate_data_selected['anyyml_max_curl_timeout']!=='' ){ ?>
        <input name="odmpro_tamplate_data[anyyml_max_curl_timeout]"  value="<?php echo $tamplate_data_selected['anyyml_max_curl_timeout'] ?>" class="form-control" type="text" />
    <?php }else{ ?>
        <input name="odmpro_tamplate_data[anyyml_max_curl_timeout]"  value="5" class="form-control" type="text" />
    <?php } ?>
    
    <label style="margin-top: 10px;">
        С какого offer'а начать
    </label>
    
    <?php if(isset($tamplate_data_selected['anyyml_start_offer']) && $tamplate_data_selected['anyyml_start_offer']!=='' ){ ?>
        <input name="odmpro_tamplate_data[anyyml_start_offer]"  value="<?php echo $tamplate_data_selected['anyyml_start_offer'] ?>" class="form-control" type="text" />
    <?php }else{ ?>
        <input name="odmpro_tamplate_data[anyyml_start_offer]"  value="1" class="form-control" type="text" />
    <?php } ?>
    
    <label style="margin-top: 10px;">
        Сколько offer'ов парсить (если все, то нужно оставить пустым)
    </label>
    
    <?php if(isset($tamplate_data_selected['anyyml_limit_offer']) && $tamplate_data_selected['anyyml_limit_offer']!=='' ){ ?>
        <input name="odmpro_tamplate_data[anyyml_limit_offer]"  value="<?php echo $tamplate_data_selected['anyyml_limit_offer'] ?>" class="form-control" type="text" />
    <?php }else{ ?>
        <input name="odmpro_tamplate_data[anyyml_limit_offer]"  value="" class="form-control" type="text" />
    <?php } ?>
    
    <label style="margin-top: 10px;">
        Создать опции из параметров<br><small>перечислите названия параметров из атрибута name тега param через вертикальную черту: |</small>
    </label>
    
    <?php if(isset($tamplate_data_selected['anyyml_option_columns']) && $tamplate_data_selected['anyyml_option_columns']!=='' ){ ?>
        <input name="odmpro_tamplate_data[anyyml_option_columns]"  value="<?php echo $tamplate_data_selected['anyyml_option_columns'] ?>" class="form-control" type="text" />
    <?php }else{ ?>
        <input name="odmpro_tamplate_data[anyyml_option_columns]"  value="" class="form-control" type="text" />
    <?php } ?>
    
    <label style="margin-top: 10px;">
        Опции у которых будет передаваться изображение<br><small>перечислите названия параметров из атрибута name тега param через вертикальную черту: |</small>
    </label>
    
    <?php if(isset($tamplate_data_selected['anyyml_option_whis_image']) && $tamplate_data_selected['anyyml_option_whis_image']!=='' ){ ?>
        <input name="odmpro_tamplate_data[anyyml_option_whis_image]"  value="<?php echo $tamplate_data_selected['anyyml_option_whis_image'] ?>" class="form-control" type="text" />
    <?php }else{ ?>
        <input name="odmpro_tamplate_data[anyyml_option_whis_image]"  value="" class="form-control" type="text" />
    <?php } ?>
    
    <label style="margin-top: 10px;">
        Тип опций (select, image или checkbox)
    </label>
    
    <?php if(isset($tamplate_data_selected['anyyml_option_type']) && $tamplate_data_selected['anyyml_option_type']!=='' ){ ?>
        <input name="odmpro_tamplate_data[anyyml_option_type]"  value="<?php echo $tamplate_data_selected['anyyml_option_type'] ?>" class="form-control" type="text" />
    <?php }else{ ?>
        <input name="odmpro_tamplate_data[anyyml_option_type]"  value="select" class="form-control" type="text" />
    <?php } ?>
    
    <label style="margin-top: 10px;">
        Способ вывода опций в колонках
    </label>
    
    <select  name="odmpro_tamplate_data[anyyml_option_value_by_column]" class="form-control">
            <?php if(isset($tamplate_data_selected['anyyml_option_value_by_column']) && $tamplate_data_selected['anyyml_option_value_by_column']==='name_column'){ ?>
                <option value="name_column" selected="" >Каждая опция в отдельной колонке под своим названием</option>
                <option value="value_column"  >Каждая опция в отдельной колонке под номером всех опций (уменьшает количество колонок)</option>
                <option value="0" >Все опции в одной колонке</option>
            <?php }elseif(isset($tamplate_data_selected['anyyml_option_value_by_column']) && $tamplate_data_selected['anyyml_option_value_by_column']==='value_column'){ ?>
                <option value="name_column" >Каждая опция в отдельной колонке под своим названием</option>
                <option value="value_column" selected="" >Каждая опция в отдельной колонке под номером всех опций (уменьшает количество колонок)</option>
                <option value="0" >Все опции в одной колонке</option>
            <?php }else{ ?>
                <option value="name_column" >Каждая опция в отдельной колонке под своим названием</option>
                <option value="value_column"  >Каждая опция в отдельной колонке под номером всех опций (уменьшает количество колонок)</option>
                <option value="0" selected="" >Все опции в одной колонке</option>
            <?php } ?>
    </select>
    
    <label style="margin-top: 10px;">
        Создать атрибуты из параметров<br><small>перечислите названия параметров из атрибута name тега param через вертикальную черту: |</small>
    </label>
    
    <?php if(isset($tamplate_data_selected['anyyml_attribute_columns']) && $tamplate_data_selected['anyyml_attribute_columns']!=='' ){ ?>
        <input name="odmpro_tamplate_data[anyyml_attribute_columns]"  value="<?php echo $tamplate_data_selected['anyyml_attribute_columns'] ?>" class="form-control" type="text" />
    <?php }else{ ?>
        <input name="odmpro_tamplate_data[anyyml_attribute_columns]"  value="" class="form-control" type="text" />
    <?php } ?>
    
    <label style="margin-top: 10px;">
        Вычеркнуть следующие param<br><small>перечислите названия параметров из атрибута name тега param через вертикальную черту: |</small>
    </label>
    
    <?php if(isset($tamplate_data_selected['anyyml_delete_params']) && $tamplate_data_selected['anyyml_delete_params']!=='' ){ ?>
        <input name="odmpro_tamplate_data[anyyml_delete_params]"  value="<?php echo $tamplate_data_selected['anyyml_delete_params'] ?>" class="form-control" type="text" />
    <?php }else{ ?>
        <input name="odmpro_tamplate_data[anyyml_delete_params]"  value="" class="form-control" type="text" />
    <?php } ?>
    
    <label style="margin-top: 10px;">
        Создать колонки из указанных тегов param, атрибута name<br><small>перечислите названия параметров из атрибута name тега param через вертикальную черту: |</small>
    </label>
    
    <?php if(isset($tamplate_data_selected['anyyml_select_params_as_column']) && $tamplate_data_selected['anyyml_select_params_as_column']!=='' ){ ?>
        <input name="odmpro_tamplate_data[anyyml_select_params_as_column]"  value="<?php echo $tamplate_data_selected['anyyml_select_params_as_column'] ?>" class="form-control" type="text" />
    <?php }else{ ?>
        <input name="odmpro_tamplate_data[anyyml_select_params_as_column]"  value="" class="form-control" type="text" />
    <?php } ?>
    
    <label style="margin-top: 10px;">
        Удалить колонки из обмена<br><small>перечислите названия тегов, или названия колонок файл, которых не должно быть (уменьшает нагрузку) через вертикальную черту: |</small>
    </label>
    
    
    
    <?php if(isset($tamplate_data_selected['anyyml_delete_columns']) && $tamplate_data_selected['anyyml_delete_columns']!=='' ){ ?>
        <input name="odmpro_tamplate_data[anyyml_delete_columns]"  value="<?php echo $tamplate_data_selected['anyyml_delete_columns'] ?>" class="form-control" type="text" />
    <?php }else{ ?>
        <input name="odmpro_tamplate_data[anyyml_delete_columns]"  value="" class="form-control" type="text" />
    <?php } ?>
    
    <label style="margin-top: 10px;">
        Не импортировать товары категорий<br><small>перечислите categoryId категорий, товары которых не должны попадать в файл импорта через вертикальную черту: |</small>
    </label>
    
    
    <?php if(isset($tamplate_data_selected['anyyml_delete_by_category_id']) && $tamplate_data_selected['anyyml_delete_by_category_id']!=='' ){ ?>
        <input name="odmpro_tamplate_data[anyyml_delete_by_category_id]"  value="<?php echo $tamplate_data_selected['anyyml_delete_by_category_id'] ?>" class="form-control" type="text" />
    <?php }else{ ?>
        <input name="odmpro_tamplate_data[anyyml_delete_by_category_id]"  value="" class="form-control" type="text" />
    <?php } ?>
    
    
    
    <label style="margin-top: 10px;">
        Импортировать товары категорий<br><small>перечислите categoryId категорий, товары которых должны попадать в файл импорта через вертикальную черту (товары остальные категорий будут отброшены): |</small>
    </label>
    
    
    <?php if(isset($tamplate_data_selected['anyyml_set_products_by_category_id']) && $tamplate_data_selected['anyyml_set_products_by_category_id']!=='' ){ ?>
        <input name="odmpro_tamplate_data[anyyml_set_products_by_category_id]"  value="<?php echo $tamplate_data_selected['anyyml_set_products_by_category_id'] ?>" class="form-control" type="text" />
    <?php }else{ ?>
        <input name="odmpro_tamplate_data[anyyml_set_products_by_category_id]"  value="" class="form-control" type="text" />
    <?php } ?>
    
    <label style="margin-top: 10px;">
        Не импортировать товары производителей<br><small>перечислите названия производителей (из тега vendor), товары которых не должны попадать в файл импорта через вертикальную черту: |</small>
    </label>
    
    
    <?php if(isset($tamplate_data_selected['anyyml_delete_by_vendor']) && $tamplate_data_selected['anyyml_delete_by_vendor']!=='' ){ ?>
        <input name="odmpro_tamplate_data[anyyml_delete_by_vendor]"  value="<?php echo $tamplate_data_selected['anyyml_delete_by_vendor'] ?>" class="form-control" type="text" />
    <?php }else{ ?>
        <input name="odmpro_tamplate_data[anyyml_delete_by_vendor]"  value="" class="form-control" type="text" />
    <?php } ?>
    
    
    <label style="margin-top: 10px;">
        Параметры записывать в колонки<br><small>Если включено, то параметры будут записываться в колонках. Если у товаров много разных параметров, то колонок может быть очень много</small>
    </label>
    
    <select  name="odmpro_tamplate_data[anyyml_params_as_column]" class="form-control">
            <?php if(isset($tamplate_data_selected['anyyml_params_as_column']) && $tamplate_data_selected['anyyml_params_as_column']){ ?>
                <option value="1" selected="" >Включено</option>
                <option value="0" >Выключено</option>
            <?php }else{ ?>
                <option value="1"  >Включено</option>
                <option value="0" selected="">Выключено</option> 
            <?php } ?>
    </select>
    
    
    
</div>