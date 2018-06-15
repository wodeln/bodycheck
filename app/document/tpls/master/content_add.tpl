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
							<li><a href="index.php?{x2;$_app}-master-files-getCourseFile&page={x2;$page}">课件管理</a></li>
							<li class="active">添加课件</li>
						</ol>
					</div>
				</div>
				<div class="box itembox" style="padding-top:10px;margin-bottom:0px;">
					<h4 class="title" style="padding:10px;">
						添加课件
						<a class="btn btn-primary pull-right" href="index.php?{x2;$_app}-master-files-getCourseFile&page={x2;$page}">课件管理</a>
					</h4>
					<form action="index.php?document-master-files-addCourse" method="post" class="form-horizontal">
						<div class="form-group">
				            <label for="coursetitle" class="control-label col-sm-2">课件名称：</label>
				            <div class="col-sm-9">
							    <input class="form-control" type="text" id="coursetitle" name="args[course_name]" needle="needle" msg="您必须输入课件名称">
					        </div>
				        </div>

						<div class="form-group">
							<label for="sound_file" class="control-label col-sm-2">PPT：</label>
							<div class="col-sm-7">
								<script type="text/template" id="pe-template-course_files">
									<div class="qq-uploader-selector" qq-drop-area-text="可将图片拖拽至此处上传" style="clear:both;">
										<ul class="qq-upload-list-selector list-unstyled pull-left" aria-live="polite" aria-relevant="additions removals">
											<li class="text-center">
												<input size="33" readonly class="form-control qq-edit-filename-selector" type="text" name="args[ppt_name]" tabindex="0" value="">
											</li>
										</ul>
										<ul class="qq-upload-list-selector list-unstyled pull-left" aria-live="polite" aria-relevant="additions removals">
											<li class="text-center">
												<input size="33" readonly class="form-control qq-edit-filename-selector" type="text" name="args[ppt_name]" tabindex="0" value="">
											</li>
										</ul>
										<div class="qq-upload-button-selector col-xs-3" style="padding-left: 0;">
											<button class="btn btn-primary">上传文件<span class="process"></span></button>
										</div>
									</div>
								</script>
								<div class="fineuploader" attr-type="files" attr-template="pe-template-course_files" attr-ftype="odp"></div>
							</div>
							<input type="hidden" id="sound_file" name="args[course_file]" value="">
						</div>

						<div class="form-group">
							<label for="basic" class="control-label col-sm-2">备注：</label>
							<div class="col-sm-9">
								<textarea class="form-control" rows="4" name="args[memo]" id="memo" autocomplete="off"></textarea>
							</div>
						</div>
				        <div class="form-group">
				            <label for="coursetitle" class="control-label col-sm-2"></label>
				            <div class="col-sm-9">
					            <button class="btn btn-primary" type="submit">提交</button>
					            <input type="hidden" name="insertCourse" value="1">
					            <input type="hidden" name="args[coursecsid]" value="{x2;$courseid}">
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
</html>
{x2;endif}