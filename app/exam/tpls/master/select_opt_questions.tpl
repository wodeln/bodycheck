		    	<script type="text/javascript">
		    	function selectall(obj,a,b){
		    		$(".sbox").prop('checked', $(obj).is(':checked'));
		    		$(".sbox").each(function(){
		    			selectquestions(this,a,b);
		    		});
		    	}
		    	</script>
		    	<form action="index.php?exam-master-exams-selectOptQuestions" method="post" direct="modal-body">
					<table class="table form-inline">
                        {x2;if:$search['organ_type']==1}
				        <tr>
							<td>

						  		<select name="search[organ_type]" class="combox form-control" id="sectionselect">
							  		<option value="">选择试题分类</option>
							  		<option value="0">心音试题</option>
							  		<option value="1">呼吸音试题</option>
						  		</select>

						  	</td>
						  	<td>

						  	</td>
						  	<td>
								<button class="btn btn-primary" type="submit">检索</button>
							</td>
						</tr>
                        {x2;endif}
					</table>
				</form>

				<table class="table table-hover table-bordered">
					<thead>
						<tr class="info">
					        <th><input type="checkbox" onclick="javascript:selectall(this,'iselectquestions_1}','ialreadyselectnumber_1');"/></th>
					        <th>类型</th>
					        <th>正确</th>
					        <th>声音</th>
						</tr>
					</thead>
					<tbody>
                    {x2;tree:$options['data'],option,qid}
					<tr>
						<td>
							<input rel="1" class="sbox" type="checkbox" name="ids[]" value="{x2;v:option['opt_question_id']}" onclick="javascript:selectquestions(this,'iselectquestions_1','ialreadyselectnumber_1')"/>
						</td>
						<td>
                            {x2;if:v:option['organ_type']==0}心音{x2;endif}
                            {x2;if:v:option['organ_type']==1}呼吸音{x2;endif}
                            {x2;if:v:option['organ_type']==4}心电图{x2;endif}
						</td>
						<td>
                            {x2;v:option['right_item']}
						</td>
						<td>
                            {x2;tree:v:option['item'],item,iid}
								{x2;if:v:item['case_id']=='0'}
                            		{x2;v:item['item_title']}:{x2;v:item['case_name']}
								{x2;else}
                            		<a cid="{x2;v:item[case_id]}" href="javascript:;"  onmouseover="play(this)" onmouseout="pause(this)"  >{x2;v:item['item_title']}:{x2;v:item['case_name']}&nbsp;</a>
									<audio src="{x2;v:item[sound_file]}" id="audio{x2;v:item[case_id]}" loop="loop"></audio>
								{x2;endif}
                            {x2;endtree}
						</td>
					</tr>
                    {x2;endtree}
					</tbody>
				</table>
				<ul class="pagination pull-right">
	            	{x2;$options['pages']}
		        </ul>
		    	<script type="text/javascript">
		    		jQuery(function($) {
						markSelectedQuestions('ids[]','iselectquestions_1');
		    		});
                    function play(obj) {
                        var cid = "audio"+$(obj).attr("cid");
                        sound=document.getElementById(cid);
                        sound.play();
                    }

                    function pause(obj){
                        var cid = "audio"+$(obj).attr("cid");
                        sound=document.getElementById(cid);
                        sound.pause();
                    }
		    	</script>
