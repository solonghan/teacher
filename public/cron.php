<?php
	$ch = curl_init();
	// 設定擷取的URL網址
	curl_setopt($ch, CURLOPT_URL, "https://easchem.anbon.vip/util/cron");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);

	curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
	// 執行
	$r=curl_exec($ch);
	curl_close($ch);

?>