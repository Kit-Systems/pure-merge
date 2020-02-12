<?php

require('./avis.php');

$options = array(
	'login' => 'Web2',
	'password' => '231642200',
);
$client1 = new SoapClient('https://tc.avislogistics.kz/workbase/ws/WebServiceRemotePoint.1cws?wsdl', $options);

?> <h3>Тарифы</h3> <?php

$res1 = $client1->VernutSpisokVidovTarifov(array('Login' => 'Пьюр Бьюти', 'Password' => 'dc226569-7b5b-4993-b23c-558300c51b19'));
$tariffs = $res1->return->VidTarifa;
foreach($tariffs as $t){
	print_r($t);
	echo "<br>";
}

?> <h3>Схемы доставки</h3> <?php

$res2 = $client1->VernutSpisokSkhemDostavki(array('Login' => 'Пьюр Бьюти', 'Password' => 'dc226569-7b5b-4993-b23c-558300c51b19'));
$schemes = $res2->return->SkhemaDostavki;
foreach($schemes as $sch){
	print_r($sch);
	echo "<br>";
}

?> <h3>Содержимое</h3> <?php

$res3 = $client1->VernutSpisokSoderzhimoye(array('Login' => 'Пьюр Бьюти', 'Password' => 'dc226569-7b5b-4993-b23c-558300c51b19'));
$soderzh = $res3->return->Soderzhimoye;
foreach($soderzh as $sod){
	print_r($sod);
	echo "<br>";
}

?> <h3>Пошла заявка</h3> <?php

$almGuid = getGUID('Алматы');

/*$resF = $client1->SozdatCWB(array('Login' => 'Пьюр Бьюти', 'Password' => 'dc226569-7b5b-4993-b23c-558300c51b19',
	'InformatsiyaCWB' => array(

		'NomerCWB_Kliyent' => '12555fdsadf123',
		'VidTarifa_GUID' => 'd63c7d77-8ab3-11e5-80e5-0cc47a30d628',
		'SkhemaDostavki_GUID' => 'f79e5e87-5e33-11e8-a22f-002590877a24',
		'Soderzhimoye_GUID' => 'd63c7d7b-8ab3-11e5-80e5-0cc47a30d628',
		'KolichestvoMest' => 1,
		'VesObyem' => 0,
		'VesFakticheskiy' => 1,
		'NaimenovaniyePoluchatelya' => 'Антон Емцев',
		'KontaktnoyeLitsoPoluchatelya' => 'Антон Емцев',
							//AdresPoluchatelya_KodAT
							//Заполняется либо KodAT либо все реквизиты адресной точки
							//AdresPoluchatelya_Predstavleniye
							//Заполняется либо AdresPoluchatelya_Predstavleniye  либо все реквизиты адресной точки
		'AdresPoluchatelya_Gorod_GUID' => $almGuid,
		'AdresPoluchatelya_Indeks' => '050000',
		'AdresPoluchatelya_Ulitsa' => 'ул. Сатпаева',
		'AdresPoluchatelya_Dom' => '',
		'AdresPoluchatelya_Korpus' => '',
		'AdresPoluchatelya_KvartiraOfis' => '',
		'AdresPoluchatelya_Dopolnitelno' => '',
		'AdresPoluchatelya_Shirota' => '',
		'AdresPoluchatelya_Dolgota' => '',

		'TelefonyPoluchatelya' => '+7(701)7296615',

		'AdresOtpravitelya_Gorod_GUID' => $almGuid,
		'AdresOtpravitelya_Indeks' => '050000',
		'AdresOtpravitelya_Ulitsa' => 'пр. Назарбаева',
		'AdresOtpravitelya_Dom' => '137',
		'AdresOtpravitelya_Korpus' => '',
		'AdresOtpravitelya_KvartiraOfis' => '',
		'AdresOtpravitelya_Dopolnitelno' => '',
		'AdresOtpravitelya_Shirota' => '',
		'AdresOtpravitelya_Dolgota' => '',

		'TelefonyOtpravitelya' => '+7(707)7770159',
		'SummaNalozhennogoPlatezha' => '',
		'OpisaniyeSoderzhimogo' => 'soderzhimoe',
		'SpetsialnyyeInstruktsii' => ''


	)));

$kod_avis = $resF->return->NomerCWB_Avis;
echo $kod_avis . "<br>";
$kod_klient = $resF->return->NomerCWB_Kliyent;
echo $kod_klient . "<br>";
$errors = $resF->return->SpisokOshibok;
foreach($errors as $err){
	print_r($err);
	echo "<br>";
}*/

/*$resR = $client1->Tracing(array('Login' => 'Пьюр Бьюти', 'Password' => 'dc226569-7b5b-4993-b23c-558300c51b19', 'NomerCWB_Avis'=>'9700000021964', 'NomerCWB_Kliyent' => ''));
//$addresPol = $resR->return->AdresPoluchatelya;
$errorsR = $resR->return->SpisokOshibok;
foreach($errorsR as $errR){
	print_r($errR);
	echo "<br>";
}*/

/*$res35 = $client1->SozdatAdresnuyuTochku(array('Login' => 'Пьюр Бьюти', 'Password' => 'dc226569-7b5b-4993-b23c-558300c51b19',
						'AdresnayaTochka' => array(
						
							'Naimenovaniye' => 'PURE',
							'Kod' => 'PureBeautyAddressPoint',
							'Gorod_GUID' => $almGuid,
							'Indeks' => '',
							'Ulitsa' => 'Назарбаева',
							'Dom' => '137',
							'Korpus' => '',
							'KvartiraOfis' => '',
							'Telefony' => '+7(707)7770159;+7(701)2221304;+7(702)7459998;',
							'Dopolnitelno' => ''
						
						)));
$atch35 = $res35->return->SpisokOshibok;
foreach($atch35 as $at35){
	print_r($at35);
	echo "<br>";
}*/

$p1 = '95332';
$p2 = '+77776665544';
$p3 = '9 800 555 5353';

function phone_number($sPhone){
    $sPhone = preg_replace("[^0-9]",'',$sPhone);
    $sPhone = preg_replace("/\s+/",'',$sPhone);
    //if(strlen($sPhone) != 10) return(False);
    if (substr($sPhone, 0) == '+') {
    	$sSign = "+7";
    	$sArea = substr($sPhone, 2,3);
    	$sNumber = substr($sPhone,5,7);
    } else {
    	$sSign = "+7";
    	$sArea = substr($sPhone, 1,3);
    	$sNumber = substr($sPhone,4,7);
    }
    
    $sPhone = $sSign."(".$sArea.")".$sNumber;
    return($sPhone);
}
echo phone_number($p1) . "<br>";
echo phone_number($p2) . "<br>";
echo phone_number($p3) . "<br>";

$res4 = $client1->PoluchitSpisokAdresnykhTochek(array('Login' => 'Пьюр Бьюти', 'Password' => 'dc226569-7b5b-4993-b23c-558300c51b19'));
$atch = $res4->return->AdresnyyeTochki;
foreach($atch as $at){
	print_r($at);
	echo "<br>";
}


?>