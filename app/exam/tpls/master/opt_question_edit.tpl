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
							<li><a href="index.php?{x2;$_app}-master-questions-heartLungQuestion&page={x2;$page}{x2;$u}">听诊试题管理</a></li>
							<li class="active">添加操作试题</li>
						</ol>
					</div>
				</div>
				<div class="box itembox" style="padding-top:10px;margin-bottom:0px;">
					<h4 class="title" style="padding:10px;">
						添加操作试题
						<a class="btn btn-primary pull-right" href="index.php?{x2;$_app}-master-questions-heartLungQuestion&page={x2;$page}{x2;$u}">听诊试题管理</a>
					</h4>
					<form action="index.php?exam-master-questions-modifyOptQuestion" method="post" class="form-horizontal">
						<fieldset>

						<div class="form-group">
							<label class="control-label col-sm-2">听诊部位：</label>
							<div class="col-sm-5">
								<input name="args[organ_type]" type="radio" value="0" {x2;if:$optQuestion['organ_type']==0}checked{x2;endif} />心音听诊题
								&nbsp;&nbsp;&nbsp;&nbsp;<input name="args[organ_type]" type="radio" value="1" {x2;if:$optQuestion['organ_type']==1}checked{x2;endif} />肺音听诊题
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-2">选题：</label>
						  	<div class="col-sm-5">
								<div>选项A</div>
								<!--<select name="A" id="item_a" class="form-control" needle="needle" msg="您必须为A选项选择一个病例声音">
								</select>-->
								<input class="form-control" id="item_a" name="t_A" type="text" value="{x2;$items[0]['item_content']}" needle="needle" msg="您必须为A选项添加一个病例声音"/>
								<input class="form-control ii" id="item_av" name="A" style="display: none;" type="text" value="{x2;$items[0]['case_id']}" />


								<div>选项B</div>
								<!--<select name="B" id="item_b" class="form-control" needle="needle" msg="您必须为B选项选择一个病例声音">
								</select>-->
								<input class="form-control" id="item_b" name="t_B" type="text" value="{x2;$items[1]['item_content']}" needle="needle" msg="您必须为B选项添加一个病例声音"/>
								<input class="form-control ii" id="item_bv" name="B" style="display: none;" type="text" value="{x2;$items[1]['case_id']}" />

								<div>选项C</div>
								<!--<select name="C" id="item_c" class="form-control" needle="needle" msg="您必须为C选项选择一个病例声音">
								</select>-->
								<input class="form-control" id="item_c" name="t_C" type="text" value="{x2;$items[2]['item_content']}" needle="needle" msg="您必须为C选项添加一个病例声音"/>
								<input class="form-control ii" id="item_cv" name="C" style="display: none;" type="text" value="{x2;$items[2]['case_id']}" />

								<div>选项D</div>
								<!--<select name="D" id="item_d" class="form-control" needle="needle" msg="您必须为D选项选择一个病例声音">
								</select>-->
								<input class="form-control" id="item_d" name="t_D" type="text" value="{x2;$items[3]['item_content']}" needle="needle" msg="您必须为D选项添加一个病例声音"/>
								<input class="form-control ii" id="item_dv" name="D" style="display: none;" type="text" value="{x2;$items[3]['case_id']}" />

								<div>选项E</div>
								<!--<select name="E" id="item_e" class="form-control" needle="needle" msg="您必须为E选项选择一个病例声音">
								</select>-->
								<input class="form-control" id="item_e" name="t_E" type="text" value="{x2;$items[4]['item_content']}" needle="needle" msg="您必须为E选项添加一个病例声音"/>
								<input class="form-control ii" id="item_ev" name="E" style="display: none;" type="text" value="{x2;$items[4]['case_id']}" />
							</div>
						</div>
						{x2;tree:$items,item,iid}
                            {x2;if:v:item['if_right']==1}<input type="hidden" id="right_answer" case_id="{x2;v:item['case_id']}" value="{x2;v:item['item_title']}">{x2;endif}
						{x2;endtree}
						<div class="form-group">
							<label class="control-label col-sm-2">正确选项：</label>
							<div class="col-sm-4">
								<input type="radio" name="args[right_item]" {x2;if:$optQuestion['right_item']=='A'}checked{x2;endif} value="A" id="r_a">A&nbsp;&nbsp;&nbsp;&nbsp;
								<input type="radio" name="args[right_item]" {x2;if:$optQuestion['right_item']=='B'}checked{x2;endif} value="B" id="r_b">B&nbsp;&nbsp;&nbsp;&nbsp;
								<input type="radio" name="args[right_item]" {x2;if:$optQuestion['right_item']=='C'}checked{x2;endif} value="C" id="r_c">C&nbsp;&nbsp;&nbsp;&nbsp;
								<input type="radio" name="args[right_item]" {x2;if:$optQuestion['right_item']=='D'}checked{x2;endif} value="D" id="r_d">D&nbsp;&nbsp;&nbsp;&nbsp;
								<input type="radio" name="args[right_item]" {x2;if:$optQuestion['right_item']=='E'}checked{x2;endif} value="E" id="r_e">E
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-2">备注：</label>
						  	<div class="col-sm-10">
								<textarea id="memo" name="args[memo]" class="form-control" rows="4">{x2;$optQuestion['memo']}</textarea>
							</div>
						</div>

						<div class="form-group">
						  	<label class="control-label col-sm-2"></label>
						  	<div class="col-sm-9">
							  	<button class="btn btn-primary" type="submit">提交</button>
							  	<input type="hidden" name="page" value="{x2;$page}"/>
							  	<input type="hidden" name="modifyOptQuestion" value="1"/>
							  	<input type="hidden" name="questionId" value="{x2;$optQuestion['opt_question_id']}"/>
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
<script src="app/core/styles/js/jquery-ui.js"></script>
<script type="text/javascript">

	var rigth_answer="";
	var organ_type = $("input[name='args[organ_type]']:checked").val();
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

        $(":radio").click(function(){
            organ_type = $(this).val();
//            console.log(organ_type);
        });

        $(".ii").each(function(){
            if($(this).val()==0){
                var item = $(this).attr('name').toLowerCase();
                var k = "r_"+item;
                $("#"+k).attr("disabled",true);
            }
        });
    });

    function change_value(str1) {
        $("#"+str1).val("");
    }

    $("#right_answer").change(function () {
        var item = $(this).val().toLowerCase();
        var k = "item_"+item+"v";
        if($("#"+k).val()=="") alert("正确答案必须为");
    });

    $("#item_a").autocomplete({
        /*source:"index.php?sound-master-discern-getSoundPackage&organ_type="+organ_type,
         minLength: 0,
         focus: function( event, ui ) {
             $("#item_a").val(ui['item'].label);
             $("#item_av").val(ui['item'].value1);
         }*/
        minLength: 0,
        maxItemsToShow: 20,
        scroll:true,
        source: function(request, response) {
            $.ajax({
                url: "index.php?sound-master-discern-getCaseNew",
                dataType : "json",
                data:{
                    key:        request.term,
                    organ_type: organ_type
                },
                success: function(data) {
                    response($.map(data, function(item) {
                        if(item.value1==-1){
                            change_value("item_av");
                            $("#r_a").attr("disabled",true);
                        }else{
                            $("#r_a").attr("disabled",false);
                        }
                        return item;
                    }));
                }
            });
        },
        focus: function( event, ui ) {
            if(ui['item'].value1!=-1) {
                $("#item_av").val(ui['item'].value1);
                $("#item_a").val(ui['item'].label);
                $("#r_a").attr("disabled",false);
            }else {
                $("#r_a").attr("disabled",true);
            }
        }
    }).focus(function () {
        $(this).autocomplete("search");
//        console.log(organ_type);
    });

    $("#item_b").autocomplete({
        /* source:"index.php?sound-master-discern-getSoundPackage&organ_type="+organ_type,
         minLength: 0,
         focus: function( event, ui ) {
             $("#item_a").val(ui['item'].label);
             $("#item_av").val(ui['item'].value1);
         }*/
        source: function(request, response) {
            $.ajax({
                url: "index.php?sound-master-discern-getCaseNew",
                dataType : "json",
                data:{
                    key:        request.term,
                    organ_type: organ_type
                },
                success: function(data) {
                    response($.map(data, function(item) {
                        if(item.value1==-1){
                            change_value("item_bv");
                            $("#r_b").attr("disabled",true);
                        }else{
                            $("#r_b").attr("disabled",false);
                        }
                        return item;
                    }));
                }
            });
        },
        focus: function( event, ui ) {
            if(ui['item'].value1!=-1) {
                $("#item_bv").val(ui['item'].value1);
                $("#item_b").val(ui['item'].label);
                $("#r_b").attr("disabled",false);
            }else {
                $("#r_b").attr("disabled",true);
            }
        },
        minLength: 0
    }).focus(function () {
        $(this).autocomplete("search");
//        console.log(organ_type);
    });

    $("#item_c").autocomplete({
        /* source:"index.php?sound-master-discern-getSoundPackage&organ_type="+organ_type,
         minLength: 0,
         focus: function( event, ui ) {
             $("#item_a").val(ui['item'].label);
             $("#item_av").val(ui['item'].value1);
         }*/
        source: function(request, response) {
            $.ajax({
                url: "index.php?sound-master-discern-getCaseNew",
                dataType : "json",
                data:{
                    key:        request.term,
                    organ_type: organ_type
                },
                success: function(data) {
                    response($.map(data, function(item) {
                        if(item.value1==-1){
                            change_value("item_cv");
                            $("#r_c").attr("disabled",true);
                        }else{
                            $("#r_c").attr("disabled",false);
                        }
                        return item;
                    }));
                }
            });
        },
        focus: function( event, ui ) {
            if(ui['item'].value1!=-1) {
                $("#item_cv").val(ui['item'].value1);
                $("#item_c").val(ui['item'].label);
                $("#r_c").attr("disabled",false);
            }else {
                $("#r_c").attr("disabled",true);
            }
        },
        minLength: 0
    }).focus(function () {
        $(this).autocomplete("search");
//        console.log(organ_type);
    });

    $("#item_d").autocomplete({
        /* source:"index.php?sound-master-discern-getSoundPackage&organ_type="+organ_type,
         minLength: 0,
         focus: function( event, ui ) {
             $("#item_a").val(ui['item'].label);
             $("#item_av").val(ui['item'].value1);
         }*/
        source: function(request, response) {
            $.ajax({
                url: "index.php?sound-master-discern-getCaseNew",
                dataType : "json",
                data:{
                    key:        request.term,
                    organ_type: organ_type
                },
                success: function(data) {
                    response($.map(data, function(item) {
                        if(item.value1==-1){
                            change_value("item_dv");
                            $("#r_d").attr("disabled",true);
                        }else{
                            $("#r_d").attr("disabled",false);
                        }
                        return item;
                    }));
                }
            });
        },
        focus: function( event, ui ) {
            if(ui['item'].value1!=-1) {
                $("#item_dv").val(ui['item'].value1);
                $("#item_d").val(ui['item'].label);
                $("#r_d").attr("disabled",false);
            }else {
                $("#r_d").attr("disabled",true);
            }
        },
        minLength: 0
    }).focus(function () {
        $(this).autocomplete("search");
//        console.log(organ_type);
    });

    $("#item_e").autocomplete({
        /* source:"index.php?sound-master-discern-getSoundPackage&organ_type="+organ_type,
         minLength: 0,
         focus: function( event, ui ) {
             $("#item_a").val(ui['item'].label);
             $("#item_av").val(ui['item'].value1);
         }*/
        source: function(request, response) {
            $.ajax({
                url: "index.php?sound-master-discern-getCaseNew",
                dataType : "json",
                data:{
                    key:        request.term,
                    organ_type: organ_type
                },
                success: function(data) {
                    response($.map(data, function(item) {
                        if(item.value1==-1){
                            change_value("item_ev");
                            $("#r_e").attr("disabled",true);
                        }else{
                            $("#r_e").attr("disabled",false);
                        }
                        return item;
                    }));
                }
            });
        },
        focus: function( event, ui ) {
            if(ui['item'].value1!=-1) {
                $("#item_ev").val(ui['item'].value1);
                $("#item_e").val(ui['item'].label);
                $("#r_e").attr("disabled",false);
            }else {
                $("#r_e").attr("disabled",true);
            }
        },
        minLength: 0
    }).focus(function () {
        $(this).autocomplete("search");
//        console.log(organ_type);
    });

</script>
</html>
{x2;endif}