if($(window).width()<=1024){ var width  = ($(window).width() * 19 / 20); }
else{ var	width  = ($(window).width() * 3 / 4); }
if($(window).height()<1024){ var height = ($(window).height() * 14 / 20); }
else{ var height = 900; }
$(document).ready(function(){
	$('[name="shipping_method"]').removeAttr('checked');

	if (width < 1024) {
		$('#dpd-modal .dpd-modal-dialog').removeAttr('style');
	}
});

$(window).resize(function() {
	if($(window).width()<=1024){ var width  = ($(window).width() * 19 / 20); }
	else{ var	width  = ($(window).width() * 3 / 4); }
	if($(window).height()<1024){ var height = ($(window).height() * 14 / 20); }
	else{ var height = 900; }
	if (width < 1024) {
		$('#dpd-modal .dpd-modal-dialog').removeAttr('style');
	} else {
		$('#dpd-modal .dpd-modal-dialog').css('width', width + 'px');
	}
});

var dpdModel = '<div class="dpd-modal dpd-fade" id="dpd-modal" tabindex="-1" role="dialog">'
	+ '<div class="dpd-modal-dialog dpd-modal-lg">'
	+ '<div class="dpd-modal-content">'
	+ '<div class="dpd-modal-header">'
	+	'<button type="button" class="dpd-close" data-dismiss="modal" aria-label="Закрыть"><span aria-hidden="true">×</span></button>'
	+	'<h4 class="dpd-modal-title">Доставка DPD, пункты самовывоза</h4>'
	+ '</div>'
	+ '<div class="dpd-modal-body">'
	+ '<div id="dpd-map"></div>'
	+ '</div></div></div></div>';
	
var inited = false;

function load_term(){
	$('#open-pvz').click(function(){
		$('#open-pvz').button('loading');
			$('body').append(dpdModel);
			$.post('index.php?route=extension/shipping/dpd/getTerminals', {}).done(function(data) {
				
				$('#open-pvz').button('reset');
				if(data.error){
					alert(data.error);
				}else{
					$('#dpd-modal').modal('show');
					if (inited)	 {
						$('#dpd-map').dpdMap('reload', data)
						.on('dpd.map.terminal.select', function(e, terminal, widget) {
								$('.dpd-courier').append(' - ' + terminal.ADDRESS_FULL);
								$('[name="shipping_address[address_1]"], [name="address_1"]').val(terminal.ADDRESS_FULL);
								if($('input[name=address_same]').is(':checked')){
									$('[name="payment_address[address_1]"]').val(terminal.ADDRESS_FULL);
								}
								
								$.post('index.php?route=extension/shipping/dpd/save', { address: terminal.ADDRESS_FULL, id: terminal.CODE });
								
								$('.dpd-close').trigger('click');
						});
					} else {
						$('#dpd-map').dpdMap({}, data)
							.on('dpd.map.terminal.select', function(e, terminal, widget) {
								$('.dpd-courier').append(' - ' + terminal.ADDRESS_FULL);
								$('[name="shipping_address[address_1]"], [name="address_1"]').val(terminal.ADDRESS_FULL);
								if($('input[name=address_same]').is(':checked')){
									$('[name="payment_address[address_1]"]').val(terminal.ADDRESS_FULL);
								}
								
								$.post('index.php?route=extension/shipping/dpd/save', { address: terminal.ADDRESS_FULL, id: terminal.CODE });
								
								$('.dpd-close').trigger('click');
							});
						
						inited = true;
					}
				}
				$(document).delegate('.DPD-button', 'click', function() {
					$('.dpd-close').trigger('click');
				});
				/* действие при закрытии всплывающего окна */
				$('#dpd-modal').on('hidden.bs.modal', function(e) {
					$(this).remove();
					
					if(typeof(reloadAll) == 'function'){
						setTimeout(function() {
							reloadAll()
						}, 500);
					}
				});
			});
	});	
}

$(document).on('click', '[name="payment_method"]', function(){
	if(($("input[name='shipping_method']:checked").val() == 'dpd.dpdterminal') || ($("input[name='shipping_method']:checked").val() == 'dpd.dpddoor')){
		$.post('index.php?route=extension/shipping/dpd/checkPayment', { payment_method: $("input[name='payment_method']:checked").val()  }).done(function(data) {
			if(data.success){
				$('#remove-success').remove();
				
				$('#button-payment-method').button('loading');
				$('#button-guest-shipping').trigger('click');
				$('#button-shipping-address').trigger('click');
				setTimeout(function() {
					if(data.shipping == 'dpd.dpdterminal'){
						if(document.getElementById('hide-pvz')){
							$('.dpd-courier').trigger('click');
							document.getElementById('hide-pvz').style.display = 'block';
							
							load_term();
							
							if(data.message){
								$('<div id="remove-success" style="margin-top:10px;" class="col-sm-12"><div class="alert alert-info alert-dismissible fade in" role="alert"><strong>Выбранный ранее терминал не удовлетворяет условиям заказа!</strong></div></div>').insertBefore("#collapse-shipping-method");
								$('[name="shipping_address[address_1]"], [name="address_1"]').val('');
								$('[name="payment_address[address_1]"]').val('');
							}
						}
					}
				}, 1500);
				
			}
		});
	}
});

$(document).on('click', '[name="shipping_method"]', function(){
	
	shipping_method = $("input[name='shipping_method']:checked").val();
	
	if(typeof(reloadAll) == 'function'){
		reloadAll()
	}
	
	if(shipping_method == 'dpd.dpdterminal'){
		if(document.getElementById('hide-pvz')){
			document.getElementById('hide-pvz').style.display = 'block';
			load_term();
		}else if(document.getElementById('hide-pvz-simple')){
			document.getElementById('hide-pvz-simple').style.display = 'block';
		}
	}else{
		if(document.getElementById('hide-pvz')){
			document.getElementById('hide-pvz').style.display = 'none';
		}else if(document.getElementById('hide-pvz-simple')){
			document.getElementById('hide-pvz-simple').style.display = 'none';
		}
		
		$.post('index.php?route=extension/shipping/dpd/save', { address_off: 'yes' });
	}
});

$(document).on('click', '#open-pvz-simple', function(){
	$('#open-pvz-simple').button('loading');
	$('body').append(dpdModel);
	$.post('index.php?route=extension/shipping/dpd/getTerminals', {}).done(function(data) {
				
		$('#open-pvz-simple').button('reset');
		if(data.error){
			alert(data.error);
		}else{
			$('#dpd-modal').modal('show');
			if (inited)	 {
				$('#dpd-map').dpdMap('reload', data)
				.on('dpd.map.terminal.select', function(e, terminal, widget) {
						$('[name="shipping_address[address_1]"], [name="address_1"]').val(terminal.ADDRESS_FULL);
						if($('input[name=address_same]').is(':checked')){
							$('[name="payment_address[address_1]"]').val(terminal.ADDRESS_FULL);
						}
							
						$.post('index.php?route=extension/shipping/dpd/save', { address: terminal.ADDRESS_FULL, id: terminal.CODE });
								
						$('.dpd-close').trigger('click');
				});
			} else {
				$('#dpd-map').dpdMap({}, data)
					.on('dpd.map.terminal.select', function(e, terminal, widget) {
						$('[name="shipping_address[address_1]"], [name="address_1"]').val(terminal.ADDRESS_FULL);
						if($('input[name=address_same]').is(':checked')){
							$('[name="payment_address[address_1]"]').val(terminal.ADDRESS_FULL);
						}
								
						$.post('index.php?route=extension/shipping/dpd/save', { address: terminal.ADDRESS_FULL, id: terminal.CODE });
							
						$('.dpd-close').trigger('click');
					});
					
				inited = true;
			}
		}
		$(document).delegate('.DPD-button', 'click', function() {
			$('.dpd-close').trigger('click');
		});
		/* действие при закрытии всплывающего окна */
		$('#dpd-modal').on('hidden.bs.modal', function(e) {
			$(this).remove();
					
			if(typeof(reloadAll) == 'function'){
				setTimeout(function() {
					reloadAll()
				}, 500);
			}
		});
	});
});

$(document).on("keyup", '[name="city"], [name="address[city]"], [name="payment_address[city]"], [name="shipping_address[city]"], [name="register[city]"]', function() {
// City delivery
	$("#content .dropdown-menu").remove()
	
	$('input[name=\'city\'], input[name=\'shipping_address[city]\']').autocomplete({
		'source': function(request, response) {
		
			var fn = function(){
				$progress = $.ajax({
					url: 'index.php?route=extension/shipping/dpd/autocomplete&filter_name=' +  encodeURIComponent(request),
					dataType: 'json',
					success: function(json) {
						
						response($.map(json, function(item) {
								return {
									label: item['name'],
									value: item['city_id'],
									val:   item['value'],
									zone_id:   item['zone_id'],
									country_id:   item['country_id'],
								}
						}));
					},
				});
			};
			var interval = setTimeout(fn, 1000);
		},
		'select': function(item) {
			$('#input-payment-city').val(item['val']);
			$('#input-shipping-city').val(item['val']);
			$('#shipping_address_city').val(item['val']);
			$('#shipping_payment_city').val(item['val']);
			$('#input-city').val(item['val']);
			
			if(item['country_id']){
				/*$('#shipping_address_country_id').val(item['country_id']);
				var array = [];
				var i = 0;
				$('#input-payment-country option').each(function() {
					array[i] = $(this).val();
					i++;
				});
				
				if(array.length != 0){
					document.getElementById('input-payment-country').selectedIndex = array.indexOf(item['country_id']);
					document.getElementById('input-payment-country').dispatchEvent(new Event('change'));
				}*/
			}
			
			setTimeout(function() {
				/*if(item['zone_id']){
					$('#input-payment-zone').val(item['zone_id']);
					$('#input-shipping-zone').val(item['zone_id']);
				}*/
			}, 500);
			
			$.post("index.php?route=extension/shipping/dpd/save", {
				city_id: item['value'],
				country_id: item['country_id'],
				zone_id: item['zone_id']
			}).done(function(data) {		
				setTimeout(function() {
					console.log('sent');
					//reloadAll()
				}, 500);
			});
			
		}
	});
});
