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
							<li><a href="index.php?{x2;$_app}-master-exams&page={x2;$page}{x2;$u}">试卷管理</a></li>
							<li class="active">手工组卷</li>
						</ol>
					</div>
				</div>
				<div class="box itembox" style="padding-top:10px;margin-bottom:0px;">
					<h4 class="title" style="padding:10px;">
						手工组卷
						<a class="btn btn-primary pull-right" href="index.php?{x2;$_app}-master-exams&page={x2;$page}{x2;$u}">试卷管理</a>
					</h4>
					<form action="index.php?exam-master-exams-selfpage" method="post" class="form-horizontal">
						<div class="form-group">
							<label class="control-label col-sm-2">试卷名称：</label>
							<div class="col-sm-4">
								<input class="form-control" type="text" name="args[exam]" value="" needle="needle" msg="您必须为该试卷输入一个名称"/>
							</div>
						</div>

						<input name="args[examdecide]" type="hidden" value="1"/>

						<!--<div class="form-group">
							<label class="control-label col-sm-2">考试时间：</label>
							<div class="col-sm-9 form-inline">
								<input class="form-control" type="text" name="args[examsetting][examtime]" value="60" size="4" needle="needle" class="inline"/> 分钟
							</div>
						</div> -->
						<div class="form-group">
							<label class="control-label col-sm-2">考试时间：</label>
							<div class="col-sm-9 form-inline">
								<input class="form-control" type="text" name="args[examsetting][examtime]" value="60" size="4" needle="needle" class="inline"/> 分钟
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-2">试卷总分：</label>
							<div class="col-sm-3">
								<input class="form-control" type="text" name="args[examsetting][score]" value="" size="4" needle="needle" msg="你要为本考卷设置总分" datatype="number"/>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-2">及格分数：</label>
							<div class="col-sm-3">
								<input class="form-control" type="text" name="args[examsetting][passscore]" value="" size="4" needle="needle" msg="你要为本考卷设置一个及格分数百分比" datatype="number"/>
							</div>
						</div>
						<input type="hidden" name="args[examsetting][questypelite][1]" value="1" class="questypepanelinput" id="panel_1" autocomplete="off">
						<div class="form-group">
							<label class="control-label col-sm-2">考卷分类：</label>
							<div class="col-sm-3">
								<select class="form-control combox" id="organ_type" name="args[organ_type]" needle="needle" msg="您必须选择一个分类">
									<option value="">请选择分类</option>
									<option value="1">心肺音听诊</option>
									<option value="2">腹部触诊</option>
									<option value="4">心电图识别</option>
								</select>
							</div>
						</div>

						<div class="form-group questpanel panel_{x2;v:questype['questid']}">
							<label class="control-label col-sm-2"></label>
							<div class="col-sm-9 form-inline">
								<input id="iselectallnumber" type="text" class="form-control" needle="needle" name="args[examsetting][questype][1][number]" value="" size="2" msg="您至少要选择一道题目"/>
								<span class="info">&nbsp;每题&nbsp;</span><input id="s_scroe" class="form-control" needle="needle" type="text" name="args[examsetting][questype][1][score]" value="" size="2" msg="您必须填写每题的分值"/>
								<span class="info">&nbsp;分，描述&nbsp;</span><input id="s_describe" class="form-control" type="text" name="args[examsetting][questype][1][describe]" value="" size="12"/>
								<span class="info">&nbsp;已选题数：<a id="ialreadyselectnumber_1">0</a>&nbsp;&nbsp;题</span>
								<span class="info">&nbsp;<a class="selfmodal btn btn-info"  href="javascript:;" data-target="#modal" url="index.php?exam-master-exams-selectedOpt&questionids={iselectquestions_1}&rowsquestionids={iselectrowsquestions_1}" valuefrom="iselectquestions_1|iselectrowsquestions_1" style="display: none;">看题</a></span>
								<span class="info">&nbsp;<a class="selfmodal btn btn-primary"  href="javascript:;" data-target="#modal" url="index.php?exam-master-exams-selectOptQuestions&search[organ_type]={organ_type}&questionids={iselectquestions_1}&useframe=1" valuefrom="iselectquestions_1|iselectrowsquestions_1|organ_type" style="display: none;">选题</a></span>
								<input type="hidden" value="" id="iselectquestions_1"  name="args[examquestions][1][questions]" />
								<input type="hidden" value="" id="iselectrowsquestions_1"  name="args[examquestions][1][rowsquestions]" />
							</div>
						</div>

						<div class="form-group">
							<label class="control-label col-sm-2"></label>
							<div class="col-sm-9">
								<button class="btn btn-primary" type="submit">提交</button>
                                <input type="hidden" name="args[question_type]" value="2">
								<input type="hidden" name="submitsetting" value="1"/>
							</div>
						</div>
					</form>
				</div>
			</div>
            {x2;if:!$userhash}
		</div>
	</div>
</div>
<div id="modal" class="modal fade">
	<div class="modal-dialog" style="min-width: 680px;">
		<div class="modal-content" style="min-width: 680px;">
			<div class="modal-header">
				<button aria-hidden="true" class="close" type="button" data-dismiss="modal">×</button>
				<h4 id="myModalLabel">
					试题详情
				</h4>
			</div>
			<div class="modal-body" id="modal-body"></div>
			<div class="modal-footer">
				<p><button aria-hidden="true" class="btn btn-primary" data-dismiss="modal">完成</button></p>
			</div>
		</div>
	</div>
</div>
<script>
    function loadsubjectsetting(obj)
    {
        $.getJSON('index.php?exam-master-basic-getsubjectquestype&subjectid='+$(obj).val()+'&'+Math.random(),function(data){$('.questpanel').hide();for(x in data){$('.panel_'+data[x]).show();}});
    }
    $("#organ_type").change(function () {
        $("#iselectquestions_1").val("");
        $("#iselectrowsquestions_1").val("");
        $("#ialreadyselectnumber_1").html("0");
        if($(this).val()!=""){
            $(".selfmodal").css("display","inline-block");
        }else {
            $(".selfmodal").css("display","none");
        }
       /* if($(this).val()==2){
			$("#iselectallnumber").attr("name","args[examsetting][questype][2][number]");
			$("#s_scroe").attr("name","args[examsetting][questype][2][score]");
			$("#iselectallnumber").attr("name","args[examsetting][questype][2][describe]");
			$("#iselectquestions_1").attr("name","args[examquestions][2][questions]");
			$("#iselectrowsquestions_1").attr("name","args[examquestions][2][rowsquestions]");
		}else {
            $("#iselectallnumber").attr("name","args[examsetting][questype][1][number]");
            $("#s_scroe").attr("name","args[examsetting][questype][1][score]");
            $("#iselectallnumber").attr("name","args[examsetting][questype][1][describe]");
            $("#iselectquestions_1").attr("name","args[examquestions][1][questions]");
            $("#iselectrowsquestions_1").attr("name","args[examquestions][1][rowsquestions]");
		}*/
    });
</script>
{x2;include:footer}
</body>
</html>
{x2;endif}