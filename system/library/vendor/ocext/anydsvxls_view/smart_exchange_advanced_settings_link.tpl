<h2>Ссылка для запуска процесса обновления компонентом smartExchange</h2>
<div><small>Установите "Cсылку smartExchange" на CRON хостинга с интервалом запуска каждые 10-20 сек. Используйте для запуска ссылки WGET или CURL. Такой плотный запуск не создаст нагрузки на сервер, не требует обращения к базе данных. Он необходим для раздельного пуска задач импорта и экспорта, которые настраиваются в рубрике Управление автообновлением / Настройка пуска через smartExchange</small></div>
<div class="input-group" style="margin-top: 5px; margin-bottom: 10px; width: 100%">
    <table class="table table-bordered table-hover">
        
        <tr>
            <td style="width: 50%;">Защитный токен (используется при автоматизации с использованием WGET или CURL)</td>
            <td>Скопировать "Cсылку smartExchange"</td>
        </tr>
        
        <tr>
            <td>
                <input class="form-control" name="odmpro_update_csv_smart_exchange_link[token]" value="<?php echo $odmpro_update_csv_smart_exchange_link['token'] ?>" />
            </td>
            <td>
                Ссылка для самостоятельной настройки с использованием CURL или WGET
                <input style="margin-bottom: 5px;" class="form-control"  readonly="" onclick="$(this).select()" value="<?php echo HTTP_CATALOG.'ocext_smart_exchange.php?exchange_link_token='.$odmpro_update_csv_smart_exchange_link['token'] ?>"/><br>
                
                Пример команды для CRON при использовании WGET (может отличаться на Вашем хостинге)
                <input style="margin-bottom: 5px;" class="form-control"  readonly="" onclick="$(this).select()" value="wget -O - -q -t 1 '<?php echo HTTP_CATALOG.'ocext_smart_exchange.php?exchange_link_token='.$odmpro_update_csv_smart_exchange_link['token'] ?>'; sleep 30; wget -O - -q -t 1 '<?php echo HTTP_CATALOG.'ocext_smart_exchange.php?exchange_link_token='.$odmpro_update_csv_smart_exchange_link['token'] ?>'; sleep 10;"/><br>
                
                (<span style="color: green">Рекомендуется</span>). Пример команды для CRON при использовании обработчика php. При использовании данной ссылки, убедитесь, что обработчик php на хостинге находится в папке: <b>/usr/bin/php</b>. Если обработчик в другой папке, укажите её вместо указанной в примере
                <input style="margin-bottom: 5px;" class="form-control"  readonly="" onclick="$(this).select()" value="/usr/bin/php <?php echo $library_vendor_ocext ?>; sleep 10; /usr/bin/php <?php echo $library_vendor_ocext ?>; sleep 10; /usr/bin/php <?php echo $library_vendor_ocext ?>; sleep 10; /usr/bin/php <?php echo $library_vendor_ocext ?>;"/>
                
                
                
                <input name="odmpro_update_csv_smart_exchange_link[path_oc_version_feed]" type="hidden" value="<?php echo $path_oc_version_feed ?>" />
                
                
                
                
            </td>
        </tr>
        
        <tr>
            <td>Текущий статус (обновляется каждые 10 сек., полная проверка доступна в течении одной минуты)</td>
        <td id="smart_exchange_check_connect_status">
            <?php echo $smart_exchange_check_connect_status; ?>
        </td>
        </tr>
        <tr>
            <td>
                Количество возможных задач в профилях автообновлений (ниже)
            </td>
            <td>
                <input class="form-control" name="odmpro_update_csv_smart_exchange_link[max_count_tasks_to_profile]" value="<?php echo $odmpro_update_csv_smart_exchange_link['max_count_tasks_to_profile'] ?>" />
            </td>
        </tr>
        
        <tr>
            <td>
                Время ожидания ответа при старте задачи (рекомендуется не более 30), в секундах
            </td>
            <td>
                <input class="form-control" name="odmpro_update_csv_smart_exchange_link[max_curl_timeout]" value="<?php echo $odmpro_update_csv_smart_exchange_link['max_curl_timeout'] ?>" />
            </td>
        </tr>
        
        
        
        
        
        
        <tr>
            <td>
                Максимальное количество задач, работающих одновременно (рекомендуется: 1, остальные задачи будут запускаться после окончания предыдущей) 
            </td>
            <td>
                <input class="form-control" name="odmpro_update_csv_smart_exchange_link[max_count_tasks]" value="<?php echo $odmpro_update_csv_smart_exchange_link['max_count_tasks'] ?>" />
            </td>
        </tr>
        <tr>
            <td>
                Дополнительный запуск smartExchange<br>
                <small>Если планировщик хостинга не позволяет запускать скрипт чаще одного раза в минуту, установите значение, сколько раз нужно пускать скрипт дополнительно в рамках этой минуты. Например, если Ссылка smartExchange запускается один раз в минуту, и необходимо запускать скрипт каждые ~10 сек., то установите 4-5</small>
            </td>
            <td>
                <input class="form-control" name="odmpro_update_csv_smart_exchange_link[max_count_self_starts]" value="<?php echo $odmpro_update_csv_smart_exchange_link['max_count_self_starts'] ?>" />
            </td>
        </tr>
        
        <tr>
            <td>
                Интеравал от минут, в который нужно также запускать задачу в сек.<br>
                <small>Если планировщик запускает задачу с интервалом больше 1 минуты, рекомендуется установить количество секунд, которые будут прибавлены к минутам запуска задачи. Это позволит не пропустить задачу в нужное время запуска</small>
            </td>
            <td>
                <input class="form-control" name="odmpro_update_csv_smart_exchange_link[max_interval_start_exchange]" value="<?php echo $odmpro_update_csv_smart_exchange_link['max_interval_start_exchange'] ?>" />
            </td>
        </tr>
        <tr>
            <td>
                Автоочищение лога по задачам, при достижении указанного количества записей
            </td>
            <td>
                <input class="form-control" name="odmpro_update_csv_smart_exchange_link[max_count_log_rows]" value="<?php echo $odmpro_update_csv_smart_exchange_link['max_count_log_rows'] ?>" />
            </td>
        </tr>
        
        
        
        
    </table>
    
</div>

    <script type="text/javascript"><!--
    
    $(document).ready(function() {
        
        setInterval(function() {
            getLastLogData();
        }, 5000);
        
        setInterval(function() {
            getSmartExchangeCheckConnect();
        }, 10000);
        
    });
    
//--></script> 