<?php
class GiiController extends Controller{
    public $layout = 'none';
    public $dirPath = "";//生成的文件路径
    public function __construct() {
        parent::__construct();
        
        $this->dirPath = LIB_DIRECTORY.SEP.'gii';
    }
    public function actionIndex(){
        $this->view('gii/index',array(),'main');
    }
    public function getModelPath(){
        return $this->dirPath.SEP.'Model';
    }
    /**
     * 返回数据表结构信息
     * @param String $table
     * @return Array 二维数组
     */
    public function getTableStructure($table){
        $mo = MODEL::mo($table);
        $structure = $mo->selectAll('desc `'.$table.'`');
        return $structure;
    }
    /**
     * 根据表结构生成规则
     * @param Array $structure
     * @return Array
     */
    public function getRules($structure){
        $primaryField = "";
        $rule = array();
        foreach ($structure as $row){
            $field = $row['Field'];
            $type = $row['Type'];
            if($row['Key']=='PRI'){
                $primaryField = $field;
            }
            //int
            if(strpos($type,"int")===0){
                $rule[$field][] = 'int';
            }
            //float
            if(strpos($type,"float")===0){
                $rule[$field][] = "float";
            }
            //char
            if(strpos($type,"char")===0){
                $rule[$field][] = "string";
            }
            //varchar
            if(strpos($type,"varchar")===0){
                $rule[$field][] = "string";
            }
            //text
            if(strpos($type,"text")===0){
                $rule[$field][] = "text";
            }
            //date
            if($type=="date"){
                $rule[$field][] = "date";
            }
            //datetime
            if($type=="datetime"){
                $rule[$field][] = "datetime";
            }
        }
        return array(
            'primary'=>$primaryField,
            'rule'=>$rule
        );
    }
    public function generateModel($table){
        $className = str_replace(" ","",ucwords(str_replace("_"," ",$table)));
        $structure = $this->getTableStructure($table);
        $rules = $this->getRules($structure);
        $content = $this->view('gii/template/model',array(
            'className'=>  $className,
            'table'=>$table,
            'rules'=>$rules
        ),'none',true);
        $modelPath = $this->getModelPath();
        if(!file_exists($modelPath)){
            mkdir($modelPath);
        }
        $fileName = ucfirst($table).'.php';
        file_put_contents($modelPath.DIRECTORY_SEPARATOR.$fileName, $content);
    }
    public function actionGenerateModel(){
        if(Bee::get('method')=='post'){
            $table = $this->getPost('inputTable');
            $this->generateModel($table);
        }else{
            $this->view('gii/generateModel',array(),'main');
        }
        
    }
}
?>