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
							<li><a href="index.php?{x2;$_app}-master-questions-optionQuestion&page={x2;$page}{x2;$u}">操作试题管理</a></li>
							<li class="active">添加操作试题</li>
						</ol>
					</div>
				</div>
				<div class="box itembox" style="padding-top:10px;margin-bottom:0px;">
					<h4 class="title" style="padding:10px;">
						添加操作试题
						<a class="btn btn-primary pull-right" href="index.php?{x2;$_app}-master-questions-optionQuestion&page={x2;$page}{x2;$u}">操作试题管理</a>
					</h4>
					<form action="index.php?exam-master-questions-addOptQuestion" method="post" class="form-horizontal">
						<fieldset>
						<div class="form-group">
							<label class="control-label col-sm-2">选题：</label>
						  	<div class="col-sm-5">
								<div>选项A</div>
								<select name="A" id="item_a" select_id="{x2;$items[0]['case_id']}" class="form-control" needle="needle" msg="您必须为A选项选择一个病例声音">
								</select>

								<div>选项B</div>
								<select name="B" id="item_b" select_id="{x2;$items[1]['case_id']}" class="form-control" needle="needle" msg="您必须为B选项选择一个病例声音">
								</select>

								<div>选项C</div>
								<select name="C" id="item_c" select_id="{x2;$items[2]['case_id']}" class="form-control" needle="needle" msg="您必须为C选项选择一个病例声音">
								</select>

								<div>选项D</div>
								<select name="D" id="item_d" select_id="{x2;$items[3]['case_id']}" class="form-control" needle="needle" msg="您必须为D选项选择一个病例声音">
								</select>
							</div>
						</div>


						<div class="form-group">
							<label class="control-label col-sm-2">正确选项：</label>
							<div class="col-sm-2">
								<select needle="needle" msg="您必须为要添加的试题选择一个正确答案" name="args[right_item]" class="form-control">
									<option value=""></option>
									<option value="A">A</option>
									<option value="B">B</option>
									<option value="C">C</option>
									<option value="D">D</option>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-2">备注：</label>
						  	<div class="col-sm-10">
								<textarea id="memo" name="args[memo]" class="form-control" rows="4"></textarea>
							</div>
						</div>

						<div class="form-group">
						  	<label class="control-label col-sm-2"></label>
						  	<div class="col-sm-9">
							  	<button class="btn btn-primary" type="submit">提交</button>
							  	<input type="hidden" name="page" value="{x2;$page}"/>
							  	<input type="hidden" name="insertOptQuestion" value="1"/>
							  	<input type="hidden" name="args[organ_type]" value="4"/>
							  	{x2;tree:$search,arg,aid}
								<input type="hidden" name="search[{x2;v:key}]" value="{x2;v:arg}"/>
								{x2;endtree}
							</div>
						</div>
					</form>
				</div>
			</div>
{x2;if:!$userhash}
		</div>
	</div>
</div>
{x2;include:footer}
</body>
<script type="text/javascript">

    var rigth_answer="";
    var o_type = "";
    $(function () {
        rigth_answer = $("#right_answer").val();
        o_type = $('input:radio:checked').val();
        $("#select_right").find("option[value='"+rigth_answer+"']").attr("selected",true);
        $.ajax({
            type: 'post',
            url: "index.php?sound-master-discern-getCase",
            data: {
                'organ_type' : 0
            },
            success:function (data) {
                var left = eval(data);

                for(var i=0;i<5;i++){
                    var opt="<option value=''></option>";
                    var item = "item_"+String.fromCharCode(97+i);
                    var selectCaseId = $("#"+item).attr("select_id");
                    $.each(left, function(i, v) {
                        if(selectCaseId == v['case_id']){
                            opt+="<option value='"+v['case_id']+"' selected>"+v['case_name']+"</option>";
                        }else {
                            opt+="<option value='"+v['case_id']+"'>"+v['case_name']+"</option>";
                        }
                    });
                    $("#"+item).html(opt);
                }
            }
        });
    });

    $(function(){

        $(":radio").click(function(){
            var organ_type = $(this).val();
            if(organ_type==o_type){
                $("#select_right").find("option[value='"+rigth_answer+"']").attr("selected",true);
                $("#select_right").val(rigth_answer);
            }else {
                $("#select_right").find("option[value='"+rigth_answer+"']").attr("selected",false);
                $("#select_right").val("");

            }
            $.ajax({
                type: 'post',
                url: "index.php?sound-master-discern-getCase",
                data: {
                    'organ_type' : organ_type
                },
                success:function (data) {
                    var left = eval(data);
                    for(var i=0;i<5;i++) {
                        var opt="<option value=''></option>";
                        var item = "item_" + String.fromCharCode(97 + i);
                        var selectCaseId = $("#" + item).attr("select_id");
                        $.each(left, function (i, v) {
                            if(selectCaseId == v['case_id']){
                                opt+="<option value='"+v['case_id']+"' selected>"+v['case_name']+"</option>";
                            }else {
                                opt+="<option value='"+v['case_id']+"'>"+v['case_name']+"</option>";
                            }
                        });
                        $("#"+item).html(opt);
                    }
                }
            });
        });
    });

</script>
</html>
{x2;endif}