<?php
class SiteController extends Controller{
    public function actionImg(){
        $m = new IdentifyCode();
        $m->generateImg(); 
    }
}
?>