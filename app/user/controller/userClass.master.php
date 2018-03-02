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
        $search = $this->ev->get('search');
        $this->u = '';
        if ($search) {
            $this->tpl->assign('search', $search);
            foreach ($search as $key => $arg) {
                $this->u .= "&search[{$key}]={$arg}";
            }
        }
        $this->tpl->assign('search', $search);
        if (!method_exists($this, $action))
            $action = "index";
        $this->$action();
        exit;
    }

    /*private function del()
    {
        $page = $this->ev->get('page');
        $userid = $this->ev->get('userid');
        $this->user->delUserById($userid);
        $message = array(
            'statusCode' => 200,
            "message" => "操作成功",
            "navTabId" => "",
            "rel" => "",
            "callbackType" => "forward",
            "forwardUrl" => "index.php?user-master-user&page={$page}{$this->u}"
        );
        exit(json_encode($message));
    }

    private function batdel()
    {
        if($this->ev->get('action') == 'delete')
        {
            $page = $this->ev->get('page');
            $delids = $this->ev->get('delids');
            foreach($delids as $userid => $p)
            $this->user->delUserById($userid);
            $message = array(
                'statusCode' => 200,
                "message" => "操作成功",
                "navTabId" => "",
                "rel" => "",
                "callbackType" => "forward",
                "forwardUrl" => "index.php?user-master-user&page={$page}{$this->u}"
            );
            exit(json_encode($message));
        }
    }

    private function modify()
    {
        $page = $this->ev->get('page');
        if($this->ev->get('modifyusergroup'))
        {
            $groupid = $this->ev->get('groupid');
            $userid = $this->ev->get('userid');
            $this->user->modifyUserGroup($groupid,$userid);
            $message = array(
                'statusCode' => 200,
                "message" => "操作成功",
                "callbackType" => "forward",
                "forwardUrl" => "index.php?user-master-user&page={$page}{$this->u}"
            );
            exit(json_encode($message));
        }
        elseif($this->ev->get('modifyuserinfo'))
        {
            $args = $this->ev->get('args');
            $userid = $this->ev->get('userid');
            $user = $this->user->getUserById($userid);
            $group = $this->user->getGroupById($user['usergroupid']);
            $args = $this->module->tidyNeedFieldsPars($args,$group['groupmoduleid'],array('iscurrentuser'=> $userid == $this->_user['sessionuserid'],'group' => $group));
            $id = $this->user->modifyUserInfo($args,$userid);
            $message = array(
                'statusCode' => 200,
                "message" => "操作成功",
                "callbackType" => "forward",
                "forwardUrl" => "index.php?user-master-user&page={$page}{$this->u}"
            );
            exit(json_encode($message));
        }
        elseif($this->ev->get('modifyuserpassword'))
        {
            $args = $this->ev->get('args');
            $userid = $this->ev->get('userid');
            if($args['password'] == $args['password2'] && $userid)
            {
                $id = $this->user->modifyUserPassword($args,$userid);
                $message = array(
                    'statusCode' => 200,
                    "message" => "操作成功",
                    "callbackType" => "forward",
                    "forwardUrl" => "index.php?user-master-user&page={$page}{$this->u}"
                );
                exit(json_encode($message));
            }
            else
            {
                $message = array(
                    'statusCode' => 300,
                    "message" => "操作失败",
                    "navTabId" => "",
                    "rel" => ""
                );
                exit(json_encode($message));
            }
        }
        else
        {
            $userid = $this->ev->get('userid');
            $user = $this->user->getUserById($userid);
            $group = $this->user->getGroupById($user['usergroupid']);
            $fields = $this->module->getMoudleFields($group['groupmoduleid'],array('iscurrentuser'=> $userid == $this->_user['sessionuserid'],'group' => $this->user->getGroupById($this->_user['sessiongroupid'])));
            $forms = $this->html->buildHtml($fields,$user);
            $this->tpl->assign('moduleid',$group['groupmoduleid']);
            $this->tpl->assign('fields',$fields);
            $this->tpl->assign('forms',$forms);
            $this->tpl->assign('user',$user);
            $this->tpl->assign('page',$page);
            $this->tpl->display('modifyuser');
        }
    }*/


    /**
     * 群组/班级管理 首页
     */
    private function index()
    {
        $page = $this->ev->get('page') ? $this->ev->get('page') : 1;
        $search = $this->ev->get('search');
        $u = '';
        if ($search) {
            foreach ($search as $key => $arg) {
                $u .= "&search[{$key}]={$arg}";
            }
        }
        $where="";
        if($search['classname']) $where.=" AND uc.classname='".$search['classname']."'";
        if ($search['subjectid']) $where.=" AND uc.subjectid=".$search['subjectid'];

        $userClasses = $this->user->getUserClassList($page, 10, $where);
        $subjects = $this->basic->getSubjectList();
        $this->tpl->assign('subjects', $subjects);
        $this->tpl->assign('userClasses', $userClasses);
        $this->tpl->assign('search', $search);
        $this->tpl->assign('u', $u);
        $this->tpl->assign('page', $page);
        $this->tpl->display('user_class');
    }

    /**
     * 添加 群组/班级
     */
    private function add()
    {
        if ($this->ev->post('insertUserClass')) {
            $args = $this->ev->post('args');
            $subjectId=$args['subjectid'];
            $className=$args['classname'];
            $page=$this->ev->post('page');
            //验证同一科目下群组名称唯一性
            $result=$this->user->getClassByInfo($subjectId,$className,'add');
            if($result){
                $errmsg = $result['subject']." 下 ".$className." 已存在";
                $message = array(
                    'statusCode' => 300,
                    "message" => "{$errmsg}",
                    "navTabId" => "",
                    "rel" => ""
                );
                exit(json_encode($message));
            }
            $id = $this->user->insertUserClass($args);
            $message = array(
                'statusCode' => 200,
                "message" => "操作成功",
                "callbackType" => "forward",
                "forwardUrl" => "index.php?user-master-userClass&page={$page}{$this->u}"
            );
            exit(json_encode($message));
        } else {
            $subjects = $this->basic->getSubjectList();
            $this->tpl->assign('subjects', $subjects);
            $this->tpl->display('add_class');
        }
    }

    /**
     * 编辑群组
     */
    private function modify(){

        if($this->ev->post('modifyUserClass')){
            $page = $this->ev->get('page');
            $args = $this->ev->post('args');
            $subjectId=$args['subjectid'];
            $className=$args['classname'];
            $classId = $this->ev->post('classid');
            //验证同一科目下群组名称唯一性
            $result=$this->user->getClassByInfo($subjectId,$className,'modify',$classId);
            if($result){
                $errmsg = $result['subject']." 下 ".$className." 已存在";
                $message = array(
                    'statusCode' => 300,
                    "message" => "{$errmsg}",
                    "navTabId" => "",
                    "rel" => ""
                );
                exit(json_encode($message));
            }
            $this->user->updataUserClass($args,$classId);
            $message = array(
                'statusCode' => 200,
                "message" => "操作成功",
                "callbackType" => "forward",
                "forwardUrl" => "index.php?user-master-userClass&page={$page}{$this->u}"
            );
            exit(json_encode($message));
        }else{
            $page = $this->ev->get('page');
            $classId = $this->ev->get('classid');
            $subjects = $this->basic->getSubjectList();
            $userClass = $this->user->getUserClassById($classId);
            $this->tpl->assign('userClass', $userClass);
            $this->tpl->assign('page', $page);
            $this->tpl->assign('subjects', $subjects);
            $this->tpl->display('modify_class');
        }
    }

    /**
     * 删除群组
     */
    private function del(){
        $page = $this->ev->get('page');
        $classid = $this->ev->get('classid');
        $this->user->delUserClassById($classid);
        $message = array(
            'statusCode' => 200,
            "message" => "操作成功",
            "navTabId" => "",
            "rel" => "",
            "callbackType" => "forward",
            "forwardUrl" => "index.php?user-master-userClass&page={$page}{$this->u}"
        );
        exit(json_encode($message));
    }
}


?>
