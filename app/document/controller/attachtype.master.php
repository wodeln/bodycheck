<?php

/*
 * Created on 2016-5-19
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

class action extends app
{
    public $a=0;
    public $courseList=array();
    public $courseList1=array();
    public function display()
    {
        $action = $this->ev->url(3);
        if (!method_exists($this, $action))
            $action = "index";
        $this->$action();
        exit;
    }

    private function del()
    {
        $page = $this->ev->get('page');
        $atid = $this->ev->get('atid');
        $this->attach->delAttachtypeById($atid);
        $message = array(
            'statusCode' => 200,
            "message" => "操作成功",
            "callbackType" => "forward",
            "forwardUrl" => "index.php?document-master-attachtype&page={$page}{$u}"
        );
        $this->G->R($message);
    }

    private function batdel()
    {
        $page = $this->ev->get('page');
        $delids = $this->ev->get('delids');
        foreach ($delids as $atid)
            $this->attach->delAttachtypeById($atid);
        $message = array(
            'statusCode' => 200,
            "message" => "操作成功",
            "callbackType" => "forward",
            "forwardUrl" => "index.php?document-master-attachtype&page={$page}{$u}"
        );
        $this->G->R($message);
    }

    private function modify()
    {
        $page = $this->ev->get('page');
        $atid = $this->ev->get('atid');
        if ($this->ev->get('modifyattachtype')) {
            $args = $this->ev->get('args');
            $this->attach->modifyAttachtypeById($args, $atid);
            $message = array(
                'statusCode' => 200,
                "message" => "操作成功",
                "callbackType" => "forward",
                "forwardUrl" => "index.php?document-master-attachtype&page={$page}{$u}"
            );
            $this->G->R($message);
        } else {
            $attachtype = $this->attach->getAttachtypeById($atid);
            $this->tpl->assign('attachtype', $attachtype);
            $this->tpl->display('types_modify');
        }
    }

    private function add()
    {
        if ($this->ev->get('inserttype')) {
            $args = $this->ev->get('args');
            $id = $this->attach->addAttachtype($args);
            $message = array(
                'statusCode' => 200,
                "message" => "操作成功",
                "callbackType" => "forward",
                "forwardUrl" => "index.php?document-master-attachtype{$u}"
            );
            $this->G->R($message);
        } else {
            $this->tpl->display('types_add');
        }
    }

    private function index()
    {
        $types = $this->attach->getAttachtypeList();
        $this->tpl->assign('types', $types);
        $this->tpl->display('types');
    }

    private function courseList()
    {
        $this->tpl->display('course_list');
    }


    private function getCourseJson(){
        $menu_id = $this->ev->post("menu_id");
        $course_name = $this->ev->post("course_name");
        $courses="";
        if(strlen($course_name)){
            $args[] = array('AND',"course_name LIKE :course_name",'course_name','%'.$course_name.'%');
            $courses = $this->attach->getCourseByArgs($args);
        }else{
            $menu = $this->attach->getMenuById($menu_id);
            $son = $this->attach->getSonMenu($menu['menu_code']);
            if(count($son)>0){
                $allMenu = $this->attach->getAllMenu();
                $tree=$this->createTree($menu, $allMenu);
                $menu['son']=$tree;
                $this->getCourseListByTree($tree);
            }else{
                $this->getCourseListByTree($menu);
            }
            foreach ($this->courseList as $k => $v){
                if (count($v)) {
                    foreach ($v as $key => $value) {
                        $courses[]= $value;
                    }
                }
            }
        }
        exit(json_encode($courses));
    }


    private function getCourseList()
    {
        $page = $this->ev->get('page')?$this->ev->get('page'):1;
        $code = $this->ev->get("code");
        $menu = $this->attach->getMenuByCode($code);
        $son = $this->attach->getSonMenu($menu['menu_code']);
        if(count($son)>0){
            $allMenu = $this->attach->getAllMenu();
            $tree=$this->createTree($menu, $allMenu);
            $menu['son']=$tree;
            $this->getCourseListByTree($tree);
        }else{
            $this->getCourseListByTree($menu);
        }

        $newCourseList="";
        foreach ($this->courseList as $k => $v){
            if (count($v)) {
                foreach ($v as $key => $value) {
                    $newCourseList[]= $value;
                }
            }
        }

        $pg = $this->G->make('pg');
        $pages = $pg->outPage($pg->getPagesNumber(count($newCourseList,10)),$page);
        $pages = str_replace('href="index.php?document-master-attachtype-getCourseList&code=','href="javascript:;" onclick="getPageContent(this)" code="',$pages);
        $pages = str_replace('&page=','" page="',$pages);

        $html="";
        $countList = ($page-1)*10+10;
        $start = ($page-1)*10;
        $end = $countList>count($newCourseList)? count($newCourseList):$countList;
        for($i=$start;$i<$end;$i++){
            if ($newCourseList[$i]['defalut_sound'] != "" && $newCourseList[$i]['defalut_sound'] != null) {
                $html .= "<tr>
								<td>" . $newCourseList[$i]['system_course_id'] . "</td>
								<td>" . $newCourseList[$i]['course_name'] . "</td>
								<td>" . $newCourseList[$i]['sound_case_name'] . "</td>
								<td><div class='sound' onmouseover='play(this)' onmouseout='pause(this)' cid='53'><audio src='" . $newCourseList[$i]['defalut_sound'] . "' id='audio53' loop='loop'></audio></div></td>
							</tr>";
            }else{
                $html .= "<tr>
								<td>" . $newCourseList[$i]['system_course_id'] . "</td>
								<td>" . $newCourseList[$i]['course_name'] . "</td>
								<td>-</td>
								<td>-</td>
							</tr>";
            }
        }

        $res['pages'] = $pages;
        $res['html'] = $html;

        exit(json_encode($res));
    }

    private function getCourseListByTree($tree)
    {
        $value = "";
//        $this->a++;

        foreach ($tree as $k => $v) {
//            $this->courseList[] = $v['menu_name'];
            $this->courseList[$v['menu_name'].$v['menu_code']] = $this->attach->getCourseByMenuId($v['menu_id']);
            if ($v['son'] != "") {
                $this->getCourseListByTree($v['son']);
//                $value[] = $v;
            }
        }
    }

    private function createTree($menu, $allMenu)
    {
        $tree = "";
        foreach ($allMenu as $k => $v) {
            if ($menu['menu_code'] == $v['father_code']) {
                $v['son'] = $this->createTree($v, $allMenu);
                $tree[] = $v;
            }
        }
        return $tree;
    }

    private function getJson(){
        $topMenu = $this->attach->getTopMenu("2");
        $allMenu = $this->attach->getAllMenu();

        foreach ($topMenu as $k=>$v){
            $topMenu[$k]['son']=$this->createTree($v, $allMenu);
        }

        $res="";
        $find = array("files/heart/","files/lung/","files/belly/");
        $replace = array("");
//        $tree=$topMenu;
        foreach ($topMenu as $k=>$v){
            $res[$k]['Name']=$v['menu_name'];
            $res[$k]['NetID']=$v['menu_code'];
            $res[$k]['Type']=$v['menu_type'];
            $res[$k]['TreeDir']="/".$v['menu_name'];
            $flash = $this->attach->getCourseByMenu($v['menu_name']);
            if($flash) {
                $res[$k]['Flash']=$flash[0]['flash_name'];
                $res[$k]['defalut_sound']=str_replace($find,$replace,$flash[0]['defalut_sound']);
            }
            $c = $this->attach->getCourseByMenuId($v['menu_id']);

            $i1=0;
            if($v['son']!=""){
                foreach ($v["son"] as $k1=>$v1){
                    $i1++;
                    $res[$k]["Courseware"][$k1]['Name']=$v1['menu_name'];
                    $res[$k]["Courseware"][$k1]['NetID']=$v1['menu_code'];
                    $res[$k]["Courseware"][$k1]['Type']=$v1['menu_type'];
                    $res[$k]["Courseware"][$k1]['TreeDir']="/".$v['menu_name']."/".$v1['menu_name'];
                    $flash1 = $this->attach->getCourseByMenu($v1['menu_name']);
                    if($flash1) {
                        $res[$k]["Courseware"][$k1]['Flash']=$flash1[0]['flash_name'];
                        $res[$k]["Courseware"][$k1]['defalut_sound']=str_replace($find,$replace,$flash1[0]['defalut_sound']);;
                    }
                    $c1 = $this->attach->getCourseByMenuId($v1['menu_id']);

                    $i2=0;
                    if($v1['son']!=""){
                        foreach ($v1["son"] as $k2=>$v2){
                            $i2++;
                            $res[$k]["Courseware"][$k1]["Courseware"][$k2]['Name']=$v2['menu_name'];
                            $res[$k]["Courseware"][$k1]["Courseware"][$k2]['NetID']=$v2['menu_code'];
                            $res[$k]["Courseware"][$k1]["Courseware"][$k2]['Type']=$v2['menu_type'];
                            $res[$k]["Courseware"][$k1]["Courseware"][$k2]['TreeDir']="/".$v['menu_name']."/".$v1['menu_name']."/".$v2['menu_name'];
                            $flash2 = $this->attach->getCourseByMenu($v2['menu_name']);
                            if($flash2) {
                                $res[$k]["Courseware"][$k1]["Courseware"][$k2]['Flash']=$flash2[0]['flash_name'];
                                $res[$k]["Courseware"][$k1]["Courseware"][$k2]['defalut_sound']=str_replace($find,$replace,$flash2[0]['defalut_sound']);;
                            }
                            $c2 = $this->attach->getCourseByMenuId($v2['menu_id']);

                            $i3=0;
                            if($v2['son']!=""){
                                foreach ($v2["son"] as $k3=>$v3){
                                    $i3++;
                                    $res[$k]["Courseware"][$k1]["Courseware"][$k2]["Courseware"][$k3]['Name']=$v3['menu_name'];
                                    $res[$k]["Courseware"][$k1]["Courseware"][$k2]["Courseware"][$k3]['NetID']=$v3['menu_code'];
                                    $res[$k]["Courseware"][$k1]["Courseware"][$k2]["Courseware"][$k3]['Type']=$v3['menu_type'];
                                    $res[$k]["Courseware"][$k1]["Courseware"][$k2]["Courseware"][$k3]['TreeDir']="/".$v['menu_name']."/".$v1['menu_name']."/".$v2['menu_name']."/".$v3['menu_name'];
                                    $flash3 = $this->attach->getCourseByMenu($v3['menu_name']);
                                    if($flash3) {
                                        $res[$k]["Courseware"][$k1]["Courseware"][$k2]["Courseware"][$k3]['Flash']=$flash3[0]['flash_name'];
                                        $res[$k]["Courseware"][$k1]["Courseware"][$k2]["Courseware"][$k3]['defalut_sound']=str_replace($find,$replace,$flash3[0]['defalut_sound']);
                                    }
                                    $c3 = $this->attach->getCourseByMenuId($v3['menu_id']);

                                    $i4=0;
                                    if($v3['son']!=""){
                                        foreach ($v3["son"] as $k4=>$v4){
                                            $i4++;
                                            $res[$k]["Courseware"][$k1]["Courseware"][$k2]["Courseware"][$k3]["Courseware"][$k4]['Name']=$v4['menu_name'];
                                            $res[$k]["Courseware"][$k1]["Courseware"][$k2]["Courseware"][$k3]["Courseware"][$k4]['NetID']=$v4['menu_code'];
                                            $res[$k]["Courseware"][$k1]["Courseware"][$k2]["Courseware"][$k3]["Courseware"][$k4]['Type']=$v4['menu_type'];
                                            $res[$k]["Courseware"][$k1]["Courseware"][$k2]["Courseware"][$k3]["Courseware"][$k4]['TreeDir']="/".$v['menu_name']."/".$v1['menu_name']."/".$v2['menu_name']."/".$v3['menu_name']."/".$v4['menu_name'];
                                            $flash4 = $this->attach->getCourseByMenu($v4['menu_name']);
                                            if($flash4) {
                                                $res[$k]["Courseware"][$k1]["Courseware"][$k2]["Courseware"][$k3]["Courseware"][$k4]['Flash']=$flash4[0]['flash_name'];
                                                $res[$k]["Courseware"][$k1]["Courseware"][$k2]["Courseware"][$k3]["Courseware"][$k4]['defalut_sound']=str_replace($find,$replace,$flash4[0]['defalut_sound']);
                                            }
                                            $c4 = $this->attach->getCourseByMenuId($v4['menu_id']);
                                            $i5=0;
                                            if($v4['son']!=""){
                                                foreach ($v4["son"] as $k5=>$v5){
                                                    $i5++;
                                                    $res[$k]["Courseware"][$k1]["Courseware"][$k2]["Courseware"][$k3]["Courseware"][$k4]["Courseware"][$k5]['Name']=$v5['menu_name'];
                                                    $res[$k]["Courseware"][$k1]["Courseware"][$k2]["Courseware"][$k3]["Courseware"][$k4]["Courseware"][$k5]['NetID']=$v5['menu_code'];
                                                    $res[$k]["Courseware"][$k1]["Courseware"][$k2]["Courseware"][$k3]["Courseware"][$k4]["Courseware"][$k5]['Type']=$v5['menu_type'];
                                                    $res[$k]["Courseware"][$k1]["Courseware"][$k2]["Courseware"][$k3]["Courseware"][$k4]["Courseware"][$k5]['TreeDir']="/".$v['menu_name']."/".$v1['menu_name']."/".$v2['menu_name']."/".$v3['menu_name']."/".$v4['menu_name']."/".$v5['menu_name'];
                                                    $flash5 = $this->attach->getCourseByMenu($v5['menu_name']);
                                                    if($flash5){
                                                        $res[$k]["Courseware"][$k1]["Courseware"][$k2]["Courseware"][$k3]["Courseware"][$k4]["Courseware"][$k5]['Flash']=$flash5[0]['flash_name'];
                                                        $res[$k]["Courseware"][$k1]["Courseware"][$k2]["Courseware"][$k3]["Courseware"][$k4]["Courseware"][$k5]['default_sound']=str_replace($find,$replace,$flash5[0]['defalut_sound']);
                                                    }

                                                    $c5 = $this->attach->getCourseByMenuId($v5['menu_id']);
                                                    if($c5){
                                                        foreach ($c5 as $key5=>$value5){
                                                            $res[$k]["Courseware"][$k1]["Courseware"][$k2]["Courseware"][$k3]["Courseware"][$k4]["Courseware"][$k5]["Courseware"][$key5]['Name']=$value5['course_name'];
                                                            $res[$k]["Courseware"][$k1]["Courseware"][$k2]["Courseware"][$k3]["Courseware"][$k4]["Courseware"][$k5]["Courseware"][$key5]['TreeDir']=$value5['path']."/".$value5['course_name'];
                                                            $res[$k]["Courseware"][$k1]["Courseware"][$k2]["Courseware"][$k3]["Courseware"][$k4]["Courseware"][$k5]["Courseware"][$key5]['Flash']=$value5['flash_name'];
                                                            $res[$k]["Courseware"][$k1]["Courseware"][$k2]["Courseware"][$k3]["Courseware"][$k4]["Courseware"][$k5]["Courseware"][$key5]['NetID']=$value5['course_code'];
                                                            $res[$k]["Courseware"][$k1]["Courseware"][$k2]["Courseware"][$k3]["Courseware"][$k4]["Courseware"][$k5]["Courseware"][$key5]['CaseId']=$value5['sound_case_id'];
                                                            $res[$k]["Courseware"][$k1]["Courseware"][$k2]["Courseware"][$k3]["Courseware"][$k4]["Courseware"][$k5]["Courseware"][$key5]['Type']=$value5['course_type'];
                                                            $res[$k]["Courseware"][$k1]["Courseware"][$k2]["Courseware"][$k3]["Courseware"][$k4]["Courseware"][$k5]["Courseware"][$key5]['default_sound']=str_replace($find,$replace,$value5['defalut_sound']);
                                                            if($value5['sound_case_id']!=0){
                                                                $res[$k]["Courseware"][$k1]["Courseware"][$k2]["Courseware"][$k3]["Courseware"][$k4]["Courseware"][$k5]["Courseware"][$key5]['sound_list']=$this->getSounds($value5['sound_case_id'])['sound'];
                                                                $res[$k]["Courseware"][$k1]["Courseware"][$k2]["Courseware"][$k3]["Courseware"][$k4]["Courseware"][$k5]["Courseware"][$key5]['tremble_position']=$this->getSounds($value5['sound_case_id'])['tremble_position'];
                                                            }


                                                        }
                                                    }
                                                }
                                            }
                                            if($c4){
                                                foreach ($c4 as $key4=>$value4){
                                                    $res[$k]["Courseware"][$k1]["Courseware"][$k2]["Courseware"][$k3]["Courseware"][$k4]["Courseware"][$i5]['Name']=$value4['course_name'];
                                                    $res[$k]["Courseware"][$k1]["Courseware"][$k2]["Courseware"][$k3]["Courseware"][$k4]["Courseware"][$i5]['TreeDir']=$value4['path']."/".$value4['course_name'];
                                                    $res[$k]["Courseware"][$k1]["Courseware"][$k2]["Courseware"][$k3]["Courseware"][$k4]["Courseware"][$i5]['Flash']=$value4['flash_name'];
                                                    $res[$k]["Courseware"][$k1]["Courseware"][$k2]["Courseware"][$k3]["Courseware"][$k4]["Courseware"][$i5]['NetID']=$value4['course_code'];
                                                    $res[$k]["Courseware"][$k1]["Courseware"][$k2]["Courseware"][$k3]["Courseware"][$k4]["Courseware"][$i5]['CaseId']=$value4['sound_case_id'];
                                                    $res[$k]["Courseware"][$k1]["Courseware"][$k2]["Courseware"][$k3]["Courseware"][$k4]["Courseware"][$i5]['Type']=$value4['course_type'];
                                                    $res[$k]["Courseware"][$k1]["Courseware"][$k2]["Courseware"][$k3]["Courseware"][$k4]["Courseware"][$i5]['default_sound']=str_replace($find,$replace,$value4['defalut_sound']);
                                                    if($value4['sound_case_id']!=0){
                                                        $res[$k]["Courseware"][$k1]["Courseware"][$k2]["Courseware"][$k3]["Courseware"][$k4]["Courseware"][$i5]['sound_list']=$this->getSounds($value4['sound_case_id'])['sound'];
                                                        $res[$k]["Courseware"][$k1]["Courseware"][$k2]["Courseware"][$k3]["Courseware"][$k4]["Courseware"][$i5]['tremble_position']=$this->getSounds($value4['sound_case_id'])['tremble_position'];
                                                    }
                                                    $i5++;
                                                }
                                            }
                                        }
                                    }
                                    if($c3){
                                        foreach ($c3 as $key3=>$value3){
//                                            if($i4!=0) $i4++;
                                            $res[$k]["Courseware"][$k1]["Courseware"][$k2]["Courseware"][$k3]["Courseware"][$i4]['Name']=$value3['course_name'];
                                            $res[$k]["Courseware"][$k1]["Courseware"][$k2]["Courseware"][$k3]["Courseware"][$i4]['TreeDir']=$value3['path']."/".$value3['course_name'];
                                            $res[$k]["Courseware"][$k1]["Courseware"][$k2]["Courseware"][$k3]["Courseware"][$i4]['Flash']=$value3['flash_name'];
                                            $res[$k]["Courseware"][$k1]["Courseware"][$k2]["Courseware"][$k3]["Courseware"][$i4]['NetID']=$value3['course_code'];
                                            $res[$k]["Courseware"][$k1]["Courseware"][$k2]["Courseware"][$k3]["Courseware"][$i4]['CaseId']=$value3['sound_case_id'];
                                            $res[$k]["Courseware"][$k1]["Courseware"][$k2]["Courseware"][$k3]["Courseware"][$i4]['Type']=$value3['course_type'];
                                            $res[$k]["Courseware"][$k1]["Courseware"][$k2]["Courseware"][$k3]["Courseware"][$i4]['defalut_sound']=str_replace($find,$replace,$value3['defalut_sound']);
                                            if($value3['sound_case_id']!=0) {
                                                $res[$k]["Courseware"][$k1]["Courseware"][$k2]["Courseware"][$k3]["Courseware"][$i4]['sound_list'] = $this->getSounds($value3['sound_case_id'])['sound'];
                                                $res[$k]["Courseware"][$k1]["Courseware"][$k2]["Courseware"][$k3]["Courseware"][$i4]['tremble_position'] = $this->getSounds($value3['sound_case_id'])['tremble_position'];
                                            }
                                            $i4++;
                                        }
                                    }
                                }
                            }
                            if($c2){
                                foreach ($c2 as $key2=>$value2){
//                                    if($i3!=0) $i3++;
                                    $res[$k]["Courseware"][$k1]["Courseware"][$k2]["Courseware"][$i3]['Name']=$value2['course_name'];
                                    $res[$k]["Courseware"][$k1]["Courseware"][$k2]["Courseware"][$i3]['TreeDir']=$value2['path']."/".$value2['course_name'];
                                    $res[$k]["Courseware"][$k1]["Courseware"][$k2]["Courseware"][$i3]['Flash']=$value2['flash_name'];
                                    $res[$k]["Courseware"][$k1]["Courseware"][$k2]["Courseware"][$i3]['NetID']=$value2['course_code'];
                                    $res[$k]["Courseware"][$k1]["Courseware"][$k2]["Courseware"][$i3]['CaseId']=$value2['sound_case_id'];
                                    $res[$k]["Courseware"][$k1]["Courseware"][$k2]["Courseware"][$i3]['Type']=$value2['course_type'];
                                    $res[$k]["Courseware"][$k1]["Courseware"][$k2]["Courseware"][$i3]['defalut_sound']=str_replace($find,$replace,$value2['defalut_sound']);
                                    if($value2['sound_case_id']!=0) {
                                        $res[$k]["Courseware"][$k1]["Courseware"][$k2]["Courseware"][$i3]['sound_list'] = $this->getSounds($value2['sound_case_id'])['sound'];
                                        $res[$k]["Courseware"][$k1]["Courseware"][$k2]["Courseware"][$i3]['tremble_position'] = $this->getSounds($value2['sound_case_id'])['tremble_position'];
                                    }
                                    $i3++;
                                }
                            }
                        }
                    }
                    if($c1){
                        foreach ($c1 as $key1=>$value1){
//                            if($i2!=0) $i2++;
                            $res[$k]["Courseware"][$k1]["Courseware"][$i2]['Name']=$value1['course_name'];
                            $res[$k]["Courseware"][$k1]["Courseware"][$i2]['TreeDir']=$value1['path']."/".$value1['course_name'];
                            $res[$k]["Courseware"][$k1]["Courseware"][$i2]['Flash']=$value1['flash_name'];
                            $res[$k]["Courseware"][$k1]["Courseware"][$i2]['NetID']=$value1['course_code'];
                            $res[$k]["Courseware"][$k1]["Courseware"][$i2]['CaseId']=$value1['sound_case_id'];
                            $res[$k]["Courseware"][$k1]["Courseware"][$i2]['Type']=$value1['course_type'];
                            $res[$k]["Courseware"][$k1]["Courseware"][$i2]['defalut_sound']=str_replace($find,$replace,$value1['defalut_sound']);
                            if($value1['sound_case_id']!=0) {
                                $res[$k]["Courseware"][$k1]["Courseware"][$i2]['sound_list'] = $this->getSounds($value1['sound_case_id'])['sound'];
                                $res[$k]["Courseware"][$k1]["Courseware"][$i2]['tremble_position'] = $this->getSounds($value1['sound_case_id'])['tremble_position'];
                            }
                            $i2++;
                        }
                    }
                }
            }
            if($c){
                foreach ($c as $key=>$value){
//                    if($i1!=0) $i1++;
                    $res[$k]["Courseware"][$i1]['Name']=$value['course_name'];
                    $res[$k]["Courseware"][$i1]['TreeDir']=$value['path']."/".$value['course_name'];
                    $res[$k]["Courseware"][$i1]['Flash']=$value['flash_name'];
                    $res[$k]["Courseware"][$i1]['NetID']=$value['course_code'];
                    $res[$k]["Courseware"][$i1]['CaseId']=$value['sound_case_id'];
                    $res[$k]["Courseware"][$i1]['Type']=$value['course_type'];
                    $res[$k]["Courseware"][$i1]['defalut_sound']=str_replace($find,$replace,$value['defalut_sound']);
                    if($value['sound_case_id']!=0) {
                        $res[$k]["Courseware"][$i1]['sound_list'] = $this->getSounds($value['sound_case_id'])['sound'];
                        $res[$k]["Courseware"][$i1]['tremble_position'] = $this->getSounds($value['sound_case_id'])['tremble_position'];
                    }
                    $i1++;
                }
            }
        }
        $result['CoursewareList']['Courseware']=$res;
        exit(json_encode($result));
    }

    private function getSounds($soundCaseId){
        $sound=$this->attach->getSoundCase($soundCaseId);
        $sounds=$this->attach->getSounds($sound['sound_case_id']);

        $res="";
        $res['tremble_position']=$sound['tremble'];
        foreach ($sounds as $k => $v){
            $res['sound'][$k]['sound_file']=$v['file_name'];
            $res['sound'][$k]['is_heart']=$sound['organ_type'];

            $play = $this->attach->getPosition($v['soundcase_sound_id']);
            foreach ($play as $key=>$value){
                $res['sound'][$k]["position_list"][$key]["position"] = $value["play_position"];
                $res['sound'][$k]["position_list"][$key]["volume"] = $value["sound_volume"];
            }
        }
        return $res;
    }

    private function getSoundCase(){
        $soundCase = $this->attach->getAllSoundCase();
        $res = "";
        $find = array("files/heart/","files/lung/","files/belly/");
        $replace = array("");
        foreach ($soundCase as $k=>$v){
            $res[$k]['m_strCaseId'] = $v['sound_case_id'];
            $res[$k]['m_strName'] = $v['sound_case_name'];
            $res[$k]['m_strType'] = $v['organ_type'];
            $res[$k]['m_strHuman'] = $v['case_type'];
            $res[$k]['default_sound'] = str_replace($find,$replace,$v['sound_file']);
            $res[$k]['tremble'] = $v['tremble'];
            $res[$k]['sound_list'] = $this->getSounds($v['sound_case_id'])['sound'];
        }
//        print_r($res);
        exit(json_encode($res));
    }

    private function getCourseListByTree1($tree)
    {
        $res = "";
        foreach ($tree as $k => $v) {
//            $this->courseList[] = $v['menu_name'];
//            $this->courseList1[$v['menu_name'].$v['menu_code']] = $this->attach->getCourseByMenuId($v['menu_id']);
            $v['courseList']=$this->attach->getCourseByMenuId($v['menu_id']);
            if ($v['son'] != "") {
                $this->getCourseListByTree1($v['son']);
                $res[] = $v;
            }
        }
        return $res;
    }


    private function echoTree()
    {

//        $topMenu = $this->attach->getTopMenu("0,1,2,4");
        $allMenu = $this->attach->getAllMenu();

        $menu_id = $this->ev->get("menu_id");
        $father_code = $this->ev->get("father_code");

        $allMenuNew = "";
        $newTopMenu = "";
        foreach ($allMenu as $k=>$v){
            $allMenuNew[$k]['text'] = $v['menu_name'];
            $allMenuNew[$k]['id'] = $v['menu_id'];
            $allMenuNew[$k]['menu_code'] = $v['menu_code'];
            $allMenuNew[$k]['father_code'] = $v['father_code'];
            $allMenuNew[$k]['state'] = $v['state'];
            if($v['father_code']==11){
                $newTopMenu[]=$allMenuNew[$k];
            }
        }


        foreach ($newTopMenu as $k=>$v){
            $newTopMenu[$k]['nodes']=$this->createTreeNew($newTopMenu[$k], $allMenuNew);
        }
        exit(json_encode($newTopMenu));
    }

    private function createTreeNew1($menu, $allMenu)
    {
        $tree = "";
        foreach ($allMenu as $k => $v) {
            if ($menu['menu_code'] == $v['father_code']) {
                $v['nodes'] = $this->createTreeNew1($v, $allMenu);
                $tree.="<ul><li><span code='".$v["menu_code"]."'><i></i> ".$v['menu_name']."</span></li></ul>";
            }
        }
        return $tree;
    }

    private function createTreeNew($menu, $allMenu)
    {
        $tree = "";
        foreach ($allMenu as $k => $v) {
            if ($menu['menu_code'] == $v['father_code']) {
                $v['nodes'] = $this->createTreeNew($v, $allMenu);
                $tree[] = $v;
            }
        }
        return $tree;
    }


    function get_top_parentid($allMenu,$father_code){

       foreach ($allMenu as $k=>$v){
           if($father_code==11) break;
           if($v['menu_code']==$father_code){
                $allMenu[$k]['state']['expanded']=true;
                $allMenu= $this->get_top_parentid($allMenu,$v['father_code']);
           }
       }
        return $allMenu;
    }

    private function coursePackage(){
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

        if(strlen($search['course_package_name']))$args[] = array('AND',"course_package_name = :course_package_name",'course_package_name',$search['course_package_name']);

        $packages = $this->attach->getPackageList($page,10,$args);

        foreach ($packages['data'] as $k=>$v){
            /*$str = "";
            $courses = $this->attach->getCoursesByPackageId($v['course_package_id']);
            foreach ($courses as $key=>$value){
                $str.=$value['course_name'].",";
            }
            $packages['data'][$k]['courses'] = substr($str,0,strlen($str)-1) ;*/
            $packages['data'][$k]['discern_name'] = $this->sound->getSoundCasePackageById($v['discern_id'],1)['package_name'] ;
            //心音课件数量
            $packages['data'][$k]['count_heart'] = $this->attach->getCountByPackageIdType($v['course_package_id'],0)['c'];
            //肺音课件数量
            $packages['data'][$k]['count_lung'] = $this->attach->getCountByPackageIdType($v['course_package_id'],1)['c'];
            //腹部触诊课件数量
            $packages['data'][$k]['count_touch'] = $this->attach->getCountByPackageIdType($v['course_package_id'],2)['c'];
            //心电图课件数量
            $packages['data'][$k]['count_cardiogram'] = $this->attach->getCountByPackageIdType($v['course_package_id'],4)['c'];
        }
        $this->tpl->assign('packages', $packages);
        $this->tpl->display('course_package');
    }

    private function addPackage(){

        $page=$this->ev->post('page');
        if($this->ev->get('insertPackage')) {
            $args = $this->ev->post('args');
            $courses = $this->ev->post('to');
            $package = $this->attach->getPackageByName($args['course_package_name']);
            if ($package) {
                $errmsg = $args['course_package_name'] . " 套餐已存在";
            }
            if(count($courses)==0) $errmsg = "请正确选择课件";
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
            $packageId = $this->attach->insertPackage($args);
            $this->attach->addPackageCourse($courses, $packageId);
            $message = array(
                'statusCode' => 200,
                "message" => "操作成功",
                "callbackType" => "forward",
                "forwardUrl" => "index.php?document-master-attachtype-coursePackage&page={$page}{$this->u}"
            );
            exit(json_encode($message));
        }else{
            $soundPackages = $this->sound->getAllSoundCasePackege();
            $this->tpl->assign("soundPackages",$soundPackages);
            $this->tpl->display('package_add');
        }

    }

    private function editPackage(){
        if($this->ev->post("editPackage")){
            $package_id = $this->ev->post("course_package_id");
            $page=$this->ev->post('page');
            $args = $this->ev->post('args');
            $courses = $this->ev->post('to');
            $package = $this->attach->getPackageByName($args['course_package_name'],$package_id);
            if ($package) {
                $errmsg = $args['course_package_name'] . " 套餐已存在";
            }
            if(count($courses)==0) $errmsg = "请正确选择课件";
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
            $this->attach->updateCoursePackage($args,$package_id);
            $this->attach->addPackageCourse($courses, $package_id);
            $message = array(
                'statusCode' => 200,
                "message" => "操作成功",
                "callbackType" => "forward",
                "forwardUrl" => "index.php?document-master-attachtype-coursePackage&page={$page}{$this->u}"
            );
            exit(json_encode($message));
        }else{
            $package_id = $this->ev->get("package_id");
            $page=$this->ev->get('page');
            $package = $this->attach->getPackageById($package_id);
            $selectCourse = $this->attach->getCoursesByPackageId($package_id);
            /*foreach ($selectCourse as $k=>$v){
                $positionList = $this->sound->getPositionByCaseId($v['case_id']);
                $str="";
                foreach ($positionList as $key=>$value){
                    $str.=$value['play_position'].",";
                }
                $selectCase[$k]['positions'] = substr($str,0,strlen($str)-1) ;

            }*/
            $soundPackages = $this->sound->getAllSoundCasePackege();
            $this->tpl->assign("soundPackages",$soundPackages);
            $this->tpl->assign("package",$package);
            $this->tpl->assign("selectCourse",$selectCourse);
            $this->tpl->display("package_edit");
        }
    }

    private function delPackage(){
        $page = $this->ev->get('page');
        $packageId = $this->ev->get('package_id');
        $this->attach->delPackageById($packageId);
        $message = array(
            'statusCode' => 200,
            "message" => "操作成功",
            "navTabId" => "",
            "rel" => "",
            "callbackType" => "forward",
            "forwardUrl" => "index.php?document-master-attachtype-coursePackage&page={$page}{$this->u}"
        );
        exit(json_encode($message));
    }
}


?>
