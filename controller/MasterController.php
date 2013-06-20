<?php

/**
 * 后台管理员controller
 * @author bric.shi
 * @create 2013.04.18
 */
class MasterController extends Controller {

    public $layout = 'main';
    public $model = "";
    
    function __construct() {
	$this->model = Model::mo('Master');
    }

    /**
     * 用户首页
     */
    public function actionIndex() {
        $pageSize = 10;
        $userCount = $this->model->getCount();
    	$pager = new Pager($userCount, $pageSize, 'index.php?r=master/index', 4);
        $userData = $this->model->getAll('*','where `enable`=1',$pager->currentPage,$pageSize);
        $pageStr = $pager->getHtml();
        $this->view('master/index', array(
            'userData' => $userData,
            'pageStr' =>$pageStr
        ));
    }
    //更换角色
    public function actionChangeRole(){
        $masterId = $this->getPost('masterId');
        $newRole = trim($this->getPost('newRole'),',');
        if($masterId && $newRole){
            $this->model->update(array(
                'role'=>$newRole
            ),'`id`='.$masterId);
        }
        $this->forward('master/index');
    }
    /**
     * 分配角色
     */
    public function actionRole() {
        $id = $this->getGet("id");
        if ($id == "") {
            $this->forward('master/index');
        }else{
            $allRoles = Model::mo('role')->getAllRoles();
            $masterRoles = $this->model->getMasterRole($id);
            $targetRoles = array();
            foreach($allRoles as $k=>$v){
                if(in_array($k, $masterRoles)){
                    $targetRoles[$k] = $v;
                    unset($allRoles[$k]);
                }
            }
            $this->view('master/role', array(
                'srcRoles' => $allRoles,
                'masterRoles' => $targetRoles,
                'masterId' => $id
            ));
        }
    }

    /**
     * 用户添加
     */
    public function actionAdd() {

        $submit = $this->getPost("smt"); //是否表单提交

        if ($submit == 1) {
            $data['name'] = $this->getPost("name");
            $data['realname'] = $this->getPost("realname");
            $pwd = $this->getPost("pwd");
            $pwd2 = $this->getPost("pwdag");
            $data['sex'] = $this->getPost("sex");
            $data['dept'] = $this->getPost("dept");
            $data['position'] = $this->getPost("position");
            $data['update_master_id'] = $_SESSION['user']['id'];
            $data['create_time'] = time();
            $data['update_time'] = time();

            if ($data['name'] == "") {
                jsonExit(array("msg" => "用户名必须", "status" => -1));
            }

            if ($pwd != $pwd2) {
                jsonExit(array("msg" => "两次密码不一致", "status" => -2));
            }

            $data['password'] = md5($pwd);
            $this->model->addMaster($data);

            jsonExit(array("msg" => "添加成功", "status" => 1));
        }
        $this->view('master/add');
    }

    /**
     * 用户更新
     */
    public function actionUpdate() {
        $id = $this->getGet("id");
        if ($id == "") {
            $id = $this->getPost("id");
        }
        if (intval($id) <= 0) {
            jsonExit(array("msg" => "ID是必须的", "status" => -2));
        }

        $submit = $this->getPost("smt");

        if ($submit == 1) {
            $data['name'] = $this->getPost("name");
            $data['realname'] = $this->getPost("realname");
            $data['sex'] = $this->getPost("sex");
            $data['dept'] = $this->getPost("dept");
            $data['position'] = $this->getPost("position");
            $data['update_master_id'] = $_SESSION['user']['id'];
            $data['update_time'] = time();

            if ($data['name'] == "") {
                jsonExit(array("msg" => "用户名必须", "status" => -1));
            }

            $this->model->updateMaster("id=" . $id, $data);
            jsonExit(array("msg" => "修改成功", "status" => 1));
        }

        $master = $this->model->getMasterById($id);
        $this->view('master/update', array(
            "master" => $master
        ));
    }

    public function actionChangePwd() {
        $this->checkLogin();
        $adminId = Bee::app()->userid();
        if ($this->isPost()) {
            $master = $this->model->getMasterById($adminId);
            $oldpwd = $this->getPost("currentPwd");
            $newPwd = $this->getPost("newPwd");
            $newPwd2 = $this->getPost("newPwd2");
            if (md5($oldpwd) != $master['password']) {
                alert('原密码输入错误', 'master/changePwd');
            }
            if ($newPwd == "" || $newPwd2 == "") {
                alert('新密码不能为空', 'master/changePwd');
            }
            if ($newPwd != $newPwd2) {
                alert('两次输入的新密码不一致', 'master/changePwd');
            }
            $adminId = Bee::app()->userid();
            $data = array(
                'password' => md5($newPwd),
                'update_master_id'=> $adminId,
                'update_time' => time(),
            );
            $rows = $this->model->update($data,"`id`=".$adminId);
            if($rows>0){
                alert('密码修改成功','master/changePwd');
            }else{
                alert('密码修改失败','master/changePwd');
            }
        }
        $this->view('master/changePwd', array(),'main');
    }

    /**
     * 删除用户
     */
    public function actionDel() {
        $id = $this->getGet("id");
        if($id){
            $rows = $this->model->delMaster($id);
            if($rows){
                alert('删除成功','master/index');
            }else{
                alert('删除失败','master/index');
            }
        }
        $this->forward('master/index');
    }
}

?>