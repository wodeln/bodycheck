<?php
include_once('connect.php');
if(!$_SESSION['currentuser']["sessionuserid"]){
    header("Location:../index.php?user-app-login");
}
?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title>课程表</title>
<link rel="stylesheet" type="text/css" href="css/fullcalendar.css">
<link rel="stylesheet" type="text/css" href="css/fancybox.css">
<style type="text/css">
body { color: #51555c;  font: 12px/18px "Microsoft Yahei",Tahoma,Helvetica,Arial,Verdana,"宋体",sans-serif;}
#calendar{width:960px; margin:20px auto 10px auto}
.fancy{width:450px; height:auto}
.fancy h3{height:30px; line-height:30px; border-bottom:1px solid #d3d3d3; font-size:14px}
.fancy form{padding:10px}
.fancy p{height:28px; line-height:28px; padding:4px; color:#999}
.input{height:20px; line-height:20px; padding:2px; border:1px solid #d3d3d3; width:100px}
.select_i{height: 24px;line-height: 22px;border:1px solid #d3d3d3;}
.btn{-webkit-border-radius: 3px;-moz-border-radius:3px;padding:5px 12px; cursor:pointer}
.btn_ok{background: #360;border: 1px solid #390;color:#fff}
.btn_cancel{background:#f0f0f0;border: 1px solid #d3d3d3; color:#666 }
.btn_del{background:#f90;border: 1px solid #f80; color:#fff }
.sub_btn{height:32px; line-height:32px; padding-top:6px; border-top:1px solid #f0f0f0; text-align:right; position:relative}
.sub_btn .del{position:absolute; left:2px}
</style>
<script src='jquery-ui-1.10.3/jquery-1.9.1.js'></script>
<script src='jquery-ui-1.10.3/ui/jquery-ui.js'></script>
<script src='js/fullcalendar.js'></script>
<script src='js/jquery.fancybox-1.3.1.pack.js'></script>
<script type="text/javascript">
$(function() {
	$('#calendar').fullCalendar({
		header: {
			left: 'prev,next today',
			center: 'title',
			right: 'month,agendaWeek,agendaDay'
		},
		editable: true,
		firstDay:0,//每行第一天为周一
		dragOpacity: {
			agenda: .5,
			'':.6
		},
		eventDrop: function(event,dayDelta,minuteDelta,allDay,revertFunc) {
			$.post("do.php?action=drag",{id:event.id,daydiff:dayDelta,minudiff:minuteDelta,allday:allDay},function(msg){
				if(msg!=1){
					alert(msg);
					revertFunc();
				}
			});
    	},
		
		 eventResize: function(event,dayDelta,minuteDelta,revertFunc) {
			$.post("do.php?action=resize",{id:event.id,daydiff:dayDelta,minudiff:minuteDelta},function(msg){
				if(msg!=1){
					alert(msg);
					revertFunc();
				}
			});
    	},
		
		
		selectable: true,
		select: function( startDate, endDate, allDay, jsEvent, view ){
            return false;
			var start =$.fullCalendar.formatDate(startDate,'yyyy-MM-dd');
			var end =$.fullCalendar.formatDate(endDate,'yyyy-MM-dd');
			$.fancybox({
				'type':'ajax',
				'href':'event.php?action=add&date='+start+'&end='+end
			});
		},
		
		
		
		
		events: 'json.php',
		dayClick: function(date, allDay, jsEvent, view) {
			var selDate =$.fullCalendar.formatDate(date,'yyyy-MM-dd');
			$.fancybox({
				'type':'ajax',
				'href':'event.php?action=add&date='+selDate
			});
    	},
		eventClick: function(calEvent, jsEvent, view) {
			$.fancybox({
				'type':'ajax',
				'href':'event.php?action=edit&id='+calEvent.id
			});
		}
	});
	
});
</script>
</head>

<body>

<div id="main" style="width:1060px">
   <div id='calendar'></div>
</div>
</body>
</html>
