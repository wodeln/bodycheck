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
							<li><a href="index.php?{x2;$_app}-master-contents">听诊套餐</a></li>
							<li class="active">{x2;$categories[$catid]['catname']}</li>
							{x2;else}
							<li class="active">听诊套餐</li>
							{x2;endif}
						</ol>
					</div>
				</div>
				<div class="box itembox" style="padding-top:10px;margin-bottom:0px;overflow:visible">
					<h4 class="title" style="padding:10px;">
						听诊套餐
						<span class="pull-right">
							<a class="btn btn-primary" href="index.php?sound-master-soundcase-packageAdd">添加听诊套餐</a>
						</span>
					</h4>
					<h4>{x2;if:$catid}{x2;$categories[$catid]['catname']}{x2;else}所有内容{x2;endif}</h4>
					<form action="index.php?sound-master-soundcase-soundCasePackage" method="post" class="form-inline">
						<table class="table">
					        <tr>

								<td>套餐名称：<input class="form-control" name="search[package_name]" size="15" type="text" value="{x2;$search['package_name']}"/></td>

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
									<th>套餐名称</th>
									<!--<th>听诊病例</th>-->
									<!--<th>鉴别听诊</th>-->
									<th>心音病例</th>
									<th>肺音病例</th>
									<th>肠鸣音病例</th>
									<th>创建人</th>
									<th>创建时间</th>
									<th>操作</th>
								</tr>
							</thead>
							<tbody>
								{x2;tree:$cases['data'],case,cid}
								<tr>
									<td>{x2;v:case['soundcase_package_id']}</td>
									<td>{x2;v:case['package_name']}</td>
									<td>{x2;v:case['count_heart']}</td>
									<td>{x2;v:case['count_lung']}</td>
									<td>{x2;v:case['count_chang']}</td>
									<!--<<td>{x2;v:case['sound_case']}</td>-->
									<!--<td>{x2;v:case['discern']}</td>-->
									<td align="center">
                                        {x2;v:case['add_name']}
									</td>
									<td>{x2;date:v:case['add_time'],'Y-m-d'}</td>
									<td class="actions">
										<a class="btn" href="index.php?sound-master-soundcase-packageEdit&soundcase_package_id={x2;v:case['soundcase_package_id']}&page={x2;$page}{x2;$u}" title="修改"><em class="glyphicon glyphicon-edit"></em></a>
										<a msg="删除后不能恢复，您确定要进行此操作吗？" class="btn confirm"  href="index.php?sound-master-soundcase-packageDel&soundcase_package_id={x2;v:case['soundcase_package_id']}&page={x2;$page}{x2;$u}" title="删除"><em class="glyphicon glyphicon-remove"></em></a>
									</td>
								</tr>
								{x2;endtree}
							</tbody>
						</table>

						<ul class="pagination pull-right">
							{x2;$contents['pages']}
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