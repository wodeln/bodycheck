				<table class="table table-hover">

					{x2;tree:$questions,question,qid}
					<tr id="selectedq_{x2;v:question['questionid']}">
						<td>
							<table>
								<tr>
									<td>备选项：</td>
									<td>
                                        {x2;tree:v:question['item'],item,iid}
										<div>
                                            {x2;if:v:item['case_id']==0}
                                            {x2;v:item['item_title']}.{x2;v:item['case_name']}
                                            {x2;else}
											<a cid="{x2;v:item[case_id]}" href="javascript:;"  onmouseover="play(this)" onmouseout="pause(this)"  >{x2;v:item['item_title']}.{x2;v:item['case_name']}&nbsp;</a>
											<audio src="{x2;v:item[sound_file]}" id="audio{x2;v:item[case_id]}" loop="loop"></audio>
                                            {x2;endif}
										</div>
                                        {x2;endtree}
									</td>
								</tr>
								<tr>
									<td>答案：</td>
									<td>{x2;realhtml:v:question['right_item']}</td>
								</tr>
							</table>
						</td>
					</tr>
					{x2;endtree}
				</table>