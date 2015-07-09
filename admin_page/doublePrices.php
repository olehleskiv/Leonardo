<?php
	$postdata = file_get_contents("php://input");
	$request = json_decode($postdata);
	$json = file_get_contents('assets/js/doublePrices.json');
		if(!$json) {
			echo "Неможливо відкрити файл на сервері!";
		}
	if (file_put_contents('assets/js/doublePrices.json', $postdata)){
		echo 'Ціни на Двокімнатний номер (3-4 місця) змінено! Перевірте на сайті';
		unset($postdata);
	};

?>