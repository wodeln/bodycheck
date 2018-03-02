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
							<li><a href="index.php?{x2;$_app}-master-video&page={x2;$page}">视频管理</a></li>
							<li class="active">添加视频</li>
						</ol>
					</div>
				</div>
				<div class="box itembox" style="padding-top:10px;margin-bottom:0px;">
					<h4 class="title" style="padding:10px;">
						添加视频
						<a class="btn btn-primary pull-right" href="index.php?{x2;$_app}-master-course&page={x2;$page}{x2;$u}">视频管理</a>
					</h4>
					<form action="index.php?course-master-video-add" method="post" class="form-horizontal">
						<div class="form-group">
				            <label for="contenttitle" class="control-label col-sm-2">视频名称：</label>
				            <div class="col-sm-9">
							    <input class="form-control" type="text" id="coursetitle" name="args[coursetitle]" needle="needle" msg="您必须输入名称">
					        </div>
				        </div>
						<div class="form-group">
							<label for="course_showhome" class="control-label col-sm-2">首页显示</label>
							<div class="col-sm-9">
								<label class="radio-line"><input type="radio" name="args[course_showhome]" id="course_showhome0" value="1" autocomplete="off"> 是</label>
								<label class="radio-line"><input type="radio" name="args[course_showhome]" id="course_showhome1" value="0" checked autocomplete="off"> 否</label>
							</div>
						</div>
						<div class="form-group">
							<label for="course_files" class="control-label col-sm-2">视频文件</label>
							<div class="col-sm-9">
								<script type="text/template" id="pe-template-course_files">
									<div class="qq-uploader-selector" qq-drop-area-text="可将图片拖拽至此处上传" style="clear:both;">
										<ul class="qq-upload-list-selector list-unstyled pull-left" aria-live="polite" aria-relevant="additions removals" style="clear:both;">
											<li class="text-center">
												<input size="45" class="form-control qq-edit-filename-selector" type="text" name="args[course_files]" tabindex="0" value="">
											</li>
										</ul>
										<ul class="qq-upload-list-selector list-unstyled pull-left" aria-live="polite" aria-relevant="additions removals" style="clear:both;">
											<li class="text-center">
												<input size="45" class="form-control qq-edit-filename-selector" type="text" name="args[course_files]" tabindex="0" value="">
											</li>
										</ul>
										<div class="qq-upload-button-selector col-xs-3">
											<button class="btn btn-primary">上传文件<span class="process"></span></button>
										</div>
									</div>
								</script>
								<div class="fineuploader" attr-type="files" attr-template="pe-template-course_files" attr-ftype="mp4"></div>							</div>
						</div>

				        <div class="form-group">
				            <label for="contentdescribe" class="control-label col-sm-2">简介：</label>
				            <div class="col-sm-10">
							    <textarea id="coursedescribe" name="args[coursedescribe]" class="form-control" rows="4"></textarea>
					        </div>
				        </div>

				        <div class="form-group">
				            <label for="contentdescribe" class="control-label col-sm-2"></label>
				            <div class="col-sm-9">
					            <button class="btn btn-primary" type="submit">提交</button>
					            <input type="hidden" name="submit" value="1">
					            <input type="hidden" name="args[coursecsid]" value="0">
					            <input type="hidden" name="args[coursemoduleid]" value="14">
					        </div>
				        </div>
					</form>
				</div>
			</div>
{x2;if:!$userhash}
		</div>
	</div>
</div>
</body>
</html>
{x2;endif}