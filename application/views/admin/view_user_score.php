<?php /*?>
<div class="search-well">
	<form class="form-inline" method="GET">
		<input name="score_month" type="text" name="uid" placeholder="<?php echo lang('score_month'); ?>(格式: 201707)" />
		
		<button class="btn" type="submit"><i class="icon-search"></i><?php echo lang('search'); ?></button>
	</form>
</div> 
<?php */?>

<div class="well">
	<table class="table">
                    <form id="search_form" name="search_form" class="form-inline" method="GET">
                        <input style="float:left;margin:0px 5px 0px 0px;" type="text" name="uid" value="<?php echo $searchData['uid'];?>" class="input-medium search-query" placeholder="<?php echo lang('pls_t_uid')?>">
                        <select id="sear_year" name="year" style="width:120px;float:left;">
                            <option value=""><?php echo lang("pls_sel_date"); ?></option>
                          <?php for($year = 2015; $year <= 2050;$year++){ if($year<= date("Y")){ ?>
                                  <option value="<?php echo $year; ?>" <?php if($searchData['year']==$year){ ?> selected="selected" <?php } ?>><?php echo $year; ?></option>
                          <?php } } ?>
                        </select>
                        <div style="float:left;margin:5px 5px 0px 5px;">年</div>
                        <select id="sear_month" name="month" style="width:120px;float:left;">
                            <option value=""><?php echo lang("pls_sel_date"); ?></option>
                          <?php for($month = 1; $month <= 12;$month++){ ?>
                                  <option value="<?php echo $month; ?>" <?php if($searchData['month']==$month){ ?> selected="selected" <?php } ?>><?php echo $month; ?></option>
                          <?php  } ?>
                        </select>
                        <div style="float:left;margin:5px 5px 0px 5px;">月</div>
                        <button class="btn" id="submit_button" type="button"><i class="icon-search"></i> <?php echo lang('search') ?></button>
                        <span id="error_msg" style="color: #ff0000;margin-left: 20px;"><?php echo isset($err) && $err==1 ? lang('id_not_null') :"";?></span>
                </form>
		<thead>
			<tr>
				<th colspan="2"><?php echo lang('current_store_sale_total_amount')?>: <?php echo (isset($total) ? $total :0) / 100?>(美元)</th>
			</tr>
			<tr>
				<th><?php echo lang('score_month'); ?></th>
				<th><?php echo lang('store_sale_amount'); ?></th>
			</tr>
		</thead>
		<tbody>
		 <?php if (isset($list) && $list){ ?>
            <?php foreach ($list as $item) { ?>
                <tr>
                    <td><?php echo $item['year_month'] ?></td>
                    <td><?php echo $item['sale_amount'] / 100 ?>(美元)</td>
                </tr>
            <?php } ?>
        <?php }else{ ?>
            <tr>
                <th colspan="2" style="text-align: center;" class="text-success"> <?php echo lang('no_item') ?></th>
            </tr>
        <?php } ?>
		</tbody>
	</table>
</div>
<?php echo isset($pager) ? $pager : "";?>
<script>
  $("#submit_button").click(function(){
        var idEmail = $("#search_form input[name='id']").val();
        $("#search_form").submit();
    })
</script>