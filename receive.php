<?php
 $json_str = file_get_contents('php://input'); //接收REQUEST的BODY
 $json_obj = json_decode($json_str); //轉JSON格式
 //產生回傳給line server的格式
 $sender_userid = $json_obj->events[0]->source->userId;
 $sender_txt = $json_obj->events[0]->message->text;
 $sender_replyToken = $json_obj->events[0]->replyToken;
 
 
 $myfile = fopen("log.txt","w+") or die("Unable to open file!"); //設定一個log.txt 用來印訊息
 fwrite($myfile, "\xEF\xBB\xBF".$json_str); //在字串前加入\xEF\xBB\xBF轉成utf8格式
 fclose($myfile);


$call_line_api = "https://api.line.me/v2/bot/message/push";
$response = array ();

 if($sender_txt == "reply"){
 	$call_line_api = "https://api.line.me/v2/bot/message/reply";
 	$response = array (
				"replyToken" => $sender_replyToken,
				"messages" => array (
					array (
						"type" => "text",
						"text" => "Hello, 你說: ".$sender_txt
					)
				)
		);
 }else if($sender_txt == "image"){
 	$call_line_api = "https://api.line.me/v2/bot/message/reply";
 	$response = array (
				"replyToken" => $sender_replyToken,
				"messages" => array (
					array (
						"type" => "image",
						"originalContentUrl" => "https://www.w3schools.com/css/paris.jpg",
						"previewImageUrl" => "https://www.nasa.gov/sites/default/themes/NASAPortal/images/feed.png"
					)
				)
			);
 }else if($sender_txt == "location"){
 	$call_line_api = "https://api.line.me/v2/bot/message/reply";
 	$response = array (
				"replyToken" => $sender_replyToken,
				"messages" => array (
					array (
						"type" => "location",
						"title" => "my location",
						"address" => "〒150-0002 東京都渋谷区渋谷２丁目２１−１",
            					"latitude" => 35.65910807942215,
						"longitude" => 139.70372892916203
					)
				)
			);
 }else if($sender_txt == "sticker"){
 	$call_line_api = "https://api.line.me/v2/bot/message/reply";
 	 $response = array (
				"replyToken" => $sender_replyToken,
				"messages" => array (
					array (
						"type" => "sticker",
						"packageId" => "1",
						"stickerId" => "1"
					)
				)
			);
 }else if($sender_txt == "sing"){
 	$call_line_api = "https://api.line.me/v2/bot/message/reply";
 	 $response = array (
				"replyToken" => $sender_replyToken,
				"messages" => array (
					array (
						"type" => "sticker",
						"packageId" => "1",
						"stickerId" => "11"
					)
				)
			);
 }


 //回傳給line server
 $header[] = "Content-Type: application/json";
 $header[] = "Authorization: Bearer tlHi3kV5jSGfrByoaLylhkMRZMc8zwOxTu7mwLsYLLmt2mL8/od0I1KDkhDzXuuu4Ccz99zLvRmXqg88Xx8WXU1CBAE774abD/u3x2WwgRc7Th7uCUDpE7tPrJHzBzFmFwaGpuFjE43QPPXFu/GUIwdB04t89/1O/w1cDnyilFU=";
 $ch = curl_init($call_line_api);                                                                      
 curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
 curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($response));                                                                  
 curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
 curl_setopt($ch, CURLOPT_HTTPHEADER, $header);                                                                                                   
 $result = curl_exec($ch);
 curl_close($ch); 

 fwrite($myfile, $result); 
 fclose($myfile);
?>
