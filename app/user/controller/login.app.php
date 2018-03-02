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
        if (!method_exists($this, $action))
            $action = "index";
        $this->$action();
        exit;
    }

    private function index()
    {
//		$a=preg_match("/^1[34578]\d{9}$/", "15021421116");
        $appid = 'user';
        $app = $this->G->make('apps', 'core')->getApp($appid);
        $this->tpl->assign('app', $app);
        $args = $this->ev->get('args');
        $user = $this->user->getUserByUserName($args['username']);
        if ($this->ev->get('userlogin')) {
            $tmp = $this->session->getSessionValue();
            if (TIME - $tmp['sessionlasttime'] < 1) {
                $message = array(
                    'statusCode' => 300,
                    "message" => "操作失败"
                );
                exit(json_encode($message));
            }

            if ($user['userid']) {
                if ($user['userpassword'] == md5($args['userpassword'])) {
                    $loginTimes = $this->user->getLoginTimes($user['userid']);
                    if ($loginTimes['loginTimes'] > 0) {
                        if ($app['appsetting']['loginmodel'] == 1) $this->session->offOnlineUser($user['userid']);

                        $this->session->setSessionUser(array('sessionuserid' => $user['userid'], 'sessionpassword' => $user['userpassword'], 'sessionip' => $this->ev->getClientIp(), 'sessiongroupid' => $user['usergroupid'], 'sessionlogintime' => TIME, 'sessionusername' => $user['username']));
                        $message = array(
                            'statusCode' => 201,
                            "message" => "操作成功",
                            "callbackType" => 'forward',
                            "forwardUrl" => "reload"
                        );
                        $logArgs['loguserid'] = $user['userid'];
                        $logArgs['logtime'] = TIME;
                        $this->user->insertLog($logArgs);
                        $this->G->R($message);
                    } else {
                        $message = array(
                            'statusCode' => 201,
                            "message" => "首次登陆，请修改密码",
                            "callbackType" => 'forward',
                            "forwardUrl" => "index.php?user-center-privatement-firstChangePassword&userid=" . $user['userid']
                        );
                        $this->G->R($message);
                    }
                } else {
                    $message = array(
                        'statusCode' => 300,
                        'errorinput' => 'args[username]',
                        "message" => "登录失败，因为用户名或密码错误"
                    );
                    exit(json_encode($message));
                }
            } else {
                $message = array(
                    'statusCode' => 300,
                    'errorinput' => 'args[username]',
                    "message" => "登录失败，因为用户名不存在"
                );
                exit(json_encode($message));
            }
        } else {
            $this->tpl->display('login');
        }
    }

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
                    "user_pic" => $user['photo']
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
                    "user_pic" => ""
                );
                exit(json_encode($m));
            }
        }else{
            $m = array(
                'state' => "0",
                'reason' => "1",
                "user_pic" => ""
            );
            exit(json_encode($m));
        }
	}
}


?>
