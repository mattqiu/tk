<?php

if (!defined('BASEPATH'))
{
	exit('No direct script access allowed');
}

class Order_enum
{
	/* 订单状态 */
	const STATUS_INIT = '1'; // 初始状态（预留）
	const STATUS_CHECKOUT = '2'; // 等待付款
	const STATUS_SHIPPING = '3'; // 等待发货
	const STATUS_SHIPPED = '4'; // 等待收货
	const STATUS_EVALUATION = '5'; // 等待评价
	const STATUS_COMPLETE = '6'; // 已完成
	const STATUS_HOLDING = '90';	//冻结
	const STATUS_RETURNING = '97'; // 退货中
	const STATUS_RETURN = '98'; // 退货完成
	const STATUS_CANCEL = '99'; // 订单取消
	const STATUS_COMPONENT = '100'; // 只分析子订单状态
	const STATUS_DOBA_EXCEPTION = '111'; // doba异常订单，需要取消订单
}
