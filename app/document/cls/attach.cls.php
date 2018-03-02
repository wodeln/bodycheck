<?php

class attach_document
{
	public $G;

	public function __construct(&$G)
	{
		$this->G = $G;
		$this->pdosql = $this->G->make('pdosql');
		$this->db = $this->G->make('pepdo');
		$this->pg = $this->G->make('pg');
		$this->ev = $this->G->make('ev');
	}

	//按页获取附件列表
	//参数：无
	//返回值：附件列表
	public function getAttachList($args,$page,$number = PN)
	{
		$data = array(
			'select' => false,
			'table' => 'attach',
			'query' => $args,
			'index' => 'attid'
		);
		$r = $this->db->listElements($page,$number,$data);
		return $r;
	}

	//根据ID获取附件信息
	//参数：附件ID
	//返回值：该附件信息数组
	public function getAttachById($attid)
	{
		$data = array(false,'attach',array(array('AND',"attid = :attid",'attid',$attid)));
		$sql = $this->pdosql->makeSelect($data);
		return $this->db->fetch($sql);
	}

	public function addAttach($args)
	{
		$args['attinputtime'] = TIME;
		$data = array('attach',$args);
		$sql = $this->pdosql->makeInsert($data);
		$this->db->exec($sql);
		return $this->db->lastInsertId();
	}

	//修改附件信息
	//参数：附件ID,要修改的信息
	//返回值：true
	public function modifyAttach($attid,$args)
	{
		$data = array('attach',$args,array(array('AND',"attid = :attid",'attid',$attid)),false,'menu_code DESC');
		$sql = $this->pdosql->makeUpdate($data);
		$this->db->exec($sql);
		return true;
	}

	//删除附件
	//参数：附件ID
	//返回值：受影响的记录数
	public function delAttach($attid)
	{
		$data = array('attach',array(array('AND',"attid = :attid",'attid',$attid)));
		$sql = $this->pdosql->makeDelete($data);
		$this->db->exec($sql);
		return $this->db->affectedRows();
	}

	public function addAttachtype($args)
	{
		$data = array('attachtype',$args);
		$sql = $this->pdosql->makeInsert($data);
		$this->db->exec($sql);
		return $this->db->lastInsertId();
	}

	public function getAllowAttachExts()
	{
		$exts = array();
		$r = $this->getAttachtypeList();
		foreach($r as $p)
		{
			$tmp = explode(',',$p['attachexts']);
			if(count($tmp))
			{
				foreach($tmp as $tp)
				{
					if($tp)
					$exts[] = $tp;
				}
			}
		}
		return $exts;
	}

	public function getAttachtypeList()
	{
		$data = array(false,'attachtype',1);
		$sql = $this->pdosql->makeSelect($data);
		return $this->db->fetchAll($sql,'atid');
	}

	public function getAttachtypeByName($attachtype)
	{
		$data = array(false,'attachtype',array(array('AND',"attachtype = :attachtype",'attachtype',$attachtype)));
		$sql = $this->pdosql->makeSelect($data);
		return $this->db->fetch($sql);
	}

	public function getAttachtypeById($atid)
	{
		$data = array(false,'attachtype',array(array('AND',"atid = :atid",'atid',$atid)));
		$sql = $this->pdosql->makeSelect($data);
		return $this->db->fetch($sql);
	}

	public function modifyAttachtypeById($args,$atid)
	{
		$data = array('attachtype',$args,array(array('AND',"atid = :atid",'atid',$atid)));
		$sql = $this->pdosql->makeUpdate($data);
		$this->db->exec($sql);
		return true;
	}

	public function delAttachtypeById($atid)
	{
		$data = array('attachtype',array(array('AND',"atid = :atid",'atid',$atid)));
		$sql = $this->pdosql->makeDelete($data);
		$this->db->exec($sql);
		return $this->db->affectedRows();
	}

    /**
	 * 根据课件名称查询课件对象
     * @param $course_name 课件名称
     * @return 课件对象
     */
	public function getCourseByName($course_name,$course_id=null){
		if($course_id==null){
            $data = array(false,'custom_course',array(array('AND',"course_name = :course_name",'course_name',$course_name)));
		}else{
            $data = array(false,'custom_course',
				array(array('AND',"course_name = :course_name",'course_name',$course_name),
                    array('AND',"custom_course_id != :custom_course_id",'custom_course_id',$course_id)

				));
		}

        $sql = $this->pdosql->makeSelect($data);
        return $this->db->fetch($sql);
	}

    /**
	 * 插入课件
     * @param $args 表单数据
     * @return 插入课件的主键ID
     */
	public function insertCourse($args){
        $data = array('custom_course',$args);
        $sql = $this->pdosql->makeInsert($data);
        $this->db->exec($sql);
        return $this->db->lastInsertId();
	}

    /**
	 * 获取自定义课件列表
     * @param $args 搜索条件
     * @param $page 当前页码
     * @param int $number 每页显示数量
     * @return 自定义课件对象list
     */
	public function getCourseList($args,$page,$number = PN){
        $data = array(
            'select' => false,
            'table' => 'custom_course',
            'query' => $args,
            'index' => 'custom_course_id'
        );
        $r = $this->db->listElements($page,$number,$data);
        return $r;
	}

	public function getCourseByArgs($args){
		$data = array(false,'system_course',$args);
		$sql = $this->pdosql->makeSelect($data);
		return $this->db->fetchAll($sql);
	}

	public function getCourseById($course_id){
        $data = array(false,'custom_course',array(array('AND',"custom_course_id = :custom_course_id",'custom_course_id',$course_id)));
        $sql = $this->pdosql->makeSelect($data);
        return $this->db->fetch($sql);
	}

	public function updateCourse($args,$course_id){
        $data = array('custom_course',$args,array(array('AND',"custom_course_id = :custom_course_id",'custom_course_id',$course_id)));
        $sql = $this->pdosql->makeUpdate($data);
        $this->db->exec($sql);
	}

    public function getMenuByCode($code){
        $data = array(false,array('menu'),array(array('AND',"menu_code = :menu_code",'menu_code',$code)));
        $sql = $this->pdosql->makeSelect($data);
        return $this->db->fetch($sql);
    }

    public function getMenuById($menuId){
        $data = array(false,array('menu'),array(array('AND',"menu_id = :menu_id",'menu_id',$menuId)));
        $sql = $this->pdosql->makeSelect($data);
        return $this->db->fetch($sql);
    }

    public function getAllMenu($code){
        $data = array(false,array('menu'));
        $sql = $this->pdosql->makeSelect($data);
        return $this->db->fetchAll($sql);
    }

    public function getSonMenu($code){
        $data = array(false,array('menu'),array(array('AND',"father_code = :father_code",'father_code',$code)));
        $sql = $this->pdosql->makeSelect($data);
        return $this->db->fetchAll($sql);
	}

	public function getCourseByMenuId($menuId){
//        $data = array(false,array('system_course'),array(array('AND',"menu_id = :menu_id",'menu_id',$menuId),array('AND',"course_menu = :course_menu",'course_menu',1)),false,"course_code ASC");
//        $sql = $this->pdosql->makeSelect($data);
		$sql['sql'] = "SELECT sc.*,m.menu_name,CONCAT(m.menu_name,' / ',sc.course_name) full_name FROM x2_system_course sc
						LEFT JOIN x2_menu m ON sc.menu_code = m.menu_code
						WHERE sc.menu_id=$menuId
						AND course_menu=1";
		$sql['v']	= null;
        return $this->db->fetchAll($sql);
	}

    public function getTopMenu($ids){
        $sql['sql'] = "SELECT * FROM x2_menu WHERE menu_code IN ($ids)";
        $sql['v'] = null;
        return $this->db->fetchAll($sql);
    }

    public function getAllCourse(){
        $data = array(false,array('system_course'));
        $sql = $this->pdosql->makeSelect($data);
        return $this->db->fetchAll($sql);
	}

	public function getCourseByMenu($menuName){
        $data = array(false,array('system_course'),array(array('AND',"course_name = :course_name",'course_name',$menuName),array('AND',"course_menu = :course_menu",'course_menu',0)));
        $sql = $this->pdosql->makeSelect($data);
        return $this->db->fetchAll($sql);
    }

    public function getSounds($soundCaseId){
        $data = array(false,array('soundcase_sound'),array(array('AND',"sound_case_id = :sound_case_id",'sound_case_id',$soundCaseId)));
        $sql = $this->pdosql->makeSelect($data);
        return $this->db->fetchAll($sql);
    }

    public function getSoundCase($soundCaseId){
        $data = array(false,array('sound_case'),array(array('AND',"sound_case_id = :sound_case_id",'sound_case_id',$soundCaseId)));
        $sql = $this->pdosql->makeSelect($data);
        return $this->db->fetch($sql);
    }

    public function getPosition($soundCaseSoudId){
        $data = array(false,array('soundcase_position'),array(array('AND',"soundcase_sound_id = :soundcase_sound_id",'soundcase_sound_id',$soundCaseSoudId)));
        $sql = $this->pdosql->makeSelect($data);
        return $this->db->fetchAll($sql);
    }

    public function getAllSoundCase(){
        $data = array(false,array('sound_case'));
        $sql = $this->pdosql->makeSelect($data);
        return $this->db->fetchAll($sql);
	}

	public function getPackageByName($courseName,$packageId=null){
    	if($packageId==null){
            $data = array(false,array('course_package'),array(array('AND',"course_package_name = :course_package_name",'course_package_name',$courseName)));
		}else{
            $data = array(false,array('course_package'),array(
            	array('AND',"course_package_name = :course_package_name",'course_package_name',$courseName),
            	array('AND',"course_package_id != :course_package_id",'course_package_id',$packageId),
			));
		}

        $sql = $this->pdosql->makeSelect($data);
        return $this->db->fetch($sql);
	}

    public function insertPackage($args){
        $data = array('course_package',$args);
        $sql = $this->pdosql->makeInsert($data);
        $this->db->exec($sql);
        return $this->db->lastInsertId();
    }

    public function updateCoursePackage($args,$packageId){
        $data = array('course_package',$args,array(array('AND',"course_package_id = :course_package_id",'course_package_id',$packageId)));
        $sql = $this->pdosql->makeUpdate($data);
        $this->db->exec($sql);
	}

    public function addPackageCourse($courses,$packageId){

        $sql['sql']	= "DELETE FROM x2_package_course WHERE package_id=".$packageId;
        $sql['v'] = null;
        $this->db->exec($sql);
        foreach ($courses as $v) {
            $sql['sql'] = "INSERT INTO x2_package_course (course_id,package_id) VALUE ($v,$packageId)";
            $this->db->exec($sql);
        }
        return true;
    }

    public function getPackageList($page,$number = 20,$args = 1)
    {
        $page = $page > 0?$page:1;
        $where = $args==1? "":"WHERE course_package_name=".$args['course_package_name'];
        $r = array();
        $data = array(false,'course_package',$args,false,'course_package_id DESC',array(intval($page-1)*$number,$number));
        $sql = $this->pdosql->makeSelect($data);
        /*$sql['sql'] = "SELECT cp.*,d.discern_name FROM x2_course_package cp
					  LEFT JOIN x2_discern d ON cp.discern_id = d.discern_id
					  $where
					  ORDER BY course_package_id DESC";
        $sql['v']	= null;*/
        $r['data'] = $this->db->fetchALL($sql,false,'caseinfo');
        /*foreach ($r['data'] as $k=>$v){
            $r['data'][$k]['case'] = $this->getCaseByDiscernId($v["discern_id"]);
        }*/
        $data = array('COUNT(*) AS number','discern',$args,false,false,false);
        $sql = $this->pdosql->makeSelect($data);
        $tmp = $this->db->fetch($sql);
        $r['number'] = $tmp['number'];
        $pages = $this->pg->outPage($this->pg->getPagesNumber($tmp['number'],$number),$page);
        $r['pages'] = $pages;
        return $r;
    }

    public function getCoursesByPackageId($packageId){
        $sql['sql'] = "SELECT pc.*,sc.system_course_id,sc.course_name,sc.course_code,sc.course_type FROM x2_package_course pc 
						LEFT JOIN x2_system_course sc ON pc.course_id=sc.system_course_id
						WHERE pc.package_id = $packageId";
        $sql['v']	= null;
        return $this->db->fetchAll($sql);
	}

	public function getCountByPackageIdType($packageId,$type){
        $sql['sql'] = "SELECT count(1) c FROM x2_package_course pc 
						LEFT JOIN x2_system_course sc ON pc.course_id=sc.system_course_id
						WHERE pc.package_id = $packageId AND course_type=$type";
        $sql['v']   = null;
        return $this->db->fetch($sql);
    }

	public function getPackageById($packageId){
        $sql['sql'] = "SELECT cp.*,sp.package_name discern_name FROM x2_course_package cp
					  LEFT JOIN x2_soundcase_package sp ON cp.discern_id = sp.soundcase_package_id
					  WHERE cp.course_package_id=$packageId";
        $sql['v']	= null;
        return $this->db->fetch($sql);
	}

	public function delPackageById($packageId){
        $sql['sql'] = "DELETE FROM x2_package_course WHERE package_id=$packageId";
        $sql['v']	= null;

        $this->db->exec($sql);
        $sql['sql'] = "DELETE  FROM x2_course_package WHERE course_package_id=$packageId";
        return $this->db->exec($sql);
    }
}

?>
