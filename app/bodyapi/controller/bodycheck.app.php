<?php
/*
 * Created on 2016-5-19
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
include 'PHPExcel/PHPExcel.php';
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

    /**
     * 获取所有系统课件
     */
	private function getAllSystemCourse(){
		$courseList = $this->api->getAllSystemCourse();
		exit(json_encode($courseList));
	}

    /**
     * 获取所有病例
     */
	private function getAllCase(){
		$case = $this->api->getAllCase();
        exit(json_encode($case));
	}

    /**
     * 获取所有鉴别听诊
     */
	private function getAllDiscern(){
        $discern = $this->api->getAllDiscern();
        foreach ($discern as $k=>$v){
            $discern[$k]['sound_list'] = $this->api->getSoundById($v['discern_id']);
        }
        exit(json_encode($discern));
	}

    /**
     * 获取鉴别听诊套餐
     */
    private function getDiscernPackage(){
        $discern = $this->api->getAllDiscern();
        foreach ($discern as $k=>$v){
            $discern[$k]['sound_list'] = $this->api->getSoundById($v['discern_id']);
        }
        exit(json_encode($discern));
    }

    /**
     * 获取所有自定义课件
     */
    private function getAllCustomCourse(){
        $customCourse = $this->api->getAllCustomCourse();
        exit(json_encode($customCourse));
    }

    /**
     * 根据鉴别听诊ID获取详情
     */
	private function getDiscernById(){
    	$discernId = $this->ev->post('discern_id');
        $discern = $this->api->getDiscernById($discernId);
        $discern['sound_list'] = $this->api->getSoundById($discernId);
//		foreach ($discern['sound_list'] as $k=>$v){
//            $discern['sound_list'][$k]['position_list'] = $this->api->getPositionByCaseId($v['case_id']);
//		}
        exit(json_encode($discern));
	}

    /**
     * 根据病例ID获取详情
     */
	private function getSoundByCaseId(){
        $caseId = $this->ev->post('case_id');
        $sounds = $this->api->getCaseById($caseId);
		$soundPostion = $this->api->getPositionByCaseId($caseId);

		$sounds['fun_name']='diffSound';
		foreach ($soundPostion as $k=>$v){
			$sounds['sound_list'][$k]['position']=$v['play_position'];
			$sounds['sound_list'][$k]['volume']=$v['sound_volume'];

		}
        exit(json_encode($sounds));
	}

    /**
     * 获取病例套餐详情
     */
	private function getSoundCasePackageById(){
		$packageId = $this->ev->post("soundcase_package_id");
		$package = $this->api->getSoundCasePackageById($packageId);
	}

    /**
     * 登录验证
     */
    private function loginCheck()
    {
//        exit(json_encode($_POST));
        $appid = 'user';
        $app = $this->G->make('apps', 'core')->getApp($appid);
        $this->tpl->assign('app', $app);
        $args['username'] = $this->ev->post('user_username');
        $args['userpassword'] = $this->ev->post('user_password');
        $user = $this->user->getUserByUserName($args['username']);
        if ($user['userid']) {
            if ($user['userpassword'] == md5($args['userpassword'])) {
                if ($app['appsetting']['loginmodel'] == 1) $this->session->offOnlineUser($user['userid']);

                $this->session->setSessionUser(array('sessionuserid' => $user['userid'], 'sessionpassword' => $user['userpassword'], 'sessionip' => $this->ev->getClientIp(), 'sessiongroupid' => $user['usergroupid'], 'sessionlogintime' => TIME, 'sessionusername' => $user['username']));
                $m = array(
                    'state' => "1",
                    "reason" => "",
                    "user_pic" => $user['photo'],
                    "user_id" => $user['userid']
                );
                $logArgs['loguserid'] = $user['userid'];
                $logArgs['logtime'] = TIME;
                $logArgs['loginfrom'] = 1;
                $this->user->insertLog($logArgs);
                exit(json_encode($m));
            } else {
                $m = array(
                    'state' => "0",
                    'reason' => "0",
                    "user_pic" => "",
                    "user_id" => $user['userid']
                );
                exit(json_encode($m));
            }
        }else{
            $m = array(
                'state' => "0",
                'reason' => "1",
                "user_pic" => "",
                "user_id" => $user['userid']
            );
            exit(json_encode($m));
        }
    }


    private function getExamsByUsername(){
	    $userName = $this->ev->post("user_name");
	    $data = $_POST;
        $this->save_log($data,"exam");
//	    $userName="15021464552";
        $exams = $this->api->getExamByUsername($userName);
        $basic = $this->getExamsInfos($exams);
        exit(json_encode($basic));
    }

    /**
     * 用户考试
     */
    private function userExam(){
        $userName = $this->ev->post("user_name");
        $user = $this->user->getUserByUserName($userName);
        $examId = $this->ev->post("exam_id");
        $args[]=array("AND", "obuserid = :obuserid", 'obuserid', $user['userid']);
        $args[]=array("AND", "obbasicid = :obbasicid", 'obbasicid', $examId);
        $exams = $this->api->getExamByUser($args);


        $args1[]=array("AND", "ehbasicid = :ehbasicid", 'ehbasicid', $examId);
        $args1[]=array("AND", "ehuserid = :ehuserid", 'ehuserid', $user['userid']);
        $examHistory = $this->api->getExamHistoryBuUserExam($args1);
        if($exams && !$examHistory) $str = true;
        else $str = false;
        exit(json_encode($str));
    }

    /**
     * 获取考试详情
     * @param $exams
     * @return mixed
     */
    private function getExamsInfos($exams){
        foreach ($exams as $k=>$v){
            $args['obuserid']=$v['basicid'];
            $basic[$k]=$this->api->getExamById($v['basicid']);
            $place = $this->api->getPlaceById($v['placeid']);
            $basic[$k]['area'] = $place['place'];
            $basic[$k]['address'] = $place['address'];
            $basic[$k]['open_num'] = $this->api->getOpenBasicNumber($v['basicid']);
            $theoryId = $basic[$k]['basicexam']['auto']==0 ? $basic[$k]['basicexam']['self'] : $basic[$k]['basicexam']['auto'];
            //已考人数
            $basic[$k]['basicexam']['finish_theory_num'] = $this->api->getFinisNum($theoryId,$v['basicid']);
            $basic[$k]['basicexam']['finish_heartlung_num'] = $this->api->getFinisNum($basic[$k]['basicexam']['heart_lung'],$v['basicid']);
            $basic[$k]['basicexam']['finish_bowel_num'] = $this->api->getFinisNum($basic[$k]['basicexam']['bowel'],$v['basicid']);
            $basic[$k]['basicexam']['finish_touch_num'] = $this->api->getFinisNum($basic[$k]['basicexam']['touch'],$v['basicid']);
            $basic[$k]['basicexam']['finish_cardiogram_num'] = $this->api->getFinisNum($basic[$k]['basicexam']['cardiogram'],$v['basicid']);

            //总分
            $basic[$k]['basicexam']['theory_score'] = $this->api->getPaperInfo($theoryId)['examsetting']['score'];
            $basic[$k]['basicexam']['heart_lung_score'] = $this->api->getPaperInfo($basic[$k]['basicexam']['heart_lung'])['examsetting']['score'];
            $basic[$k]['basicexam']['bowel_score'] = $this->api->getPaperInfo($basic[$k]['basicexam']['bowel'])['examsetting']['score'];
            $basic[$k]['basicexam']['touch_score'] = $this->api->getPaperInfo($basic[$k]['basicexam']['touch'])['examsetting']['score'];
            $basic[$k]['basicexam']['cardiogram_score'] = $this->api->getPaperInfo($basic[$k]['basicexam']['cardiogram'])['examsetting']['score'];

            //及格分数
            $basic[$k]['basicexam']['theory_passscore'] = $this->api->getPaperInfo($theoryId)['examsetting']['passscore'];
            $basic[$k]['basicexam']['heart_lung_passscore'] = $this->api->getPaperInfo($basic[$k]['basicexam']['heart_lung'])['examsetting']['passscore'];
            $basic[$k]['basicexam']['bowel_passscore'] = $this->api->getPaperInfo($basic[$k]['basicexam']['bowel'])['examsetting']['passscore'];
            $basic[$k]['basicexam']['touch_passscore'] = $this->api->getPaperInfo($basic[$k]['basicexam']['touch'])['examsetting']['passscore'];
            $basic[$k]['basicexam']['cardiogram_passscore'] = $this->api->getPaperInfo($basic[$k]['basicexam']['cardiogram'])['examsetting']['passscore'];

            //考试时间
            $basic[$k]['basicexam']['theory_examtime'] = $this->api->getPaperInfo($theoryId)['examsetting']['examtime'];
            $basic[$k]['basicexam']['heart_lung_examtime'] = $this->api->getPaperInfo($basic[$k]['basicexam']['heart_lung'])['examsetting']['examtime'];
            $basic[$k]['basicexam']['bowel_examtime'] = $this->api->getPaperInfo($basic[$k]['basicexam']['bowel'])['examsetting']['examtime'];
            $basic[$k]['basicexam']['touch_examtime'] = $this->api->getPaperInfo($basic[$k]['basicexam']['touch'])['examsetting']['examtime'];
            $basic[$k]['basicexam']['cardiogram_examtime'] = $this->api->getPaperInfo($basic[$k]['basicexam']['cardiogram'])['examsetting']['examtime'];
            unset($basic[$k]['basicareaid']);
            unset($basic[$k]['basicsubjectid']);
            unset($basic[$k]['placeid']);
            unset($basic[$k]['basicsection']);
            unset($basic[$k]['basicknows']);
            unset($basic[$k]['basicapi']);
            unset($basic[$k]['basicdemo']);
            unset($basic[$k]['basicthumb']);
            unset($basic[$k]['basicprice']);
            unset($basic[$k]['basicclosed']);
            unset($basic[$k]['basicclosed']);
            //examsetting examtime考试时间  score总分 passscore及格分数
        }

        return $basic;
    }

   /* private function getExamByUserName(){
        $userName = $this->ev->post('user_name');
//        $userName = "peadmin";
        $exams = $this->api->getExamByUserName($userName);
        exit(json_encode($exams));
    }*/

    private function getExamByUser(){
        $userName = $this->ev->post('user_name');
//        $userName = "15021464551";
        $user = $this->user->getUserByUserName($userName);
        $args[]=array("AND", "obuserid = :obuserid", 'obuserid', $user['userid']);
        $examIds = $this->api->getExamByUser($args);
        foreach ($examIds as $k => $v){
            $basic=$this->api->getExamById($v['obbasicid']);
            if($basic) $exams[]=$basic;
        }
        $basic = $this->getExamsInfos($exams);
        exit(json_encode($basic));
    }

    private function getExamByUserDate(){
        $userName = $this->ev->post('user_name');
//        $userName = "15021464551";
        $user = $this->user->getUserByUserName($userName);
        $today =strtotime(date('Y-m-d',time()));
        $todayExam = $this->api->getExamByDate($today);
        $args[]=array("AND", "obuserid = :obuserid", 'obuserid', $user['userid']);
        $args[]=array("AND", "obbasicid = :obbasicid", 'obbasicid', $todayExam['event_content_id']);
        $examIds = $this->api->getExamByUser($args);
        if($examIds){
            $exam[]=$this->api->getExamById($todayExam['event_content_id']);
            $basic = $this->getExamsInfos($exam);
            exit(json_encode($basic));
        }else{
            exit(json_encode("false"));
        }
    }

    private function checkUserExam(){

        $userName = $this->ev->post('user_name');
        $examId = $this->ev->post("exam_id");
        $res = "";
        $user = $this->user->getUserByUserName($userName);
        $args[]=array("AND", "obuserid = :obuserid", 'obuserid', $user['userid']);
        $args[]=array("AND", "obbasicid = :obbasicid", 'obbasicid', $examId);
        $examUser = $this->api->getExamByUserId($args);
        if($examUser) $res= true;
        else $res=false;
        exit(json_encode($res));
    }

    /**
     * 获取某场考试所有学生
     */
    private function getExamUsers(){
        $examId = $this->ev->post("exam_id");
        $users = $this->api->getExamUsers($examId);
        exit(json_encode($users));
    }

    private function getExamInfoById(){
        $examId = $this->ev->post("exam_id");
        $basic=$this->api->getExamById($examId);
        if($basic) $exams[]=$basic;
        $basic = $this->getExamsInfos($exams);

        exit(json_encode($basic));
    }

    /**
     * 获取考卷详情
     */
    private function getPaperInfo(){
        $paperId = $this->ev->post('paper_id');
//        $paperId = 10;
//        $data=$_POST;
        $r = $this->exam->getExamSettingById($paperId);
        $questions = array();
        $questionrows = array();
        foreach ($r['examquestions'] as $key => $p) {

            if($r['question_type']==1){
                foreach ($r['examquestions'] as $key => $p) {
                    $qids = '';
                    $qrids = '';
                    if ($p['questions']) $qids = trim($p['questions'], " ,");
                    if ($qids)
                        $questions[$key] = $this->exam->getQuestionListByIds($qids);
                    if ($p['rowsquestions']) $qrids = trim($p['rowsquestions'], " ,");
                    if ($qrids) {
                        $qrids = explode(",", $qrids);
                        foreach ($qrids as $t) {
                            $qr = $this->exam->getQuestionRowsById($t);
                            if ($qr)
                                $questionrows[$key][$t] = $qr;
                        }
                    }
                }
            }else{
                foreach ($r['examquestions'] as $key => $p) {
                    $qids = '';
                    if ($p['questions']) $qids = trim($p['questions'], " ,");
                    if ($qids)
                        $question[$key] = $this->exam->getQuestionsByIds($qids);
                }
                foreach ($question as $k=>$v){
                    foreach ($v as $k1=>$v1){
                        $questions[$k][$k1]['questionid'] = $v1['opt_question_id'];
                        $questions[$k][$k1]['questiontype'] = 1;
                        $questions[$k][$k1]['question'] = "";
                        $questions[$k][$k1]['questionuserid'] = 0;
                        $questions[$k][$k1]['questionusername'] = "";
                        $questions[$k][$k1]['questionlastmodifyuser'] = "";
                        $questions[$k][$k1]['questionselectnumber'] = count($v1['item']);
                        $questions[$k][$k1]['questionanswer'] = $v1['right_item'];
                        $questions[$k][$k1]['questiondescribe'] = $v1['opt_question_memo'];
                        $questions[$k][$k1]['questionknowsid'] = "";
                        $questions[$k][$k1]['questioncreatetime'] = $v1['addtime'];
                        $questions[$k][$k1]['questionstatus'] = 1;
                        $questions[$k][$k1]['questionhtml'] = false;
                        $questions[$k][$k1]['questionparent'] = 0;
                        $questions[$k][$k1]['questionsequence'] = 0;
                        $questions[$k][$k1]['questionlevel'] = 1;
                        $questions[$k][$k1]['questionaboutfile'] = $v1['about_file'];
                        $questionStr = "&lt;p&gt;";
                        $soundStr = "&lt;p&gt;";
                        //&lt;p&gt;A:张治中&lt;/p&gt;&lt;p&gt;B:张自忠&lt;/p&gt;&lt;p&gt;C:赵登禹&lt;/p&gt;&lt;p&gt;D:左权&lt;/p&gt;
                        $search = array("files/lung/",'files/heart/');
                        foreach ($v1['item'] as $kk=>$vv){
                            if(($v1['organ_type']==0 || $v1['organ_type']==1) && $vv['item_title']==$v1['right_item']){
                                $questions[$k][$k1]['questionaboutfile']=str_replace($search,"",$vv['sound_file']);
                            }
                            $questionStr.=$vv['item_title'].":".$vv["item_content"]."&lt;/p&gt;&lt;p&gt;";
                            $soundStr.=$vv['item_title'].":".str_replace($search,"",$vv['sound_file'])."&lt;/p&gt;&lt;p&gt;";
                        }
                        $questions[$k][$k1]['questionselect'] = $questionStr;
                        $questions[$k][$k1]['questionsound'] = $soundStr;
                    }
                }
            }
        }
        $args['examsessionquestion'] = array('questions' => $questions, 'questionrows' => $questionrows);
        $args['examsessionsetting'] = $r;
        $args['examsessionstarttime'] = TIME;
        $args['examsession'] = $r['exam'];
        $args['examsessionscore'] = 0;
        $args['examsessiontime'] = $r['examsetting']['examtime'];
        $args['examsessiontype'] = 2;
        $args['examsessionkey'] = $r['examid'];
        $args['examsessionissave'] = 0;

        exit(json_encode($args));
    }


    private function _getPaperInfo($paperId){

//        $paperId = 10;
//        $data=$_POST;
        $r = $this->exam->getExamSettingById($paperId);
        $questions = array();
        $questionrows = array();
        foreach ($r['examquestions'] as $key => $p) {

            if($r['question_type']==1){
                foreach ($r['examquestions'] as $key => $p) {
                    $qids = '';
                    $qrids = '';
                    if ($p['questions']) $qids = trim($p['questions'], " ,");
                    if ($qids)
                        $questions[$key] = $this->exam->getQuestionListByIds($qids);
                    if ($p['rowsquestions']) $qrids = trim($p['rowsquestions'], " ,");
                    if ($qrids) {
                        $qrids = explode(",", $qrids);
                        foreach ($qrids as $t) {
                            $qr = $this->exam->getQuestionRowsById($t);
                            if ($qr)
                                $questionrows[$key][$t] = $qr;
                        }
                    }
                }
            }else{
                foreach ($r['examquestions'] as $key => $p) {
                    $qids = '';
                    if ($p['questions']) $qids = trim($p['questions'], " ,");
                    if ($qids)
                        $question[$key] = $this->exam->getQuestionsByIds($qids);
                }
                foreach ($question as $k=>$v){
                    foreach ($v as $k1=>$v1){
                        $questions[$k][$k1]['questionid'] = $v1['opt_question_id'];
                        $questions[$k][$k1]['questiontype'] = 1;
                        $questions[$k][$k1]['question'] = "";
                        $questions[$k][$k1]['questionuserid'] = 0;
                        $questions[$k][$k1]['questionusername'] = "";
                        $questions[$k][$k1]['questionlastmodifyuser'] = "";
                        $questions[$k][$k1]['questionselectnumber'] = count($v1['item']);
                        $questions[$k][$k1]['questionanswer'] = $v1['right_item'];
                        $questions[$k][$k1]['questiondescribe'] = $v1['opt_question_memo'];
                        $questions[$k][$k1]['questionknowsid'] = "";
                        $questions[$k][$k1]['questioncreatetime'] = $v1['addtime'];
                        $questions[$k][$k1]['questionstatus'] = 1;
                        $questions[$k][$k1]['questionhtml'] = false;
                        $questions[$k][$k1]['questionparent'] = 0;
                        $questions[$k][$k1]['questionsequence'] = 0;
                        $questions[$k][$k1]['questionlevel'] = 1;
                        $questions[$k][$k1]['questionaboutfile'] = $v1['about_file'];
                        $questionStr = "&lt;p&gt;";
                        $soundStr = "&lt;p&gt;";
                        //&lt;p&gt;A:张治中&lt;/p&gt;&lt;p&gt;B:张自忠&lt;/p&gt;&lt;p&gt;C:赵登禹&lt;/p&gt;&lt;p&gt;D:左权&lt;/p&gt;
                        $search = array("files/lung/",'files/heart/');
                        foreach ($v1['item'] as $kk=>$vv){
                            if(($v1['organ_type']==0 || $v1['organ_type']==1) && $vv['item_title']==$v1['right_item']){
                                $questions[$k][$k1]['questionaboutfile']=str_replace($search,"",$vv['sound_file']);
                            }
                            $questionStr.=$vv['item_title'].":".$vv["item_content"]."&lt;/p&gt;&lt;p&gt;";
                            $soundStr.=$vv['item_title'].":".str_replace($search,"",$vv['sound_file'])."&lt;/p&gt;&lt;p&gt;";
                        }
                        $questions[$k][$k1]['questionselect'] = $questionStr;
                        $questions[$k][$k1]['questionsound'] = $soundStr;
                    }
                }
            }
        }
        $args['examsessionquestion'] = array('questions' => $questions, 'questionrows' => $questionrows);
        $args['examsessionsetting'] = $r;
        $args['examsessionstarttime'] = TIME;
        $args['examsession'] = $r['exam'];
        $args['examsessionscore'] = 0;
        $args['examsessiontime'] = $r['examsetting']['examtime'];
        $args['examsessiontype'] = 2;
        $args['examsessionkey'] = $r['examid'];
        $args['examsessionissave'] = 0;

       return $args;
    }

    private function getPaperById(){
        $paperId = $this->ev->post('paper_id');
        $paper   = $this->api->getPaperById($paperId);
        exit(json_encode($paper));
    }

    /**
     * 提交用户答案
     */
    private function submitExamAnwser(){
        $data = $this->ev->post("Answer");
        $data1 = $_POST;
        $this->save_log($data1,"answer");
        $this->save_log($data,"answer");
        $postAnswer = $order = json_decode($data1['Answer'],TRUE);
        $this->save_log($postAnswer,"answer");
//        $test["examID"]=18;
//        $test["basicID"]=87;
//        $test["userID"]=4;
//        $test["userName"]="15021464551";
//        $test["answerList"][0]["questionID"]=1;
//        $test["answerList"][0]["answer"]="E";
//        $test["answerList"][0]["timestamp"]="1506407059";
//        $json = json_encode($test);
//        echo $json;exit;

        //心肺音
        /*$postAnswer = array (
            'answerList' =>
                array (
                    0 =>
                        array (
                            'answer' => 'D',
                            'questionID' => 101,
                        ),
                    1 =>
                        array (
                            'answer' => 'E',
                            'questionID' => 102,
                        ),
                    2 =>
                        array (
                            'answer' => 'C',
                            'questionID' => 103,
                        ),
                    3 =>
                        array (
                            'answer' => 'E',
                            'questionID' => 104,
                        ),
                    4 =>
                        array (
                            'answer' => 'D',
                            'questionID' => 105,
                        ),
                ),
            'basicID' => '2',
            'examID' => '2',
            'userName' => '15021464554',
        );*/


        /*$postAnswer = array (
            'answerList' =>
                array (
                    0 =>
                        array (
                            'answer' => '1,3,7',
                            'questionID' => 118,
                        ),
                    1 =>
                        array (
                            'answer' => '4,10',
                            'questionID' => 119,
                        ),
                    2 =>
                        array (
                            'answer' => '4,9',
                            'questionID' => 120,
                        ),
                    3 =>
                        array (
                            'answer' => '4,11',
                            'questionID' => 121,
                        ),
                    4 =>
                        array (
                            'answer' => '5,12',
                            'questionID' => 122,
                        ),
                ),
            'basicID' => '2',
            'examID' => '6',
            'userName' => '15021464551',
        );*/


        //理论试题
        /*$postAnswer = array (
            'answerList' =>
                array (
                    0 =>
                        array (
                            'answer' => 'AD',
                            'questionID' => '662',
                        ),
                    1 =>
                        array (
                            'answer' => 'D',
                            'questionID' => '879',
                        ),
                    2 =>
                        array (
                            'answer' => 'A',
                            'questionID' => '876',
                        ),
                    3 =>
                        array (
                            'answer' => 'A',
                            'questionID' => '873',
                        ),
                    4 =>
                        array (
                            'answer' => 'BC',
                            'questionID' => '642',
                        ),
                    5 =>
                        array (
                            'answer' => 'ACD',
                            'questionID' => '538',
                        ),
                    6 =>
                        array (
                            'answer' => 'D',
                            'questionID' => '877',
                        ),
                    7 =>
                        array (
                            'answer' => 'AD',
                            'questionID' => '545',
                        ),
                    8 =>
                        array (
                            'answer' => 'A',
                            'questionID' => '875',
                        ),
                    9 =>
                        array (
                            'answer' => 'A',
                            'questionID' => '878',
                        ),
                    10 =>
                        array (
                            'answer' => 'C',
                            'questionID' => '880',
                        ),
                    11 =>
                        array (
                            'answer' => 'B',
                            'questionID' => '874',
                        ),
                    12 =>
                        array (
                            'answer' => 'D',
                            'questionID' => '872',
                        ),
                    13 =>
                        array (
                            'answer' => 'B',
                            'questionID' => '881',
                        ),
                    14 =>
                        array (
                            'answer' => 'ACD',
                            'questionID' => '638',
                        ),
                ),
            'basicID' => '2',
            'examID' => '1',
            'userName' => '15021464554',
        );*/

        $user = $this->user->getUserByUserName($postAnswer["userName"]);
        $basic = $this->basic->getBasicById($postAnswer["basicID"]);
        $r = $this->exam->getExamSettingById($postAnswer["examID"]);
        $questions = array();
        $questionrows = array();
        foreach($r['examquestions'] as $key => $p)
        {
            $qids = '';
            $qrids = '';
            $q = '';
            if($p['questions'])$qids = trim($p['questions']," ,");
            if($qids)
                if($r["question_type"]==1){
                    $q = $this->exam->getQuestionListByIds($qids);
                }else{
                    $qestionsTemp=$this->exam->getQuestionsByIds($qids);
                    $q="";
                    foreach ($qestionsTemp as $k=>$v){

                        $qName="";
                        if($v['organ_type']==0) $qName="心脏操作试题";
                        elseif($v['organ_type']==1) $qName="肺部操作试题";
                        elseif($v['organ_type']==2) $qName="腹部触诊操作试题";
                        elseif($v['organ_type']==4) $qName="心电图触诊操作试题";

                        $selectStr="";
                        foreach ($v['item'] as $k1=>$v1){
                            //&lt;p&gt;A:汉代&lt;/p&gt;&lt;p&gt;B:唐代&lt;/p&gt;&lt;p&gt;C:宋代&lt;/p&gt;&lt;p&gt;D:元代&lt;/p&gt;
                            if($k1==0){
                                $selectStr.="&lt;p&gt;".$v1['item_title'].":".$v1['case_name'];
                            }else{
                                $selectStr.="&lt;/p&gt;".$v1['item_title'].":".$v1['case_name'];
                            }
                        }

                        $q[$v["opt_question_id"]]['questionid']=$v["opt_question_id"];
                        $q[$v["opt_question_id"]]['questiontype']=1;
                        $q[$v["opt_question_id"]]['question']=$qName;
                        $q[$v["opt_question_id"]]['questionuserid']="0";
                        $q[$v["opt_question_id"]]['questionusername']="";
                        $q[$v["opt_question_id"]]['questionlastmodifyuser']="";
                        $q[$v["opt_question_id"]]['questionselect']=$selectStr;
                        $q[$v["opt_question_id"]]['questionselectnumber']=count($v['item']);
                        $q[$v["opt_question_id"]]['questionanswer']=$v['right_item'];
                        $q[$v["opt_question_id"]]['questiodescribe']="";
                        $q[$v["opt_question_id"]]['questioknowsid']=array();
                        $q[$v["opt_question_id"]]['questiocreatetime']=$v['addtime'];
                        $q[$v["opt_question_id"]]['questionstatus']="1";
                        $q[$v["opt_question_id"]]['questionhtml']=false;
                        $q[$v["opt_question_id"]]['questionparent']=0;
                        $q[$v["opt_question_id"]]['questionsequence']=0;
                        $q[$v["opt_question_id"]]['questionlevel']="2";
                    }
                }

                $questions[$key] =$q;
            if($p['rowsquestions'])$qrids = trim($p['rowsquestions']," ,");
            if($qrids)
            {
                $qrids = explode(",",$qrids);
                foreach($qrids as $t)
                {
                    $qr = $this->exam->getQuestionRowsById($t);
                    if($qr)
                        $questionrows[$key][$t] = $qr;
                }
            }
        }
        $args['examsessionquestion'] = array('questions'=>$questions,'questionrows'=>$questionrows);
        $args['examsessionsetting'] = $r;
        $args['examsessionstarttime'] = TIME;
        $args['examsession'] = $r['exam'];
        $args['examsessionscore'] = 0;
        $args['examsessionuseranswer'] = '';
        $args['examsessionscorelist'] = '';
        $args['examsessionsign'] = '';
        $args['examsessiontime'] = $r['examsetting']['examtime'];
        $args['examsessionstatus'] = 0;
        $args['examsessiontype'] = 2;
        $args['examsessionkey'] = $r['examid'];
        $args['examsessionissave'] = 0;
        $args['examsessionbasic'] = $postAnswer["basicID"];
        $args['examsessionuserid'] = $user["userid"];
        $args['examsessionusername'] = $postAnswer["userName"];

        $this->api->insertExamSession($args);

        $answerInfo = $this->getUserAnswers($postAnswer['answerList'],$questions,$r['examsetting']['questype']);

        $answer['examsessionuseranswer']=$answerInfo['answer_list'];
        $answer['examsessiontimelist']=$answerInfo['answer_time'];
        $answer['examsessionscorelist']=$answerInfo['answer_scrolist'];
        $answer['examsessionstatus']=2;
        $answer['examsessionscore']=$answerInfo['sum_score'];

        $sessionId=$postAnswer['examID'].$postAnswer['userName'].$postAnswer['basicID'];

        $this->exam->modifyExamSession($answer,$sessionId);

        $this->makescore($sessionId);
    }

    private function getUserAnswers($answer,$q,$r){
        $userAnswer="";
//        $sumScore=0;
        $questions = array();
        foreach ($q as $key=>$value){
            if($value!="") {
//                $questions = array_merge($questions,$value);
                foreach ($value as $key1=>$value1){
                   $questions[$key1]=$value1;
                }
            }
        }
        foreach ($answer as $k=>$v){
            $answerValue = explode(",",$v['answer']);
            $userAnswer['answer_list'][$v['questionID']]=count($answerValue)>1?$answerValue:$answerValue[0];
            $userAnswer['answer_time'][$v['questionID']]=$v['timestamp'];
            if($questions[$v['questionID']]['questionanswer']==$v['answer']){
                $userAnswer['answer_scrolist'][$v['questionID']]=$r[$questions[$v['questionID']]["questiontype"]]["score"];
            }else{
                $userAnswer['answer_scrolist'][$v['questionID']]=0;
            }
        }
        $userAnswer['sum_score'] =array_sum($userAnswer['answer_scrolist']) ;
        return $userAnswer;
    }


    private function makescore($sessionId)
    {
        $sessionvars = $this->exam->getExamSessionBySessionid($sessionId);

        $sumscore = 0;
        foreach($sessionvars['examsessionscorelist'] as $p)
        {
            $sumscore = $sumscore + floatval($p);
        }
        $sessionvars['examsessionscore'] = $sumscore;
        $args['examsessionscorelist'] = $sessionvars['examsessionscorelist'];
        $allnumber = floatval(count($sessionvars['examsessionscorelist']));
        $args['examsessionscore'] = $sessionvars['examsessionscore'];
        $args['examsessionstatus'] = 2;
        $this->exam->modifyExamSession($args);
        $id = $this->favor->addExamHistory($sessionId);
    }

    /**
     * 获取所有课程表
     */
    private function getTimeTables(){
        $data = $_POST;
        $this->save_log($data,"course");
        $userId = $this->ev->post('userId');
//        $userId = '15021464552';
        $user = $this->user->getUserByUserName($userId);
        $date = $this->ev->post('date');
        if(!$date) $date = date("Y-m-d",time());
        $args['user_id'] = $user['userid'];
        $args['start_time'] = $date;
        $timeTables = $this->api->getTimeTablesByUserDate($args);
        /*foreach ($timeTables as $k=>$v){
            if($v['event_type']==1){
                $coursePackage = $this->attach->getPackageById($v['event_content_id']);
                $courses = $this->attach->getCoursesByPackageId($coursePackage['course_package_id']);
                $timeTables[$k]['courses'] = $courses;
            }
            if($v['event_type']==2){
//                $exam = $this->
                $timeTables[$k]['exam'] = $v['event_content_id'];
            }
        }*/
        exit(json_encode($timeTables));
    }

    /**
     * 根据课程表ID获取详细信息
     */
    private function getInfoById(){
        $id = $this->ev->post('id');
//        $id = 4;
        $timeTable = $this->api->getTimeTableById($id);
        if($timeTable['event_type']==1){
            $coursePackage = $this->attach->getPackageById($timeTable['event_content_id']);
            $courses = $this->attach->getCoursesByPackageId($coursePackage['course_package_id']);
//            $timeTable['discern_id'] = $coursePackage['discern_id'];
            $timeTable['courses'] = $courses;

//            $coursePackage = $this->sound->getSoundCasePackageById($timeTable['discern_id'],1);
//            $cases = $this->sound->getSoundCasePackageById($coursePackage['course_package_id'],1);

            $casePackage = $this->sound->getCasePackage($coursePackage['discern_id']);
            $selectCase = "";
            $selectDiscern = "";
            $i=0;
            $ii=0;
            foreach ($casePackage as $k1=>$v1){
                if($v1['type_id']==0){
                    $soundCase = $this->sound->getSoundCaseById($v1['case_id']);
                    $selectCase[$i]['case_id'] = $soundCase['sound_case_id'];
                    $selectCase[$i]['case_name'] = $soundCase['sound_case_name'];
                    $i++;
                }else{
                    $discern = $this->sound->getDiscernById($v1['case_id']);
                    $selectDiscern[$ii]['discern_id'] = $discern['discern_id'];
                    $selectDiscern[$ii]['discern_name'] = $discern['discern_name'];
                    $selectDiscern[$ii]['organ_type'] = $discern['organ_type'];
                    $selectDiscern[$ii]['if_use'] = $discern['if_use'];
                    $selectDiscern[$ii]['sound_list'] = $this->api->getSoundById($discern['discern_id']);
                    $ii++;
                }
            }
            $timeTable['cases'] = $selectCase;
            $timeTable['discern'] = $selectDiscern;
        }
        if($timeTable['event_type']==2){
//                $exam = $this->
            $timeTable['exam'] = $timeTable['event_content_id'];
        }

        exit(json_encode($timeTable));
    }

    /**
     * 随机获取操作题
     */
    private function getOptQuestionRandom(){
        $num = $this->ev->post("num");
        $organ_type = $this->ev->post("organ_type");
//        $num = 5;
//        $organ_type=0;
        $typeNum = $this->api->getOptNumsByType($organ_type)['number'];
        $res['status'] = 1;
        if($typeNum<$num){
            $res['status'] = 0;
            $res['error_message'] = "选题数目不能超过 $typeNum";
            exit(json_encode($res));
        }else{
            $optQuestion = $this->api->getOptQuestionRandom($organ_type,$num);
            foreach ($optQuestion as $k=>$v){
                if($v['organ_type']==0 || $v['organ_type']==1){
                    $optQuestion[$k]['about_file'] = $this->api->getSoundByRightItme($v['opt_question_id'])['sound_name'];
                }
                $optQuestion[$k]['sound_name'] = $this->api->getSoundByRightItme($v['opt_question_id'])['sound_name'];
                $optQuestion[$k]['itmes']=$this->api->getOptQuestionItmeById($v['opt_question_id']);
            }
            $res['questions']=$optQuestion;
            exit(json_encode($res));
        }
    }

    /**
     * 理论考试练习模式
     */
    private function exercise()
    {
        $questype = $this->basic->getQuestypeList();
        if($this->ev->get('setExecriseConfig'))
        {
            $args = $this->ev->get('args');
            $sessionvars = $this->exam->getExamSessionBySessionid();
            if(!$args['knowsid'])
            {
                $args['knowsid'] = '';
                if($args['sectionid'])
                    $knowsids = $this->section->getKnowsListByArgs(array(array("AND","knowssectionid = :knowssectionid",'knowssectionid',$args['sectionid']),array("AND","knowsstatus = 1")));
                else
                {
                    $knowsids = $this->section->getAllKnowsBySubject($this->data['currentsubject']['subjectid']);
                }
                foreach($knowsids as $key => $p)
                    $args['knowsid'] .= intval($key).",";
                $args['knowsid'] = trim($args['knowsid']," ,");
            }
            arsort($args['number']);
            $snumber = 0;
            foreach($args['number'] as $key => $v)
            {
                $snumber += $v;
                if($snumber > 100)
                {
                    $message = array(
                        'statusCode' => 300,
                        "message" => "强化练习最多一次只能抽取100道题"
                    );
                    $this->G->R($message);
                }
            }
            $dt = key($args['number']);
            $questionids = $this->question->selectQuestionsByKnows($args['knowsid'],$args['number'],$dt);
            $questions = array();
            $questionrows = array();
            foreach($questionids['question'] as $key => $p)
            {
                $ids = "";
                if(count($p))
                {
                    foreach($p as $t)
                    {
                        $ids .= $t.',';
                    }
                    $ids = trim($ids," ,");
                    if(!$ids)$ids = 0;
                    $questions[$key] = $this->exam->getQuestionListByIds($ids);
                }
            }
            foreach($questionids['questionrow'] as $key => $p)
            {
                $ids = "";
                if(is_array($p))
                {
                    if(count($p))
                    {
                        foreach($p as $t)
                        {
                            $questionrows[$key][$t] = $this->exam->getQuestionRowsById($t);
                        }
                    }
                }
                else $questionrows[$key][$p] = $this->exam->getQuestionRowsByArgs("qrid = '{$p}'");
            }
            $sargs['examsessionquestion'] = array('questionids'=>$questionids,'questions'=>$questions,'questionrows'=>$questionrows);
            $sargs['examsessionsetting'] = $args;
            $sargs['examsessionstarttime'] = TIME;
            $sargs['examsessionuseranswer'] = NULL;
            $sargs['examsession'] = $args['title'];
            $sargs['examsessiontime'] = $args['time']>0?$args['time']:60;
            $sargs['examsessionstatus'] = 0;
            $sargs['examsessiontype'] = 0;
            $sargs['examsessionbasic'] = $this->data['currentbasic']['basicid'];
            $sargs['examsessionkey'] = $args['knowsid'];
            $sargs['examsessionissave'] = 0;
            $sargs['examsessionsign'] = NULL;
            $sargs['examsessionsign'] = '';
            $sargs['examsessionuserid'] = $this->_user['sessionuserid'];
            if($sessionvars['examsessionid'])
                $this->exam->modifyExamSession($sargs);
            else
                $this->exam->insertExamSession($sargs);
            $message = array(
                'statusCode' => 200,
                "message" => "抽题成功，正在转入试题页面",
                "callbackType" => 'forward',
                "forwardUrl" => "index.php?exam-app-exercise-paper"
            );
            $this->G->R($message);
        }
        else
        {
            $sections = $this->section->getSectionListByArgs(array(array("AND","sectionsubjectid = :sectionsubjectid",'sectionsubjectid',$this->data['currentbasic']['basicsubjectid'])));
            $knows = $this->section->getAllKnowsBySubject($this->data['currentbasic']['basicsubjectid']);
            $knowids = '';
            foreach($knows as $key => $p)
                $knowids .= "{$key},";
            $knowids = trim($knowids," ,");
            $numbers = array();
            foreach($questype as $p)
            {
                $numbers[$p['questid']] = intval(ceil($this->exam->getQuestionNumberByQuestypeAndKnowsid($p['questid'],$knowids)));
            }
            $this->tpl->assign('basicnow',$this->data['currentbasic']);
            $this->tpl->assign('sections',$sections);
            $this->tpl->assign('questype',$questype);
            $this->tpl->assign('numbers',$numbers);
            $this->tpl->display('exercise');
        }
    }

    /**
     * 更新用户密码
     */
    private function updatePassword(){
        $userName = $this->ev->post("user_name");
        $password = $this->ev->post("password");
        $args['userpassword']=$password;
//($res,$type,$functionName="",$url="")
//        $data= $_POST;
//        $this->save_log($userName,"password");
//        $this->save_log($password,"password");
        $id=$this->api->modifyUserPassword($args,$userName);

        if($id){
            $message = array(
                'status_code' => 1,
                "message" => "操作成功"
            );
        }else{
            $message = array(
                'status_code' => 0,
                "message" => "操作失败"
            );
        }
        exit(json_encode($message));
    }

    /**
     * 添加单元练习
     */
    private function addUnitTest(){
        $args['basic'] = "单元练习";
        $args['basicclosed'] = 0;
        $args['basictype'] = 1;
        $args['placeid']=9;
        $args['basicdescribe'] = "这是一个接口创建的单元练习考试";
        $args['adduserid'] = 1;

        $basicid = $this->basic->addBasic($args);

        $users = $this->user->getAllUser();
        foreach ($users as $k=>$v){
            $examUser[]=$v['userid'];
        }

        $time=365*24*3600;
        if (count($examUser) > 0) {
           $id = $this->basic->addExamUser($examUser, $basicid, $time);
        }

        if($basicid && $id){
            $message = array(
                'status_code' => 200,
                "message" => "操作成功"
            );
        }

        exit(json_encode($basicid));
    }

    private function getAllPaperByType(){
        $type = $this->ev->post("type");
        if (strlen($type)>0) {
            if($type==0){
                $args[] = array("AND", "question_type = :question_type", 'question_type', 1);
            }else{
                $args[] = array("AND", "organ_type = :organ_type", 'organ_type', $type);
            }
        }

        $paper = $this->api->getUnitTestPaperByType($args);

        exit(json_encode($paper));
    }

    private function setUnitTestPaper(){
        $basicid = $this->ev->post("exam_id");
        $paperId = $this->ev->post("page_id");
        $type = $this->ev->post("type");

        if($type==0){
            $args['basicexam']['self']=$paperId;
        }else if($type==1){
            $args['basicexam']['heart_lung']=$paperId;
        }else if($type==2){
            $args['basicexam']['touch']=$paperId;
        }else if($type==4){
            $args['basicexam']['cardiogram']=$paperId;
        }

        $args['basicexam']['model']=2;
        $args['basicexam']['changesequence']=0;
        $args['basicexam']['auto']=0;
        $args['basicexam']['autotemplate']='exampaper_paper';
        $args['basicexam']['selftemplate']='exam_paper';
//        $args['basicexam']['bowel']="";
        $args['basicexam']['selectrule']=0;
        $args['basicexam']['examnumber']=0;
        $args['basicexam']['notviewscore']=0;

        $this->basic->setBasicConfig($basicid, $args);
    }

    private function delUnitTest(){
        $this->api->delUnitTest();
    }

    private function getExamHistoryByUserExamPaper(){
        $paperId = $this->ev->post("paper_id");
        $examId = $this->ev->post("exam_id");
        $userName = $this->ev->post("user_name");

        $data = $_POST;
        $this->save_log($data,"history");

       /* $paperId = 2;
        $examId = 2;
        $userName = 15021464551;*/

       /* $data = $_POST;

        $this->save_log($data,"history");*/


       $paperInfo = $this->_getPaperInfo($paperId);


       $questions = $paperInfo["examsessionquestion"];
       foreach ($questions as $k=>$v){

           foreach ($v as $k1=>$v1){
               $q = array();
               foreach ($v1 as $k2=>$v2){
                   $q[$v2["questionid"]] = $v2;
               }
               $questions[$k][$k1]=$q;
           }

       }


        $args[] = array("AND", "ehexamid = :ehexamid", 'ehexamid', $paperId);
        $args[] = array("AND", "ehbasicid = :ehbasicid", 'ehbasicid', $examId);
        $args[] = array("AND", "ehusername = :ehusername", 'ehusername', $userName);

        $info = $this->api->getExamHistoryByUserExamPaper($args);

        $info[0]["ehquestion"]=$questions;

        $userAnswer = $info[0]["ehuseranswer"];
        $i=0;
        foreach ($userAnswer as $k=>$v){
            $temp[$i]["question_id"]=$k;
            $temp[$i]["user_answer"]=count($v)>1?implode(",",$v):$v;
            $i++;
        }
        $info[0]["ehuseranswer"]=$temp;
        $paperInfo['useranswer'] = $temp;
//        foreach ($infos as $k=>$v){
//
//        }
        exit(json_encode($info[0]));
//        exit(json_encode($paperInfo));
    }

    private function countExam(){
        $paperId = $this->ev->post("paper_id");
        $examId = $this->ev->post("exam_id");

        /*$paperId = 2;
        $examId = 2;*/
        $args[] = array("AND", "ehexamid = :ehexamid", 'ehexamid', $paperId);
        $args[] = array("AND", "ehbasicid = :ehbasicid", 'ehbasicid', $examId);
        $info = $this->api->getExamHistoryByUserExamPaper($args);
        $questionIds = array_keys($info[0]["ehuseranswer"]);

        $rightAnswer = array();
        foreach ($info[0]["ehquestion"]["questions"] as $k=>$v){
            foreach ($v as $k1=>$v1){
                $rightAnswer[$k1]=$v1["questionanswer"];
            }
        }

        foreach ($questionIds as $k=>$v){
            $q[$v]=array();
        }

        foreach ($info as $k1=>$v1){
            foreach ($v1["ehuseranswer"] as $k2=>$v2){
                array_push($q[$k2],$v2);
            }
        }

        foreach ($q as $k=>$v){
            $q[$k]=array_count_values($q[$k]);
        }


        $j = 0;
        $res = array();
        foreach ($q as $k=>$v){
            $i = 0;
            foreach ($v as $k1=>$v1){
                $res[$j][$i]["questionId"]=$k;
                $res[$j][$i]["item"] = $k1;
                $res[$j][$i]["percent"] = round($v1/count($info),2)."";
                $res[$j][$i]["if_right"] = $k1==$rightAnswer["$k"]?1:0;
                $i++;
            }
            $j++;
        }
        $examcount[0]["examcount"] = $res;
        exit(json_encode($examcount));
    }


    private function getInfoByExamPaper(){
        $paperId = $this->ev->post("paper_id");
        $examId = $this->ev->post("exam_id");

        /*$paperId = 1;
        $examId = 2;*/

        $args[] = array("AND", "ehexamid = :ehexamid", 'ehexamid', $paperId);
        $args[] = array("AND", "ehbasicid = :ehbasicid", 'ehbasicid', $examId);

        $infos = $this->api->getInfoByExamPaper($args);
        foreach ($infos as $k => $v){
            $user = $this->user->getUserById($v["ehuserid"]);
            $infos[$k]["usertruename"]=$user["usertruename"];
        }
        exit(json_encode($infos));
    }

    private function readOptionHeard(){
        $filePath = 'files/question.xls';
        $reader = new PHPExcel_Reader_Excel2007();
        if (!$reader->canRead($filePath)) {
            $reader = new PHPExcel_Reader_Excel5();
            if (!$reader->canRead($filePath)) {
                echo 'no Excel';
                return false;
            }
        }

        $PHPExcel = $reader->load($filePath);
        $sheet = $PHPExcel->getSheet(1);
        $highestRow = $sheet->getHighestRow(); // 取得总行数
        for ($row = 2; $row <= $highestRow; $row++){
            /*$path['path'] = $sheet->getCell('C'.$row)->getValue();
            $name = $sheet->getCell('A'.$row)->getValue();
            $this->api->updateSoundCase($path,$name);*/
            $args['organ_type']=1;
            $args['right_item']=$sheet->getCell('F'.$row)->getValue();

            foreach(range('A','E') as $k=>$v){
//                $item[$k]['opt_question_id'] = $questionId;
                $itemTitle = $sheet->getCell($v.$row)->getValue();
                $resItem = $this->api->getCaseByName($itemTitle);
                if(!$resItem && $args['right_item'] == $v){
                    echo $row."行-".$itemTitle."<br />";
//                    return false;
                }
                $item[$k]['item_title'] = $v;
                $item[$k]['item_content'] = $itemTitle;
                $item[$k]['case_id'] =$resItem ? $resItem['case_id']:0;
                if($args['right_item'] == $v) $item[$k]["if_right"] = 1;
                else $item[$k]["if_right"] = 0;
            }

            $args['itmes']=$item;
            $data[]=$args;
        }
//echo "1";
//        $this->exam->addOptQuestion();
        foreach ($data as $k=>$v){
            $mainTable="";
            $mainTable['organ_type']=$v['organ_type'];
            $mainTable['right_item']=$v['right_item'];
            $mainTable['add_name']="admin";
            $optId=$this->exam->addOptQuestion($mainTable);
            $subTable="";
            foreach ($v['itmes'] as $key=>$value){
                $subTable[$key]['item_title'] = $value['item_title'];
                $subTable[$key]['item_content'] = $value['item_content'];
                $subTable[$key]['case_id'] = $value['case_id'];
                $subTable[$key]['if_right'] = $value['if_right'];
                $subTable[$key]['opt_question_id'] = $optId;
                $this->exam->addOptQuestionItem($subTable);
            }

        }
    }


    private function readOptionCardiogram(){
        $filePath = 'files/question.xls';
        $reader = new PHPExcel_Reader_Excel2007();
        if (!$reader->canRead($filePath)) {
            $reader = new PHPExcel_Reader_Excel5();
            if (!$reader->canRead($filePath)) {
                echo 'no Excel';
                return false;
            }
        }

        $PHPExcel = $reader->load($filePath);
        $sheet = $PHPExcel->getSheet(3);
        $highestRow = $sheet->getHighestRow(); // 取得总行数
        for ($row = 2; $row <= $highestRow; $row++){
            /*$path['path'] = $sheet->getCell('C'.$row)->getValue();
            $name = $sheet->getCell('A'.$row)->getValue();
            $this->api->updateSoundCase($path,$name);*/
            $args['organ_type']=4;
            $args['about_file']=$sheet->getCell('F'.$row)->getValue();
            $args['right_item']=$sheet->getCell('E'.$row)->getValue();

            foreach(range('A','D') as $k=>$v){
//                $item[$k]['opt_question_id'] = $questionId;
                $itemTitle = $sheet->getCell($v.$row)->getValue();
                $resItem = $this->api->getCaseByName($itemTitle);
                /*if(!$resItem && $args['right_item'] == $v){
                    echo $row."行-".$itemTitle."<br />";
                    return false;
                }*/
                $item[$k]['item_title'] = $v;
                $item[$k]['item_content'] = $itemTitle;
                $item[$k]['case_id'] =$resItem ? $resItem['case_id']:0;
                if($args['right_item'] == $v) $item[$k]["if_right"] = 1;
                else $item[$k]["if_right"] = 0;
            }

            $args['itmes']=$item;
            $data[]=$args;
        }
//echo "1";
//        $this->exam->addOptQuestion();
        foreach ($data as $k=>$v){
            $mainTable="";
            $mainTable['organ_type']=$v['organ_type'];
            $mainTable['right_item']=$v['right_item'];
            $mainTable['about_file']=$v['about_file'];
            $mainTable['add_name']="admin";
            $optId=$this->exam->addOptQuestion($mainTable);
            $subTable="";
            foreach ($v['itmes'] as $key=>$value){
                $subTable[$key]['item_title'] = $value['item_title'];
                $subTable[$key]['item_content'] = $value['item_content'];
                $subTable[$key]['case_id'] = $value['case_id'];
                $subTable[$key]['if_right'] = $value['if_right'];
                $subTable[$key]['opt_question_id'] = $optId;
                $this->exam->addOptQuestionItem($subTable);
            }

        }
    }

    private function readOptionTouch(){
        $filePath = 'files/question.xls';
        $reader = new PHPExcel_Reader_Excel2007();
        if (!$reader->canRead($filePath)) {
            $reader = new PHPExcel_Reader_Excel5();
            if (!$reader->canRead($filePath)) {
                echo 'no Excel';
                return false;
            }
        }

        $PHPExcel = $reader->load($filePath);
        $sheet = $PHPExcel->getSheet(2);
        $highestRow = $sheet->getHighestRow(); // 取得总行数
        for ($row = 2; $row <= $highestRow; $row++){
            /*$path['path'] = $sheet->getCell('C'.$row)->getValue();
            $name = $sheet->getCell('A'.$row)->getValue();
            $this->api->updateSoundCase($path,$name);*/
            $args['organ_type']=2;
            $args['right_item']=str_replace(";",",",$sheet->getCell('B'.$row)->getValue());
            $args['add_name']="admin";
            $optId=$this->exam->addOptQuestion($args);
            $item[0]['item_title'] = 1;
            $item[0]['item_content'] = $sheet->getCell('A'.$row)->getValue();
            $item[0]['case_id'] = 0;
            $item[0]["if_right"] = 0;
            $item[0]["opt_question_id"] = $optId;
            $this->exam->addOptQuestionItem($item);
        }
    }

    private function updateSoundCase(){
        $filePath = 'files/sc.xls';
        $reader = new PHPExcel_Reader_Excel2007();
        if (!$reader->canRead($filePath)) {
            $reader = new PHPExcel_Reader_Excel5();
            if (!$reader->canRead($filePath)) {
                echo 'no Excel';
                return false;
            }
        }

        $PHPExcel = $reader->load($filePath);
        $sheet = $PHPExcel->getSheet(0);
        $highestRow = $sheet->getHighestRow(); // 取得总行数
        for ($row = 2; $row <= $highestRow; $row++){
            $path['path'] = $sheet->getCell('C'.$row)->getValue();
            $name = $sheet->getCell('A'.$row)->getValue();
            $this->api->updateSoundCase($path,$name);
        }
    }

    private function readTree(){
        $filePath='files/tree1.xls';
        $reader = new PHPExcel_Reader_Excel2007();
        if(!$reader->canRead($filePath)){
            $reader = new PHPExcel_Reader_Excel5();
            if(!$reader->canRead($filePath)){
                echo 'no Excel';
            }
        }
        $PHPExcel = $reader->load($filePath);
        $sheet = $PHPExcel->getSheet(3);
        $highestRow = $sheet->getHighestRow(); // 取得总行数
        $highestColumm = $sheet->getHighestColumn(); // 取得总列数
        $highestColumm= PHPExcel_Cell::columnIndexFromString($highestColumm); //字母列转换为数字列 如:AA变为27

        /** 循环读取每个单元格的数据 */
        for ($row = 1; $row <= $highestRow; $row++){//行数是以第1行开始
            for ($column = 0; $column < $highestColumm; $column++) {//列数是以第0列开始
                $columnName = PHPExcel_Cell::stringFromColumnIndex($column);
//                echo $sheet->getCellByColumnAndRow($column, $row)->getValue()." <br />";
                $excelarr[] =  $sheet->getCellByColumnAndRow($column, $row)->getValue();
            }
//            $temparr[] =       $sheet->getCell('A'.$row)->getValue();
//            $temparr[] =       $sheet->getCell('B'.$row)->getValue();
//            $temparr[] =       $sheet->getCell('C'.$row)->getValue();
//            $temparr[] =       $sheet->getCell('D'.$row)->getValue();
//            $temparr[] =       $sheet->getCell('E'.$row)->getValue();
        }
        $a = array_filter($excelarr);
        foreach ($a as $k=>$v){
            $temp = explode(",",$v);
            $data="";
            $data['menu_name'] =$temp[0];
            $data['menu_code'] =$temp[1];
            //0:心音 1:呼吸音 2:肠鸣音 4心电图
            $data['menu_type'] = 4;
            if(count($temp)==3){
                $data['father_code'] =$temp[2];
            }
            $this->api->saveMenu($data);
        }
    }


    private function readSound(){
        $filePath='files/sound.xls';
        $reader = new PHPExcel_Reader_Excel2007();
        if(!$reader->canRead($filePath)){
            $reader = new PHPExcel_Reader_Excel5();
            if(!$reader->canRead($filePath)){
                echo 'no Excel';
            }
        }
        $PHPExcel = $reader->load($filePath);
        $sheet = $PHPExcel->getSheet(0);
        $highestRow = $sheet->getHighestRow(); // 取得总行数
        $highestColumm = $sheet->getHighestColumn(); // 取得总列数
        $highestColumm= PHPExcel_Cell::columnIndexFromString($highestColumm); //字母列转换为数字列 如:AA变为27

        /** 循环读取每个单元格的数据 */
        for ($row = 2; $row <= $highestRow; $row++){//行数是以第1行开始
//            for ($column = 0; $column < $highestColumm; $column++) {//列数是以第0列开始
//                $columnName = PHPExcel_Cell::stringFromColumnIndex($column);
////                echo $sheet->getCellByColumnAndRow($column, $row)->getValue()." <br />";
//                $excelarr[] =  $sheet->getCellByColumnAndRow($column, $row)->getValue();
//            }
            $args="";
            $sound="";
            $args['case_name'] =       $sheet->getCell('A'.$row)->getValue();
            $args['sound_name'] =       $sheet->getCell('F'.$row)->getValue();
            $args['sound_file'] =       "files/heart/".$sheet->getCell('F'.$row)->getValue();
            $args['case_type'] =       $sheet->getCell('C'.$row)->getValue();
            $args['organ_type'] =       $sheet->getCell('D'.$row)->getValue();
            $args['system_custom'] =       0;
            $args['tremble'] =       $sheet->getCell('E'.$row)->getValue();
            $sound['play_position'] =       explode(",",$sheet->getCell('G'.$row)->getValue());
            $sound['sound_volume'] =        explode(",",$sheet->getCell('H'.$row)->getValue());
            $this->sound->insertHeartCase($args,$sound);
        }
    }


    private function readLung(){
        $filePath='files/sound.xls';
        $reader = new PHPExcel_Reader_Excel2007();
        if(!$reader->canRead($filePath)){
            $reader = new PHPExcel_Reader_Excel5();
            if(!$reader->canRead($filePath)){
                echo 'no Excel';
            }
        }
        $PHPExcel = $reader->load($filePath);
        $sheet = $PHPExcel->getSheet(1);
        $highestRow = $sheet->getHighestRow(); // 取得总行数
        $highestColumm = $sheet->getHighestColumn(); // 取得总列数
        $highestColumm= PHPExcel_Cell::columnIndexFromString($highestColumm); //字母列转换为数字列 如:AA变为27

        /** 循环读取每个单元格的数据 */
        for ($row = 2; $row <= $highestRow; $row++){//行数是以第1行开始
//            for ($column = 0; $column < $highestColumm; $column++) {//列数是以第0列开始
//                $columnName = PHPExcel_Cell::stringFromColumnIndex($column);
////                echo $sheet->getCellByColumnAndRow($column, $row)->getValue()." <br />";
//                $excelarr[] =  $sheet->getCellByColumnAndRow($column, $row)->getValue();
//            }
            $args="";
            $sound="";
            $args['case_name'] =       $sheet->getCell('A'.$row)->getValue();
            $args['sound_name'] =       $sheet->getCell('E'.$row)->getValue();
            $args['sound_file'] =       "files/heart/".$sheet->getCell('E'.$row)->getValue();
            $args['case_type'] =       $sheet->getCell('C'.$row)->getValue();
            $args['organ_type'] =       $sheet->getCell('D'.$row)->getValue();
            $args['system_custom'] =       0;
            $args['tremble'] =       $sheet->getCell('E'.$row)->getValue();
            $sound['play_position'] =       explode(",",$sheet->getCell('F'.$row)->getValue());
            $soundPositions="";
            foreach ($sound["play_position"] as $k=>$v){
                if(stripos($v,"-")){
                    $arr = explode("-",$v);
                    for ($i=$arr[0];$i<=$arr[1];$i++){
                        $soundPositions['play_position'][]=$i."";
                    }
                }else{
                    $soundPositions['play_position'][]=$v;
                }
            }
            $volume = $sheet->getCell('G'.$row)->getValue();
            $soundPositions['sound_volume'] = array_fill(0,count($soundPositions['play_position']),$volume);
//            $sound['sound_volume'] =        explode(",",$sheet->getCell('H'.$row)->getValue());
            $this->sound->insertHeartCase($args,$soundPositions);
        }
    }

    private function readHeartSoundCase(){
        $filePath='files/111.xls';
        $reader = new PHPExcel_Reader_Excel2007();
        if(!$reader->canRead($filePath)){
            $reader = new PHPExcel_Reader_Excel5();
            if(!$reader->canRead($filePath)){
                echo 'no Excel';
            }
        }
        $PHPExcel = $reader->load($filePath);
        $sheet = $PHPExcel->getSheet(0);
        $highestRow = $sheet->getHighestRow(); // 取得总行数
        $highestColumm = $sheet->getHighestColumn(); // 取得总列数
        $highestColumm= PHPExcel_Cell::columnIndexFromString($highestColumm); //字母列转换为数字列 如:AA变为27
        $soundCase="";
        $i = 0;
        $ii = 0;

        $defalutSounds[0]['sound_file'] = "7.wav";
        $defalutSounds[0]['play_position'] = "26,35";
        $defalutSounds[0]['sound_volume'] = "90,90";

        $defalutSounds[1]['sound_file'] = "1.wav";
        $defalutSounds[1]['play_position'] = "14,15,16,18,19,20,21,22,23,24,25,29,30,31,32,33,34,37,38,39,40,41,43,44,45,46,47,48,49,50,51";
        $defalutSounds[1]['sound_volume'] = "100,100,100,100,100,100,100,100,100,100,100,100,100,100,100,100,100,100,100,100,100,100,100,100,100,100,100,100,100,100,100";

        $defalutSounds[2]['sound_file'] = "4.wav";
        $defalutSounds[2]['play_position'] = "7,8,9,17,27,28,36,42";
        $defalutSounds[2]['sound_volume'] = "100,100,100,100,100,100,100,100";

        for ($row = $highestRow; $row >=2; $row--) {//行数是以第1行开始
            $soundCase[$i]['sound_case_name'] =  $sheet->getCell('A'.$row)->getValue();
            $soundCase[$i]['case_type'] =  $sheet->getCell('B'.$row)->getValue();
            $soundCase[$i]['organ_type'] =  $sheet->getCell('C'.$row)->getValue();
            $soundCase[$i]['tremble'] =  $sheet->getCell('D'.$row)->getValue();
            $soundCase[$i]['sound_file'] =  $sheet->getCell('E'.$row)->getValue();
            $sounds['sound_file'] =  $sheet->getCell('E'.$row)->getValue();
            $sounds['play_position'] =  $sheet->getCell('F'.$row)->getValue();
            $sounds['sound_volume'] =  $sheet->getCell('G'.$row)->getValue();
            if($soundCase[$i]['sound_case_name']!="二尖瓣狭窄" && $soundCase[$i]['sound_case_name']!="主动脉瓣关闭不全" && $soundCase[$i]['sound_case_name']!=null){
                $temp[0]['sound_file'] =  $sounds['sound_file'];
                $temp[0]['play_position'] =  $sounds['play_position'];
                $temp[0]['sound_volume'] =  $sounds['sound_volume'];
                $soundCase[$i]['sounds']= array_merge_recursive($temp,$defalutSounds);
                $i++;
            }else{
                if($soundCase[$i]['sound_case_name']==null){
                    $soundCase[$i-1]['sounds'][$ii]=$sounds;
                    $ii++;
                }else{
                    $ii=0;
                    $soundCase[$i]['sounds'][$ii++]=$sounds;
                    $i++;

                }
            }

        }
        foreach ($soundCase as $v){
            $this->api->saveHeartSoundCase($v);
        }
    }

    private function readLungSoundCase(){
        $filePath='files/111.xls';
        $reader = new PHPExcel_Reader_Excel2007();
        if(!$reader->canRead($filePath)){
            $reader = new PHPExcel_Reader_Excel5();
            if(!$reader->canRead($filePath)){
                echo 'no Excel';
            }
        }
        $PHPExcel = $reader->load($filePath);
        $sheet = $PHPExcel->getSheet(2);
        $highestRow = $sheet->getHighestRow(); // 取得总行数
        $highestColumm = $sheet->getHighestColumn(); // 取得总列数
        $soundCase="";
        $i = 0;
        $ii = 0;

        for ($row = $highestRow; $row >=2; $row--) {//行数是以第1行开始
            $soundCase[$i]['sound_case_name'] =  $sheet->getCell('A'.$row)->getValue();
            $soundCase[$i]['case_type'] =  $sheet->getCell('B'.$row)->getValue();
            $soundCase[$i]['organ_type'] =  $sheet->getCell('C'.$row)->getValue();
            $soundCase[$i]['sound_file'] =  $sheet->getCell('D'.$row)->getValue();
            $sounds['sound_file'] =  $sheet->getCell('D'.$row)->getValue();
            $sounds['play_position'] =  $sheet->getCell('E'.$row)->getValue();
            $sounds['sound_volume'] =  $sheet->getCell('F'.$row)->getValue();

            if($soundCase[$i]['sound_case_name']==null){
                $soundCase[$i-1]['sounds'][$ii]=$sounds;
                $ii++;
            }else{
                $ii=0;
                $soundCase[$i]['sounds'][$ii++]=$sounds;
                $i++;
            }


        }
        foreach ($soundCase as $v){
            $this->api->saveLungSoundCase($v);
        }
    }

    private function readSystemCourse()
    {
        $filePath = 'files/kejianbc.xls';
        $reader = new PHPExcel_Reader_Excel2007();
        if (!$reader->canRead($filePath)) {
            $reader = new PHPExcel_Reader_Excel5();
            if (!$reader->canRead($filePath)) {
                echo 'no Excel';
                return false;
            }
        }
        $PHPExcel = $reader->load($filePath);
        $sheet = $PHPExcel->getSheet(0);
        $highestRow = $sheet->getHighestRow(); // 取得总行数
        $highestColumm = $sheet->getHighestColumn(); // 取得总列数
        for ($row = 2; $row <= $highestRow; $row++){
            $args = "";
            $args['course_name'] =       $sheet->getCell('B'.$row)->getValue();
            $args['course_code'] =       $sheet->getCell('A'.$row)->getValue();
            $args['course_menu'] =       $sheet->getCell('E'.$row)->getValue();
            $args['sound_case_name'] =   $sheet->getCell('F'.$row)->getValue();
            $args['flash_name'] =        $sheet->getCell('D'.$row)->getValue();

            $menuPath = $sheet->getCell('C'.$row)->getValue();
            $menuName = end(explode("@",$menuPath));
            $menu = $this->api->getMenuByName($menuName);
            if($menu){
                $args['menu_id'] = $menu['menu_id'];
                $args['menu_code'] = $menu['menu_code'];
            }else{
                echo "无此菜单".$args['course_name'];
                return false;
            }

            if($args['sound_case_name']!=""){
                $soundCase = $this->api->getSoundCaseByName($args['sound_case_name']);
                if($soundCase){
                    $args['sound_case_id'] = $soundCase['sound_case_id'];
                    $args['defalut_sound'] = $soundCase['sound_file'];
                }else{
                    echo "无此病例".$args['course_name'];
                    return false;
                }
            }

            $args['course_type']=0;
            $data[]=$args;
//            $this->api->insertSystemCourse($args);
        }
        foreach ($data as $k=>$v){
            $this->api->insertSystemCourse($v);
        }
    }

    /**
     * 日志方法
     * @param $res 传递 数组/JSON 详情
     * @param string $functionName 调用接口方法名称
     * @param string $url 调用接口URL
     */
    function save_log($res,$type,$functionName="",$url="") {
        $date = date("Y-m-d", time());
        //$address = '/var/log/error';
        $address = './application/apilog';
        if (!is_dir($address)) {
            mkdir($address, 0777, true);
        }
        $address = $address.'/'.$date . '_'.$type.'.log';
        $error_date = date("Y-m-d H:i:s", time());
        if(!empty($_SERVER['HTTP_REFERER'])) {
            $file = $_SERVER['HTTP_REFERER'];
        } else {
            $file = $_SERVER['REQUEST_URI'];
        }

        $res_real = "$error_date\t$file\t$functionName";
        file_put_contents($address, $res_real . PHP_EOL, FILE_APPEND);
        if($url!=""){
            file_put_contents($address, $url . PHP_EOL, FILE_APPEND);
        }
        $res = var_export($res,true);
        $res = $res."\n";
        file_put_contents($address, $res . PHP_EOL, FILE_APPEND);

    }

}


?>
