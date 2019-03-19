<?php
/*
in the name of God 
source of fot tabchi
by php 
v.3
dev : mohammadrezajafari [@mohammadrezajiji]
*/
ob_start();
error_reporting(0);
define('API_KEY','837707059:AAF5kdTKzJTAuvAsNQ738nzuzOX-znV-G3E');
//-----------------------------------------------------------------------------------------
//فانکشن jijibot :
function jijibot($method,$data){
  
  $url = "https://api.telegram.org/bot".API_KEY."/".$method;
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_POST, count($data));
  curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  $result = curl_exec($ch);
  curl_close($ch);
  return $result;
 }
//-----------------------------------------------------------------------------------------
//متغیر ها :
@$Dev = array("554796467"); // put id of admins
@$token = API_KEY;
//-----------------------------------------------------------------------------------------------
$update = json_decode(file_get_contents('php://input'));
@$message = $update->message;
@$from_id = $message->from->id;
@$chat_id = $message->chat->id;
@$message_id = $message->message_id;
@$first_name = $message->from->first_name;
@$last_name = $message->from->last_name;
@$username = $message->from->username;
@$textmassage = $message->text;
@$reply = $update->message->reply_to_message->chat->id;
@$forward_from_message_id = $update->message->reply_to_message->message_id;
//------------------------------------------------------------------------
@$tc = $update->message->chat->type;
//=======================================================================================
@$user = json_decode(file_get_contents("data/user.json"),true);
//=====================================================================================
if($textmassage=="/start"){
    jijibot('sendmessage',[
        "chat_id"=>$chat_id,
        "text"=>"ربات ارسال خودکار تبچی 
		
برنامه نویس : @Mohammadrezajiji",
    		]);
}
elseif($textmassage=="/setgp"){
if(in_array($from_id,$Dev) != false) {
    jijibot('sendmessage',[
        "chat_id"=>$chat_id,
        "text"=>"گروه با موفقیت به لیست گروه های زیر نظر ربات اضافه شد 
		
ایدی گروه شما : $chat_id",
    		]);
$user["gplist"][]="$chat_id";
$user = json_encode($user,true);
file_put_contents("data/user.json",$user);
}
}
elseif (strpos($textmassage , "/auto ") !== false) {
if(in_array($from_id,$Dev) != false) {
$get = str_replace('/auto ','',$textmassage);
    jijibot('sendmessage',[
        "chat_id"=>$chat_id,
        "text"=>"وضعیت ارسال پیام خودکار تغییر کرد !
		
وضعیت فعلی : $get",
    		]);
$user["auto"]="$get";
$user = json_encode($user,true);
file_put_contents("data/user.json",$user);
}
}
elseif($textmassage=="/setmsg"){
if(in_array($from_id,$Dev) != false) {
    jijibot('sendmessage',[
        "chat_id"=>$chat_id,
        "text"=>"بنر با موفیت به عنوان بنر پیش فرض تنظیم شد
		
ایدی پیام  : $forward_from_message_id
ایدی محل فوروارد شده  : $reply",
    		]);
$user["msgid"]="$forward_from_message_id";
$user["from"]="$reply";
$user = json_encode($user,true);
file_put_contents("data/user.json",$user);
}
}
elseif (strpos($textmassage , "/settime ") !== false) {
if(in_array($from_id,$Dev) != false) {
$get = str_replace('/settime ','',$textmassage);
    jijibot('sendmessage',[
        "chat_id"=>$chat_id,
        "text"=>"زمان بین ارسال هر بنر تغییر کرد !
		
فاصله بین هر بنر : $get دقیقه",
    		]);
date_default_timezone_set('Asia/Tehran');
$date1 = date("H:i:s");
$next_date = date('H:i:s', strtotime($date1 ."+$get Minutes"));
$user["time"]="$next_date";
$user["space"]="$get";
$user = json_encode($user,true);
file_put_contents("data/user.json",$user);
}
} 
date_default_timezone_set('Asia/Tehran');
$date1 = date("H:i:s");
if($date1 > $user["time"]){
if($user["auto"] == "on"){
$msg = $user["msgid"];
$from = $user["from"];
$get = $user["space"];
jijibot('ForwardMessage',[
'chat_id'=>$user["gplist"][0],
'from_chat_id'=>$from,
'message_id'=>$msg
]);
   jijibot('sendmessage',[
        "chat_id"=>$user["gplist"][0],
        "text"=>"#nfwd sgps 3",
		  'reply_to_message_id'=>$msg,
    		]);
$next_date = date('H:i:s', strtotime($date1 ."+$get Minutes"));
$user["time"]="$next_date";
$user = json_encode($user,true);
file_put_contents("data/user.json",$user);
    }
}
if($textmassage=="/help"){
if(in_array($from_id,$Dev) != false) {
    jijibot('sendmessage',[
        "chat_id"=>$chat_id,
        "text"=>"راهنمای ربات ارسال خودکار تبچی 
		
/setgp
تنظیم گروه به عنوان گروه های زیر نظر

/setmsg
تنظیم پیام به عنوان بنر پیش فرض

/auto [on | off]
روشن و خاموش کردن حالت ارسال خودکار

/settime [دقیقه]
تایین فاصله بین ارسال هر بنر بر حساب دقیقه",
    		]);
}
}
elseif(in_array($chat_id, $user["gplist"])) {
if ($tc == 'group' | $tc == 'supergroup'){
if($update->message->forward_from | $update->message->forward_from_chat){
if(in_array($from_id,$Dev)== true){
    jijibot('sendmessage',[
        "chat_id"=>$chat_id,
        "text"=>"ارسال به سوپرگروه",
		  'reply_to_message_id'=>$message_id,
    		]);
 }
}
}
}
?>
