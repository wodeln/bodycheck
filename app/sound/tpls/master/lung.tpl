{x2;if:!$userhash}
{x2;include:header}
<body>
{x2;include:nav}
<div class="container-fluid">
	<div class="row-fluid">
		<div class="main">
			<div class="col-xs-2" style="padding-top:10px;margin-bottom:0px;">
				{x2;include:menu}
			</div>
			<div class="col-xs-10" id="datacontent">
{x2;endif}
				<div class="box itembox" style="margin-bottom:0px;border-bottom:1px solid #CCCCCC;">
					<div class="col-xs-12">
						<ol class="breadcrumb">
							<li><a href="index.php?{x2;$_app}-master">{x2;$apps[$_app]['appname']}</a></li>
							{x2;if:$catid}
							<li><a href="index.php?{x2;$_app}-master-contents">自定义声音列表</a></li>
							<li class="active">{x2;$categories[$catid]['catname']}</li>
							{x2;else}
							<li class="active">自定义声音列表</li>
							{x2;endif}
						</ol>
					</div>
				</div>
				<div class="box itembox" style="padding-top:10px;margin-bottom:0px;overflow:visible">
					<h4 class="title" style="padding:10px;">
						自定义声音管理
						<span class="pull-right">
							<a data-toggle="dropdown" class="btn btn-primary" href="#">添加自定义声音 <strong class="caret"></strong></a>
							<ul class="dropdown-menu">
								<li><a href="index.php?sound-master-sound-customadd&type=0&page={x2;$page}">添加心音</a></li>
								<li><a href="index.php?sound-master-sound-customadd&type=1&page={x2;$page}">添加呼吸音</a></li>
								<li><a href="index.php?sound-master-sound-customadd&type=3&page={x2;$page}">添加肠鸣音</a></li>
							</ul>
						</span>
					</h4>
					<h4>{x2;if:$catid}{x2;$categories[$catid]['catname']}{x2;else}所有内容{x2;endif}</h4>
					<form action="index.php?sound-master-sound-lung" method="post" class="form-inline">
						<table class="table">
					        <tr>
								<td>
									人群：
								</td>
								<td>
									<select name="search[case_type]" class="form-control">
										<option value="">请选择人群分类</option>
										<option value="0">儿童</option>
										<option value="1">成人</option>
										<option value="2">老人</option>
									</select>
								</td>
								<td>
								</td>
								<td>
								</td>
								<td>病例名称:</td>
								<td>
									<input class="form-control" name="search[case_name]" size="15" type="text" value="{x2;$search['casename']}"/>
								</td>

								<td>
									<button class="btn btn-primary" type="submit">提交</button>
								</td>
					        </tr>

						</table>
						<div class="input">
							<input type="hidden" value="1" name="search[argsmodel]" />
						</div>
					</form>
					<fieldset>
						<table class="table table-hover table-bordered">
							<thead>
								<tr class="info">
									<th>编号</th>
									<th>病例名称</th>
									<th>音源文件</th>
									<th>类型</th>
									<th>部位</th>
									<th>分类</th>
									<th>试听</th>
									<th>操作</th>
								</tr>
							</thead>
							<tbody>
								{x2;tree:$cases['data'],case,cid}
								<tr>
									<td>{x2;v:case['case_id']}</td>
									<td>{x2;v:case['case_name']}</td>
									<td>{x2;v:case['sound_name']}</td>
									<td>
										{x2;if:v:case['organ_type']==0}心音{x2;endif}
										{x2;if:v:case['organ_type']==1}呼吸音{x2;endif}
										{x2;if:v:case['organ_type']==2}肠鸣音{x2;endif}
									</td>
									<td>
										<span class="pic" onmouseover="show(this)" onmouseout="hidd(this)" cid="{x2;v:case['case_id']}" sid="{x2;v:cid}">滑动</span>
									</td>
									<td>
                                        {x2;if:v:case['case_type']==0}儿童{x2;endif}
                                        {x2;if:v:case['case_type']==1}成人{x2;endif}
                                        {x2;if:v:case['case_type']==2}老人{x2;endif}
									</td>
									<td align="center">
										<div class="sound" onmouseover="play(this)" onmouseout="pause(this)" cid="{x2;v:case[case_id]}"><audio src="{x2;v:case[sound_file]}" id="audio{x2;v:case[case_id]}" loop="loop"/></div>
									</td>
									<td class="actions">
										<div class="btn-group">
											<!--<a class="btn" href="{x2;if:v:case['organ_type']==0}index.php?sound-master-sound-heartedit{x2;endif}{x2;if:v:case['organ_type']==1}index.php?sound-master-sound-lungedit{x2;endif}&case_id={x2;v:case['case_id']}&page={x2;$page}{x2;$u}" title="修改"><em class="glyphicon glyphicon-edit"></em></a>
											<a class="btn confirm" href="index.php?sound-master-sound-del&case_id={x2;v:case['case_id']}&page={x2;$page}{x2;$u}" title="删除"><em class="glyphicon glyphicon-remove"></em></a>-->
										</div>
									</td>
								</tr>
								<div style="position:absolute;top:0;display:none;z-index:1;height: 360px;" id="position{x2;v:case['case_id']}" class="{x2;if:v:case['organ_type']==0}heart_bak_img{x2;endif}{x2;if:v:case['organ_type']==1}lung_bak_img{x2;endif}">
                                    {x2;tree:v:case['positions'],position,pid}
									<img style="display: block;" src="/app/core/styles/img/point_img" class="{x2;if:v:case['organ_type']==0}heartpoint{x2;endif}{x2;if:v:case['organ_type']==1}lungPoint{x2;endif}{x2;v:position['play_position']}">
                                    {x2;endtree}
								</div>
								{x2;endtree}
							</tbody>
						</table>

						<ul class="pagination pull-right">
							{x2;$cases['pages']}
						</ul>
					</fieldset>
				</div>
			</div>
{x2;if:!$userhash}
		</div>
	</div>
</div>
{x2;include:footer}

</body>
<script type="text/javascript">
	var sound = ""
//	$(".sound").mouseover(function () {
//	    var cid = "audio"+$(this).attr("cid");
//	    console.log(cid);
//		sound=document.getElementById(cid);
//        sound.play();
//    });
	
	function play(obj) {
        var cid = "audio"+$(obj).attr("cid");
        sound=document.getElementById(cid);
        sound.play();
    }

    function pause(obj){
        var cid = "audio"+$(obj).attr("cid");
        sound=document.getElementById(cid);
        sound.pause();
	}

	function show(obj,event) {
        var e = event || window.event;
        var x = e.clientX, y = e.clientY;
        var position = "position" + $(obj).attr("cid");
        var py = -115 + $(obj).attr("sid")*50;
        var px = x-$("#"+position).width()/2-300;
        $("#"+position).css(
            {
                "left" : px + "px",
                "top" : py + "px"
            }
        );
        $("#"+position).show();
    }

    function hidd(obj) {
        var position = "position" + $(obj).attr("cid");
        $("#"+position).hide();
    }

</script>
</html>
{x2;endif}