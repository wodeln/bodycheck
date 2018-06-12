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
		if($search)
		{
			$this->tpl->assign('search',$search);
			foreach($search as $key => $arg)
			{
				$this->u .= "&search[{$key}]={$arg}";
			}
		}
		$this->tpl->assign('search',$search);
		if(!method_exists($this,$action))
		$action = "index";
		$this->$action();
		exit;
	}

	private function del()
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
            $userclassid = $this->ev->get('userclassid');
			$userid = $this->ev->get('userid');
			$this->user->modifyUserGroup($groupid,$userclassid,$userid);
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
			$fields = $this->module->getMoudleFields($group['groupmoduleid'],array('iscurrentuser'=> $userid ,'group' => $this->user->getGroupById($this->_user['sessiongroupid'])));
			$forms = $this->html->buildHtml($fields,$user);
            $userClasses = $this->user->getUserClassList(1, 500);
            $this->tpl->assign('userClasses', $userClasses);
			$this->tpl->assign('moduleid',$group['groupmoduleid']);
			$this->tpl->assign('fields',$fields);
			$this->tpl->assign('forms',$forms);
			$this->tpl->assign('user',$user);
			$this->tpl->assign('page',$page);
			$this->tpl->display('modifyuser');
		}
	}

	private function batadd()
	{
		if($this->ev->post('insertUser'))
		{
			$uploadfile = $this->ev->get('uploadfile');
			if(!file_exists($uploadfile))
			{
				$message = array(
					'statusCode' => 300,
					"message" => "上传文件不存在"
				);
				exit(json_encode($message));
			}
			else
			{
				setlocale(LC_ALL,'zh_CN');
				$handle = fopen($uploadfile,"r");
				$defaultgroup = $this->user->getDefaultGroup();
				$strings = $this->G->make('strings');

				$i=1;//计数器
                $error = array();
				while ($data = fgetcsv($handle,200))
				{
					$errorStr="";
                    $args = array();

				    if($data[0])//用户名为空判断
				    {
					    $args['username'] = iconv("GBK","UTF-8",$data[0]);
					    if(!$strings->isUserName($args['username'])){//用户名格式错误
                            $errorStr.="用户名格式错误 ";
						}

                        $u = $this->user->getUserByUserName($args['username']);
                        if($u){//用户名重复
                            $errorStr.="用户名重复 ";
						}
				    }else{
				    	$errorStr.="用户名为空 ";
					}

                    if($strings->isEmail($data[1])){//邮件格式错误判断
                        $args['useremail'] = $data[1];
                    }else{
                        $errorStr.="邮件格式错误 ";
                    }

                    $userClassName=iconv("GBK","UTF-8",$data[3]);
                    $userclass = $this->user->getUserClassByName($userClassName);
                    if($userclass){//是否查找到班级判断
                        $args['userclassid'] = $userclass['classid'];
                    }else{
                        $errorStr.="未找到班级名称 ";
                    }

					if(!strlen($errorStr)){
                        if(!$data[2])$data[2] = '123456';
                        $args['userpassword'] = md5($data[2]);
                        $args['usergroupid'] = $defaultgroup['groupid'];
                        $inserArray[]=$args;
					}else{
						$error[$i]=$errorStr;
					}
                    $i++;
				}
				fclose($handle);
				if(count($error)>0){
                    exit(json_encode($error));
				}else{
					foreach ($inserArray as $k=>$v){
                        $this->user->insertUser($v);
					}

					exit(json_encode(1));
                }
			}
		}
		else
		{
			$this->tpl->display('batadduser');
		}
	}

	private function add()
	{
		if($this->ev->post('insertUser'))
		{
			$args = $this->ev->post('args');
			$args['username'] = $args['mobile'];
			$userbyname = $this->user->getUserByUserName($args['mobile']);
			$userbyemail = $this->user->getUserByEmail($args['useremail']);
			if($userbyname)
			$errmsg = "这个手机号码已经被注册了";
			if($userbyemail)
			$errmsg = "这个邮箱已经被注册了";
			if($errmsg)
			{
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
			if($search)
			{
				foreach($search as $key => $arg)
				{
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
		}
		else
		{
            $userClasses = $this->user->getUserClassList(1, 500);
            $this->tpl->assign('userClasses', $userClasses);
			$this->tpl->display('adduser');
		}
	}

	private function index()
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
		$args = array();
		if($search['userid'])$args[] = array('AND',"userid = :userid",'userid',$search['userid']);
		if($search['username'])$args[] = array('AND',"username LIKE :username",'username','%'.$search['username'].'%');
		if($search['useremail'])$args[] = array('AND',"useremail  LIKE :useremail",'useremail','%'.$search['useremail'].'%');
		if($search['groupid'])$args[] = array('AND',"usergroupid = :usergroupid",'usergroupid',$search['groupid']);
		if($search['userclassid']) $args[]=array('AND',"userclassid = :userclassid",'userclassid',$search['userclassid']);
		if($search['subject1']) $args[]=array('AND',"subject1 = :subject1",'subject1',$search['subject1']);
		if($search['academy']) $args[]=array('AND',"academy = :academy",'academy',$search['academy']);
		if($search['stime'] || $search['etime'])
		{
			if(!is_array($args))$args = array();
			if($search['stime']){
				$stime = strtotime($search['stime']);
				$args[] = array('AND',"userregtime >= :userregtime",'userregtime',$stime);
			}
			if($search['etime']){
				$etime = strtotime($search['etime']);
				$args[] = array('AND',"userregtime <= :userregtime",'userregtime',$etime);
			}
		}
		$users = $this->user->getUserList($page,10,$args);
        $userClasses = $this->user->getUserClassList(1, 500);
        $this->tpl->assign('userClasses', $userClasses);
		$this->tpl->assign('users',$users);
		$this->tpl->assign('search',$search);
		$this->tpl->assign('u',$u);
		$this->tpl->assign('page',$page);
		$this->tpl->display('user');
	}

    /**
     * 根据 群组/班级ID 获取 学员列表信息
     */
    private function getUserByClassId(){
        $classid = $this->ev->get('classid');
        $userList = $this->user->getUserByClassId($classid);
        exit(json_encode($userList));
    }

    /**
     * 根据 用户类型ID获取 form项目
     */
    private function getUserFormsById(){
		$moduleId = $this->ev->get("moduleid");
        $fields = $this->module->getMoudleFields($moduleId,array('iscurrentuser'=> $this->_user['sessionuserid'] ,'group' => $this->user->getGroupById($this->_user['sessiongroupid'])));
        $forms = $this->html->buildHtml($fields);
        exit(json_encode($forms));
    }
}


?>
