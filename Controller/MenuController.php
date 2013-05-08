<?php
/**
 * 菜单controller
 * @author bric.shi
 * @create 2013.04.18
 */
class MenuController extends Controller{
    public $layout = 'main';
    public $model = "";

    function __construct() {
        $this->model = Model::mo('Menu');
    }
    
    /**
     * 菜单管理首页
     */
    public function actionIndex(){
        $this->view('menu/index',array(
            'items'=>  $this->getMenu()
        ));
    }
    
    /**
     *给角色分配菜单 
     */
    public function actionRole() {
        $id = $this->getGet("id");
        if ($id == "") {
            $id = $this->getPost("id");
        }
        if (intval($id) <= 0) {
            return false;
        }
        $roleModel = Model::mo('Role');
        $submit = $this->getPost("smt"); //是否表单提交

        if ($submit == 1) {
            $roles = $this->getPost("role");
            if (empty($roles)) {
                jsonExit(array("msg" => "至少选择一个角色", "status" => -2));
            }
            
            //获取设置的$roles，逐个插入到数据表menu_relation_role
            $data = array();
            //拼装批量添加数组
            foreach ($roles as $rid) {
                $data[] = array($id, $rid);
            }
            
            //删除就有列表
            $this->model->delRoleByMenu($id);
            //插入新数据
            $sql = $this->model->setMenus($data);
            jsonExit(array("msg" => "添加成功", "status" => 1));
        }
        
        $sitems = $roleModel->getRoleListByMenu($id);
        
        //提取已选择的roleid
        $sids = array();
        foreach($sitems as $i) {
            $sids[] = $i['id'];
        }

        if(!empty($sids)) {
            $items = $roleModel->getRoleList("id not in(".implode(",", $sids).")");
        } else {
            $items = $roleModel->getRoleList();
        }
        
        $this->view('menu/role', array(
            'menuid' => $id,
            'roles' => $items,
            'sroles' => $sitems
        ));
    }
    
    /**
     * 菜单添加
     */
    public function actionAdd(){
    	$submit = $this->getPost("smt");	//是否表单提交
    	
    	if($submit == 1) {
            $data['name'] = $this->getPost("name");
            $data['url'] = $this->getPost("url");
            $data['parent_id'] = intval($this->getPost("pid"));

            if($data['url'] == "") {
                    $data['url'] == "#";
            }

            $this->model->addMenu($data);

            alert("添加成功", "menu/add");
    	}
    	
    	//获得所有父级权限
    	$parents = $this->model->getMenuList("parent_id=0");
        $this->view('menu/add', array(
        	"parents"=>	$parents	
        ));
    }
    
    /**
     * 菜单更新
     */
    public function actionUpdate(){
    	$id = $this->getGet("id");
    	if($id == "") {
    		$id = $this->getPost("id");
    	}
    	if(intval($id) <= 0) {
    		return false;
    	}
    	
    	$submit = $this->getPost("smt");
    	
    	if($submit == 1) {
            $data['name'] = $this->getPost("name");
            $data['url'] = $this->getPost("url");
            $data['parent_id'] = intval($this->getPost("pid"));

            if($data['url'] == "") {
                    $data['url'] == "#";
            }

            $this->model->updateMenu("id=".$id, $data);
            alert("修改成功", "menu/update&id=".$id);
    	}
    	
    	$menu = $this->model->getMenuById($id);
    	
    	//获得所有父级权限
    	$parents = $this->model->getMenuList("parent_id=0 And id !=".$id);
    	
        $this->view('menu/update', array(
            "menu"=>$menu,
            "parents"=>$parents
        ));
    }
    
    /**
     * 删除菜单
     */
    public function actionDel(){
    	$id = $this->getGet("id");
    	if(intval($id) <= 0) {
    		return false;
    	}
    	$this->model->delMenu($id);
    	
    	jsonExit(array("msg"=>"删除成功", "status"=>1));
    }
    
    /**
     * 创建菜单
     */
    static function getMenu() {
    	$menuModel = Model::mo('Menu');
    	$tmpitems = $menuModel->getMenuList();
    	$items = array();
    	//按级别重组数组
    	foreach($tmpitems as $tmp) {
            if($tmp['parent_id'] == 0) {
                $items[$tmp['id']]['info'] = $tmp;
            } else {
                $items[$tmp['parent_id']]['sub'][] = $tmp;
            }
    	}
    	return $items;
    }
}
?>