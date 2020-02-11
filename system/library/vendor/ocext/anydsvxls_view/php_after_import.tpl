<td class="text-left">
            Выполнить php код перед началом обработки строки<br>
            <small>Код будет выполнен перед началом импорта порции. Номер строки обработки находится в переменной $this_num_row. Номер строки равен номеру строки файла минус 1, если в файле присутствует первая строка с названиями колонок</small>
        </td>
        <td class="text-left">

            <?php if(isset($tamplate_data_selected['php_after_import'])){ ?>
                <textarea class="form-control" name="odmpro_tamplate_data[php_after_import]"><?php echo $tamplate_data_selected['php_after_import']; ?></textarea>
            <?php }else{ ?>
                <textarea class="form-control" name="odmpro_tamplate_data[php_after_import]"></textarea>
            <?php } ?>

        </td>
</tr>
<tr>
    <td class="text-left">
        Выполнить php код в конце обработки строки<br>
        <small>Код будет выполнен после обработки каждой строки. В переменной $entity_id содержится автоинкремент, обрабатываемых данных - например, если производился импорт/экспорт товара, то в $entity_id будет находиться значение product_id товара</small>
        </td>
        <td class="text-left">

            <?php if(isset($tamplate_data_selected['php_before_row_import'])){ ?>
                <textarea class="form-control" name="odmpro_tamplate_data[php_before_row_import]"><?php echo $tamplate_data_selected['php_before_row_import']; ?></textarea>
            <?php }else{ ?>
                <textarea class="form-control" name="odmpro_tamplate_data[php_before_row_import]"></textarea>
            <?php } ?>

</td>