<?php echo "<?php\n"; ?>

class <?=$className?>Model extends Model{
    public $tableName = "<?=$table?>";
    public $_pripary = "<?=$rules['primary']?>";
    protected function rules(){
        return array(
        <? foreach($rules['rule'] as $key => $rule){ ?>
        '<?=$key?>'=>array('<?=implode("','",$rule)?>'),
        <? } ?>
    );
    }
}
<?php echo "?>"; ?>
