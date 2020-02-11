
<label style="margin-bottom: 10px;">Формат экспорта</label>
<select id='export_boxes_selector' style="margin-bottom: 10px;" onchange="$('.export_boxes').hide();$('#export_box_'+this.value).show();" name="odmpro_tamplate_data[export_format_selected]" class="form-control">
        <?php if(isset($tamplate_data_selected['export_format_selected']) && $tamplate_data_selected['export_format_selected']=='xls'){ ?>
            <option value="csv"  >CSV</option>
            <option value="xls" selected="" >XLS</option>
        <?php }else{ ?>
            <option value="csv" selected="" >CSV</option>
            <option value="xls" >XLS</option> 
        <?php } ?>
</select>

<div class="input-group export_boxes"  style="width: 100%" id="export_box_xls">
    <label>Имя файл вместе с путем</label>
    <table>
        <tr>
            <td><?php echo HTTPS_CATALOG.'image/' ?></td>
            <td><input style="margin-bottom: 5px;" type="text" onchange="update_link('#export_file_name_link_xls','<?php echo HTTPS_CATALOG.'image/' ?>',this.value,'.xlsx')" name="odmpro_tamplate_data[export_file_name_xls]" value="<?php echo $tamplate_data_selected['export_file_name_xls'] ?>" class="form-control" /></td>
            <td>.xlsx</td>
        </tr>
    </table>
    <table class="table table-bordered table-hover">
        <tr>  
            <td colspan="3">
            <input   class="form-control" id="export_file_name_link_xls"  readonly="" onclick="$(this).select()" value="<?php echo HTTPS_CATALOG.'image/'.$tamplate_data_selected['export_file_name_xls'].'.xlsx' ?>"/>
            </td>
        </tr>

    </table>

    <label style="margin-top: 10px;">Добавлять время записи к называнию файла?</label>
    <select name="odmpro_tamplate_data[file_name_write_time_xls]" class="form-control">
            <?php if(isset($tamplate_data_selected['file_name_write_time_xls']) && $tamplate_data_selected['file_name_write_time_xls']){ ?>
                <option value="1" selected="" >Включено</option>
                <option value="0" >Выключено</option>
            <?php }else{ ?>
                <option value="1"  >Включено</option>
                <option value="0" selected="">Выключено</option> 
            <?php } ?>
    </select>

</div>

<script type="text/javascript"><!--
    
    $(document).ready(function() {
        
        $("#export_boxes_selector").change();
        
    });
    
//--></script> 