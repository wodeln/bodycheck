<?php
include_once('connect.php');//连接数据库

header('Content-Type:text/html;charset=UTF-8');

//为了方便举例，这里使用数组来模拟，你也可以在实际应用中从数据库中读取数据
//返回的数据最好是数组或对象类型的JSON格式字符串
$type = $_GET["type"];
$key = str_replace(" ","",$_GET["key"]);

/**
 *  1 听诊套餐
 *  2 考试
 */
$data="";

if($type==1){
    $sql = "SELECT course_package_id,course_package_name FROM x2_course_package WHERE course_package_name LIKE '%$key%'";
    $query = mysql_query($sql);
    while($row=mysql_fetch_array($query)){
        $data[] = array(
            'label' => $row['course_package_name'],
            'value1' => $row['course_package_id']
        );
    }
}elseif ($type==2){
    $sql = "SELECT basicid,`basic` FROM x2_basic WHERE basictype!=1 AND `basic` LIKE '%$key%'";
    $query = mysql_query($sql);
    while($row=mysql_fetch_array($query)){
        $data[] = array(
            'label' => $row['basic'],
            'value1' => $row['basicid']
        );
    }
}

if($data==""){
    $data[] = array(
        'value1' => -1,
        'label' => "没有您想要的结果"
    );
}

echo json_encode($data);
?>