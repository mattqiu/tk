<style type="text/css">
	select,input[type="text"] { width: 120px; }
	.input-group, .address-inline, label { display:inline-block; }
	
	/** 树型结构 **/
	.node circle {
	  fill: #fff;
	  stroke: steelblue;
	  stroke-width: 1.5px;
	}

	.node {
	  font: 12px sans-serif;
	}

	.link {
	  fill: none;
	  stroke: #ccc;
	  stroke-width: 1.5px;
	}
	
	.well { overflow-y: auto; }
</style>

<ul class="nav nav-tabs">
	<?php foreach ($tab_map as $k => $v): ?>
		<li <?php if ($k == $fun) echo " class=\"active\""; ?>>
			<a href="<?php echo base_url($v['url']); ?>">
				<?php echo $v['desc']; ?>
			</a>
		</li>
	<?php endforeach; ?>
</ul>

<div class="search-well">
	<div class="input-group">
		<label for="sf_free_type">
			<span id="express_free"><?php echo lang('express_free');?>:</span>
		</label>
		<select name="express_arrive_type" id="express_arrive_type">
			<?php foreach($express_arrive_type as $key => $value) {?>
				<option value="<?php echo $key;?>"><?php echo $value;?></option>
			<?php }?>
		</select>
		<div class="address-inline" id="box_addr">
			<select class="select" id="box_country" name="country" disabled="disabled">
				<option value="0"><?php echo lang('checkout_addr_country'); ?></option>
			</select>
		</div>
		<input type="text" name="sf_free" id="sf_free" placeholder="<?php echo lang('please_input').lang('choose2');?>" />
		<button class="btn" type="button" id="add_free" rel="tooltip" data-original-title="<?php echo lang('invoice_save_sf_free');?>"><i class="icon-plus"></i><?php echo lang('invoice_save_sf_free');?></button>
		<a href="javascript:;" id="see_free"><?php echo lang('see_sf_free_num');?></a>
	</div>
</div>

<div class="well">
	
</div>
<?php $this->load->view("admin/invoice_address_js.php");?>
<script src="<?php echo base_url('themes/admin/javascripts/d3.v3.min.js'); ?>"></script>
<script type="text/javascript">
	// 地址显示
	$(function() {'use strict'; sfcountry();});
	
	$('#see_free').click(function() {
		layer.open({
		  type: 1,
		  title: '预览',
		  closeBtn: 1,
		  area: '880px',
		  skin: 'layui-layer-nobg', //没有背景色
		  shadeClose: true,
		  fixed: false,
		  scrollbar: false,
		  content: '<img src="<?php echo base_url('themes/admin/images/SF_express_free.png'); ?>" />'
		});
	});
	
	// 提交验证
	$('#add_free').click(function() {
		var freeType  = $('#express_arrive_type').val();
		var freeMoney = $('#sf_free').val();
		var priceEx   = /(^[1-9]\d*(\.\d{1,2})?$)|(^0(\.\d{1,2})?$)/;

		if (freeType == 0) {
			layer.msg('请选择到达类型');
			return false;
		} else if ($('#box_addr_lv2').val() == 0) {
			layer.msg('请选择省份/直辖市');
			return false;
		} else if (freeMoney == 0) {
			layer.msg('请输入运费');
			return false;
		} else if (!priceEx.test(freeMoney)) {
			layer.msg('运费格式有误');
			return false;
		}
		// 提交数据
		var url  = '/admin/invoice/add_free';
		var data = {
			freetype: freeType,
			freemoney: freeMoney,
			country: $('#box_country').val(),
			provice: $('#box_addr_lv2').val(),
			city: $('#box_addr_lv3').val(),
			area: $('#box_addr_lv4').val(),
			provice_name: $('#box_addr_lv2 option:selected').text(),
			city_name: $('#box_addr_lv3 option:selected').text(),
			area_name: $('#box_addr_lv4 option:selected').text()
		};
		
		$.ajax({
			url: url,
			data: data,
			type: 'POST',
			dataType: 'json',
			success: function(res) {
				layer.msg(res.msg);
				if (res.success) $('#sf_free').val('');
				window.location.reload();
			}
		});
	});
	
	var root   = JSON.parse('<?php echo $json;?>');
	var width  = 1200;
	var height = 580;

	//边界空白
	var padding = {left: 40, right:20, top: 5, bottom: 10 };

	var svg = d3.select("div.well")
			   .append("svg")
				 .attr("width", width + padding.left + padding.right)
				 .attr("height", height + padding.top + padding.bottom)
			   .append("g")
			   .attr("transform","translate("+ padding.left + "," + padding.top + ")");
	
	//树状图布局
	var tree = d3.layout.tree()
				.size([height, width]);

	//对角线生成器
	var diagonal = d3.svg.diagonal()
		.projection(function(d) { return [d.y, d.x]; });

  {	
	  //给第一个节点添加初始坐标x0和x1
	  root.x0 = height / 2;
	  root.y0 = 0;
	  //以第一个节点为起始节点，重绘
	  redraw(root);
  }
  
  //重绘函数
  function redraw(source){

	/*
	（1） 计算节点和连线的位置
	*/

	//应用布局，计算节点和连线
	var nodes = tree.nodes(root);
	var links = tree.links(nodes);

	//重新计算节点的y坐标
	nodes.forEach(function(d) { d.y = d.depth * 180; });

	/*
	（2） 节点的处理
	*/

	//获取节点的update部分
	var nodeUpdate = svg.selectAll(".node")
						.data(nodes, function(d){ return d.name; });

	//获取节点的enter部分
	var nodeEnter = nodeUpdate.enter();

	//获取节点的exit部分
	var nodeExit = nodeUpdate.exit();

	//1. 节点的 Enter 部分的处理办法
	var enterNodes = nodeEnter.append("g")
					.attr("class","node")
					.attr("transform", function(d) { return "translate(" + source.y0 + "," + source.x0 + ")"; })
					.on("click", function(d) { toggle(d); redraw(d); });

	enterNodes.append("circle")
	  .attr("r", 0)
	  .style("fill", function(d) { return d._children ? "lightsteelblue" : "#fff"; });

	enterNodes.append("text")
		.attr("x", function(d) { return d.children || d._children ? -14 : 14; })
		.attr("dy", ".35em")
		.attr("text-anchor", function(d) { return d.children || d._children ? "end" : "start"; })
		.text(function(d) { return d.name; })
		.style("fill-opacity", 0);


	//2. 节点的 Update 部分的处理办法
	var updateNodes = nodeUpdate.transition()
						.duration(500)
						.attr("transform", function(d) { return "translate(" + d.y + "," + d.x + ")"; });

	updateNodes.select("circle")
	  .attr("r", 8)
	  .style("fill", function(d) { return d._children ? "lightsteelblue" : "#fff"; });

	updateNodes.select("text")
	  .style("fill-opacity", 1);

	//3. 节点的 Exit 部分的处理办法
	var exitNodes = nodeExit.transition()
					  .duration(500)
					  .attr("transform", function(d) { return "translate(" + source.y + "," + source.x + ")"; })
					  .remove();

	exitNodes.select("circle")
	  .attr("r", 0);

	exitNodes.select("text")
	  .style("fill-opacity", 0);

	/*
	（3） 连线的处理
	*/

	//获取连线的update部分
	var linkUpdate = svg.selectAll(".link")
						.data(links, function(d){ return d.target.name; });

	//获取连线的enter部分
	var linkEnter = linkUpdate.enter();

	//获取连线的exit部分
	var linkExit = linkUpdate.exit();

	//1. 连线的 Enter 部分的处理办法
	linkEnter.insert("path",".node")
		  .attr("class", "link")
		  .attr("d", function(d) {
			  var o = {x: source.x0, y: source.y0};
			  return diagonal({source: o, target: o});
		  })
		  .transition()
		  .duration(500)
		  .attr("d", diagonal);

	//2. 连线的 Update 部分的处理办法
	linkUpdate.transition()
		.duration(500)
		.attr("d", diagonal);

	//3. 连线的 Exit 部分的处理办法
	linkExit.transition()
		  .duration(500)
		  .attr("d", function(d) {
			var o = {x: source.x, y: source.y};
			return diagonal({source: o, target: o});
		  })
		  .remove();


	/*
	（4） 将当前的节点坐标保存在变量x0、y0里，以备更新时使用
	*/
	nodes.forEach(function(d) {
	  d.x0 = d.x;
	  d.y0 = d.y;
	});

  }

  //切换开关，d 为被点击的节点
  function toggle(d){
	if(d.children){ //如果有子节点
	  d._children = d.children; //将该子节点保存到 _children
	  d.children = null;  //将子节点设置为null
	}else{  //如果没有子节点
	  d.children = d._children; //从 _children 取回原来的子节点 
	  d._children = null; //将 _children 设置为 null
	}
  }
</script>