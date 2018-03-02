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
							<li class="active">操作试题管理</li>
						</ol>
					</div>
				</div>
				<div class="box itembox" style="padding-top:10px;margin-bottom:0px;">
					<h4 class="title" style="padding:10px;">
						操作试题管理
						<span class="pull-right">
							<a data-toggle="dropdown" class="btn btn-primary" href="#">添加操作试题 <strong class="caret"></strong></a>
							<ul class="dropdown-menu">
								<li><a href="index.php?{x2;$_app}-master-questions-addOptQuestion&page={x2;$page}{x2;$u}">心肺音操作试题</a></li>
								<li><a href="index.php?{x2;$_app}-master-questions-addOptQuestionCardiogram&page={x2;$page}{x2;$u}">心电图操作试题</a></li>
							</ul>
						</span>
					</h4>
					<form action="index.php?exam-master-questions-optionQuestion" method="post" class="form-inline">
						<table class="table">

					        <tr>
								<td>
									题目分类：
									<select name="search[organ_type]" class="combox form-control">
										<option value="">选择分类</option>
										<option value="0">心音</option>
										<option value="1">呼吸音</option>
										<option value="4">心电图</option>
									</select>

								</td>
								<td>
									<button class="btn btn-primary" type="submit">搜索</button>
									<input type="hidden" value="1" name="search[argsmodel]" />
					        	</td>
					        	<td>

								</td>
								<td>

					        	</td>
					        	<td>

								</td>
								<td>

					        	</td>
							</tr>
						</table>
					</form>
					<form action="index.php?exam-master-questions-batdel" method="post">
						<fieldset>
							<table class="table table-hover table-bordered">
								<thead>
									<tr class="info">
					                    <th>编号</th>
								        <th>类型</th>
										<th>正确</th>
								        <th>声音内容</th>
								        <th>录入人</th>
								        <th>操作</th>
					                </tr>
					            </thead>
					            <tbody>
				                    {x2;tree:$options['data'],option,qid}
							        <tr>
										<td>
											{x2;v:option['opt_question_id']}
										</td>
										<td>
                                            {x2;if:v:option['organ_type']==0}心音{x2;endif}
                                            {x2;if:v:option['organ_type']==1}呼吸音{x2;endif}
                                            {x2;if:v:option['organ_type']==4}心电图{x2;endif}
										</td>
										<td>
                                            {x2;v:option['right_item']}
										</td>
										<td>
                                            {x2;tree:v:option['item'],item,iid}
											<a cid="{x2;v:item[case_id]}" href="javascript:;"  onmouseover="play(this)" onmouseout="pause(this)"  >{x2;v:item['item_title']}.{x2;v:item['case_name']}&nbsp;</a>
											<audio src="{x2;v:item[sound_file]}" id="audio{x2;v:item[case_id]}" loop="loop"></audio>
											{x2;endtree}

										</td>
										<td>

										</td>

										<td>
											<div class="btn-group">
					                    		<a class="btn" href="index.php?exam-master-questions-modifyOptQuestion&organ_type={x2;v:option['organ_type']}&page={x2;$page}&questionid={x2;v:option['opt_question_id']}{x2;$u}" title="修改"><em class="glyphicon glyphicon-edit"></em></a>
												<!--<a class="btn confirm" href="index.php?exam-master-questions-delquestion&questionparent=0&page={x2;$page}&questionid={x2;v:question['questionid']}{x2;$u}" title="删除"><em class="glyphicon glyphicon-remove"></em></a>-->
					                    	</div>
										</td>
							        </tr>
							        {x2;endtree}
					        	</tbody>
					        </table>
					        <ul class="pagination pull-right">
					            {x2;$questions['pages']}
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