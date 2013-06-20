<ul class="breadcrumb">
    <li><a href="#">商品管理</a> <span class="divider">/</span></li>
    <li class="active">商品列表</li>
</ul>
<div class="main_prompt">
    温馨提示：此版块为商品信息列表，可进行相关的条件查询
</div>
<div class="main_addbtn">
    <dl>
        <a class="all_btn" href="javascript:;" onclick="Vendor.goods.showadd()">
            添加商品信息
        </a>
    </dl>
    <dl>
        <a class="all_btn" href="javascript:;" onclick="Vendor.goods.ImportGoods()">
            批量导入商品资料
        </a>
    </dl>
    <dl>
        <a class="all_btn" href="javascript:;" onclick="Vendor.goods.ImportPic()">
            批量导入商品图片
        </a>
    </dl>
</div>
<div class="condition_area">
    <dl>
        <dd class="w100">
            <select id="brand" class="autow">
                <option value="brandName">
                    品牌名称
                </option>
                <option value="brandID" selected="">
                    品牌ID
                </option>
            </select>
        </dd>
        <dd class="w177">
            <input type="text" class="autow" />
        </dd>
        <dd class="w100">
            <select id="goods" class="autow">
                <option value="goodsName">
                    商品名称
                </option>
                <option value="goodsID" selected="">
                    商品ID
                </option>
            </select>
        </dd>
        <dd class="w177">
            <input type="text" class="autow"/>
        </dd>
    </dl>
    <dl>
        <dd class="w100 text_r">
            条形码：
        </dd>
        <dd class="w177">
            <input type="text" class="autow" />
        </dd>
        <dd class="w100 text_r">
            商品类目：
        </dd>
        <dd class="w177">
            <input type="text" class="autow" />
        </dd>
        <dd class="w100 text_r">
            状态：
        </dd>
        <dd class="w177">
            <select name="" id="" class="autow">
                <option value="-1" selected="">
                    请选择…
                </option>
                <option value="1">
                    待审核
                </option>
                <option value="2">
                    通过
                </option>
                <option value="3">
                    未通过
                </option>
            </select>
        </dd>
    </dl>
    <dl>
        <dd class="w100 text_r">
            添加时间：
        </dd>
        <dd class="w177">
            <input type="text" class="autow" />
        </dd>
        <dd class="w30">
            至
        </dd>
        <dd class="w200">
            <input type="text" class="autow"/>
        </dd>
        <dd class="w80">
            <input type="button" class="btn" value="查询"/>
        </dd>
        <dd class="w80">
            <input type="button" class="btn" value="刷新"/>
        </dd>
    </dl>
</div>
<div class="data-list">
    <table id="table1" >
        <thead>
            <tr>
                <th>全选</th>
                <th>品牌名称</th>
                <th>品牌ID</th>
                <th>商品名称</th>
                <th>商品ID</th>
                <th>货号</th>
                <th>类目</th>
                <th>添加时间</th>
                <th>修改时间</th>
                <th>状态</th>
                <th>操作</th>
            </tr> 
        </thead>
        <tbody>
            <tr>
                <td><input type="checkbox" value="114"></td>
                <td>卓丹</td>
                <td>9</td>
                <td>商品名称</td>
                <td>114</td>
                <td>69435704013</td>
                <td>男款凉鞋</td>
                <td>2013-03-26</td>
                <td>2013-03-26</td>
                <td>待审核</td>
                <td>更多</td>
            </tr>
            <tr>
                <td><input type="checkbox" value=""></td>
                <td>卓丹</td>
                <td>8</td>
                <td>商品名称</td>
                <td>114</td>
                <td>69435704013</td>
                <td>男款凉鞋</td>
                <td>2013-03-26</td>
                <td>2013-03-26</td>
                <td>待审核</td>
                <td>更多</td>
            </tr>
        </tbody>
    </table>
    <div class="pagination pagination-right">
        <ul>
            <li><a href="#">Prev</a></li>
            <li><a href="#">1</a></li>
            <li><a href="#">2</a></li>
            <li><a href="#">3</a></li>
            <li><a href="#">4</a></li>
            <li><a href="#">5</a></li>
            <li><a href="#">Next</a></li>
        </ul>
    </div>
</div>
<script type="text/javascript">
    $('#table1').tablecloth();
</script>