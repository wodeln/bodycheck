<?php
include_once("connect.php");
$action = $_GET['action'];
$id = (int)$_GET['id'];
switch($action){
	case 'add':
		addform();
		break;
	case 'edit':
		editform($id);
		break;
}

function addform(){
$date = $_GET['date'];
$enddate = $_GET['end'];
if($date==$enddate) $enddate='';
if(empty($enddate)){
	$display = 'style="display:none"';
	$enddate = $date;
	$chk = '';
}else{
	$display = 'style=""';
	$chk = 'checked';
}
$enddate = empty($_GET['end'])?$date:$_GET['end'];
?>
<link rel="stylesheet" type="text/css" href="css/jquery-ui.css">
<div class="fancy">
	<h3>新建事件</h3>
    <form id="add_form" action="do.php?action=add" method="post">
    <p>日程标题：<input type="text" class="input" name="event" id="event" style="width:320px" placeholder="记录你将要做的一件事..."></p>
    <p>日程内容：
        <select id="type" class="select_i" name="event_type">
            <option value="1">课件套餐</option>
            <option value="2">考试</option>
        </select>

        <input type="text" class="input" name="eventContent" id="eventContent" style="width:150px;">
        <input type="text" class="input" name="eventContentId" id="eventContentId" style="width:150px;display: none;">
    </p>
    <p>开始时间：<input type="text" class="input datepicker" name="startdate" id="startdate" value="<?php echo $date;?>" readonly>
    <span id="sel_start" style="display:none;">
        <select name="s_hour" class="select_i">
    	<option value="00">00</option>
        <option value="01">01</option>
        <option value="02">02</option>
        <option value="03">03</option>
        <option value="04">04</option>
        <option value="05">05</option>
        <option value="06">06</option>
        <option value="07">07</option>
        <option value="08" selected>08</option>
        <option value="09">09</option>
        <option value="10">10</option>
        <option value="11">11</option>
        <option value="12">12</option>
        <option value="13">13</option>
        <option value="14">14</option>
        <option value="15">15</option>
        <option value="16">16</option>
        <option value="17">17</option>
        <option value="18">18</option>
        <option value="19">19</option>
        <option value="20">20</option>
        <option value="21">21</option>
        <option value="22">22</option>
        <option value="23">23</option>
    </select>:
    <select name="s_minute" class="select_i">
    	<option value="00" selected>00</option>
        <option value="10">10</option>
        <option value="20">20</option>
        <option value="30">30</option>
        <option value="40">40</option>
        <option value="50">50</option>
    </select>
    </span>
    </p>
    <p id="p_endtime" <?php echo $display;?>>结束时间：<input type="text" class="input datepicker" name="enddate" id="enddate" value="<?php echo $enddate;?>" readonly>
    <span id="sel_end">
        <select name="e_hour" class="select_i">
    	<option value="00">00</option>
    	<option value="01">01</option>
        <option value="02">02</option>
        <option value="03">03</option>
        <option value="04">04</option>
        <option value="05">05</option>
        <option value="06">06</option>
        <option value="07">07</option>
        <option value="08">08</option>
        <option value="09">09</option>
        <option value="10">10</option>
        <option value="11">11</option>
        <option value="12" selected>12</option>
        <option value="13">13</option>
        <option value="14">14</option>
        <option value="15">15</option>
        <option value="16">16</option>
        <option value="17">17</option>
        <option value="18">18</option>
        <option value="19">19</option>
        <option value="20">20</option>
        <option value="21">21</option>
        <option value="22">22</option>
        <option value="23">23</option>
    </select>:
    <select name="e_minute" class="select_i">
    	<option value="00" selected>00</option>
        <option value="10">10</option>
        <option value="20">20</option>
        <option value="30">30</option>
        <option value="40">40</option>
        <option value="50">50</option>
    </select>
    </span>
    </p>
    <p>
    <label id="isallday"><input type="checkbox" value="1"  name="isallday" checked> 全天</label>
    <label id="isend" style="display: none;"><input type="checkbox" value="1"  name="isend" <?php echo $chk;?>> 结束时间</label>
    </p>
    <div class="sub_btn"><input type="submit" class="btn btn_ok" value="确定"> <input type="button" class="btn btn_cancel" value="取消" onClick="$.fancybox.close()"></div>
    </form>
</div>
<?php }

function editform($id){
	$query = mysql_query("select * from calendar where id='$id'");
	$row = mysql_fetch_array($query);
	if($row){
		$id = $row['id'];
		$title = $row['title'];
		$starttime = $row['starttime'];
		$start_d = date("Y-m-d",$starttime);
		$start_h = date("H",$starttime);
		$start_m = date("i",$starttime);
		$type = $row['event_type'];
		$eventContent = $row['event_content'];
		$eventContentId = $row['event_content_id'];

		$endtime = $row['endtime'];
		if($endtime==0){
			$end_d = $start_d;
			$end_chk = '';
			$end_display = "style='display:none'";
		}else{
			$end_d = date("Y-m-d",$endtime);
			$end_h = date("H",$endtime);
			$end_m = date("i",$endtime);
			$end_chk = "checked";
			$end_display = "style=''";
		}
		
		$allday = $row['allday'];
		if($allday==1){
			$display = "style='display:none'";
			$allday_chk = "checked";
		}else{
			$display = "style=''";
			$allday_chk = '';
		}
	}
?>
<link rel="stylesheet" type="text/css" href="css/jquery-ui.css">
<div class="fancy">
	<h3>编辑事件</h3>
    <form id="add_form" action="do.php?action=edit" method="post">
    <input type="hidden" name="id" id="eventid" value="<?php echo $id;?>">
        <p>日程标题：<input type="text" class="input" name="event" id="event" value="<?php echo $title; ?>" style="width:320px" placeholder="记录你将要做的一件事..."></p>
        <p>日程内容：
            <select id="type" class="select_i" name="event_type">
                <option <?php if ($type==1) echo "selected"; ?> value="1">课件套餐</option>
                <option <?php if ($type==2) echo "selected"; ?> value="2">考试</option>
            </select>

            <input type="text" class="input" name="eventContent" value="<?php echo $eventContent;?>" id="eventContent" style="width:150px;">
            <input type="text" class="input" name="eventContentId" value="<?php echo $eventContentId;?>" id="eventContentId" style="width:150px;display: none;">
        </p>
    <p>开始时间：<input type="text" class="input datepicker" name="startdate" id="startdate" value="<?php echo $start_d;?>" readonly>
    <span id="sel_start" <?php echo $display;?>>
        <select name="s_hour">
    	<option value="<?php echo $start_h;?>" selected><?php echo $start_h;?></option>
    	<option value="00">00</option>
        <option value="01">01</option>
        <option value="02">02</option>
        <option value="03">03</option>
        <option value="04">04</option>
        <option value="05">05</option>
        <option value="06">06</option>
        <option value="07">07</option>
        <option value="08">08</option>
        <option value="09">09</option>
        <option value="10">10</option>
        <option value="11">11</option>
        <option value="12">12</option>
        <option value="13">13</option>
        <option value="14">14</option>
        <option value="15">15</option>
        <option value="16">16</option>
        <option value="17">17</option>
        <option value="18">18</option>
        <option value="19">19</option>
        <option value="20">20</option>
        <option value="21">21</option>
        <option value="22">22</option>
        <option value="23">23</option>
    </select>:
    <select name="s_minute">
    	<option value="<?php echo $start_m;?>" selected><?php echo $start_m;?></option>
    	<option value="00">00</option>
        <option value="10">10</option>
        <option value="20">20</option>
        <option value="30">30</option>
        <option value="40">40</option>
        <option value="50">50</option>
    </select>
    </span>
    </p>
    <p id="p_endtime" <?php echo $end_display;?>>结束时间：<input type="text" class="input datepicker" name="enddate" id="enddate" value="<?php echo $end_d;?>" readonly>
    <span id="sel_end"  <?php echo $end_display;?>>
        <select name="e_hour">
    	<option value="<?php echo $end_h;?>" selected><?php echo $end_h;?></option>
    	<option value="00">00</option>
    	<option value="01">01</option>
        <option value="02">02</option>
        <option value="03">03</option>
        <option value="04">04</option>
        <option value="05">05</option>
        <option value="06">06</option>
        <option value="07">07</option>
        <option value="08">08</option>
        <option value="09">09</option>
        <option value="10">10</option>
        <option value="11">11</option>
        <option value="12">12</option>
        <option value="13">13</option>
        <option value="14">14</option>
        <option value="15">15</option>
        <option value="16">16</option>
        <option value="17">17</option>
        <option value="18">18</option>
        <option value="19">19</option>
        <option value="20">20</option>
        <option value="21">21</option>
        <option value="22">22</option>
        <option value="23">23</option>
    </select>:
    <select name="e_minute">
    	<option value="<?php echo $end_m;?>" selected><?php echo $end_m;?></option>
    	<option value="00">00</option>
        <option value="10">10</option>
        <option value="20">20</option>
        <option value="30">30</option>
        <option value="40">40</option>
        <option value="50">50</option>
    </select>
    </span>
    </p>
    <p>
    <label id="isallday"><input type="checkbox" value="1"  name="isallday" <?php echo $allday_chk;?>> 全天</label>
    <label id="isend"><input type="checkbox" value="1"  name="isend" <?php echo $end_chk;?>> 结束时间</label>
    </p>
    <div class="sub_btn"><span class="del"><input type="button" class="btn btn_del" id="del_event" value="删除"></span><input type="submit" class="btn btn_ok" value="确定"> <input type="button" class="btn btn_cancel" value="取消" onClick="$.fancybox.close()"></div>
    </form>
</div>
<?php }?>
<script type="text/javascript" src="js/jquery.form.min.js"></script>
<script type="text/javascript">

    $("#eventContent").autocomplete({
        source: function(request, response) {
            $.ajax({
                url: "content.php",
                dataType : "json",
                data:{
                    key: request.term,
                    type: $("#type").val()
                },
                success: function(data) {
                    response($.map(data, function(item) {
                     return item;
                    }));
                }
            });
        },
        focus: function( event, ui ) {
            $("#eventContentId").val(ui['item'].value1);
            $("#eventContent").val(ui['item'].label);
        },
        minLength: 0
    });

    $('#type').change(function () {
        $("#eventContent").val("");
        $("#eventContentId").val("");
    });

    jQuery(function($){
        $.datepicker.regional['zh-CN'] = {
            clearText: '清除',
            clearStatus: '清除已选日期',
            closeText: '关闭',
            closeStatus: '不改变当前选择',
            prevText: '上月',
            prevStatus: '显示上月',
            prevBigText: '<<',
            prevBigStatus: '显示上一年',
            nextText: '下月',
            nextStatus: '显示下月',
            nextBigText: '>>',
            nextBigStatus: '显示下一年',
            currentText: '今天',
            currentStatus: '显示本月',
            monthNames: ['一月','二月','三月','四月','五月','六月', '七月','八月','九月','十月','十一月','十二月'],
            monthNamesShort: ['一月','二月','三月','四月','五月','六月', '七月','八月','九月','十月','十一月','十二月'],
            monthStatus: '选择月份',
            yearStatus: '选择年份',
            weekHeader: '周',
            weekStatus: '年内周次',
            dayNames: ['星期日','星期一','星期二','星期三','星期四','星期五','星期六'],
            dayNamesShort: ['周日','周一','周二','周三','周四','周五','周六'],
            dayNamesMin: ['日','一','二','三','四','五','六'],
            dayStatus: '设置 DD 为一周起始',
            dateStatus: '选择 m月 d日, DD',
            dateFormat: 'yy-mm-dd',
            firstDay: 1,
            initStatus: '请选择日期',
            isRTL: false};
        $.datepicker.setDefaults($.datepicker.regional['zh-CN']);
    });
$(function(){
	$(".datepicker").datepicker({
        showButtonPanel: false,
        showOn: "both",
        buttonImageOnly: true,
        buttonText: ""
    });
	$("#isallday").click(function(){

        if($("#isallday > input").is(':checked')){
            $("#p_endtime").hide();
            $("#sel_start").hide();
            $("#isend").hide();
            $("#isend > input").attr('checked',false);
        }else {
            $("#sel_start").show();
            $("#isend").show();
        }

		/*if($("#sel_start").css("display")=="none"){
			$("#sel_start,#sel_end").show();
            $("#isend").show();
		}else{
			$("#sel_start,#sel_end").hide();
			$("#isend").hide();
		}*/
	});
	
	$("#isend").click(function(){
	    if($("#isend > input").is(':checked')){
            $("#p_endtime").show();
            $("#sel_start").show();
            $("#sel_end").show();
        }else {
	        if($("#isallday > input").is(':checked')){
                $("#p_endtime").hide();
                $("#sel_start").hide();
            }else {
                $("#p_endtime").hide();
            }
        }

		$.fancybox.resize();//调整高度自适应
	});
	
	//提交表单
	$('#add_form').ajaxForm({
		beforeSubmit: showRequest, //表单验证
        success: showResponse //成功返回
    }); 
	
	//删除事件
	$("#del_event").click(function(){
		if(confirm("您确定要删除吗？")){
			var eventid = $("#eventid").val();
			$.post("do.php?action=del",{id:eventid},function(msg){
				if(msg==1){//删除成功
					$.fancybox.close();
					$('#calendar').fullCalendar('refetchEvents'); //重新获取所有事件数据
				}else{
					alert(msg);	
				}
			});
		}
	});
});

function showRequest(){
	var events = $("#event").val();
	if(events==''){
		alert("请输入日程内容！");
		$("#event").focus();
		return false;
	}
}

function showResponse(responseText, statusText, xhr, $form){
	if(statusText=="success"){	
		if(responseText==1){
			$.fancybox.close();
			$('#calendar').fullCalendar('refetchEvents'); //重新获取所有事件数据
		}else{
			alert(responseText);
		}
	}else{
		alert(statusText);
	}
}
</script>