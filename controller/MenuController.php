<?php

/**
 * 菜单controller
 * @author bric.shi
 * @create 2013.04.18
 */
class MenuController extends Controller {

        public $layout = 'main';
        public $model = "";

        function __construct() {
                $this->model = Model::mo('Menu');
        }

        /**
         * 菜单管理首页
         */
        public function actionIndex() {
                $this->view('menu/index', array(
                        'items' => $this->getMenu()
                ));
        }

        /**
         * 给角色分配菜单 
         */
        public function actionRole() {
                $id = $this->getGet("id");
                if ($id == "") {
                        $this->forward('menu/index');
                } else {
                        $allRoles = Model::mo('role')->getAllRoles();
                        $menuRoles = $this->model->getMenuRole($id);
                        $targetRoles = array();
                        foreach ($allRoles as $k => $v) {
                                if (in_array($k, $menuRoles)) {
                                        $targetRoles[$k] = $v;
                                        unset($allRoles[$k]);
                                }
                        }
                        $this->view('menu/role', array(
                                'srcRoles' => $allRoles,
                                'menuRoles' => $targetRoles,
                                'menuId' => $id
                        ));
                }
        }

        /**
         * 给菜单更换角色
         */
        public function actionChangeRole() {
                $menuId = $this->getPost('menuId');
                $newRole = trim($this->getPost('newRole'), ',');
                if ($menuId && $newRole) {
                        $menuRelationModel = Model::mo('MenuRelationRole');
                        //先删除之前的Role
                        $menuRelationModel->delete("menu_id=" . $menuId);
                        //将新的关系添加到表中
                        $data = array();
                        foreach ($newRole as $i) {
                                $data[] = array(
                                        'menu_id' => $menuId,
                                        'role_id' => $i
                                );
                        }
                        $menuRelationModel->insertMany($data);
                }
                $this->forward('master/index');
        }

        /**
         * 菜单添加
         */
        public function actionAdd() {
                if ($this->isPost()) {
                        $data['name'] = $this->getPost("menuName");
                        $data['url'] = $this->getPost("url");
                        $data['parent_id'] = intval($this->getPost("pid"));

                        if ($data['url'] == "") {
                                $data['url'] == "#";
                        }
                        $this->model->addMenu($data);
                        alert("添加成功", "menu/index");
                }

                //获得所有父级权限
                $parents = $this->model->getParentMenu();
                $this->view('menu/add', array(
                        "parents" => array(0 => '作为父级') + $parents
                ));
        }

        /**
         * 菜单更新
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
                        $data['url'] = $this->getPost("url");
                        $data['parent_id'] = intval($this->getPost("pid"));

                        if ($data['url'] == "") {
                                $data['url'] == "#";
                        }

                        $this->model->updateMenu($data,"id=" . $id);
                        alert("修改成功", "menu/index");
                }

                $menu = $this->model->getMenuById($id);

                //获得所有父级权限
                $parents = $this->model->getMenuList("parent_id=0 And id !=" . $id);

                $this->view('menu/update', array(
                        "menu" => $menu,
                        "parents" => $parents
                ));
        }

        /**
         * 删除菜单
         */
        public function actionDel() {
                $id = $this->getGet("id");
                $this->model->delMenu($id);
                $this->forward('menu/index');
        }

        /**
         * 创建菜单
         */
        static function getMenu() {
                $menuModel = Model::mo('Menu');
                $tmpitems = $menuModel->getMenuList();
                $items = array();
                //按级别重组数组
                foreach ($tmpitems as $tmp) {
                        if ($tmp['parent_id'] == 0) {
                                $items[$tmp['id']]['info'] = $tmp;
                        } else {
                                $items[$tmp['parent_id']]['sub'][] = $tmp;
                        }
                }
                return $items;
        }

}

?>