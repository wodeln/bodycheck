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

    private function custom()
    {
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
        $args[]=array('AND',"system_custom = :system_custom",'system_custom',1);
        if(strlen($search['case_type']))$args[] = array('AND',"case_type = :case_type",'case_type',$search['case_type']);
        if(strlen($search['case_name']))$args[] = array('AND',"case_name LIKE :case_name",'case_name','%'.$search['case_name'].'%');
        if(strlen($search['organ_type']))$args[] = array('AND',"organ_type = :organ_type",'organ_type',$search['organ_type']);

        $cases = $this->sound->getCaseList($page,10,$args);
        $this->tpl->assign('cases',$cases);
        $this->tpl->assign('search',$search);
        $this->tpl->assign('u',$u);
        $this->tpl->assign('page',$page);

        $this->tpl->display('custom');
    }

    private function heartadd()
    {
        $args = $this->ev->post('args');
        $soundPositions["play_position"] = $this->ev->post('position');
        $soundPositions["sound_volume"] = $this->ev->post('volume');
        $caseByNameType = $this->sound->getCaseByNameType($args['case_name'], $args['case_type']);
        if ($caseByNameType) {
            $errmsg = "该分类下已有 " . $args['case_name'] . " 病例";
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
        $this->sound->insertHeartCase($args,$soundPositions);
        $message = array(
            'statusCode' => 200,
            "message" => "操作成功",
            "callbackType" => "forward",
            "forwardUrl" => "index.php?sound-master-sound-custom&page={$page}{$this->u}"
        );
        exit(json_encode($message));
    }

    private function heartedit()
    {
        $case_id = $this->ev->get('case_id');
        $page = $this->ev->get('page');
        if($this->ev->get('modifyHeart')) {
            $args = $this->ev->post('args');
            $soundPositions["play_position"] = $this->ev->post('position');
            $soundPositions["sound_volume"] = $this->ev->post('volume');
            $caseByNameType = $this->sound->getCaseByNameType($args['case_name'], $args['case_type'],$case_id);
            if ($caseByNameType) {
                $errmsg = "该分类下已有 " . $args['case_name'] . " 病例";
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
            $this->sound->updateCase($args,$soundPositions,$case_id);
            $message = array(
                'statusCode' => 200,
                "message" => "操作成功",
                "callbackType" => "forward",
                "forwardUrl" => "index.php?sound-master-sound-custom&page={$page}{$this->u}"
            );
            exit(json_encode($message));
        }
        else {
            $case = $this->sound->getCaseById($case_id);
            $casePositionTemp = $this->sound->getPositionByCaseId($case_id);
            $casePosition = "";
            for($i=1;$i<9;$i++){
                $casePosition[$i]["checked"]=0;
                $casePosition[$i]["volume"]="";
            }

            for($i=1;$i<9;$i++){
                foreach ($casePositionTemp as $v){
                    if($v['play_position'] == $i){
                        $casePosition[$i]["checked"]=1;
                        $casePosition[$i]["volume"]=$v['sound_volume'];
                        continue;
                    }
                }
            }
            $this->tpl->assign('case',$case);
            $this->tpl->assign('casePosition',$casePosition);
            $this->tpl->assign('page',$page);
            $this->tpl->display('heartedit');
        }
    }

    private function lungadd(){
        $args = $this->ev->post('args');
        $page = $this->ev->post('page');
        foreach ($this->ev->post('lungposition') as $k=>$v){
            $soundPositions[$this->ev->post('sid')[$k]]['position'] = $v;
            $soundPositions[$this->ev->post('sid')[$k]]['volume'] = $this->ev->post('lungvolume')[$k];

        }
        $caseByNameType = $this->sound->getCaseByNameType($args['case_name'], $args['case_type']);
        if ($caseByNameType) {
            $errmsg = "该分类下已有 " . $args['case_name'] . " 病例";
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
        $this->sound->insertLungCase($args,$soundPositions);
        $message = array(
            'statusCode' => 200,
            "message" => "操作成功",
            "callbackType" => "forward",
            "forwardUrl" => "index.php?sound-master-sound-custom&page={$page}{$this->u}"
        );
        exit(json_encode($message));
    }

    private function lungedit()
    {
        $case_id = $this->ev->get('case_id');
        $page = $this->ev->get('page');
        if($this->ev->get('modifyLung')) {
            $args = $this->ev->post('args');
            foreach ($this->ev->post('lungposition') as $k=>$v){
                $soundPositions[$this->ev->post('sid')[$k]]['position'] = $v;
                $soundPositions[$this->ev->post('sid')[$k]]['volume'] = $this->ev->post('lungvolume')[$k];
            }
            $caseByNameType = $this->sound->getCaseByNameType($args['case_name'], $args['case_type'], $case_id);
            if ($caseByNameType) {
                $errmsg = "该分类下已有 " . $args['case_name'] . " 病例";
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
            $this->sound->updateLungCase($args,$soundPositions,$case_id);
            $message = array(
                'statusCode' => 200,
                "message" => "操作成功",
                "callbackType" => "forward",
                "forwardUrl" => "index.php?sound-master-sound-custom&page={$page}{$this->u}"
            );
            exit(json_encode($message));
        }
        else {
            $case = $this->sound->getCaseById($case_id);
            $casePositionTemp = $this->sound->getPositionByCaseId($case_id);
            foreach ($casePositionTemp as $k=>$v){
                $casePosition[$v['sid']]['lungposition'][$k] = $v['play_position'];
                $casePosition[$v['sid']]['lungvolume'][$k] = $v['sound_volume'];
            }

            foreach ($casePosition as $k=>$v){
                $casePosition[$k]['lungposition'] = implode(",",$v['lungposition']);
                $casePosition[$k]['lungvolume'] = implode(",",$v['lungvolume']);
            }

            $this->tpl->assign('case',$case);
            $this->tpl->assign('casePosition',$casePosition);
            $this->tpl->assign('page',$page);
            $this->tpl->display('lungedit');
        }
    }

    private function customadd()
    {
        $soundType = $this->ev->get("type");
        if ($this->ev->post('insertCustom')) {
            $args = $this->ev->post('args');
            $args['username'] = $args['mobile'];
            $userbyname = $this->user->getUserByUserName($args['mobile']);
            $userbyemail = $this->user->getUserByEmail($args['useremail']);
            if ($userbyname)
                $errmsg = "这个手机号码已经被注册了";
            if ($userbyemail)
                $errmsg = "这个邮箱已经被注册了";
            if ($errmsg) {
                $message = array(
                    'statusCode' => 300,
                    "message" => "{$errmsg}",
                    "navTabId" => "",
                    "rel" => ""
                );
                exit(json_encode($message));
            }
            $args['userpassword'] = md5("123456");
            $search = $this->ev->get('search');
            $u = '';
            if ($search) {
                foreach ($search as $key => $arg) {
                    $u .= "&search[{$key}]={$arg}";
                }
            }
            $id = $this->user->insertUser($args);
            $message = array(
                'statusCode' => 200,
                "message" => "操作成功",
                "callbackType" => "forward",
                "forwardUrl" => "index.php?user-master-user&page={$page}{$this->u}"
            );
            exit(json_encode($message));
        } else {
            if ($soundType == 0) $templetName = "heartadd";
            if ($soundType == 1) $templetName = "lungadd";
            if ($soundType == 2) $templetName = "bowel";
            $this->tpl->display($templetName);
        }
    }

    private function heart(){
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
        $args[]=array('AND',"system_custom = :system_custom",'system_custom',0);
        $args[]=array('AND',"organ_type = :organ_type",'organ_type',0);
        if(strlen($search['case_type']))$args[] = array('AND',"case_type = :case_type",'case_type',$search['case_type']);
        if(strlen($search['case_name']))$args[] = array('AND',"case_name LIKE :case_name",'case_name','%'.$search['case_name'].'%');

        $cases = $this->sound->getCaseList($page,10,$args);
        $this->tpl->assign('cases',$cases);
        $this->tpl->assign('search',$search);
        $this->tpl->assign('u',$u);
        $this->tpl->assign('page',$page);

        $this->tpl->display('heart');
    }

    private function lung(){
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
        $args[]=array('AND',"system_custom = :system_custom",'system_custom',0);
        $args[]=array('AND',"organ_type = :organ_type",'organ_type',1);
        if(strlen($search['case_type']))$args[] = array('AND',"case_type = :case_type",'case_type',$search['case_type']);
        if(strlen($search['case_name']))$args[] = array('AND',"case_name LIKE :case_name",'case_name','%'.$search['case_name'].'%');

        $cases = $this->sound->getCaseList($page,10,$args);
        $this->tpl->assign('cases',$cases);
        $this->tpl->assign('search',$search);
        $this->tpl->assign('u',$u);
        $this->tpl->assign('page',$page);

        $this->tpl->display('lung');
    }

}

?>