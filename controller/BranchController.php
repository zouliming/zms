<?php

class BranchController extends Controller {

        public $layout = 'main';
        public $model;
        public $pageTitle = "这就是标题";

        function __construct() {
                $this->model = Model::mo('Branch');
        }

        public function actionIndex(){
                $model = Model::mo('Branch');
                $data = $model->getAll('*');
                $this->view('branch/index',array(
                        'items'=>$data
                ));
        }
        public function _updateData(){
                $postData = $this->getPost();
                $data = array(
                        'project' => $postData['project'],
                        'branch' => $postData['branch'],
                        'owner' => $postData['owner'],
                        'from_trunk_version' => $postData['from_trunk_version'],
                        'is_online' => $postData['is_online'],
                        'remark' => $postData['remark'],
                );
                $aq = $this->model->loadValue($data)->save();
                if ($aq) {
                        $this->forward('branch/branchIndex');
                }
        }
        /**
         * 权限管理首页
         */
        public function actionAddBranch() {
                if ($this->isPost()) {
                        $this->_updateData();
                }
                $this->view('branch/addBranch', array(
                ));
        }
        public function actionUpdate(){
                $id = $this->getGet('id');
                $this->model->find($id);
                if ($this->isPost()) {
                        $this->_updateData();
                }
                $this->view('branch/update',array(
                        'model'=>  $this->model
                ));
        }
        public function actionView(){
                $id = $this->getGet('id');
                $this->model->find($id);
                $this->view('branch/view',array(
                        'model'=>  $this->model
                ));
        }
}

?>