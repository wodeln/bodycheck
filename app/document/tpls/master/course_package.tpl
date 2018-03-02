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
							<li><a href="index.php?{x2;$_app}-master-contents">课件套餐</a></li>
							<li class="active">{x2;$categories[$catid]['catname']}</li>
							{x2;else}
							<li class="active">课件套餐</li>
							{x2;endif}
						</ol>
					</div>
				</div>
				<div class="box itembox" style="padding-top:10px;margin-bottom:0px;">
					<h4 class="title" style="padding:10px;">
						课件套餐管理
						<a class="btn btn-primary pull-right" href="index.php?document-master-attachtype-addPackage&page={x2;$page}">添加课件套餐</a>
					</h4>
					<h4>{x2;if:$course}{x2;$course['cstitle']}{x2;else}所有课件{x2;endif}</h4>
					<form action="index.php?document-master-attachtype-coursePackage" method="post" class="form-inline">
						<table class="table">
					        <tr>
								<td>
									课件套餐名称：<input name="search[course_package_name]" class="form-control" size="15" type="text" class="number" value="{x2;$search['course_package_name']}"/>
								</td>
								<td>

								</td>
								<td><button class="btn btn-primary" type="submit">提交</button></td>
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
					                    <!--<th width="80">课件</th>-->
										<th>心音听诊</th>
										<th>肺音听诊</th>
										<th>腹部触诊</th>
										<th>心电图</th>
					                    <th>听诊套餐</th>
								        <th>操作</th>
					                </tr>
					            </thead>
					            <tbody>
					            	{x2;tree:$packages['data'],content,cid}
					            	<tr>
					                    <td>{x2;v:content['course_package_id']}</td>
					                    <td>{x2;v:content['course_package_name']}</td>
					                    <td>{x2;v:content['count_heart']}</td>
					                    <td>{x2;v:content['count_lung']}</td>
					                    <td>{x2;v:content['count_touch']}</td>
					                    <td>{x2;v:content['count_cardiogram']}</td>
										<td>{x2;v:content['discern_name']}</td>
					                    <td class="actions">
					                    	<div class="btn-group">
					                    		<a class="btn" href="index.php?document-master-attachtype-editPackage&package_id={x2;v:content['course_package_id']}&page={x2;$page}{x2;$u}" title="修改"><em class="glyphicon glyphicon-edit"></em></a>
					                    		<a msg="删除后不能恢复，您确定要进行此操作吗？" class="btn confirm"  href="index.php?document-master-attachtype-delPackage&package_id={x2;v:content['course_package_id']}&page={x2;$page}{x2;$u}" title="删除"><em class="glyphicon glyphicon-remove"></em></a>
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
</html>
{x2;endif}