<?php if($type_process=='import'){ ?>
                        
                        <?php

                        $import_css = "";
                        $export_css = " style='display:none' ";

                        ?>

                   <?php }elseif($type_process=='export'){ ?>

                       <?php

                        $import_css = " style='display:none' ";
                        $export_css = "";

                        ?>

<?php } ?>

<input type="hidden" class="form-control" name="odmpro_tamplate_data[type_data_column][<?php echo $field ?>][self_column]" value="1">

<?php if($columns){ ?>

<h5 <?php echo $import_css ?>>Выбрать поле для импорта</h5>

<h5 <?php echo $export_css ?>>Выбрать поле для экспорта (если требуется, может быть использовано в php ниже во "Вставке php")</h5>

<select <?php //echo $import_css ?> onchange="getTypesDataColumnsAdditional(this.value,'<?php echo $field ?>','#type_data_self_column_additional_<?php echo md5($field) ?>','<?php echo $type_process; ?>')" name="odmpro_tamplate_data[type_data_column][<?php echo $field ?>][db_table___db_column]"  class="form-control select-type-data-column select-type-data-self-column_<?php echo md5($field) ?>">
        <option value="<?php echo $type_data.'___no_db_column'; ?>" >Выбрать поле в базе данных</option>
        <?php foreach($columns as $db_table => $columns_table){ ?>

           <optgroup label="<?php echo ${'types_data_option_group_name_'.$db_table}; ?>">
            <?php foreach($columns_table as $db_column => $db_column_name){ ?>
                <?php if(isset($tamplate_data_selected['type_data_column'][$field]['db_table___db_column']) && $tamplate_data_selected['type_data_column'][$field]['db_table___db_column'] == $db_table.'___'.$db_column ){ ?>
                    <option value="<?php echo $db_table.'___'.$db_column ?>" selected="" ><?php echo $db_column_name; ?></option>
                <?php }else{ ?>
                    <option value="<?php echo $db_table.'___'.$db_column ?>" ><?php echo $db_column_name; ?></option> 
                <?php } ?>
            <?php } ?>
            </optgroup>
        <?php } ?>
</select>
            
<?php if($export_columns && FALSE){ ?>

<h5 <?php echo $export_css ?>>Выбрать поле для экспорта (если требуется, может быть использовано в php ниже во "Вставке php")</h5>

<select <?php echo $export_css ?> onchange="getTypesDataColumnsAdditional(this.value,'<?php echo $field ?>','#type_data_self_column_additional_<?php echo md5($field) ?>','<?php echo $type_process; ?>')" name="odmpro_tamplate_data[type_data_export_column][<?php echo $field ?>][db_table___db_column]"  class="form-control select-type-data-column select-type-data-self-column_<?php echo md5($field) ?>">
        <option value="<?php echo $type_data.'___no_db_column'; ?>" >Выбрать поле в базе данных</option>
        <?php foreach($export_columns as $db_table => $columns_table){ ?>

           <optgroup label="<?php echo ${'types_data_option_group_name_'.$db_table}; ?>">
            <?php foreach($columns_table as $db_column => $db_column_name){ ?>
                <?php if(isset($tamplate_data_selected['type_data_export_column'][$field]['db_table___db_column']) && $tamplate_data_selected['type_data_export_column'][$field]['db_table___db_column'] == $db_table.'___'.$db_column ){ ?>
                    <option value="<?php echo $db_table.'___'.$db_column ?>" selected="" ><?php echo $db_column_name; ?></option>
                <?php }else{ ?>
                    <option value="<?php echo $db_table.'___'.$db_column ?>" ><?php echo $db_column_name; ?></option> 
                <?php } ?>
            <?php } ?>
            </optgroup>
        <?php } ?>
</select>
<?php } ?>        
<div id="type_data_self_column_additional_<?php echo md5($field) ?>" class="well" style="margin-top: 5px; display: none;">
        
</div>

<?php } ?>

<script type="text/javascript"><!--
    
    $(document).ready(function() {
        updateSaveButton('<?php echo $type_process ?>');
        changeTypeDataColumn('select-type-data-self-column_<?php echo md5($field) ?>');
    });
    
//--></script> 

<div style="margin-top: 5px;">
    <h4 style="margin-bottom: 2px;"><b>Создать произвольный текст</b></h4>
    <?php if(isset($tamplate_data_selected['self_column'][$field]['string'])){ ?>
        <textarea class="form-control" name="odmpro_tamplate_data[self_column][<?php echo $field ?>][string]"><?php echo $tamplate_data_selected['self_column'][$field]['string']; ?></textarea>
    <?php }else{ ?>
        <textarea class="form-control" name="odmpro_tamplate_data[self_column][<?php echo $field ?>][string]"></textarea>
    <?php } ?>
</div>

<div style="margin-top: 5px;">
    <h4 style="margin-bottom: 3px;"><b>Создать произвольный текст по правилу ("склеивание" значений в колонках и призвольного текста)</b></h4>
    
    <table class="table table-bordered table-hover">
        <tbody>
            
            <?php for($r=0;$r<3;$r++){ ?>
            <tr>
                <td colspan="3" style="background: #e5e5e5"><span onclick="showHide('.rule_row_<?php echo md5($field).'__'.$r ?>');" style="border-bottom: 1px dashed; cursor: pointer">Правило <?php echo ($r+1); ?></span></td>
            </tr>
            <tr class="rule_row_<?php echo md5($field).'__'.$r ?>" style="display: none">
                <td>Выбрать колонку файла</td>
                <td>Оператор</td>
                <td>Значение в колонке</td>
            </tr>
            <tr class="rule_row_<?php echo md5($field).'__'.$r ?>" style="display: none">
                <td>
                    
                    <select name="odmpro_tamplate_data[self_column][<?php echo $field ?>][rule][<?php echo $r ?>][field]"  class="form-control select-type-data-column select-type-data-column_<?php echo md5($field) ?>">
                        <option value="0" >Неважно</option>
                        <?php foreach($fields as $field_this => $field_name){ ?>

                            <?php if(isset($tamplate_data_selected['self_column'][$field]['rule'][$r]['field']) && $tamplate_data_selected['self_column'][$field]['rule'][$r]['field'] == $field_this ){ ?>
                                <option value="<?php echo $field_this ?>" selected="" ><?php echo $field_name; ?></option>
                            <?php }else{ ?>
                                <option value="<?php echo $field_this ?>" ><?php echo $field_name; ?></option> 
                            <?php } ?>
                           
                        <?php } ?>
                    </select>
                    
                </td>
                <td>
                    <select name="odmpro_tamplate_data[self_column][<?php echo $field ?>][rule][<?php echo $r ?>][operator]"  class="form-control select-type-data-column select-type-data-column_<?php echo md5($field) ?>">
                        <option value="0" >Неважно</option>
                        <?php foreach($operators as $operator => $operator_name){ ?>

                            <?php if(isset($tamplate_data_selected['self_column'][$field]['rule'][$r]['operator']) && $tamplate_data_selected['self_column'][$field]['rule'][$r]['operator'] == $operator ){ ?>
                                <option value="<?php echo $operator ?>" selected="" ><?php echo $operator_name; ?></option>
                            <?php }else{ ?>
                                <option value="<?php echo $operator ?>" ><?php echo $operator_name; ?></option> 
                            <?php } ?>
                           
                        <?php } ?>
                    </select>
                </td>
                <td>
                    
                    <?php if(isset($tamplate_data_selected['self_column'][$field]['rule'][$r]['value'])){ ?>
                        <input class="form-control" name="odmpro_tamplate_data[self_column][<?php echo $field ?>][rule][<?php echo $r ?>][value]" value="<?php echo $tamplate_data_selected['self_column'][$field]['rule'][$r]['value']; ?>">
                    <?php }else{ ?>
                        <input class="form-control" name="odmpro_tamplate_data[self_column][<?php echo $field ?>][rule][<?php echo $r ?>][value]" value="" />
                    <?php } ?>
                    
                </td>
            </tr>
            <tr class="rule_row_<?php echo md5($field).'__'.$r ?>" style="display: none">
                <td colspan="3">Создание произвольного текста (может быть собран из значений в колонках, если выше указано условие, то текст будет создан в случае его выполнения)</td>
            </tr>
            <tr class="rule_row_<?php echo md5($field).'__'.$r ?>" style="display: none">
                <td colspan="3">
                    <table>
                        <tr>
                            <td>
                                <?php if(isset($tamplate_data_selected['self_column'][$field]['rule'][$r]['text'][0])){ ?>
                                    <input placeholder="Произвольный текст"  class="form-control" name="odmpro_tamplate_data[self_column][<?php echo $field ?>][rule][<?php echo $r ?>][text][0]" value="<?php echo $tamplate_data_selected['self_column'][$field]['rule'][$r]['text'][0]; ?>">
                                <?php }else{ ?>
                                    <input placeholder="Произвольный текст"  class="form-control" name="odmpro_tamplate_data[self_column][<?php echo $field ?>][rule][<?php echo $r ?>][text][0]" value="">
                                <?php } ?>
                            </td>
                            <td>
                                
                                <select name="odmpro_tamplate_data[self_column][<?php echo $field ?>][rule][<?php echo $r ?>][text][1]"  class="form-control select-type-data-column select-type-data-column_<?php echo md5($field) ?>">
                                    <option value="skip_this_part" >Значение из колонки (если нужно)</option>
                                    <?php foreach($fields as $field_this => $field_name){ ?>

                                    <?php if(isset($tamplate_data_selected['self_column'][$field]['rule'][$r]['text'][1]) && $tamplate_data_selected['self_column'][$field]['rule'][$r]['text'][1] == $field_this ){ ?>
                                        <option value="<?php echo $field_this ?>" selected="" ><?php echo $field_name; ?></option>
                                    <?php }else{ ?>
                                        <option value="<?php echo $field_this ?>" ><?php echo $field_name; ?></option> 
                                    <?php } ?>

                                <?php } ?>
                                </select>
                                
                            </td>
                            <td>
                                <?php if(isset($tamplate_data_selected['self_column'][$field]['rule'][$r]['text'][2])){ ?>
                                    <input placeholder="Произвольный текст"  class="form-control" name="odmpro_tamplate_data[self_column][<?php echo $field ?>][rule][<?php echo $r ?>][text][2]" value="<?php echo $tamplate_data_selected['self_column'][$field]['rule'][$r]['text'][2]; ?>">
                                <?php }else{ ?>
                                    <input placeholder="Произвольный текст"  class="form-control" name="odmpro_tamplate_data[self_column][<?php echo $field ?>][rule][<?php echo $r ?>][text][2]" value="">
                                <?php } ?>
                            </td>
                            <td>
                                
                                <select name="odmpro_tamplate_data[self_column][<?php echo $field ?>][rule][<?php echo $r ?>][text][3]"  class="form-control select-type-data-column select-type-data-column_<?php echo md5($field) ?>">
                                    <option value="skip_this_part" >Значение из колонки (если нужно)</option>
                                    <?php foreach($fields as $field_this => $field_name){ ?>

                                    <?php if(isset($tamplate_data_selected['self_column'][$field]['rule'][$r]['text'][3]) && $tamplate_data_selected['self_column'][$field]['rule'][$r]['text'][3] == $field_this ){ ?>
                                        <option value="<?php echo $field_this ?>" selected="" ><?php echo $field_name; ?></option>
                                    <?php }else{ ?>
                                        <option value="<?php echo $field_this ?>" ><?php echo $field_name; ?></option> 
                                    <?php } ?>

                                <?php } ?>
                                </select>
                                
                            </td>
                            <td>
                                <?php if(isset($tamplate_data_selected['self_column'][$field]['rule'][$r]['text'][4])){ ?>
                                    <input placeholder="Произвольный текст"  class="form-control" name="odmpro_tamplate_data[self_column][<?php echo $field ?>][rule][<?php echo $r ?>][text][4]" value="<?php echo $tamplate_data_selected['self_column'][$field]['rule'][$r]['text'][4]; ?>">
                                <?php }else{ ?>
                                    <input placeholder="Произвольный текст"  class="form-control" name="odmpro_tamplate_data[self_column][<?php echo $field ?>][rule][<?php echo $r ?>][text][4]" value="">
                                <?php } ?>
                            </td>
                            <td>
                                
                                <select name="odmpro_tamplate_data[self_column][<?php echo $field ?>][rule][<?php echo $r ?>][text][5]"  class="form-control select-type-data-column select-type-data-column_<?php echo md5($field) ?>">
                                    <option value="skip_this_part" >Значение из колонки (если нужно)</option>
                                    <?php foreach($fields as $field_this => $field_name){ ?>

                                    <?php if(isset($tamplate_data_selected['self_column'][$field]['rule'][$r]['text'][5]) && $tamplate_data_selected['self_column'][$field]['rule'][$r]['text'][5] == $field_this ){ ?>
                                        <option value="<?php echo $field_this ?>" selected="" ><?php echo $field_name; ?></option>
                                    <?php }else{ ?>
                                        <option value="<?php echo $field_this ?>" ><?php echo $field_name; ?></option> 
                                    <?php } ?>

                                <?php } ?>
                                </select>
                                
                            </td>
                            <td>
                                
                                <?php if(isset($tamplate_data_selected['self_column'][$field]['rule'][$r]['text'][6])){ ?>
                                    <input placeholder="Произвольный текст" class="form-control" name="odmpro_tamplate_data[self_column][<?php echo $field ?>][rule][<?php echo $r ?>][text][6]" value="<?php echo $tamplate_data_selected['self_column'][$field]['rule'][$r]['text'][6]; ?>">
                                <?php }else{ ?>
                                    <input placeholder="Произвольный текст"  class="form-control" name="odmpro_tamplate_data[self_column][<?php echo $field ?>][rule][<?php echo $r ?>][text][6]" value="">
                                <?php } ?>
                                
                            </td>
                        </tr>
                    </table>
                </td>
                
            </tr>
            
            <?php } ?>
        </tbody>
    </table>
    
    
</div>
<hr>



<div style="margin-top: 10px;">
    <h4 style="margin-bottom: 2px;"><b onclick="showHide('#parsing-box-<?php echo md5($field).'__'.$r ?>')" style="border-bottom: 1px dashed; cursor: pointer">Парсинг с использованием xPath</b> <span style="border-bottom: 1px dashed; cursor: pointer" onclick="showHide('.xpath_row_<?php echo md5($field).'__'.$r ?>');">Справка</span></h4>
    <small class="xpath_row_<?php echo md5($field).'__'.$r ?>" style="display: none; padding: 7px; background: white;">Укажите ссылку для парсинга или укажите колонку, в которой будет находиться ссылка для парсинга. При импорте: результат выполнения будет импортироваться в указанное выше поле базы данных. При экспорте будет создаваться колонка, с указанным называнием и результат будет находиться в ней. Подробнее о синтаксисе xPath см. <a href="http://zvon.org/xxl/XPathTutorial/Output_rus/examples.html" target="_blank">здесь</a></small>
    
    <div id="parsing-box-<?php echo md5($field).'__'.$r ?>" style="display: none" class="well">
    
    <h5 style="margin-top: 7px;">Брать данную ссылку для парсинга</h5>
    <?php if(isset($tamplate_data_selected['self_column'][$field]['xpath_url_as_string'])){ ?>
        <input class="form-control" name="odmpro_tamplate_data[self_column][<?php echo $field ?>][xpath_url_as_string]" value="<?php echo $tamplate_data_selected['self_column'][$field]['xpath_url_as_string']; ?>">
    <?php }else{ ?>
        <input class="form-control" name="odmpro_tamplate_data[self_column][<?php echo $field ?>][xpath_url_as_string]" value="">
    <?php } ?>
    <h5 style="margin-top: 7px;">Брать ссылку для парсинга из колонки файла</h5>
    <select name="odmpro_tamplate_data[self_column][<?php echo $field ?>][xpath_url_from_column]"  class="form-control select-type-data-column select-type-data-column_<?php echo md5($field) ?>">
        <option value="skip_this_name" >Не использовать</option>
        <?php foreach($fields as $field_this => $field_name){ ?>

            <?php if(isset($tamplate_data_selected['self_column'][$field]['xpath_url_from_column']) && $tamplate_data_selected['self_column'][$field]['xpath_url_from_column'] == $field_this ){ ?>
                <option value="<?php echo $field_this ?>" selected="" ><?php echo $field_name; ?></option>
            <?php }else{ ?>
                <option value="<?php echo $field_this ?>" ><?php echo $field_name; ?></option> 
            <?php } ?>

        <?php } ?>
    </select>
    <h5 style="margin-top: 7px;">Время прерывания между соединениями (для избежания блокировки, желательно не менее 1) в сек.</h5>
    <?php if(isset($tamplate_data_selected['self_column'][$field]['xpath_sleep'])){ ?>
        <input class="form-control" name="odmpro_tamplate_data[self_column][<?php echo $field ?>][xpath_sleep]" value="<?php echo $tamplate_data_selected['self_column'][$field]['xpath_sleep']; ?>">
    <?php }else{ ?>
        <input class="form-control" name="odmpro_tamplate_data[self_column][<?php echo $field ?>][xpath_sleep]" value="1">
    <?php } ?>
    
    <h5 style="margin-top: 7px;">Использовать разные заголовки при парсинге</h5>
    <select style="margin-top: 5px;" id='sheadXPath_<?php echo md5($field) ?>'  name="odmpro_tamplate_data[self_column][<?php echo $field ?>][def_headers]"  class="form-control select-type-data-column select-type-data-column_<?php echo md5($field) ?>">
        <?php if(isset($tamplate_data_selected['self_column'][$field]['def_headers']) && $tamplate_data_selected['self_column'][$field]['def_headers'] ){ ?>
            
            <option value="0" >Выключено</option>
            <option value="1" selected="" >Включено</option>
            
        <?php }else{ ?>
            <option value="0"  selected="" >Выключено</option>
            <option value="1" >Включено</option>
        <?php } ?>
    </select>
    
    
    <h5 style="margin-top: 7px;">xPath запрос (не указывайте ничего, если нужно получить полный html-документ (в теге body)</h5>
    <?php if(isset($tamplate_data_selected['self_column'][$field]['xpath'])){ ?>
        <textarea id="xpath_textarea_slef_column_<?php echo md5($field) ?>" class="form-control" name="odmpro_tamplate_data[self_column][<?php echo $field ?>][xpath]"><?php echo $tamplate_data_selected['self_column'][$field]['xpath']; ?></textarea>
    <?php }else{ ?>
        <textarea id="xpath_textarea_slef_column_<?php echo md5($field) ?>" class="form-control" name="odmpro_tamplate_data[self_column][<?php echo $field ?>][xpath]"></textarea>
    <?php } ?>
    <select style="margin-top: 5px;" id='stagsXPath_<?php echo md5($field) ?>'  name="odmpro_tamplate_data[self_column][<?php echo $field ?>][xpath_stags]"  class="form-control select-type-data-column select-type-data-column_<?php echo md5($field) ?>">
        <?php if(isset($tamplate_data_selected['self_column'][$field]['xpath_stags']) && $tamplate_data_selected['self_column'][$field]['xpath_stags'] ){ ?>
            
            <option value="0" >Не удалять html-теги</option>
            <option value="1" selected="" >Удалять html-теги</option>
            
        <?php }else{ ?>
            <option value="0"  selected="" >Не удалять html-теги</option>
            <option value="1" >Удалять html-теги</option>
        <?php } ?>
    </select>
    <h5 style="margin-top: 7px;">Проверить результат по этой ссылке (введите ссылку, для которой будет выполнен запрос xPath, указанный выше и нажмите проверить, чтобы проверить резальтат выполнения)</h5>
    <input placeholder="Ссылка для проверки xPath запроса" class="form-control" id='checkXPath_<?php echo md5($field) ?>' value="">
    <a style="margin-top: 5px;" onclick="getXPathResult('#checkXPath_<?php echo md5($field) ?>','#xpath_textarea_slef_column_<?php echo md5($field) ?>','#stagsXPath_<?php echo md5($field) ?>','#checkXPathresult_<?php echo md5($field) ?>');" class="btn btn-info">Проверить</a>
    <div id='checkXPathresult_<?php echo md5($field) ?>' style="overflow: auto; max-height: 300px; max-width: 600px;" ></div>
    
    
    </div>
    
</div>    

<div style="margin-top: 10px;">
    <h4 style="margin-bottom: 2px;"><b onclick="showHide('#php-box-<?php echo md5($field).'__'.$r ?>')" style="border-bottom: 1px dashed; cursor: pointer">Вставка php (выполняется перед обработкой строки)</b> <span style="border-bottom: 1px dashed; cursor: pointer" onclick="showHide('.php_row_<?php echo md5($field).'__'.$r ?>');">Справка</span></h4>
    
    <div id="php-box-<?php echo md5($field).'__'.$r ?>" style="display: none" class="well">
    
    <small class="php_row_<?php echo md5($field).'__'.$r ?>" style="display: none; padding: 7px; background: white;">Вставьте php, который будет выполнен при обработке каждой строки. Результат выполнения нужно присвоить переменной $result. Например, если нужно импортировать/экспортировать значение 52, в указанное выше поле базы данных, напишите: <span style="color: goldenrod">$result = 52;</span>. В области видимости выполнения данного php доступны: переменная $num_row с номером, обрабатываемой строки (без учета первой строки с названиями колонок, если таковая присутствует в файле), переменная $result_xpath с результатом обработки xPath (если настраивался выше). А также доступны значения в колонках соответствующей строки. Данные значения будут находиться в переменной: ${'Название колонки'}. Вы также можете изменить значения в этих колонках. Например, если при обработке нужно присвоить значение 5 в колонке под называнием Количество соответствущей строки, напишите: <span style="color: goldenrod">${'Количество'} = 5;</span> Для работы с базой данный используйте стандартный объект db OpenCart. Например, для подучения содержимого таблицы товаров напишите: <span style="color: goldenrod">$products = $this->db->query("SELECT * FROM " . DB_PREFIX . "product");</span></small>
    <?php if(isset($tamplate_data_selected['self_column'][$field]['php'])){ ?>
        <textarea class="form-control" name="odmpro_tamplate_data[self_column][<?php echo $field ?>][php]"><?php echo $tamplate_data_selected['self_column'][$field]['php']; ?></textarea>
    <?php }else{ ?>
        <textarea class="form-control" name="odmpro_tamplate_data[self_column][<?php echo $field ?>][php]"></textarea>
    <?php } ?>
    
    </div>
</div>