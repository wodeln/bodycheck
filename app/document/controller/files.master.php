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
		if(!method_exists($this,$action))
		$action = "index";
		$this->$action();
		exit;
	}

	private function del()
	{
		$page = $this->ev->get('page');
		$attid = $this->ev->get('attid');
		$this->attach->delAttach($attid);
		$message = array(
			'statusCode' => 200,
			"message" => "操作成功",
			"callbackType" => "forward",
		    "forwardUrl" => "reload"
		);
		$this->G->R($message);
	}

	private function batdel()
	{
		$page = $this->ev->get('page');
		$delids = $this->ev->get('delids');
		foreach($delids as $attid => $p)
		$this->attach->delAttach($attid);
		$message = array(
			'statusCode' => 200,
			"message" => "操作成功",
			"callbackType" => "forward",
		    "forwardUrl" => "reload"
		);
		$this->G->R($message);
	}

	private function modifyquestion()
	{
		if($this->ev->get('modifyquestion'))
		{
			$page = $this->ev->get('page');
			$args = $this->ev->get('args');
			$questionid = $this->ev->get('questionid');
			$targs = $this->ev->get('targs');
			$questype = $this->basic->getQuestypeById($args['questiontype']);
			if($questype['questsort'])$choice = 0;
			else $choice = $questype['questchoice'];
			$args['questionanswer'] = $targs['questionanswer'.$choice];
			if(is_array($args['questionanswer']))$args['questionanswer'] = implode('',$args['questionanswer']);
			$this->exam->modifyQuestions($questionid,$args);
			if($args['questionparent'])
			$message = array(
				'statusCode' => 200,
				"message" => "操作成功",
				"callbackType" => "forward",
				"forwardUrl" => "index.php?exam-master-questions&page={$page}{$u}"
			);
			else
			$message = array(
				'statusCode' => 200,
				"message" => "操作成功",
				"callbackType" => "forward",
			    "forwardUrl" => "index.php?exam-master-questions&page={$page}{$u}"
			);
			$this->G->R($message);
		}
		else
		{
			$page = $this->ev->get('page');
			$questionparent = $this->ev->get('questionparent');
			$knowsid = $this->ev->get('knowsid');
			$questionid = $this->ev->get('questionid');
			$questypes = $this->basic->getQuestypeList();
			$question = $this->exam->getQuestionByArgs("questionid = '{$questionid}'");
			$subjects = $this->basic->getSubjectList();
			foreach($question['questionknowsid'] as $key => $p)
			{
				$knows = $this->section->getKnowsByArgs("knowsid = '{$p['knowsid']}'");
				$question['questionknowsid'][$key]['knows'] = $knows['knows'];
			}
			$this->tpl->assign('subjects',$subjects);
			$this->tpl->assign('questionparent',$questionparent);
			$this->tpl->assign('questypes',$questypes);
			$this->tpl->assign('page',$page);
			$this->tpl->assign('knowsid',$knowsid);
			$this->tpl->assign('question',$question);
			if($questionparent)
			$this->tpl->display('questionchildrows_modify');
			else
			$this->tpl->display('questions_modify');
		}
	}

	private function detail()
	{
		$questionid = $this->ev->get('questionid');
		$questionparent = $this->ev->get('questionparent');
		if($questionparent)
		{
			$questions = $this->exam->getQuestionByArgs("questionparent = '{$questionparent}'");
		}
		else
		{
			$question = $this->exam->getQuestionByArgs("questionid = '{$questionid}'");
			$sections = array();
			foreach($question['questionknowsid'] as $key => $p)
			{
				$knows = $this->section->getKnowsByArgs("knowsid = '{$p['knowsid']}'");
				$question['questionknowsid'][$key]['knows'] = $knows['knows'];
				$sections[] = $this->section->getSectionByArgs("sectionid = '{$knows['knowssectionid']}'");
			}
			$subject = $this->basic->getSubjectById($sections[0]['sectionsubjectid']);
		}
		$this->tpl->assign("subject",$subject);
		$this->tpl->assign("sections",$sections);
		$this->tpl->assign("question",$question);
		$this->tpl->assign("questions",$questions);
		$this->tpl->display('question_detail');
	}

	private function index()
	{
		$search = $this->ev->get('search');
		$u = '';
		if($search)
		{
			$this->tpl->assign('search',$search);
			foreach($search as $key => $arg)
			{
				$u .= "&search[{$key}]={$arg}";
			}
		}
		$this->tpl->assign('u',$u);
		$page = $this->ev->get('page');
		$this->tpl->assign('page',$page);
		$page = $page > 0?$page:1;
		$types = $this->attach->getAttachtypeList();
		$args = array();
		if($search['attid'])
		{
			$args[] = array('AND',"attid = :attid",'attid',$search['attid']);
		}
		if($search['stime'])
		{
			$args[] = array('AND',"attinputtime >= :attinputtime",'attinputtime',strtotime($search['stime']));
		}
		if($search['etime'])
		{
			$args[] = array('AND',"attinputtime <= :attinputtime",'attinputtime',strtotime($search['etime']));
		}
		if($search['atttype'])
		{
			$exts = implode(',',explode(',',$types[$search['atttype']]['attachexts']));
			if($exts)
			$args[] = array('AND',"find_in_set(attext,:attext)",'attext',$exts);
		}
		if($search['username'])
		{
			$user = $this->user->getUserByUserName($search['username']);
			if($user)
			$args[] = array('AND',"attuserid = :attuserid",'attuserid',$user['userid']);
		}
		$attach = $this->attach->getAttachList($args,$page);
		$this->tpl->assign('attachtypes',$types);
        $this->tpl->assign('attach',$attach);
        $this->tpl->display('attachs');
    }

	private function getCourseFile(){
        $search = $this->ev->get('search');
        $u = '';
        if($search)
        {
            $this->tpl->assign('search',$search);
            foreach($search as $key => $arg)
            {
                $u .= "&search[{$key}]={$arg}";
            }
        }
        $this->tpl->assign('u',$u);
        $page = $this->ev->get('page');
        $this->tpl->assign('page',$page);
        $page = $page > 0?$page:1;
        $args = array();
        if($search['course_name']) $args[] = $args[] = array('AND',"course_name LIKE :course_name",'course_name','%'.$search['course_name'].'%');
        $course = $this->attach->getCourseList($args);


        $this->tpl->assign('page',$page);
        $this->tpl->assign('search',$search);
        $this->tpl->assign('course',$course);
        $this->tpl->display('content');
	}

	private function addCourse(){
		$page = $this->ev->get("page");
		if($this->ev->post("insertCourse")){
            $args = $this->ev->post("args");
            $course = $this->attach->getCourseByName($args['courese_name']);
            if ($course) {
                $errmsg = "该课件名称已存在";
            }
            if ($errmsg) {
                $message = array(
                    'statusCode' => 300,
                    "message" => "{$errmsg}",
                    "navTabId" => "",
                    "rel" => ""
                );
                exit(json_encode($message));
            }
            $search = $this->ev->get('search');
            $u = '';
            if ($search) {
                foreach ($search as $key => $arg) {
                    $u .= "&search[{$key}]={$arg}";
                }
            }
            $this->attach->insertCourse($args);
            $message = array(
                'statusCode' => 200,
                "message" => "操作成功",
                "callbackType" => "forward",
                "forwardUrl" => "index.php?document-master-files-getCourseFile&page={$page}{$this->u}"
            );
            exit(json_encode($message));
		}else{
            $this->tpl->assign('page',$page);
            $this->tpl->display('content_add');
		}

	}

	private function courseEdit(){
        $page = $this->ev->get("page");
		if($this->ev->post("modifyCourse")){
            $args = $this->ev->post("args");
            $course_id = $this->ev->post("custom_course_id");
            $course = $this->attach->getCourseByName($args['course_name'],$course_id);
            if ($course) {
                $errmsg = "该课件名称已存在";
            }
            if ($errmsg) {
                $message = array(
                    'statusCode' => 300,
                    "message" => "{$errmsg}",
                    "navTabId" => "",
                    "rel" => ""
                );
                exit(json_encode($message));
            }
            $search = $this->ev->get('search');
            $u = '';
            if ($search) {
                foreach ($search as $key => $arg) {
                    $u .= "&search[{$key}]={$arg}";
                }
            }
            $this->attach->updateCourse($args,$course_id);
            $message = array(
                'statusCode' => 200,
                "message" => "操作成功",
                "callbackType" => "forward",
                "forwardUrl" => "index.php?document-master-files-getCourseFile&page={$page}{$this->u}"
            );
            exit(json_encode($message));
		}else{
            $course_id = $this->ev->get("course_id");
            $course = $this->attach->getCourseById($course_id);
            $this->tpl->assign('course',$course);
            $this->tpl->display('content_edit');
		}
	}


}


?>
