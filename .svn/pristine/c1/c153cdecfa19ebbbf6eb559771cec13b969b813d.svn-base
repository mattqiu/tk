<script language="javascript">
function Dsy()
{
	this.Items = {};
}
Dsy.prototype.add = function(id,iArray)
{
	this.Items[id] = iArray;
}
Dsy.prototype.Exists = function(id)
{
	if(typeof(this.Items[id]) == "undefined") 
		return false;
	return true;
}

function change(v)
{
	var str="0";
	for(i=0;i<v;i++)
	{
		str+=("_"+(document.getElementById(s[i]).selectedIndex-1));
	};
	var ss=document.getElementById(s[v]);
	with(ss)
	{
		length = 0;
		options[0]=new Option(opt0[v],opt0[v]);
		if(v && document.getElementById(s[v-1]).selectedIndex>0 || !v)
		{
			if(dsy.Exists(str))
			{
				ar = dsy.Items[str];
				for(i=0;i<ar.length;i++)
					options[length]=new Option(ar[i],ar[i]);
				if(v)options[1].selected = true;
			}
		}
		if(++v<s.length)
		{ 
			change(v);
		}
	}
}

var dsy = new Dsy();

dsy.add("0",["中国","美国","加拿大","俄罗斯","埃及","南非","希腊","荷兰","比利时","法国","西班牙","意大利","罗马尼亚","瑞士","奥地利","英国","丹麦","瑞典","挪威","波兰","秘鲁","墨西哥","古巴","阿根廷","巴西","智利","哥伦比亚","委内瑞拉","马来西亚","澳大利亚","印度尼西亚","菲律宾","新西兰","新加坡","泰国","日本","韩国","越南","土耳其","印度","巴基斯坦","阿富汗","斯里兰卡","缅甸","伊朗","摩洛哥","阿尔及利亚","突尼斯","利比亚","冈比亚","塞内加尔","毛里塔尼亚","圣马力诺","几内亚","科特迪瓦","布基拉法索","尼日尔","多哥","贝宁","毛里求斯","利比里亚","塞拉利昂","加纳","尼日利亚","乍得","中非","喀麦隆","佛得角","圣多美","普林西比","赤道几内亚","加蓬","刚果","扎伊尔","安哥拉","几内亚比绍","阿森松","塞舌尔","苏丹","卢旺达","埃塞俄比亚","索马里","吉布提","肯尼亚","坦桑尼亚","乌干达","布隆迪","莫桑比克","赞比亚","马达加斯加","留尼旺岛","津巴布韦","纳米比亚","马拉维","莱索托","博茨瓦纳","斯威士兰","科摩罗","圣赫勒拿","阿鲁巴岛","法罗群岛","格陵兰岛","匈牙利","南斯拉夫","德国","直布罗陀","葡萄牙","卢森堡","爱尔兰","冰岛","阿尔巴尼亚","马耳他","塞浦路斯","芬兰","保加利亚","梵蒂冈","福克兰群岛","伯利兹","危地马拉","萨尔瓦多","洪都拉斯","尼加拉瓜","哥斯达黎加","巴拿马","海地","玻利维亚","圭亚那","厄瓜多尔","法属圭亚那","巴拉圭","马提尼克","苏里南","乌拉圭","关岛","文莱","瑙鲁","汤加","所罗门群岛","瓦努阿图","斐济","科克群岛","纽埃岛","东萨摩亚","西萨摩亚","基里巴斯","图瓦卢","朝鲜","柬埔寨","老挝","孟加拉国","马尔代夫","黎巴嫩","约旦","叙利亚","伊拉克","科威特","沙特阿拉伯","阿曼","以色列","巴林","卡塔尔","不丹","蒙古","尼泊尔","威克岛","中途岛","夏威夷","维尔京群岛","波多黎各","巴哈马","圣卢西亚","牙买加","巴巴多斯","安圭拉岛","阿拉斯加","列支敦士登","科科斯岛","诺福克岛","圣诞岛"]);
dsy.add("0_0",["北京","上海","天津","重庆","广东","广西","贵州","海南","河北","河南","黑龙江","湖北","湖南","吉林","江苏","江西","辽宁","内蒙古","宁夏","青海","山东","山西","陕西","上海","四川","天津","西藏","新疆","云南","浙江","甘肃","安徽","福建"]);
dsy.add("0_1",["阿拉巴马","阿拉斯加","亚利桑那","阿肯色","加利福尼亚","科罗拉多","康涅狄格","德拉华","佛罗里达","乔治亚","夏威夷","爱达荷","伊利诺","印第安纳","依阿华","堪萨斯","肯塔基","路易斯安那","缅因","马里兰","麻萨诸塞","密执安","明尼苏达","密西西比","密苏里","蒙大拿","内布拉斯加","内华达","新罕布什尔","新泽西","新墨西哥","纽约","北卡罗来纳","北达科他","俄亥俄","俄克拉荷马","俄勒冈","宾夕法尼亚","罗德岛","南卡罗来纳","南达科他","田纳西","德克萨斯","犹他","佛蒙特","佛吉尼亚","华盛顿","西佛吉尼亚","威斯康辛","怀俄明"]);

dsy.add("0_0_0",["北京"]);
dsy.add("0_0_1",["上海"]);
dsy.add("0_0_2",["天津"]);
dsy.add("0_0_3",["重庆"]);

dsy.add("0_0_4",["潮州","东莞","佛山","广州","河源","惠州","江门","揭阳","茂名","梅州","清远","汕头","汕尾","韶关","深圳","阳江","云浮","湛江","肇庆","中山","珠海"]);

dsy.add("0_0_5",["百色","北海","崇左","防城港","桂林","贵港","河池","贺州","来宾","柳州","南宁","钦州","梧州","玉林"]);


dsy.add("0_0_6",["安顺","毕节","贵阳","六盘水","黔东南苗族侗族自治州","黔南布依族苗族自治州","黔西南布依族苗族自治州","铜仁","遵义"]);

dsy.add("0_0_7",["白沙黎族自治县","保亭黎族苗族自治县","昌江黎族自治县","澄迈县","定安县","东方","海口","乐东黎族自治县","临高县","陵水黎族自治县","琼海","琼中黎族苗族自治县","三亚","屯昌县","万宁","文昌","五指山","儋州"]);

dsy.add("0_0_8",["保定","沧州","承德","邯郸","衡水","廊坊","秦皇岛","石家庄","唐山","邢台","张家口"]);

dsy.add("0_0_9",["安阳","鹤壁","济源","焦作","开封","洛阳","南阳","平顶山","三门峡","商丘","新乡","信阳","许昌","郑州","周口","驻马店","漯河","濮阳"]);

dsy.add("0_0_10",["大庆","大兴安岭","哈尔滨","鹤岗","黑河","鸡西","佳木斯","牡丹江","七台河","齐齐哈尔","双鸭山","绥化","伊春"]);

dsy.add("0_0_11",["武汉","恩施土家族苗族自治州","黄冈","黄石","荆门","荆州","潜江","神农架林区","十堰","随州","天门","鄂州","仙桃","咸宁","襄樊","孝感","宜昌"]);

dsy.add("0_0_12",["常德","长沙","郴州","衡阳","怀化","娄底","邵阳","湘潭","湘西土家族苗族自治州","益阳","永州","岳阳","张家界","株洲"]);

dsy.add("0_0_13",["白城","白山","长春","吉林","辽源","四平","松原","通化","延边朝鲜族自治州"]);

dsy.add("0_0_14",["常州","淮安","连云港","南京","南通","苏州","宿迁","泰州","无锡","徐州","盐城","扬州","镇江"]);

dsy.add("0_0_15",["抚州","赣州","吉安","景德镇","九江","南昌","萍乡","上饶","新余","宜春","鹰潭"]);

dsy.add("0_0_16",["鞍山","本溪","朝阳","大连","丹东","抚顺","阜新","葫芦岛","锦州","辽阳","盘锦","沈阳","铁岭","营口"]);

dsy.add("0_0_17",["阿拉善盟","巴彦淖尔盟","包头","赤峰","鄂尔多斯","呼和浩特","呼伦贝尔","通辽","乌海","乌兰察布盟","锡林郭勒盟","兴安盟"]);

dsy.add("0_0_18",["固原","石嘴山","吴忠","银川"]);

dsy.add("0_0_19",["果洛藏族自治州","海北藏族自治州","海东","海南藏族自治州","海西蒙古族藏族自治州","黄南藏族自治州","西宁","玉树藏族自治州"]);

dsy.add("0_0_20",["滨州","德州","东营","菏泽","济南","济宁","莱芜","聊城","临沂","青岛","日照","泰安","威海","潍坊","烟台","枣庄","淄博"]);


dsy.add("0_0_21",["长治","大同","晋城","晋中","临汾","吕梁","朔州","太原","忻州","阳泉","运城"]);

dsy.add("0_0_22",["安康","宝鸡","汉中","商洛","铜川","渭南","西安","咸阳","延安","榆林"]);

dsy.add("0_0_23",["上海"]);


dsy.add("0_0_24",["阿坝藏族羌族自治州","巴中","成都","达州","德阳","甘孜藏族自治州","广安","广元","乐山","凉山彝族自治州","眉山","绵阳","南充","内江","攀枝花","遂宁","雅安","宜宾","资阳","自贡","泸州"]);

dsy.add("0_25",["天津"]);
dsy.add("0_25_0",["","蓟县","静海县","宁河县","天津市"]);

dsy.add("0_0_26",["阿里","昌都","拉萨","林芝","那曲","日喀则","山南"]);

dsy.add("0_0_27",["阿克苏","阿拉尔","巴音郭楞蒙古自治州","博尔塔拉蒙古自治州","昌吉回族自治州","哈密","和田","喀什","克拉玛依","克孜勒苏柯尔克孜自治州","石河子","图木舒克","吐鲁番","乌鲁木齐","五家渠","伊犁哈萨克自治州"]);

dsy.add("0_0_28",["保山","楚雄彝族自治州","大理白族自治州","德宏傣族景颇族自治州","迪庆藏族自治州","红河哈尼族彝族自治州","昆明","丽江","临沧","怒江傈傈族自治州","曲靖","思茅","文山壮族苗族自治州","西双版纳傣族自治州","玉溪","昭通"]);

dsy.add("0_0_29",["杭州","湖州","嘉兴","金华","丽水","宁波","绍兴","台州","温州","舟山","衢州"]);

dsy.add("0_0_30",["白银","定西","甘南藏族自治州","嘉峪关","金昌","酒泉","兰州","临夏回族自治州","陇南","平凉","庆阳","天水","武威","张掖"]);

dsy.add("0_0_31",["安庆","蚌埠","巢湖","池州","滁州","阜阳","合肥","淮北","淮南","黄山","六安","马鞍山","宿州","铜陵","芜湖","宣城","亳州"]);


dsy.add("0_0_32",["福州","龙岩","南平","宁德","莆田","泉州","三明","厦门","漳州"]);

var s = ["s1","s2","s3"];
var opt0 = ["国家","省份","地级市"];
function setup(n1,n2,n3) 
{
	for(i=0;i<s.length-1;i++) 
	document.getElementById(s[i]).onchange=new Function("change("+(i+1)+")"); 
	change(0); 
	document.getElementById('s1').value=n1;
	change(1);
	document.getElementById('s2').value=n2;
	change(2);
	document.getElementById('s3').value=n3;
} 
</script>
<div class="container" style="border:0px;">
    <form>
    	<div class="tb1">
        	<div class="ship_addr">
            	<div class="one_ship" style="margin-top:0px;">
                	<div class="left_txt"><span style="color:#e4393c;margin-right:5px;">*</span>收货人：</div>
                    <div class="rig_txt"><input type="text" placeholder="收货人姓名" name="Receiver"/></div>
                </div>
                <div class="one_ship">
                	<div class="left_txt"><span style="color:#e4393c;margin-right:5px;">*</span>手机号码：</div>
                    <div class="rig_txt"><input type="text" placeholder="手机号码" name="mobile"/></div>
                </div>
                <div class="one_ship">
                	<div class="left_txt">备用号码：</div>
                    <div class="rig_txt"><input type="text" placeholder="备用联系电话（固话/手机）" name="tel"/><span style="margin-left:15px;height:15px;color:#666;font-size:12px;">* 可选填</span></div>
                </div>
                <div class="one_ship">
                	<div class="left_txt"><span style="color:#e4393c;margin-right:5px;">*</span>地址：</div>
                    <div class="rig_txt">
                    	<ul>
                        	<li style="height:25px;width:150px;"><select name="country" class="select_cs" id="s1"><option>国家</option></select></li>
                            <li style="height:25px;width:150px;margin-left:10px;"><select name="prov" class="select_cs" id="s2"><option>省份</option></select></li>
                            <li style="height:25px;width:150px;margin-left:10px;"><select name="city" class="select_cs" disabled="disabled" id="s3"><option>地级市</option></select></li><br /><br />
                            <li style="height:30px;width:150px;"><select name="area" class="select_cs" id="s4" disabled="disabled"><option>区/县</option></select></li>
                            <li style="height:30px;width:150px;margin-left:10px;"><select name="street" class="select_cs" id="s5" disabled="disabled"><option>乡镇/街道</option></select></li>
                        </ul>
                    </div>
                </div>
                <script language="javascript">
                	window.onLoad=setup('中国','北京','北京');
                </script>
                <div class="one_ship">
                	<div class="left_txt"></div>
                    <div class="rig_txt"><input type="text" placeholder="详细地址" name="address"/></div>
                </div>
                <div class="save_addr">
                	<input type="checkbox" name="default_check" style="position:relative;top:-4px;"/> 设置为默认收货地址<br /><br />
                	<input type="button" value="保存收货地址" name="save_addr" style="background:#f0f0f0;border:1px solid #ccc;height:25px;" />
                </div>
               <div class="addr_list">
                	<h4>您已创建2个收货地址，最多可创建20个</h4>
					<div class="addr_list1" id="addr_list1" >
                    	<h4>潘某宝安区</h4>
                    	<a href="##" onClick="document.getElementById('addr_list1').style.display='none'" style="float:right;"><img src="../../../img/new/ucenter/fork.png" /></a><br />
                        <div class="row_text">
                        	<div class="ltxt">收货人：</div><div class="rtxt">某某</div>
                        </div>
                        <div class="row_text">
                        	<div class="ltxt">所在地区：</div><div class="rtxt">中国广东深圳市福田区</div>
                        </div>
                        <div class="row_text">
                        	<div class="ltxt">地址：</div><div class="rtxt">车公庙深南大道6019金润大厦1111</div>
                        </div>
                        <div class="row_text">
                        	<div class="ltxt">手机：</div><div class="rtxt">130*****888</div>
                        </div>
                        <div class="row_text">
                        	<div class="ltxt">固定电话：</div><div class="rtxt">0755-*****</div>
                        </div>
                        <div class="row_text">
                        	<div class="ltxt">电子邮箱：</div>
                            <div class="rtxt1">28*****88@qq.com</div>
                            <div class="rtxt2"><a href="##">设置为默认地址</a><a href="##">/修改</a></div>
                        </div>
                    </div>
                    <div class="addr_list1" id="addr_list2" style="margin-top:5px;">
                    	<h4>潘某宝安区</h4>
                    	<a href="##" onClick="document.getElementById('addr_list2').style.display='none'" style="float:right;"><img src="../../../img/new/ucenter/fork.png" /></a><br />
                        <div class="row_text">
                        	<div class="ltxt">收货人：</div><div class="rtxt">某某</div>
                        </div>
                        <div class="row_text">
                        	<div class="ltxt">所在地区：</div><div class="rtxt">中国广东深圳市福田区</div>
                        </div>
                        <div class="row_text">
                        	<div class="ltxt">地址：</div><div class="rtxt">车公庙深南大道6019金润大厦1111</div>
                        </div>
                        <div class="row_text">
                        	<div class="ltxt">手机：</div><div class="rtxt">130*****888</div>
                        </div>
                        <div class="row_text">
                        	<div class="ltxt">固定电话：</div><div class="rtxt">0755-*****</div>
                        </div>
                        <div class="row_text">
                        	<div class="ltxt">电子邮箱：</div>
                            <div class="rtxt1">28*****88@qq.com</div>
                            <div class="rtxt2"><a href="##">设置为默认地址</a><a href="##">/修改</a></div>
                        </div>
                    </div>
                    <div class="page"><img src="../../../img/new/ucenter/page.png" /></div>
                </div>
            </div>
        
        </div>
    </form>
</div>
