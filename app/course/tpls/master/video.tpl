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
							<li><a href="index.php?{x2;$_app}-master-course">视频管理</a></li>
							<li class="active">{x2;$categories[$catid]['catname']}</li>
							{x2;else}
							<li class="active">视频管理</li>
							{x2;endif}
						</ol>
					</div>
				</div>
				<div class="box itembox" style="padding-top:0px;margin-bottom:0px;">
					<h4 class="title" style="padding:0px;">
						视频管理
						<a class="btn btn-primary pull-right" href="index.php?course-master-video-add&page={x2;$page}">添加视频</a>
					</h4>
					<form action="index.php?course-master-video" method="post" class="form-inline">
						<table class="table">
					        <tr>
								<td>
									视频名称：
								</td>
								<td>
									<input name="search[coursetitle]" class="form-control" size="15" type="text" value="{x2;$search['coursetitle']}"/>
								</td>
								<td>
									录入时间：
								</td>
								<td>
									<input class="form-control datetimepicker" data-date="{x2;date:TIME,'Y-m-d'}" data-date-format="yyyy-mm-dd" type="text" name="search[stime]" size="10" id="stime" value="{x2;$search['stime']}"/> - <input class="form-control datetimepicker" data-date="{x2;date:TIME,'Y-m-d'}" data-date-format="yyyy-mm-dd" size="10" type="text" name="search[etime]" id="etime" value="{x2;$search['etime']}"/>
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
					<form action="index.php?course-master-course-lite" method="post">
						<fieldset>
							<table class="table table-hover table-bordered">
								<thead>
									<tr class="info">
					                    <th>视频名称</th>
					                    <th>简介</th>
								        <th>录入时间</th>
								        <th>操作</th>
					                </tr>
					            </thead>
					            <tbody>
					            	{x2;tree:$videos['data'],video,vid}
					            	<tr>
					                    <td>
					                        {x2;v:video['coursetitle']}
					                    </td>
					                    <td>
					                    	{x2;v:video['coursedescribe']}
					                    </td>
					                    <td>
					                    	{x2;date:v:video['courseinputtime'],'Y-m-d'}
					                    </td>

					                    <td class="actions">
					                    	<div class="btn-group">
												<a class="btn" href="index.php?course-master-video-edit&courseid={x2;v:video['courseid']}&page={x2;$page}{x2;$u}" title="修改"><em class="glyphicon glyphicon-edit"></em></a>
												<a class="btn confirm" href="index.php?course-master-video-del&courseid={x2;v:video['courseid']}&page={x2;$page}{x2;$u}" title="删除"><em class="glyphicon glyphicon-remove"></em></a>
					                    	</div>
					                    </td>
					                </tr>
					                {x2;endtree}
					        	</tbody>
					        </table>

							<ul class="pagination pull-right">
								{x2;$courses['pages']}
							</ul>
						</fieldset>
					</form>
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