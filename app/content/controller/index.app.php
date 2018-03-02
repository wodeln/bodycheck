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
		if($this->ev->isMobile())
		{
			header("location:index.php?content-phone");
			exit;
		}
		$action = $this->ev->url(3);
		if(!method_exists($this,$action))
		$action = "index";
		$this->$action();
		exit;
	}

	private function index()
	{
		$catids = array();
		$catids['index'] = $this->category->getCategoriesByArgs(array(array("AND","catindex > 0")));
		$contents = array();
		if($catids['index'])
		{
			foreach($catids['index'] as $p)
			{
				$catstring = $this->category->getChildCategoryString($p['catid']);
				$contents[$p['catid']] = $this->content->getContentList(array(array("AND","find_in_set(contentcatid,:catstring)",'catstring',$catstring)),1,$p['catindex']?$p['catindex']:10);
			}
		}
		$this->category->app = 'course';
		$coursecats = $this->category->getCategoriesByArgs(array(array("AND","catparent = '0'")));
		$topcourse = array();
		foreach($coursecats as $cat)
		{
			$catstring = $this->category->getChildCategoryString($cat['catid']);
			$topcourse[$cat['catid']] =  $this->course->getCourseList(array(array("AND","find_in_set(cscatid,:cscatid)",'cscatid',$catstring)),1,6);
		}
		$question=$this->content->getShowHomeQuestion(1);
		if(!$question){
            $question=$this->content->getShowHomeQuestion(0);
		}
		$subQuestions = $this->content->getSubQuestionsByParentId($question["qrid"]);
		$question['sub'] = $subQuestions;
		$this->tpl->assign('topcourse',$topcourse);
		$courses = $this->course->getCourseList(1,1,8);
		$basic = $this->G->make('basic','exam');
		$basics = $basic->getBestBasics();
        $this->selectorder = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N');
        $vedio = $this->content->getShowHomeVedio(1);
        if(!$vedio){
            $vedio=$this->content->getShowHomeVedio(0);
        }
        $this->tpl->assign('vedio',$vedio);
        $this->tpl->assign('selectorder',$this->selectorder);
        $this->tpl->assign('question',$question);
		$this->tpl->assign('coursecats',$coursecats);
		$this->tpl->assign('courses',$courses);
		$this->tpl->assign('basics',$basics);
		$this->tpl->assign('contents',$contents);
		$this->tpl->display('index');
	}

    private function showhome(){
        $questionId = $this->ev->get('questionid');
        $number = $this->ev->get('number');
        $question=$this->content->getShowHomeQuestionById($questionId);
        $subQuestions = $this->content->getSubQuestionsByParentId($question["qrid"]);
        $question['sub'] = $subQuestions[$number-1];
        $this->selectorder = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N');
        $this->tpl->assign('selectorder',$this->selectorder);
        $this->tpl->assign('allnumber',count($subQuestions));
        $this->tpl->assign('question',$question);
        $this->tpl->assign('number',$number);

        $this->tpl->display("showhome");
    }

    private function showhomevedio(){
        $courseid = $this->ev->get('courseid');
        $vedio=$this->content->getShowHomeVedioById($courseid);
        $this->tpl->assign('vedio',$vedio);
        $this->tpl->display("showhomevedio");
    }

	private function test(){
    	$a=$this->ev->get("a");
    	$b=$this->ev->post("a");

        exit(json_encode($_POST));
//    	if($a!=""){
//            $a.=1;
//            exit(json_encode($a));
//		}else{
//            $b.=2;
//            exit(json_encode($b));
//		}

	}
   /* private function showTime(){
    	$question = $this->ev->get('question');
    	$this->content->getUser($question);
    	$this->assgin("selectorder",$this->selectactor());
    	return null;
	}*/
}


?>
