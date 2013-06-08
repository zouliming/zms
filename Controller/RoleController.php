<?php

/**
 * 角色controller
 * @author bric.shi
 * @create 2013.04.17
 */
class RoleController extends Controller {

    public $layout = 'main';
    public $model = "";

    function __construct() {
        $this->model = Model::mo('Role');
    }

    /**
     * 角色首页
     */
    public function actionIndex() {
        $items = $this->model->getRoleList();

        $this->view('role/index', array(
            'items' => $items
        ));
    }

    public function actionAssign(){
        $id = $this->getGet('id');
        if ($id == "") {
            $this->forward('role/index');
        }else{
            $allActions = Model::mo('action')->getAllActions();
            $masterActions = Model::mo('RoleRelationAction')->getActionsByRole($id);
            $targetActions = array();
            foreach($allActions as $k=>$v){
                if(in_array($k, $masterActions)){
                    $targetActions[$k] = $v;
                    unset($allActions[$k]);
                }
            }
            $this->view('role/assign', array(
                'srcActions' => $allActions,
                'masterActions' => $targetActions,
                'roleId' => $id
            ));
        }
    }
    /**
     * 角色分配权限
     */
    public function actionPriv() {
        $id = $this->getGet("id");
        if ($id == "") {
            $id = $this->getPost("id");
        }
        if (intval($id) <= 0) {
            return false;
        }
        $actionModel = Model::mo('Action');
        $submit = $this->getPost("smt"); //是否表单提交

        if ($submit == 1) {
            $privs = $this->getPost("priv");
            if (empty($privs)) {
                jsonExit(array("msg" => "至少选择一个权限", "status" => -2));
            }

            //获取设置的$privs，逐个插入到数据表role_relation_action
            $data = array();
            //拼装批量添加数组
            //'role_id','role_name','action_id','action_name','update_master_id','update_master_name','update_time'
            $uptime = time();

            foreach ($privs as $pr) {
                $priv = explode(":", $pr);

                $data[] = array($id, $this->model->getRoleNameById($id), $priv[0], $priv[1], $_SESSION['user']['id'], $_SESSION['user']['name'], $uptime);
            }
            //删除就有列表
            $this->model->delActionByRole($id);
            //插入新数据
            $sql = $this->model->setActions($data);

            jsonExit(array("msg" => "添加成功", "status" => 1));
        }

        $items = $this->model->getRoleList();

        $tmpitems = $actionModel->getActionList();
        $tmpselected = $actionModel->getActionByRole($id);
        $actions = array();
        $selected = array();
        //按级别重组数组共js调用
        foreach ($tmpitems as $tmp) {
            if ($tmp['parent_id'] == 0) {
                $actions[$tmp['id']]['name'] = $tmp['name'];
            } else {
                $actions[$tmp['parent_id']]['sublist'][$tmp['id']] = $tmp['name'];
            }
        }

        foreach ($tmpselected as $tmp) {
            if ($tmp['action_id'] == 0) {
                $selected[$tmp['action_id']]['name'] = $tmp['action_name'];
            } else {
                $selected[$tmp['action_id']]['sublist'][$tmp['action_id']] = $tmp['action_name'];
            }
        }

        $this->view('role/priv', array(
            'roleid' => $id,
            'actions' => json_encode($actions),
            'selected' => json_encode($selected)
        ));
    }

    /**
     * 角色添加
     */
    public function actionAdd() {
        $model = new RoleModel();
        if ($this->isPost()) {
            $data['name'] = $this->getPost("name");
            $data['info'] = $this->getPost("info");
            $data['update_master_id'] = $_SESSION['user']['id'];
            $data['update_master_name'] = $_SESSION['user']['name'];
            $data['update_time'] = time();
            $model->loadValue($data);
            if($model->save()){
                $this->forward('role/index');
            }
        }
        $this->view('role/add',array(
            'model'=>$model
        ));
    }

    /**
     * 角色更新
     */
    public function actionUpdate() {
        $id = $this->getGet("id");
        $roleModel = $this->model->find($id);
        if($roleModel){
            if($this->isPost()){
                var_dump($_SESSION);
                $data = array(
                    'name' => $this->getPost("name"),
                    'info' => $this->getPost("info"),
                    'update_master_id' => $_SESSION['user']['id'],
                    'update_master_name' => $_SESSION['user']['name'],
                    'update_time' => time()
                );
                $aq = $roleModel->loadValue($data)->save();
                if($aq){
                    $this->forward('role/index');
                }
            }
            $this->view('role/update', array(
                "model" => $roleModel
            ));
        }else{
            $this->forward('role/index');
        }
    }

    /**
     * 删除角色
     */
    public function actionDelete() {
        $id = $this->getGet("id");
        if($id){
            $this->model->delRole($id);
        }
        $this->forward('role/index');
    }

}

?>