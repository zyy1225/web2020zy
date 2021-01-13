<?php
date_default_timezone_set('PRC');
$start_time = '09:00:00';
$end_time = '18:00:00';
$start_famate_time = strtotime($start_time);//开始时间转化为时间戳
$end_famate_time = strtotime($end_time); //结束时间转化为时间戳
$now_time = time();
if($end_famate_time < $now_time || $start_time > $now_time){
 echo '当前不在考试的时间范围内！';
 exit;
}
$remain_time = $end_famate_time-$now_time; //剩余的秒数
$remain_hour = floor($remain_time/(60*60)); //剩余的小时
$remain_minute = floor(($remain_time - $remain_hour*60*60)/60); //剩余的分钟数
$remain_second = ($remain_time - $remain_hour*60*60 - $remain_minute*60); //剩余的秒数
echo json_encode(array('hour'=>$remain_hour,'minute'=>$remain_minute,'second'=>$remain_second));

?>