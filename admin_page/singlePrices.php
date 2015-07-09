<?php
	$postdata = file_get_contents("php://input");
	$json = file_get_contents('assets/js/singlePrices.json');
		if(!$json) {
			echo "Неможливо відкрити файл на сервері!";
		}
	include "file_put_contents.php";
	file_put_contents('assets/js/singlePrices.json', $postdata);
	echo 'Ціни на Однокімнатний номер (2-3 місця) змінено! Перевірте на сайті!';
	unset($postdata);

?>