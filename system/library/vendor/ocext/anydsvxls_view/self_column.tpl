<?php if(isset($tamplate_data_selected['self_column'])){ ?>

<?php foreach($tamplate_data_selected['self_column'] as $field => $self_column){ ?>

<tr id="type_data_self_column_<?php echo $field; ?>_table_row" class="self_column_table_row" style="border-left: 3px solid lightseagreen; ">
    <td class="text-left" width="20%">

    <input value="<?php echo $self_column['import_self_column_name'] ?>" class="form-control" name="odmpro_tamplate_data[self_column][<?php echo $field; ?>][import_self_column_name]" />
    <input type="hidden" value="<?php echo $field; ?>" class="form-control" name="odmpro_tamplate_data[self_column][<?php echo $field; ?>][import_self_column_id]" />

    </td>
    <td class="text-left" width="20%">
    <div class="input-group">
        
        <select onchange="getTypesDataSelfColumns(this.value,'#type_data_column_<?php echo $field; ?>','<?php echo $field; ?>','<?php echo $type_process; ?>');" name="odmpro_tamplate_data[type_data][<?php echo $field; ?>]"  class="form-control select-type-data">
            <option value="0" >Не использовать</option>
                <?php foreach($types_data as $type => $columns){ ?>
                    <?php if(isset($tamplate_data_selected['type_data'][$field]) && $tamplate_data_selected['type_data'][$field] == $type ){ ?>
            <option value="<?php echo $type ?>" selected="" ><?php echo ${'text_type_data_'.$type}; ?></option>
                    <?php }else{ ?>
            <option value="<?php echo $type ?>" ><?php echo ${'text_type_data_'.$type}; ?></option> 
                    <?php } ?>
                <?php } ?>
        </select>
    </div>
    </td>
    <td class="text-left" id="type_data_column_<?php echo $field; ?>">
    </td>
    <td class="text-left"><button type="button" onclick="$('#type_data_self_column_<?php echo $field; ?>_table_row').remove();" data-toggle="tooltip" title="" class="btn btn-danger" ><i class="fa fa-minus-circle"></i></button></td>
</tr>

<?php } ?>

<?php } ?>

<tr id="addSelfTypeDataColumnTableRow_<?php echo $type_process; ?>">
    <td colspan="4">
        <a onclick="addSelfTypeDataColumnTableRow('<?php echo $type_process; ?>','<?php echo $tamplate_data_selected['id'] ?>')" data-toggle="tooltip" title="" class="btn btn-primary" style="background: lightseagreen; border: 1px solid darkcyan"><i class="fa fa-plus"></i> Добавить функциональную колонку</a>
    </td>
</tr>