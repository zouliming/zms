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
            foreach($allActions as $k=>$v){
                if(in_array($v['id'], $masterActions) || in_array($v['parent_id'], $masterActions)){
                    $allActions[$k]['mark'] = 1;
                }else{
                    $allActions[$k]['mark'] = 0;
                }
            }
            $this->view('role/assign', array(
                'allActions' => $this->formatAction($allActions),
                'roleId' => $id
            ));
        }
    }
    public function actionChangeAssign(){
        if($this->isPost()){
            $data = $this->getPost();
            Model::mo('RoleRelationAction')->changeAction($data['roleId'],$data['actions']);
        }
        $this->forward('role/index');
    }
    private function formatAction($actions){
        $r = array();
        foreach($actions as $k=>$v){
            if($v['parent_id']==0 || !array_key_exists($v['parent_id'], $r)){
                $r[$v['id']] = array(
                    'name'=>$v['name'],
                    'mark'=>$v['mark'],
                    'children'=>array()
                );
            }else{
                $r[$v['parent_id']]['children'][] = array(
                    'id'=>$v['id'],
                    'name'=>$v['name'],
                    'mark'=>$v['mark']
                );
            }
        }
        return $r;
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