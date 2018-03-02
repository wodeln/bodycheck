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
							<li><a href="index.php?{x2;$_app}-master-discern">听诊套餐</a></li>
							<li class="active">添加听诊套餐</li>
						</ol>
					</div>
				</div>
				<div class="box itembox" style="padding-top:10px;margin-bottom:0px;">
					<h4 class="title" style="padding:10px;">
						添加听诊套餐
						<a class="btn btn-primary pull-right" href="index.php?sound-master-soundcase">听诊套餐管理</a>
					</h4>
					<form action="index.php?sound-master-soundcase-packageEdit" method="post" class="form-horizontal">
						<fieldset>
							<div class="form-group">
								<label for="basic" class="control-label col-sm-2">套餐名称</label>
								<div class="col-sm-6">
									<input class="form-control" id="discern_name" name="args[package_name]" type="text" value="{x2;$cases[package_name]}" needle="needle" msg="您必须输入套餐名称" />
								</div>
							</div>

                        <div class="form-group">
                            <label for="basicsubjectid" class="control-label col-sm-2"></label>
                            <div class="col-sm-4" style="width: 70%">

								<select style="margin: 0 20px 8px 0; width: 18%;" class="pull-left form-control combox" id="type">
									<option value="0">听诊病例</option>
									<!--<option value="1">鉴别听诊</option>-->
								</select>

								<select style="margin: 0 20px 8px 0; width: 18%;" class="pull-left form-control combox" id="case_type">
									<option value="">病例人群</option>
									<option value="0">儿童</option>
									<option value="1">成人</option>
									<option value="2">老人</option>
								</select>
								<select style="margin: 0 20px 8px 0; width: 18%;" class="pull-left form-control combox" id="organ_type">
									<option value="">病例分类</option>
									<option value="0">心音</option>
									<option value="1">呼吸音</option>
									<option value="2">肠鸣音</option>
								</select>
								<input class="form-control pull-left" style="width: 18%;margin: 0 20px 8px 0;" id="case_name" type="text" value="" placeholder="病例名称"/>
								<button class="btn btn-primary" id="getCase" type="button">搜索</button>
								<div class="row">
                                    <div class="col-xs-5">
                                        <select  name="from[]" id="undo_redo" class="form-control" size="13" multiple="multiple" style="height:274px;">

                                        </select>
                                    </div>

                                    <div class="col-xs-2">
                                        <button type="button" id="undo_redo_undo" class="btn btn-primary btn-block">撤销</button>
                                        <button type="button" id="undo_redo_rightAll" class="btn btn-default btn-block"><i class="glyphicon glyphicon-forward"></i></button>
                                        <button type="button" id="undo_redo_rightSelected" class="btn btn-default btn-block"><i class="glyphicon glyphicon-chevron-right"></i></button>
                                        <button type="button" id="undo_redo_leftSelected" class="btn btn-default btn-block"><i class="glyphicon glyphicon-chevron-left"></i></button>
                                        <button type="button" id="undo_redo_leftAll" class="btn btn-default btn-block"><i class="glyphicon glyphicon-backward"></i></button>
                                        <button type="button" id="undo_redo_redo" class="btn btn-warning btn-block">前进</button>
                                    </div>

                                    <div class="col-xs-5">
                                        <select name="to[]" id="undo_redo_to" class="form-control" size="13" needle="needle" msg="您必须鉴别听诊或标准化病例" multiple="multiple" style="height:275px;">
                                            {x2;tree:$selectCase,case,cid}
											<option value="{x2;v:case['case_id']}">{x2;v:case['case_name']}</option>
                                            {x2;endtree}
										</select>
                                    </div>
                                </div>
                            </div>
                        </div>
						<div class="form-group">
							<label for="basic" class="control-label col-sm-2">备注：</label>
							<div class="col-sm-9">
								<textarea class="form-control" rows="4" name="args[memo]" id="memo" autocomplete="off">{x2;$cases[memo]}</textarea>
							</div>
						</div>
						<div class="form-group">
							<label for="basic" class="control-label col-sm-2"></label>
							<div class="col-sm-9">
								<input type="hidden" class="btn btn-primary" name="page" value="{x2;$page}"/>
                                <button class="btn btn-primary" type="submit">提交</button>
								<input type="hidden" name="editPackage" value="1"/>
								<input type="hidden" name="soundcase_package_id" value="{x2;$cases[soundcase_package_id]}"/>
								{x2;tree:$search,arg,aid}
								<input type="hidden" name="search[{x2;v:key}]" value="{x2;v:arg}"/>
								{x2;endtree}
							</div>
						</div>
						</fieldset>
					</form>
				</div>
			</div>

            <script type="text/javascript" src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
            <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/prettify/r298/prettify.min.js"></script>
			<script src="app/core/styles/js/multiselect.min.js"></script>
			<script type="text/javascript">
                $(document).ready(function() {
                    window.prettyPrint && prettyPrint();
                    $('#undo_redo').multiselect();
                });

                function difference(a, b) { // 差集 a - b
                    //clone = a
//					console.log(b);
                    var clone = a.slice(0);
                    for(var i = 0; i < b.length; i ++) {
                        var temp = b[i].innerHTML;
                        for(var j = 0; j < clone.length; j ++) {
                            if(temp === clone[j]['case_name']) {
                                clone.splice(j,1);
                            }
                        }
                    }
                    return clone;
                }

                $('#getCase').click(function () {
					var case_type = $("#case_type").val();
					var organ_type  = $("#organ_type").val();
					var case_name = $("#case_name").val();
					var type = $("#type").val();
                    $.ajax({
                        type: 'post',
                        url: "index.php?sound-master-soundcase-getContent",
						data: {
							'case_type' : case_type,
							'organ_type' : organ_type,
							'case_name' : case_name,
							'type' : type
						},
                        success:function (data) {
                            var left = eval(data);
                            var right = $('#undo_redo_to option').slice(0);
                            var opt="";

                            var clone=difference(left,right);

                            $.each(clone, function(i, v) {
                                opt+="<option value='"+v['case_id']+","+type+"'>"+v['case_name']+"</option>";
                            });
                            $('#undo_redo').html(opt);
                        }
                    });
                });
			</script>
{x2;if:!$userhash}
		</div>
	</div>
</div>
{x2;include:footer}

</body>
</html>
{x2;endif}