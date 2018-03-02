{x2;if:!$userhash}
{x2;include:header}
<body>
{x2;include:nav}
<div class="container-fluid">
    <div class="row-fluid">
        <div class="main">
            <div class="box itembox" style="margin-bottom:0px;">
                <div class="col-xs-12">
                    <ol class="breadcrumb">
                        <li><a href="index.php">首页</a></li>
                        <li><a href="index.php?course">课程</a></li>
                        {x2;tree:$catbread,cb,cbid}
                        <li><a href="index.php?course-app-category&catid={x2;v:cb['catid']}">{x2;v:cb['catname']}</a>
                        </li>
                        {x2;endtree}
                        <li class="active">{x2;$cat['catname']}</li>
                    </ol>
                </div>
            </div>
        </div>
        <div class="main" id="datacontent">
            {x2;endif}

            <div class="col-xs-5" style="padding-left:0px;">
                <div class="box itembox" style="padding-top:20px;">
                    <h4 class="title">{x2;$course['cstitle']}</h4>
                    {x2;realhtml:$course['coursedescribe']}
                </div>
                {x2;tree:$contents['data'],content,cid}
                <input type="hidden" class="count_courseware" value="">
                <div class="box itembox" style="padding-top:20px;">
                    <div class="col-xs-6">
                        <a csid="{x2;$course['csid']}" contentid="{x2;v:content['courseid']}"
                           class="thumbnail pull-left ajax">
                            <img src="{x2;v:content['coursethumb']}" alt="" width="100%">
                        </a>
                    </div>
                    <div class="col-xs-6" style="overflow: hidden;text-overflow: ellipsis; height: 120px;">
                        <a csid="{x2;$course['csid']}" contentid="{x2;v:content['courseid']}" class="ajax"><h4
                                    class="title">{x2;v:content['coursetitle']}</h4></a>
                        <p>{x2;realhtml:v:content['coursedescribe']}</p>
                        <p>
                            <!--<span class="pull-right">
								{x2;if:$content['courseid'] == v:content['courseid']}
								<a href="javascript:;" style="color:green;"><em class="glyphicon glyphicon-play"></em> 播放中</a>
								{x2;endif}
							</span> -->
                        </p>
                    </div>
                </div>
                {x2;endtree}
                <ul class="pagination pagination-right">{x2;$contents['pages']}</ul>
            </div>

            <div class="col-xs-7" style="padding-right:0px;position:relative;">
                <div class="box itembox" id="course_content" style="width:685px;top:0px;" data-spy="affix" data-offset-top="245">
                    {x2;if:$content['ppt_file']!=''}
                        <iframe src="ViewerJS/#../{x2;$content['ppt_file']}" width='100%' height='450'
                            style='margin-top: 20px;' allowfullscreen webkitallowfullscreen></iframe>
                    {x2;endif}
                    {x2;if:$content['course_files']!=''}
                        <video style="margin-top:20px;" controls="true" width="100%" height="450">
                            <source src="{x2;$content['course_files']}" type='video/mp4'/>
                        </video>
                    {x2;endif}
                    {x2;if:$content['course_files']=='' && $content['ppt_file']==''}
                        <div style='overflow:auto;'>{x2;realhtml:$content['coursedescribe']}</div>
                    {x2;endif}
                </div>
            </div>
            {x2;if:!$userhash}
        </div>
    </div>
</div>
{x2;include:footer}
</body>
<script type="text/javascript">

    $(document).ready(function() {
        var maxHeight=""
        if($("input[class='count_courseware']").length==1){
            maxHeight=450;
        }else if($("input[class='count_courseware']").length==2) {
            maxHeight = 500;
        }else{
            maxHeight = $("input[class='count_courseware']").length * 200;
        }
        $("#course_content").find("div").css("max-height",maxHeight);
    });



    $(".ajax").click(function () {
        var contentid = $(this).attr("contentid");
        $.ajax({
            type: 'get',
            url: "index.php?course-app-course-getCourseById&contentid=" + contentid,
            success: function (data) {
                var courseContent = eval("(" + data + ")");
                var htmlStr = "";
                var c = "";
                if (courseContent["ppt_file"] != "") {
                    c = "ViewerJS/#../" + courseContent["ppt_file"];
                    htmlStr = "<iframe src='" + c + "' width='100%' height='450' style='margin-top: 20px;' allowfullscreen webkitallowfullscreen></iframe>";
                }
                if (courseContent["course_files"] != "") {
                    c = courseContent["course_files"];
                    htmlStr = "<video style='margin-top:20px;' controls='true' width='100%' height='450'> <source src='" + c + "' type='video/mp4' /> </video>"
                }
                if (courseContent["ppt_file"] == "" && courseContent["course_files"] == "") {
                    var maxHeight=""
                    c = courseContent["coursedescribe"];
                    if($("input[class='count_courseware']").length==1){
                        maxHeight=450;
                    }else if($("input[class='count_courseware']").length==2) {
                        maxHeight = 500;
                    }else{
                        maxHeight = $("input[class='count_courseware']").length * 200;
                    }

                    htmlStr = "<div style='overflow:auto;max-height: "+maxHeight+"px;'>" + escape2Html(c) + "</div>"
                }
                $('#course_content').html(htmlStr);
            }
        });
    });

    function escape2Html(str) {
        var arrEntities ={'lt':'<','gt':'>','nbsp':' ','amp':'&','quot':'"'};
        return str.replace(/&(lt|gt|nbsp|amp|quot);/ig, function (all, t){return arrEntities[t];});
    }
</script>
</html>
{x2;endif}