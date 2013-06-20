<?php

/**
 * 操作权限controller
 * @author bric.shi
 * @create 2013.04.17
 */
class ActionController extends Controller {

    public $layout = 'main';
    var $model = "";

    function __construct() {
        $this->model = Model::mo('Action');
    }

    /**
     * 权限管理首页
     */
    public function actionIndex() {
        $tmpitems = $this->model->getActionList();
        $items = array();
        //按级别重组数组
        foreach ($tmpitems as $tmp) {
            if ($tmp['parent_id'] == 0) {
                $items[$tmp['id']]['info'] = $tmp;
            } else {
                $items[$tmp['parent_id']]['sub'][] = $tmp;
            }
        }
        $this->view('action/index', array(
            'items' => $items
        ));
    }

    /**
     * 权限添加
     */
    public function actionAdd() {

        $submit = $this->getPost("smt"); //是否表单提交

        if ($submit == 1) {
            $data['name'] = $this->getPost("name");
            $data['info'] = $this->getPost("info");
            $data['parent_id'] = intval($this->getPost("pid"));
            $data['update_master_id'] = $_SESSION['user']['id'];
            $data['update_master_name'] = $_SESSION['user']['name'];
            $data['update_time'] = time();

            $this->model->addAction($data);

            alert("添加成功", "action/add");
        }

        //获得所有父级权限
        $parents = $this->model->getActionList("parent_id=0");
        $this->view('action/add', array(
            "parents" => $parents
        ));
    }

    /**
     * 权限更新
     */
    public function actionUpdate() {
        $id = $this->getGet("id");
        if ($id == "") {
            $id = $this->getPost("id");
        }
        if (intval($id) <= 0) {
            return false;
        }

        $submit = $this->getPost("smt");

        if ($submit == 1) {
            $data['name'] = $this->getPost("name");
            $data['info'] = $this->getPost("info");
            $data['parent_id'] = intval($this->getPost("pid"));
            $data['update_master_id'] = $_SESSION['user']['id'];
            $data['update_master_name'] = $_SESSION['user']['name'];
            $data['update_time'] = time();

            $this->model->updateAction("id=" . $id, $data);
            alert("修改成功", "action/update&id=" . $id);
        }

        $action = $this->model->getActionById($id);

        //获得所有父级权限
        $parents = $this->model->getActionList("parent_id=0 And id !=" . $id);

        $this->view('action/update', array(
            "action" => $action,
            "parents" => $parents
        ));
    }

    /**
     * 删除权限
     */
    public function actionDel() {
        $id = $this->getGet("id");
        if (intval($id) <= 0) {
            return false;
        }
        $this->model->delAction($id);

        jsonExit(array("msg" => "删除成功", "status" => 1));
    }

}

?>