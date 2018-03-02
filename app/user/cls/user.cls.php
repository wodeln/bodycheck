<?php

class user_user
{
	public $G;

	public function __construct(&$G)
	{
		$this->G = $G;
	}

	public function _init()
	{
		$this->sql = $this->G->make('sql');
		$this->pdosql = $this->G->make('pdosql');
		$this->db = $this->G->make('pepdo');
		$this->pg = $this->G->make('pg');
		$this->ev = $this->G->make('ev');
		$this->module = $this->G->make('module');
		$this->session = $this->G->make('session');
	}

	public function getModuleUserInfo($userid = 0)
	{
		$user = $this->session->getSessionUser();
		$group = $this->getGroupById($user['sessiongroupid']);
		if($userid)
		return array('iscurrentuser'=> $userid == $user['sessionuserid'],'group' => $group);
		else
		return array('iscurrentuser'=> 0,'group' => $group);
	}

	//user function
	public function insertUser($args)
	{
		$args['userregip'] = $this->ev->getClientIp();
		$args['userregtime'] = TIME;
		$data = array('user',$args);
		$sql = $this->pdosql->makeInsert($data);
		$this->db->exec($sql);
		return $this->db->lastInsertId();
	}

	public function modifyUserGroup($groupid,$userclassid,$userid)
	{
		$user = $this->getUserById($userid);
		if($groupid == $user['usergroupid'] && $userclassid == $user['userclassid'])return true;
		$group = $this->getGroupById($groupid);
		if($group['groupmoduleid'] == $user['groupmoduleid'])
		{
			$data = array('user',array('usergroupid'=>$groupid,'userclassid'=>$userclassid),array(array("AND","userid = :userid",'userid',$userid)));
			$sql = $this->pdosql->makeUpdate($data);
			$this->db->exec($sql);
			return true;
		}
		else
		{
			$args = array('usergroupid'=>$groupid);
			$fields = $this->module->getPrivateMoudleFields($user['groupmoduleid']);
			foreach($fields as $p)
			{
				$args[$p['field']] = NULL;
			}
			$data = array('user',$args,array(array("AND","userid = :userid",'userid',$userid)));
			$sql = $this->pdosql->makeUpdate($data);
			$this->db->exec($sql);
			return true;
		}
	}

	public function modifyUserPassword($args,$userid)
	{
		$data = array('user',array('userpassword'=>md5($args['password'])),array(array("AND","userid = :userid",'userid',$userid)));
		$sql = $this->pdosql->makeUpdate($data);
		$this->db->exec($sql);
		return true;
	}

	public function modifyUserInfo($args,$userid)
	{
		if(!$args)return false;
		$data = array('user',$args,array(array('AND',"userid = :userid",'userid',$userid)));
		$sql = $this->pdosql->makeUpdate($data);
		return $this->db->exec($sql);
		//return $this->db->affectedRows();
	}

	public function delUserById($userid)
	{
		$data = array('user',array(array('AND',"userid = :userid",'userid',$userid)));
		$sql = $this->pdosql->makeDelete($data);
		return $this->db->exec($sql);
		//return $this->db->affectedRows();
	}

	public function delActorById($groupid)
	{
		$r = $this->getUserListByGroupid($groupid);
		if($r['number'])return false;
		else
		{
			$args = array(
				'table' => "user_group",
				'query' => array(array('AND',"groupid = :groupid",'groupid',$groupid))
			);
			return $this->db->delElement($args);
		}
	}

	public function getUserById($id)
	{
		$data = array(false,array('user','user_group'),array(array('AND',"user.userid = :id",'id',$id),array('AND','user.usergroupid = user_group.groupid')));
		$sql = $this->pdosql->makeSelect($data);
		return $this->db->fetch($sql,array('userinfo','groupright'));
	}

	public function getUserByArgs($args)
	{
		$data = array(false,array('user','user_group'),$args);
		$sql = $this->pdosql->makeSelect($data);
		return $this->db->fetch($sql,array('userinfo','groupright'));
	}

	public function getUsersByArgs($args)
	{
		$data = array(false,array('user','user_group'),$args,false,false,false);
		$sql = $this->pdosql->makeSelect($data);
		return $this->db->fetchAll($sql,'userid',array('userinfo','groupright'));
	}

	public function getUserListByArgs($page,$args,$number = 10)
	{
		$args = array(
			'table' => array('user','user_group'),
			'query' => $args,
			'serial' => array('userinfo','groupright'),
			'index' => 'userid'
		);
		return $this->db->listElements($page,$number,$args);
	}

	public function getUserListByGroupid($groupid,$number = 10,$page = 1)
	{
		$args = array(
			'table' => array('user','user_group'),
			'query' => array(array('AND',"user.usergroupid = :usergroupid",'usergroupid',$groupid),array('AND','user.usergroupid = user_group.groupid')),
			'serial' => array('userinfo','groupright')
		);
		return $this->db->listElements($page,$number,$args);
	}

	public function getUserByUserName($username)
	{
		//$username = urldecode($username);
		$data = array(false,array('user','user_group'),array(array('AND',"user.username = :username",'username',$username),array('AND','user.usergroupid = user_group.groupid')));
		$sql = $this->pdosql->makeSelect($data);
		return $this->db->fetch($sql,array('userinfo','groupright'));
	}

	public function getUserByEmail($email)
	{
		$data = array(false,array('user','user_group'),array(array('AND',"user.useremail = :email",'email',$email),array('AND','user.usergroupid = user_group.groupid')));
		$sql = $this->pdosql->makeSelect($data);
		return $this->db->fetch($sql,array('userinfo','groupright'));
	}

	public function getUserList($page,$number = 20,$args = 1)
	{
		$page = $page > 0?$page:1;
		$r = array();
		$data = array(false,'user',$args,false,'userid DESC',array(intval($page-1)*$number,$number));
		$sql = $this->pdosql->makeSelect($data);
		$r['data'] = $this->db->fetchALL($sql,false,'userinfo');
		$data = array('COUNT(*) AS number','user',$args,false,false,false);
		$sql = $this->pdosql->makeSelect($data);
		$tmp = $this->db->fetch($sql);
		$r['number'] = $tmp['number'];
		$pages = $this->pg->outPage($this->pg->getPagesNumber($tmp['number'],$number),$page);
		$r['pages'] = $pages;
		return $r;
	}

	//user group functions
	public function getGroupById($groupid)
	{
		$data = array(false,'user_group',array(array('AND',"groupid = :groupid",'groupid',$groupid)),false,'groupid DESC',false);
		$sql = $this->pdosql->makeSelect($data);
		return $this->db->fetch($sql,'groupright');
	}

	public function getGroupByArgs($args)
	{
		$data = array(false,'user_group',$args);
		$sql = $this->pdosql->makeSelect($data);
		return $this->db->fetch($sql,'groupright');
	}

	public function getUserGroups()
	{
		$data = array(false,'user_group',1,false,'groupid DESC',false);
		$sql = $this->pdosql->makeSelect($data);
		return $this->db->fetchAll($sql,'groupid','groupright');
	}

	public function getUserGroupList($args,$number = 10,$page = 1)
	{
		$args = array(
			'table' => 'user_group',
			'query' => $args,
			'index' => 'groupid',
			'serial' => 'groupright'
		);
		return $this->db->listElements($page,10,$args);
	}

	public function getGroupsByModuleid($moduleid)
	{
		$data = array(false,'user_group',array(array('AND',"groupmoduleid = :groupmoduleid",'groupmoduleid',$moduleid)),false,false,false);
		$sql = $this->pdosql->makeSelect($data);
		return $this->db->fetchAll($sql,'groupid','groupright');
	}

	public function getDefaultGroupByModuleid($moduleid)
	{
		$data = array(false,'user_group',array(array('AND',"groupmoduledefault = 1"),array('AND',"groupmoduleid = :groupmoduleid",'groupmoduleid',$moduleid)),false,'groupid DESC',false);
		$sql = $this->pdosql->makeSelect($data);
		return $this->db->fetch($sql);
	}

	public function insertActor($args)
	{
		if($args['groupmoduledefault'])
		{
			$data = array('user_group',array('groupmoduledefault'=>0),array(array('AND',"groupmoduleid = :groupmoduleid",'groupmoduleid',$args['groupmoduleid'])));
			$sql = $this->pdosql->makeUpdate($data);
			$this->db->exec($sql);
		}
		$data = array('user_group',$args);
		$sql = $this->pdosql->makeInsert($data);
		$this->db->exec($sql);
		return $this->db->lastInsertId();
	}

	public function modifyActor($groupid,$args)
	{
		$g = $this->getGroupByArgs(array(array('AND',"groupname = :groupname",'groupname',$args['groupname']),array('AND',"groupid != :groupid",'groupid',$groupid)));
		if($g)return false;
		$data = array('user_group',$args,array(array('AND',"groupid = :groupid",'groupid',$groupid)));
		$sql = $this->pdosql->makeUpdate($data);
		$this->db->exec($sql);
		return true;
	}

	public function selectDefaultActor($groupid)
	{
		$args = array("groupdefault" => 0);
		$data = array('user_group',$args);
		$sql = $this->pdosql->makeUpdate($data);
		$this->db->exec($sql);
		$args = array("groupdefault" => 1);
		$data = array('user_group',$args,array(array('AND',"groupid = :groupid",'groupid',$groupid)));
		$sql = $this->pdosql->makeUpdate($data);
		$this->db->exec($sql);
		return true;
	}

	public function getDefaultGroup()
	{
		$data = array(false,'user_group',array(array('AND',"groupdefault = 1")));
		$sql = $this->pdosql->makeSelect($data);
		return $this->db->fetch($sql);
	}

    /**
	 * 获取所有 群组/班级
     * @param $where 筛选条件
     * @param int $number 每页显示数量
     * @param int $page 页数
     * @return mixed page数组
     */
	public function getUserClassList($page = 1,$number = 10,$where=''){
        $page = $page > 0?$page:1;
        $r = array();
        $limt = intval($page-1)*$number.",".$number;
		$sql['sql'] = "SELECT * FROM x2_user_class AS uc 
						LEFT JOIN x2_subject AS s ON uc.subjectid=s.subjectid
						WHERE 1 ".$where."
						ORDER BY classid DESC
						LIMIT ".$limt;
		$sql['v']	= null;
        $r['data'] = $this->db->fetchALL($sql,false,'userClass');
        $sql['sql']	= "SELECT COUNT(*) AS number FROM x2_user_class AS uc WHERE 1 ".$where;
        $sql['v']	= null;
        $tmp = $this->db->fetch($sql);
        $r['number'] = $tmp['number'];
        $pages = $this->pg->outPage($this->pg->getPagesNumber($tmp['number'],$number),$page);
        $r['pages'] = $pages;
        return $r;
	}

    /**
	 * 验证同一科目下群组名称唯一性
     * @param $subjectId 科目ID
     * @param $className 群组/班级名称
     * @param string $type 验证类型 add:添加 modify:编辑
	 * @param $classid 编辑时 群组/班级ID
     * @return mixed 科目名称
     */
    public function getClassByInfo($subjectId,$className,$type='add',$classid=NULL){
		if($type=='add'){
            $sqlStr	= "SELECT subject FROM x2_user_class AS uc 
							LEFT JOIN x2_subject AS s ON uc.subjectid=s.subjectid
						 	WHERE classname='".$className."' AND s.subjectid=".$subjectId;
		}else{
			$sqlStr = "SELECT subject FROM x2_user_class AS uc 
							LEFT JOIN x2_subject AS s ON uc.subjectid=s.subjectid
						 	WHERE classname='".$className."' AND s.subjectid=".$subjectId." AND classid!=".$classid;
		}
		$sql['sql'] = $sqlStr;
		$sql['v'] = null;
		return $this->db->fetch($sql);

	}

    /**
	 * 添加用户群组/班级
     * @param $args 表单提交数据 数组
     * @return mixed 插入ID
     */
	public function insertUserClass($args){
        $data = array('user_class',$args);
        $sql = $this->pdosql->makeInsert($data);
        $this->db->exec($sql);
        return $this->db->lastInsertId();
	}

    /**
	 * 更新用户群组/班级 信息
     * @param $args 表单数据 数组
     * @param $classid 用户群组/班级ID
     */
	public function updataUserClass($args,$classid){
        if(!$args)return false;
        $data = array('user_class',$args,array(array('AND',"classid = :classid",'classid',$classid)));
        $sql = $this->pdosql->makeUpdate($data);
        return $this->db->exec($sql);
	}

    /**
	 * 删除 用户群组/班级
     * @param $classid 群组/班级ID
     */
	public function delUserClassById($classid){
        $r = $this->getUserNumberByUserClassid($classid);
        if($r['number'])return false;
        else
        {
            $args = array(
                'table' => "user_class",
                'query' => array(array('AND',"classid = :classid",'classid',$classid))
            );
            return $this->db->delElement($args);
        }
	}

    /**
	 * 根据用户群组ID获取 用户数目
     * @param $classid 群组/班级ID
     */
	public function getUserNumberByUserClassid($classid){
		$sql['sql'] = "SELECT COUNT(*) AS number FROM x2_user WHERE userclassid=".$classid;
		$sql['v']	= null;
		return $this->db->fetch($sql);
	}
    /**
	 * 根据群组/班级ID 获取群组/班级信息
     * @param $classId 群组/班级ID
     * @return mixed
     */
	public function getUserClassById($classId){
		$sql['sql'] = "SELECT * FROM x2_user_class WHERE classid=".$classId;
		$sql['v']	= null;
		return $this->db->fetch($sql);
	}

    public function getUserClassByName($className){
        $sql['sql'] = "SELECT * FROM x2_user_class WHERE classname='".$className."'";
        $sql['v']	= null;
        return $this->db->fetch($sql);
    }

    /**
     * 获取所有 用户群组/班级
     */
    public function getUserClasses(){
        $data = array(false,'user_class',1,false,'classid DESC',false);
        $sql = $this->pdosql->makeSelect($data);
        return $this->db->fetchAll($sql,'classid');
    }

    /**
	 * 插入登录log记录
     * @param $args
     */
    public function insertLog($args){
        $data = array('log',$args);
        $sql = $this->pdosql->makeInsert($data);
        $this->db->exec($sql);
	}

    /**
	 * 根据用户ID获取 登陆记录
     * @param $userid 用户ID
     */
	public function getLoginTimes($userid){
		$sql['sql'] = "SELECT COUNT(1) AS loginTimes FROM x2_log WHERE loguserid=".$userid;
		$sql['v']	= null;
		return $this->db->fetch($sql);
	}

	public function getUserByClassId($classid){
		$sql['sql'] = "SELECT * FROM x2_user WHERE userclassid=".$classid." AND usergroupid=8";
		$sql['v']	= null;
		return $this->db->fetchAll($sql);
	}

	public function getAllUser(){
        $sql['sql'] = "SELECT * FROM x2_user";
        $sql['v']	= null;
        return $this->db->fetchAll($sql);
	}
	//public function getRightModuleFileds

	/**
	public function searchModules($args)
	{
		$data = array(false,'module');
		foreach($args as $p)
		{
			$data[] = $p;
		}
		$sql = $this->pdosql->makeSelect($data);
		return $this->db->fetchAll($sql,'moduleid');
	}

	public function getUserModules()
	{
		$data = array(false,'module','moduleapp = \'user\'',false,'moduleid DESC',false);
		$sql = $this->pdosql->makeSelect($data);
		return $this->db->fetchAll($sql,'moduleid');
	}

	public function getUserModuleById($moduleid)
	{
		$data = array(false,'module',"moduleid = '{$moduleid}'");
		$sql = $this->pdosql->makeSelect($data);
		return $this->db->fetch($sql,'modulefields');
	}

	public function insertModule($args)
	{
		$args['moduleapp'] = 'user';
		$data = array('module',$args);
		$sql = $this->pdosql->makeInsert($data);
		$this->insertDefaultUserTable($args['moduletable']);
		$this->db->exec($sql);
		return $this->db->lastInsertId();
	}

	public function modifyUserModule($args,$moduleid)
	{
		$data = array("module",$args,"moduleid = '{$moduleid}'");
		$sql = $this->pdosql->makeUpdate($data);
		return $this->db->exec($sql);
	}

	public function getMoudleFields($moduleid,$showall = false)
	{
		if($showall)
		$data = array(false,'module_fields',"fieldmoduleid = '{$moduleid}'",false,'fieldsequence DESC,fieldid DESC');
		else
		$data = array(false,'module_fields',array("fieldmoduleid = '{$moduleid}'","fieldlock = 0"),false,'fieldsequence DESC,fieldid DESC');
		$sql = $this->pdosql->makeSelect($data);
		return $this->db->fetchAll($sql);
	}

	public function getFieldByNameAndModuleid($field,$moduleid)
	{
		$data = array(false,'module_fields',array("fieldmoduleid = '{$moduleid}'","field = '{$field}'"));
		$sql = $this->pdosql->makeSelect($data);
		return $this->db->fetch($sql);
	}

	public function getFieldById($fieldid)
	{
		$data = array(false,'module_fields',"fieldid = '{$fieldid}'");
		$sql = $this->pdosql->makeSelect($data);
		return $this->db->fetch($sql);
	}

	public function insertModuleFields($args)
	{
		$this->insertUserModuleField($args);
		$data = array('module_fields',$args);
		$sql = $this->pdosql->makeInsert($data);
		$this->db->exec($sql);
		return $this->db->lastInsertId();
	}

	public function getUserModuleHtml($moduleid)
	{}

	public function modifyUserFieldHtmlType($args,$fieldid)
	{
		$data = array('module_fields',$args,"fieldid = '{$fieldid}'");
		$sql = $this->pdosql->makeUpdate($data);
		return $this->db->exec($sql);
	}

	public function modifyUserFieldDataType($args,$fieldid)
	{
		$this->modifyUserModuleField($args,$fieldid);
		$data = array('module_fields',$args,"fieldid = '{$fieldid}'");
		$sql = $this->pdosql->makeUpdate($data);
		return $this->db->exec($sql);
	}

	//user database exec
	public function insertDefaultUserTable($table)
	{
		$fields = array(array('name'=>'userid','type'=>'INT','length'=>11));
		$indexs = array(array('type'=>'PRIMARY KEY','field'=>'userid'));
		$sql = $this->pdosql->createTable($table,$fields,$indexs);
		return $this->db->exec($sql);
	}

	public function insertUserModuleField($args)
	{
		if(HE == 'gbk')$args['fieldcharset'] = 'gbk';
		else $args['fieldcharset'] = 'utf8';
		$r = $this->getUserModuleById($args['fieldmoduleid']);
		$table = $r['moduletable'];
		$sql = $this->pdosql->addField($args,$table);
		return $this->db->exec($sql);
	}

	public function modifyUserModuleField($args,$fieldid)
	{
		if(HE == 'gbk')$args['fieldcharset'] = 'gbk';
		else $args['fieldcharset'] = 'utf8';
		$field = $this->getFieldById($fieldid);
		$args['field'] = $field['field'];
		$r = $this->getUserModuleById($field['fieldmoduleid']);
		$table = $r['moduletable'];
		$sql = $this->pdosql->modifyField($args,$table);
		return $this->db->exec($sql);
	}
	**/

	//public function
}

?>
