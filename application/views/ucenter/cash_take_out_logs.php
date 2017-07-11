<!--<div class="btn-toolbar">
    <button class="btn">Export</button>
    <div class="btn-group">
    </div>
</div>-->
<script src="<?php echo base_url('ucenter_theme/lib/My97DatePicker/WdatePicker.js?v=1'); ?>"></script>
<div class="search-well">
    <form class="form-inline" method="GET">
        <input class="Wdate span2 time_input" type="text" name="start" value="<?php echo $start_time; ?>" onClick="WdatePicker({dateFmt: 'yyyy-MM-dd', lang: '<?php echo $curLanguage; ?>'})" class="input-medium search-query" placeholder="<?php echo lang('start_date') ?>">
        -
        <input class="Wdate span2 time_input" type="text" name="end" value="<?php echo $end_time; ?>" onClick="WdatePicker({dateFmt: 'yyyy-MM-dd', lang: '<?php echo $curLanguage; ?>'})" class="input-medium search-query" placeholder="<?php echo lang('end_date') ?>">

        <button class="btn" type="submit"><i class="icon-search"></i> <?php echo lang('search') ?></button>
    </form>
</div>

<div class="well">
    <table class="table">
        <thead>
            <tr>
                <th><?php echo lang('time'); ?></th>
                <th><?php echo lang('money'); ?></th>
                <th><?php echo lang('withdrawal_fee_'); ?></th>
                <th><?php echo lang('withdrawal_actual_'); ?></th>
                <th><?php echo lang('take_out_cash_type'); ?></th>
				<th><?php echo lang('bank_card_number').'/'.lang('withdrawal_alipay_'); ?></th>
				<th><?php echo lang('bank_name'); ?></th>
                <th><?php echo lang('card_holder_name').'<br>/'.lang('alipay_actual_name');; ?></th>
                <th><?php echo lang('status'); ?></th>
            </tr>
        </thead>
       <tbody>
            <?php if (!empty($list)) { ?>
                <?php foreach ($list as $row) { ?>
                    <tr>
                        <td><?php echo date('Y-m-d',strtotime($row['create_time'])) ?></td>
                        <td>$<?php echo $row['amount'] ?></td>
                        <td>$<?php echo $row['handle_fee'] ?></td>
                        <td>$<?php echo $row['actual_amount'] ?></td>
                        <td><?php echo lang(config_item('take_out_type')[$row['take_out_type']]) ?></td>
						<td><?php echo $row['card_number'] ?></td>
						<td><?php echo $row['account_bank'] == 0 ? $row['account_bank'] : "".'<br>'.$row['subbranch_bank'] ? $row['subbranch_bank']: '' ?></td>
                        <td><?php echo $row['take_out_type'] != 4 ? $row['account_name'] : ''?></td>
                        <td><?php if($row['status'] == 1){ 
                                        echo '<strong class="text-success">'.lang('tps_status_1').'</strong>';
                                    }else if($row['status'] == 0) {
                                        echo '<strong class="text-error">'.lang('tps_status_0').'</strong>'; 
                                    }
                                    else if($row['status'] == 2){ 
                                        echo '<strong class="text-warning">'.lang('tps_status_2').'</strong>';
                                    }else if($row['status'] == 4){
                                        echo '<strong class="text-info">'.lang('tps_status_4').'</strong>';
                                    }else if($row['status'] == 3){ 
                                        $check_txt = $row['check_info'] != "收件人的帐户已锁定或无效" ? $row['check_info'] : "This email did not go to your Paypal registered Paypal account, or your Paypal account not login authentication Paypal website./님의 이 이메일 주소는 아직 Paypal계정으로 등록되여 있지 않았거나 Paypal사이트에서 로그인 인증을 마치지 않았습니다.";
                                        echo '<a href="##" rel="tooltip" data-original-title="'.$check_txt.'"><i class="icon-question-sign"></i></a><strong class="text-warning">'.lang('reject').'</strong>'.'<br>'.lang('reapply');
                                    }?>
                        </td>
                        <td> <?php if ($row['status'] == 0 || $row['status'] == 3){?>
                                <button class="btn btn-link process" value="<?php echo $row['id']?>"><?php echo lang('cancel_withdrawal')?></button>
                            <?php } ?>
                        </td>
                    </tr>
                <?php } ?>
            <?php } else { ?>
                <tr>
                   <th colspan="9" style="text-align: center;" class="text-success"> <?php echo lang('no_item') ?></th>
                </tr>
            <?php } ?>
        </tbody>
    </table>
    <style>
        .modal{
            width:250px;
            left:60%;
        }
    </style>
    <script>
        $(function(){
            $('.process').click(function(){
                var id = $(this).val();
                $("#confirm_sponsor").modal();
                $("#confirm_message").html('<?php echo lang('sure')?>');
                pre_withdrawal(id);
            });
        });
        function pre_withdrawal(id){
            document.getElementById('confirm_ok').onclick = function(){
                confirm_cancel_withdrawal(id);
                $("#confirm_sponsor").modal('hide');
            }
            document.getElementById('confirm_cancel').onclick = function(){
                $("#confirm_sponsor").modal('hide');
            }
        }

        function confirm_cancel_withdrawal(id){
            $.post("/ucenter/cash_take_out_logs/cancel", {id:id}, function (data) {
                if(data.success){
                    location.reload();
                }else{
                    layer.msg(data.msg);
                    location.reload();
                }
            },'json');
        }
    </script>
</div>
<?php
echo $pager;
