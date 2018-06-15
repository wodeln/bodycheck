<?php

/**
 * Created by PhpStorm.
 * User: PVer
 * Date: 2017/7/25
 * Time: 10:59
 */
class action extends app
{

    public function display()
    {
        $action = $this->ev->url(3);
        if (!method_exists($this, $action))
            $action = "index";
        $this->$action();
        exit;
    }


    private function index(){
        $page = $this->ev->get('page')?$this->ev->get('page'):1;
        $search = $this->ev->get('search');
        $u = '';
        if($search)
        {
            foreach($search as $key => $arg)
            {
                $u .= "&search[{$key}]={$arg}";
            }
        }
//        $args[]=array('AND',"system_custom = :system_custom",'system_custom',1);
        if(strlen($search['case_type']))$args[] = array('AND',"case_type = :case_type",'case_type',$search['case_type']);
        if(strlen($search['case_name']))$args[] = array('AND',"case_name LIKE :case_name",'case_name','%'.$search['case_name'].'%');
        if(strlen($search['organ_type']))$args[] = array('AND',"organ_type = :organ_type",'organ_type',$search['organ_type']);

        $cases = $this->sound->getSoundCaseList($page,10,$args);
        $this->tpl->assign('cases',$cases);
        $this->tpl->assign('search',$search);
        $this->tpl->assign('u',$u);
        $this->tpl->assign('page',$page);

        $this->tpl->display('soundcase');
    }

    private function chageState(){
        $state = $this->ev->get("state");
        $sound_case_id = $this->ev->get("sound_case_id");
        if($state=="false") $args['if_use']=0;
        else $args['if_use']=1;
        $this->sound->changeSoundCaseState($args,$sound_case_id);
    }

    private function soundCasePackage(){
        $page = $this->ev->get('page')?$this->ev->get('page'):1;
        $search = $this->ev->get('search');
        $u = '';
        if($search)
        {
            foreach($search as $key => $arg)
            {
                $u .= "&search[{$key}]={$arg}";
            }
        }
//        $args[]=array('AND',"system_custom = :system_custom",'system_custom',1);
        if(strlen($search['package_name']))$args[] = array('AND',"package_name LIKE :package_name",'package_name','%'.$search['package_name'].'%');

        $cases = $this->sound->getSoundCasePackageList($page,10,$args);

        foreach ($cases['data'] as $k=>$v){
            $soundCaseStr = "";
            $discernStr = "";
            $casePackage = $this->sound->getCasePackage($v['soundcase_package_id']);
            foreach ($casePackage as $k1=>$v1){
                if($v1['type_id']==0){
                    /*$soundCase = $this->sound->getSoundCaseById($v1['case_id']);
                    $soundCaseStr.=$soundCase['sound_case_name'].",";*/
                    $cases['data'][$k]['count_heart']=$this->sound->getCountByPackageIdType($v1['case_id'],0)['c'];
                    $cases['data'][$k]['count_lung']=$this->sound->getCountByPackageIdType($v1['case_id'],1)['c'];
                    $cases['data'][$k]['count_chang']=$this->sound->getCountByPackageIdType($v1['case_id'],2)['c'];
                }else{
                    $discern = $this->sound->getDiscernById($v1['case_id']);
                    $discernStr.=$discern['discern_name'].",";
                }
            }
//            $cases['data'][$k]['sound_case'] = substr($soundCaseStr,0,strlen($soundCaseStr)-1);
            $cases['data'][$k]['discern'] = substr($discernStr,0,strlen($discernStr)-1);
        }

        $this->tpl->assign('cases',$cases);
        $this->tpl->assign('search',$search);
        $this->tpl->assign('u',$u);
        $this->tpl->assign('page',$page);
        $this->tpl->display('sound_case_package');
    }

    private function packageAdd(){
        $page=$this->ev->post('page');
        if($this->ev->get('insertPackage')) {
            $args = $this->ev->post('args');
            $packageItems = $this->ev->post('to');
            $package = $this->sound->getPackageByName($args['package_name']);
            if ($package) {
                $errmsg = "该分套餐名称已存在";
            }
            if(count($packageItems)==0) $errmsg = "请正确选择鉴别听诊或标准化病例";
            if ($errmsg) {
                $message = array(
                    'statusCode' => 300,
                    "message" => "{$errmsg}",
                    "navTabId" => "",
                    "rel" => ""
                );
                exit(json_encode($message));
            }
            $user = $this->user->getUserById($this->session->getSessionUser()['sessionuserid']);
            $args['add_name']= $user['usertruename'];
            $args['add_time'] = time();
            $search = $this->ev->get('search');
            $u = '';
            if ($search) {
                foreach ($search as $key => $arg) {
                    $u .= "&search[{$key}]={$arg}";
                }
            }
            $packageId = $this->sound->insertPackage($args);
            $this->sound->addPackageItems($packageItems, $packageId);
            $message = array(
                'statusCode' => 200,
                "message" => "操作成功",
                "callbackType" => "forward",
                "forwardUrl" => "index.php?sound-master-soundcase-soundCasePackage&page={$page}{$this->u}"
            );
            exit(json_encode($message));
        }else{
            $this->tpl->display('package_add');
        }

    }

    private function packageEdit(){
        $page = $this->ev->get("page");
        if($this->ev->post("editPackage")){
            $args = $this->ev->post('args');
            $packageItems = $this->ev->post('to');
            $soundcase_package_case_id = $this->ev->post("soundcase_package_id");
            $package = $this->sound->getPackageByName($args['package_name'],$soundcase_package_case_id);
            if ($package) {
                $errmsg = "该分套餐名称已存在";
            }
            if(count($packageItems)==0) $errmsg = "请正确选择鉴别听诊或标准化病例";
            if ($errmsg) {
                $message = array(
                    'statusCode' => 300,
                    "message" => "{$errmsg}",
                    "navTabId" => "",
                    "rel" => ""
                );
                exit(json_encode($message));
            }
//            $user = $this->user->getUserById($this->session->getSessionUser()['sessionuserid']);
//            $args['add_name']= $user['usertruename'];
//            $args['add_time'] = time();
            $search = $this->ev->get('search');
            $u = '';
            if ($search) {
                foreach ($search as $key => $arg) {
                    $u .= "&search[{$key}]={$arg}";
                }
            }
            $this->sound->updatePackage($args,$soundcase_package_case_id);
            $this->sound->addPackageItems($packageItems, $soundcase_package_case_id);
            $message = array(
                'statusCode' => 200,
                "message" => "操作成功",
                "callbackType" => "forward",
                "forwardUrl" => "index.php?sound-master-soundcase-soundCasePackage&page={$page}{$this->u}"
            );
            exit(json_encode($message));
        }else{
            $soundcase_package_id = $this->ev->get("soundcase_package_id");
            $cases = $this->sound->getSoundCasePackageById($soundcase_package_id,1);

            $casePackage = $this->sound->getCasePackage($cases['soundcase_package_id']);
            $selectCase = "";
            foreach ($casePackage as $k1=>$v1){
                if($v1['type_id']==0){
                    $soundCase = $this->sound->getSoundCaseById($v1['case_id']);
                    $selectCase[$k1]['case_id'] = $soundCase['sound_case_id'].",".$v1['type_id'];
                    $selectCase[$k1]['case_name'] = $soundCase['sound_case_name'];
                }else{
                    $discern = $this->sound->getDiscernById($v1['case_id']);
                    $selectCase[$k1]['case_id'] = $discern['discern_id'].",".$v1['type_id'];
                    $selectCase[$k1]['case_name'] = $discern['discern_name'];
                }
            }
            $this->tpl->assign('cases',$cases);
            $this->tpl->assign('selectCase',$selectCase);
            $this->tpl->assign('page',$page);
            $this->tpl->display('package_edit');
        }
    }

    private function packageDel(){
        $page = $this->ev->get('page');
        $packageId = $this->ev->get('soundcase_package_id');
        $this->sound->delPackageById($packageId);
        $message = array(
            'statusCode' => 200,
            "message" => "操作成功",
            "navTabId" => "",
            "rel" => "",
            "callbackType" => "forward",
            "forwardUrl" => "index.php?sound-master-soundcase-soundCasePackage&page={$page}{$this->u}"
        );
        exit(json_encode($message));
    }

    private function getContent(){
        $type = $this->ev->post("type");
        $case_type = $this->ev->post("case_type");
        $organ_type = $this->ev->post("organ_type");
        $case_name = $this->ev->post("case_name");

        $case = array();
        $args[] = array('AND',"if_use = :if_use",'if_use',1);
        if($type==1){
            if(strlen($case_name))$args[] = array('AND',"case_name LIKE :case_name",'case_name','%'.$case_name.'%');
            if(strlen($organ_type))$args[] = array('AND',"organ_type = :organ_type",'organ_type',$organ_type);
            $data = $this->sound->getDiscern($args);
        }else{
            if(strlen($case_type))$args[] = array('AND',"case_type = :case_type",'case_type',$case_type);
            if(strlen($case_name))$args[] = array('AND',"case_name LIKE :case_name",'case_name','%'.$case_name.'%');
            if(strlen($organ_type))$args[] = array('AND',"organ_type = :organ_type",'organ_type',$organ_type);
            $data = $this->sound->getSoundCase($args);
        }
        exit(json_encode($data));
    }
}

?>