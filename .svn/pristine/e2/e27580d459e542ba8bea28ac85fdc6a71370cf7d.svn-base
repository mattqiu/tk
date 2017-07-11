<div class="well" style="display:none;> 
	<form class="form-inline" method="POST">		
		<button class="btn" type="button" onclick="reset_pre_bonus()"><?php echo lang('pre_bonus_submit'); ?></button>
		<span style="color:red;"><?php echo lang('pre_bonus_submit_note'); ?></span>
	</form>
</div>

<?php if (isset($err_msg)): ?>
<div class="well">
	<p style="color: red;"><?php echo $err_msg; ?></p>
</div>
<?php endif; ?>

<div class="well">
	<table class="table">
		<thead>
			<tr>
				<th></th>
				<th><?php echo lang('member_id'); ?></th>
				<th><?php echo lang('pre_amount_bonus'); ?></th>
				<th><?php echo lang('generate_time'); ?></th>

			</tr>
		</thead>
		<tbody>
		<?php
			foreach ($list as $v)
			{
				echo "<tr>";
				echo "<td>";
				echo "<td>{$v['uid']}</td>";
				echo "<td>{$v['amount']}</td>";
				echo "<td>{$v['create_time']}</td>";	
				echo "<td>";
				echo "</td>";
				echo "</tr>";
			}
		?>
		</tbody>
	</table>
</div>


<?php
	if (isset($paginate))
	{
		echo $paginate;
	}
?>

<script src="<?php echo base_url('ucenter_theme/lib/My97DatePicker/WdatePicker.js?v=1'); ?>"></script>
<script src="<?php echo base_url('themes/admin/lib/thinkbox/jquery.thinkbox.js?v=1'); ?>"></script>
<script>

function errboxHtml(msg) {
	return "<div class=\"thinkbox-tips thinkbox-tips-error\">" + msg + "</div>";
}



function show_msg()
{
	
	$.ajax({
        type:'POST',
        url: '/admin/pre_month_team_bonus/get_pre_bonus_state',
        data: '',
        dataType: "json",
        success: function (data) 
        {           
          if(data.state!=3)
          {     
        	  setInterval(show_msg,1000);
          }	
          else
          {              
        	  window.location.href = '/admin/pre_month_team_bonus';
          }
        }
	 });      
	         
}




function reset_pre_bonus()
{

	layer.confirm("<?php echo lang('pre_bonus_submit'); ?>", {
		icon: 3,
		title: "<?php echo lang('pre_bonus_submit'); ?>",
		closeBtn: 2,
		btn: ['<?php echo lang('ok'); ?>', '<?php echo lang('admin_order_cancel'); ?>']
	},function(){       
		var index = layer.load(1, {
		  shade: [0.5,'#000000'] //0.1透明度的白色背景
		});
    	curEle = $(this);
    	curEle.attr("disabled", true);	
    	 $.ajax({
             type:'POST',
             url: '/admin/pre_month_team_bonus/reset_pre_bonus',
             data: '',
             dataType: "json",
             success: function (data) {
                 if (data.success) {                
                	 show_msg();
                 }           
                 curEle.attr("disabled", false);
             }
         });
	});
}



</script>