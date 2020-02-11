<div class="scrollbox" style="max-height: 350px; overflow-y: auto; width: 100%">
    
    <table class="table table-bordered table-hover">
        <thead>

            <tr>

                <td>Поле в таблице</td>
                <td>Оператор</td>
                <td>Значение</td>
                <td>Логика</td>

            </tr>

        </thead>
        <?php for($i=0;$i<5;$i++){ ?>


                    <tr>

                        <td>

                            <div class="input-group" >
                                <select name="odmpro_tamplate_data[type_data_general_settings][<?php echo $type_data ?>][where_rules][<?php echo $i ?>][product_field]"  class="form-control select-type-data">
                                    <option value="0" >Не указано</option>
                                        <?php foreach($columns as $product_field => $product_value){ ?>
                                            <?php if(isset($tamplate_data_selected['type_data_general_settings'][$type_data]['where_rules'][$i]['product_field']) && $tamplate_data_selected['type_data_general_settings'][$type_data]['where_rules'][$i]['product_field']==$product_field ){ ?>
                                    <option value="<?php echo $product_field ?>" selected="" ><?php echo $product_value; ?></option>
                                            <?php }else{ ?>
                                    <option value="<?php echo $product_field ?>" ><?php echo $product_value; ?></option> 
                                            <?php } ?>
                                        <?php } ?>
                                </select>
                            </div>

                        </td>

                        <td>

                            <div class="input-group" >
                                <select name="odmpro_tamplate_data[type_data_general_settings][<?php echo $type_data ?>][where_rules][<?php echo $i ?>][operator]"  class="form-control select-type-data">
                                    <option value="0" >Не указывать</option>
                                        <?php foreach($operators as $product_field => $product_value){ ?>
                                            <?php if(isset($tamplate_data_selected['type_data_general_settings'][$type_data]['where_rules'][$i]['operator']) && $tamplate_data_selected['type_data_general_settings'][$type_data]['where_rules'][$i]['operator']==$product_field ){ ?>
                                    <option value="<?php echo $product_field ?>" selected="" ><?php echo $product_value; ?></option>
                                            <?php }else{ ?>
                                    <option value="<?php echo $product_field ?>" ><?php echo $product_value; ?></option> 
                                            <?php } ?>
                                        <?php } ?>
                                </select>
                            </div>

                        </td>

                        <td>

                            <div class="input-group" >

                                <?php if(isset($tamplate_data_selected['type_data_general_settings'][$type_data]['where_rules'][$i]['value']) ){ ?>
                                    <input name="odmpro_tamplate_data[type_data_general_settings][<?php echo $type_data ?>][where_rules][<?php echo $i ?>][value]"  value="<?php echo $tamplate_data_selected['type_data_general_settings'][$type_data]['where_rules'][$i]['value'] ?>" class="form-control select-type-data" type="text" />
                                <?php }else{ ?>
                                    <input name="odmpro_tamplate_data[type_data_general_settings][<?php echo $type_data ?>][where_rules][<?php echo $i ?>][value]" value=""  class="form-control select-type-data" type="text" />
                                <?php } ?>

                            </div>

                        </td>
                        
                        <td>

                            <div class="input-group" >
                                <select name="odmpro_tamplate_data[type_data_general_settings][<?php echo $type_data ?>][where_rules][<?php echo $i ?>][logic]"  class="form-control select-type-data">
                                        <?php foreach($logics as $product_field => $product_value){ ?>
                                            <?php if(isset($tamplate_data_selected['type_data_general_settings'][$type_data]['where_rules'][$i]['logic']) && $tamplate_data_selected['type_data_general_settings'][$type_data]['where_rules'][$i]['logic']==$product_field ){ ?>
                                    <option value="<?php echo $product_field ?>" selected="" ><?php echo $product_value; ?></option>
                                            <?php }else{ ?>
                                    <option value="<?php echo $product_field ?>" ><?php echo $product_value; ?></option> 
                                            <?php } ?>
                                        <?php } ?>
                                </select>
                            </div>

                        </td>

                    </tr>


        <?php } ?>
    </table>
    
</div>
