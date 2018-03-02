{x2;include:header}
<body>
{x2;include:nav}
<div class="container-fluid">
	<div class="row-fluid">
		<div class="main" style="height:450px;overflow:hidden;">

			<div class="col-xs-10 box split" style="padding:0px;width:100%;">
				<div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
					<!-- Indicators -->
					<ol class="carousel-indicators" style="left:90%;bottom:0px;">
						{x2;tree:$contents[2]['data'],content,cid}
						<li data-target="#carousel-example-generic" data-slide-to="{x2;v:key}"{x2;if:v:cid == 1} class="active"{x2;endif}></li>
						{x2;endtree}
					</ol>
					<!-- Wrapper for slides -->
					<div class="carousel-inner" role="listbox">
						{x2;tree:$contents[2]['data'],content,cid}
						<div class="item{x2;if:v:cid == 1} active{x2;endif}">
							<a href="index.php?content-app-content&contentid={x2;v:content['contentid']}">
								<img src="{x2;v:content['contentthumb']}" alt="" style="width:100%;">
							</a>
							<div class="carousel-caption">
								<!--{x2;v:content['contenttitle']}-->
							</div>
						</div>
						{x2;endtree}
					</div>
					<!-- Controls
					<a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
					<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
					<span class="sr-only">Previous</span>
					</a>
					<a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
					<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
					<span class="sr-only">Next</span>
					</a>
					-->
				</div>
			</div>
		</div>
	</div>
</div>

<!--<div class="container-fluid hide">
	<div class="row-fluid">
		<div class="main"><img src="app/core/styles/img/ad.jpg"/></div>
	</div>
</div>-->
<div class="container-fluid main_backimg">
	<div class="row-fluid">
		<div class="main box itembox sub_backimg">
			<div class="more_knowledge">————————————————— 更多知识 —————————————————</div>
			<div class="subject-video pull-left center-block">
				<div class="subjec pull-left">
					<div class="title_str">测试您的<div>知识</div></div>
					<hr>
					<div>
						<div>{x2;realhtml:$question['qrquestion']}</div>
                        <div>{x2;realhtml:$question['sub'][0]['question']}</div>
                        <div class="choice">
                            {x2;realhtml:$question['sub'][0]['questionselect']}
                        </div>
                        <div class="selector">
                            {x2;tree:$selectorder,so,sid}
                            {x2;if:v:key == $question['sub'][0]['questionselectnumber']}
                            {x2;eval: break;}
                            {x2;endif}
                            <label class="radio-inline"><input type="radio" name="question[{x2;$question['questionid']}]" rel="{x2;$question['questionid']}" value="{x2;v:so}" {x2;if:v:so == $sessionvars['examsessionuseranswer'][$question['questionid']]}checked{x2;endif}/>{x2;v:so} </label>
                            {x2;endtree}
                        </div>
					</div>
					<div class="pull-right bottom-s">
                       <a class="selfmodal" href="javascript:;" data-toggle="modal" data-target="#modal" url="index.php?content-app-index-showhome&questionid={x2;$question['qrid']}&number=1">我的回答是否正确</a>
				    </div>
				</div>
				<div class="video pull-left" style="position:relative;">
					<div style="position:relative; z-index:1;">
						<video style="margin-top:1px;position:relative;" width="100%">
							<source src="{x2;$vedio['course_files']}" type="video/mp4">
						</video>
					</div>
					<a class="selfmodal" href="javascript:;" data-toggle="modal" data-target="#video_modal" url="index.php?content-app-index-showhomevedio&courseid={x2;$vedio['courseid']}">
						<div class="play_img">
							<img src="app/core/styles/img/play.png"/>
						</div>
					</a>
					<div class="pull-right bottom-s"><a href="index.php?content-app-content-getvideos">查看更多视频</a></div>
				</div>
			</div>
		</div>
	</div>
</div>
<!--<div class="container-fluid hide">
	<div class="row-fluid">
		<div class="main"><img src="app/core/styles/img/ad2.png"/></div>
	</div>
</div>-->
<div class="container-fluid">
	<div class="row-fluid">
		<div class="main">
			<div class="col-xs-4 box itembox" style="min-height:320px;">
				<h4 class="title"><a href="index.php?content-app-category&catid=1">{x2;$rcats[1]['catname']}</a></h4>
				<ul class="list-unstyled">
					{x2;tree:$contents[1]['data'],content,cid}
					<li><a href="index.php?content-app-content&contentid={x2;v:content['contentid']}" title="{x2;v:content['contenttitle']}">{x2;v:content['contenttitle']}</a></li>
					{x2;endtree}
				</ul>
			</div>
			<div class="col-xs-4 box itembox split" style="min-height:320px;">
				<h4 class="title"><a href="index.php?content-app-category&catid=3">{x2;$rcats[3]['catname']}</a></h4>
				<ul class="list-unstyled">
					{x2;tree:$contents[3]['data'],content,cid}
					<li><a href="index.php?content-app-content&contentid={x2;v:content['contentid']}" title="{x2;v:content['contenttitle']}">{x2;v:content['contenttitle']}</a></li>
					{x2;endtree}
				</ul>
			</div>
			<div class="col-xs-4 box itembox" style="min-height:320px;">
				<h4 class="title"><a href="index.php?content-app-category&catid=4">{x2;$rcats[4]['catname']}</a></h4>
				<ul class="list-unstyled">
					{x2;tree:$contents[4]['data'],content,cid}
					<li><a href="index.php?content-app-content&contentid={x2;v:content['contentid']}" title="{x2;v:content['contenttitle']}">{x2;v:content['contenttitle']}</a></li>
					{x2;endtree}
				</ul>
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

<div id="modal" class="modal fade">
	<div class="modal-dialog" style="min-width: 680px;">
		<div class="modal-content" style="min-width: 680px;">
			<div class="modal-header">
				<button aria-hidden="true" class="close" type="button" data-dismiss="modal">×</button>
			</div>
            <div class="modal-body" id="modal-body"></div>
		</div>
	</div>
</div>
{x2;include:footer}
</body>
</html>