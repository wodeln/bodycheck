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
							<li><a href="index.php?{x2;$_app}-master-questions">试题管理</a></li>
							<li class="active">腹部触诊试题管理</li>
						</ol>
					</div>
				</div>
				<div class="box itembox" style="padding-top:10px;margin-bottom:0px;">
					<form action="index.php?exam-master-questions-batdel" method="post">
						<fieldset>
							<table class="table table-hover table-bordered">
								<thead>
									<tr class="info">
					                    <th>编号</th>
								        <th>类型</th>
										<th>正确</th>
								        <th>触诊模拟人症状</th>
								        <th>录入人</th>
								        <!--<th>操作</th>-->
					                </tr>
					            </thead>
					            <tbody>
				                    {x2;tree:$options['data'],option,qid}
							        <tr>
										<td>
											{x2;v:option['opt_question_id']}
										</td>
										<td>
                                            腹部触诊
										</td>
										<td>
                                            {x2;v:option['right_item']}
										</td>

                                        {x2;tree:v:option['item'],item,iid}
										<td>
											{x2;v:item['case_name']}
										</td>
                                        {x2;endtree}
										<td>
                                            {x2;v:option['add_name']}
										</td>

										<!--<td>
											<div class="btn-group">
					                    		<a class="btn" href="index.php?exam-master-questions-modifyOptQuestion&organ_type={x2;v:option['organ_type']}&page={x2;$page}&questionid={x2;v:option['opt_question_id']}{x2;$u}" title="修改"><em class="glyphicon glyphicon-edit"></em></a>
												<a class="btn confirm" href="index.php?exam-master-questions-delquestion&questionparent=0&page={x2;$page}&questionid={x2;v:question['questionid']}{x2;$u}" title="删除"><em class="glyphicon glyphicon-remove"></em></a>
					                    	</div>
										</td>-->
							        </tr>
							        {x2;endtree}
					        	</tbody>
					        </table>
					        <ul class="pagination pull-right">
					            {x2;$options['pages']}
					        </ul>
				        </fieldset>
					</form>
				</div>
			</div>
{x2;if:!$userhash}
		</div>
	</div>
</div>
<div id="modal" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button aria-hidden="true" class="close" type="button" data-dismiss="modal">×</button>
				<h4 id="myModalLabel">
					试题详情
				</h4>
			</div>
			<div class="modal-body"></div>
			<div class="modal-footer">
				 <button aria-hidden="true" class="btn btn-primary" data-dismiss="modal">关闭</button>
			</div>
		</div>
	</div>
</div>
{x2;include:footer}
</body>
<script type="text/javascript">
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
</script>
</html>
{x2;endif}