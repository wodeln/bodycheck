{x2;include:header}
<body>
{x2;include:nav}
{x2;tree:$basics,basic,bid}
<div class="container-fluid">
	<div class="row-fluid">
		<div class="main box itembox  top30 border-bottom">
			<div class="col-lg-7" style="padding:0px;">
				<div class="pull-left">
					<p class="course_title">{x2;v:basic['basic']}</p>
					<div>{x2;v:basic['basicdescribe']}</div>
				</div>
			</div>
			<div class="pull-right more">
				<!--<ul>
					<li><a href="">课后练习</a></li>
					<li><a href="">强化训练</a></li>
					<li><a href="">模拟考试</a></li>
					<li><a href="">正式考试</a></li>
				</ul>
				<ul>
					<li><a href="">考试记录</a></li>
					<li><a href="">习题收藏</a></li>
					<li><a href="">成绩单</a></li>
				</ul>-->
				<a href="index.php?{x2;$_app}-app-index-setCurrentBasic&basicid={x2;v:basic['basicid']}" class="ajax">查看详情</a>
			</div>
			<!--<div class="col-xs-7" style="padding:0px;">

				<div class="col-xs-3" style="width:20%">
					<a href="index.php?{x2;$_app}-app-index-setCurrentBasic&basicid={x2;v:basic['basicid']}" class="thumbnail ajax">
						<img src="{x2;if:v:basic['basicthumb']}{x2;v:basic['basicthumb']}{x2;else}app/core/styles/img/item.jpg{x2;endif}" alt="" width="100%">
					</a>
					<h5 class="text-center">{x2;v:basic['basic']}</h5>
				</div>
				{x2;if:v:bid % 5 == 0}
				<div class="col-xs-12"><hr /></div>
				{x2;endif}

			</div>-->
		</div>
	</div>
</div>
{x2;endtree}
{x2;include:footer}
</body>
</html>