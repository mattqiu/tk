<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/* Bonus config. By Terry. */
$config['bonus'] = array(
    'newMemberProfitShar'=>array(
        'profitProp'=>0.02,//昨天全球利润的比例，用以发放日分红。
        'avgProp'=>0.9,//日分红利润中用来均摊的比例。
        'pageSize'=>1000,//分页执行发奖的每页数量。
    ),
    /*日分红*/
    'dailyProfitShar'=>array(
        'profitProp'=>0.34,//昨天全球利润的比例，用以发放日分红。
        'avgProp'=>0.9,//日分红利润中用来均摊的比例。
        'pageSize'=>1000,//分页执行发奖的每页数量。
    ),
    'daily_bonus_rank'=>array(
        '1'=>0.3,//钻石
        '2'=>0.3,//白金
        '3'=>0.2,//白银
        '4'=>0.1,//免费
        '5'=>0.1//铜级
    ),

    /*138分红（日）*/
    '138Shar'=>array(
        'profitProp'=>0.23,//昨天全球利润的比例，用以发放138。
        'avgProp'=>0.5,//138利润中用来均摊的比例。
        'pageSize'=>1000,//分页处理发奖的每页数量。
    ),

    /*精英日分红*/
    'dailyEliteProfitShar'=>array(
        'profitProp'=>0.28,//昨天全球利润的比例，用以发放精英日分红。
        'pageSize'=>1000,//分页执行发奖的每页数量。
    ),

    'bonus_type'=>array(
        24=>"daily_bonus_elite",//销售精英日分红
        2=>'138_bonus',//138分红
        7=>'week_bonus',//
        8=>'month_eminent_store',//月杰出店铺奖
        25=>'week_share_bonus',//
        26=>'new_member_bonus',//
        6=>'daily_bonus',//
        1=>'month_group_share', //每月团队组织分红奖
        23 =>'month_leader_share_bonus',//每月领导分红奖
        27=>'supplier_recommendation',//供应商推荐奖
        4=>'leader_lv5'//全球副总裁
    ),
);
