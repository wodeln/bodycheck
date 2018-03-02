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
							<li class="active">考试地点</li>
						</ol>
					</div>
				</div>
				<div class="box itembox" style="padding-top:10px;margin-bottom:0px;">
					<h4 class="title" style="padding:10px;">
						考试地点
						<a class="btn btn-primary pull-right" href="index.php?exam-master-basic-addplace">添加地点</a>
					</h4>
			        <form action="index.php?exam-master-basic-place" method="post" class="form-inline">
						<table class="table">
							<thead>
								<tr>
									<th colspan="5">搜索</th>
								</tr>
							</thead>
							<tr>
								<td>
									考试区域：
								</td>
								<td class="input">
									<input name="search[place]" class="form-control" size="25" type="text" value="{x2;$search['place']}"/>
								</td>
								<td>
								</td>
								<td class="input">
								</td>
								<td>
									<button class="btn btn-primary" type="submit">搜索</button>
									<input type="hidden" value="1" name="search[argsmodel]" />
								</td>
							</tr>
						</table>
					</form>
			        <table class="table table-hover table-bordered">
						<thead>
							<tr class="info">
			                    <th>ID</th>
						        <th>考试区域</th>
						        <th>考试地点</th>
								<th>操作</th>
			                </tr>
			            </thead>
			            <tbody>
			            	{x2;tree:$place['data'],place,pid}
			            	<tr>
			                    <td>{x2;v:place['placeid']}</td>
			                    <td>{x2;v:place['place']}</td>
								<td>{x2;v:place['address']}</td>
								<td>
									<a class="btn" href="index.php?exam-master-basic-modifyplace&page={x2;$page}&placeid={x2;v:place['placeid']}{x2;$u}" title="修改"><em class="glyphicon glyphglyphicon glyphicon-edit"></em></a>
                                    <a class="btn confirm" href="index.php?exam-master-basic-delplace&placeid={x2;v:place['placeid']}&page={x2;$page}{x2;$u}" title="删除"><em class="glyphicon glyphglyphicon glyphicon-remove"></em></a>
								</td>
			                </tr>
			                {x2;endtree}
			        	</tbody>
			        </table>
			        <ul class="pagination pull-right">
			            {x2;$users['pages']}
			        </ul>
				</div>
			</div>
{x2;if:!$userhash}
		</div>
	</div>
</div>
{x2;include:footer}
</body>
</html>
{x2;endif}