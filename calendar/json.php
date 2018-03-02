<?php
include_once('connect.php');//连接数据库
$user_id=$_SESSION['currentuser']["sessionuserid"];
$sql = "select * from calendar WHERE user_id=$user_id";
$query = mysql_query($sql);
while($row=mysql_fetch_array($query)){
	$allday = $row['allday'];
	$is_allday = $allday==1?true:false;
	
	$data[] = array(
		'id' => $row['id'],
		'title' => $row['title'],
		'start' => date('Y-m-d H:i',$row['starttime']),
		'end' => date('Y-m-d H:i',$row['endtime']),
		'allDay' => $is_allday,
		'color' => $row['color']
	);
}
echo json_encode($data);
?>