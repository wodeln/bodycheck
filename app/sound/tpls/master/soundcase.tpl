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
							<li><a href="index.php?{x2;$_app}-master-contents">标准化病例</a></li>
							<li class="active">{x2;$categories[$catid]['catname']}</li>
							{x2;else}
							<li class="active">听诊病例</li>
							{x2;endif}
						</ol>
					</div>
				</div>
				<div class="box itembox" style="padding-top:10px;margin-bottom:0px;overflow:visible">
					<h4 class="title" style="padding:10px;">
						标准化病例

					</h4>
					<h4>{x2;if:$catid}{x2;$categories[$catid]['catname']}{x2;else}所有内容{x2;endif}</h4>
					<form action="index.php?sound-master-soundcase" method="post" class="form-inline">
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
									异常部位：
								</td>
								<td>
									<select name="search[organ_type]" class="form-control">
										<option value="">请选择异常部位</option>
										<option value="0">心音</option>
										<option value="1">呼吸音</option>
										<option value="2">肠鸣音</option>
									</select>
								</td>
								<td>病例名称:</td>
								<td>
									<input class="form-control" name="search[sound_case_name]" size="15" type="text" value="{x2;$search['sound_case_name']}"/>
								</td>

								<td>
									<button class="btn btn-primary" onclick="cc()" type="submit">提交</button>
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
									<th>听诊病例名称</th>
									<th>异常部位</th>
									<th>分类</th>
									<th>指定扩音试听</th>
									<th>震颤</th>
									<th>操作</th>
								</tr>
							</thead>
							<tbody>
								{x2;tree:$cases['data'],case,cid}
								<tr>
									<td>{x2;v:case['sound_case_id']}</td>
									<td>{x2;v:case['sound_case_name']}</td>
									<td>
										{x2;if:v:case['organ_type']==0}心音{x2;endif}
										{x2;if:v:case['organ_type']==1}呼吸音{x2;endif}
										{x2;if:v:case['organ_type']==2}肠鸣音{x2;endif}
									</td>
									<td>
                                        {x2;if:v:case['case_type']==0}儿童{x2;endif}
                                        {x2;if:v:case['case_type']==1}成人{x2;endif}
                                        {x2;if:v:case['case_type']==2}老人{x2;endif}
									</td>
									<td align="center">
										<div class="sound" onmouseover="play(this)" onmouseout="pause(this)" scid="{x2;v:case[sound_case_id]}"><audio src="{x2;v:case[sound_file]}" id="audio{x2;v:case[sound_case_id]}" loop="loop"/></div>
									</td>
									<td align="center">
                                        {x2;v:case['tremble']}
									</td>
									<td class="actions">
										<div class="switch switch-mini">
											<input class="ifuse" sound_case_id="{x2;v:case['sound_case_id']}" type="checkbox" {x2;if:v:case['if_use']==1}checked{x2;endif} name="mycheck">
										</div>
									</td>
								</tr>
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
        var scid = "audio"+$(obj).attr("scid");
        sound=document.getElementById(scid);
        sound.play();
    }

    function pause(obj){
        var scid = "audio"+$(obj).attr("scid");
        sound=document.getElementById(scid);
        sound.pause();
	}

    function cc() {
        setTimeout(load_switch,200);
    }

    $(window).load(load_switch);

    function load_switch() {
        $(".ifuse").bootstrapSwitch({
            onText:"启",
            offText:"禁",
            size:"mini",
            handleWidth: 25,
            onSwitchChange:function(event,state){
                $.ajax({
                    type : "get",
                    url : "index.php?sound-master-soundcase-chageState&state="+state+"&sound_case_id="+$(this).attr("sound_case_id"),
                    success : function (data) {
                    }
                });
            }
        });
    }

</script>
</html>
{x2;endif}