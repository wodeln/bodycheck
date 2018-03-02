{x2;include:header}
<body>
{x2;include:nav}
{x2;tree:$course,course,cid}
<div class="container-fluid">
	<div class="row-fluid">
		<div class="main box itembox top30 border-bottom">
			<div class="col-lg-7" style="padding:0px;">
				<div class="pull-left">
					<p class="course_title">{x2;v:course['coursetitle']}</p>
					<div>{x2;realhtml:v:course['coursedescribe']}</div>
				</div>
			</div>
			<div class="pull-right more">
                {x2;if:v:course['ppt_file']!=''}
					<a target="_blank" href="ViewerJS/#../{x2;v:course['ppt_file']}">查看详情</a>
                {x2;endif}
                {x2;if:v:course['course_files']!=''}
				<a class="selfmodal" href="javascript:;" data-toggle="modal" data-target="#video_modal" url="index.php?content-app-index-showhomevedio&courseid={x2;v:course['courseid']}">查看详情</a>
                {x2;endif}
                {x2;if:v:course['course_files']=='' && v:course['ppt_file']==''}
					<a target="_blank" href="index.php?course-app-course-gethtmlcourse&courseid={x2;v:course['courseid']}">查看详情</a>
                {x2;endif}

			</div>
		</div>
	</div>
</div>

<div id="video_modal" class="modal fade">
	<div class="modal-dialog" style="min-width: 780px;">
		<div class="modal-content" style="min-width: 780px;">
			<div class="modal-header my-modal-header">
				<button aria-hidden="true" class="close" type="button" data-dismiss="modal">×</button>
			</div>
			<div class="modal-body" id="modal-body"></div>
		</div>
	</div>
</div>
{x2;endtree}
{x2;include:footer}
</body>
</html>