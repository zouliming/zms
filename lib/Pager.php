<?php
/**
 * 分页类
 *
 * @author liming.zou@vipshop.com
 */
class Pager {
    /**
     * 每页显示多少数据
     */
    private $pageSize = 10;
    /**
     * 一共有多少条数据
     */
    private $dataCount;
    /**
     * 分页的链接
     * @var String
     */
    private $url;
    /*
     * 一共有多少页
     */
    private $pageCount;
    /*
     * 默认当前页
     */
    public $currentPage = 1;
    /*
     * 页码的参数名称
     */
    public static $parameter = "p";
    /*
     * 默认的分页展示模式
     */
    private $mode = 1;
    /**
     * 生成的分页Html
     * @var String
     */
    private $html;
    
    public function __construct($dataCount,$pageSize,$url,$mode) {
        $this->dataCount = $dataCount;
        $this->pageSize = $pageSize;
        if($this->getCurrentPage()!=""){
            $this->currentPage = $this->getCurrentPage();
        }
        $this->url = $url;
        $this->mode = $mode;
        $this->process();
    }
    /**
     * 返回页码参数
     */
    public static function getP(){
        return self::$parameter;
    }
    /**
     * 从参数里得到当前页码
     * @return type
     */
    public static function getCurrentPage(){
        return Bee::get('get',Pager::getP());
    }
    /**
     * 计算一共有多少页
     * @return int
     */
    public function getPageCount(){
        return ($this->pageCount = ceil($this->dataCount/$this->pageSize));
    }
    public function createUrl($prefix,$no){
        if(strpos($prefix, '?')){
            return $prefix."&".self::$parameter."=".$no;
        }else{
            return $prefix."?".self::$parameter."=".$no;
        }
    }
    /*
     * 得到首页的html
     */
    public function getFirstStr($txt=''){
        $d = $txt=="" ? "首页" : $txt;
        $activeStr = "";
        $href = $this->createUrl($this->url, 1);
        if($this->currentPage==1){
            $activeStr = "class='disabled'";
            $href = "javascript:;";
        }
        return "<li {$activeStr}><a href='{$href}'>{$d}</a></li>";
    }
    /*
     * 得到末页的Html
     */
    public function getLastStr($txt=''){
        $d = $txt=="" ? "末页" : $txt;
        $activeStr = "";
        $href = $this->createUrl($this->url, $this->pageCount);
        if($this->currentPage==$this->pageCount){
            $activeStr = "class='disabled'";
            $href = "javascript:;";
        }
        return "<li {$activeStr}><a href='{$href}'>{$d}</a></li>";
    }
    /*
     * 得到上一页的Html
     */
    public function getPrevStr($txt=''){
        $d = $txt=="" ? "上一页" : $txt;
        $activeStr = $href = "";
        if($this->currentPage<=1){
            $activeStr = "class='disabled'";
            $href = "javascript:;";
        }else{
            $href = $this->createUrl($this->url, $this->currentPage-1);
        }
        return "<li {$activeStr}><a href='{$href}'>{$d}</a></li>";
    }
    /*
     * 得到下一页的Html
     */
    public function getNextStr($txt=''){
        $d = $txt=="" ? "下一页" : $txt;
        $activeStr = $href = "";
        //过界了
        if($this->currentPage >= $this->pageCount){
            $activeStr = "class='disabled'";
            $href = "javascript:;";
        }else{
            $href = $this->createUrl($this->url, $this->currentPage+1);
        }
        return "<li {$activeStr}><a href='{$href}'>{$d}</a></li>";
    }
    /*
     * 生成自定义的Html
     */
    public function getStr($p,$txt,$active=false){
        $activeStr = $href = "";
        if($active){
            $activeStr = "class='active'";
            $href = "javascript:;";
        }else{
            $href = $this->createUrl($this->url, $p);
        }
        return "<li {$activeStr}><a href='{$href}'>{$txt}</a></li>";
    }
    public function getRedirectHtml(){
        return "<input type='text'>";
    }
    public function generateHtml(){
        if($this->mode==1){
            /**
             * 首页 上一页 5 下一页 末页 跳转到【】页
             */
            $arr[] = $this->getFirstStr();
            $arr[] = $this->getPrevStr();
            $arr[] = $this->getNextStr();
            $arr[] = $this->getLastStr();
            
        }elseif($this->mode==2){
            /**
             * Older  Newer
             */
        }elseif($this->mode==3){
            /**
             * 首页 上一页 1 2 3 4 5 6 7 下一页 末页
             */
        }elseif($this->mode==4){
            /**
             * 首页 1 2 3 4 5 6 7 末页
             * <div class="pagination">
             * <ul>
             * <li><a href="#">首页</a></li>
             * <li><a href="#">1</a></li>
             * <li><a href="#">2</a></li>
             * <li><a href="#">3</a></li>
             * <li><a href="#">4</a></li>
             * <li><a href="#">5</a></li>
             * <li><a href="#">6</a></li>
             * <li><a href="#">末页</a></li>
             * </ul>
             * </div>
             */
            $arr = array();
            $l = 3;
            $arr[] = $this->getFirstStr();
            if($this->currentPage>$l){
                $max = $l*2>$this->pageCount?$this->pageCount:$l*2;
                for($i=$this->currentPage-$l;$i<=$max;$i++){
                    $b = $i==  $this->currentPage ? true:false;
                    $arr[] = $this->getStr($i, $i,$b);
                }
            }elseif($this->currentPage<$this->pageCount-$l){
                $max = $l*2>$this->pageCount?$this->pageCount:$l*2;
                for($i=1;$i<=$max;$i++){
                    $b = $i==  $this->currentPage ? true:false;
                    $arr[] = $this->getStr($i, $i,$b);
                }
            }else{
                for($i=1;$i<=$this->pageCount;$i++){
                    $b = $i==  $this->currentPage ? true:false;
                    $arr[] = $this->getStr($i, $i,$b);
                }
            }
            $arr[] = $this->getLastStr();
            $s = '<div class="pagination"><ul>';
            foreach($arr as $u){
                $s.= $u;
            }
            $s.= '</ul></div>';
            return $s;
        }
    }
    public function process(){
        $this->getPageCount();
        $this->html = $this->generateHtml();
    }
    public function getHtml(){
        return $this->html;
    }
    
}

?>
