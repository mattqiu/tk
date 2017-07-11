<div>

    <form id="express_form" action="<?php echo base_url('admin/logistics_manage') ?>" method="post">

        <select name="express_name" class="express_name" autocomplete="off">
            <option value=""><?php echo '请选择快递名称'; ?></option>
            <?php foreach($map as $k=>$v){ ?>
                <option value="<?php echo $k ?>" <?php echo isset($searchData['express_name']) && $searchData['express_name']==$k ? 'selected' : '' ?> ><?php echo $v; ?></option>
            <?php } ?>
        </select

        <br>
        <br>
        <br>

        <input type="text" value="<?php echo isset($searchData['express_num']) ? $searchData['express_num'] : ''; ?>" name="express_num" class="span3" placeholder="请输入快递单号">

        <br>
        <br>

        <button type="submit" class="btn submit_btn">查找</button>

    </form>


        <?php if(isset($data)){
            var_dump($data);
        } ?>


<script>
    $('.submit_btn').click(function(){
//        $.ajax({
//            type: "POST",
//            url: "/admin/logistics_manage/get_express_info",
//            data: $('#express_form').serialize(),
//            dataType: "json",
//            success: function (res) {
//                if(res.success==1){
//                }
//            }
//        });
    });

</script>


</div>