<?php
class GoodsController extends Controller{
    public $layout = 'main';
    public function actionIndex(){
        $this->forward('goods/list');
    }
    public function actionList(){
        $this->view('goods/list');
    }
    public function actionAdd(){
        $menuModel = Model::mo('Menu');
        $list = $menuModel->getMenuList();
        $this->view('goods/add',array(
            'list'=>$list
        ));
    }
}
?>