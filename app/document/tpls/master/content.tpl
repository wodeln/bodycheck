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
							<li><a href="index.php?{x2;$_app}-master-contents">自定义课件</a></li>
							<li class="active">{x2;$categories[$catid]['catname']}</li>
							{x2;else}
							<li class="active">自定义课件</li>
							{x2;endif}
						</ol>
					</div>
				</div>
				<div class="box itembox" style="padding-top:10px;margin-bottom:0px;">
					<h4 class="title" style="padding:10px;">
						课件管理
						<a class="btn btn-primary pull-right" href="index.php?document-master-files-addCourse&page={x2;$page}">添加自定义课件</a>
					</h4>
					<h4>{x2;if:$course}{x2;$course['cstitle']}{x2;else}所有课件{x2;endif}</h4>
					<form action="index.php?document-master-files-getCourseFile" method="post" class="form-inline">
						<table class="table">
					        <tr>
								<td>
									课件名称：
								</td>
								<td>
									<input name="search[course_name]" class="form-control" size="15" type="text" class="number" value="{x2;$search['course_name']}"/>
								</td>
								<td><button class="btn btn-primary" type="submit">提交</button></td>
							</tr>

						</table>
						<div class="input">
							<input type="hidden" value="1" name="search[argsmodel]" />
						</div>
					</form>
					<form action="index.php?course-master-contents-lite" method="post">
						<fieldset>
							<table class="table table-hover table-bordered">
								<thead>
									<tr class="info">
					                    <th width="80">编号</th>
					                    <th width="40">课件名称</th>
					                    <th width="80">PPT文件名称</th>
								        <th width="100">操作</th>
					                </tr>
					            </thead>
					            <tbody>
					            	{x2;tree:$course['data'],content,cid}
					            	<tr>
					                    <td>{x2;v:content['custom_course_id']}</td>
					                    <td>{x2;v:content['course_name']}</td>
					                    <td>
											<a target="_blank" href="ViewerJS/#../{x2;v:content['course_file']}">{x2;v:content['ppt_name']}</a>
					                    </td>
					                    <td class="actions">
					                    	<div class="btn-group">
					                    		<a class="btn" href="index.php?document-master-files-courseEdit&course_id={x2;v:content['custom_course_id']}&page={x2;$page}{x2;$u}" title="修改"><em class="glyphicon glyphicon-edit"></em></a>
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