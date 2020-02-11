
<tr>
    <td colspan="3" style="padding: 20px;">
<table  class="table table-bordered table-hover"  style="border:5px solid #ccc;">

<tr>
    <td colspan="3">
        <h3>Настройка пуска через smartExchange</h3>
        <small>Для включения этих настроек, используйте инструкцию, расположенную выше на этой странице, в пункте: "Ссылка для запуска процесса обновления компонентом smartExchange"</small>
    
    </td>
</tr>


        <?php $id = $link_data['id']; ?>

        <tr>
            <td>
                Если задача не завершается:
            </td>
            <td>
                <input  style="width:60%; float: left"  class="form-control" name="odmpro_update_csv_link[<?php echo $id ?>][max_task_timeout]" value="<?php echo $link_data['max_task_timeout'] ?>" />&nbsp;&nbsp;&nbsp;сек.
            </td>
            <td>
                  <select name="odmpro_update_csv_link[<?php echo $id ?>][max_task_timeout_action]"  class="form-control">

                      <option value="0" >Ничего не предпринимать</option>

                      <?php if($link_data['max_task_timeout_action'] && $link_data['max_task_timeout_action']==1){ ?>

                            <option selected="" value="1" >Запустить повторно с места остановки</option>

                            <option value="3" >Остановить</option>

                        <?php }elseif($link_data['max_task_timeout_action'] && $link_data['max_task_timeout_action']==3){ ?>

                            <option value="1" >Запустить повторно с места остановки</option>

                            <option selected="" value="3" >Остановить</option>

                        <?php }else{ ?>

                            <option value="1" >Запустить повторно с места остановки</option>

                            <option value="3" >Остановить</option>

                        <?php } ?>

                  </select>
            </td>
        </tr>
        
        <tr>
            <td colspan="2">
                Тип процесса обмена
            </td>
            <td>
                  <select name="odmpro_update_csv_link[<?php echo $id ?>][type_process]"  class="form-control">

                      <option value="0" >Выбрать</option>

                      <?php if($link_data['type_process'] && $link_data['type_process']=='import'){ ?>

                            <option selected="" value="import" >Импорт в ОпенКарт</option>

                            <option value="export" >Экспорт из ОпенКарт</option>

                        <?php }elseif($link_data['status'] && $link_data['type_process']=='export'){ ?>

                            <option value="import" >Импорт в ОпенКарт</option>

                            <option selected="" value="export" >Экспорт из ОпенКарт</option>

                        <?php }else{ ?>

                            <option value="import" >Импорт в ОпенКарт</option>

                            <option value="export" >Экспорт из ОпенКарт</option>

                        <?php } ?>

                  </select>
            </td>
        </tr>
        <tr>
            <td colspan="3">Установка времени запуска</td>
            
        <tr>
            <td colspan="3">
                
                <table  class="table table-bordered table-hover">
                    
                    <tr>
                        
                            <td>
                                <table class="table table-bordered table-hover">
                                    <tr>
                                    <td>День запуска (от 1 до 7)</td>
                                    <td>Час запуска (от 1 до 24)</td>
                                    <td>Мин (от 1 до 60)</td>
                                    <td>Последняя активность</td>
                                    </tr>
                                    
                                    <tr>
                                        <td colspan="4">Используйте дату и время сервера (может отличаться от местного времени). Время и дата на Вашем сервере: <?php echo $server_data_time ?></td>
                                    </tr>
                                    
                                <?php for($s=0;$s < $max_count_tasks_to_profile;$s++){ ?>

                                

                                <tr style="margin-bottom: 5px;">
                                            
                                            <td>

                                                <input placeholder="день недели"  style="width:60%; float: left"  class="form-control" name="odmpro_update_csv_link[<?php echo $id ?>][timestart][<?php echo $s ?>][d]" value="<?php if(isset($link_data['timestart'][$s]['d'])){ echo $link_data['timestart'][$s]['d']; } ?>" />

                                            </td>
                                            
                                            <td>

                                                <input placeholder="час"  style="width:60%; float: left"  class="form-control" name="odmpro_update_csv_link[<?php echo $id ?>][timestart][<?php echo $s ?>][h]" value="<?php if(isset($link_data['timestart'][$s]['h'])){ echo $link_data['timestart'][$s]['h']; } ?>" />

                                            </td>
                                            <td>

                                                <input placeholder="мин." style="width:60%; float: left"  class="form-control" name="odmpro_update_csv_link[<?php echo $id ?>][timestart][<?php echo $s ?>][m]" value="<?php if(isset($link_data['timestart'][$s]['m'])){ echo $link_data['timestart'][$s]['m']; } ?>" />

                                            </td>
                                            <td style="max-width: 600px;overflow-x: auto;">
                                                <?php if(isset($link_data['timestart'][$s]['task_id']) && $link_data['timestart'][$s]['task_id']){ ?>
                                                <div style="margin-bottom: 15px;">
                                                    <a onclick="getActionTask('<?php echo $link_data['timestart'][$s]['task_id'] ?>',1)" data-toggle="tooltip" class="btn btn-default"><i class="fa fa-play-circle"></i> Запустить сейчас</a>
                                                    <a onclick="getActionTask('<?php echo $link_data['timestart'][$s]['task_id'] ?>',3)" data-toggle="tooltip" class="btn btn-default"><i class="fa fa-pause-circle"></i> Остановить (если работает, остановится)</a>
                                                </div>
                                                <?php } ?>
                                                <span style="margin-bottom: 3px;">On-line лог, обновляется каждые 5 секунд:</span>
                                                <div style="max-height: 150px; overflow-y: auto; overflow-x: auto;font-size: 9px;" id='task_id_last_log_data_<?php if(isset($link_data['timestart'][$s]['task_id'])){ echo $link_data['timestart'][$s]['task_id']; } ?>' class="alert alert-info"></div>
                                                <span style="margin-bottom: 3px;">Лог обмена, обновляется при перезагрузке страницы:</span>
                                                <div class="well well-sm" style="height: 150px; overflow-y: auto; overflow-x: auto; ">
                                                    <span style="font-size: 9px;">
                                                        <?php if(isset($link_data['timestart'][$s]['last_log_data'])){ echo $link_data['timestart'][$s]['last_log_data']; }else{ ?> - <?php } ?>
                                                    </span>
                                                </div>

                                            </td>
                                        </tr>

                                    

                                <?php } ?>
                                
                                </table>
                                
                            </td>
                    </tr>
                    
                </table>
                
                
            </td>
        </tr>
        
            
        <tr>
            <td colspan="2">Email для отправки уведомлений (через запятую, если несколько)</td>
            <td>
                <input  style="width:60%; float: left"  class="form-control" name="odmpro_update_csv_link[<?php echo $id ?>][email_notice]" value="<?php echo $link_data['email_notice'] ?>" />
            </td>
        </tr>
        <tr>
            <td colspan="2">С этой задачей разрешены одновременные задачи (если выключено, то запуск одновременных задач вместе с этим процессом, будет невозможен)</td>
            <td>
                
                    <?php $setting_name = 'asynchronous_status'; ?>
                
                    <select name="odmpro_update_csv_link[<?php echo $id ?>][<?php echo $setting_name; ?>]"  class="form-control">

                      <?php if($link_data[$setting_name]){ ?>

                            <option selected="" value="1" >ДА</option>

                            <option value="0" >НЕТ</option>

                        <?php }elseif($link_data[$setting_name] && !$link_data[$setting_name]){ ?>

                            <option value="1" >ДА</option>

                            <option selected="" value="0" >НЕТ</option>

                        <?php }else{ ?>

                            <option value="1" >ДА</option>

                            <option selected="" value="0" >НЕТ</option>

                        <?php } ?>

                  </select>
            </td>
        </tr>
        
        <tr>
            <td colspan="2">Отправлять письмо о начале</td>
            <td>
                
                    <?php $setting_name = 'start_email_notice'; ?>
                
                    <select name="odmpro_update_csv_link[<?php echo $id ?>][<?php echo $setting_name; ?>]"  class="form-control">

                      <?php if($link_data[$setting_name]){ ?>

                            <option selected="" value="1" >ДА</option>

                            <option value="0" >НЕТ</option>

                        <?php }elseif($link_data[$setting_name] && !$link_data[$setting_name]){ ?>

                            <option value="1" >ДА</option>

                            <option selected="" value="0" >НЕТ</option>

                        <?php }else{ ?>

                            <option value="1" >ДА</option>

                            <option selected="" value="0" >НЕТ</option>

                        <?php } ?>

                  </select>
            </td>
        </tr>
        
        <tr>
            <td colspan="2">Отправлять письмо об остановке (превышения таймаута, установленного выше)</td>
            <td>
                
                    <?php $setting_name = 'timeout_email_notice'; ?>
                
                    <select name="odmpro_update_csv_link[<?php echo $id ?>][<?php echo $setting_name; ?>]"  class="form-control">

                      <?php if($link_data[$setting_name]){ ?>

                            <option selected="" value="1" >ДА</option>

                            <option value="0" >НЕТ</option>

                        <?php }elseif($link_data[$setting_name] && !$link_data[$setting_name]){ ?>

                            <option value="1" >ДА</option>

                            <option selected="" value="0" >НЕТ</option>

                        <?php }else{ ?>

                            <option value="1" >ДА</option>

                            <option selected="" value="0" >НЕТ</option>

                        <?php } ?>

                  </select>
            </td>
        </tr>
        
        <tr>
            <td colspan="2">Отправлять письмо о завершении</td>
            <td>
                
                    <?php $setting_name = 'finish_email_notice'; ?>
                
                    <select name="odmpro_update_csv_link[<?php echo $id ?>][<?php echo $setting_name; ?>]"  class="form-control">

                      <?php if($link_data[$setting_name]){ ?>

                            <option selected="" value="1" >ДА</option>

                            <option value="0" >НЕТ</option>

                        <?php }elseif($link_data[$setting_name] && !$link_data[$setting_name]){ ?>

                            <option value="1" >ДА</option>

                            <option selected="" value="0" >НЕТ</option>

                        <?php }else{ ?>

                            <option value="1" >ДА</option>

                            <option selected="" value="0" >НЕТ</option>

                        <?php } ?>

                  </select>
            </td>
        </tr>
</table>
        <br><br>
</td>
</tr>