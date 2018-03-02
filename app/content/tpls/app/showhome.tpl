	<h4 class="title">
		第{x2;$number}题
	</h4>
	<div>
		{x2;realhtml:$question['qrquestion']}
	</div>
	<div>
        {x2;realhtml:$question['sub']['question']}
	</div>
	<div class="choice">
        {x2;realhtml:$question['sub']['questionselect']}
	</div>
	<div class="selector">
        {x2;tree:$selectorder,so,sid}
        {x2;if:v:key == $question['sub']['questionselectnumber']}
        {x2;eval: break;}
        {x2;endif}
		<label class="radio-inline"><input type="radio" name="question[{x2;$question['questionid']}]" rel="{x2;$question['sub']['questionid']}" value="{x2;v:so}" />{x2;v:so} </label>
        {x2;endtree}
	</div>
	<div class="choice" style="margin-top:20px;overflow:hidden;">
		<div class="btn-group pull-right hide answerbox">
            {x2;if:$number > 1}
            <a class="btn btn-primary selfmodal"  data-target="#modal" href="javascript:;" title="上一题" url="index.php?content-app-index-showhome&questionid={x2;$question['qrid']}&number={x2;eval: echo intval($number - 1)}">上一题</a>
            {x2;endif}
            {x2;if:$number < $allnumber}
            <a class="btn btn-primary selfmodal"  data-target="#modal" href="javascript:;" title="下一题" url="index.php?content-app-index-showhome&questionid={x2;$question['qrid']}&number={x2;eval: echo intval($number + 1)}">下一题</a>
            {x2;endif}
		</div>
	</div>

	<div id="rightanswer_{x2;$question['sub']['questionid']}" class="hide">{x2;realhtml:$question['sub']['questionanswer']}</div>
	<div class="answerbox hide" style="margin-top:1rem;">
		<table class="table table-hover table-bordered">
			<tr class="info">
				<td width="15%">正确答案：</td>
				<td>{x2;realhtml:$question['sub']['questionanswer']}</td>
			</tr>
			<tr>
				<td>答案解析：</td>
				<td>{x2;realhtml:$question['sub']['questiondescribe']}</td>
			</tr>
		</table>
	</div>

	<script type="text/javascript">
        $("input:radio").click(function(){
            var _this = $(this);
            var qid = _this.attr('rel');
            console.log(qid);
            _this.parents('.selector').parent().find('.questionbtn').addClass('hide');
            _this.parents('.selector').parent().find('.answerbox').removeClass('hide');
            if(_this.val() == $("#rightanswer_"+qid).html())
            {
                _this.parents('.selector').addClass('alert-success').addClass('alert').html('恭喜您回答正确！');
            }
            else
            {
                _this.parents('.selector').addClass('alert-danger').addClass('alert').html('回答错误，正确答案为：'+$("#rightanswer_"+qid).html()+'，您选择的答案是：'+_this.val());
            }
        });

	</script>