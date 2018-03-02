<?php
/*
 * Created on 2016-5-19
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
class action extends app
{
	public function display()
	{
		$action = $this->ev->url(3);
		$subjects = $this->basic->getSubjectList();
		$this->tpl->assign('subjects',$subjects);
		if(!method_exists($this,$action))
		$action = "index";
		$this->$action();
		exit;
	}

	private function add()
	{
		if($this->ev->get('submit'))
		{
			$args = $this->ev->get('args');
			$args['csuserid'] = $this->_user['sessionuserid'];
			$args['courseinputtime'] = TIME;
			$id = $this->course->addVideo($args);
			$message = array(
				'statusCode' => 200,
				"message" => "操作成功",
			    "callbackType" => "forward",
			    "forwardUrl" => "index.php?course-master-video"
			);
			exit(json_encode($message));
		}
		else
		{
			$this->tpl->display('video_add');
		}
	}

	private function edit()
	{
		$page = intval($this->ev->get('page'));
		$courseid = intval($this->ev->get('courseid'));
		$video = $this->course->getVideoById($courseid);
		if($this->ev->get('submit'))
		{
			$args = $this->ev->get('args');
			$this->course->modifyVideo($courseid,$args);
			$message = array(
				'statusCode' => 200,
				"message" => "操作成功",
			    "callbackType" => "forward",
			    "forwardUrl" => "index.php?course-master-video&page={$page}"
			);
			$this->G->R($message);
		}
		else
		{
			$this->tpl->assign('video',$video);
			$this->tpl->assign('page',$page);
			$this->tpl->display('video_edit');
		}
	}

	private function del()
	{
		$page = intval($this->ev->get('page'));
		$courseid = intval($this->ev->get('courseid'));
		$this->course->delVideo($courseid);
		$message = array(
			'statusCode' => 200,
			"message" => "操作成功",
			"callbackType" => "forward",
			"forwardUrl" => "reload"
		);

		$this->G->R($message);
	}

	private function index()
	{
		$search = $this->ev->get('search');
		$page = $this->ev->get('page');
		$page = $page?$page:1;

		$args[] = Array("AND","coursecsid = :coursecsid",'coursecsid',0);


		if($search['stime'])$args[] = array("AND","courseinputtime >= :courseinputtime",'courseinputtime',strtotime($search['stime']));
		if($search['etime'])$args[] = array("AND","courseinputtime <= :courseinputtime",'courseinputtime',strtotime($search['etime']));
		if($search['coursetitle'])$args[] = array("AND","coursetitle LIKE :coursetitle",'coursetitle',"%{$search['coursetitle']}%");

		$videos = $this->course->getVedioList($args,$page,10);
		$this->tpl->assign('page',$page);
		$this->tpl->assign('videos',$videos);
		$this->tpl->display('video');
	}
}


?>
