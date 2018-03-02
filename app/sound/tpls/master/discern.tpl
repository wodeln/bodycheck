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
							<li><a href="index.php?{x2;$_app}-master-contents">鉴别听诊</a></li>
							<li class="active">{x2;$categories[$catid]['catname']}</li>
							{x2;else}
							<li class="active">鉴别听诊</li>
							{x2;endif}
						</ol>
					</div>
				</div>
				<div class="box itembox" style="padding-top:10px;margin-bottom:0px;overflow:visible">
					<h4 class="title" style="padding:10px;">
						鉴别听诊套餐管理
						<span class="pull-right">
							<a data-toggle="dropdown" class="btn btn-primary" href="#">添加鉴别听诊套餐 <strong class="caret"></strong></a>
							<ul class="dropdown-menu">
								<li><a href="index.php?sound-master-discern-add&organ_type=0&page={x2;$page}">添加心音鉴别听诊套餐</a></li>
								<li><a href="index.php?sound-master-discern-add&organ_type=1&page={x2;$page}">添加呼吸音鉴别听诊套餐</a></li>
							</ul>
						</span>
					</h4>
					<h4>{x2;if:$catid}{x2;$categories[$catid]['catname']}{x2;else}所有内容{x2;endif}</h4>
					<form action="index.php?sound-master-discern" method="post" class="form-inline">
						<table class="table">
					        <tr>
								<td>
									声音分类：
								</td>
								<td>
									<select name="search[organ_type]" class="form-control">
										<option value="">请选择声音分类</option>
										<option value="0">心音</option>
										<option value="1">呼吸音</option>
										<option value="2">肠鸣音</option>
									</select>
								</td>
								<td>关键字:</td>
								<td>
									<input class="form-control" name="search[discern_name]" size="15" type="text" value="{x2;$search['discern_name']}"/>
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
									<th>分类名称</th>
									<th>鉴别听诊音</th>
									<th>类型</th>
									<th>禁用</th>
									<th>操作</th>
								</tr>
							</thead>
							<tbody>
								{x2;tree:$discern['data'],case,cid}
								<tr>
									<td>{x2;v:case['discern_id']}</td>
									<td>{x2;v:case['discern_name']}</td>
									<td>{x2;v:case['case']}</td>
									<td>
										{x2;if:v:case['organ_type']==0}心音{x2;endif}
										{x2;if:v:case['organ_type']==1}呼吸音{x2;endif}
										{x2;if:v:case['organ_type']==2}肠鸣音{x2;endif}
									</td>
									<td align="center">
										<div class="switch switch-mini">
											<input class="ifuse" discern_id="{x2;v:case['discern_id']}" type="checkbox" {x2;if:v:case['if_use']==1}checked{x2;endif} name="mycheck">
										</div>
									</td>
									<td class="actions">
										<div class="btn-group">
											<a class="btn" href="index.php?sound-master-discern-discernedit&discern_id={x2;v:case['discern_id']}&organ_type={x2;v:case['organ_type']}&page={x2;$page}{x2;$u}" title="修改"><em class="glyphicon glyphicon-edit"></em></a>
										</div>
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
                    url : "index.php?sound-master-discern-chageState&state="+state+"&discern_id="+$(this).attr("discern_id"),
                    success : function (data) {
                    }
                });
            }
        });
    }
</script>
</html>
{x2;endif}