<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
 $arComponentParameters = array(
	"GROUPS" => array(),
	"PARAMETERS" => array(
		
		"DISPLAY_TEMPERATURE_TYPE" => Array(
			"PARENT" => "VISUAL",
			"TYPE" => "LIST",
			"NAME" => "Отображать температуру в градусах",
			"VALUES" => Array(
				"C" => "Цельсия",
				"F" => "Фаренгейта",
			),
			"DEFAULT" => "C",
		),
	),
);
?>