<?php

class api_bodyapi
{
	public $G;

	public function __construct(&$G)
	{
		$this->G = $G;
	}

	public function _init()
	{
		$this->categories = NULL;
		$this->tidycategories = NULL;
		$this->pdosql = $this->G->make('pdosql');
		$this->db = $this->G->make('pepdo');
		$this->pg = $this->G->make('pg');
		$this->ev = $this->G->make('ev');
	}

    /**
	 * 获取系统课件List
     * @return 系统课件List
     */
	public function getAllSystemCourse()
	{
		$data=array(false,"system_course");
        $sql = $this->pdosql->makeSelect($data);
		return $this->db->fetchAll($sql);
	}

    /**
	 * 获取标准化病例List
     * @return 标准化病例List
     */
	public function getAllCase(){
        $data=array(false,"sound_case");
        $sql = $this->pdosql->makeSelect($data);
        return $this->db->fetchAll($sql);
	}

    /**
	 * 获取鉴别听诊List
     * @return 鉴别听诊List
     */
    public function getAllDiscern(){
        $data=array(false,"discern");
        $sql = $this->pdosql->makeSelect($data);
        return $this->db->fetchAll($sql);
    }

    /**
	 * 获取自定义课件List
     * @return 自定义课件List
     */
    public function getAllCustomCourse(){
        $data=array(false,"custom_course");
        $sql = $this->pdosql->makeSelect($data);
        return $this->db->fetchAll($sql);
    }

    /**
	 * 根据ID获取鉴别听诊信息
     * @param $discernId 鉴别听诊ID
     * @return 鉴别听诊信息
     */
    public function getDiscernById($discernId){
        $data = array(false,array('discern'),array(array('AND',"discern_id = :discern_id",'discern_id',$discernId)));
        $sql = $this->pdosql->makeSelect($data);
        return $this->db->fetch($sql);
	}

    /**
	 * 根据鉴别听诊ID获取与其关联的声音信息
     * @param $discernId 鉴别听诊ID
     * @return 声音信息List
     */
	public function getSoundById($discernId){
//        $data = array(false,array('discern_sound'),array(array('AND',"discern_id = :discern_id",'discern_id',$discernId)));
//        $sql = $this->pdosql->makeSelect($data);
		$sql['sql'] = "SELECT c.sound_file,c.organ_type,ds.case_id,c.case_name FROM x2_discern_sound AS ds
						LEFT JOIN x2_case1 AS c ON ds.case_id=c.case_id
						WHERE ds.discern_id=$discernId";
		$sql['v']	= null;
        return $this->db->fetchAll($sql);
	}

    /**
	 * 根据声音ID获取与其关联的播放位置和播放音量信息
     * @param $caseId 声音ID
     * @return 播放位置播放音量List
     */
	public function getPositionByCaseId($caseId){
		$sql['sql']	= "SELECT play_position,sound_volume FROM x2_case_position WHERE case_id = $caseId";
		$sql['v']	= null;
        return $this->db->fetchAll($sql);
	}

    /**
	 * 根据ID获取声音信息
     * @param $caseId 声音ID
     * @return 声音信息详情
     */
	public function getCaseById($caseId){
        $sql['sql']	= "SELECT * FROM x2_case1 WHERE case_id = $caseId";
        $sql['v']	= null;
        return $this->db->fetch($sql);
	}

    /**
     * 根据用户名获取考试信息
     * @param $userName 用户名
     * @return mixed 考试List
     */
	public function getExamByUsername($userName){
        $sql['sql']	= "SELECT * FROM x2_basic WHERE addusername='$userName'";
        $sql['v']	= null;
        return $this->db->fetchAll($sql);
	}

	public function getExamByUser($args){
        $data = array(false,'openbasics',$args);
        $sql = $this->pdosql->makeSelect($data);
//        $sql['sql'] = "SELECT obbasicid FROM x2_openbasics WHERE obuserid=$userName";
//        $sql['v']	= null;
        return $this->db->fetchAll($sql);
    }

    public function getExamByUserId($args){
        $data = array(false,'openbasics',$args);
        $sql = $this->pdosql->makeSelect($data);
        return $this->db->fetchAll($sql);
    }

    public function getExamUsers($examId){
        $sql['sql'] = "SELECT u.userid,u.username,u.usertruename FROM x2_openbasics o 
                        LEFT JOIN x2_user u ON o.obuserid = u.userid
                        WHERE o.obbasicid=$examId";
        $sql['v']   = null;

        return $this->db->fetchAll($sql);
    }

    public function getCaseByName($case_name){
        $data = array(false,array('case1'),array(array('AND',"case_name = :case_name",'case_name',$case_name)));
        $sql = $this->pdosql->makeSelect($data);
        return $this->db->fetch($sql);
    }

    /**
     * 根据ID获取考试信息
     * @param $basicId 考试ID
     * @return mixed
     */
    public function getExamById($basicId){
        $data = array(false,'basic',array(array("AND","basicid = :basicid",'basicid',$basicId)));
        $sql = $this->pdosql->makeSelect($data);
        return $this->db->fetch($sql,array('basicknows','basicsection','basicexam'));
    }

    public function getExamByDate($today){
        $sql['sql'] = "SELECT event_content_id FROM calendar WHERE starttime=$today";
        $sql['v']   = null;
        return $this->db->fetch($sql);
    }

    /**
     * 获取ID获取地点信息
     * @param $placeId 考试ID
     * @return mixed
     */
    public function getPlaceById($placeId){
    	$sql['sql'] = "SELECT * FROM x2_place WHERE placeid=$placeId";
    	$sql['v']	= null;
    	return $this->db->fetch($sql);
	}

    /**
     * 根据考试ID获取开通人数
     * @param $basicid 考试ID
     * @return mixed
     */
    public function getOpenBasicNumber($basicid){
        $data = array("count(*) as number",'openbasics',array(array("AND","obbasicid = :obbasicid",'obbasicid',$basicid),array("AND","obendtime >= :obendtime",'obendtime',TIME)));
        $sql = $this->pdosql->makeSelect($data);
        $r = $this->db->fetch($sql);
        return $r['number'];
    }

    /**
     * 获取考卷已考人数
     * @param $examId 考试ID
     * @return mixed
     */
    public function getFinisNum($examId,$basicId){
        $data = array("count(*) as number",'examhistory',array(array("AND","ehexamid = :ehexamid",'ehexamid',$examId),array("AND","ehbasicid = :ehbasicid",'ehbasicid',$basicId)));
        $sql = $this->pdosql->makeSelect($data);
        $r = $this->db->fetch($sql);
        return $r['number'];
    }

    /**
     * 根据ID获取考卷信息
     * @param $paperId 考卷ID
     * @return mixed
     */
    public function getPaperInfo($paperId){
        $data = array(false,'exams',array(array("AND","examid = :examid",'examid',$paperId)));
        $sql = $this->pdosql->makeSelect($data);
        return $this->db->fetch($sql, array('examsetting', 'examquestions', 'examscore'));
    }

    public function getQuestionListByIds($ids, $fields = false)
    {
        $data = array($fields, 'questions', array(array("AND", "find_in_set(questionid,:ids)", 'ids', $ids), array("AND", "questionstatus = 1")), false, array("questionsequence ASC", "questionid ASC"), false);
        $sql = $this->pdosql->makeSelect($data);
        return $this->db->fetchAll($sql);
    }

    public function getPaperQuestion($examid){
        $r = $this->exam->getExamSettingById($examid);
        $questions = array();
        $questionrows = array();
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
        $args['examsessionquestion'] = array('questions' => $questions, 'questionrows' => $questionrows);
        $args['examsessionsetting'] = $r;
        $args['examsessionstarttime'] = TIME;
        $args['examsession'] = $r['exam'];
        $args['examsessionscore'] = 0;
        $args['examsessiontime'] = $r['examsetting']['examtime'];
        $args['examsessiontype'] = 2;
        $args['examsessionkey'] = $r['examid'];
        $args['examsessionissave'] = 0;
    }

    public function saveMenu($args){
        $data = array('menu',$args);
        $sql = $this->pdosql->makeInsert($data);
        $this->db->exec($sql);
    }

    public function saveHeartSoundCase($soundCase){
        $args['sound_case_name'] =  $soundCase['sound_case_name'];
        $args['case_type'] =  $soundCase['case_type'];
        $args['organ_type'] =  $soundCase['organ_type'];
        $args['tremble'] =  $soundCase['tremble'];
        $args['sound_file'] =  "files/heart/".$soundCase['sound_file'];

        $data = array('sound_case',$args);
        $sql = $this->pdosql->makeInsert($data);
        $this->db->exec($sql);
        $caseId = $this->db->lastInsertId();
        foreach ($soundCase["sounds"] as $k=>$v){
            $args1['sound_case_id']=$caseId;
            $args1['sound_file']="files/heart/".$v['sound_file'];
            $args1['file_name']=$v['sound_file'];
            $data = array("soundcase_sound",$args1);
            $sql = $this->pdosql->makeInsert($data);
            $this->db->exec($sql);
            $caseSoundId = $this->db->lastInsertId();
            $soundPositions=explode(",",$v["play_position"]);
            $soundVolume=explode(",",$v["sound_volume"]);
            foreach ($soundPositions as $k=>$v){
                $soundPosition = array();
                $soundPosition['soundcase_sound_id'] =$caseSoundId;
                $soundPosition['sound_case_id'] =$caseId;
                $soundPosition['play_position'] = $v;
                $soundPosition['sound_volume'] = $soundVolume[$k];
                $data = array("soundcase_position",$soundPosition);
                $sql = $this->pdosql->makeInsert($data);
                $this->db->exec($sql);
            }
        }
    }


    public function saveLungSoundCase($soundCase){
        $args['sound_case_name'] =  $soundCase['sound_case_name'];
        $args['case_type'] =  $soundCase['case_type'];
        $args['organ_type'] =  $soundCase['organ_type'];
        $args['tremble'] =  $soundCase['tremble'];
        $args['sound_file'] =  "files/belly/".$soundCase['sound_file'];

        $data = array('sound_case',$args);
        $sql = $this->pdosql->makeInsert($data);
        $this->db->exec($sql);
        $caseId = $this->db->lastInsertId();
        foreach ($soundCase["sounds"] as $k=>$v){
            $args1['sound_case_id']=$caseId;
            $args1['sound_file']="files/belly/".$v['sound_file'];
            $args1['file_name']=$v['sound_file'];
            $data = array("soundcase_sound",$args1);
            $sql = $this->pdosql->makeInsert($data);
            $this->db->exec($sql);
            $caseSoundId = $this->db->lastInsertId();
            $soundPositionsTemp=explode(",",$v["play_position"]);
            $soundVolumeTemp=explode(",",$v["sound_volume"]);
            $soundPositions="";
            $soundVolume="";
            foreach ($soundPositionsTemp as $k=>$v){
                if(stripos($v,"-")){
                    $arr = explode("-",$v);
                    for ($i=$arr[0];$i<=$arr[1];$i++){
                        $soundPositions[]=$i."";
                    }
                }else{
                    $soundPositions[]=$v;
                }
            }
            $soundVolume = array_fill(0,count($soundPositions),$soundVolumeTemp[0]);
            foreach ($soundPositions as $k=>$v){
                $soundPosition = array();
                $soundPosition['soundcase_sound_id'] =$caseSoundId;
                $soundPosition['sound_case_id'] =$caseId;
                $soundPosition['play_position'] = $v;
                $soundPosition['sound_volume'] = $soundVolume[$k];
                $data = array("soundcase_position",$soundPosition);
                $sql = $this->pdosql->makeInsert($data);
                $this->db->exec($sql);
            }
        }
    }

    public function insertSystemCourse($args){
        $data = array('system_course',$args);
        $sql = $this->pdosql->makeInsert($data);
        $this->db->exec($sql);
    }

    public function getMenuByName($menuName){
        $data = array(false,array('menu'),array(array('AND',"menu_name = :menu_name",'menu_name',$menuName)));
        $sql = $this->pdosql->makeSelect($data);
        return $this->db->fetch($sql);
    }

    public function getSoundCaseByName($soundCaseName){
        $data = array(false,array('sound_case'),array(array('AND',"sound_case_name = :sound_case_name",'sound_case_name',$soundCaseName)));
        $sql = $this->pdosql->makeSelect($data);
        return $this->db->fetch($sql);
    }

    public function updateSoundCase($args,$name){
        $data = array('system_course',$args,array(array('AND',"course_code = :course_code",'course_code',$name)));
        $sql = $this->pdosql->makeUpdate($data);
        return $this->db->exec($sql);
    }

    public function insertExamSession($args)
    {
        $args['examsessionid'] = $args['examsessionkey'].$args['examsessionuserid'];
        $args['examsessionstarttime'] = TIME;
        $data = array('examsession', $args);
        $sql = $this->pdosql->makeInsert($data);
        $this->db->exec($sql);
        return true;
    }

    public function getTimeTablesByUserDate($args){
        if($args['start_time']){
            $sql['sql'] = "SELECT * FROM calendar WHERE FROM_UNIXTIME(starttime,'%Y-%m-%d') = '".$args['start_time']."' AND user_id = '".$args['user_id']."'";
        }else{
            $sql['sql'] = "SELECT * FROM calendar WHERE user_id = '".$args['user_id']."'";
        }

        $sql['v']   = null;
        return $this->db->fetchAll($sql);
    }

    public function getTimeTableById($id){
        $sql['sql'] = "SELECT * FROM calendar WHERE id = $id";
        $sql['v']   = null;
        return $this->db->fetch($sql);
    }

    public function getOptNumsByType($organ_type){
        $data = array("COUNT(*) AS number",'opt_question',array(array('AND',"organ_type = :organ_type",'organ_type',$organ_type)));
        $sql = $this->pdosql->makeSelect($data);
        return $this->db->fetch($sql);
    }

    public function getOptQuestionRandom($organ_type,$num){
        $sql['sql'] = "SELECT * FROM x2_opt_question WHERE organ_type=$organ_type order by rand() LIMIT $num";
        $sql['v']   = null;
        return $this->db->fetchAll($sql);
    }

    public function getOptQuestionItmeById($question_id){
        $data = array(false,'opt_question_item',array(array('AND',"opt_question_id = :opt_question_id",'opt_question_id',$question_id)),false,'item_title ASC');
        $sql = $this->pdosql->makeSelect($data);
        return $this->db->fetchAll($sql);
    }

    public function getSoundByRightItme($question_id){
        $sql['sql'] = "SELECT c.sound_name FROM x2_opt_question_item oqi
                      LEFT JOIN x2_case1 c ON oqi.case_id=c.case_id
                      WHERE opt_question_id=$question_id AND if_right=1";
        $sql['v']   = null;
        return $this->db->fetch($sql);
    }

    public function modifyUserPassword($args,$username)
    {
        $data = array('user',array('userpassword'=>md5($args['userpassword'])),array(array("AND","username = :username",'username',$username)));
        $sql = $this->pdosql->makeUpdate($data);
        $this->db->exec($sql);
        return true;
    }

    public function getUnitTestPaperByType($args){
        $data = array(false, 'exams', $args, false, 'examid DESC');
        $sql = $this->pdosql->makeSelect($data);
        return $this->db->fetchAll($sql, false, array('examsetting', 'examquestions', 'examscore'));
    }
}

?>
