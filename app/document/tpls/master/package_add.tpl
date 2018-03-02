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
							<li><a href="index.php?{x2;$_app}-master-discern">课件套餐</a></li>
							<li class="active">添加课件套餐</li>
						</ol>
					</div>
				</div>
				<div class="box itembox" style="padding-top:10px;margin-bottom:0px;">
					<h4 class="title" style="padding:10px;">
						添加课件套餐
						<a class="btn btn-primary pull-right" href="index.php?sound-master-soundcase">课件套餐管理</a>
					</h4>
					<form action="index.php?document-master-attachtype-addPackage" method="post" class="form-horizontal">
						<fieldset>
						<div class="form-group">
							<label for="course_package_name" class="control-label col-sm-2">套餐名称</label>
							<div class="col-sm-6">
								<input class="form-control" id="course_package_name" name="args[course_package_name]" type="text" value="" needle="needle" msg="您必须输入套餐名称" />
							</div>
						</div>

						<div class="form-group">
							<label for="discern_id" class="control-label col-sm-2">听诊套餐</label>
							<div class="col-sm-6">
							<!--<input class="form-control" id="sound_package_name" type="text" value="" />
								<input class="form-control" id="sound_package_id" name="args[discern_id]" style="display: none;" type="text" value="" />-->
                                <select name="args[discern_id]" class="form-control">
                                    <option value=""></option>
                                    {x2;tree:$soundPackages,sp,spid}
                                    <option value="{x2;v:sp['soundcase_package_id']}">{x2;v:sp['package_name']}</option>
                                    {x2;endtree}
                                </select>
							</div>
						</div>

                        <div class="form-group">
                            <label for="basicsubjectid" class="control-label col-sm-2"></label>
                            <div class="col-sm-4" style="width: 70%">
								<input type="text" id="menu_name" name="args[menu_name]" class="form-control pull-left" style="width: 35%;margin: 0 20px 8px 0;display: inline-block;" value="" onclick="$('#treeview').show()" placeholder="分类名称">
								<div id="treeview" style="display: none;"></div>

								<input class="form-control pull-left" style="width: 35%;margin: 0 20px 8px 0;display: inline-block;" id="course_name" type="text" value="" placeholder="病例名称"/>
								<input value="" id="menu_id" style="display: none;">
								<input value="" id="father_code" style="display: none;">
								<button class="btn btn-primary" id="getCourse" type="button">搜索</button>
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

										</select>
                                    </div>
                                </div>
                            </div>
                        </div>
						<div class="form-group">
							<label for="basic" class="control-label col-sm-2">备注：</label>
							<div class="col-sm-9">
								<textarea class="form-control" rows="4" name="args[memo]" id="memo" autocomplete="off"></textarea>
							</div>
						</div>
						<div class="form-group">
							<label for="basic" class="control-label col-sm-2"></label>
							<div class="col-sm-9">
								<input type="hidden" class="btn btn-primary" name="page" value="{x2;$page}"/>
                                <button class="btn btn-primary" type="submit">提交</button>
								<input type="hidden" name="insertPackage" value="1"/>
								{x2;tree:$search,arg,aid}
								<input type="hidden" name="search[{x2;v:key}]" value="{x2;v:arg}"/>
								{x2;endtree}
							</div>
						</div>
						</fieldset>
					</form>
				</div>
			</div>

            <script type="text/javascript" src="app/core/styles/js/bootstrap.min.js"></script>
            <script type="text/javascript" src="app/core/styles/js/prettify.min.js"></script>
			<script src="app/core/styles/js/multiselect.min.js"></script>
			<script src="app/core/styles/js/bootstrap-treeview.js"></script>
			<script type="text/javascript" src="calendar/js/jquery.form.min.js"></script>
			<script src="app/core/styles/js/jquery-ui.js"></script>
			<script type="text/javascript">
                var data1 = [];
                $("#sound_package_name").autocomplete({
                    source:"index.php?sound-master-discern-getSoundPackage",
                    minLength: 0,
                    focus: function( event, ui ) {
						$("#sound_package_name").val(ui['item'].label);
						$("#sound_package_id").val(ui['item'].value1);
					}
				}).focus(function () {
                    $(this).autocomplete("search");
                })

                window.onload=function(){
                    var treeview = document.getElementById("treeview");
                    var menu_name = document.getElementById("menu_name");
                    document.addEventListener("click",function(){
                        treeview.style.display="none";
                    });
                    treeview.addEventListener("click",function(event){
                        event=event||window.event;
                        event.stopPropagation();
                    });
                    menu_name.addEventListener("click",function(event){
                        event=event||window.event;
                        event.stopPropagation();
                    });
                };

                $(document).ready(function() {
                    window.prettyPrint && prettyPrint();
                    $('#undo_redo').multiselect();

                    var menu_id = $("#menu_id").val();
                    var father_code = $("#father_code").val();
                    $.ajax({
                        type: 'get',
                        url: "index.php?document-master-attachtype-echoTree&menu_id="+menu_id+"&father_code="+father_code,
                        dataType: "json",
                        success:function (data) {
							data1=data;
                            $('#treeview').treeview({
                                bootstrap2 : false,
                                showTags : true,
                                showCheckbox : false,
                                levels:0,
                                checkedIcon : "glyphicon glyphicon-check",
                                data : data1,
                                onNodeSelected : function(event, data) {
                                    $("#menu_name").val(data.text);
                                    $("#menu_id").val(data.id);
                                    $("#father_code").val(data.father_code);
                                    $("#treeview").hide();
                                }
                            });
                        }
                    });


                });

                function difference(a, b) { // 差集 a - b
                    //clone = a
                    var clone = a.slice(0);
                    for(var i = 0; i < b.length; i ++) {
                        var temp = b[i].value;
                        for(var j = 0; j < clone.length; j ++) {
                            if(temp === clone[j]['system_course_id']) {
                                clone.splice(j,1);
                            }
                        }
                    }
                    return clone;
                }

                $('#getCourse').click(function () {
					var menu_id = $("#menu_id").val();
					var course_name = $("#course_name").val();
                    $.ajax({
                        type: 'post',
                        dataType : "json",
                        url: "index.php?document-master-attachtype-getCourseJson",
						data: {
							'menu_id' : menu_id,
							'course_name' : course_name
						},
                        success:function (data) {
                            var left = data;
                            var right = $('#undo_redo_to option').slice(0);
                            var opt="";

                            var clone=difference(left,right);

                            $.each(clone, function(i, v) {
                                var nameStr=v['full_name'];
                                if(v['full_name'].length>15){
                                    nameStr=v['full_name'].substr(0,15)+"…";
                                }
                                // opt+="<option title='"+v['full_name']+"' value='"+v['system_course_id']+"'>"+v['course_name']+"</option>";
                                opt+="<option title='"+v['full_name']+"' value='"+v['system_course_id']+"'>"+nameStr+"</option>";
                                // opt+="<option value='"+v['system_course_id']+"'>"+v['menu_name']+":"+v['course_name']+"</option>";
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