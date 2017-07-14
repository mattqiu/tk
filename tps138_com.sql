/*
Navicat MySQL Data Transfer

Source Server         : rm-wz9791vy49524iv95o.mysql.rds.aliyuncs.com_3306
Source Server Version : 50634
Source Host           : rm-wz9791vy49524iv95o.mysql.rds.aliyuncs.com:3306
Source Database       : tps138_com

Target Server Type    : MYSQL
Target Server Version : 50634
File Encoding         : 65001

Date: 2017-07-14 09:59:55
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for 138_debug
-- ----------------------------
DROP TABLE IF EXISTS `138_debug`;
CREATE TABLE `138_debug` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `x` int(11) NOT NULL DEFAULT '0',
  `y` int(11) NOT NULL DEFAULT '0',
  `create_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='138矩阵重置值日志';

-- ----------------------------
-- Table structure for 138_grant_tmp
-- ----------------------------
DROP TABLE IF EXISTS `138_grant_tmp`;
CREATE TABLE `138_grant_tmp` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `num_share` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '138分红时的份额数（矩阵底下的人数）',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='138分红时，用以统计份额数的临时表';

-- ----------------------------
-- Table structure for 138_grant_tmp2
-- ----------------------------
DROP TABLE IF EXISTS `138_grant_tmp2`;
CREATE TABLE `138_grant_tmp2` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `num_share` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '138分红时的份额数（矩阵底下的人数）',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='138分红时，用以统计份额数的临时表';

-- ----------------------------
-- Table structure for admin_action_logs
-- ----------------------------
DROP TABLE IF EXISTS `admin_action_logs`;
CREATE TABLE `admin_action_logs` (
  `log_id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_id` int(11) NOT NULL DEFAULT '0' COMMENT '管理员ID',
  `action` varchar(50) NOT NULL DEFAULT '0' COMMENT '管理员操作行为:删除，修改，新增',
  `action_table` varchar(50) NOT NULL DEFAULT '0' COMMENT '操作对应的表：users，orders',
  `action_object_id` int(11) NOT NULL DEFAULT '0' COMMENT '操作的对象：user_id,order_id',
  `action_field` varchar(50) DEFAULT '0' COMMENT '操作的字段：email,cash,',
  `before_data` text COMMENT '操作前的数据',
  `after_data` text COMMENT '操作后的数据',
  `change_data` int(11) DEFAULT '0' COMMENT '增加或减少数据',
  `action_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '操作时间',
  PRIMARY KEY (`log_id`),
  KEY `action_object_id` (`action_object_id`),
  KEY `action` (`action`),
  KEY `action_time` (`action_time`)
) ENGINE=InnoDB AUTO_INCREMENT=1108904 DEFAULT CHARSET=utf8 COMMENT='管理员操作日志表';

-- ----------------------------
-- Table structure for admin_ads_file_manage
-- ----------------------------
DROP TABLE IF EXISTS `admin_ads_file_manage`;
CREATE TABLE `admin_ads_file_manage` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `file_area` tinyint(4) NOT NULL DEFAULT '1' COMMENT '区域 1 英文 2 中文 3 繁体 4 韩文 5 其他',
  `admin_id` int(11) NOT NULL DEFAULT '0' COMMENT '作者',
  `file_type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '文件类型',
  `file_name` varchar(100) NOT NULL DEFAULT '' COMMENT '文件名称',
  `file_real_name` varchar(100) NOT NULL DEFAULT '' COMMENT '文件真实名字',
  `dir_name` varchar(255) NOT NULL DEFAULT '' COMMENT '文件夹路径',
  `file_path` varchar(255) NOT NULL DEFAULT '' COMMENT '文件路径',
  `width` int(11) NOT NULL DEFAULT '0',
  `height` int(11) NOT NULL DEFAULT '0' COMMENT '高',
  `size` int(11) NOT NULL DEFAULT '0' COMMENT '大小',
  `file_extension` varchar(10) NOT NULL DEFAULT '' COMMENT '文件后缀',
  `is_show` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0：不显示 1：显示',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1：正常显示 0：已删除',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `file_type_index` (`file_type`) USING BTREE,
  KEY `file_name_index` (`file_name`) USING BTREE,
  KEY `create_time` (`create_time`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COMMENT='后台文件管理';

-- ----------------------------
-- Table structure for admin_ads_file_manage_log
-- ----------------------------
DROP TABLE IF EXISTS `admin_ads_file_manage_log`;
CREATE TABLE `admin_ads_file_manage_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` tinyint(4) NOT NULL DEFAULT '1' COMMENT '操作码 1 添加 2 更新 3 删除',
  `admin_id` int(11) NOT NULL DEFAULT '0',
  `file_id` int(11) NOT NULL DEFAULT '0' COMMENT '文件id',
  `old_data` text NOT NULL COMMENT '操作前数据',
  `new_data` text NOT NULL COMMENT '操作后数据',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `admin_id_index` (`admin_id`) USING BTREE,
  KEY `file_id_index` (`file_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for admin_after_sale_batch
-- ----------------------------
DROP TABLE IF EXISTS `admin_after_sale_batch`;
CREATE TABLE `admin_after_sale_batch` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `batch_num` varchar(20) NOT NULL DEFAULT '0' COMMENT '批次号',
  `total` int(11) NOT NULL DEFAULT '0' COMMENT '总数',
  `lump_sum` decimal(14,2) NOT NULL DEFAULT '0.00' COMMENT '总金额:人民币',
  `status` int(1) NOT NULL DEFAULT '1' COMMENT '状态（1待处理，2处理中，3处理完成）',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `process_time` timestamp NULL DEFAULT NULL,
  `failure` int(5) DEFAULT '0' COMMENT '失败数',
  `success` int(5) DEFAULT '0' COMMENT '成功数',
  PRIMARY KEY (`id`),
  KEY `batch_num` (`batch_num`),
  KEY `status` (`status`),
  KEY `born_time` (`create_time`)
) ENGINE=InnoDB AUTO_INCREMENT=117 DEFAULT CHARSET=utf8 COMMENT='支付宝提现批次表（售后）';

-- ----------------------------
-- Table structure for admin_after_sale_order
-- ----------------------------
DROP TABLE IF EXISTS `admin_after_sale_order`;
CREATE TABLE `admin_after_sale_order` (
  `as_id` varchar(50) NOT NULL DEFAULT '',
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '会员ID',
  `name` varchar(50) NOT NULL DEFAULT '0' COMMENT '会员名字',
  `type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '售后分类：0退会，1，降级，2商城交易',
  `refund_method` tinyint(4) NOT NULL DEFAULT '0' COMMENT '退款方式：0:转账银行信息，1：转现金池，2：支付宝',
  `refund_amount` varchar(50) NOT NULL DEFAULT '0.00',
  `remark` varchar(500) NOT NULL DEFAULT '0' COMMENT '反馈内容：会员要求店铺降级为银级，原为白金。',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '处理状态:0：待处理。1：已抽回(待付款)，2：已抽回（已退款到现金池），3:已退款到银行,4,抽回驳回，5退款银行卡驳回,6:作废（抽回驳回）7，已录入的',
  `apply_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '申请时间',
  `card_number` varchar(100) NOT NULL DEFAULT '0' COMMENT '银行卡号',
  `account_bank` varchar(100) NOT NULL DEFAULT '0' COMMENT '开户行 支行名称',
  `account_name` varchar(100) NOT NULL DEFAULT '0' COMMENT '开户名',
  `transfer_uid` int(11) DEFAULT '0' COMMENT '转现金池：需要转入的ID号',
  `demote_level` int(11) DEFAULT '0' COMMENT '降級的等級',
  `reject_remark` varchar(50) DEFAULT NULL COMMENT '駁回原因',
  `admin_id` int(11) NOT NULL DEFAULT '0' COMMENT '申請人',
  `admin_email` varchar(50) NOT NULL DEFAULT '0' COMMENT '申請人Email',
  `img` varchar(250) DEFAULT NULL COMMENT '回执单路径',
  `order_id` char(19) DEFAULT NULL COMMENT '订单id',
  `batch_id` int(11) DEFAULT NULL COMMENT '批次表关联ID',
  `is_three_month` tinyint(4) DEFAULT '0' COMMENT '是否是3月3号升级订单的售后单',
  `order_cance_amount` int(11) DEFAULT '0' COMMENT '需要取消的订单总金额',
  `order_count_amount` int(11) DEFAULT '0' COMMENT '订单总金额',
  UNIQUE KEY `as_id` (`as_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='管理员售后单：退级，退会等流程';

-- ----------------------------
-- Table structure for admin_after_sale_remark
-- ----------------------------
DROP TABLE IF EXISTS `admin_after_sale_remark`;
CREATE TABLE `admin_after_sale_remark` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `as_id` varchar(50) NOT NULL DEFAULT '0' COMMENT '单号',
  `admin_id` varchar(50) NOT NULL DEFAULT '0' COMMENT '管理员ID',
  `remark` varchar(500) NOT NULL DEFAULT '0',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21788 DEFAULT CHARSET=utf8 COMMENT='售后单备注';

-- ----------------------------
-- Table structure for admin_blacklist
-- ----------------------------
DROP TABLE IF EXISTS `admin_blacklist`;
CREATE TABLE `admin_blacklist` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `admin_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '管理员ID',
  `content` varchar(50) NOT NULL DEFAULT '0' COMMENT '黑名单',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1059 DEFAULT CHARSET=utf8 COMMENT='词语黑名单';

-- ----------------------------
-- Table structure for admin_fix_credit_log
-- ----------------------------
DROP TABLE IF EXISTS `admin_fix_credit_log`;
CREATE TABLE `admin_fix_credit_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `admin_id` int(11) NOT NULL DEFAULT '0' COMMENT '操作的后台用户',
  `action` varchar(50) NOT NULL DEFAULT '' COMMENT '操作的方法',
  `action_data` varchar(255) NOT NULL DEFAULT '' COMMENT '操作传递的数据 type 1只修复积分 2 一起修复 3 修复职称',
  `create_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '操作时间',
  `admin_email` varchar(50) NOT NULL DEFAULT '' COMMENT '操作的邮件',
  `data` varchar(255) NOT NULL DEFAULT '' COMMENT '修复前后的积分和职称',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='后台修复积积分和职称表';

-- ----------------------------
-- Table structure for admin_knowledge
-- ----------------------------
DROP TABLE IF EXISTS `admin_knowledge`;
CREATE TABLE `admin_knowledge` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_id` int(11) NOT NULL DEFAULT '0' COMMENT '作者',
  `title` varchar(150) NOT NULL DEFAULT '' COMMENT '标题',
  `content` text NOT NULL COMMENT '内容',
  `category_id` int(11) NOT NULL COMMENT '分类ID',
  `is_show` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0：不显示 1：显示',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1：正常显示 0：已删除',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `title_index` (`title`) USING BTREE,
  KEY `create_time` (`create_time`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8 COMMENT='后台知识库管理';

-- ----------------------------
-- Table structure for admin_knowledge_cate
-- ----------------------------
DROP TABLE IF EXISTS `admin_knowledge_cate`;
CREATE TABLE `admin_knowledge_cate` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_id` int(11) NOT NULL DEFAULT '0' COMMENT '作者',
  `parent_id` int(11) NOT NULL DEFAULT '0' COMMENT '父分类ID',
  `name` varchar(150) NOT NULL DEFAULT '' COMMENT '分类名',
  `is_show` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0：不显示 1：显示',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1：正常显示 0：已删除',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `sort` int(11) DEFAULT '0',
  `modify_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `name_index` (`name`) USING BTREE,
  KEY `create_time` (`create_time`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8 COMMENT='后台知识库分类';

-- ----------------------------
-- Table structure for admin_knowledge_cate_log
-- ----------------------------
DROP TABLE IF EXISTS `admin_knowledge_cate_log`;
CREATE TABLE `admin_knowledge_cate_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` tinyint(4) NOT NULL DEFAULT '1' COMMENT '类型，1编辑，2添加，3删除',
  `knowledge_cate_id` int(11) NOT NULL DEFAULT '0' COMMENT '知识分类id',
  `admin_id` int(11) NOT NULL DEFAULT '0' COMMENT '操作者',
  `old_data` text COMMENT '旧数据',
  `new_data` text COMMENT '新数据',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=utf8 COMMENT='知识库分类修改记录';

-- ----------------------------
-- Table structure for admin_knowledge_log
-- ----------------------------
DROP TABLE IF EXISTS `admin_knowledge_log`;
CREATE TABLE `admin_knowledge_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` tinyint(4) NOT NULL DEFAULT '1' COMMENT '类型，1编辑，2添加，3删除',
  `knowledge_id` int(11) NOT NULL DEFAULT '0' COMMENT '知识id',
  `admin_id` int(11) NOT NULL DEFAULT '0' COMMENT '操作者',
  `old_data` text COMMENT '旧数据',
  `new_data` text COMMENT '新数据',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=77 DEFAULT CHARSET=utf8 COMMENT='知识库修改记录';

-- ----------------------------
-- Table structure for admin_manage_commission_logs
-- ----------------------------
DROP TABLE IF EXISTS `admin_manage_commission_logs`;
CREATE TABLE `admin_manage_commission_logs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `admin_id` int(10) unsigned DEFAULT '0' COMMENT '操作人id',
  `oper_type` tinyint(3) unsigned DEFAULT '0' COMMENT '操作类型：1给用户加减佣金（特殊款项）',
  `uid` int(10) unsigned DEFAULT '0',
  `comm_amount` decimal(14,2) DEFAULT '0.00',
  `desc` text,
  `key` varchar(50) NOT NULL DEFAULT '' COMMENT '关联字段，用竖线 | 隔开，竖线前是表名，竖线后是关联的ID',
  `create_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25501 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for admin_notice
-- ----------------------------
DROP TABLE IF EXISTS `admin_notice`;
CREATE TABLE `admin_notice` (
  `id` mediumint(7) unsigned NOT NULL AUTO_INCREMENT,
  `card_phone` tinyint(4) DEFAULT '0' COMMENT '阿里云身份证接口次数用尽通知 默认值为0则手机短信通知打开,1则关闭',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='管理员通知表 ';

-- ----------------------------
-- Table structure for admin_repair_user_amount_log
-- ----------------------------
DROP TABLE IF EXISTS `admin_repair_user_amount_log`;
CREATE TABLE `admin_repair_user_amount_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `amount_before` decimal(14,2) NOT NULL DEFAULT '0.00' COMMENT '修复之前的余额',
  `amount_after` decimal(14,2) NOT NULL DEFAULT '0.00' COMMENT '修复之后的余额',
  `admin_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '操作人id',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `user_id` (`uid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=394 DEFAULT CHARSET=utf8 COMMENT='修复会员现金池记录表';

-- ----------------------------
-- Table structure for admin_repair_user_point_log
-- ----------------------------
DROP TABLE IF EXISTS `admin_repair_user_point_log`;
CREATE TABLE `admin_repair_user_point_log` (
  `log_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '日志ID',
  `uid` int(11) NOT NULL COMMENT '用户ID',
  `point_before` decimal(14,2) NOT NULL DEFAULT '0.00' COMMENT '修复之前分红点',
  `point_after` decimal(14,2) NOT NULL DEFAULT '0.00' COMMENT '修复之后的分红点',
  `admin_id` int(11) NOT NULL COMMENT '管理员Id',
  `create_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '修复时间',
  PRIMARY KEY (`log_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COMMENT='用户分红点修复记录表';

-- ----------------------------
-- Table structure for admin_right
-- ----------------------------
DROP TABLE IF EXISTS `admin_right`;
CREATE TABLE `admin_right` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` tinyint(4) NOT NULL DEFAULT '1' COMMENT '配置类型 1 id',
  `admin_id` int(11) NOT NULL DEFAULT '0' COMMENT '初始权限者',
  `right_name` varchar(255) NOT NULL DEFAULT 'default' COMMENT '权限名称',
  `right_key` varchar(255) NOT NULL DEFAULT 'default' COMMENT '权限的键',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注信息',
  `right` varchar(10000) NOT NULL DEFAULT '0' COMMENT '权限详细',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `right_key_index` (`right_key`) USING BTREE,
  KEY `create_time_index` (`create_time`) USING BTREE,
  KEY `right_name_index` (`right_name`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=281 DEFAULT CHARSET=utf8 COMMENT='权限记录表';

-- ----------------------------
-- Table structure for admin_right_log
-- ----------------------------
DROP TABLE IF EXISTS `admin_right_log`;
CREATE TABLE `admin_right_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` tinyint(4) NOT NULL DEFAULT '1' COMMENT '类型 1 编辑，2 添加',
  `right_id` int(11) NOT NULL DEFAULT '0' COMMENT '权限id',
  `admin_id` int(11) NOT NULL DEFAULT '0' COMMENT '操作者',
  `old_data` varchar(10000) NOT NULL DEFAULT '' COMMENT '旧数据',
  `new_data` varchar(10000) NOT NULL DEFAULT '' COMMENT '新数据',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `right_id_index` (`right_id`) USING BTREE,
  KEY `type_index` (`type`) USING BTREE,
  KEY `create_time_index` (`create_time`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=477 DEFAULT CHARSET=utf8 COMMENT='权限修改记录';

-- ----------------------------
-- Table structure for admin_tickets
-- ----------------------------
DROP TABLE IF EXISTS `admin_tickets`;
CREATE TABLE `admin_tickets` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `uid` varchar(50) NOT NULL DEFAULT '0' COMMENT '会员ID',
  `title` varchar(255) NOT NULL DEFAULT '0' COMMENT '问题标题',
  `content` text NOT NULL COMMENT '问题内容',
  `admin_id` int(11) NOT NULL DEFAULT '0' COMMENT '管理员ID',
  `is_attach` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否存在附件',
  `language_id` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1 为英文',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '问题分类,0：佣金问题 ，1：退会问题，2：降级问题，3：订单问题',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态，0：新建，1：已开启, 2 : 待回应，3：待商议，4：已解决，5：已评价，6：申请关闭',
  `priority` tinyint(1) NOT NULL DEFAULT '0' COMMENT '工单的优先级，0：一般，1优先，2，紧急',
  `sender` tinyint(1) NOT NULL DEFAULT '0' COMMENT '谁发的  0：会员  1：客服',
  `last_reply` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0:会员 1:客服',
  `score` tinyint(4) NOT NULL DEFAULT '5' COMMENT '评分',
  `send_email_num` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0 未发邮件 1已发1封邮件,2已发第二封邮件',
  `last_assign_time` varchar(10) NOT NULL DEFAULT '0',
  `apply_close_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '申请时间',
  PRIMARY KEY (`id`),
  KEY `type` (`type`),
  KEY `status` (`status`),
  KEY `admin_id` (`admin_id`),
  KEY `uid` (`uid`),
  KEY `priority` (`priority`),
  KEY `create_time` (`create_time`),
  KEY `apply_close_time` (`apply_close_time`),
  KEY `idx_last_assign_time` (`last_assign_time`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=890262 DEFAULT CHARSET=utf8 COMMENT='会员申请的售后工单';

-- ----------------------------
-- Table structure for admin_tickets_attach
-- ----------------------------
DROP TABLE IF EXISTS `admin_tickets_attach`;
CREATE TABLE `admin_tickets_attach` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tickets_id` int(11) NOT NULL DEFAULT '0' COMMENT '关联的工单ID',
  `name` varchar(255) NOT NULL DEFAULT '0' COMMENT '附件的名称',
  `path_name` varchar(255) NOT NULL DEFAULT '0' COMMENT '保存在服务器的路径',
  `extension` varchar(50) NOT NULL DEFAULT '0' COMMENT '附件的后缀',
  `is_reply` tinyint(4) DEFAULT '0' COMMENT '0:原始工单的附件，1回复工单的附件',
  PRIMARY KEY (`id`),
  KEY `tickets_id` (`tickets_id`)
) ENGINE=InnoDB AUTO_INCREMENT=477090 DEFAULT CHARSET=utf8 COMMENT='工单的附件';

-- ----------------------------
-- Table structure for admin_tickets_bak
-- ----------------------------
DROP TABLE IF EXISTS `admin_tickets_bak`;
CREATE TABLE `admin_tickets_bak` (
  `id` int(11) NOT NULL DEFAULT '0' COMMENT '自增id',
  `uid` varchar(50) NOT NULL DEFAULT '0' COMMENT '会员ID',
  `title` varchar(255) NOT NULL DEFAULT '0' COMMENT '问题标题',
  `content` text NOT NULL COMMENT '问题内容',
  `admin_id` int(11) NOT NULL DEFAULT '0' COMMENT '管理员ID',
  `is_attach` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否存在附件',
  `language_id` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1 为英文',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '问题分类,0：加入/退出 ，1：升级/降级，2：月费问题，3：店铺转让，5：产品推荐，6：佣金问题，7：订单问题，8：运费问题投诉，9：提现问题，11：其他',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态，0：新建，1：已开启, 2 : 待回应，3：待商议，4：已解决，5：已评价，6：申请关闭',
  `priority` tinyint(1) NOT NULL DEFAULT '0' COMMENT '工单的优先级，0：一般，1优先，2，紧急',
  `sender` tinyint(1) NOT NULL DEFAULT '0' COMMENT '谁发的  0：会员  1：客服',
  `last_reply` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0:会员 1:客服',
  `score` tinyint(4) NOT NULL DEFAULT '5' COMMENT '评分',
  `send_email_num` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0 未发邮件 1已发1封邮件,2已发第二封邮件',
  `last_assign_time` varchar(10) NOT NULL DEFAULT '0',
  `apply_close_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '申请关闭时间',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '申请时间',
  `insert_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '备份时间',
  KEY `type` (`type`),
  KEY `status` (`status`),
  KEY `admin_id` (`admin_id`),
  KEY `uid` (`uid`),
  KEY `priority` (`priority`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='会员申请的售后工单';

-- ----------------------------
-- Table structure for admin_tickets_black_list
-- ----------------------------
DROP TABLE IF EXISTS `admin_tickets_black_list`;
CREATE TABLE `admin_tickets_black_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `uid` varchar(50) NOT NULL COMMENT '会员id',
  `admin_id` int(11) NOT NULL DEFAULT '0' COMMENT '管理员id',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0 为在黑名单，1 为已从黑名单中移除',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `uid_index` (`uid`),
  KEY `admin_id_index` (`admin_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for admin_tickets_count
-- ----------------------------
DROP TABLE IF EXISTS `admin_tickets_count`;
CREATE TABLE `admin_tickets_count` (
  `admin_id` int(11) NOT NULL DEFAULT '0' COMMENT '客服id',
  `count` int(11) NOT NULL DEFAULT '0' COMMENT '总数',
  `create_time` varchar(10) NOT NULL DEFAULT '0' COMMENT '时间',
  PRIMARY KEY (`admin_id`,`create_time`),
  KEY `index` (`admin_id`,`create_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for admin_tickets_customer_role
-- ----------------------------
DROP TABLE IF EXISTS `admin_tickets_customer_role`;
CREATE TABLE `admin_tickets_customer_role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_id` int(11) NOT NULL,
  `role` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1:客服 2:节假日值班经理',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=108 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for admin_tickets_customer_role_logs
-- ----------------------------
DROP TABLE IF EXISTS `admin_tickets_customer_role_logs`;
CREATE TABLE `admin_tickets_customer_role_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_id` int(11) NOT NULL COMMENT '操作人',
  `cus_id` int(11) NOT NULL COMMENT '客服id',
  `old_value` tinyint(4) NOT NULL DEFAULT '0' COMMENT '1:客服 2:节假日值班经理',
  `new_value` tinyint(4) NOT NULL DEFAULT '0' COMMENT '1:客服 2:节假日值班经理',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=100 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for admin_tickets_daily_count
-- ----------------------------
DROP TABLE IF EXISTS `admin_tickets_daily_count`;
CREATE TABLE `admin_tickets_daily_count` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_id` int(11) NOT NULL DEFAULT '0',
  `d_in` int(11) NOT NULL DEFAULT '0',
  `d_out` int(11) NOT NULL DEFAULT '0',
  `d_count` int(11) NOT NULL DEFAULT '0',
  `count_time` date NOT NULL DEFAULT '0000-00-00',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `admin_id` (`admin_id`),
  KEY `count_time` (`count_time`)
) ENGINE=InnoDB AUTO_INCREMENT=16615 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for admin_tickets_logs
-- ----------------------------
DROP TABLE IF EXISTS `admin_tickets_logs`;
CREATE TABLE `admin_tickets_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tickets_id` int(11) NOT NULL DEFAULT '0' COMMENT '工单id',
  `old_data` smallint(4) NOT NULL DEFAULT '0' COMMENT '老的数据；100为不需要数据',
  `new_data` smallint(4) NOT NULL DEFAULT '0' COMMENT '新的数据；100为不需要数据',
  `data_type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '数据类型,0:添加新工单，1:状态，2：优先级，3，变更管理员，4：申请关闭 ，5：查看，6：回复为待回应，7：回复为待商议，8：注释，9：回复为已解决',
  `admin_id` int(11) NOT NULL DEFAULT '0' COMMENT '谁处理的',
  `is_admin` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0:会员   1:客服',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '处理时间',
  PRIMARY KEY (`id`),
  KEY `tickets_id` (`tickets_id`)
) ENGINE=InnoDB AUTO_INCREMENT=13608329 DEFAULT CHARSET=utf8 COMMENT='管理员操作工单，状态，优先级，管理员的的变更记录';

-- ----------------------------
-- Table structure for admin_tickets_record
-- ----------------------------
DROP TABLE IF EXISTS `admin_tickets_record`;
CREATE TABLE `admin_tickets_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_id` int(11) NOT NULL DEFAULT '0',
  `type` tinyint(4) NOT NULL DEFAULT '0',
  `count` int(11) NOT NULL DEFAULT '0',
  `assign_time` varchar(15) NOT NULL DEFAULT '0',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `type` (`type`),
  KEY `assign_time` (`assign_time`),
  KEY `admin_id` (`admin_id`)
) ENGINE=InnoDB AUTO_INCREMENT=633885 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for admin_tickets_reply
-- ----------------------------
DROP TABLE IF EXISTS `admin_tickets_reply`;
CREATE TABLE `admin_tickets_reply` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tickets_id` int(11) NOT NULL DEFAULT '0' COMMENT '工单号ID',
  `content` text COMMENT '回复的内容',
  `admin_id` int(11) NOT NULL DEFAULT '0' COMMENT '回复的管理员ID',
  `uid` varchar(50) NOT NULL DEFAULT '0' COMMENT '回复的会员ID',
  `is_attach` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否存在附件',
  `sender` tinyint(1) NOT NULL DEFAULT '0' COMMENT '发送者   0:会员 1:客服',
  `type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0 为工单回复，100 为注释',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '回复的时间',
  PRIMARY KEY (`id`),
  KEY `tickets_id_index` (`tickets_id`),
  KEY `admin_id` (`admin_id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB AUTO_INCREMENT=1936795 DEFAULT CHARSET=utf8 COMMENT='工单所有的回复的内容';

-- ----------------------------
-- Table structure for admin_tickets_template
-- ----------------------------
DROP TABLE IF EXISTS `admin_tickets_template`;
CREATE TABLE `admin_tickets_template` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '模板id',
  `name` varchar(100) NOT NULL,
  `admin_id` int(11) NOT NULL DEFAULT '0',
  `content` text NOT NULL COMMENT '模板内容',
  `type` tinyint(4) NOT NULL COMMENT '类型',
  `status` tinyint(1) NOT NULL COMMENT '0:公开 1:私有 2:禁用',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `admin_id` (`admin_id`),
  KEY `status` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=2531 DEFAULT CHARSET=utf8 COMMENT='自定义模板';

-- ----------------------------
-- Table structure for admin_users
-- ----------------------------
DROP TABLE IF EXISTS `admin_users`;
CREATE TABLE `admin_users` (
  `id` mediumint(7) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(50) NOT NULL DEFAULT '0',
  `pwd_encry` varchar(40) NOT NULL DEFAULT '0' COMMENT '密码密文',
  `token` varchar(32) NOT NULL DEFAULT '0' COMMENT 'md5后的一个用户安全码',
  `role` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '0=>超级管理员，1=>客服,2=>客服经理',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '賬戶狀態0 未激活 1激活',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `job_number` int(11) DEFAULT '0' COMMENT '客服电话号码工号',
  `area` int(11) DEFAULT '0' COMMENT '1：英文客服，2：中国客服',
  PRIMARY KEY (`id`),
  KEY `job_number` (`job_number`)
) ENGINE=InnoDB AUTO_INCREMENT=572 DEFAULT CHARSET=utf8 COMMENT='后台管理用户表';

-- ----------------------------
-- Table structure for bonus_plan_control
-- ----------------------------
DROP TABLE IF EXISTS `bonus_plan_control`;
CREATE TABLE `bonus_plan_control` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL COMMENT '标题',
  `item_type` int(2) unsigned NOT NULL DEFAULT '0' COMMENT '分红类型',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '状态（0 未执行 1执行中 2已执行）',
  `exec_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '执行时间',
  `exec_end_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '最后完成时间',
  `description` varchar(255) DEFAULT NULL COMMENT '描述',
  `type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '类型 1天 2周 3月',
  `opentype` tinyint(2) DEFAULT '0' COMMENT '操作类型：0，分红监控;1.其他监控',
  `ishand` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否手动发奖 0否 1是',
  `ishanding` tinyint(1) NOT NULL DEFAULT '0' COMMENT '手动发奖执行状态  0未执行 1开始执行',
  `rate` float(4,4) NOT NULL DEFAULT '0.0000' COMMENT '比例',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COMMENT='分红计划监控';

-- ----------------------------
-- Table structure for bonus_system_management
-- ----------------------------
DROP TABLE IF EXISTS `bonus_system_management`;
CREATE TABLE `bonus_system_management` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL COMMENT '名称',
  `lang` tinyint(3) unsigned DEFAULT '2' COMMENT '2 简体中文 3繁体中文 1英文 4韩文',
  `content` text COMMENT '内容',
  `html_content` text COMMENT '包含html标签的内容',
  `status` tinyint(1) unsigned DEFAULT '1' COMMENT '状态 1显示  0不显示',
  `sort` tinyint(1) unsigned DEFAULT '0' COMMENT '排序',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='奖金管理制度表';

-- ----------------------------
-- Table structure for bulletin_board
-- ----------------------------
DROP TABLE IF EXISTS `bulletin_board`;
CREATE TABLE `bulletin_board` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title_english` varchar(255) NOT NULL DEFAULT '0',
  `title_zh` varchar(255) NOT NULL DEFAULT '0',
  `title_hk` varchar(255) NOT NULL DEFAULT '0',
  `title_kr` varchar(255) NOT NULL DEFAULT '0',
  `permission` tinyint(1) NOT NULL COMMENT '1：所有，2：店主，3顧客',
  `english` text COMMENT '英文内容',
  `zh` text COMMENT '中文',
  `hk` text COMMENT '繁体',
  `kr` text COMMENT '韩文',
  `display` tinyint(3) NOT NULL DEFAULT '0' COMMENT '0：显示，1不显示',
  `sort` int(11) NOT NULL DEFAULT '100' COMMENT '排序',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `important` tinyint(3) NOT NULL DEFAULT '0' COMMENT '1 顯示重要提示',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=220 DEFAULT CHARSET=utf8 COMMENT='公告栏';

-- ----------------------------
-- Table structure for bulletin_read
-- ----------------------------
DROP TABLE IF EXISTS `bulletin_read`;
CREATE TABLE `bulletin_read` (
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户编号',
  `bulletin_id` int(11) NOT NULL DEFAULT '0' COMMENT '广告编号',
  PRIMARY KEY (`uid`,`bulletin_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for bulletin_unread
-- ----------------------------
DROP TABLE IF EXISTS `bulletin_unread`;
CREATE TABLE `bulletin_unread` (
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '会员ID',
  `bulletin_id` int(11) NOT NULL DEFAULT '0' COMMENT '公告ID',
  PRIMARY KEY (`uid`,`bulletin_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户未读公告提醒';

-- ----------------------------
-- Table structure for cache_data
-- ----------------------------
DROP TABLE IF EXISTS `cache_data`;
CREATE TABLE `cache_data` (
  `cache_key` varchar(45) NOT NULL COMMENT '缓存key',
  `cache_val` text COMMENT '缓存内容',
  PRIMARY KEY (`cache_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for cash_account_log_201607
-- ----------------------------
DROP TABLE IF EXISTS `cash_account_log_201607`;
CREATE TABLE `cash_account_log_201607` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL,
  `item_type` tinyint(1) unsigned NOT NULL COMMENT '资金变动项类型。',
  `amount` int(11) NOT NULL,
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `order_id` varchar(25) NOT NULL DEFAULT '0' COMMENT '订单号',
  `related_uid` int(10) unsigned NOT NULL DEFAULT '0',
  `related_id` varchar(35) NOT NULL DEFAULT '0' COMMENT '关联id',
  `remark` text COMMENT '佣金备注',
  PRIMARY KEY (`id`),
  KEY `create_time` (`create_time`),
  KEY `item_type` (`item_type`),
  KEY `order_id` (`order_id`),
  KEY `related_uid` (`related_uid`),
  KEY `uid` (`uid`,`item_type`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2818603 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for cash_account_log_201608
-- ----------------------------
DROP TABLE IF EXISTS `cash_account_log_201608`;
CREATE TABLE `cash_account_log_201608` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL,
  `item_type` tinyint(1) unsigned NOT NULL COMMENT '资金变动项类型。',
  `amount` int(11) NOT NULL,
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `order_id` varchar(25) NOT NULL DEFAULT '0' COMMENT '订单号',
  `related_uid` int(10) unsigned NOT NULL DEFAULT '0',
  `related_id` varchar(35) NOT NULL DEFAULT '0' COMMENT '关联id',
  `remark` text,
  PRIMARY KEY (`id`),
  KEY `create_time` (`create_time`),
  KEY `item_type` (`item_type`),
  KEY `order_id` (`order_id`),
  KEY `related_uid` (`related_uid`),
  KEY `uid` (`uid`,`item_type`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=4358312 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for cash_account_log_201609
-- ----------------------------
DROP TABLE IF EXISTS `cash_account_log_201609`;
CREATE TABLE `cash_account_log_201609` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL,
  `item_type` tinyint(1) unsigned NOT NULL COMMENT '资金变动项类型。',
  `amount` int(11) NOT NULL,
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `order_id` varchar(25) NOT NULL DEFAULT '0' COMMENT '订单号',
  `related_uid` int(10) unsigned NOT NULL DEFAULT '0',
  `related_id` varchar(35) NOT NULL DEFAULT '0' COMMENT '关联id',
  `remark` text,
  PRIMARY KEY (`id`),
  KEY `create_time` (`create_time`),
  KEY `item_type` (`item_type`),
  KEY `order_id` (`order_id`),
  KEY `related_uid` (`related_uid`),
  KEY `uid` (`uid`,`item_type`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=5133722 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for cash_account_log_201610
-- ----------------------------
DROP TABLE IF EXISTS `cash_account_log_201610`;
CREATE TABLE `cash_account_log_201610` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL,
  `item_type` tinyint(1) unsigned NOT NULL COMMENT '资金变动项类型。',
  `amount` int(11) NOT NULL,
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `order_id` varchar(25) NOT NULL DEFAULT '0' COMMENT '订单号',
  `related_uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '相关uid',
  `related_id` varchar(35) NOT NULL DEFAULT '0' COMMENT '关联id',
  `remark` text,
  PRIMARY KEY (`id`),
  KEY `create_time` (`create_time`),
  KEY `item_type` (`item_type`),
  KEY `order_id` (`order_id`),
  KEY `related_uid` (`related_uid`),
  KEY `uid` (`uid`,`item_type`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=11415830 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for cash_account_log_201611
-- ----------------------------
DROP TABLE IF EXISTS `cash_account_log_201611`;
CREATE TABLE `cash_account_log_201611` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL,
  `item_type` tinyint(1) unsigned NOT NULL COMMENT '资金变动项类型。',
  `amount` int(11) NOT NULL,
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `order_id` varchar(25) NOT NULL DEFAULT '0' COMMENT '订单号',
  `related_uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '相关uid',
  `related_id` varchar(35) NOT NULL DEFAULT '0' COMMENT '关联id',
  `remark` text,
  PRIMARY KEY (`id`),
  KEY `create_time` (`create_time`),
  KEY `item_type` (`item_type`),
  KEY `order_id` (`order_id`),
  KEY `related_uid` (`related_uid`),
  KEY `uid` (`uid`,`item_type`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=9098079 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for cash_account_log_201612
-- ----------------------------
DROP TABLE IF EXISTS `cash_account_log_201612`;
CREATE TABLE `cash_account_log_201612` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL,
  `item_type` tinyint(1) unsigned NOT NULL COMMENT '资金变动项类型。',
  `amount` int(11) NOT NULL,
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `order_id` varchar(25) NOT NULL DEFAULT '0' COMMENT '订单号',
  `related_uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '相关uid',
  `related_id` varchar(35) NOT NULL DEFAULT '0' COMMENT '关联id',
  `remark` text,
  PRIMARY KEY (`id`),
  KEY `create_time` (`create_time`),
  KEY `item_type` (`item_type`),
  KEY `order_id` (`order_id`),
  KEY `related_uid` (`related_uid`),
  KEY `uid` (`uid`,`item_type`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=17837651 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for cash_account_log_201701
-- ----------------------------
DROP TABLE IF EXISTS `cash_account_log_201701`;
CREATE TABLE `cash_account_log_201701` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL,
  `item_type` tinyint(1) unsigned NOT NULL COMMENT '资金变动项类型。',
  `amount` int(11) NOT NULL,
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `order_id` varchar(25) NOT NULL DEFAULT '0' COMMENT '订单号',
  `related_uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '相关uid',
  `related_id` varchar(35) NOT NULL DEFAULT '0' COMMENT '关联id',
  `remark` text,
  PRIMARY KEY (`id`),
  KEY `create_time` (`create_time`),
  KEY `item_type` (`item_type`),
  KEY `order_id` (`order_id`),
  KEY `related_uid` (`related_uid`),
  KEY `uid` (`uid`,`item_type`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=34920182 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for cash_account_log_201702
-- ----------------------------
DROP TABLE IF EXISTS `cash_account_log_201702`;
CREATE TABLE `cash_account_log_201702` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL,
  `item_type` tinyint(1) unsigned NOT NULL COMMENT '资金变动项类型。',
  `amount` int(11) NOT NULL,
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `order_id` varchar(25) NOT NULL DEFAULT '0' COMMENT '订单号',
  `related_uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '相关uid',
  `related_id` varchar(35) NOT NULL DEFAULT '0' COMMENT '关联id',
  `remark` text,
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  KEY `related_uid` (`related_uid`),
  KEY `uid` (`uid`,`item_type`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3140209275 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for cash_account_log_201703
-- ----------------------------
DROP TABLE IF EXISTS `cash_account_log_201703`;
CREATE TABLE `cash_account_log_201703` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL,
  `item_type` tinyint(1) unsigned NOT NULL COMMENT '资金变动项类型。',
  `amount` int(11) NOT NULL,
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `order_id` varchar(25) NOT NULL DEFAULT '0' COMMENT '订单号',
  `related_uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '相关uid',
  `related_id` varchar(35) NOT NULL DEFAULT '0' COMMENT '关联id',
  `remark` text,
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  KEY `related_uid` (`related_uid`),
  KEY `uid` (`uid`,`item_type`) USING BTREE,
  KEY `item_type` (`item_type`,`create_time`) USING BTREE,
  KEY `create_time` (`create_time`)
) ENGINE=InnoDB AUTO_INCREMENT=457783961 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for cash_account_log_201704
-- ----------------------------
DROP TABLE IF EXISTS `cash_account_log_201704`;
CREATE TABLE `cash_account_log_201704` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL,
  `item_type` tinyint(1) unsigned NOT NULL COMMENT '资金变动项类型。',
  `amount` int(11) NOT NULL,
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `order_id` varchar(25) NOT NULL DEFAULT '0' COMMENT '订单号',
  `related_uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '相关uid',
  `related_id` varchar(35) NOT NULL DEFAULT '0' COMMENT '关联id',
  `remark` text,
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`) USING HASH,
  KEY `related_uid` (`related_uid`) USING HASH,
  KEY `uid` (`uid`,`item_type`) USING BTREE,
  KEY `item_type` (`item_type`,`create_time`) USING BTREE,
  KEY `create_time` (`create_time`)
) ENGINE=InnoDB AUTO_INCREMENT=4294967295 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for cash_account_log_201705
-- ----------------------------
DROP TABLE IF EXISTS `cash_account_log_201705`;
CREATE TABLE `cash_account_log_201705` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL,
  `item_type` tinyint(1) unsigned NOT NULL COMMENT '资金变动项类型。',
  `amount` int(11) NOT NULL,
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `order_id` varchar(25) NOT NULL DEFAULT '0' COMMENT '订单号',
  `related_uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '相关uid',
  `related_id` varchar(35) NOT NULL DEFAULT '0' COMMENT '关联id',
  `remark` text,
  PRIMARY KEY (`id`),
  KEY `create_time` (`create_time`),
  KEY `order_id` (`order_id`) USING HASH,
  KEY `related_uid` (`related_uid`) USING HASH,
  KEY `item_type` (`item_type`,`create_time`) USING BTREE,
  KEY `uid` (`uid`,`item_type`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=573211538 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for cash_account_log_201706
-- ----------------------------
DROP TABLE IF EXISTS `cash_account_log_201706`;
CREATE TABLE `cash_account_log_201706` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL,
  `item_type` tinyint(1) unsigned NOT NULL COMMENT '资金变动项类型。',
  `amount` int(11) NOT NULL,
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `order_id` varchar(25) NOT NULL DEFAULT '0' COMMENT '订单号',
  `related_uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '相关uid',
  `related_id` varchar(35) NOT NULL DEFAULT '0' COMMENT '关联id',
  `remark` text,
  PRIMARY KEY (`id`),
  KEY `create_time` (`create_time`),
  KEY `uid` (`uid`) USING HASH,
  KEY `item_type` (`item_type`) USING HASH,
  KEY `order_id` (`order_id`) USING HASH,
  KEY `related_uid` (`related_uid`) USING HASH
) ENGINE=InnoDB AUTO_INCREMENT=95378752 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for cash_account_log_201707
-- ----------------------------
DROP TABLE IF EXISTS `cash_account_log_201707`;
CREATE TABLE `cash_account_log_201707` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL,
  `item_type` tinyint(1) unsigned NOT NULL COMMENT '资金变动项类型。',
  `amount` int(11) NOT NULL,
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `order_id` varchar(25) NOT NULL DEFAULT '0' COMMENT '订单号',
  `related_uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '相关uid',
  `related_id` varchar(35) NOT NULL DEFAULT '0' COMMENT '关联id',
  `remark` text,
  PRIMARY KEY (`id`),
  KEY `create_time` (`create_time`),
  KEY `uid` (`uid`) USING HASH,
  KEY `item_type` (`item_type`) USING HASH,
  KEY `order_id` (`order_id`) USING HASH,
  KEY `related_uid` (`related_uid`) USING HASH
) ENGINE=InnoDB AUTO_INCREMENT=58427558 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for cash_account_log_201707_1380
-- ----------------------------
DROP TABLE IF EXISTS `cash_account_log_201707_1380`;
CREATE TABLE `cash_account_log_201707_1380` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '流水ID',
  `uid` int(11) unsigned NOT NULL COMMENT '用户ID',
  `item_type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '16[佣金抽回], 5[个人销售提成],17[佣金转分红点],3[团队销售提成],11[用户转账],其他值待补全',
  `amount` int(11) NOT NULL DEFAULT '0' COMMENT '表报金额',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `order_id` char(25) NOT NULL DEFAULT '' COMMENT '订单ID',
  `related_uid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '关联用户ID',
  `before_amount` int(11) NOT NULL DEFAULT '0' COMMENT '变动前帐户余额',
  `after_amount` int(11) NOT NULL DEFAULT '0' COMMENT '变动后帐户余额',
  PRIMARY KEY (`id`),
  KEY `IDX_create_time` (`create_time`),
  KEY `IDX_uid` (`uid`),
  KEY `IDX_item_type` (`item_type`),
  KEY `IDX_order_id` (`order_id`),
  KEY `IDX_related_uid` (`related_uid`)
) ENGINE=InnoDB AUTO_INCREMENT=18256825 DEFAULT CHARSET=utf8 COMMENT='资金变动报表';

-- ----------------------------
-- Table structure for cash_account_log_201707_1381
-- ----------------------------
DROP TABLE IF EXISTS `cash_account_log_201707_1381`;
CREATE TABLE `cash_account_log_201707_1381` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '流水ID',
  `uid` int(11) unsigned NOT NULL COMMENT '用户ID',
  `item_type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '16[佣金抽回], 5[个人销售提成],17[佣金转分红点],3[团队销售提成],11[用户转账],其他值待补全',
  `amount` int(11) NOT NULL DEFAULT '0' COMMENT '表报金额',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `order_id` char(25) NOT NULL DEFAULT '' COMMENT '订单ID',
  `related_uid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '关联用户ID',
  `before_amount` int(11) NOT NULL DEFAULT '0' COMMENT '变动前帐户余额',
  `after_amount` int(11) NOT NULL DEFAULT '0' COMMENT '变动后帐户余额',
  PRIMARY KEY (`id`),
  KEY `IDX_create_time` (`create_time`),
  KEY `IDX_uid` (`uid`),
  KEY `IDX_item_type` (`item_type`),
  KEY `IDX_order_id` (`order_id`),
  KEY `IDX_related_uid` (`related_uid`)
) ENGINE=InnoDB AUTO_INCREMENT=15249682 DEFAULT CHARSET=utf8 COMMENT='资金变动报表';

-- ----------------------------
-- Table structure for cash_account_log_201707_1382
-- ----------------------------
DROP TABLE IF EXISTS `cash_account_log_201707_1382`;
CREATE TABLE `cash_account_log_201707_1382` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '流水ID',
  `uid` int(11) unsigned NOT NULL COMMENT '用户ID',
  `item_type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '16[佣金抽回], 5[个人销售提成],17[佣金转分红点],3[团队销售提成],11[用户转账],其他值待补全',
  `amount` int(11) NOT NULL DEFAULT '0' COMMENT '表报金额',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `order_id` char(25) NOT NULL DEFAULT '' COMMENT '订单ID',
  `related_uid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '关联用户ID',
  `before_amount` int(11) NOT NULL DEFAULT '0' COMMENT '变动前帐户余额',
  `after_amount` int(11) NOT NULL DEFAULT '0' COMMENT '变动后帐户余额',
  PRIMARY KEY (`id`),
  KEY `IDX_create_time` (`create_time`),
  KEY `IDX_uid` (`uid`),
  KEY `IDX_item_type` (`item_type`),
  KEY `IDX_order_id` (`order_id`),
  KEY `IDX_related_uid` (`related_uid`)
) ENGINE=InnoDB AUTO_INCREMENT=11682773 DEFAULT CHARSET=utf8 COMMENT='资金变动报表';

-- ----------------------------
-- Table structure for cash_account_log_201707_1383
-- ----------------------------
DROP TABLE IF EXISTS `cash_account_log_201707_1383`;
CREATE TABLE `cash_account_log_201707_1383` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '流水ID',
  `uid` int(11) unsigned NOT NULL COMMENT '用户ID',
  `item_type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '16[佣金抽回], 5[个人销售提成],17[佣金转分红点],3[团队销售提成],11[用户转账],其他值待补全',
  `amount` int(11) NOT NULL DEFAULT '0' COMMENT '表报金额',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `order_id` char(25) NOT NULL DEFAULT '' COMMENT '订单ID',
  `related_uid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '关联用户ID',
  `before_amount` int(11) NOT NULL DEFAULT '0' COMMENT '变动前帐户余额',
  `after_amount` int(11) NOT NULL DEFAULT '0' COMMENT '变动后帐户余额',
  PRIMARY KEY (`id`),
  KEY `IDX_create_time` (`create_time`),
  KEY `IDX_uid` (`uid`),
  KEY `IDX_item_type` (`item_type`),
  KEY `IDX_order_id` (`order_id`),
  KEY `IDX_related_uid` (`related_uid`)
) ENGINE=InnoDB AUTO_INCREMENT=12559091 DEFAULT CHARSET=utf8 COMMENT='资金变动报表';

-- ----------------------------
-- Table structure for cash_account_log_201707_1384
-- ----------------------------
DROP TABLE IF EXISTS `cash_account_log_201707_1384`;
CREATE TABLE `cash_account_log_201707_1384` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '流水ID',
  `uid` int(11) unsigned NOT NULL COMMENT '用户ID',
  `item_type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '16[佣金抽回], 5[个人销售提成],17[佣金转分红点],3[团队销售提成],11[用户转账],其他值待补全',
  `amount` int(11) NOT NULL DEFAULT '0' COMMENT '表报金额',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `order_id` char(25) NOT NULL DEFAULT '' COMMENT '订单ID',
  `related_uid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '关联用户ID',
  `before_amount` int(11) NOT NULL DEFAULT '0' COMMENT '变动前帐户余额',
  `after_amount` int(11) NOT NULL DEFAULT '0' COMMENT '变动后帐户余额',
  PRIMARY KEY (`id`),
  KEY `IDX_create_time` (`create_time`),
  KEY `IDX_uid` (`uid`),
  KEY `IDX_item_type` (`item_type`),
  KEY `IDX_order_id` (`order_id`),
  KEY `IDX_related_uid` (`related_uid`)
) ENGINE=InnoDB AUTO_INCREMENT=679158 DEFAULT CHARSET=utf8 COMMENT='资金变动报表';

-- ----------------------------
-- Table structure for cash_account_log_201707_1385
-- ----------------------------
DROP TABLE IF EXISTS `cash_account_log_201707_1385`;
CREATE TABLE `cash_account_log_201707_1385` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '流水ID',
  `uid` int(11) unsigned NOT NULL COMMENT '用户ID',
  `item_type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '16[佣金抽回], 5[个人销售提成],17[佣金转分红点],3[团队销售提成],11[用户转账],其他值待补全',
  `amount` int(11) NOT NULL DEFAULT '0' COMMENT '表报金额',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `order_id` char(25) NOT NULL DEFAULT '' COMMENT '订单ID',
  `related_uid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '关联用户ID',
  `before_amount` int(11) NOT NULL DEFAULT '0' COMMENT '变动前帐户余额',
  `after_amount` int(11) NOT NULL DEFAULT '0' COMMENT '变动后帐户余额',
  PRIMARY KEY (`id`),
  KEY `IDX_create_time` (`create_time`),
  KEY `IDX_uid` (`uid`),
  KEY `IDX_item_type` (`item_type`),
  KEY `IDX_order_id` (`order_id`),
  KEY `IDX_related_uid` (`related_uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='资金变动报表';

-- ----------------------------
-- Table structure for cash_account_log_201708
-- ----------------------------
DROP TABLE IF EXISTS `cash_account_log_201708`;
CREATE TABLE `cash_account_log_201708` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL,
  `item_type` tinyint(1) unsigned NOT NULL COMMENT '资金变动项类型。',
  `amount` int(11) NOT NULL,
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `order_id` varchar(25) NOT NULL DEFAULT '0' COMMENT '订单号',
  `related_uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '相关uid',
  `related_id` varchar(35) NOT NULL DEFAULT '0' COMMENT '关联id',
  `remark` text,
  PRIMARY KEY (`id`),
  KEY `create_time` (`create_time`),
  KEY `uid` (`uid`) USING HASH,
  KEY `item_type` (`item_type`) USING HASH,
  KEY `order_id` (`order_id`) USING HASH,
  KEY `related_uid` (`related_uid`) USING HASH
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for cash_account_log_201708_1380
-- ----------------------------
DROP TABLE IF EXISTS `cash_account_log_201708_1380`;
CREATE TABLE `cash_account_log_201708_1380` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '流水ID',
  `uid` int(11) unsigned NOT NULL COMMENT '用户ID',
  `item_type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '16[佣金抽回], 5[个人销售提成],17[佣金转分红点],3[团队销售提成],11[用户转账],其他值待补全',
  `amount` int(11) NOT NULL DEFAULT '0' COMMENT '表报金额',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `order_id` char(25) NOT NULL DEFAULT '' COMMENT '订单ID',
  `related_uid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '关联用户ID',
  `before_amount` int(11) NOT NULL DEFAULT '0' COMMENT '变动前帐户余额',
  `after_amount` int(11) NOT NULL DEFAULT '0' COMMENT '变动后帐户余额',
  PRIMARY KEY (`id`),
  KEY `IDX_create_time` (`create_time`),
  KEY `IDX_uid` (`uid`),
  KEY `IDX_item_type` (`item_type`),
  KEY `IDX_order_id` (`order_id`),
  KEY `IDX_related_uid` (`related_uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='资金变动报表';

-- ----------------------------
-- Table structure for cash_account_log_201708_1381
-- ----------------------------
DROP TABLE IF EXISTS `cash_account_log_201708_1381`;
CREATE TABLE `cash_account_log_201708_1381` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '流水ID',
  `uid` int(11) unsigned NOT NULL COMMENT '用户ID',
  `item_type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '16[佣金抽回], 5[个人销售提成],17[佣金转分红点],3[团队销售提成],11[用户转账],其他值待补全',
  `amount` int(11) NOT NULL DEFAULT '0' COMMENT '表报金额',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `order_id` char(25) NOT NULL DEFAULT '' COMMENT '订单ID',
  `related_uid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '关联用户ID',
  `before_amount` int(11) NOT NULL DEFAULT '0' COMMENT '变动前帐户余额',
  `after_amount` int(11) NOT NULL DEFAULT '0' COMMENT '变动后帐户余额',
  PRIMARY KEY (`id`),
  KEY `IDX_create_time` (`create_time`),
  KEY `IDX_uid` (`uid`),
  KEY `IDX_item_type` (`item_type`),
  KEY `IDX_order_id` (`order_id`),
  KEY `IDX_related_uid` (`related_uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='资金变动报表';

-- ----------------------------
-- Table structure for cash_account_log_201708_1382
-- ----------------------------
DROP TABLE IF EXISTS `cash_account_log_201708_1382`;
CREATE TABLE `cash_account_log_201708_1382` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '流水ID',
  `uid` int(11) unsigned NOT NULL COMMENT '用户ID',
  `item_type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '16[佣金抽回], 5[个人销售提成],17[佣金转分红点],3[团队销售提成],11[用户转账],其他值待补全',
  `amount` int(11) NOT NULL DEFAULT '0' COMMENT '表报金额',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `order_id` char(25) NOT NULL DEFAULT '' COMMENT '订单ID',
  `related_uid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '关联用户ID',
  `before_amount` int(11) NOT NULL DEFAULT '0' COMMENT '变动前帐户余额',
  `after_amount` int(11) NOT NULL DEFAULT '0' COMMENT '变动后帐户余额',
  PRIMARY KEY (`id`),
  KEY `IDX_create_time` (`create_time`),
  KEY `IDX_uid` (`uid`),
  KEY `IDX_item_type` (`item_type`),
  KEY `IDX_order_id` (`order_id`),
  KEY `IDX_related_uid` (`related_uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='资金变动报表';

-- ----------------------------
-- Table structure for cash_account_log_201708_1383
-- ----------------------------
DROP TABLE IF EXISTS `cash_account_log_201708_1383`;
CREATE TABLE `cash_account_log_201708_1383` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '流水ID',
  `uid` int(11) unsigned NOT NULL COMMENT '用户ID',
  `item_type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '16[佣金抽回], 5[个人销售提成],17[佣金转分红点],3[团队销售提成],11[用户转账],其他值待补全',
  `amount` int(11) NOT NULL DEFAULT '0' COMMENT '表报金额',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `order_id` char(25) NOT NULL DEFAULT '' COMMENT '订单ID',
  `related_uid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '关联用户ID',
  `before_amount` int(11) NOT NULL DEFAULT '0' COMMENT '变动前帐户余额',
  `after_amount` int(11) NOT NULL DEFAULT '0' COMMENT '变动后帐户余额',
  PRIMARY KEY (`id`),
  KEY `IDX_create_time` (`create_time`),
  KEY `IDX_uid` (`uid`),
  KEY `IDX_item_type` (`item_type`),
  KEY `IDX_order_id` (`order_id`),
  KEY `IDX_related_uid` (`related_uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='资金变动报表';

-- ----------------------------
-- Table structure for cash_account_log_201708_1384
-- ----------------------------
DROP TABLE IF EXISTS `cash_account_log_201708_1384`;
CREATE TABLE `cash_account_log_201708_1384` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '流水ID',
  `uid` int(11) unsigned NOT NULL COMMENT '用户ID',
  `item_type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '16[佣金抽回], 5[个人销售提成],17[佣金转分红点],3[团队销售提成],11[用户转账],其他值待补全',
  `amount` int(11) NOT NULL DEFAULT '0' COMMENT '表报金额',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `order_id` char(25) NOT NULL DEFAULT '' COMMENT '订单ID',
  `related_uid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '关联用户ID',
  `before_amount` int(11) NOT NULL DEFAULT '0' COMMENT '变动前帐户余额',
  `after_amount` int(11) NOT NULL DEFAULT '0' COMMENT '变动后帐户余额',
  PRIMARY KEY (`id`),
  KEY `IDX_create_time` (`create_time`),
  KEY `IDX_uid` (`uid`),
  KEY `IDX_item_type` (`item_type`),
  KEY `IDX_order_id` (`order_id`),
  KEY `IDX_related_uid` (`related_uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='资金变动报表';

-- ----------------------------
-- Table structure for cash_account_log_201708_1385
-- ----------------------------
DROP TABLE IF EXISTS `cash_account_log_201708_1385`;
CREATE TABLE `cash_account_log_201708_1385` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '流水ID',
  `uid` int(11) unsigned NOT NULL COMMENT '用户ID',
  `item_type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '16[佣金抽回], 5[个人销售提成],17[佣金转分红点],3[团队销售提成],11[用户转账],其他值待补全',
  `amount` int(11) NOT NULL DEFAULT '0' COMMENT '表报金额',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `order_id` char(25) NOT NULL DEFAULT '' COMMENT '订单ID',
  `related_uid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '关联用户ID',
  `before_amount` int(11) NOT NULL DEFAULT '0' COMMENT '变动前帐户余额',
  `after_amount` int(11) NOT NULL DEFAULT '0' COMMENT '变动后帐户余额',
  PRIMARY KEY (`id`),
  KEY `IDX_create_time` (`create_time`),
  KEY `IDX_uid` (`uid`),
  KEY `IDX_item_type` (`item_type`),
  KEY `IDX_order_id` (`order_id`),
  KEY `IDX_related_uid` (`related_uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='资金变动报表';

-- ----------------------------
-- Table structure for cash_account_log_info
-- ----------------------------
DROP TABLE IF EXISTS `cash_account_log_info`;
CREATE TABLE `cash_account_log_info` (
  `info_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `id` varchar(35) NOT NULL COMMENT '报表流水ID',
  `related_id` varchar(40) DEFAULT NULL COMMENT '自关联报表流水ID',
  `remark` text COMMENT '备注',
  PRIMARY KEY (`info_id`),
  UNIQUE KEY `UQE_id` (`id`,`related_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=46081 DEFAULT CHARSET=utf8 COMMENT='资金报表详情表';

-- ----------------------------
-- Table structure for cash_paypal_take_out_batch_tb
-- ----------------------------
DROP TABLE IF EXISTS `cash_paypal_take_out_batch_tb`;
CREATE TABLE `cash_paypal_take_out_batch_tb` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `batch_num` varchar(20) NOT NULL DEFAULT '0' COMMENT '批次号',
  `total` int(11) NOT NULL DEFAULT '0' COMMENT '总数',
  `lump_sum` decimal(14,2) NOT NULL DEFAULT '0.00' COMMENT '总金额',
  `reason` int(1) NOT NULL DEFAULT '0' COMMENT '付款理由（1货款，2运费，3饭钱,4销售佣金）',
  `status` int(1) NOT NULL DEFAULT '1' COMMENT '状态（1待处理，2处理中，3处理完成）',
  `born_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `process_time` timestamp NULL DEFAULT NULL,
  `failure` int(5) DEFAULT '0' COMMENT '失败数',
  `success` int(5) DEFAULT '0' COMMENT '成功数',
  `exchange_rate` decimal(10,2) DEFAULT NULL COMMENT '汇率',
  `handle_fee` decimal(14,2) NOT NULL DEFAULT '0.00' COMMENT '手续费',
  `actual_amount` decimal(14,2) NOT NULL DEFAULT '0.00' COMMENT '实际到账金额',
  `operator` int(11) DEFAULT NULL COMMENT '提交paypal的操作者',
  PRIMARY KEY (`id`),
  KEY `batch_num` (`batch_num`),
  KEY `status` (`status`),
  KEY `born_time` (`born_time`)
) ENGINE=InnoDB AUTO_INCREMENT=132 DEFAULT CHARSET=utf8 COMMENT='支付宝提现批次表（佣金）';

-- ----------------------------
-- Table structure for cash_take_out_batch_tb
-- ----------------------------
DROP TABLE IF EXISTS `cash_take_out_batch_tb`;
CREATE TABLE `cash_take_out_batch_tb` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `batch_num` varchar(20) NOT NULL DEFAULT '0' COMMENT '批次号',
  `total` int(11) NOT NULL DEFAULT '0' COMMENT '总数',
  `lump_sum` decimal(14,2) NOT NULL DEFAULT '0.00' COMMENT '总金额',
  `reason` int(1) NOT NULL DEFAULT '0' COMMENT '付款理由（1货款，2运费，3饭钱,4销售佣金）',
  `status` int(1) NOT NULL DEFAULT '1' COMMENT '状态（1待处理，2处理中，3处理完成）',
  `born_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `process_time` timestamp NULL DEFAULT NULL,
  `failure` int(5) DEFAULT '0' COMMENT '失败数',
  `success` int(5) DEFAULT '0' COMMENT '成功数',
  `exchange_rate` decimal(10,2) DEFAULT NULL COMMENT '汇率',
  PRIMARY KEY (`id`),
  KEY `batch_num` (`batch_num`),
  KEY `status` (`status`),
  KEY `born_time` (`born_time`)
) ENGINE=InnoDB AUTO_INCREMENT=337 DEFAULT CHARSET=utf8 COMMENT='支付宝提现批次表（佣金）';

-- ----------------------------
-- Table structure for cash_take_out_logs
-- ----------------------------
DROP TABLE IF EXISTS `cash_take_out_logs`;
CREATE TABLE `cash_take_out_logs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `amount` decimal(14,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '提现金额',
  `handle_fee` decimal(14,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '手续费',
  `actual_amount` decimal(14,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '实际到帐金额',
  `take_out_type` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '提现方式1：银行卡，2支付宝',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0:未处理，1，已处理,2,处理中，3驳回 ，4:已取消',
  `check_info` text COMMENT '失敗原因',
  `check_admin` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '处理人',
  `check_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '处理时间',
  `account_bank` varchar(100) NOT NULL DEFAULT '0' COMMENT '开户行',
  `account_name` varchar(100) NOT NULL DEFAULT '0' COMMENT '开户名',
  `card_number` varchar(100) NOT NULL DEFAULT '0' COMMENT '银行卡号',
  `remark` text COMMENT '备注',
  `subbranch_bank` varchar(100) NOT NULL DEFAULT '0' COMMENT '支行名稱',
  `batch_num` varchar(20) DEFAULT NULL COMMENT '支付宝处理内部流水号',
  `process_num` varchar(50) DEFAULT NULL COMMENT '支付宝处理内部流水号',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `take_out_type` (`take_out_type`),
  KEY `create_time` (`create_time`),
  KEY `card_number` (`card_number`),
  KEY `account_name` (`account_name`),
  KEY `status` (`status`),
  KEY `batch_num` (`batch_num`)
) ENGINE=InnoDB AUTO_INCREMENT=14789617 DEFAULT CHARSET=utf8 COMMENT='提现记录';

-- ----------------------------
-- Table structure for cash_to_month_fee_logs
-- ----------------------------
DROP TABLE IF EXISTS `cash_to_month_fee_logs`;
CREATE TABLE `cash_to_month_fee_logs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `amount` decimal(14,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '金额',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`,`create_time`)
) ENGINE=InnoDB AUTO_INCREMENT=212553 DEFAULT CHARSET=utf8 COMMENT='现金池转月费池记录';

-- ----------------------------
-- Table structure for ci_sessions
-- ----------------------------
DROP TABLE IF EXISTS `ci_sessions`;
CREATE TABLE `ci_sessions` (
  `session_id` varchar(40) NOT NULL DEFAULT '0',
  `ip_address` varchar(45) NOT NULL DEFAULT '0',
  `user_agent` varchar(120) NOT NULL,
  `last_activity` int(10) unsigned NOT NULL DEFAULT '0',
  `user_data` text NOT NULL,
  PRIMARY KEY (`session_id`),
  KEY `last_activity_idx` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='会话信息保存（已弃用）';

-- ----------------------------
-- Table structure for commission_detail_logs
-- ----------------------------
DROP TABLE IF EXISTS `commission_detail_logs`;
CREATE TABLE `commission_detail_logs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `type` varchar(255) NOT NULL DEFAULT '0' COMMENT '1=>2*5见点佣金；2=>138见点佣金；3=>团队销售佣金；4=>团队无限代；5=>个人店铺销售佣金；6=>周分红；7=>周领导对等奖;8=>月领导分红奖，14=>月费池转现金池',
  `amount` decimal(14,2) NOT NULL DEFAULT '0.00' COMMENT '佣金金额(美元)',
  `old_amount` decimal(14,2) NOT NULL DEFAULT '0.00',
  `new_amount` decimal(14,2) NOT NULL DEFAULT '0.00',
  `order_id` varchar(20) NOT NULL DEFAULT '0' COMMENT '订单号',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `pay_user_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4020 DEFAULT CHARSET=utf8 COMMENT='佣金日志';

-- ----------------------------
-- Table structure for commission_logs
-- ----------------------------
DROP TABLE IF EXISTS `commission_logs`;
CREATE TABLE `commission_logs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '1=>2*5见点佣金；2=>138见点佣金；3=>团队销售佣金；4=>团队无限代；5=>个人店铺销售佣金；6=>周分红；7=>周领导对等奖;8=>月领导分红奖，14=>月费池转现金池',
  `amount` decimal(14,2) NOT NULL DEFAULT '0.00' COMMENT '佣金金额(美元)',
  `order_id` varchar(20) NOT NULL DEFAULT '0' COMMENT '订单号',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `pay_user_id` int(11) NOT NULL DEFAULT '0',
  `related_id` varchar(35) NOT NULL DEFAULT '0',
  `remark` text COMMENT '佣金备注',
  PRIMARY KEY (`id`),
  KEY `create_time` (`create_time`),
  KEY `type` (`type`),
  KEY `order_id` (`order_id`),
  KEY `pay_user_id` (`pay_user_id`),
  KEY `amount` (`amount`),
  KEY `uid` (`uid`,`create_time`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=25226423 DEFAULT CHARSET=utf8 COMMENT='佣金日志';

-- ----------------------------
-- Table structure for company_calc_monthly
-- ----------------------------
DROP TABLE IF EXISTS `company_calc_monthly`;
CREATE TABLE `company_calc_monthly` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `country_id` smallint(11) NOT NULL DEFAULT '1' COMMENT '区域码 与用户表的字段一致',
  `year_month` int(11) NOT NULL DEFAULT '0' COMMENT '201506',
  `goods_amount` bigint(20) NOT NULL DEFAULT '0' COMMENT '订单收入（商品金额）(美分)',
  `month_fee` bigint(20) NOT NULL DEFAULT '0' COMMENT '月费收入(美分)',
  `cost_price` bigint(20) NOT NULL DEFAULT '0' COMMENT '商品总成本价(美分)',
  `operating_cost` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '运营成本(美分)',
  `bonus` bigint(20) NOT NULL DEFAULT '0' COMMENT '会员佣金支出',
  `upate_time` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uni_country_id_year_month` (`country_id`,`year_month`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8 COMMENT='公司每月统计\r\n区域代码   年月    订单收入（商品金额）    月费收入    商品总成本价   运营成本（商品金额 * 5%）   会员佣金支出\r\n';

-- ----------------------------
-- Table structure for company_money_today_total
-- ----------------------------
DROP TABLE IF EXISTS `company_money_today_total`;
CREATE TABLE `company_money_today_total` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `money` decimal(20,0) NOT NULL DEFAULT '0' COMMENT '每天全球利润',
  `mall_orders` decimal(20,0) NOT NULL DEFAULT '0' COMMENT '沃好订单利润',
  `one_direct_orders` decimal(20,0) NOT NULL DEFAULT '0' COMMENT '美国订单',
  `trade_orders` decimal(20,0) NOT NULL DEFAULT '0' COMMENT 'tps商城订单利润(个人+套餐) 美分',
  `walmart_orders` decimal(20,0) NOT NULL DEFAULT '0' COMMENT '沃尔玛订单',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `create_time` (`create_time`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=383 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for config_site
-- ----------------------------
DROP TABLE IF EXISTS `config_site`;
CREATE TABLE `config_site` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL DEFAULT '0',
  `value` varchar(120) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='站点配置表';

-- ----------------------------
-- Table structure for country
-- ----------------------------
DROP TABLE IF EXISTS `country`;
CREATE TABLE `country` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `region_name` varchar(55) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT '全称',
  `short_name` char(3) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT '2位的简称',
  `zh_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT '国家中文名字',
  `region_id` tinyint(3) NOT NULL DEFAULT '0' COMMENT '0,一级',
  `parent_id` tinyint(3) NOT NULL DEFAULT '0' COMMENT '0:默认',
  `angency_id` tinyint(3) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Table structure for cron_doing
-- ----------------------------
DROP TABLE IF EXISTS `cron_doing`;
CREATE TABLE `cron_doing` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `cron_name` varchar(100) NOT NULL DEFAULT '' COMMENT '计划任务名称',
  `false_count` int(11) DEFAULT '0' COMMENT 'return false 次数',
  PRIMARY KEY (`id`),
  UNIQUE KEY `cron_name` (`cron_name`)
) ENGINE=InnoDB AUTO_INCREMENT=5968590 DEFAULT CHARSET=utf8 COMMENT='正在执行中的计划任务表。';

-- ----------------------------
-- Table structure for cron_status
-- ----------------------------
DROP TABLE IF EXISTS `cron_status`;
CREATE TABLE `cron_status` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `type` varchar(255) NOT NULL DEFAULT 'new_member_bonus' COMMENT '执行类型',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1执行中 2 执行成功 3执行失败',
  `cron_day` int(11) NOT NULL DEFAULT '0' COMMENT '统计的哪天的 20170418',
  `content` varchar(255) NOT NULL DEFAULT '' COMMENT '说明',
  `create_time` datetime NOT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `type` (`type`) USING BTREE COMMENT '类型'
) ENGINE=InnoDB AUTO_INCREMENT=669 DEFAULT CHARSET=utf8 COMMENT='队列执行状态表';

-- ----------------------------
-- Table structure for daily_bonus_elite
-- ----------------------------
DROP TABLE IF EXISTS `daily_bonus_elite`;
CREATE TABLE `daily_bonus_elite` (
  `uid` int(11) NOT NULL DEFAULT '0',
  `sale_amount` int(10) unsigned DEFAULT NULL COMMENT '销售额（单位：分）',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for daily_bonus_elite_qualified_list
-- ----------------------------
DROP TABLE IF EXISTS `daily_bonus_elite_qualified_list`;
CREATE TABLE `daily_bonus_elite_qualified_list` (
  `uid` int(10) unsigned NOT NULL,
  `bonus_shar_weight` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分红权重（套餐订单+普通订单 的销售额，单位：分）',
  `qualified_day` int(8) unsigned NOT NULL DEFAULT '0' COMMENT '合格日期：20161001',
  PRIMARY KEY (`uid`),
  KEY `qualified_day` (`qualified_day`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='精英日分红合格列表';

-- ----------------------------
-- Table structure for daily_bonus_qualified_list
-- ----------------------------
DROP TABLE IF EXISTS `daily_bonus_qualified_list`;
CREATE TABLE `daily_bonus_qualified_list` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `qualified_day` int(8) unsigned NOT NULL DEFAULT '0' COMMENT '合格日期，如：20160803，如果是月初统计上月合格的则为默认值0',
  `amount` bigint(20) NOT NULL DEFAULT '0' COMMENT '用户上月的金额',
  `user_rank` tinyint(2) NOT NULL DEFAULT '0' COMMENT '用户等级',
  PRIMARY KEY (`uid`),
  KEY `qualified_day` (`qualified_day`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='日分红的合格人员列表';

-- ----------------------------
-- Table structure for debug_logs
-- ----------------------------
DROP TABLE IF EXISTS `debug_logs`;
CREATE TABLE `debug_logs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `content` text,
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=143534 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for debug_rx
-- ----------------------------
DROP TABLE IF EXISTS `debug_rx`;
CREATE TABLE `debug_rx` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `content` text,
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `content1` text COMMENT '自定义字段',
  `content2` text COMMENT '自定义字段',
  `content3` text COMMENT '自定义字段',
  `content4` text COMMENT '自定义字段',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1167769 DEFAULT CHARSET=utf8 COMMENT='RX调试记录表（仅调试记录用）';

-- ----------------------------
-- Table structure for delete_users_logs
-- ----------------------------
DROP TABLE IF EXISTS `delete_users_logs`;
CREATE TABLE `delete_users_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT NULL COMMENT '用戶ID',
  `parent_id` int(11) DEFAULT NULL COMMENT '用戶的父類',
  `admin_id` int(11) DEFAULT NULL COMMENT '操作admin',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=289127 DEFAULT CHARSET=utf8 COMMENT='刪除免費用戶記錄';

-- ----------------------------
-- Table structure for dts_increment_trx
-- ----------------------------
DROP TABLE IF EXISTS `dts_increment_trx`;
CREATE TABLE `dts_increment_trx` (
  `job_id` char(32) NOT NULL,
  `partition` int(11) NOT NULL,
  `checkpoint` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`job_id`,`partition`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='DTS迁移位点表,请勿轻易删除!';

-- ----------------------------
-- Table structure for error_404
-- ----------------------------
DROP TABLE IF EXISTS `error_404`;
CREATE TABLE `error_404` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `url` varchar(100) NOT NULL DEFAULT '0' COMMENT '404URL',
  `create_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=236 DEFAULT CHARSET=utf8 COMMENT='404错误记录';

-- ----------------------------
-- Table structure for error_log
-- ----------------------------
DROP TABLE IF EXISTS `error_log`;
CREATE TABLE `error_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `content` text,
  `create_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for exchange_rate
-- ----------------------------
DROP TABLE IF EXISTS `exchange_rate`;
CREATE TABLE `exchange_rate` (
  `currency` varchar(16) NOT NULL DEFAULT '' COMMENT '货币符号',
  `rate` decimal(12,6) DEFAULT '0.000000' COMMENT '对美元的汇率',
  `icon` varchar(16) NOT NULL DEFAULT '' COMMENT '符号',
  `name` varchar(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`currency`),
  KEY `currency` (`currency`) USING HASH
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='汇率表';

-- ----------------------------
-- Table structure for exchange_rate_history
-- ----------------------------
DROP TABLE IF EXISTS `exchange_rate_history`;
CREATE TABLE `exchange_rate_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `currency` varchar(16) NOT NULL DEFAULT '' COMMENT '货币符号',
  `rate` decimal(12,6) NOT NULL DEFAULT '0.000000' COMMENT '对美元的汇率',
  `icon` varchar(16) NOT NULL DEFAULT '' COMMENT '符号',
  `name` varchar(32) NOT NULL DEFAULT '' COMMENT '名字',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `currency` (`currency`) USING HASH
) ENGINE=InnoDB AUTO_INCREMENT=12657 DEFAULT CHARSET=utf8 COMMENT='汇率记录表';

-- ----------------------------
-- Table structure for execute_sql_log
-- ----------------------------
DROP TABLE IF EXISTS `execute_sql_log`;
CREATE TABLE `execute_sql_log` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `sql` text NOT NULL COMMENT 'sql 源码',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `admin_id` int(5) NOT NULL DEFAULT '0' COMMENT '操作者id',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `status` int(1) DEFAULT '0' COMMENT '是否通过(0=>未处理,1=>已通过,2=>驳回)',
  `audit_id` int(10) DEFAULT '0' COMMENT '审核人id',
  `audit_time` timestamp NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP COMMENT '审核时间',
  `refuse_reason` text COMMENT '驳回原因',
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=501 DEFAULT CHARSET=utf8 COMMENT='sql执行操作记录';

-- ----------------------------
-- Table structure for export_customs_orders
-- ----------------------------
DROP TABLE IF EXISTS `export_customs_orders`;
CREATE TABLE `export_customs_orders` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `fifter_array` text COMMENT '查询条件',
  `file_name` varchar(50) DEFAULT NULL COMMENT '文件名称',
  `file_path` varchar(255) DEFAULT NULL COMMENT '文件路径',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态:0=》未开始 1=》处理中 2=》处理完成 3=》已操作 4=》处理失败 5=》数据为空',
  `admin_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '操作人ID',
  `update_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=101 DEFAULT CHARSET=utf8 COMMENT='导出订单到海关、银行的记录表';

-- ----------------------------
-- Table structure for export_order_tmp
-- ----------------------------
DROP TABLE IF EXISTS `export_order_tmp`;
CREATE TABLE `export_order_tmp` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `operator_id` int(11) DEFAULT NULL COMMENT '所属运营方',
  `fifter_array` text COMMENT '需要查询的条件',
  `filename_tmp` varchar(30) DEFAULT NULL COMMENT '临时名',
  `filename` text,
  `status` tinyint(3) DEFAULT '0' COMMENT '0=》未开始 1=》处理中 2=》处理完成 3=》已操作 4=》处理失败 5=》数据为空',
  `admin_id` int(6) DEFAULT NULL COMMENT '操作人ID',
  `system_time` timestamp NULL DEFAULT NULL,
  `update_path` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=112049 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for generation_sales_logs
-- ----------------------------
DROP TABLE IF EXISTS `generation_sales_logs`;
CREATE TABLE `generation_sales_logs` (
  `log_id` int(10) NOT NULL AUTO_INCREMENT,
  `commission_id` int(11) NOT NULL DEFAULT '0' COMMENT '佣金表ID',
  `parent_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '得到提成獎的会员ID',
  `child_id` int(10) NOT NULL DEFAULT '0' COMMENT '产生订单的会员的ID',
  `level` int(10) NOT NULL DEFAULT '0' COMMENT 'child_id位於parent_id的第幾代',
  `sales` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '订单的销售利润',
  `push_money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '提成金额',
  `percent` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '提成多少点',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '变动时间',
  PRIMARY KEY (`log_id`),
  KEY `commission_id` (`commission_id`),
  KEY `parent_id` (`parent_id`),
  KEY `child_id` (`child_id`),
  KEY `sales` (`sales`)
) ENGINE=InnoDB AUTO_INCREMENT=15122940 DEFAULT CHARSET=utf8 COMMENT='团队销售提成表';

-- ----------------------------
-- Table structure for grant_bonus_138_every_param
-- ----------------------------
DROP TABLE IF EXISTS `grant_bonus_138_every_param`;
CREATE TABLE `grant_bonus_138_every_param` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `total_shar_num` bigint(11) unsigned NOT NULL DEFAULT '0' COMMENT '138人员矩阵底下人数总和',
  `total_amount_other` float(11,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '用来按照矩阵发放的总利润',
  `amount_avg` float(11,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '每个人可以拿到的均摊利润',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '添加时间',
  `x` int(11) NOT NULL DEFAULT '0' COMMENT 'X 轴',
  `y` int(11) NOT NULL DEFAULT '0' COMMENT 'Y 轴',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=93 DEFAULT CHARSET=utf8 COMMENT='每天138分红参数记录表';

-- ----------------------------
-- Table structure for grant_bonus_elite_every_param
-- ----------------------------
DROP TABLE IF EXISTS `grant_bonus_elite_every_param`;
CREATE TABLE `grant_bonus_elite_every_param` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `total_shar_amount` float(11,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '利润比例算出发放金额',
  `total_weight` bigint(11) unsigned NOT NULL DEFAULT '0' COMMENT '统计发奖人员总权重点',
  `create_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=91 DEFAULT CHARSET=utf8 COMMENT='每日销售精英分红参数记录表';

-- ----------------------------
-- Table structure for grant_bonus_user_logs
-- ----------------------------
DROP TABLE IF EXISTS `grant_bonus_user_logs`;
CREATE TABLE `grant_bonus_user_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `proportion` tinyint(1) DEFAULT '0' COMMENT '是否自动转分红点',
  `share_point` int(11) DEFAULT '0' COMMENT '自动转分红点点数',
  `amount` decimal(26,0) DEFAULT '0' COMMENT '发奖前金额,单位:美分',
  `bonus` decimal(26,0) DEFAULT '0' COMMENT '要发的奖金, 单位:美元',
  `type` tinyint(1) DEFAULT '0' COMMENT '默认为0，0为错误；1为正确 ',
  `item_type` int(4) DEFAULT '0' COMMENT '发奖类型',
  `create_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid` (`uid`,`type`,`item_type`,`create_time`) USING HASH
) ENGINE=InnoDB AUTO_INCREMENT=714740 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for grant_pre_138_bonus
-- ----------------------------
DROP TABLE IF EXISTS `grant_pre_138_bonus`;
CREATE TABLE `grant_pre_138_bonus` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) unsigned DEFAULT NULL,
  `amount` decimal(20,0) DEFAULT '0' COMMENT '发奖金额,单位:美分',
  `create_time` int(11) DEFAULT '0' COMMENT '创建时间，格式时间戳',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1325809 DEFAULT CHARSET=utf8 COMMENT='预发奖138分红    预发表';

-- ----------------------------
-- Table structure for grant_pre_bonus_state
-- ----------------------------
DROP TABLE IF EXISTS `grant_pre_bonus_state`;
CREATE TABLE `grant_pre_bonus_state` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `state` tinyint(1) NOT NULL DEFAULT '0' COMMENT '预发奖状态，0，未开始，1，开始预发奖；2，正在进行中；3，发奖完成',
  `item_type` tinyint(2) NOT NULL DEFAULT '0' COMMENT '发奖类型',
  `remark` varchar(16) DEFAULT NULL COMMENT '备注',
  PRIMARY KEY (`id`,`item_type`),
  KEY `idx_item_type` (`item_type`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for grant_pre_every_month_leader_bonus
-- ----------------------------
DROP TABLE IF EXISTS `grant_pre_every_month_leader_bonus`;
CREATE TABLE `grant_pre_every_month_leader_bonus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `amount` decimal(20,0) NOT NULL DEFAULT '0' COMMENT '发奖金额,单位:美分',
  `level` tinyint(1) NOT NULL DEFAULT '0' COMMENT '用户职称；销售主管3,销售总监4,销售副总裁5，默认为0',
  `create_time` int(11) DEFAULT '0' COMMENT '创建时间，格式时间戳',
  PRIMARY KEY (`id`),
  KEY `idx_uid` (`uid`) USING BTREE,
  KEY `idx_level` (`level`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=166625 DEFAULT CHARSET=utf8 COMMENT='每月领导分红奖 预发表';

-- ----------------------------
-- Table structure for grant_pre_every_month_team_bonus
-- ----------------------------
DROP TABLE IF EXISTS `grant_pre_every_month_team_bonus`;
CREATE TABLE `grant_pre_every_month_team_bonus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `amount` decimal(20,0) NOT NULL DEFAULT '0' COMMENT '发奖金额,单位:美分',
  `create_time` int(11) DEFAULT '0' COMMENT '创建时间，格式时间戳',
  PRIMARY KEY (`id`),
  KEY `idx_uid` (`uid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=96141 DEFAULT CHARSET=utf8 COMMENT='预发奖表每周团队分红奖    预发表';

-- ----------------------------
-- Table structure for grant_pre_every_week_team_bonus
-- ----------------------------
DROP TABLE IF EXISTS `grant_pre_every_week_team_bonus`;
CREATE TABLE `grant_pre_every_week_team_bonus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `amount` decimal(20,0) NOT NULL DEFAULT '0' COMMENT '发奖金额,单位:美分',
  `create_time` int(11) DEFAULT '0' COMMENT '创建时间，格式时间戳',
  PRIMARY KEY (`id`),
  KEY `idx_uid` (`uid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=186851 DEFAULT CHARSET=utf8 COMMENT='预发奖表每周团队分红奖    预发表';

-- ----------------------------
-- Table structure for grant_pre_sales_executive_bonus
-- ----------------------------
DROP TABLE IF EXISTS `grant_pre_sales_executive_bonus`;
CREATE TABLE `grant_pre_sales_executive_bonus` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) unsigned DEFAULT NULL,
  `amount` decimal(20,0) DEFAULT '0' COMMENT '发奖金额,单位:美分',
  `create_time` int(11) DEFAULT '0' COMMENT '创建时间，格式时间戳',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=256006 DEFAULT CHARSET=utf8 COMMENT='预发奖表销售精英日分红    预发表';

-- ----------------------------
-- Table structure for grant_pre_users_daily_bonus
-- ----------------------------
DROP TABLE IF EXISTS `grant_pre_users_daily_bonus`;
CREATE TABLE `grant_pre_users_daily_bonus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `amount` decimal(20,0) unsigned NOT NULL DEFAULT '0' COMMENT '发奖金额,单位:美分',
  `level` tinyint(1) NOT NULL DEFAULT '0' COMMENT '用户职称；销售主管3,销售总监4,销售副总裁5，默认为0',
  `create_time` int(11) unsigned DEFAULT '0' COMMENT '创建时间，格式时间戳',
  `bonus_time` datetime NOT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '发的哪天的奖 20170201',
  PRIMARY KEY (`id`),
  KEY `idx_uid` (`uid`) USING BTREE,
  KEY `idx_bonus_time` (`bonus_time`)
) ENGINE=InnoDB AUTO_INCREMENT=1402289 DEFAULT CHARSET=utf8 COMMENT='全球日分红 预发奖 ';

-- ----------------------------
-- Table structure for grant_pre_users_new_member_bonus
-- ----------------------------
DROP TABLE IF EXISTS `grant_pre_users_new_member_bonus`;
CREATE TABLE `grant_pre_users_new_member_bonus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `amount` decimal(20,0) unsigned NOT NULL DEFAULT '0' COMMENT '发奖金额,单位:美分',
  `level` tinyint(1) NOT NULL DEFAULT '0' COMMENT '用户职称；销售主管3,销售总监4,销售副总裁5，默认为0',
  `create_time` int(11) unsigned DEFAULT '0' COMMENT '创建时间，格式时间戳',
  `bonus_time` int(11) NOT NULL COMMENT '发的哪天的奖 20170201',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idex_uid_bonus_time` (`uid`,`bonus_time`),
  KEY `idx_uid` (`uid`) USING BTREE,
  KEY `idx_bonus_time` (`bonus_time`)
) ENGINE=InnoDB AUTO_INCREMENT=345864 DEFAULT CHARSET=utf8 COMMENT='新用户分红预发奖 ';

-- ----------------------------
-- Table structure for infinity_generation_count
-- ----------------------------
DROP TABLE IF EXISTS `infinity_generation_count`;
CREATE TABLE `infinity_generation_count` (
  `uid` int(11) DEFAULT NULL,
  UNIQUE KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='满足总裁奖的人数';

-- ----------------------------
-- Table structure for infinity_generation_log
-- ----------------------------
DROP TABLE IF EXISTS `infinity_generation_log`;
CREATE TABLE `infinity_generation_log` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `uid` int(10) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `total_money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '销售利润总金额',
  `money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '无限代奖励金额 = 销售利润总金额 * 0.005',
  `qualified_time` varchar(50) NOT NULL DEFAULT '0' COMMENT '符合无限代奖的月份',
  `grant` tinyint(3) NOT NULL DEFAULT '0' COMMENT '金额是否发放：0：未发放，1：已发放.1号统计 ，10号发放',
  `create_time` int(10) NOT NULL DEFAULT '0' COMMENT '奖励时间',
  `status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '用户是否查看记录 0 没看 1有看',
  `child_count` int(11) DEFAULT '0' COMMENT '11代一下的数量',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_month` (`uid`,`qualified_time`)
) ENGINE=InnoDB AUTO_INCREMENT=425 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ip_address_info
-- ----------------------------
DROP TABLE IF EXISTS `ip_address_info`;
CREATE TABLE `ip_address_info` (
  `ip` varchar(15) NOT NULL DEFAULT '',
  `type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '2:中文，1：英文',
  `country_code` char(2) NOT NULL DEFAULT '',
  `country_name` varchar(100) NOT NULL DEFAULT '',
  `region_code` varchar(10) NOT NULL DEFAULT '',
  `region_name` varchar(100) NOT NULL DEFAULT '',
  `city_code` varchar(10) NOT NULL DEFAULT '',
  `city` varchar(100) NOT NULL DEFAULT '',
  KEY `ip_type` (`ip`,`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ip_user_login_info
-- ----------------------------
DROP TABLE IF EXISTS `ip_user_login_info`;
CREATE TABLE `ip_user_login_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL DEFAULT '0',
  `ip` varchar(15) NOT NULL DEFAULT '0',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `create_time` (`create_time`),
  KEY `ip` (`ip`)
) ENGINE=InnoDB AUTO_INCREMENT=139480116 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for is_customs_bank
-- ----------------------------
DROP TABLE IF EXISTS `is_customs_bank`;
CREATE TABLE `is_customs_bank` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` char(19) NOT NULL,
  `type` int(1) NOT NULL DEFAULT '0' COMMENT '1：海关  2：银行',
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `expand` text COMMENT '预留字段',
  PRIMARY KEY (`id`),
  KEY `UQE_is_order_id` (`order_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=102 DEFAULT CHARSET=utf8 COMMENT='导出报文记录表';

-- ----------------------------
-- Table structure for language
-- ----------------------------
DROP TABLE IF EXISTS `language`;
CREATE TABLE `language` (
  `language_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL DEFAULT '',
  `code` varchar(16) NOT NULL DEFAULT '',
  `image` varchar(255) NOT NULL DEFAULT '',
  `sort_order` tinyint(3) NOT NULL DEFAULT '99',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`language_id`),
  KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for logs_bonus_special
-- ----------------------------
DROP TABLE IF EXISTS `logs_bonus_special`;
CREATE TABLE `logs_bonus_special` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `admin_id` int(11) NOT NULL DEFAULT '0' COMMENT '操作的后台用户',
  `action` varchar(255) NOT NULL DEFAULT '' COMMENT '操作的方法',
  `action_data` varchar(255) NOT NULL DEFAULT '' COMMENT '操作传递的数据',
  `create_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '操作时间',
  `admin_email` varchar(50) NOT NULL DEFAULT '' COMMENT '操作的邮件',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=39204 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for logs_cron
-- ----------------------------
DROP TABLE IF EXISTS `logs_cron`;
CREATE TABLE `logs_cron` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `content` text,
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1400576 DEFAULT CHARSET=utf8 COMMENT='计划任务日志表';

-- ----------------------------
-- Table structure for logs_interface
-- ----------------------------
DROP TABLE IF EXISTS `logs_interface`;
CREATE TABLE `logs_interface` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `content` text,
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14151 DEFAULT CHARSET=utf8 COMMENT='计划任务日志表';

-- ----------------------------
-- Table structure for logs_interface_walhao
-- ----------------------------
DROP TABLE IF EXISTS `logs_interface_walhao`;
CREATE TABLE `logs_interface_walhao` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` tinyint(3) NOT NULL COMMENT '1沃好推送过来的订单',
  `content` text NOT NULL,
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4523351 DEFAULT CHARSET=utf8 COMMENT='计划任务日志表';

-- ----------------------------
-- Table structure for logs_new_member_bonus
-- ----------------------------
DROP TABLE IF EXISTS `logs_new_member_bonus`;
CREATE TABLE `logs_new_member_bonus` (
  `uid` int(10) NOT NULL COMMENT '拿分红的id',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY `uid` (`uid`) USING BTREE,
  KEY `create_time` (`create_time`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='新用户专属奖日志';

-- ----------------------------
-- Table structure for logs_orders
-- ----------------------------
DROP TABLE IF EXISTS `logs_orders`;
CREATE TABLE `logs_orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_sn` varchar(50) NOT NULL DEFAULT '0' COMMENT '訂單號',
  `content` text,
  `create_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=690381 DEFAULT CHARSET=utf8 COMMENT='订单创建失败或支付异步通知失败记录';

-- ----------------------------
-- Table structure for logs_orders_notify
-- ----------------------------
DROP TABLE IF EXISTS `logs_orders_notify`;
CREATE TABLE `logs_orders_notify` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `payment_type` varchar(50) NOT NULL DEFAULT '0' COMMENT '֧',
  `type` varchar(50) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6414436 DEFAULT CHARSET=utf8 COMMENT='支付异步通知日志表';

-- ----------------------------
-- Table structure for logs_orders_rollback
-- ----------------------------
DROP TABLE IF EXISTS `logs_orders_rollback`;
CREATE TABLE `logs_orders_rollback` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` varchar(50) NOT NULL DEFAULT '0' COMMENT '订单id',
  `txn_id` varchar(50) DEFAULT '0' COMMENT '交易ID',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '处理结果 0 未处理 1处理成功',
  `process_num` tinyint(1) NOT NULL DEFAULT '0' COMMENT '处理次数',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `order_id` (`order_id`),
  KEY `status` (`status`),
  KEY `process_num` (`process_num`)
) ENGINE=InnoDB AUTO_INCREMENT=2615047 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for logs_unfrost_user
-- ----------------------------
DROP TABLE IF EXISTS `logs_unfrost_user`;
CREATE TABLE `logs_unfrost_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `admin_id` int(11) NOT NULL DEFAULT '0' COMMENT '后台操作人id',
  `account` varchar(25) NOT NULL DEFAULT '' COMMENT '解冻账号',
  `create_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1069 DEFAULT CHARSET=utf8 COMMENT='用户登录解冻记录';

-- ----------------------------
-- Table structure for logs_wohao_api
-- ----------------------------
DROP TABLE IF EXISTS `logs_wohao_api`;
CREATE TABLE `logs_wohao_api` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` varchar(25) NOT NULL,
  `api` varchar(255) NOT NULL DEFAULT '' COMMENT '调用接口',
  `ip` varchar(40) NOT NULL DEFAULT '' COMMENT 'ip地址',
  `create_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=79204 DEFAULT CHARSET=utf8 COMMENT='沃好接口调用日志';

-- ----------------------------
-- Table structure for log_erp_hg
-- ----------------------------
DROP TABLE IF EXISTS `log_erp_hg`;
CREATE TABLE `log_erp_hg` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `content` text,
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `content1` text COMMENT '自定义字段',
  `content2` text COMMENT '自定义字段',
  `content3` text COMMENT '自定义字段',
  `content4` text COMMENT '自定义字段',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21792 DEFAULT CHARSET=utf8 COMMENT='海关ERP记录';

-- ----------------------------
-- Table structure for ly_fix_parent_id
-- ----------------------------
DROP TABLE IF EXISTS `ly_fix_parent_id`;
CREATE TABLE `ly_fix_parent_id` (
  `id` int(10) NOT NULL,
  `parent_id` int(10) DEFAULT NULL,
  `parent_ids` longtext
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ly_temp
-- ----------------------------
DROP TABLE IF EXISTS `ly_temp`;
CREATE TABLE `ly_temp` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `commission` decimal(14,2) DEFAULT '0.00',
  `level` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=183995 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ly_test
-- ----------------------------
DROP TABLE IF EXISTS `ly_test`;
CREATE TABLE `ly_test` (
  `id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mall_ads
-- ----------------------------
DROP TABLE IF EXISTS `mall_ads`;
CREATE TABLE `mall_ads` (
  `ad_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ad_img` varchar(255) NOT NULL DEFAULT '' COMMENT '广告图片地址',
  `ad_url` varchar(255) NOT NULL DEFAULT '' COMMENT '链接地址',
  `language_id` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1显示0不显示',
  `location` varchar(128) NOT NULL DEFAULT '' COMMENT '广告位置',
  `sort_order` tinyint(3) unsigned NOT NULL DEFAULT '99' COMMENT '显示顺序',
  PRIMARY KEY (`ad_id`)
) ENGINE=InnoDB AUTO_INCREMENT=293 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mall_customer_feedback
-- ----------------------------
DROP TABLE IF EXISTS `mall_customer_feedback`;
CREATE TABLE `mall_customer_feedback` (
  `feed_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `content` text COMMENT '反馈内容',
  `add_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `user_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '反馈人员ID，游客默认是0',
  `state` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '信息的阅读和处理状态   1新增内容装填  2已处理状态',
  PRIMARY KEY (`feed_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='客户意见反馈表';

-- ----------------------------
-- Table structure for mall_feedback
-- ----------------------------
DROP TABLE IF EXISTS `mall_feedback`;
CREATE TABLE `mall_feedback` (
  `feed_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(128) NOT NULL DEFAULT '',
  `content` text,
  `add_time` int(11) unsigned NOT NULL DEFAULT '0',
  `user_id` int(11) unsigned NOT NULL DEFAULT '0',
  `state` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '处理状态',
  `name` varchar(30) NOT NULL DEFAULT '',
  `mobile` varchar(30) NOT NULL DEFAULT '',
  `title` varchar(200) NOT NULL DEFAULT '',
  PRIMARY KEY (`feed_id`)
) ENGINE=InnoDB AUTO_INCREMENT=66 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for mall_goods
-- ----------------------------
DROP TABLE IF EXISTS `mall_goods`;
CREATE TABLE `mall_goods` (
  `product_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `goods_sn_main` varchar(32) NOT NULL DEFAULT '',
  `goods_sn` varchar(32) NOT NULL DEFAULT '',
  `color` varchar(32) NOT NULL DEFAULT '',
  `size` varchar(32) NOT NULL DEFAULT '',
  `customer` varchar(100) NOT NULL DEFAULT '' COMMENT '自定义属性',
  `goods_number` mediumint(8) unsigned NOT NULL DEFAULT '1000' COMMENT '库存数量',
  `warn_number` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '预警数量',
  `language_id` smallint(5) unsigned NOT NULL DEFAULT '1',
  `price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '私有价格，如果设置了优先使用',
  `purchase_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '进货价',
  `is_lock` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '库存锁定 0：非锁定 1：锁定',
  `goods_currency` decimal(10,2) unsigned DEFAULT '0.00' COMMENT '本币供货价',
  PRIMARY KEY (`product_id`),
  KEY `goods_sn_main` (`goods_sn_main`),
  KEY `goods_sn` (`goods_sn`)
) ENGINE=InnoDB AUTO_INCREMENT=140247 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mall_goods_ads
-- ----------------------------
DROP TABLE IF EXISTS `mall_goods_ads`;
CREATE TABLE `mall_goods_ads` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `media` enum('1','2') NOT NULL DEFAULT '1' COMMENT '媒体。1 电脑端；2 移动端',
  `region_code` enum('156','344','840','410','000') NOT NULL DEFAULT '156' COMMENT '区域码',
  `position_id` mediumint(3) unsigned NOT NULL DEFAULT '0' COMMENT '广告位置编号',
  `ad_img` varchar(255) NOT NULL DEFAULT '' COMMENT '广告图片uri',
  `img_subhead` varchar(255) NOT NULL DEFAULT '' COMMENT '图片副标题或附加内容',
  `action_type` enum('1','2','3') NOT NULL DEFAULT '1' COMMENT '行为类型。1 url跳转；2 商品主sku；3 关键字',
  `action_val` varchar(255) NOT NULL DEFAULT '' COMMENT '行为值，根据行为类型而定',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态。1 显示；0 隐藏',
  `sort_order` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序。相同的媒体、区域及位置下，排序必须唯一且连续',
  PRIMARY KEY (`id`),
  KEY `KEY_MEDIA` (`media`),
  KEY `KEY_REGION_CODE` (`region_code`),
  KEY `KEY_SORT_ORDER` (`sort_order`),
  KEY `KEY_POSITION_ID` (`position_id`)
) ENGINE=InnoDB AUTO_INCREMENT=275 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mall_goods_attribute
-- ----------------------------
DROP TABLE IF EXISTS `mall_goods_attribute`;
CREATE TABLE `mall_goods_attribute` (
  `attr_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `attr_name` varchar(60) NOT NULL DEFAULT '',
  `attr_values` varchar(128) NOT NULL DEFAULT '',
  `language_id` smallint(5) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`attr_id`)
) ENGINE=InnoDB AUTO_INCREMENT=105 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mall_goods_brand
-- ----------------------------
DROP TABLE IF EXISTS `mall_goods_brand`;
CREATE TABLE `mall_goods_brand` (
  `brand_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `brand_name` varchar(255) NOT NULL DEFAULT '' COMMENT '品牌名称',
  `language_id` smallint(5) NOT NULL DEFAULT '1',
  `cate_id` smallint(5) NOT NULL DEFAULT '0' COMMENT '所属分类id',
  PRIMARY KEY (`brand_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4464 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mall_goods_category
-- ----------------------------
DROP TABLE IF EXISTS `mall_goods_category`;
CREATE TABLE `mall_goods_category` (
  `cate_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `cate_sn` varchar(32) NOT NULL DEFAULT '' COMMENT '不同语种在分类页面切换的关联sn',
  `cate_name` varchar(128) NOT NULL DEFAULT '' COMMENT '分类名',
  `cate_desc` varchar(255) NOT NULL DEFAULT '' COMMENT '分类描述',
  `cate_img` varchar(255) NOT NULL DEFAULT '' COMMENT '分类icon',
  `parent_id` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '上级分类id',
  `meta_title` varchar(255) NOT NULL DEFAULT '' COMMENT 'SEO网页title',
  `meta_keywords` varchar(255) NOT NULL DEFAULT '' COMMENT 'SEO keywords',
  `meta_desc` varchar(255) NOT NULL DEFAULT '' COMMENT 'SEO description',
  `language_id` smallint(5) unsigned NOT NULL DEFAULT '1',
  `sort_order` int(10) unsigned NOT NULL DEFAULT '99' COMMENT '排序',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '状态0停用1使用',
  `is_doba_cate` tinyint(1) unsigned DEFAULT '0' COMMENT 'doba分类',
  PRIMARY KEY (`cate_id`),
  KEY `parent_id` (`parent_id`),
  KEY `cate_name` (`cate_name`),
  KEY `status_ind` (`status`),
  KEY `language_id` (`language_id`),
  KEY `sort_order` (`sort_order`),
  KEY `cat_sn` (`cate_sn`),
  KEY `ind_mall_goods_category` (`language_id`,`status`,`sort_order`)
) ENGINE=InnoDB AUTO_INCREMENT=1940 DEFAULT CHARSET=utf8 COMMENT='产品分类';

-- ----------------------------
-- Table structure for mall_goods_comments
-- ----------------------------
DROP TABLE IF EXISTS `mall_goods_comments`;
CREATE TABLE `mall_goods_comments` (
  `com_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `com_user` varchar(255) NOT NULL DEFAULT '' COMMENT '评论人',
  `com_contents` varchar(512) NOT NULL DEFAULT '' COMMENT '评论内容',
  `goods_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '产品主id',
  `goods_sn_main` varchar(32) NOT NULL DEFAULT '' COMMENT '主sku',
  `add_time` int(11) DEFAULT '0' COMMENT '添加时间戳',
  `com_score` decimal(2,1) DEFAULT '5.0' COMMENT '评分',
  PRIMARY KEY (`com_id`),
  KEY `idx_goods_sn_main` (`goods_sn_main`) USING BTREE,
  KEY `idx_goods_id` (`goods_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3109237 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mall_goods_customs
-- ----------------------------
DROP TABLE IF EXISTS `mall_goods_customs`;
CREATE TABLE `mall_goods_customs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `goods_sn_main` varchar(32) NOT NULL DEFAULT '' COMMENT '商品主sku',
  `goods_sn` varchar(32) NOT NULL DEFAULT '' COMMENT '商品子sku',
  `ciqgno` varchar(30) NOT NULL DEFAULT '' COMMENT '检验检疫商品备案号',
  `gcode` char(10) NOT NULL DEFAULT '' COMMENT '商品编码',
  `gmodel` varchar(250) NOT NULL DEFAULT '' COMMENT '海关规格型号',
  `ciqgmodel` varchar(250) NOT NULL DEFAULT '' COMMENT '检验检疫规格型号',
  PRIMARY KEY (`id`),
  KEY `goods_sn_main` (`goods_sn_main`) USING BTREE,
  KEY `goods_sn` (`goods_sn`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=61 DEFAULT CHARSET=utf8 COMMENT='商品海关信息';

-- ----------------------------
-- Table structure for mall_goods_detail_img
-- ----------------------------
DROP TABLE IF EXISTS `mall_goods_detail_img`;
CREATE TABLE `mall_goods_detail_img` (
  `img_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `goods_sn_main` varchar(32) NOT NULL COMMENT '主sku',
  `image_url` varchar(255) NOT NULL COMMENT '详情图地址',
  `language_id` smallint(5) NOT NULL DEFAULT '1',
  `img_order` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '图片排序',
  PRIMARY KEY (`img_id`),
  KEY `goods_sn` (`goods_sn_main`)
) ENGINE=InnoDB AUTO_INCREMENT=438875 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mall_goods_effect
-- ----------------------------
DROP TABLE IF EXISTS `mall_goods_effect`;
CREATE TABLE `mall_goods_effect` (
  `effect_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `effect_name` varchar(255) NOT NULL DEFAULT '' COMMENT '商品风格名称',
  `language_id` smallint(5) unsigned NOT NULL DEFAULT '1',
  `cate_id` smallint(5) NOT NULL DEFAULT '0' COMMENT '风格所属分类id',
  PRIMARY KEY (`effect_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mall_goods_gallery
-- ----------------------------
DROP TABLE IF EXISTS `mall_goods_gallery`;
CREATE TABLE `mall_goods_gallery` (
  `img_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `goods_sn` varchar(32) NOT NULL DEFAULT '' COMMENT '子sku',
  `thumb_img` varchar(255) NOT NULL DEFAULT '' COMMENT '小图',
  `big_img` varchar(255) NOT NULL DEFAULT '' COMMENT '大图',
  `img_order` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '图片排序',
  PRIMARY KEY (`img_id`),
  KEY `goods_sn` (`goods_sn`)
) ENGINE=InnoDB AUTO_INCREMENT=1380022 DEFAULT CHARSET=utf8 COMMENT='产品相册';

-- ----------------------------
-- Table structure for mall_goods_goods_number_logs
-- ----------------------------
DROP TABLE IF EXISTS `mall_goods_goods_number_logs`;
CREATE TABLE `mall_goods_goods_number_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) DEFAULT '0' COMMENT 'SKU 的PRODUCT_ID ',
  `log` varchar(500) DEFAULT NULL COMMENT '日志内容',
  `operator` char(4) DEFAULT NULL COMMENT '库存操作符，加号用于表示增加，减号用于表示减少，等于号用于表示直接设置库存值',
  `original_num` int(11) DEFAULT '0' COMMENT '原库存',
  `new_num` int(11) DEFAULT '0' COMMENT '新库存',
  `order_id` varchar(40) DEFAULT NULL COMMENT '记录的关联订单ID',
  `created_time` timestamp NULL DEFAULT NULL COMMENT '日志入库时间',
  `opera_time` timestamp NULL DEFAULT NULL COMMENT '库存操作时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1890769 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mall_goods_group
-- ----------------------------
DROP TABLE IF EXISTS `mall_goods_group`;
CREATE TABLE `mall_goods_group` (
  `group_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `group_goods` varchar(255) NOT NULL DEFAULT '' COMMENT '逗号分开的goods_id',
  PRIMARY KEY (`group_id`)
) ENGINE=InnoDB AUTO_INCREMENT=911 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mall_goods_keyword
-- ----------------------------
DROP TABLE IF EXISTS `mall_goods_keyword`;
CREATE TABLE `mall_goods_keyword` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `media` enum('1','2') NOT NULL DEFAULT '1' COMMENT '媒体。1 电脑端；2 移动端',
  `region_code` enum('156','344','840','410','000') NOT NULL DEFAULT '156' COMMENT '区域码',
  `position_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '位置编号',
  `keyword` varchar(128) NOT NULL DEFAULT '' COMMENT '关键字内容',
  `priority` enum('normal','emphasize') NOT NULL DEFAULT 'normal' COMMENT '优先级。normal 普通；emphasize 强调',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态。1 显示；0 隐藏',
  `sort_order` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序。相同的媒体、区域及位置下，排序必须唯一且连续',
  PRIMARY KEY (`id`),
  KEY `KEY_MEDIA` (`media`),
  KEY `KEY_REGION_CODE` (`region_code`),
  KEY `KEY_POSITION_ID` (`position_id`),
  KEY `KEY_SORT_ORDER` (`sort_order`)
) ENGINE=InnoDB AUTO_INCREMENT=81 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mall_goods_main
-- ----------------------------
DROP TABLE IF EXISTS `mall_goods_main`;
CREATE TABLE `mall_goods_main` (
  `goods_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `goods_sn_main` varchar(32) NOT NULL DEFAULT '' COMMENT '主sku',
  `cate_id` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '分类id',
  `goods_name` text NOT NULL COMMENT '商品名',
  `goods_name_cn` varchar(255) NOT NULL DEFAULT '' COMMENT '商品中文名',
  `sale_number` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '销售总数',
  `goods_img` varchar(255) NOT NULL DEFAULT '' COMMENT '产品列表图',
  `goods_weight` decimal(10,3) unsigned NOT NULL DEFAULT '0.000' COMMENT '重量',
  `goods_size` decimal(10,3) unsigned NOT NULL DEFAULT '0.000' COMMENT '货物体积',
  `purchase_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '进货价，美元',
  `market_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '市场价，美元',
  `shop_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '销售价，美元',
  `promote_start_date` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '促销开始时间戳',
  `promote_end_date` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '促销结束时间戳',
  `promote_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '促销价',
  `is_promote` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否促销商品',
  `is_on_sale` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否销售中',
  `is_delete` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否已删除',
  `is_best` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否推荐产品',
  `is_new` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否最新产品',
  `is_hot` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否最热产品',
  `is_home` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否首页展示',
  `is_free_shipping` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否免邮',
  `is_ship24h` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否24小时内发货',
  `is_alone_sale` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1单品2套餐',
  `group_goods_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '是套餐时对应的套餐id',
  `is_for_upgrade` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否用于升级的套装产品',
  `is_require_id` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0: 不需要身份证信息 1：需要身份证信息',
  `require_type` enum('0','1','2','3') NOT NULL DEFAULT '0' COMMENT '0：默认没有 1：需要身份证号码 2：需要身份证图片 3：需要身份证图片和身份证号码',
  `sort_order` smallint(4) unsigned NOT NULL DEFAULT '99' COMMENT '排序',
  `add_user` varchar(32) NOT NULL DEFAULT '' COMMENT '添加人',
  `update_user` varchar(32) NOT NULL DEFAULT '' COMMENT '更新人',
  `seller_note` text NOT NULL COMMENT '销售备注',
  `click_count` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '点击总数',
  `comment_count` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '评论总数',
  `comment_star_avg` decimal(2,1) NOT NULL DEFAULT '5.0' COMMENT '评论平均星星得分',
  `last_update` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后更新时间戳',
  `goods_grade` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '产品所属等级',
  `add_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间戳',
  `language_id` smallint(5) unsigned NOT NULL DEFAULT '1',
  `store_code` varchar(32) NOT NULL DEFAULT '' COMMENT '仓库简码',
  `brand_id` smallint(5) NOT NULL DEFAULT '0' COMMENT '品牌id',
  `effect_id` smallint(5) NOT NULL DEFAULT '0' COMMENT '风格id',
  `country_flag` varchar(32) DEFAULT '' COMMENT '产地旗标名',
  `like_num` int(10) unsigned DEFAULT '0' COMMENT '喜欢数量',
  `goods_note` varchar(255) NOT NULL DEFAULT '' COMMENT '特殊产品备注',
  `is_special` int(1) DEFAULT '0' COMMENT '是否特卖单品(0->否，1->是)',
  `sale_country` char(3) NOT NULL DEFAULT '' COMMENT '销售国家,$分割的国家id，000表示其他地区',
  `gift_skus` varchar(255) NOT NULL DEFAULT '' COMMENT '赠品主sku，多个逗号分隔',
  `goods_tags` varchar(255) NOT NULL DEFAULT '' COMMENT '站内搜索关键词组',
  `supplier_id` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '供应商id',
  `ship_note_type` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '发货时间备注1需要生产周期,2固定日期',
  `ship_note_val` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '天或时间戳',
  `is_doba_goods` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否doba产品数据',
  `doba_supplier_id` varchar(32) NOT NULL DEFAULT '' COMMENT 'doba供应商id',
  `doba_drop_ship_fee` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT 'doba运费',
  `doba_supplier_name` varchar(128) NOT NULL DEFAULT '' COMMENT 'doba供应商名',
  `doba_product_id` varchar(32) NOT NULL DEFAULT '' COMMENT 'doba产品id',
  `doba_product_sku` varchar(32) NOT NULL DEFAULT '' COMMENT 'doba产品sku',
  `doba_warranty` varchar(64) NOT NULL DEFAULT '' COMMENT '保修',
  `doba_manufacturer` varchar(255) NOT NULL DEFAULT '' COMMENT '制造商',
  `doba_country_of_origin` varchar(32) NOT NULL DEFAULT '' COMMENT '原产地',
  `doba_item_id` varchar(32) NOT NULL DEFAULT '' COMMENT 'doba item id',
  `doba_item_sku` varchar(32) NOT NULL DEFAULT '',
  `doba_item_weight` decimal(10,3) NOT NULL DEFAULT '0.000' COMMENT '商品重量 kg',
  `doba_ship_alone` varchar(32) NOT NULL DEFAULT '',
  `doba_ship_weight` decimal(10,3) NOT NULL DEFAULT '0.000' COMMENT '发货重量 kg',
  `doba_ship_cost` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '运费',
  `doba_prepay_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '预付费优惠价',
  `daba_msrp` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '制造商建议零售价',
  `shipper_id` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '发货商id',
  `is_voucher_goods` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否可用代金卷购买商品',
  `is_direc_goods` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否海外直销商品',
  `home_title` varchar(64) NOT NULL DEFAULT '' COMMENT '首页标题',
  `home_note` varchar(128) NOT NULL DEFAULT '' COMMENT '首页副标题',
  `is_for_app` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '移动端首页独立标识',
  `is_hg` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否海关',
  `country_code` char(3) NOT NULL DEFAULT '' COMMENT '产地编号',
  `goods_unit` varchar(20) NOT NULL DEFAULT '7' COMMENT '商品单位',
  PRIMARY KEY (`goods_id`),
  UNIQUE KEY `goods_sn_main` (`goods_sn_main`) USING BTREE,
  KEY `doba_item_id` (`doba_item_id`),
  KEY `is_on_sale` (`is_on_sale`),
  KEY `shop_price` (`shop_price`),
  KEY `shipper_id` (`shipper_id`),
  KEY `is_hot` (`is_hot`),
  KEY `is_best` (`is_best`),
  KEY `is_home` (`is_home`),
  KEY `add_time` (`add_time`),
  KEY `comment_count` (`comment_count`),
  KEY `goods_id` (`goods_id`),
  KEY `ind_mall_goods_main` (`sale_country`,`language_id`,`last_update`,`add_time`),
  KEY `IDX_01` (`group_goods_id`),
  KEY `IDX_IS_LA_SA_AD` (`is_new`,`language_id`,`sale_country`,`add_time`),
  KEY `last_update` (`last_update`) USING BTREE,
  KEY `IDX_CA_LA_SA` (`cate_id`,`language_id`,`sale_country`),
  KEY `IDX_LA_SA_GO` (`language_id`,`sale_country`,`goods_sn_main`,`is_free_shipping`) USING BTREE,
  FULLTEXT KEY `goods_tags` (`goods_tags`)
) ENGINE=InnoDB AUTO_INCREMENT=100894 DEFAULT CHARSET=utf8 COMMENT='商品主表';

-- ----------------------------
-- Table structure for mall_goods_main_detail
-- ----------------------------
DROP TABLE IF EXISTS `mall_goods_main_detail`;
CREATE TABLE `mall_goods_main_detail` (
  `detail_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `goods_main_id` mediumint(8) unsigned NOT NULL COMMENT '对应mall_goods_main中的goods id',
  `meta_title` text COMMENT 'seo标题',
  `meta_keywords` varchar(500) NOT NULL DEFAULT '' COMMENT 'seo关键词',
  `meta_desc` varchar(500) NOT NULL DEFAULT '' COMMENT 'seo描述',
  `goods_desc` text NOT NULL COMMENT '产品详细描述文字',
  `goods_tags` varchar(255) NOT NULL DEFAULT '' COMMENT '产品标签',
  `doba_details` text COMMENT 'doba产品附加描述2',
  PRIMARY KEY (`detail_id`),
  KEY `main_id` (`goods_main_id`)
) ENGINE=InnoDB AUTO_INCREMENT=100887 DEFAULT CHARSET=utf8 COMMENT='商品主表详细';

-- ----------------------------
-- Table structure for mall_goods_number_exception
-- ----------------------------
DROP TABLE IF EXISTS `mall_goods_number_exception`;
CREATE TABLE `mall_goods_number_exception` (
  `goods_sn` varchar(32) NOT NULL,
  `goods_name` varchar(500) NOT NULL,
  `language_id` smallint(5) NOT NULL,
  `number_zh` mediumint(8) DEFAULT NULL,
  `number_hk` mediumint(8) DEFAULT NULL,
  `number_english` mediumint(8) DEFAULT NULL,
  `number_kr` mediumint(8) DEFAULT NULL,
  KEY `goods_sn` (`goods_sn`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mall_goods_origin
-- ----------------------------
DROP TABLE IF EXISTS `mall_goods_origin`;
CREATE TABLE `mall_goods_origin` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `country_flag` varchar(20) DEFAULT '' COMMENT '国家简称 如CN ',
  `name` varchar(50) DEFAULT '' COMMENT '国家名称',
  `language` varchar(20) DEFAULT '' COMMENT '那个地区',
  PRIMARY KEY (`id`),
  KEY `country_flag` (`country_flag`),
  KEY `lan` (`language`)
) ENGINE=InnoDB AUTO_INCREMENT=249 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mall_goods_promote
-- ----------------------------
DROP TABLE IF EXISTS `mall_goods_promote`;
CREATE TABLE `mall_goods_promote` (
  `goods_sn_main` varchar(32) NOT NULL DEFAULT '' COMMENT '商品主sku',
  `goods_sn` varchar(32) NOT NULL DEFAULT '' COMMENT '商品子sku',
  `promote_price_main` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '主促销价格。货币美元，单位分',
  `promote_price` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '促销价格。货币美元，单位分',
  `start_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '促销开始时间',
  `end_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '促销结束时间',
  `promote_currency` decimal(10,2) unsigned DEFAULT '0.00' COMMENT '本币供货价',
  PRIMARY KEY (`goods_sn_main`,`goods_sn`),
  KEY `goods_sn` (`goods_sn`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='商品促销表';

-- ----------------------------
-- Table structure for mall_goods_sale_country
-- ----------------------------
DROP TABLE IF EXISTS `mall_goods_sale_country`;
CREATE TABLE `mall_goods_sale_country` (
  `country_id` char(3) NOT NULL COMMENT '地区代码。一般为国家代码，东南亚地区为001，其他地区为000',
  `name_zh` varchar(128) NOT NULL DEFAULT '',
  `name_hk` varchar(128) NOT NULL DEFAULT '',
  `name_english` varchar(128) NOT NULL DEFAULT '',
  `name_kr` varchar(128) NOT NULL DEFAULT '',
  `default_language` varchar(16) NOT NULL DEFAULT '' COMMENT '默认对应的语言包',
  `default_flag` varchar(16) NOT NULL DEFAULT '' COMMENT '对应的国旗',
  PRIMARY KEY (`country_id`),
  KEY `country_id` (`country_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mall_goods_shipper
-- ----------------------------
DROP TABLE IF EXISTS `mall_goods_shipper`;
CREATE TABLE `mall_goods_shipper` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `shipper_id` int(11) NOT NULL DEFAULT '0' COMMENT '发货商id',
  `sale_area` varchar(128) NOT NULL DEFAULT '' COMMENT '该发货地支持发货的区域代码',
  `freight_company_code` varchar(128) NOT NULL DEFAULT '' COMMENT '支持的快递公司代号',
  `permit_customer_pickup` int(1) NOT NULL DEFAULT '0' COMMENT '支持客户自提。0 不支持；1 支持',
  `shipping_currency` varchar(16) NOT NULL DEFAULT '' COMMENT '运费对应的货币',
  `area_rule` int(5) NOT NULL DEFAULT '1' COMMENT '地区运费规则',
  `store_location` varchar(255) NOT NULL DEFAULT '' COMMENT '发货方地址',
  `store_location_code` int(11) NOT NULL DEFAULT '0' COMMENT '中国仓库的始发地省份code',
  PRIMARY KEY (`Id`),
  KEY `shipper_id` (`shipper_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2653 DEFAULT CHARSET=utf8 COMMENT='发货商运费表';

-- ----------------------------
-- Table structure for mall_goods_storehouse
-- ----------------------------
DROP TABLE IF EXISTS `mall_goods_storehouse`;
CREATE TABLE `mall_goods_storehouse` (
  `store_code` varchar(32) NOT NULL DEFAULT '' COMMENT '仓库简码',
  `supplier_id` int(11) NOT NULL DEFAULT '0' COMMENT '供应商ID',
  `storehouse_type` enum('1','2','3') NOT NULL DEFAULT '1' COMMENT '仓库类型。1 TPS仓库；2 海关仓库；3 第三方仓库',
  `sale_area` varchar(128) NOT NULL DEFAULT '' COMMENT '该仓库支持销售的区域代码，以''$''隔开。',
  `freight_company_code` varchar(128) NOT NULL DEFAULT '' COMMENT '支持的快递公司代号，以''$''隔开',
  `permit_customer_pickup` tinyint(1) NOT NULL DEFAULT '0' COMMENT '支持客户自提。0 不支持；1 支持',
  `shipping_currency` varchar(16) NOT NULL DEFAULT '' COMMENT '运费对应的货币',
  `rule_type` enum('1','2','3','4') NOT NULL DEFAULT '1' COMMENT '运费计算方式。1 首重一千克续重一千克',
  `shipping_rule` text NOT NULL COMMENT '运费计算规则',
  `store_location` varchar(255) NOT NULL DEFAULT '' COMMENT '仓库地址',
  `status` enum('1','2') NOT NULL DEFAULT '1' COMMENT '使用状态。1 使用中；2 已关闭',
  `store_location_code` int(11) NOT NULL DEFAULT '0' COMMENT '中国仓库的始发地省份code',
  PRIMARY KEY (`store_code`),
  KEY `supplier_id` (`supplier_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mall_goods_sync_number
-- ----------------------------
DROP TABLE IF EXISTS `mall_goods_sync_number`;
CREATE TABLE `mall_goods_sync_number` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `goods_sn` varchar(50) NOT NULL DEFAULT '0' COMMENT '商品子sku',
  `api_url` varchar(255) NOT NULL DEFAULT '' COMMENT '接口url',
  `api_param` text NOT NULL COMMENT '接口参数',
  `error` text COMMENT '错误信息',
  `create_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='同步商品库存';

-- ----------------------------
-- Table structure for mall_orders
-- ----------------------------
DROP TABLE IF EXISTS `mall_orders`;
CREATE TABLE `mall_orders` (
  `order_id` varchar(40) NOT NULL DEFAULT '0' COMMENT '订单号',
  `order_pay_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '订单付款的时间',
  `customer_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '顾客id',
  `shopkeeper_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '店主id',
  `order_amount` decimal(14,2) NOT NULL DEFAULT '0.00' COMMENT '订单金额',
  `order_profit` decimal(14,2) NOT NULL DEFAULT '0.00' COMMENT '订单利润',
  `currency` char(3) NOT NULL DEFAULT '0' COMMENT '币种（国际币种标识符）',
  `order_amount_usd` decimal(14,2) NOT NULL DEFAULT '0.00' COMMENT '订单金额（美元）',
  `order_profit_usd` decimal(14,2) NOT NULL DEFAULT '0.00' COMMENT '订单利润（美元）',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `score_year_month` char(6) NOT NULL DEFAULT '0' COMMENT '业绩年月',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '默认1=>已完成，2=>已取消',
  PRIMARY KEY (`order_id`),
  KEY `idx_customer_id` (`customer_id`,`create_time`) USING BTREE,
  KEY `idx_shopkeeper_id` (`shopkeeper_id`,`create_time`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='商城订单';

-- ----------------------------
-- Table structure for mall_orders_paypal_info
-- ----------------------------
DROP TABLE IF EXISTS `mall_orders_paypal_info`;
CREATE TABLE `mall_orders_paypal_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用戶ID',
  `order_id` varchar(255) NOT NULL DEFAULT '0' COMMENT '訂單號',
  `txn_id` varchar(255) NOT NULL DEFAULT '0' COMMENT 'paypal的交易号',
  `tracking_number` varchar(255) NOT NULL DEFAULT '0' COMMENT '訂單發貨的跟踪號',
  `company_name` varchar(255) NOT NULL DEFAULT '0' COMMENT '物流公司',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '訂單生成時間',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=82245 DEFAULT CHARSET=utf8 COMMENT='paypal order 跟踪號';

-- ----------------------------
-- Table structure for mall_orders_paypal_refund
-- ----------------------------
DROP TABLE IF EXISTS `mall_orders_paypal_refund`;
CREATE TABLE `mall_orders_paypal_refund` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` varchar(50) NOT NULL DEFAULT '0' COMMENT '訂單號',
  `txn_id` varchar(50) NOT NULL DEFAULT '0' COMMENT 'paypal的交易号',
  `email` varchar(50) NOT NULL DEFAULT '0' COMMENT '郵箱',
  `name` varchar(50) NOT NULL DEFAULT '0' COMMENT '名字',
  `type` varchar(50) NOT NULL DEFAULT '0' COMMENT 'refund',
  `amount` varchar(50) NOT NULL DEFAULT '0' COMMENT '金額',
  `note` varchar(500) DEFAULT '0' COMMENT '備註',
  `create_time` varchar(50) DEFAULT NULL COMMENT '訂單生成時間',
  `status` varchar(50) DEFAULT NULL COMMENT '是否發了獎勵',
  `ipn_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'IPN通知时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=675 DEFAULT CHARSET=utf8 COMMENT='paypal order 退款交易';

-- ----------------------------
-- Table structure for mall_order_user_monthly_list
-- ----------------------------
DROP TABLE IF EXISTS `mall_order_user_monthly_list`;
CREATE TABLE `mall_order_user_monthly_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL COMMENT '用户id',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_uid` (`uid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=4096 DEFAULT CHARSET=utf8 COMMENT='沃好会员下单队列';

-- ----------------------------
-- Table structure for mall_payment
-- ----------------------------
DROP TABLE IF EXISTS `mall_payment`;
CREATE TABLE `mall_payment` (
  `pay_id` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '支付方式id',
  `pay_code` varchar(20) NOT NULL DEFAULT '' COMMENT '该支付方式处理不带后缀的文件名部分',
  `pay_name` varchar(120) NOT NULL DEFAULT '' COMMENT '支付方式名称',
  `pay_desc` text COMMENT '支付方式描述',
  `pay_order` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '支付方式在页面的显示顺序',
  `pay_config` text COMMENT '支付方式的配置信息，包括商户号和密钥什么的',
  `is_enabled` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否可用，0，否；1，是',
  `pay_currency` varchar(20) NOT NULL DEFAULT 'USD' COMMENT '默认货币',
  `payment_currency` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`pay_id`),
  UNIQUE KEY `pay_code` (`pay_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='支付方式配置信息';

-- ----------------------------
-- Table structure for mall_payment_new
-- ----------------------------
DROP TABLE IF EXISTS `mall_payment_new`;
CREATE TABLE `mall_payment_new` (
  `pay_id` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '支付方式id',
  `pay_code` varchar(20) NOT NULL DEFAULT '' COMMENT '该支付方式处理不带后缀的文件名部分',
  `pay_name` varchar(120) NOT NULL DEFAULT '' COMMENT '支付方式名称',
  `pay_desc` text COMMENT '支付方式描述',
  `pay_order` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '支付方式在页面的显示顺序',
  `pay_config` text COMMENT '支付方式的配置信息，包括商户号和密钥什么的',
  `is_enabled` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否可用，0，否；1，是',
  `pay_currency` varchar(20) NOT NULL DEFAULT 'USD' COMMENT '默认货币',
  `payment_currency` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`pay_id`),
  UNIQUE KEY `pay_code` (`pay_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='支付方式配置信息';

-- ----------------------------
-- Table structure for mall_supplier
-- ----------------------------
DROP TABLE IF EXISTS `mall_supplier`;
CREATE TABLE `mall_supplier` (
  `supplier_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `supplier_name` varchar(255) NOT NULL DEFAULT '',
  `supplier_address` varchar(255) NOT NULL DEFAULT '',
  `supplier_user` varchar(128) NOT NULL DEFAULT '',
  `supplier_tel` varchar(32) NOT NULL DEFAULT '',
  `supplier_phone` varchar(32) NOT NULL DEFAULT '',
  `supplier_qq` varchar(128) NOT NULL DEFAULT '',
  `supplier_ww` varchar(128) NOT NULL DEFAULT '',
  `supplier_type` varchar(32) NOT NULL DEFAULT '',
  `supplier_addtime` int(11) unsigned NOT NULL DEFAULT '0',
  `supplier_email` varchar(128) NOT NULL DEFAULT '',
  `supplier_link` varchar(255) NOT NULL DEFAULT '',
  `supplier_recommend` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '推荐人id',
  `supplier_username` varchar(255) NOT NULL DEFAULT '' COMMENT '登录用户名',
  `supplier_password` varchar(255) NOT NULL DEFAULT '' COMMENT '登录密码',
  `supplier_last_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '最后登录日期',
  `supplier_login_time` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '登录次数',
  `is_supplier_shipping` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否发货商',
  `operator_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '绑定运营方id',
  `status` enum('1','2','3') NOT NULL DEFAULT '1' COMMENT '状态。1 正常合作中，2 已冻结，3 停止合作',
  `country_code` varchar(50) NOT NULL DEFAULT '0' COMMENT '供应商所在地国家code',
  `addr_lv2` varchar(50) NOT NULL DEFAULT '0' COMMENT '省份code',
  `addr_lv3` varchar(50) NOT NULL DEFAULT '0' COMMENT '市级/县级code',
  PRIMARY KEY (`supplier_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4176 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mall_wish
-- ----------------------------
DROP TABLE IF EXISTS `mall_wish`;
CREATE TABLE `mall_wish` (
  `wish_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) NOT NULL,
  `goods_id` mediumint(8) NOT NULL,
  `goods_sn_main` varchar(32) NOT NULL DEFAULT '',
  `add_time` int(11) unsigned NOT NULL,
  PRIMARY KEY (`wish_id`),
  KEY `user_id` (`user_id`),
  KEY `add_time` (`add_time`),
  KEY `goods_id` (`goods_id`),
  KEY `goods_sn_main` (`goods_sn_main`)
) ENGINE=InnoDB AUTO_INCREMENT=2756110 DEFAULT CHARSET=utf8 COMMENT='收藏夹';

-- ----------------------------
-- Table structure for mass_pay_trade_no
-- ----------------------------
DROP TABLE IF EXISTS `mass_pay_trade_no`;
CREATE TABLE `mass_pay_trade_no` (
  `id` int(11) NOT NULL,
  `trade_no` varchar(100) DEFAULT NULL COMMENT '交易号',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='paypal提现，大宗付款交易号';

-- ----------------------------
-- Table structure for member_login_info_log
-- ----------------------------
DROP TABLE IF EXISTS `member_login_info_log`;
CREATE TABLE `member_login_info_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `ip` varchar(15) NOT NULL DEFAULT '',
  `country_code` char(2) NOT NULL DEFAULT '',
  `country_name` varchar(100) NOT NULL DEFAULT '',
  `region_code` varchar(10) NOT NULL DEFAULT '',
  `region_name` varchar(100) NOT NULL DEFAULT '',
  `city_code` varchar(10) NOT NULL DEFAULT '',
  `city` varchar(100) NOT NULL DEFAULT '',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `ip` (`ip`)
) ENGINE=InnoDB AUTO_INCREMENT=67593 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for miss_bonus
-- ----------------------------
DROP TABLE IF EXISTS `miss_bonus`;
CREATE TABLE `miss_bonus` (
  `uid` int(11) unsigned NOT NULL COMMENT '用户ID',
  `type` tinyint(1) DEFAULT NULL COMMENT '类型',
  `create_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='遗漏分红表';

-- ----------------------------
-- Table structure for mobile_message_log
-- ----------------------------
DROP TABLE IF EXISTS `mobile_message_log`;
CREATE TABLE `mobile_message_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `mobile` varchar(15) NOT NULL DEFAULT '' COMMENT '手机号',
  `code` varchar(10) NOT NULL DEFAULT '' COMMENT '验证码',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `status` enum('0','1') DEFAULT '0' COMMENT '默认0 发送成功 1发送失败',
  `is_verify` enum('0','1') DEFAULT '0' COMMENT '默认0 未验证 1验证过',
  `logs` varchar(255) NOT NULL DEFAULT '' COMMENT '短信平台返回记录',
  PRIMARY KEY (`id`),
  KEY `index_mobile` (`mobile`),
  KEY `index_status` (`status`),
  KEY `index_create_time` (`create_time`),
  KEY `index_is_verify` (`is_verify`)
) ENGINE=InnoDB AUTO_INCREMENT=382197 DEFAULT CHARSET=utf8 COMMENT='短信验证码发送记录';

-- ----------------------------
-- Table structure for monthly_fee_coupon
-- ----------------------------
DROP TABLE IF EXISTS `monthly_fee_coupon`;
CREATE TABLE `monthly_fee_coupon` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB AUTO_INCREMENT=6116689 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for month_eminent_store_preview
-- ----------------------------
DROP TABLE IF EXISTS `month_eminent_store_preview`;
CREATE TABLE `month_eminent_store_preview` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `amount` int(11) NOT NULL DEFAULT '0' COMMENT '应发奖金，单位：美分',
  `date` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '预览创建日期',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB AUTO_INCREMENT=208175 DEFAULT CHARSET=utf8 COMMENT='月杰出店铺奖预览';

-- ----------------------------
-- Table structure for month_expense_preview
-- ----------------------------
DROP TABLE IF EXISTS `month_expense_preview`;
CREATE TABLE `month_expense_preview` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `amount` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '应收金额，单位：美分',
  `coupon_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '月费券id',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB AUTO_INCREMENT=1081738 DEFAULT CHARSET=utf8 COMMENT='月费预览表';

-- ----------------------------
-- Table structure for month_fee_change
-- ----------------------------
DROP TABLE IF EXISTS `month_fee_change`;
CREATE TABLE `month_fee_change` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `old_month_fee_pool` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '没转之前的月费池',
  `month_fee_pool` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '现在的月费池',
  `cash` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '转入的金额',
  `admin_id` int(11) NOT NULL DEFAULT '0' COMMENT '管理员ID',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '改變類型1：充值，2：現金池轉月份池，3：月費券4：扣月費，5：月費轉現金池，6：月费抽回',
  `old_coupon_num` int(10) unsigned NOT NULL DEFAULT '0',
  `coupon_num` int(10) unsigned NOT NULL DEFAULT '0',
  `coupon_num_change` int(11) NOT NULL DEFAULT '0',
  `create_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  PRIMARY KEY (`Id`),
  KEY `user_id` (`user_id`),
  KEY `type` (`type`),
  KEY `create_time` (`create_time`)
) ENGINE=InnoDB AUTO_INCREMENT=4774019 DEFAULT CHARSET=utf8 COMMENT='月费转现金池log';

-- ----------------------------
-- Table structure for month_fee_level_change
-- ----------------------------
DROP TABLE IF EXISTS `month_fee_level_change`;
CREATE TABLE `month_fee_level_change` (
  `uid` int(11) NOT NULL DEFAULT '0',
  `new_month_fee_level` tinyint(3) NOT NULL DEFAULT '0' COMMENT '新的月份等级。',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for month_group_share_list
-- ----------------------------
DROP TABLE IF EXISTS `month_group_share_list`;
CREATE TABLE `month_group_share_list` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `sale_amount_weight` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '销售额权重（销售额美分计数）',
  `sale_rank_weight` int(10) unsigned NOT NULL DEFAULT '1' COMMENT '职称权重（职称等级+1）',
  `store_rank_weight` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '店铺等级权重（铜级1，银级店铺2，白金级店铺3，钻石级店铺4）',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='每月团队组织分红发奖信息列表';

-- ----------------------------
-- Table structure for month_group_share_list_201704
-- ----------------------------
DROP TABLE IF EXISTS `month_group_share_list_201704`;
CREATE TABLE `month_group_share_list_201704` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `sale_amount_weight` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '销售额权重（销售额美分计数）',
  `sale_rank_weight` int(10) unsigned NOT NULL DEFAULT '1' COMMENT '职称权重（职称等级+1）',
  `store_rank_weight` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '店铺等级权重（铜级1，银级店铺2，白金级店铺3，钻石级店铺4）'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for month_group_share_list_201705
-- ----------------------------
DROP TABLE IF EXISTS `month_group_share_list_201705`;
CREATE TABLE `month_group_share_list_201705` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `sale_amount_weight` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '销售额权重（销售额美分计数）',
  `sale_rank_weight` int(10) unsigned NOT NULL DEFAULT '1' COMMENT '职称权重（职称等级+1）',
  `store_rank_weight` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '店铺等级权重（铜级1，银级店铺2，白金级店铺3，钻石级店铺4）'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for month_group_share_list_201706
-- ----------------------------
DROP TABLE IF EXISTS `month_group_share_list_201706`;
CREATE TABLE `month_group_share_list_201706` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `sale_amount_weight` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '销售额权重（销售额美分计数）',
  `sale_rank_weight` int(10) unsigned NOT NULL DEFAULT '1' COMMENT '职称权重（职称等级+1）',
  `store_rank_weight` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '店铺等级权重（铜级1，银级店铺2，白金级店铺3，钻石级店铺4）'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for month_leader_bonus
-- ----------------------------
DROP TABLE IF EXISTS `month_leader_bonus`;
CREATE TABLE `month_leader_bonus` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `sharing_point` decimal(14,2) unsigned NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for month_leader_bonus_201704
-- ----------------------------
DROP TABLE IF EXISTS `month_leader_bonus_201704`;
CREATE TABLE `month_leader_bonus_201704` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `sharing_point` decimal(14,2) unsigned NOT NULL DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for month_leader_bonus_201705
-- ----------------------------
DROP TABLE IF EXISTS `month_leader_bonus_201705`;
CREATE TABLE `month_leader_bonus_201705` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `sharing_point` decimal(14,2) unsigned NOT NULL DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for month_leader_bonus_201706
-- ----------------------------
DROP TABLE IF EXISTS `month_leader_bonus_201706`;
CREATE TABLE `month_leader_bonus_201706` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `sharing_point` decimal(14,2) unsigned NOT NULL DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for month_leader_bonus_lv5
-- ----------------------------
DROP TABLE IF EXISTS `month_leader_bonus_lv5`;
CREATE TABLE `month_leader_bonus_lv5` (
  `uid` int(11) NOT NULL,
  `sharing_point` decimal(14,2) DEFAULT '0.00',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for month_leader_bonus_lv5_201704
-- ----------------------------
DROP TABLE IF EXISTS `month_leader_bonus_lv5_201704`;
CREATE TABLE `month_leader_bonus_lv5_201704` (
  `uid` int(11) NOT NULL,
  `sharing_point` decimal(14,2) DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for month_leader_bonus_lv5_201705
-- ----------------------------
DROP TABLE IF EXISTS `month_leader_bonus_lv5_201705`;
CREATE TABLE `month_leader_bonus_lv5_201705` (
  `uid` int(11) NOT NULL,
  `sharing_point` decimal(14,2) DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for month_leader_bonus_lv5_201706
-- ----------------------------
DROP TABLE IF EXISTS `month_leader_bonus_lv5_201706`;
CREATE TABLE `month_leader_bonus_lv5_201706` (
  `uid` int(11) NOT NULL,
  `sharing_point` decimal(14,2) DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for month_sharing_members
-- ----------------------------
DROP TABLE IF EXISTS `month_sharing_members`;
CREATE TABLE `month_sharing_members` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `sharing_point` decimal(14,2) NOT NULL DEFAULT '0.00' COMMENT '分红点（自身和奖励）',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='月分红会员列表';

-- ----------------------------
-- Table structure for month_top_leader_bonus
-- ----------------------------
DROP TABLE IF EXISTS `month_top_leader_bonus`;
CREATE TABLE `month_top_leader_bonus` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `sharing_point` decimal(14,2) unsigned NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for month_top_leader_bonus_201704
-- ----------------------------
DROP TABLE IF EXISTS `month_top_leader_bonus_201704`;
CREATE TABLE `month_top_leader_bonus_201704` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `sharing_point` decimal(14,2) unsigned NOT NULL DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for month_top_leader_bonus_201705
-- ----------------------------
DROP TABLE IF EXISTS `month_top_leader_bonus_201705`;
CREATE TABLE `month_top_leader_bonus_201705` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `sharing_point` decimal(14,2) unsigned NOT NULL DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for month_top_leader_bonus_201706
-- ----------------------------
DROP TABLE IF EXISTS `month_top_leader_bonus_201706`;
CREATE TABLE `month_top_leader_bonus_201706` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `sharing_point` decimal(14,2) unsigned NOT NULL DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mvp_list
-- ----------------------------
DROP TABLE IF EXISTS `mvp_list`;
CREATE TABLE `mvp_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `order_id` char(19) NOT NULL DEFAULT '0' COMMENT '订单id',
  `apply_number` varchar(50) NOT NULL DEFAULT '0' COMMENT '报名号',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`) USING HASH,
  KEY `order_id` (`order_id`) USING HASH
) ENGINE=InnoDB AUTO_INCREMENT=8799 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for my_order_exchange_log
-- ----------------------------
DROP TABLE IF EXISTS `my_order_exchange_log`;
CREATE TABLE `my_order_exchange_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) NOT NULL COMMENT '用户ID',
  `order_id` varchar(100) NOT NULL COMMENT '订单id',
  `status` tinyint(1) NOT NULL COMMENT '换货状态 1换货中 2换货完成 0 取消换货',
  `create_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`,`create_time`) USING BTREE,
  KEY `status` (`order_id`,`status`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=10357201 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for my_order_exchange_time
-- ----------------------------
DROP TABLE IF EXISTS `my_order_exchange_time`;
CREATE TABLE `my_order_exchange_time` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) NOT NULL COMMENT '用户ID',
  `order_id` varchar(255) NOT NULL,
  `create_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP COMMENT '创建时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `order_id` (`order_id`) USING BTREE,
  KEY `create_time` (`uid`,`create_time`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=7520513 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for news
-- ----------------------------
DROP TABLE IF EXISTS `news`;
CREATE TABLE `news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '0' COMMENT '新聞標題',
  `source` varchar(255) NOT NULL DEFAULT '0' COMMENT '來源',
  `content` longtext COMMENT '內容',
  `img` varchar(50) NOT NULL DEFAULT '0' COMMENT '新聞圖片',
  `type` varchar(255) NOT NULL DEFAULT '0' COMMENT '新聞類型： ，0 中文，1 英文',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `sort` int(11) NOT NULL DEFAULT '100' COMMENT '新聞排序',
  `html_content` longtext,
  `hot` tinyint(3) NOT NULL DEFAULT '0' COMMENT '是否是热门新闻 1：热门，0：不是热门',
  `display` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否显示',
  `language_id` smallint(5) NOT NULL DEFAULT '1',
  `cate_id` smallint(5) NOT NULL DEFAULT '0' COMMENT '分类id',
  PRIMARY KEY (`id`),
  KEY `display` (`display`),
  KEY `language_id` (`language_id`),
  KEY `cat_id` (`cate_id`)
) ENGINE=InnoDB AUTO_INCREMENT=98 DEFAULT CHARSET=utf8 COMMENT='新聞表';

-- ----------------------------
-- Table structure for news_type
-- ----------------------------
DROP TABLE IF EXISTS `news_type`;
CREATE TABLE `news_type` (
  `type_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `type_name` varchar(255) NOT NULL DEFAULT '',
  `language_id` smallint(5) NOT NULL DEFAULT '1',
  `sort_order` smallint(5) NOT NULL DEFAULT '99',
  PRIMARY KEY (`type_id`),
  KEY `language_id` (`language_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1135 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for new_member_bonus
-- ----------------------------
DROP TABLE IF EXISTS `new_member_bonus`;
CREATE TABLE `new_member_bonus` (
  `uid` int(10) unsigned NOT NULL,
  `qualified_day` int(8) unsigned NOT NULL DEFAULT '0' COMMENT '合格日期如：20161001',
  `end_day` int(10) NOT NULL COMMENT '结束日期',
  `bonus_shar_weight` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`),
  KEY `end_day` (`uid`,`end_day`) USING BTREE,
  KEY `qualified_day` (`uid`,`qualified_day`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='新会员专享奖';

-- ----------------------------
-- Table structure for new_member_bonus_total_weight
-- ----------------------------
DROP TABLE IF EXISTS `new_member_bonus_total_weight`;
CREATE TABLE `new_member_bonus_total_weight` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '日期 201407',
  `total_weight` bigint(20) NOT NULL DEFAULT '0' COMMENT ' 某日的新会员奖总权重',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uni_create_time` (`create_time`) USING BTREE COMMENT '时间唯一索引'
) ENGINE=InnoDB AUTO_INCREMENT=158 DEFAULT CHARSET=utf8 COMMENT='用户每日新会员奖总权重';

-- ----------------------------
-- Table structure for new_order_trigger_queue
-- ----------------------------
DROP TABLE IF EXISTS `new_order_trigger_queue`;
CREATE TABLE `new_order_trigger_queue` (
  `oid` varchar(40) NOT NULL DEFAULT '0' COMMENT '订单号',
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id(店主id)',
  `order_amount_usd` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '订单金额（美分）',
  `order_profit_usd` int(10) NOT NULL DEFAULT '0' COMMENT '订单利润（美分）',
  `order_year_month` mediumint(6) unsigned NOT NULL DEFAULT '0' COMMENT '订单年月',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`oid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='新订单完成的后续逻辑处理队列表。';

-- ----------------------------
-- Table structure for new_order_trigger_queue_admin_log
-- ----------------------------
DROP TABLE IF EXISTS `new_order_trigger_queue_admin_log`;
CREATE TABLE `new_order_trigger_queue_admin_log` (
  `log_id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_id` int(11) NOT NULL COMMENT '管理员ID',
  `oid` varchar(40) NOT NULL COMMENT '订单ID',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '操作时间',
  PRIMARY KEY (`log_id`),
  UNIQUE KEY `UQE_oid` (`oid`)
) ENGINE=InnoDB AUTO_INCREMENT=3846 DEFAULT CHARSET=utf8 COMMENT='管理员手动发奖日志列表';

-- ----------------------------
-- Table structure for new_order_trigger_queue_err_log
-- ----------------------------
DROP TABLE IF EXISTS `new_order_trigger_queue_err_log`;
CREATE TABLE `new_order_trigger_queue_err_log` (
  `err_id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL COMMENT '用户ID',
  `oid` varchar(40) NOT NULL COMMENT '订单ID',
  `order_amount_usd` int(10) unsigned NOT NULL COMMENT '订单金额（美分）',
  `order_profit_usd` int(11) NOT NULL COMMENT '订单利润（美分）',
  `order_year_month` char(6) NOT NULL COMMENT '订单年月',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`err_id`),
  UNIQUE KEY `UQE_oid` (`oid`)
) ENGINE=InnoDB AUTO_INCREMENT=142 DEFAULT CHARSET=utf8 COMMENT='个人,团队发奖错误日志表';

-- ----------------------------
-- Table structure for one_direct_orders
-- ----------------------------
DROP TABLE IF EXISTS `one_direct_orders`;
CREATE TABLE `one_direct_orders` (
  `order_id` varchar(40) NOT NULL DEFAULT '0' COMMENT '订单号',
  `customer_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '顾客id',
  `shopkeeper_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '店主id',
  `order_amount` decimal(14,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '订单金额',
  `order_profit` decimal(14,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '订单利润',
  `currency` char(3) NOT NULL DEFAULT '0' COMMENT '币种（国际币种标识符）',
  `order_amount_usd` decimal(14,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '订单金额（美元）',
  `order_profit_usd` decimal(14,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '订单利润（美元）',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `score_year_month` char(6) NOT NULL DEFAULT '0' COMMENT '业绩年月',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '默认1=>已完成，2=>已取消',
  `vendor` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`order_id`),
  KEY `idx_shopkeeper_id` (`shopkeeper_id`,`create_time`),
  KEY `idx_customer_id` (`customer_id`,`create_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='商城订单';

-- ----------------------------
-- Table structure for one_direct_order_user_list
-- ----------------------------
DROP TABLE IF EXISTS `one_direct_order_user_list`;
CREATE TABLE `one_direct_order_user_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL COMMENT '用户id',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_uid` (`uid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='美国会员下单队列';

-- ----------------------------
-- Table structure for order_cancel_log
-- ----------------------------
DROP TABLE IF EXISTS `order_cancel_log`;
CREATE TABLE `order_cancel_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT '0',
  `order_id` varchar(25) DEFAULT NULL,
  `type` tinyint(3) DEFAULT '0',
  `content` text,
  `system_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=113383 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for order_pay_url
-- ----------------------------
DROP TABLE IF EXISTS `order_pay_url`;
CREATE TABLE `order_pay_url` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` char(19) NOT NULL,
  `pay_url` varchar(250) NOT NULL COMMENT '支付地址',
  `time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '过期时间',
  PRIMARY KEY (`id`),
  KEY `UQE_order_id` (`order_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8 COMMENT='快付通订单支付地址,用于第二次支付';

-- ----------------------------
-- Table structure for order_prize_queue_log
-- ----------------------------
DROP TABLE IF EXISTS `order_prize_queue_log`;
CREATE TABLE `order_prize_queue_log` (
  `log_id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` varchar(22) NOT NULL COMMENT '订单ID',
  `exec_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '执行时间',
  `exec_result` tinyint(4) NOT NULL DEFAULT '0' COMMENT '执行结果',
  `uid` int(10) unsigned DEFAULT '0' COMMENT '用户ID',
  `order_amount_usd` int(10) unsigned DEFAULT '0' COMMENT '商品金额（美元）。单位：分',
  `order_profit_usd` int(10) unsigned DEFAULT '0' COMMENT '订单利润（美元）。单位：分',
  `score_year_month` char(6) DEFAULT '' COMMENT '业绩年月',
  `err_count` tinyint(4) DEFAULT '0' COMMENT '执行失败次数',
  PRIMARY KEY (`log_id`),
  UNIQUE KEY `UQD_order_id` (`order_id`),
  KEY `IDX_exec_time` (`exec_time`,`exec_result`)
) ENGINE=InnoDB AUTO_INCREMENT=7423678 DEFAULT CHARSET=utf8 COMMENT='发奖订单执行日志记录表';

-- ----------------------------
-- Table structure for order_query_num
-- ----------------------------
DROP TABLE IF EXISTS `order_query_num`;
CREATE TABLE `order_query_num` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` char(19) NOT NULL COMMENT '订单ID',
  `num` int(1) DEFAULT '1' COMMENT '被接口主动查询的次数',
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  KEY `num` (`num`)
) ENGINE=InnoDB AUTO_INCREMENT=1343193 DEFAULT CHARSET=utf8 COMMENT='接口查询交易，计数表，用户记录被接口查询的次数，超过三次就取消订单';

-- ----------------------------
-- Table structure for order_repair_log
-- ----------------------------
DROP TABLE IF EXISTS `order_repair_log`;
CREATE TABLE `order_repair_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `admin_id` int(11) NOT NULL DEFAULT '0' COMMENT '后台操作id',
  `order_year_month` varchar(25) NOT NULL DEFAULT '' COMMENT '补单年月',
  `create_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `item_type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '奖金类型',
  `amount` int(11) NOT NULL DEFAULT '0' COMMENT '补单金额',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21625 DEFAULT CHARSET=utf8 COMMENT='佣金补单日志表';

-- ----------------------------
-- Table structure for paypal_log
-- ----------------------------
DROP TABLE IF EXISTS `paypal_log`;
CREATE TABLE `paypal_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL COMMENT '会员id',
  `paypal_email` varchar(50) DEFAULT NULL COMMENT 'paypal邮箱',
  `time` datetime DEFAULT NULL COMMENT '绑定时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19427 DEFAULT CHARSET=utf8 COMMENT='paypal绑定记录表';

-- ----------------------------
-- Table structure for paypal_pending_log
-- ----------------------------
DROP TABLE IF EXISTS `paypal_pending_log`;
CREATE TABLE `paypal_pending_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` char(19) DEFAULT NULL COMMENT '订单ID',
  `txn_id` varchar(100) DEFAULT NULL COMMENT '交易号',
  `time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `expand` varchar(255) DEFAULT NULL COMMENT '扩展字段',
  `status` int(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8 COMMENT='paypal已延迟过账状态订单记录';

-- ----------------------------
-- Table structure for paypal_remark_list
-- ----------------------------
DROP TABLE IF EXISTS `paypal_remark_list`;
CREATE TABLE `paypal_remark_list` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` char(19) DEFAULT NULL COMMENT '订单ID',
  `admin_user` varchar(100) DEFAULT NULL COMMENT '操作者ID',
  `time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '时间',
  `remark` varchar(255) DEFAULT NULL COMMENT '备注',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8 COMMENT='paypal订单退款记录备注表';

-- ----------------------------
-- Table structure for profit_sharing_point_add_log
-- ----------------------------
DROP TABLE IF EXISTS `profit_sharing_point_add_log`;
CREATE TABLE `profit_sharing_point_add_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `commission_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '關聯佣金ID',
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `add_source` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '分红点的来源1=》销售佣金自动转，2=》见点佣金自动转，3=》分红自动转，4=>手动',
  `money` decimal(12,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '要转化为分红点金钱的数额',
  `point` decimal(12,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '转化的分红点数',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '转化的时间戳',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `add_source` (`add_source`),
  KEY `create_time` (`create_time`)
) ENGINE=InnoDB AUTO_INCREMENT=69618332 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for profit_sharing_point_proportion
-- ----------------------------
DROP TABLE IF EXISTS `profit_sharing_point_proportion`;
CREATE TABLE `profit_sharing_point_proportion` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `proportion_type` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '分红点类型1=>销售佣金转分红点；2=>见点佣金转分红点；3=>分红转分红点',
  `proportion` decimal(5,2) unsigned DEFAULT '0.00' COMMENT '分红点转化比例。单位%',
  PRIMARY KEY (`uid`,`proportion_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='客户分红点转换比例配置表';

-- ----------------------------
-- Table structure for profit_sharing_point_reduce_log
-- ----------------------------
DROP TABLE IF EXISTS `profit_sharing_point_reduce_log`;
CREATE TABLE `profit_sharing_point_reduce_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `point` decimal(12,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '减少的分红点数',
  `money` decimal(12,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '转化成的相应金额',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '转化的时间戳',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB AUTO_INCREMENT=445678 DEFAULT CHARSET=utf8 COMMENT='分红点转出日志表';

-- ----------------------------
-- Table structure for profit_stat
-- ----------------------------
DROP TABLE IF EXISTS `profit_stat`;
CREATE TABLE `profit_stat` (
  `profit_date` int(11) NOT NULL COMMENT '利润的日期，如2016（按年）或201605（按月）或者20160515（按日）',
  `profit` bigint(14) NOT NULL DEFAULT '0' COMMENT '利润值（单位：分）',
  PRIMARY KEY (`profit_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for reissue_138_tmp
-- ----------------------------
DROP TABLE IF EXISTS `reissue_138_tmp`;
CREATE TABLE `reissue_138_tmp` (
  `uid` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='未进入138排序名单，补发金额';

-- ----------------------------
-- Table structure for report_week_leader
-- ----------------------------
DROP TABLE IF EXISTS `report_week_leader`;
CREATE TABLE `report_week_leader` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '获得周奖的会员id',
  `cid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '下线id',
  `comm_type_txt` varchar(45) NOT NULL,
  `amount` decimal(14,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=236 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for reset_group_log
-- ----------------------------
DROP TABLE IF EXISTS `reset_group_log`;
CREATE TABLE `reset_group_log` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `order_id` char(19) NOT NULL DEFAULT '0',
  `reset_type` int(11) NOT NULL DEFAULT '0',
  `admin_id` int(11) NOT NULL DEFAULT '0',
  `create_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=221 DEFAULT CHARSET=utf8 COMMENT='重置套餐日志';

-- ----------------------------
-- Table structure for save_user_for_138
-- ----------------------------
DROP TABLE IF EXISTS `save_user_for_138`;
CREATE TABLE `save_user_for_138` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `create_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`Id`),
  KEY `user_id` (`user_id`) USING HASH
) ENGINE=InnoDB AUTO_INCREMENT=2646562 DEFAULT CHARSET=utf8 COMMENT='保存138排序的用户';

-- ----------------------------
-- Table structure for sequence
-- ----------------------------
DROP TABLE IF EXISTS `sequence`;
CREATE TABLE `sequence` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `value` bigint(20) NOT NULL,
  `gmt_modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for sequence_opt
-- ----------------------------
DROP TABLE IF EXISTS `sequence_opt`;
CREATE TABLE `sequence_opt` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `value` bigint(20) unsigned NOT NULL,
  `increment_by` int(10) unsigned NOT NULL DEFAULT '1',
  `start_with` bigint(20) unsigned NOT NULL DEFAULT '1',
  `max_value` bigint(20) unsigned NOT NULL DEFAULT '18446744073709551615',
  `cycle` tinyint(5) unsigned NOT NULL DEFAULT '0',
  `gmt_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `gmt_modified` timestamp NOT NULL DEFAULT '1970-02-01 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for single_task_control
-- ----------------------------
DROP TABLE IF EXISTS `single_task_control`;
CREATE TABLE `single_task_control` (
  `task_name` varchar(35) NOT NULL DEFAULT '' COMMENT '任务名称',
  `run_cycle_num` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '任务运行消耗了的周期数。',
  PRIMARY KEY (`task_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for sphinx_counter
-- ----------------------------
DROP TABLE IF EXISTS `sphinx_counter`;
CREATE TABLE `sphinx_counter` (
  `counter_id` int(11) NOT NULL COMMENT '标识不同的数据表',
  `max_doc_id` int(11) NOT NULL COMMENT '每个索引表的最大ID,会实时更新',
  PRIMARY KEY (`counter_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for sphinx_counter2
-- ----------------------------
DROP TABLE IF EXISTS `sphinx_counter2`;
CREATE TABLE `sphinx_counter2` (
  `counter_id` int(11) NOT NULL COMMENT '标识不同的数据表',
  `max_doc_id` int(11) NOT NULL COMMENT '每个索引表的最大ID,会实时更新',
  PRIMARY KEY (`counter_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for stat_intr_mem_month
-- ----------------------------
DROP TABLE IF EXISTS `stat_intr_mem_month`;
CREATE TABLE `stat_intr_mem_month` (
  `year_month` mediumint(6) unsigned NOT NULL DEFAULT '0' COMMENT '年月，比如：201502',
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '被统计的会员id',
  `member_free_num` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `member_silver_num` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `member_platinum_num` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `member_diamond_num` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `qualified_orders_num` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '合格订单数',
  `self_orders_num` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '自己',
  `member_bronze_num` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `pro_set_amount` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '推荐人的产品套装销售额（美分）',
  PRIMARY KEY (`year_month`,`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='统计会员每月推荐的人';

-- ----------------------------
-- Table structure for supplier_recommendation_preview
-- ----------------------------
DROP TABLE IF EXISTS `supplier_recommendation_preview`;
CREATE TABLE `supplier_recommendation_preview` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `amount` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '应发奖金，单位：美分',
  `date` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '预览创建日期',
  `name` varchar(100) NOT NULL DEFAULT '',
  `num` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='供应商推荐奖预览表';

-- ----------------------------
-- Table structure for sync_charge_month_fee
-- ----------------------------
DROP TABLE IF EXISTS `sync_charge_month_fee`;
CREATE TABLE `sync_charge_month_fee` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '会员id',
  `fail_time` timestamp NULL DEFAULT NULL COMMENT '非空：欠月费的用户，空值：正常交月费用户',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='异步扣取月费';

-- ----------------------------
-- Table structure for sync_ip_to_address
-- ----------------------------
DROP TABLE IF EXISTS `sync_ip_to_address`;
CREATE TABLE `sync_ip_to_address` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip` varchar(20) NOT NULL DEFAULT '0' COMMENT 'IP地址',
  `is_zh` int(11) DEFAULT '0' COMMENT '中国同步',
  `is_english` int(11) DEFAULT '0' COMMENT '国外同步',
  PRIMARY KEY (`id`),
  KEY `ip` (`ip`)
) ENGINE=InnoDB AUTO_INCREMENT=139433929 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for sync_month_fee_fail_info
-- ----------------------------
DROP TABLE IF EXISTS `sync_month_fee_fail_info`;
CREATE TABLE `sync_month_fee_fail_info` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '失败时间',
  `ids` text NOT NULL COMMENT '扣除月费失败的会员id 已逗号分隔',
  `uid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='存储过程扣除月费失败错误记录表';

-- ----------------------------
-- Table structure for sync_send_receipt_email
-- ----------------------------
DROP TABLE IF EXISTS `sync_send_receipt_email`;
CREATE TABLE `sync_send_receipt_email` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `order_id` char(19) NOT NULL DEFAULT '0' COMMENT 'order_id',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '0:未处理,1:已处理,2:文件不存在',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0：默认收据邮件 1：上传运单号，发货通知，2：4月份活动订单 ，3：当天欠月份 ，4：6天仍未付费的会员发送提醒邮件，5：给7天以上90天内为支付月费的会员发送提醒邮件，每周发送一次（第8天单独发送一次）',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=638173 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for sync_to_wohao
-- ----------------------------
DROP TABLE IF EXISTS `sync_to_wohao`;
CREATE TABLE `sync_to_wohao` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '会员id',
  `sync_item` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '0代表同步所有；1 email；2 pwd；3 pwd_token；4 parent_id；5 languageid；6 name；7 mobile；8 country_id；9 address；10 store_prefix；11 store_level；12 create_time；13 status',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8517024 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for system_rebate_conf
-- ----------------------------
DROP TABLE IF EXISTS `system_rebate_conf`;
CREATE TABLE `system_rebate_conf` (
  `id` int(4) unsigned NOT NULL AUTO_INCREMENT,
  `category_id` int(4) unsigned NOT NULL DEFAULT '0' COMMENT '分类ID',
  `child_id` int(4) unsigned DEFAULT '0' COMMENT '子级',
  `rate_a` float(4,4) NOT NULL DEFAULT '0.0000' COMMENT '参数1',
  `rate_b` float(3,3) NOT NULL DEFAULT '0.000' COMMENT '参数2',
  `rate_c` float(3,3) NOT NULL DEFAULT '0.000' COMMENT '参数3',
  `rate_d` float(3,3) NOT NULL DEFAULT '0.000' COMMENT '参数4',
  `rate_e` float(3,3) NOT NULL DEFAULT '0.000' COMMENT '参数5',
  PRIMARY KEY (`id`),
  KEY `child_id` (`child_id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='系统分红比例';

-- ----------------------------
-- Table structure for system_rebate_conf_child
-- ----------------------------
DROP TABLE IF EXISTS `system_rebate_conf_child`;
CREATE TABLE `system_rebate_conf_child` (
  `id` int(4) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(4) unsigned NOT NULL DEFAULT '0' COMMENT '关联类型ID',
  `name` varchar(50) NOT NULL COMMENT '名称',
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='系统分红比例子表';

-- ----------------------------
-- Table structure for temp_save_coupons
-- ----------------------------
DROP TABLE IF EXISTS `temp_save_coupons`;
CREATE TABLE `temp_save_coupons` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `order_id` char(19) NOT NULL DEFAULT '0' COMMENT '订单id',
  `coupons_value` int(11) NOT NULL DEFAULT '0' COMMENT '代品券面额',
  `coupons_num` int(11) NOT NULL DEFAULT '0' COMMENT '代品券数量',
  PRIMARY KEY (`Id`),
  KEY `user_id` (`user_id`),
  KEY `order_id` (`order_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1850646 DEFAULT CHARSET=utf8 COMMENT='临时保存券表';

-- ----------------------------
-- Table structure for temp_uid
-- ----------------------------
DROP TABLE IF EXISTS `temp_uid`;
CREATE TABLE `temp_uid` (
  `uid` int(11) NOT NULL COMMENT '用户id',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for temp_uid_cash
-- ----------------------------
DROP TABLE IF EXISTS `temp_uid_cash`;
CREATE TABLE `temp_uid_cash` (
  `uid` int(11) DEFAULT NULL,
  `create_time` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='临时表 韩国缴费会员是否可以拿到日分红或其他奖励';

-- ----------------------------
-- Table structure for test_ly
-- ----------------------------
DROP TABLE IF EXISTS `test_ly`;
CREATE TABLE `test_ly` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for tmp_uid_cash_elite
-- ----------------------------
DROP TABLE IF EXISTS `tmp_uid_cash_elite`;
CREATE TABLE `tmp_uid_cash_elite` (
  `uid` int(11) DEFAULT NULL,
  `create_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='补发精英日分红';

-- ----------------------------
-- Table structure for tmp_user_rank123
-- ----------------------------
DROP TABLE IF EXISTS `tmp_user_rank123`;
CREATE TABLE `tmp_user_rank123` (
  `id` int(10) unsigned NOT NULL DEFAULT '0',
  `name` varchar(100) NOT NULL DEFAULT '0' COMMENT '名称',
  `email` varchar(100) NOT NULL DEFAULT '0',
  `mobile` varchar(50) NOT NULL DEFAULT '0',
  `address` varchar(255) NOT NULL DEFAULT '0',
  `pwd` char(40) NOT NULL DEFAULT '0',
  `pwd_ori_md5` char(32) NOT NULL DEFAULT '0' COMMENT '密码原文的md5，放在商城那边无法改变加密方式导致无法同步用户密码',
  `pwd_take_out_cash` char(40) NOT NULL DEFAULT '0' COMMENT '提现密码',
  `parent_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '推荐人的id',
  `parent_ids` longtext COMMENT '所有历代推荐id，以逗号分隔',
  `languageid` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '会员注册语种：1=中文,2=英文,3=繁体中文',
  `user_rank` tinyint(3) unsigned NOT NULL DEFAULT '4' COMMENT '用户等级, 1钻石，2白金级，3银级，4免费会员，5铜级会员',
  `month_fee_rank` tinyint(3) unsigned NOT NULL DEFAULT '4' COMMENT '月费等级：4免费，3银级，2白金，1钻石',
  `sale_rank` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '0普通店主，1资深店主，2销售经理，3销售主任，4销售总监，5全球销售副总裁',
  `sale_rank_up_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '会员职称更新时间',
  `amount` decimal(14,2) NOT NULL DEFAULT '0.00' COMMENT '用户余额',
  `amount_store_commission` decimal(14,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '用户个人店铺提成总金额',
  `amount_profit_sharing_comm` decimal(14,2) NOT NULL DEFAULT '0.00' COMMENT '用户日分红总金额',
  `amount_weekly_Leader_comm` decimal(14,2) NOT NULL DEFAULT '0.00' COMMENT '周领导对等奖总金额',
  `amount_monthly_leader_comm` decimal(14,2) NOT NULL DEFAULT '0.00' COMMENT '月领导分红总金额',
  `profit_sharing_point` decimal(14,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '用户总分红点',
  `profit_sharing_point_from_sale` decimal(14,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '销售佣金自动转入的分红点',
  `profit_sharing_point_from_force` decimal(14,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '见点佣金自动转入的分红点',
  `profit_sharing_point_from_sharing` decimal(14,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '分红佣金自动转入分红点',
  `profit_sharing_point_manually` decimal(14,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '手动转入的分红点',
  `profit_sharing_point_to_money` decimal(14,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '分红点转现金',
  `month_fee_pool` decimal(14,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '月费池',
  `user_point` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户积分',
  `country_id` tinyint(3) unsigned DEFAULT NULL COMMENT '国家id',
  `id_card_num` varchar(50) NOT NULL DEFAULT '0' COMMENT '身份证号码',
  `id_card_scan` varchar(50) NOT NULL DEFAULT '0' COMMENT '身份证扫描件',
  `alipay_account` varchar(50) NOT NULL DEFAULT '0' COMMENT '支付宝账户',
  `alipay_name` varchar(50) NOT NULL DEFAULT '0' COMMENT '支付宝姓名',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '注册时间',
  `send_email_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '發送激活郵件時間',
  `send_email_token` varchar(255) NOT NULL DEFAULT '0' COMMENT '发送邮件token',
  `token` char(32) NOT NULL DEFAULT '0' COMMENT 'md5后的一个用户安全加密码',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '状态0->未激活，1->激活(正常、包括7天内未支付月费的会员)，2->拖欠月费7天（月费会员），3->冻结账户（不欠月费）,4->公司预留账户，5->冻结账户（欠月费）',
  `enable_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '激活时间',
  `month_fee_date` tinyint(2) NOT NULL DEFAULT '0' COMMENT '月费日',
  `update_token_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用于用户更新信息时生成token以及过期验证',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `upgrade_month_fee_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '升级月费时间',
  `upgrade_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '升級時的時間',
  `img` varchar(255) NOT NULL DEFAULT '0' COMMENT '用户头像',
  `store_url` varchar(255) NOT NULL DEFAULT '0' COMMENT '用户店铺url',
  `store_url_modify_counts` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '店铺url修改次数',
  `member_url_prefix` varchar(45) DEFAULT NULL,
  `member_url_modify_counts` tinyint(3) DEFAULT '0',
  `personal_commission` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '2x5佣金',
  `company_commission` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '138佣金',
  `team_commission` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '团队销售提成奖',
  `infinite_commission` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '无限代提成奖',
  `ewallet_name` varchar(100) NOT NULL DEFAULT '0' COMMENT '電子錢包的賬號',
  `child_count` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '父類所有激活的會員',
  `from` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '注册自哪里0:tps；1:沃好',
  `sync_walhao` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否同步到walhao，0未同步，1同步',
  `store_qualified` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否合格店铺：0不合格，1合格',
  `is_choose` int(1) NOT NULL DEFAULT '0',
  `store_name` varchar(250) NOT NULL DEFAULT '0' COMMENT '店铺名称',
  `first_monthly_fee_level` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '会员第一个月的月费等级。（系统记录其第一个月中首次的等级）',
  `alert_count` int(11) DEFAULT '0' COMMENT '超重要通知，强制弹出提醒次数',
  `is_auto` int(2) NOT NULL DEFAULT '0' COMMENT '是否自动转月费池(0=>否,1=>是)',
  `is_auto_notice` int(2) NOT NULL DEFAULT '0' COMMENT '是否弹出自动转月费的通知(0=>弹出，1=>不弹)',
  `is_verified_email` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否已验证Email，0：未验证，1：已验证，验证后才能用Email登陆',
  `is_verified_mobile` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否已验证mobile，0：未验证，1：已验证，验证后才能用phone登陆'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for trade_addr_linkage
-- ----------------------------
DROP TABLE IF EXISTS `trade_addr_linkage`;
CREATE TABLE `trade_addr_linkage` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `country_code` char(3) NOT NULL COMMENT '国家代码，参考 ISO 3166-1 标准',
  `code` varchar(11) NOT NULL COMMENT '地区代码',
  `parent_code` varchar(11) NOT NULL COMMENT '父级地区代码。无父级则为0',
  `name` varchar(64) NOT NULL COMMENT '名称',
  `level` enum('1','2','3','4','5') NOT NULL COMMENT '级别。中国 1 国家、2 省份/直辖市、3 城市、4 区/县、5 乡镇/街道 | 美国 1 国家、2 州 | 韩国 1 国家、2 市/道',
  PRIMARY KEY (`id`),
  KEY `country_code` (`country_code`),
  KEY `code` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=22964 DEFAULT CHARSET=utf8 COMMENT='用户收货信息级联地址数据表';

-- ----------------------------
-- Table structure for trade_freight
-- ----------------------------
DROP TABLE IF EXISTS `trade_freight`;
CREATE TABLE `trade_freight` (
  `company_code` smallint(5) unsigned NOT NULL COMMENT '公司代码',
  `company_shortname` varchar(128) NOT NULL DEFAULT '' COMMENT '公司简称',
  `company_name` varchar(128) NOT NULL COMMENT '公司全称',
  `tracking_url` varchar(255) NOT NULL DEFAULT '' COMMENT '运单追踪网址的 url',
  PRIMARY KEY (`company_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for trade_freight_fee
-- ----------------------------
DROP TABLE IF EXISTS `trade_freight_fee`;
CREATE TABLE `trade_freight_fee` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `country_code` int(11) NOT NULL DEFAULT '0' COMMENT '國家代碼',
  `begin_code` int(11) NOT NULL DEFAULT '0' COMMENT '始发地：省份code',
  `dest_code` int(11) NOT NULL DEFAULT '0' COMMENT '目的地：省份code',
  `start_weight_fee` int(11) NOT NULL DEFAULT '0' COMMENT '始重运费',
  `add_weight_fee` int(11) NOT NULL DEFAULT '0' COMMENT '续重运费',
  `goods_sn_main` int(11) NOT NULL DEFAULT '0' COMMENT '产品sku：针对特定产品的运费',
  `goods_type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0:普通產品，1:特定產品,2:預留',
  PRIMARY KEY (`id`),
  KEY `country_code` (`country_code`),
  KEY `dest_code` (`dest_code`),
  KEY `goods_sn_main` (`goods_sn_main`),
  KEY `begin_code` (`begin_code`,`country_code`,`dest_code`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=233370 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for trade_freight_fee_international
-- ----------------------------
DROP TABLE IF EXISTS `trade_freight_fee_international`;
CREATE TABLE `trade_freight_fee_international` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `goods_sn_main` varchar(50) NOT NULL DEFAULT '0' COMMENT '商品主SKU',
  `country_id` varchar(10) NOT NULL DEFAULT '0' COMMENT '销售国家',
  `freight_fee` int(11) NOT NULL DEFAULT '0' COMMENT '运费(美金)',
  `currency` varchar(10) NOT NULL DEFAULT 'USD' COMMENT '币种',
  `create_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `update_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  `admin_id` int(5) NOT NULL DEFAULT '0' COMMENT '管理员id',
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=198 DEFAULT CHARSET=utf8 COMMENT='国际运费表';

-- ----------------------------
-- Table structure for trade_inovice_order
-- ----------------------------
DROP TABLE IF EXISTS `trade_inovice_order`;
CREATE TABLE `trade_inovice_order` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `invoice_num` varchar(30) NOT NULL COMMENT '发票单号',
  `order_id` char(19) NOT NULL COMMENT '订单号',
  `order_type` tinyint(3) NOT NULL COMMENT '订单类型',
  `money` int(10) NOT NULL DEFAULT '0' COMMENT '订单金额',
  `express_free` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '运费',
  `status` tinyint(3) NOT NULL COMMENT '订单类型',
  `mark` tinyint(1) NOT NULL DEFAULT '0' COMMENT '标示 是否开过',
  `cateids` varchar(255) NOT NULL DEFAULT '' COMMENT '所属类别ID',
  PRIMARY KEY (`id`),
  KEY `innvoce_num` (`invoice_num`) USING BTREE,
  KEY `status` (`order_type`,`status`,`mark`) USING BTREE,
  KEY `order_id` (`order_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2616 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for trade_invoice
-- ----------------------------
DROP TABLE IF EXISTS `trade_invoice`;
CREATE TABLE `trade_invoice` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `invoice_num` varchar(30) NOT NULL COMMENT '开票单号',
  `uid` int(10) NOT NULL COMMENT '会员id',
  `invoice_start_time` varchar(20) NOT NULL COMMENT '开票开始日期',
  `invoice_end_time` varchar(20) NOT NULL COMMENT '开票结束时间',
  `invoice_total_money` int(10) NOT NULL DEFAULT '0' COMMENT '可开票总金额 单位：分',
  `invoice_fact_money` int(10) NOT NULL DEFAULT '0' COMMENT '实际开票总金额 单位：分',
  `invoice_head` varchar(255) NOT NULL COMMENT '开票抬头',
  `invoice_type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '发票类型',
  `invoice_address` varchar(255) DEFAULT NULL COMMENT '收货地址',
  `zh_express_free` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '人民币——快递费',
  `us_express_free` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '美元——快递费',
  `rate` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '汇率——人民币转$',
  `recipient` char(10) DEFAULT NULL COMMENT '收件人',
  `email` char(28) DEFAULT NULL COMMENT '收票邮箱',
  `mobile` char(11) NOT NULL COMMENT '手机号',
  `backup_num` char(50) DEFAULT '' COMMENT '备用号码',
  `remark` text COMMENT '备注',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0：未开票 1：已开票 2：已邮寄  4：已完成 8：驳回 9：作废',
  `invoice_code` text COMMENT '发票编号',
  `express_num` varchar(20) DEFAULT NULL COMMENT '快递单号',
  `invoice_details` text NOT NULL COMMENT '开票明细',
  `cannel_remark` text COMMENT '驳回备注',
  `express_arrive` tinyint(1) DEFAULT NULL COMMENT '快递到达类型 1次晨达 2次日达 3隔日达',
  `js_invoicenum` varchar(30) DEFAULT '' COMMENT '与其它发票一起寄送 发票单号',
  `adminid` int(10) NOT NULL DEFAULT '0' COMMENT '创建者ID',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `update_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  `invoice_type_2` tinyint(4) NOT NULL DEFAULT '1' COMMENT '发票类型 1 普通发票 2 增值税发票',
  `invoice_type_2_content` text COMMENT '发票类型内容',
  `invoice_taxpayer_id_number` varchar(100) NOT NULL DEFAULT '0' COMMENT '纳税识别号',
  PRIMARY KEY (`id`),
  UNIQUE KEY `invoice_num` (`invoice_num`) USING BTREE,
  KEY `invoice_group` (`uid`,`invoice_start_time`,`invoice_end_time`) USING BTREE,
  KEY `express_num` (`express_num`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=700 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for trade_invoice_log
-- ----------------------------
DROP TABLE IF EXISTS `trade_invoice_log`;
CREATE TABLE `trade_invoice_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `operator_id` int(10) NOT NULL COMMENT '操作者ID',
  `invoice_num` varchar(30) NOT NULL COMMENT '开票单号',
  `status` tinyint(1) NOT NULL COMMENT '状态',
  `remark` text NOT NULL COMMENT '备注',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2104 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for trade_invoice_sf_free
-- ----------------------------
DROP TABLE IF EXISTS `trade_invoice_sf_free`;
CREATE TABLE `trade_invoice_sf_free` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `operator_id` int(10) NOT NULL DEFAULT '0' COMMENT '创建者ID',
  `country_id` smallint(3) NOT NULL DEFAULT '156' COMMENT '国家ID 156中国',
  `provice_id` tinyint(2) NOT NULL COMMENT '省份ID',
  `city_id` int(6) NOT NULL COMMENT '城市ID',
  `area_id` int(8) NOT NULL COMMENT '区域ID',
  `country_fast_free` int(10) NOT NULL DEFAULT '0' COMMENT '最快 次晨达',
  `country_second_free` int(10) NOT NULL DEFAULT '0' COMMENT '第二快 次日达',
  `country_slow_free` int(10) NOT NULL DEFAULT '0' COMMENT '最慢 隔日达',
  `provice_fast_free` int(10) NOT NULL DEFAULT '0' COMMENT '省份最快',
  `provice_second_free` int(10) NOT NULL DEFAULT '0' COMMENT '省份第二快',
  `provice_slow_free` int(10) NOT NULL DEFAULT '0' COMMENT '省份最慢',
  `city_fast_free` int(10) NOT NULL DEFAULT '0' COMMENT '城市最快',
  `city_second_free` int(10) NOT NULL DEFAULT '0' COMMENT '城市第二快',
  `city_slow_free` int(10) NOT NULL DEFAULT '0' COMMENT '城市最慢',
  `area_fast_free` int(10) NOT NULL DEFAULT '0' COMMENT '区域最快',
  `area_second_free` int(10) NOT NULL DEFAULT '0' COMMENT '区域第二快',
  `area_slow_free` int(10) NOT NULL DEFAULT '0' COMMENT '区域最慢',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  `provice_name` varchar(50) NOT NULL DEFAULT '' COMMENT '省份名称',
  `city_name` varchar(50) NOT NULL DEFAULT '' COMMENT '城市名称',
  `area_name` varchar(50) NOT NULL DEFAULT '' COMMENT '区域名称',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=72 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for trade_orders
-- ----------------------------
DROP TABLE IF EXISTS `trade_orders`;
CREATE TABLE `trade_orders` (
  `order_id` char(19) NOT NULL DEFAULT '' COMMENT '订单号。格式：X+datetime+四位随机数。X：普通订单为N；子订单为C；子订单的主单为P。',
  `order_prop` enum('0','1','2','3') NOT NULL DEFAULT '0' COMMENT '订单性质。0 普通订单; 1 拆单后的子订单; 2 拆单的子订单所关联的主订单; 3 普通订单合单后的主订单',
  `attach_id` char(19) NOT NULL DEFAULT '' COMMENT '关联id。若订单为子订单，关联id则为该子订单的主订单id。若无关联则为order_id',
  `customer_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '顾客id',
  `shopkeeper_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '店主id',
  `area` char(3) NOT NULL DEFAULT '' COMMENT '区域代码。一般为国家代码，东南亚地区为001，其他地区为000',
  `consignee` varchar(255) NOT NULL DEFAULT '' COMMENT '收货人',
  `phone` varchar(24) DEFAULT NULL COMMENT '联系电话',
  `reserve_num` varchar(24) DEFAULT NULL COMMENT '备用电话',
  `address` varchar(512) NOT NULL DEFAULT '' COMMENT '收货地址',
  `country_address` varchar(50) NOT NULL DEFAULT '' COMMENT '收貨國家和收貨地址分離，單獨開來',
  `zip_code` varchar(16) DEFAULT '' COMMENT '邮政编码',
  `customs_clearance` varchar(32) DEFAULT '' COMMENT '海关报关号。目前只有韩国需要',
  `deliver_time_type` enum('1','2','3') NOT NULL DEFAULT '1' COMMENT '送货时间类型。1 周一至周日均可；2 仅周一至周五；3 仅周六日、节假日',
  `expect_deliver_date` date DEFAULT NULL COMMENT '预计发货时间。日期',
  `goods_list` text NOT NULL COMMENT '商品列表。格式：goods_sn:quantity$goods_sn:quantity ...',
  `remark` varchar(128) DEFAULT NULL COMMENT '订单备注。',
  `need_receipt` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要收据。0 不需要；1 需要',
  `payment_type` enum('0','1','2','105','106','107','108','109','110','111','104') NOT NULL DEFAULT '0' COMMENT '支付方式。0 未支付；1 选择套装; 2 代品券换购; 105 支付宝；106 银联；107 paypal；108 ewallet；109 银盛；110 余额支付；111 銀聯國際;',
  `currency` varchar(16) NOT NULL DEFAULT 'USD' COMMENT '币种',
  `currency_rate` decimal(12,6) NOT NULL DEFAULT '1.000000' COMMENT '下单时兑美元汇率',
  `discount_type` enum('0','1','2') NOT NULL DEFAULT '0' COMMENT '折扣类型。0 无折扣；1 获取代品券，2使用代品券',
  `goods_amount` int(11) NOT NULL DEFAULT '0' COMMENT '商品总金额。单位：分',
  `deliver_fee` int(11) NOT NULL DEFAULT '0' COMMENT '订单运费。单位：分',
  `order_amount` int(11) NOT NULL DEFAULT '0' COMMENT '订单实付金额。单位：分',
  `format_paid_amount` varchar(50) DEFAULT '0',
  `goods_amount_usd` int(11) NOT NULL DEFAULT '0' COMMENT '商品金额（美元）。单位：分',
  `deliver_fee_usd` int(11) NOT NULL DEFAULT '0' COMMENT '订单运费（美元）。单位：分',
  `discount_amount_usd` int(11) NOT NULL DEFAULT '0' COMMENT '折扣金额（美元）。单位：分',
  `order_amount_usd` int(11) NOT NULL DEFAULT '0' COMMENT '订单金额（美元）。单位：分',
  `order_profit_usd` int(11) NOT NULL DEFAULT '0' COMMENT '订单利润（美元）。单位：分',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `notify_num` int(11) NOT NULL DEFAULT '0' COMMENT 'call支付接口的次数',
  `txn_id` varchar(100) NOT NULL DEFAULT '0' COMMENT '交易号',
  `pay_time` timestamp NULL DEFAULT NULL COMMENT '交易時間',
  `store_code` varchar(32) NOT NULL DEFAULT '' COMMENT '仓库简码',
  `freight_info` text COMMENT '物流信息。code|id。物流公司代号|快递单号',
  `deliver_time` timestamp NULL DEFAULT NULL COMMENT '发货时间',
  `receive_time` timestamp NULL DEFAULT NULL COMMENT '收货时间',
  `status` enum('1','2','3','4','5','6','90','97','98','99','100','111') NOT NULL DEFAULT '1' COMMENT '订单状态。1 正在发货中；2 等待付款；3 等待发货；4 等待收货；5 等待评价；6 已完成；90 冻结；97 退货中；98 退货完成；99 订单取消；100 拆分（主单专属）；111 doba异常订单',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  `is_export_lock` tinyint(4) NOT NULL DEFAULT '0' COMMENT '导出订单后，锁定，就不能取消订单。0：未锁定，1：锁定',
  `is_doba_order` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '订单中是否含有doba产品',
  `doba_supplier_id` varchar(32) DEFAULT NULL,
  `supplier_id` int(11) NOT NULL DEFAULT '0' COMMENT '供应商id',
  `shipper_id` int(11) NOT NULL DEFAULT '0' COMMENT '发货商id',
  `order_type` int(3) NOT NULL DEFAULT '0' COMMENT '订单类型(0=>未定义，1=>选购，2=>升级，3=>代品券，4=>普通订单)',
  `score_year_month` mediumint(6) unsigned NOT NULL DEFAULT '0' COMMENT '业绩月份',
  `ID_no` varchar(50) NOT NULL DEFAULT '' COMMENT '证件号码/护照号码',
  `ID_front` varchar(150) NOT NULL DEFAULT '' COMMENT '身份证正面',
  `ID_reverse` varchar(150) NOT NULL DEFAULT '' COMMENT '身份证背面',
  PRIMARY KEY (`order_id`),
  KEY `INDEX_CREATED_AT` (`created_at`),
  KEY `INDEX_STATUS` (`status`),
  KEY `IDX_ATTACH_ID` (`attach_id`),
  KEY `idx_shopkeeper_id` (`shopkeeper_id`),
  KEY `customer_id` (`customer_id`),
  KEY `area` (`area`),
  KEY `pay_time` (`pay_time`),
  KEY `payment_type` (`payment_type`),
  KEY `deliver_time` (`deliver_time`),
  KEY `txn_id` (`txn_id`),
  KEY `index_shipperid_created` (`shipper_id`,`created_at`),
  KEY `idx_idfront_idreverse` (`ID_front`,`ID_reverse`),
  KEY `IDX_SH_ST_PA` (`shipper_id`,`status`,`pay_time`),
  KEY `idx_order_prop` (`order_prop`) USING BTREE,
  FULLTEXT KEY `freight_info` (`freight_info`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='商城订单';

-- ----------------------------
-- Table structure for trade_orders_1706
-- ----------------------------
DROP TABLE IF EXISTS `trade_orders_1706`;
CREATE TABLE `trade_orders_1706` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `order_id` char(19) NOT NULL COMMENT '订单ID',
  `order_prop` tinyint(4) NOT NULL DEFAULT '0' COMMENT '订单性质,0 普通订单; 1 拆单后的子订单; 2 拆单的子订单所关联的主订单; 3 普通订单合单后的主订单',
  `attach_id` char(19) NOT NULL COMMENT '关联订单ID',
  `customer_id` int(11) NOT NULL DEFAULT '0' COMMENT '顾客id',
  `shopkeeper_id` int(11) NOT NULL DEFAULT '0' COMMENT '店主id',
  `area` char(3) NOT NULL DEFAULT '001' COMMENT '区域代码。一般为国家代码，东南亚地区为001，其他地区为000',
  `deliver_time_type` tinyint(4) DEFAULT '1' COMMENT '送货时间类型。1 周一至周日均可；2 仅周一至周五；3 仅周六日、节假日',
  `expect_deliver_date` date DEFAULT NULL COMMENT '预计发货时间',
  `need_receipt` tinyint(4) DEFAULT '0' COMMENT '是否需要收据。0 不需要；1 需要',
  `currency` char(3) DEFAULT 'USD' COMMENT '币种, USD,RMB',
  `payment_type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '支付方式。0 未支付；1 选择套装; 2 代品券换购; 105 支付宝；106 银联；107 paypal；108 ewallet；109 银盛；110 余额支付；111 銀聯國際;',
  `currency_rate` decimal(12,6) DEFAULT '1.000000' COMMENT '下单时兑美元汇率',
  `discount_type` tinyint(4) DEFAULT '0' COMMENT '折扣类型。0 无折扣；1 获取代品券，2使用代品券',
  `goods_amount` int(11) NOT NULL DEFAULT '0' COMMENT '商品总金额。单位：分',
  `deliver_fee` int(11) NOT NULL DEFAULT '0' COMMENT '订单运费。单位：分',
  `order_amount` int(11) NOT NULL DEFAULT '0' COMMENT '订单实付金额。单位：分',
  `goods_amount_usd` int(11) NOT NULL DEFAULT '0' COMMENT '商品金额（美元）。单位：分',
  `deliver_fee_usd` int(11) NOT NULL DEFAULT '0' COMMENT '订单运费（美元）。单位：分',
  `discount_amount_usd` int(11) NOT NULL DEFAULT '0' COMMENT '折扣金额（美元）。单位：分',
  `order_amount_usd` int(11) NOT NULL DEFAULT '0' COMMENT '订单金额（美元）。单位：分',
  `order_profit_usd` int(11) DEFAULT '0' COMMENT '订单利润（美元）。单位：分',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `txn_id` varchar(30) NOT NULL DEFAULT '' COMMENT '交易号',
  `pay_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '支付时间',
  `store_code` varchar(10) DEFAULT '' COMMENT '仓库简码',
  `deliver_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '发货时间',
  `receive_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '收货时间',
  `status` tinyint(4) NOT NULL DEFAULT '2' COMMENT '订单状态。1 正在发货中；2 等待付款；3 等待发货；4 等待收货；5 等待评价；6 已完成；90 冻结；97 退货中；98 退货完成；99 订单取消；100 拆分（主单专属）；111 doba异常订单',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  `is_export_lock` tinyint(4) DEFAULT '0' COMMENT '导出订单后，锁定，就不能取消订单。0：未锁定，1：锁定',
  `freight_no` varchar(20) DEFAULT '' COMMENT '物流单号',
  `is_doba_order` tinyint(1) DEFAULT '0' COMMENT '订单中是否含有doba产品, 0[没有], 1[有]',
  `doba_supplier_id` varchar(12) DEFAULT NULL COMMENT 'doba店铺ID',
  `supplier_id` int(11) NOT NULL DEFAULT '0' COMMENT '供应商id',
  `shipper_id` int(11) NOT NULL DEFAULT '0' COMMENT '发货商id',
  `order_type` tinyint(4) NOT NULL DEFAULT '4' COMMENT '订单类型(0=>未定义，1=>选购，2=>升级，3=>代品券，，4=>普通订单, 5=>换货订单)',
  `score_year_month` char(6) NOT NULL COMMENT '业绩月份',
  `order_from` tinyint(4) NOT NULL DEFAULT '1' COMMENT '订单来源,1[PC],2[ios],3[android]',
  PRIMARY KEY (`id`),
  KEY `INDEX_CREATED_AT` (`created_at`),
  KEY `INDEX_STATUS` (`status`),
  KEY `IDX_ATTACH_ID` (`attach_id`),
  KEY `idx_shopkeeper_id` (`shopkeeper_id`),
  KEY `customer_id` (`customer_id`),
  KEY `area` (`area`),
  KEY `pay_time` (`pay_time`),
  KEY `payment_type` (`payment_type`),
  KEY `deliver_time` (`deliver_time`),
  KEY `txn_id` (`txn_id`),
  KEY `idx_order_prop` (`order_prop`),
  KEY `order_id` (`order_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4365959 DEFAULT CHARSET=utf8 COMMENT='订单表';

-- ----------------------------
-- Table structure for trade_orders_1707
-- ----------------------------
DROP TABLE IF EXISTS `trade_orders_1707`;
CREATE TABLE `trade_orders_1707` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `order_id` char(19) NOT NULL COMMENT '订单ID',
  `order_prop` tinyint(4) NOT NULL DEFAULT '0' COMMENT '订单性质,0 普通订单; 1 拆单后的子订单; 2 拆单的子订单所关联的主订单; 3 普通订单合单后的主订单',
  `attach_id` char(19) NOT NULL COMMENT '关联订单ID',
  `customer_id` int(11) NOT NULL DEFAULT '0' COMMENT '顾客id',
  `shopkeeper_id` int(11) NOT NULL DEFAULT '0' COMMENT '店主id',
  `area` char(3) NOT NULL DEFAULT '001' COMMENT '区域代码。一般为国家代码，东南亚地区为001，其他地区为000',
  `deliver_time_type` tinyint(4) DEFAULT '1' COMMENT '送货时间类型。1 周一至周日均可；2 仅周一至周五；3 仅周六日、节假日',
  `expect_deliver_date` date DEFAULT NULL COMMENT '预计发货时间',
  `need_receipt` tinyint(4) DEFAULT '0' COMMENT '是否需要收据。0 不需要；1 需要',
  `currency` char(3) DEFAULT 'USD' COMMENT '币种, USD,RMB',
  `payment_type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '支付方式。0 未支付；1 选择套装; 2 代品券换购; 105 支付宝；106 银联；107 paypal；108 ewallet；109 银盛；110 余额支付；111 銀聯國際;',
  `currency_rate` decimal(12,6) DEFAULT '1.000000' COMMENT '下单时兑美元汇率',
  `discount_type` tinyint(4) DEFAULT '0' COMMENT '折扣类型。0 无折扣；1 获取代品券，2使用代品券',
  `goods_amount` int(11) NOT NULL DEFAULT '0' COMMENT '商品总金额。单位：分',
  `deliver_fee` int(11) NOT NULL DEFAULT '0' COMMENT '订单运费。单位：分',
  `order_amount` int(11) NOT NULL DEFAULT '0' COMMENT '订单实付金额。单位：分',
  `goods_amount_usd` int(11) NOT NULL DEFAULT '0' COMMENT '商品金额（美元）。单位：分',
  `deliver_fee_usd` int(11) NOT NULL DEFAULT '0' COMMENT '订单运费（美元）。单位：分',
  `discount_amount_usd` int(11) NOT NULL DEFAULT '0' COMMENT '折扣金额（美元）。单位：分',
  `order_amount_usd` int(11) NOT NULL DEFAULT '0' COMMENT '订单金额（美元）。单位：分',
  `order_profit_usd` int(11) DEFAULT '0' COMMENT '订单利润（美元）。单位：分',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `txn_id` varchar(30) NOT NULL DEFAULT '' COMMENT '交易号',
  `pay_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '支付时间',
  `store_code` varchar(10) DEFAULT '' COMMENT '仓库简码',
  `deliver_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '发货时间',
  `receive_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '收货时间',
  `status` tinyint(4) NOT NULL DEFAULT '2' COMMENT '订单状态。1 正在发货中；2 等待付款；3 等待发货；4 等待收货；5 等待评价；6 已完成；90 冻结；97 退货中；98 退货完成；99 订单取消；100 拆分（主单专属）；111 doba异常订单',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  `is_export_lock` tinyint(4) DEFAULT '0' COMMENT '导出订单后，锁定，就不能取消订单。0：未锁定，1：锁定',
  `freight_no` varchar(20) DEFAULT '' COMMENT '物流单号',
  `is_doba_order` tinyint(1) DEFAULT '0' COMMENT '订单中是否含有doba产品, 0[没有], 1[有]',
  `doba_supplier_id` varchar(12) DEFAULT NULL COMMENT 'doba店铺ID',
  `supplier_id` int(11) NOT NULL DEFAULT '0' COMMENT '供应商id',
  `shipper_id` int(11) NOT NULL DEFAULT '0' COMMENT '发货商id',
  `order_type` tinyint(4) NOT NULL DEFAULT '4' COMMENT '订单类型(0=>未定义，1=>选购，2=>升级，3=>代品券，，4=>普通订单, 5=>换货订单)',
  `score_year_month` char(6) NOT NULL COMMENT '业绩月份',
  `order_from` tinyint(4) NOT NULL DEFAULT '1' COMMENT '订单来源,1[PC],2[ios],3[android]',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UQE_order_id` (`order_id`),
  KEY `INDEX_CREATED_AT` (`created_at`),
  KEY `INDEX_STATUS` (`status`),
  KEY `IDX_ATTACH_ID` (`attach_id`),
  KEY `idx_shopkeeper_id` (`shopkeeper_id`),
  KEY `customer_id` (`customer_id`),
  KEY `area` (`area`),
  KEY `pay_time` (`pay_time`),
  KEY `payment_type` (`payment_type`),
  KEY `deliver_time` (`deliver_time`),
  KEY `txn_id` (`txn_id`),
  KEY `idx_order_prop` (`order_prop`,`order_type`,`pay_time`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2256047 DEFAULT CHARSET=utf8 COMMENT='订单表';

-- ----------------------------
-- Table structure for trade_orders_doba_order_info
-- ----------------------------
DROP TABLE IF EXISTS `trade_orders_doba_order_info`;
CREATE TABLE `trade_orders_doba_order_info` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` char(19) NOT NULL DEFAULT '',
  `order_id_doba` varchar(32) NOT NULL DEFAULT '' COMMENT 'doba订单id',
  `order_total_doba` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '订单总金额',
  `state` varchar(32) NOT NULL DEFAULT '' COMMENT '订单状态',
  `doba_order_id` varchar(128) NOT NULL DEFAULT '' COMMENT 'tps订单_唯一时间戳组合的唯一单号',
  `doba_ship_info` varchar(255) NOT NULL DEFAULT '' COMMENT '序列化后的订单物流信息',
  `last_update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '最后更新时间戳',
  `goods_list_info` varchar(500) NOT NULL DEFAULT '' COMMENT '序列化的订单商品详细',
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  KEY `doba_order_id` (`doba_order_id`)
) ENGINE=InnoDB AUTO_INCREMENT=30248 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for trade_orders_goods
-- ----------------------------
DROP TABLE IF EXISTS `trade_orders_goods`;
CREATE TABLE `trade_orders_goods` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `order_id` char(19) NOT NULL DEFAULT '0' COMMENT '订单ID',
  `goods_sn_main` varchar(50) NOT NULL DEFAULT '0' COMMENT '产品主sku',
  `goods_sn` varchar(60) NOT NULL DEFAULT '0' COMMENT '产品sku',
  `goods_name` varchar(250) DEFAULT '0' COMMENT '产品名称',
  `supplier_id` int(11) NOT NULL DEFAULT '0' COMMENT '产品供应商',
  `store_code` varchar(50) NOT NULL DEFAULT '0' COMMENT '产品的仓库',
  `cate_id` int(11) NOT NULL DEFAULT '0' COMMENT '分类ID',
  `goods_attr` varchar(50) DEFAULT '0' COMMENT '产品属性',
  `goods_number` smallint(5) unsigned NOT NULL DEFAULT '1' COMMENT '产品数量',
  `market_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '市场价格',
  `goods_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '产品价格',
  `is_doba_goods` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否doba平台产品',
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  KEY `goods_sn` (`goods_sn`),
  KEY `goods_sn_main` (`goods_sn_main`),
  KEY `store_code` (`store_code`),
  KEY `supplier_id` (`supplier_id`)
) ENGINE=InnoDB AUTO_INCREMENT=32774733 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for trade_orders_goods_1706
-- ----------------------------
DROP TABLE IF EXISTS `trade_orders_goods_1706`;
CREATE TABLE `trade_orders_goods_1706` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `order_id` char(19) NOT NULL COMMENT '订单ID',
  `goods_sn_main` varchar(50) NOT NULL DEFAULT '0' COMMENT '产品主sku',
  `goods_sn` varchar(60) NOT NULL DEFAULT '0' COMMENT '产品sku',
  `goods_name` varchar(250) NOT NULL COMMENT '产品名称',
  `supplier_id` int(11) NOT NULL DEFAULT '0' COMMENT '产品供应商',
  `store_code` varchar(10) NOT NULL DEFAULT '0' COMMENT '产品的仓库',
  `cate_id` int(11) NOT NULL DEFAULT '0' COMMENT '分类ID',
  `goods_attr` varchar(50) DEFAULT '' COMMENT '产品属性',
  `goods_number` smallint(6) NOT NULL DEFAULT '1' COMMENT '产品数量',
  `market_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '市场价格',
  `goods_price` decimal(10,2) DEFAULT '0.00' COMMENT '产品价格',
  `is_doba_goods` tinyint(4) DEFAULT '0' COMMENT '是否doba平台产品',
  `supply_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '本币供货价',
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  KEY `goods_sn` (`goods_sn`),
  KEY `goods_sn_main` (`goods_sn_main`),
  KEY `store_code` (`store_code`),
  KEY `supplier_id` (`supplier_id`)
) ENGINE=InnoDB AUTO_INCREMENT=33236829 DEFAULT CHARSET=utf8 COMMENT='订单商品表';

-- ----------------------------
-- Table structure for trade_orders_goods_1707
-- ----------------------------
DROP TABLE IF EXISTS `trade_orders_goods_1707`;
CREATE TABLE `trade_orders_goods_1707` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `order_id` char(19) NOT NULL COMMENT '订单ID',
  `goods_sn_main` varchar(50) NOT NULL DEFAULT '0' COMMENT '产品主sku',
  `goods_sn` varchar(60) NOT NULL DEFAULT '0' COMMENT '产品sku',
  `goods_name` varchar(250) NOT NULL COMMENT '产品名称',
  `supplier_id` int(11) NOT NULL DEFAULT '0' COMMENT '产品供应商',
  `store_code` varchar(10) NOT NULL DEFAULT '0' COMMENT '产品的仓库',
  `cate_id` int(11) NOT NULL DEFAULT '0' COMMENT '分类ID',
  `goods_attr` varchar(50) DEFAULT '' COMMENT '产品属性',
  `goods_number` smallint(6) NOT NULL DEFAULT '1' COMMENT '产品数量',
  `market_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '市场价格',
  `goods_price` decimal(10,2) DEFAULT '0.00' COMMENT '产品价格',
  `is_doba_goods` tinyint(4) DEFAULT '0' COMMENT '是否doba平台产品',
  `supply_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '本币供货价',
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  KEY `goods_sn` (`goods_sn`),
  KEY `goods_sn_main` (`goods_sn_main`),
  KEY `store_code` (`store_code`),
  KEY `supplier_id` (`supplier_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3056528 DEFAULT CHARSET=utf8 COMMENT='订单商品表';

-- ----------------------------
-- Table structure for trade_orders_info
-- ----------------------------
DROP TABLE IF EXISTS `trade_orders_info`;
CREATE TABLE `trade_orders_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `order_id` char(19) NOT NULL COMMENT '订单ID',
  `consignee` char(255) NOT NULL DEFAULT '' COMMENT '收货人',
  `phone` varchar(24) DEFAULT '' COMMENT '联系电话',
  `reserve_num` varchar(24) DEFAULT '' COMMENT '备用电话',
  `address` varchar(512) NOT NULL DEFAULT '' COMMENT '收货地址',
  `country_address` varchar(50) DEFAULT '' COMMENT '收貨國家和收貨地址分離，單獨開來',
  `zip_code` varchar(16) DEFAULT '' COMMENT '邮政编码',
  `customs_clearance` varchar(32) DEFAULT '' COMMENT '海关报关号',
  `remark` varchar(128) DEFAULT '' COMMENT '订单备注',
  `freight_info` varchar(512) DEFAULT '' COMMENT '物流信息',
  `ID_no` varchar(18) DEFAULT '' COMMENT '证件号码/护照号码',
  `ID_front` varchar(50) DEFAULT '' COMMENT '身份证正面',
  `ID_reverse` varchar(50) DEFAULT '' COMMENT '身份证背面',
  PRIMARY KEY (`id`,`order_id`),
  UNIQUE KEY `UQE_order_id` (`order_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5512 DEFAULT CHARSET=utf8 COMMENT='订单详情表';

-- ----------------------------
-- Table structure for trade_orders_info_1706
-- ----------------------------
DROP TABLE IF EXISTS `trade_orders_info_1706`;
CREATE TABLE `trade_orders_info_1706` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `order_id` char(19) NOT NULL COMMENT '订单ID',
  `consignee` char(255) NOT NULL DEFAULT '' COMMENT '收货人',
  `phone` varchar(24) DEFAULT '' COMMENT '联系电话',
  `reserve_num` varchar(24) DEFAULT '' COMMENT '备用电话',
  `address` varchar(512) NOT NULL DEFAULT '' COMMENT '收货地址',
  `country_address` varchar(50) DEFAULT '' COMMENT '收貨國家和收貨地址分離，單獨開來',
  `zip_code` varchar(16) DEFAULT '' COMMENT '邮政编码',
  `customs_clearance` varchar(32) DEFAULT '' COMMENT '海关报关号',
  `remark` varchar(128) DEFAULT '' COMMENT '订单备注',
  `freight_info` varchar(512) DEFAULT '' COMMENT '物流信息',
  `ID_no` varchar(18) DEFAULT '' COMMENT '证件号码/护照号码',
  `ID_front` varchar(50) DEFAULT '' COMMENT '身份证正面',
  `ID_reverse` varchar(50) DEFAULT '' COMMENT '身份证背面',
  PRIMARY KEY (`id`,`order_id`),
  UNIQUE KEY `UQE_order_id` (`order_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4364643 DEFAULT CHARSET=utf8 COMMENT='订单详情表';

-- ----------------------------
-- Table structure for trade_orders_info_1707
-- ----------------------------
DROP TABLE IF EXISTS `trade_orders_info_1707`;
CREATE TABLE `trade_orders_info_1707` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `order_id` char(19) NOT NULL COMMENT '订单ID',
  `consignee` char(255) NOT NULL DEFAULT '' COMMENT '收货人',
  `phone` varchar(24) DEFAULT '' COMMENT '联系电话',
  `reserve_num` varchar(24) DEFAULT '' COMMENT '备用电话',
  `address` varchar(512) NOT NULL DEFAULT '' COMMENT '收货地址',
  `country_address` varchar(50) DEFAULT '' COMMENT '收貨國家和收貨地址分離，單獨開來',
  `zip_code` varchar(16) DEFAULT '' COMMENT '邮政编码',
  `customs_clearance` varchar(32) DEFAULT '' COMMENT '海关报关号',
  `remark` varchar(128) DEFAULT '' COMMENT '订单备注',
  `freight_info` varchar(512) DEFAULT '' COMMENT '物流信息',
  `ID_no` varchar(18) DEFAULT '' COMMENT '证件号码/护照号码',
  `ID_front` varchar(50) DEFAULT '' COMMENT '身份证正面',
  `ID_reverse` varchar(50) DEFAULT '' COMMENT '身份证背面',
  PRIMARY KEY (`id`,`order_id`),
  UNIQUE KEY `UQE_order_id` (`order_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2256049 DEFAULT CHARSET=utf8 COMMENT='订单详情表';

-- ----------------------------
-- Table structure for trade_orders_log
-- ----------------------------
DROP TABLE IF EXISTS `trade_orders_log`;
CREATE TABLE `trade_orders_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` char(19) NOT NULL COMMENT '订单id',
  `oper_code` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '操作代码',
  `statement` varchar(1024) NOT NULL DEFAULT '' COMMENT '操作内容',
  `operator_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '操作者id。0 表示系统操作',
  `update_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `KEY_ORDER_ID` (`order_id`),
  KEY `ind_trade_orders_log` (`update_time`)
) ENGINE=InnoDB AUTO_INCREMENT=55170418 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for trade_orders_type
-- ----------------------------
DROP TABLE IF EXISTS `trade_orders_type`;
CREATE TABLE `trade_orders_type` (
  `order_id` char(19) NOT NULL DEFAULT '0' COMMENT '订单号',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1是产品套装',
  `level` varchar(50) NOT NULL DEFAULT '0' COMMENT '3銀，2金，1 鑽',
  `amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '套餐金額',
  UNIQUE KEY `order_id_type` (`order_id`,`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='商城產品套裝订单';

-- ----------------------------
-- Table structure for trade_order_cron_import
-- ----------------------------
DROP TABLE IF EXISTS `trade_order_cron_import`;
CREATE TABLE `trade_order_cron_import` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` char(19) NOT NULL DEFAULT '' COMMENT '订单号。格式：X+datetime+四位随机数。X：普通订单为N；子订单为C；子订单的主单为P。',
  `company_code` smallint(5) NOT NULL COMMENT '公司代码',
  `trck_num` varchar(255) NOT NULL DEFAULT '' COMMENT '快递单号',
  `uid` int(11) NOT NULL COMMENT '用户ID',
  `update_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `ORDER_ID_INDEX` (`order_id`) USING BTREE,
  KEY `UID_INDEX` (`uid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=7269982 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for trade_order_doba_log
-- ----------------------------
DROP TABLE IF EXISTS `trade_order_doba_log`;
CREATE TABLE `trade_order_doba_log` (
  `order_id` varchar(25) NOT NULL DEFAULT '0',
  `goods_list` text,
  `phone` varchar(20) DEFAULT NULL COMMENT '收货人电话',
  `city` varchar(50) DEFAULT NULL COMMENT '收货城市',
  `country` varchar(10) DEFAULT NULL COMMENT '国家',
  `firstname` varchar(50) DEFAULT NULL COMMENT '姓',
  `lastname` varchar(50) DEFAULT NULL COMMENT '名',
  `postal` varchar(20) DEFAULT NULL COMMENT '邮编',
  `state` varchar(10) DEFAULT NULL COMMENT '州',
  `street` varchar(50) DEFAULT NULL COMMENT '详细地址',
  `status` int(1) DEFAULT '0' COMMENT '0=>未付款,1=>已付款',
  `create_time` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `pay_time` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT '支付时间',
  `reponse_error` text COMMENT '推送失败返回错误信息',
  PRIMARY KEY (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for trade_order_import_log
-- ----------------------------
DROP TABLE IF EXISTS `trade_order_import_log`;
CREATE TABLE `trade_order_import_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) NOT NULL COMMENT '操作者ID',
  `file_name` varchar(50) NOT NULL COMMENT '原始文件名',
  `re_file_name` varchar(80) NOT NULL COMMENT '保存在oss上的路径名称',
  `create_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`,`create_time`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=45096 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for trade_order_remark_record
-- ----------------------------
DROP TABLE IF EXISTS `trade_order_remark_record`;
CREATE TABLE `trade_order_remark_record` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` varchar(40) NOT NULL DEFAULT '' COMMENT '对应订单id',
  `type` enum('1','2') NOT NULL DEFAULT '1' COMMENT '备注类型。1 系统可见备注；2 用户可见备注',
  `remark` text NOT NULL COMMENT '备注内容',
  `admin_id` mediumint(9) NOT NULL COMMENT '后台操作人员id',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '创建时间',
  `operator` varchar(50) NOT NULL DEFAULT '' COMMENT '操作人名称',
  PRIMARY KEY (`id`),
  KEY `INDEX_ORDER_ID` (`order_id`),
  KEY `type_index` (`type`),
  KEY `index_admin_id` (`admin_id`),
  KEY `idx_create_at` (`created_at`)
) ENGINE=InnoDB AUTO_INCREMENT=520334 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for trade_order_status_rollback_logs
-- ----------------------------
DROP TABLE IF EXISTS `trade_order_status_rollback_logs`;
CREATE TABLE `trade_order_status_rollback_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` varchar(20) NOT NULL DEFAULT '0' COMMENT 'order_id',
  `status` smallint(6) NOT NULL DEFAULT '0',
  `freight_info` varchar(256) DEFAULT '' COMMENT '物流信息',
  `deliver_time` timestamp NULL DEFAULT NULL COMMENT '发货时间',
  `admin_id` int(11) NOT NULL DEFAULT '0' COMMENT '操作人',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '记录时间',
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  KEY `create_time` (`create_time`),
  KEY `admin_id` (`admin_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for trade_order_to_erp_inventory_queue
-- ----------------------------
DROP TABLE IF EXISTS `trade_order_to_erp_inventory_queue`;
CREATE TABLE `trade_order_to_erp_inventory_queue` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `goods_sn` varchar(32) NOT NULL DEFAULT '' COMMENT '商品sn',
  `oper_type` enum('dec','inc') NOT NULL DEFAULT 'dec' COMMENT '库存修改类型。dec 减少；inc 增加',
  `quantity` int(11) NOT NULL DEFAULT '0' COMMENT '需要增减的库存数量',
  `inventory` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '当前库存',
  `order_id` char(19) NOT NULL DEFAULT '' COMMENT '订单号',
  `oper_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '操作时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=33443446 DEFAULT CHARSET=utf8 COMMENT='订单推送ERP库存队列';

-- ----------------------------
-- Table structure for trade_order_to_erp_logs
-- ----------------------------
DROP TABLE IF EXISTS `trade_order_to_erp_logs`;
CREATE TABLE `trade_order_to_erp_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` char(19) NOT NULL DEFAULT '0' COMMENT '订单id',
  `api_url` varchar(255) NOT NULL DEFAULT '' COMMENT 'erp接口url',
  `api_param` text NOT NULL COMMENT 'erp接口参数',
  `error` text COMMENT '同步错误信息',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='订单推送到erp log表';

-- ----------------------------
-- Table structure for trade_order_to_erp_oper_queue
-- ----------------------------
DROP TABLE IF EXISTS `trade_order_to_erp_oper_queue`;
CREATE TABLE `trade_order_to_erp_oper_queue` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` char(19) NOT NULL DEFAULT '' COMMENT '订单id',
  `oper_data` text NOT NULL COMMENT '业务操作数据',
  `oper_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '操作时间',
  PRIMARY KEY (`id`),
  KEY `IDX_order_id` (`order_id`)
) ENGINE=InnoDB AUTO_INCREMENT=100562315 DEFAULT CHARSET=utf8 COMMENT='订单推送ERP业务队列';

-- ----------------------------
-- Table structure for trade_order_user_monthly_list
-- ----------------------------
DROP TABLE IF EXISTS `trade_order_user_monthly_list`;
CREATE TABLE `trade_order_user_monthly_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL COMMENT '用户id',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_uid` (`uid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=65536 DEFAULT CHARSET=utf8 COMMENT='tps会员下单队列';

-- ----------------------------
-- Table structure for trade_user_address
-- ----------------------------
DROP TABLE IF EXISTS `trade_user_address`;
CREATE TABLE `trade_user_address` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL COMMENT '用户id',
  `is_default` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否默认地址。1 是；0 否',
  `consignee` varchar(255) NOT NULL COMMENT '收货人',
  `phone` varchar(24) NOT NULL COMMENT '手机号码',
  `reserve_num` varchar(24) NOT NULL COMMENT '备用号码',
  `country` varchar(64) NOT NULL COMMENT '国家',
  `addr_lv2` varchar(64) DEFAULT NULL COMMENT '二级地址。中国：省份/直辖市、韩国：市/道、美国：州',
  `addr_lv3` varchar(64) DEFAULT NULL COMMENT '三级地址。中国：城市',
  `addr_lv4` varchar(64) DEFAULT NULL COMMENT '四级地址。中国：区/县',
  `addr_lv5` varchar(64) DEFAULT NULL COMMENT '五级地址。中国；街道',
  `address_detail` varchar(255) NOT NULL COMMENT '单位、楼层、门牌等详细地址信息',
  `zip_code` varchar(16) DEFAULT '' COMMENT '邮政编码',
  `customs_clearance` varchar(32) DEFAULT '' COMMENT '海关报关号。目前只有韩国需要',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `city` varchar(32) DEFAULT NULL,
  `ID_no` varchar(50) NOT NULL DEFAULT '' COMMENT '证件号码/护照号码',
  `ID_front` varchar(150) NOT NULL DEFAULT '' COMMENT '正面',
  `ID_reverse` varchar(150) NOT NULL DEFAULT '' COMMENT '反面',
  PRIMARY KEY (`id`),
  KEY `INDEX_UID` (`uid`)
) ENGINE=InnoDB AUTO_INCREMENT=3250619 DEFAULT CHARSET=utf8 COMMENT='记录订单页用户收货信息';

-- ----------------------------
-- Table structure for unbinding_account_log
-- ----------------------------
DROP TABLE IF EXISTS `unbinding_account_log`;
CREATE TABLE `unbinding_account_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `old_data` varchar(255) NOT NULL DEFAULT '0' COMMENT '修改前的数据，数据以&&分隔',
  `new_data` varchar(255) NOT NULL DEFAULT '0' COMMENT '修改前数据，数据以&&分隔',
  `admin_id` int(11) NOT NULL DEFAULT '0' COMMENT '操作者',
  `type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '解绑类型，1:支付宝,2:paypal,3:手机号',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=832 DEFAULT CHARSET=utf8 COMMENT='解绑支付宝，paypal,手机号  log';

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT '0' COMMENT '名称',
  `email` varchar(100) NOT NULL DEFAULT '0',
  `mobile` varchar(50) NOT NULL DEFAULT '0',
  `address` varchar(255) NOT NULL DEFAULT '0',
  `pwd` char(40) NOT NULL DEFAULT '0',
  `pwd_ori_md5` char(32) NOT NULL DEFAULT '0' COMMENT '密码原文的md5，放在商城那边无法改变加密方式导致无法同步用户密码',
  `pwd_take_out_cash` char(40) NOT NULL DEFAULT '0' COMMENT '提现密码',
  `parent_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '推荐人的id',
  `parent_ids` longtext COMMENT '所有历代推荐id，以逗号分隔',
  `languageid` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '会员注册语种：1=中文,2=英文,3=繁体中文',
  `user_rank` tinyint(3) unsigned NOT NULL DEFAULT '4' COMMENT '用户等级, 1钻石，2白金级，3银级，4免费会员，5铜级会员',
  `month_fee_rank` tinyint(3) unsigned NOT NULL DEFAULT '4' COMMENT '月费等级：4免费，3银级，2白金，1钻石',
  `sale_rank` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '0普通店主，1资深店主，2销售经理，3销售主任，4销售总监，5全球销售副总裁',
  `sale_rank_up_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '会员职称更新时间',
  `amount` decimal(14,2) NOT NULL DEFAULT '0.00' COMMENT '用户余额',
  `amount_store_commission` decimal(14,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '用户个人店铺提成总金额',
  `amount_profit_sharing_comm` decimal(14,2) NOT NULL DEFAULT '0.00' COMMENT '用户日分红总金额',
  `amount_weekly_Leader_comm` decimal(14,2) NOT NULL DEFAULT '0.00' COMMENT '周领导对等奖总金额',
  `amount_monthly_leader_comm` decimal(14,2) NOT NULL DEFAULT '0.00' COMMENT '月领导分红总金额',
  `profit_sharing_point` decimal(14,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '用户总分红点',
  `profit_sharing_point_from_sale` decimal(14,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '销售佣金自动转入的分红点',
  `profit_sharing_point_from_force` decimal(14,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '见点佣金自动转入的分红点',
  `profit_sharing_point_from_sharing` decimal(14,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '分红佣金自动转入分红点',
  `profit_sharing_point_manually` decimal(14,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '手动转入的分红点',
  `profit_sharing_point_to_money` decimal(14,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '分红点转现金',
  `month_fee_pool` decimal(14,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '月费池',
  `user_point` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户积分',
  `country_id` tinyint(3) unsigned DEFAULT NULL COMMENT '国家id',
  `id_card_num` varchar(50) NOT NULL DEFAULT '0' COMMENT '身份证号码',
  `id_card_scan` varchar(50) NOT NULL DEFAULT '0' COMMENT '身份证扫描件',
  `alipay_account` varchar(50) NOT NULL DEFAULT '' COMMENT '支付宝账户',
  `alipay_name` varchar(50) NOT NULL DEFAULT '' COMMENT '支付宝姓名',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '注册时间',
  `send_email_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '發送激活郵件時間',
  `send_email_token` varchar(255) NOT NULL DEFAULT '0' COMMENT '发送邮件token',
  `token` char(32) NOT NULL DEFAULT '0' COMMENT 'md5后的一个用户安全加密码',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '状态0->未激活，1->激活(正常、包括7天内未支付月费的会员)，2->拖欠月费7天（月费会员），3->冻结账户（不欠月费）,4->公司预留账户，5->冻结账户（欠月费）',
  `enable_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '激活时间',
  `month_fee_date` tinyint(2) NOT NULL DEFAULT '0' COMMENT '月费日',
  `update_token_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用于用户更新信息时生成token以及过期验证',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `upgrade_month_fee_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '升级月费时间',
  `upgrade_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '升級時的時間',
  `img` varchar(255) NOT NULL DEFAULT '0' COMMENT '用户头像',
  `store_url` varchar(255) NOT NULL DEFAULT '0' COMMENT '用户店铺url',
  `store_url_modify_counts` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '店铺url修改次数',
  `member_url_prefix` varchar(45) DEFAULT NULL,
  `member_url_modify_counts` tinyint(3) DEFAULT '0',
  `personal_commission` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '2x5佣金',
  `company_commission` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '138佣金',
  `team_commission` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '团队销售提成奖',
  `infinite_commission` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '无限代提成奖',
  `ewallet_name` varchar(100) NOT NULL DEFAULT '0' COMMENT '電子錢包的賬號',
  `child_count` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '父類所有激活的會員',
  `from` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '注册自哪里0:tps；1:沃好',
  `sync_walhao` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否同步到walhao，0未同步，1同步',
  `store_qualified` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否合格店铺：0不合格，1合格',
  `is_choose` int(1) NOT NULL DEFAULT '0',
  `store_name` varchar(250) NOT NULL DEFAULT '0' COMMENT '店铺名称',
  `first_monthly_fee_level` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '会员第一个月的月费等级。（系统记录其第一个月中首次的等级）',
  `alert_count` int(11) DEFAULT '0' COMMENT '超重要通知，强制弹出提醒次数',
  `is_auto` int(2) NOT NULL DEFAULT '0' COMMENT '是否自动转月费池(0=>否,1=>是)',
  `is_auto_notice` int(2) NOT NULL DEFAULT '0' COMMENT '是否弹出自动转月费的通知(0=>弹出，1=>不弹)',
  `is_verified_email` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否已验证Email，0：未验证，1：已验证，验证后才能用Email登陆',
  `is_verified_mobile` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否已验证mobile，0：未验证，1：已验证，验证后才能用phone登陆',
  PRIMARY KEY (`id`),
  KEY `email` (`email`),
  KEY `user_rank` (`user_rank`),
  KEY `sale_rank` (`sale_rank`),
  KEY `status` (`status`),
  KEY `parent_id` (`parent_id`),
  KEY `month_fee_rank` (`month_fee_rank`),
  KEY `store_qualified` (`store_qualified`),
  KEY `is_choose` (`is_choose`),
  KEY `country_id` (`country_id`),
  KEY `member_url_prefix` (`member_url_prefix`),
  KEY `create_time` (`create_time`),
  KEY `enable_time` (`enable_time`),
  KEY `is_verified_email` (`is_verified_email`),
  KEY `is_verified_mobile` (`is_verified_mobile`),
  KEY `name` (`name`),
  KEY `alipay_account` (`alipay_account`),
  KEY `alipay_name` (`alipay_name`),
  KEY `mobile` (`mobile`),
  KEY `profit_sharing_point` (`profit_sharing_point`),
  KEY `store_url` (`store_url`)
) ENGINE=InnoDB AUTO_INCREMENT=1384456502 DEFAULT CHARSET=utf8 COMMENT='用户表';

-- ----------------------------
-- Table structure for users_april_plan
-- ----------------------------
DROP TABLE IF EXISTS `users_april_plan`;
CREATE TABLE `users_april_plan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT '0' COMMENT '会员ID',
  `type` int(11) DEFAULT '0' COMMENT '会员参加计划的类型,1:参加此优惠计划, 2:不参加该计划，并同意设置自动从现金池转差额到月费池以支付月费, 3:不参加该计划，不设置自动从现金池转差额到月费池。',
  `create_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '加入的时间',
  PRIMARY KEY (`id`),
  KEY `type` (`type`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB AUTO_INCREMENT=17506 DEFAULT CHARSET=utf8 COMMENT='用户在4月份做50美金销售额即可从休眠恢复正常';

-- ----------------------------
-- Table structure for users_april_plan_order
-- ----------------------------
DROP TABLE IF EXISTS `users_april_plan_order`;
CREATE TABLE `users_april_plan_order` (
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '会员iD',
  `order_id` varchar(40) NOT NULL DEFAULT '0' COMMENT '订单ID号',
  PRIMARY KEY (`order_id`,`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='4月份活动订单，是不能取消的。';

-- ----------------------------
-- Table structure for users_bank_card
-- ----------------------------
DROP TABLE IF EXISTS `users_bank_card`;
CREATE TABLE `users_bank_card` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `bank_name` varchar(50) NOT NULL DEFAULT '' COMMENT '开户行名称',
  `bank_branch_name` varchar(50) NOT NULL DEFAULT '' COMMENT '开户支行名称',
  `bank_number` varchar(50) NOT NULL DEFAULT '' COMMENT '银行账号',
  `bank_user_name` varchar(50) NOT NULL DEFAULT '' COMMENT '开户人名称',
  `create_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户银行卡表';

-- ----------------------------
-- Table structure for users_cash_bonus
-- ----------------------------
DROP TABLE IF EXISTS `users_cash_bonus`;
CREATE TABLE `users_cash_bonus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '会员ID',
  `amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '奖励金额',
  `type` int(11) NOT NULL DEFAULT '0' COMMENT '1：1月份奖励红包，2：预留',
  `commission_id` int(11) NOT NULL DEFAULT '0' COMMENT '佣金报表的id',
  `status` int(11) NOT NULL DEFAULT '0' COMMENT '0：未发放，1发放了',
  `referrals_num` int(11) NOT NULL DEFAULT '0' COMMENT '1月份直推人数',
  `sale_amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '1月份销售金额',
  `orders_num` int(11) NOT NULL DEFAULT '0' COMMENT '1月份销售订单数',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `type` (`type`)
) ENGINE=InnoDB AUTO_INCREMENT=664 DEFAULT CHARSET=utf8 COMMENT='用户直推人现金红包';

-- ----------------------------
-- Table structure for users_child_group_info
-- ----------------------------
DROP TABLE IF EXISTS `users_child_group_info`;
CREATE TABLE `users_child_group_info` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `group_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户一级组的id（组顶部那个店铺主的id）',
  `mso_num` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '组里面资深店主数量',
  `sm_num` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '组里面销售经理数量',
  `sd_num` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '组里面销售主任数量',
  `vp_num` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '组里面销售副总监数量',
  PRIMARY KEY (`uid`,`group_id`),
  KEY `group_id` (`group_id`) USING HASH
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户一级组的信息';

-- ----------------------------
-- Table structure for users_coupon_monthfee
-- ----------------------------
DROP TABLE IF EXISTS `users_coupon_monthfee`;
CREATE TABLE `users_coupon_monthfee` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `coupon_level` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '3=>银级（使用后，直接加到月费池）\n2=>金级\n1=》钻石',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0未使用；1已使用',
  `use_time` timestamp NULL DEFAULT NULL,
  `monthly_fee_charge_time` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for users_credit
-- ----------------------------
DROP TABLE IF EXISTS `users_credit`;
CREATE TABLE `users_credit` (
  `uid` bigint(18) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户id',
  `credit` bigint(18) NOT NULL DEFAULT '0' COMMENT '用户积分',
  `created_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '更新时间',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB AUTO_INCREMENT=1384456498 DEFAULT CHARSET=utf8 COMMENT='用户积分表';

-- ----------------------------
-- Table structure for users_credit_log_201707
-- ----------------------------
DROP TABLE IF EXISTS `users_credit_log_201707`;
CREATE TABLE `users_credit_log_201707` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `before_amount` int(11) NOT NULL DEFAULT '0' COMMENT '变动前的积分',
  `after_amount` int(11) NOT NULL DEFAULT '0' COMMENT '变动后的积分',
  `created_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '说明',
  `child_uid` varchar(25) NOT NULL DEFAULT '' COMMENT '影响该次变动的用户',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4740119 DEFAULT CHARSET=utf8 COMMENT='积分变动日志表';

-- ----------------------------
-- Table structure for users_credit_queue_sale_rank
-- ----------------------------
DROP TABLE IF EXISTS `users_credit_queue_sale_rank`;
CREATE TABLE `users_credit_queue_sale_rank` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '等级变动的用户',
  `before_sale_rank` varchar(255) NOT NULL,
  `after_sale_rank` varchar(255) NOT NULL,
  `created_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `type` tinyint(4) unsigned NOT NULL DEFAULT '1' COMMENT '积分变化类型，负数的时候，1.升级增加积分，2 降级或者退会减少积分',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uni_uid_before_after` (`uid`,`before_sale_rank`,`after_sale_rank`)
) ENGINE=InnoDB AUTO_INCREMENT=23643 DEFAULT CHARSET=utf8 COMMENT='职称变动队列表';

-- ----------------------------
-- Table structure for users_credit_queue_user_rank
-- ----------------------------
DROP TABLE IF EXISTS `users_credit_queue_user_rank`;
CREATE TABLE `users_credit_queue_user_rank` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '等级变动的用户',
  `before_user_rank` varchar(255) NOT NULL,
  `after_user_rank` varchar(255) NOT NULL,
  `created_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `type` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1升级 2 降级或者退会',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uni_uid_before_after` (`uid`,`before_user_rank`,`after_user_rank`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=86706 DEFAULT CHARSET=utf8 COMMENT='等级变化队列';

-- ----------------------------
-- Table structure for users_frozen_remark
-- ----------------------------
DROP TABLE IF EXISTS `users_frozen_remark`;
CREATE TABLE `users_frozen_remark` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(32) NOT NULL COMMENT '用户编号',
  `options` varchar(64) NOT NULL COMMENT '操作者',
  `content` varchar(128) NOT NULL COMMENT '备注内容',
  `dates` datetime DEFAULT NULL COMMENT '时间',
  `optiontype` int(4) DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB AUTO_INCREMENT=3703 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for users_level_change_log
-- ----------------------------
DROP TABLE IF EXISTS `users_level_change_log`;
CREATE TABLE `users_level_change_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `old_level` tinyint(1) DEFAULT NULL,
  `new_level` tinyint(1) DEFAULT NULL,
  `level_type` tinyint(1) DEFAULT NULL COMMENT '1月费等级，2店铺等级',
  `create_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `level_type` (`level_type`),
  KEY `create_time` (`create_time`),
  KEY `new_level` (`new_level`),
  KEY `create_time_2` (`create_time`)
) ENGINE=InnoDB AUTO_INCREMENT=2352268 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for users_level_statistics_en
-- ----------------------------
DROP TABLE IF EXISTS `users_level_statistics_en`;
CREATE TABLE `users_level_statistics_en` (
  `date` int(8) NOT NULL COMMENT '时间',
  `free` int(8) DEFAULT '0' COMMENT '免费会员',
  `bronze` int(8) DEFAULT '0' COMMENT '统计会员',
  `silver` int(8) DEFAULT '0' COMMENT '银级会员',
  `golden` int(8) DEFAULT '0' COMMENT '金级会员',
  `diamond` int(8) DEFAULT '0' COMMENT '钻石级会员',
  PRIMARY KEY (`date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for users_level_statistics_hk
-- ----------------------------
DROP TABLE IF EXISTS `users_level_statistics_hk`;
CREATE TABLE `users_level_statistics_hk` (
  `date` int(8) NOT NULL COMMENT '时间',
  `free` int(8) DEFAULT '0' COMMENT '免费会员',
  `bronze` int(8) DEFAULT '0' COMMENT '统计会员',
  `silver` int(8) DEFAULT '0' COMMENT '银级会员',
  `golden` int(8) DEFAULT '0' COMMENT '金级会员',
  `diamond` int(8) DEFAULT '0' COMMENT '钻石级会员',
  PRIMARY KEY (`date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for users_level_statistics_kr
-- ----------------------------
DROP TABLE IF EXISTS `users_level_statistics_kr`;
CREATE TABLE `users_level_statistics_kr` (
  `date` int(8) NOT NULL COMMENT '时间',
  `free` int(8) DEFAULT '0' COMMENT '免费会员',
  `bronze` int(8) DEFAULT '0' COMMENT '统计会员',
  `silver` int(8) DEFAULT '0' COMMENT '银级会员',
  `golden` int(8) DEFAULT '0' COMMENT '金级会员',
  `diamond` int(8) DEFAULT '0' COMMENT '钻石级会员',
  PRIMARY KEY (`date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for users_level_statistics_other
-- ----------------------------
DROP TABLE IF EXISTS `users_level_statistics_other`;
CREATE TABLE `users_level_statistics_other` (
  `date` int(8) NOT NULL COMMENT '时间',
  `free` int(8) DEFAULT '0' COMMENT '免费会员',
  `bronze` int(8) DEFAULT '0' COMMENT '统计会员',
  `silver` int(8) DEFAULT '0' COMMENT '银级会员',
  `golden` int(8) DEFAULT '0' COMMENT '金级会员',
  `diamond` int(8) DEFAULT '0' COMMENT '钻石级会员',
  PRIMARY KEY (`date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for users_level_statistics_total
-- ----------------------------
DROP TABLE IF EXISTS `users_level_statistics_total`;
CREATE TABLE `users_level_statistics_total` (
  `date` int(8) NOT NULL COMMENT '时间',
  `free` int(11) DEFAULT '0' COMMENT '免费会员',
  `bronze` int(11) DEFAULT '0' COMMENT '铜级会员',
  `silver` int(11) DEFAULT '0' COMMENT '银级会员',
  `golden` int(11) DEFAULT '0' COMMENT '金级会员',
  `diamond` int(11) DEFAULT '0' COMMENT '钻石会员',
  PRIMARY KEY (`date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for users_level_statistics_zh
-- ----------------------------
DROP TABLE IF EXISTS `users_level_statistics_zh`;
CREATE TABLE `users_level_statistics_zh` (
  `date` int(8) NOT NULL COMMENT '时间',
  `free` int(8) DEFAULT '0' COMMENT '免费会员',
  `bronze` int(8) DEFAULT '0' COMMENT '统计会员',
  `silver` int(8) DEFAULT '0' COMMENT '银级会员',
  `golden` int(8) DEFAULT '0' COMMENT '金级会员',
  `diamond` int(8) DEFAULT '0' COMMENT '钻石级会员',
  PRIMARY KEY (`date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for users_month_fee_fail_info
-- ----------------------------
DROP TABLE IF EXISTS `users_month_fee_fail_info`;
CREATE TABLE `users_month_fee_fail_info` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '月份扣除失败的时间',
  `last_mail_date` date NOT NULL DEFAULT '0000-00-00' COMMENT '上次发提醒邮件的日期',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='月份扣除失败的用户信息';

-- ----------------------------
-- Table structure for users_month_fee_log
-- ----------------------------
DROP TABLE IF EXISTS `users_month_fee_log`;
CREATE TABLE `users_month_fee_log` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `year_and_month` mediumint(6) unsigned NOT NULL DEFAULT '0' COMMENT '年月，比如：201502',
  `amount` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '月费',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`uid`,`year_and_month`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='月费扣取记录';

-- ----------------------------
-- Table structure for users_prepaid_card_info
-- ----------------------------
DROP TABLE IF EXISTS `users_prepaid_card_info`;
CREATE TABLE `users_prepaid_card_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '申请人',
  `card_no` varchar(50) DEFAULT NULL COMMENT '预付卡，卡号',
  `name` varchar(50) NOT NULL DEFAULT '0' COMMENT '名字',
  `ID_type` tinyint(1) NOT NULL COMMENT '证件类型：0:身份证，1:护照',
  `ID_no` varchar(50) NOT NULL DEFAULT '0' COMMENT '证件号码/护照号码',
  `ID_front` varchar(150) NOT NULL DEFAULT '0' COMMENT '正面',
  `ID_reverse` varchar(150) NOT NULL DEFAULT '0' COMMENT '反面',
  `chinese_name` varchar(50) NOT NULL DEFAULT '0' COMMENT '中国名字',
  `address_prove` varchar(250) NOT NULL DEFAULT '0' COMMENT '地址证明：水电发票',
  `mobile` varchar(50) NOT NULL DEFAULT '0' COMMENT '手机号',
  `email` varchar(50) NOT NULL DEFAULT '0' COMMENT '邮箱',
  `country` varchar(50) NOT NULL DEFAULT '0' COMMENT '国家',
  `ship_to_address` varchar(250) NOT NULL DEFAULT '0' COMMENT '地址',
  `nationality` varchar(50) NOT NULL DEFAULT '0' COMMENT '国籍',
  `issuing_country` varchar(50) NOT NULL DEFAULT '0' COMMENT '证件发放国家',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '申请时间',
  `status` int(11) NOT NULL DEFAULT '0' COMMENT '卡的状态：0：未支付， 1:待审核，2：驳回，3：审核通过开卡中，4，卡已寄出，5，已审核',
  `reject_remark` varchar(250) NOT NULL DEFAULT '0' COMMENT '驳回原因',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid` (`uid`),
  KEY `status` (`status`),
  KEY `ID_no` (`ID_no`),
  KEY `card_no` (`card_no`)
) ENGINE=InnoDB AUTO_INCREMENT=252 DEFAULT CHARSET=utf8 COMMENT='用户申请银联预付卡信息';

-- ----------------------------
-- Table structure for users_prepaid_card_no
-- ----------------------------
DROP TABLE IF EXISTS `users_prepaid_card_no`;
CREATE TABLE `users_prepaid_card_no` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `card_no` varchar(50) NOT NULL DEFAULT '0' COMMENT '卡号',
  `uid` int(11) DEFAULT NULL COMMENT '对应的会员ID',
  `status` varchar(50) NOT NULL DEFAULT '0' COMMENT '0：未使用 1：鎖定卡號了，2，已分配',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3796 DEFAULT CHARSET=utf8 COMMENT='银联预付卡号';

-- ----------------------------
-- Table structure for users_profit_sharing_point_last_month
-- ----------------------------
DROP TABLE IF EXISTS `users_profit_sharing_point_last_month`;
CREATE TABLE `users_profit_sharing_point_last_month` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `profit_sharing_point` bigint(10) unsigned NOT NULL DEFAULT '0' COMMENT '分红点(单位分)',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户每月初分红点统计表';

-- ----------------------------
-- Table structure for users_referrals_count_info
-- ----------------------------
DROP TABLE IF EXISTS `users_referrals_count_info`;
CREATE TABLE `users_referrals_count_info` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '被统计的会员id',
  `member_free_num` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '免费会员',
  `member_bronze_num` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '铜级会员',
  `member_silver_num` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '银级会员',
  `member_platinum_num` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '白金会员',
  `member_diamond_num` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '钻石会员',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='统计会员所有的直推人';

-- ----------------------------
-- Table structure for users_register_captcha
-- ----------------------------
DROP TABLE IF EXISTS `users_register_captcha`;
CREATE TABLE `users_register_captcha` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email_or_phone` varchar(50) NOT NULL DEFAULT '0' COMMENT '邮箱或者中国地区的手机号',
  `code` int(11) NOT NULL DEFAULT '0' COMMENT '验证码',
  `language_id` tinyint(1) NOT NULL DEFAULT '0' COMMENT '用户是哪种语言注册的，发送邮箱的内容按照此字段',
  `expire_time` int(11) NOT NULL DEFAULT '0' COMMENT '失效时间',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否發送，0：等待發送 1：已發送',
  `action_id` tinyint(4) NOT NULL DEFAULT '0' COMMENT '活动ID，验证码参与的活动ID：1：注册，2：修改密码，3：修改邮箱，4：绑定信息',
  PRIMARY KEY (`id`),
  KEY `email_or_phone` (`email_or_phone`),
  KEY `code` (`code`),
  KEY `expire_time` (`expire_time`),
  KEY `action_id` (`action_id`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户注册验证码表';

-- ----------------------------
-- Table structure for users_sale_rank_info
-- ----------------------------
DROP TABLE IF EXISTS `users_sale_rank_info`;
CREATE TABLE `users_sale_rank_info` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `above_silver_num` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '银级以上直接的合格分店数',
  `qualified_orders_num` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '合格的订单数',
  `self_orders_num` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '自己下的订单数',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户职称数据';

-- ----------------------------
-- Table structure for users_sharing_point_reward
-- ----------------------------
DROP TABLE IF EXISTS `users_sharing_point_reward`;
CREATE TABLE `users_sharing_point_reward` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `point` decimal(14,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '分红点',
  `end_time` date NOT NULL DEFAULT '0000-00-00' COMMENT '截止日期',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `end_time` (`end_time`)
) ENGINE=InnoDB AUTO_INCREMENT=2376364 DEFAULT CHARSET=utf8 COMMENT='用户奖励分红点';

-- ----------------------------
-- Table structure for users_status_log
-- ----------------------------
DROP TABLE IF EXISTS `users_status_log`;
CREATE TABLE `users_status_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL,
  `front_status` tinyint(1) NOT NULL COMMENT '变更前的状态',
  `back_status` tinyint(1) NOT NULL COMMENT '变更后的状态',
  `type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '变更原因，1 是扣取月费，2是订单抵扣月费',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '记录时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB AUTO_INCREMENT=447019 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for users_store_sale_info
-- ----------------------------
DROP TABLE IF EXISTS `users_store_sale_info`;
CREATE TABLE `users_store_sale_info` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `orders_num` int(11) NOT NULL DEFAULT '0' COMMENT '店铺订单总数',
  `sale_amount` bigint(20) NOT NULL DEFAULT '0' COMMENT '店铺销售总额（不含运费，单位：美分）',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户职称数据';

-- ----------------------------
-- Table structure for users_store_sale_info_monthly
-- ----------------------------
DROP TABLE IF EXISTS `users_store_sale_info_monthly`;
CREATE TABLE `users_store_sale_info_monthly` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `year_month` mediumint(6) unsigned NOT NULL DEFAULT '0' COMMENT '年月',
  `orders_num` int(11) NOT NULL DEFAULT '0' COMMENT '店铺订单总数',
  `sale_amount` bigint(20) NOT NULL DEFAULT '0' COMMENT '店铺销售总额（不含运费，单位：美分）',
  PRIMARY KEY (`uid`,`year_month`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户职称数据';

-- ----------------------------
-- Table structure for user_138_bonus_qualified_list
-- ----------------------------
DROP TABLE IF EXISTS `user_138_bonus_qualified_list`;
CREATE TABLE `user_138_bonus_qualified_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `user_rank` int(2) NOT NULL DEFAULT '0' COMMENT '店铺等级',
  `sale_amount` int(11) NOT NULL DEFAULT '0' COMMENT '销售额',
  `x` int(10) NOT NULL DEFAULT '0' COMMENT 'X轴',
  `y` int(10) NOT NULL DEFAULT '0' COMMENT 'Y轴',
  `create_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=845623 DEFAULT CHARSET=utf8 COMMENT='获得138合格人数';

-- ----------------------------
-- Table structure for user_account_logs
-- ----------------------------
DROP TABLE IF EXISTS `user_account_logs`;
CREATE TABLE `user_account_logs` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '会员ID',
  `user_amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '提成金额或者扣费金额',
  `user_point` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户增加或减去的积分',
  `change_type` varchar(50) NOT NULL DEFAULT '0' COMMENT '类型：R1,个人产品销售奖,R2,团队产品销售利润提成奖,R3:团队组织见点奖 R4:每周销售利润分红',
  `change_desc` varchar(255) NOT NULL DEFAULT '0' COMMENT '账号变动详情',
  `change_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '变动时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户金额，积分账号变动表';

-- ----------------------------
-- Table structure for user_bind_mobile_log
-- ----------------------------
DROP TABLE IF EXISTS `user_bind_mobile_log`;
CREATE TABLE `user_bind_mobile_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(50) CHARACTER SET armscii8 NOT NULL COMMENT '日志类型 默认绑定手机号',
  `old_mobile` varchar(25) NOT NULL DEFAULT '' COMMENT '原来手机号',
  `new_mobile` varchar(25) NOT NULL DEFAULT '' COMMENT '新手机号',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '解绑备注',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=214035 DEFAULT CHARSET=utf8 COMMENT='用户绑定手机号记录';

-- ----------------------------
-- Table structure for user_cart
-- ----------------------------
DROP TABLE IF EXISTS `user_cart`;
CREATE TABLE `user_cart` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `customer_id` int(10) NOT NULL COMMENT '顾客ID',
  `goods_sn` varchar(32) NOT NULL DEFAULT '' COMMENT '商品子sku',
  `goods_sn_main` varchar(32) NOT NULL DEFAULT '' COMMENT '商品主SKU',
  `goods_name` varchar(255) NOT NULL DEFAULT '' COMMENT '商品名称',
  `goods_img` varchar(255) NOT NULL DEFAULT '' COMMENT '商品图片地址',
  `market_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '市场价',
  `shop_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '售价',
  `country_flag` varchar(5) NOT NULL DEFAULT '' COMMENT '产地旗帜',
  `goods_number` mediumint(8) NOT NULL DEFAULT '0' COMMENT '商品数量',
  `country_id` mediumint(8) NOT NULL COMMENT '区域ID',
  `color` varchar(32) DEFAULT NULL COMMENT '颜色',
  `size` varchar(32) DEFAULT NULL COMMENT '尺寸',
  `customer` varchar(32) DEFAULT NULL COMMENT '自定义属性',
  PRIMARY KEY (`id`),
  KEY `goods_sn` (`goods_sn`),
  KEY `goods_sn_main` (`goods_sn_main`),
  KEY `country_id` (`country_id`),
  KEY `customer_id` (`customer_id`)
) ENGINE=InnoDB AUTO_INCREMENT=25631612 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for user_comm_stat
-- ----------------------------
DROP TABLE IF EXISTS `user_comm_stat`;
CREATE TABLE `user_comm_stat` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `daily_bonus_elite` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户获得的精英日分红总额',
  `138_bonus` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '138分红总额',
  `week_bonus` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '周领导对等奖总额',
  `week_share_bonus` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '每周团队分红',
  `month_group_share` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '月团队组织分红奖',
  `new_member_bonus` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '新用户分红',
  `daily_bonus` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '日分红',
  `month_leader_share_bonus` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '每月领导团队组织分红奖',
  `month_eminent_store` int(10) unsigned NOT NULL DEFAULT '0',
  `supplier_recommendation` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '供应商推荐奖',
  `leader_lv5` bigint(20) DEFAULT '0' COMMENT '全球副总裁',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户奖金统计表。';

-- ----------------------------
-- Table structure for user_comm_stat_month
-- ----------------------------
DROP TABLE IF EXISTS `user_comm_stat_month`;
CREATE TABLE `user_comm_stat_month` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `create_time` varchar(255) NOT NULL DEFAULT '0' COMMENT '月份',
  `daily_bonus_elite` bigint(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户获得的精英日分红总额',
  `138_bonus` bigint(10) unsigned NOT NULL DEFAULT '0' COMMENT '138分红总额',
  `week_bonus` bigint(10) unsigned NOT NULL DEFAULT '0' COMMENT '周领导对等奖总额',
  `week_share_bonus` bigint(10) unsigned NOT NULL DEFAULT '0' COMMENT '每周团队分红',
  `month_group_share` bigint(10) unsigned NOT NULL DEFAULT '0' COMMENT '月团队组织分红奖',
  `new_member_bonus` bigint(10) unsigned NOT NULL DEFAULT '0' COMMENT '新用户分红',
  `daily_bonus` bigint(10) unsigned NOT NULL DEFAULT '0' COMMENT '日分红',
  `month_group_share_bonus` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '每月团队组织分红奖',
  `month_leader_share_bonus` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '每月领导团队组织分红奖',
  `month_eminent_store` bigint(10) unsigned NOT NULL DEFAULT '0',
  `supplier_recommendation` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '供应商推荐奖',
  `leader_lv5` bigint(20) DEFAULT '0' COMMENT '全球副总裁',
  PRIMARY KEY (`uid`),
  UNIQUE KEY `unix_uid_create_time` (`uid`,`create_time`) COMMENT 'uid 月 唯一索引'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户奖金统计月表。';

-- ----------------------------
-- Table structure for user_coordinates
-- ----------------------------
DROP TABLE IF EXISTS `user_coordinates`;
CREATE TABLE `user_coordinates` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自动编号',
  `user_id` int(10) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `name` varchar(255) DEFAULT NULL COMMENT '姓名',
  `x` int(11) NOT NULL DEFAULT '0' COMMENT 'x坐标',
  `y` int(11) NOT NULL DEFAULT '0' COMMENT 'y坐标',
  `month_fee_rank` int(11) DEFAULT '0' COMMENT '月费等级',
  `create_time` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`),
  KEY `x` (`x`),
  KEY `y` (`y`)
) ENGINE=InnoDB AUTO_INCREMENT=2929590 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for user_coordinates_temp
-- ----------------------------
DROP TABLE IF EXISTS `user_coordinates_temp`;
CREATE TABLE `user_coordinates_temp` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自动编号',
  `user_id` int(10) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `name` varchar(255) DEFAULT NULL COMMENT '姓名',
  `x` int(11) NOT NULL DEFAULT '0' COMMENT 'x坐标',
  `y` int(11) NOT NULL DEFAULT '0' COMMENT 'y坐标',
  `month_fee_rank` int(11) DEFAULT '0' COMMENT '月费等级',
  `create_time` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6417 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for user_daily_bonus_qualified_list
-- ----------------------------
DROP TABLE IF EXISTS `user_daily_bonus_qualified_list`;
CREATE TABLE `user_daily_bonus_qualified_list` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `qualified_day` int(8) unsigned NOT NULL DEFAULT '0' COMMENT '合格日期，如：20160803，如果是月初统计上月合格的则为默认值0',
  `amount` bigint(20) NOT NULL DEFAULT '0' COMMENT '用户上月的金额',
  `user_rank` tinyint(2) NOT NULL DEFAULT '0' COMMENT '用户等级',
  PRIMARY KEY (`uid`),
  KEY `qualified_day` (`qualified_day`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='日分红的合格人员列表';

-- ----------------------------
-- Table structure for user_email_exception_list
-- ----------------------------
DROP TABLE IF EXISTS `user_email_exception_list`;
CREATE TABLE `user_email_exception_list` (
  `uid` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for user_frost_list
-- ----------------------------
DROP TABLE IF EXISTS `user_frost_list`;
CREATE TABLE `user_frost_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '冻结的账号',
  `frost_days` int(6) NOT NULL DEFAULT '1' COMMENT '冻结天数',
  `create_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '冻结时间',
  `end_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '结束时间',
  PRIMARY KEY (`id`),
  KEY `end_time` (`end_time`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COMMENT='用户冻结列表';

-- ----------------------------
-- Table structure for user_id_card_info
-- ----------------------------
DROP TABLE IF EXISTS `user_id_card_info`;
CREATE TABLE `user_id_card_info` (
  `uid` int(11) NOT NULL DEFAULT '0',
  `name` varchar(100) NOT NULL DEFAULT '0' COMMENT '用户姓名',
  `id_card_num` varchar(50) NOT NULL DEFAULT '0' COMMENT '身份证号码',
  `id_card_scan` varchar(100) NOT NULL DEFAULT '0' COMMENT '身份证扫描件正面',
  `id_card_scan_back` varchar(100) NOT NULL DEFAULT '0' COMMENT '身份證背面',
  `check_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0:未提交審核或審核失敗 1：審核中 2：已審核',
  `check_admin` varchar(50) NOT NULL DEFAULT '0' COMMENT '哪個管理員審核的',
  `check_admin_id` int(11) NOT NULL DEFAULT '0',
  `check_time` int(11) NOT NULL DEFAULT '0' COMMENT '審核時間',
  `check_info` varchar(255) NOT NULL DEFAULT '0' COMMENT '审核未通过原因',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `is` varchar(50) DEFAULT '1' COMMENT '审核脚本读取后，标记，处理之后，放开标记',
  `check_times` tinyint(4) DEFAULT '0' COMMENT '身份证审核次数',
  PRIMARY KEY (`uid`),
  KEY `create_time` (`create_time`),
  KEY `check_status` (`check_status`),
  KEY `check_admin` (`check_admin`),
  KEY `check_time` (`check_time`),
  KEY `is` (`is`),
  KEY `check_times` (`check_times`),
  KEY `id_card_num` (`id_card_num`) USING HASH
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用戶身份證審核表';

-- ----------------------------
-- Table structure for user_list_monthly_modify_logs
-- ----------------------------
DROP TABLE IF EXISTS `user_list_monthly_modify_logs`;
CREATE TABLE `user_list_monthly_modify_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT NULL,
  `amount` int(11) DEFAULT '0' COMMENT '修复前业绩(单位：美分)',
  `amount_a` int(11) DEFAULT '0' COMMENT '修复后业绩(单位：美分)',
  `create_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_uid` (`uid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=806488 DEFAULT CHARSET=utf8 COMMENT='用户业绩修复日志记录表';

-- ----------------------------
-- Table structure for user_march_monthly_logs
-- ----------------------------
DROP TABLE IF EXISTS `user_march_monthly_logs`;
CREATE TABLE `user_march_monthly_logs` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `uid` int(11) unsigned NOT NULL COMMENT '用户id',
  `front_monthly` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '修复前的月绩，单位美分',
  `back_monthly` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '修复后的月绩,单位美分',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4701 DEFAULT CHARSET=utf8 COMMENT='销售月绩修复记录表';

-- ----------------------------
-- Table structure for user_month_fee_log
-- ----------------------------
DROP TABLE IF EXISTS `user_month_fee_log`;
CREATE TABLE `user_month_fee_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL DEFAULT '0',
  `order_id` int(11) NOT NULL DEFAULT '0',
  `money` decimal(10,2) NOT NULL DEFAULT '0.00',
  `payment` varchar(255) NOT NULL DEFAULT '0',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13010 DEFAULT CHARSET=utf8 COMMENT='添加月費log';

-- ----------------------------
-- Table structure for user_month_fee_order
-- ----------------------------
DROP TABLE IF EXISTS `user_month_fee_order`;
CREATE TABLE `user_month_fee_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_sn` varchar(50) NOT NULL DEFAULT '0' COMMENT '訂單號',
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用戶ID',
  `money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '交付月費的總金額',
  `usd_money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '會員充值是 加入數據庫的值',
  `txn_id` varchar(255) NOT NULL DEFAULT '0' COMMENT 'paypal的交易号 验证是否重复',
  `status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '訂單狀態: 0:未付款 1：付款中 2：已付款',
  `payment` varchar(50) NOT NULL DEFAULT '0' COMMENT '通過什麼方式付款的',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '訂單生成時間',
  `pay_time` timestamp NULL DEFAULT NULL COMMENT '付款时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=33392 DEFAULT CHARSET=utf8 COMMENT='用戶充值月費池';

-- ----------------------------
-- Table structure for user_month_group_share_list
-- ----------------------------
DROP TABLE IF EXISTS `user_month_group_share_list`;
CREATE TABLE `user_month_group_share_list` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `sale_amount_weight` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '销售额权重（销售额美分计数）',
  `sale_rank_weight` int(10) unsigned NOT NULL DEFAULT '1' COMMENT '职称权重（职称等级+1）',
  `store_rank_weight` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '店铺等级权重（铜级1，银级店铺2，白金级店铺3，钻石级店铺4）',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='每月团队组织分红发奖信息列表';

-- ----------------------------
-- Table structure for user_month_leader_bonus_list
-- ----------------------------
DROP TABLE IF EXISTS `user_month_leader_bonus_list`;
CREATE TABLE `user_month_leader_bonus_list` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `sharing_point` decimal(14,2) unsigned NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for user_month_leader_bonus_lv5_list
-- ----------------------------
DROP TABLE IF EXISTS `user_month_leader_bonus_lv5_list`;
CREATE TABLE `user_month_leader_bonus_lv5_list` (
  `uid` int(11) NOT NULL,
  `sharing_point` decimal(14,2) DEFAULT '0.00',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for user_month_top_leader_bonus_list
-- ----------------------------
DROP TABLE IF EXISTS `user_month_top_leader_bonus_list`;
CREATE TABLE `user_month_top_leader_bonus_list` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `sharing_point` decimal(14,2) unsigned NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for user_pwd_error_num
-- ----------------------------
DROP TABLE IF EXISTS `user_pwd_error_num`;
CREATE TABLE `user_pwd_error_num` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `type` int(1) DEFAULT NULL COMMENT '资金密码2，登录密码1',
  `num` int(2) DEFAULT NULL COMMENT '错误次数',
  `time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '错误时间',
  `expand` int(11) DEFAULT NULL COMMENT '预留可扩展字段',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1175969 DEFAULT CHARSET=utf8 COMMENT='资金密码错误次数表';

-- ----------------------------
-- Table structure for user_qualified_for_138
-- ----------------------------
DROP TABLE IF EXISTS `user_qualified_for_138`;
CREATE TABLE `user_qualified_for_138` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `user_rank` int(2) NOT NULL DEFAULT '0' COMMENT '店铺等级',
  `sale_amount` int(11) NOT NULL DEFAULT '0' COMMENT '销售额',
  `x` int(10) NOT NULL DEFAULT '0' COMMENT 'X轴',
  `y` int(10) NOT NULL DEFAULT '0' COMMENT 'Y轴',
  `create_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1314365 DEFAULT CHARSET=utf8 COMMENT='获得138合格人数';

-- ----------------------------
-- Table structure for user_rank
-- ----------------------------
DROP TABLE IF EXISTS `user_rank`;
CREATE TABLE `user_rank` (
  `rank_id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT COMMENT '会员等级编号',
  `rank_name` varchar(30) NOT NULL DEFAULT '0' COMMENT '会员等级名称',
  `annual_fee` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '年费',
  `manage_fee` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '管理费',
  PRIMARY KEY (`rank_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='会员等级配置信息';

-- ----------------------------
-- Table structure for user_recommend_commission_logs
-- ----------------------------
DROP TABLE IF EXISTS `user_recommend_commission_logs`;
CREATE TABLE `user_recommend_commission_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `name` varchar(255) DEFAULT NULL,
  `supplier_id` int(5) NOT NULL DEFAULT '0' COMMENT '供应商ID',
  `goods_sn_main` varchar(255) NOT NULL DEFAULT '' COMMENT '商品主SKU',
  `goods_name` varchar(255) DEFAULT NULL COMMENT '商品名称',
  `amount` decimal(10,2) DEFAULT '0.00' COMMENT '金额',
  `sale_number` int(11) NOT NULL DEFAULT '0',
  `is_group` int(1) DEFAULT '0' COMMENT '是否套餐',
  `created_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=37308 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for user_reduce_commission_logs
-- ----------------------------
DROP TABLE IF EXISTS `user_reduce_commission_logs`;
CREATE TABLE `user_reduce_commission_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '抽回的用户',
  `amount` decimal(14,2) NOT NULL DEFAULT '0.00' COMMENT '抽回的金额',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1=>2*5见点佣金；2=>138见点佣金；3=>团队销售佣金；4=>团队无限代；5=>个人店铺销售佣金；6=>周分红；7=>周领导对等奖;8=>月领导分红奖，14=>月费池转现金池',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '抽回的时间',
  `pay_user_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `type` (`type`),
  KEY `pay_user_id` (`pay_user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=149034 DEFAULT CHARSET=utf8 COMMENT='用戶佣金抽回記錄  （各大獎項的抽回）';

-- ----------------------------
-- Table structure for user_sale_rank_repair_log
-- ----------------------------
DROP TABLE IF EXISTS `user_sale_rank_repair_log`;
CREATE TABLE `user_sale_rank_repair_log` (
  `log_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT '会员ID',
  `before_rank` tinyint(4) NOT NULL COMMENT '修复前职称',
  `after_rank` tinyint(4) NOT NULL COMMENT '修复后职称',
  `repair_time` datetime NOT NULL COMMENT '修复时间',
  PRIMARY KEY (`log_id`)
) ENGINE=InnoDB AUTO_INCREMENT=36284 DEFAULT CHARSET=utf8 COMMENT='会员职称修复日志表';

-- ----------------------------
-- Table structure for user_sort
-- ----------------------------
DROP TABLE IF EXISTS `user_sort`;
CREATE TABLE `user_sort` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `email` varchar(255) NOT NULL DEFAULT '0',
  `leader_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户上级ID',
  `tree` longblob COMMENT '排序树(序列化)',
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=3173 DEFAULT CHARSET=utf8 COMMENT='2x5排序表';

-- ----------------------------
-- Table structure for user_sort_2x5
-- ----------------------------
DROP TABLE IF EXISTS `user_sort_2x5`;
CREATE TABLE `user_sort_2x5` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) DEFAULT NULL,
  `pay_parent_id` int(11) NOT NULL DEFAULT '0',
  `leader_id` int(11) NOT NULL DEFAULT '0',
  `left_id` int(11) DEFAULT NULL,
  `right_id` int(11) DEFAULT NULL,
  `level` int(11) NOT NULL DEFAULT '0',
  `create_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `child_count` int(11) DEFAULT '0',
  PRIMARY KEY (`Id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=91596 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for user_suite_exchange_coupon
-- ----------------------------
DROP TABLE IF EXISTS `user_suite_exchange_coupon`;
CREATE TABLE `user_suite_exchange_coupon` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '代品券id',
  `face_value` smallint(6) unsigned NOT NULL DEFAULT '1' COMMENT '面额',
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0代表未使用，1代表使用',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `use_time` timestamp NULL DEFAULT NULL COMMENT '使用时间',
  PRIMARY KEY (`id`),
  KEY `face_value` (`face_value`),
  KEY `uid` (`uid`),
  KEY `status` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=78573020 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for user_transfer_account_waring
-- ----------------------------
DROP TABLE IF EXISTS `user_transfer_account_waring`;
CREATE TABLE `user_transfer_account_waring` (
  `id` int(22) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT NULL COMMENT '转账用户id',
  `amount` int(11) DEFAULT NULL COMMENT '转账金额',
  `relate_uid` int(11) DEFAULT NULL COMMENT '转账用户id',
  `transfer_time` datetime DEFAULT NULL COMMENT '转账时间',
  `create_time` datetime DEFAULT NULL COMMENT '告警创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=83055 DEFAULT CHARSET=utf8 COMMENT='转账监控';

-- ----------------------------
-- Table structure for user_transfer_refund_logs
-- ----------------------------
DROP TABLE IF EXISTS `user_transfer_refund_logs`;
CREATE TABLE `user_transfer_refund_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` varchar(100) NOT NULL DEFAULT '0' COMMENT '賬號ID',
  `receive_email` varchar(100) NOT NULL DEFAULT '0' COMMENT '接收人郵箱',
  `receive_card_number` varchar(100) NOT NULL DEFAULT '0' COMMENT '接收人身份證',
  `transfer_card_number` varchar(100) NOT NULL DEFAULT '0' COMMENT '轉讓人身份證',
  `refund_card_number` varchar(100) NOT NULL DEFAULT '0' COMMENT '退款人身份證',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1：轉讓 2：退款',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '轉讓或退款時間',
  `check_admin` int(11) NOT NULL DEFAULT '0' COMMENT '操作管理员',
  `check_info` text NOT NULL COMMENT '管理员备注',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `type_idx` (`type`)
) ENGINE=InnoDB AUTO_INCREMENT=46912 DEFAULT CHARSET=utf8 COMMENT='用户账号转让或者退款';

-- ----------------------------
-- Table structure for user_upgrade_log
-- ----------------------------
DROP TABLE IF EXISTS `user_upgrade_log`;
CREATE TABLE `user_upgrade_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL DEFAULT '0',
  `upgrade_rank` tinyint(3) NOT NULL DEFAULT '0' COMMENT '用户等级, 4：免费会员.....',
  `create_time` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2296250 DEFAULT CHARSET=utf8 COMMENT='用户等级信息表';

-- ----------------------------
-- Table structure for user_upgrade_month_order
-- ----------------------------
DROP TABLE IF EXISTS `user_upgrade_month_order`;
CREATE TABLE `user_upgrade_month_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_sn` varchar(50) NOT NULL DEFAULT '0' COMMENT '訂單號',
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用戶ID',
  `money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '月份訂單金額',
  `usd_money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '月費等級金額',
  `level` tinyint(3) NOT NULL DEFAULT '3' COMMENT '需要升级的等级',
  `txn_id` varchar(255) NOT NULL DEFAULT '0' COMMENT 'paypal的交易号 验证是否重复',
  `status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '訂單狀態: 0:未付款 1：付款中 2：已付款',
  `payment` varchar(50) NOT NULL DEFAULT '0' COMMENT '通過什麼方式付款的',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '訂單生成時間',
  `pay_time` timestamp NULL DEFAULT NULL COMMENT '付款时间',
  `notify_num` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '该订单的接口通知次数',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=38841 DEFAULT CHARSET=utf8 COMMENT='用戶升級的訂單';

-- ----------------------------
-- Table structure for user_upgrade_order
-- ----------------------------
DROP TABLE IF EXISTS `user_upgrade_order`;
CREATE TABLE `user_upgrade_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_sn` varchar(50) NOT NULL DEFAULT '0' COMMENT '訂單號',
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用戶ID',
  `money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '升級訂單金額',
  `join_fee` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT 'tps会员产品金额',
  `before_level` tinyint(3) NOT NULL DEFAULT '0' COMMENT '升級前的等級',
  `level` tinyint(3) NOT NULL DEFAULT '3' COMMENT '需要升级的等级',
  `txn_id` varchar(255) NOT NULL DEFAULT '0' COMMENT 'paypal的交易号 验证是否重复',
  `status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '訂單狀態: 0:未付款 1：付款中 2：已付款',
  `payment` varchar(50) NOT NULL DEFAULT '0' COMMENT '通過什麼方式付款的',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '訂單生成時間',
  `pay_time` timestamp NULL DEFAULT NULL COMMENT '付款时间',
  `notify_num` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '该订单的接口通知次数',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15005 DEFAULT CHARSET=utf8 COMMENT='用戶升級的訂單';

-- ----------------------------
-- Table structure for user_week_bonus_qualified_list
-- ----------------------------
DROP TABLE IF EXISTS `user_week_bonus_qualified_list`;
CREATE TABLE `user_week_bonus_qualified_list` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `sale_amount_weight` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '销售额权重（销售额美分计数）',
  `sale_rank_weight` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '职称权重（资深店主1，市场主管2，高级市场主管3，市场总监4，市场副总裁5）',
  `store_rank_weight` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '店铺等级权重（银级店铺1，白金级店铺2，钻石级店铺3）',
  `share_point_weight` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分红点权重（分以红点*100，换算成分来计数）',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='每周团队分红发奖信息列表';

-- ----------------------------
-- Table structure for user_week_leader_member_list
-- ----------------------------
DROP TABLE IF EXISTS `user_week_leader_member_list`;
CREATE TABLE `user_week_leader_member_list` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='周领导对等奖会员列表';

-- ----------------------------
-- Table structure for voucher_manage_logs
-- ----------------------------
DROP TABLE IF EXISTS `voucher_manage_logs`;
CREATE TABLE `voucher_manage_logs` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `voucher_value` int(11) NOT NULL DEFAULT '0' COMMENT '代品券金额',
  `admin_id` int(11) NOT NULL DEFAULT '0' COMMENT '管理员id',
  `reason` text COMMENT '原因',
  `create_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=1257 DEFAULT CHARSET=utf8 COMMENT='代品券管理日志';

-- ----------------------------
-- Table structure for walmart_orders
-- ----------------------------
DROP TABLE IF EXISTS `walmart_orders`;
CREATE TABLE `walmart_orders` (
  `order_id` varchar(40) NOT NULL DEFAULT '0' COMMENT '订单号',
  `customer_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '顾客id',
  `shopkeeper_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '店主id',
  `order_amount` decimal(14,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '订单金额',
  `order_profit` decimal(14,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '订单利润',
  `currency` char(3) NOT NULL DEFAULT '0' COMMENT '币种（国际币种标识符）',
  `order_amount_usd` decimal(14,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '订单金额（美元）',
  `order_profit_usd` decimal(14,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '订单利润（美元）',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `score_year_month` char(6) NOT NULL DEFAULT '0' COMMENT '业绩年月',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '默认1=>已完成，2=>已取消',
  `order_from` mediumint(8) unsigned NOT NULL DEFAULT '1' COMMENT '1.沃尔玛订单，2.待定',
  `order_amount_usd_one_third` int(11) NOT NULL DEFAULT '0' COMMENT '订单总额的三分之一,单位美分',
  `affiliate` varchar(100) NOT NULL DEFAULT '' COMMENT '订单来源名称',
  PRIMARY KEY (`order_id`),
  KEY `idx_customer_id` (`customer_id`,`create_time`,`status`) USING BTREE,
  KEY `idx_shopkeeper_id` (`shopkeeper_id`,`create_time`,`status`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='沃尔玛订单';

-- ----------------------------
-- Table structure for walmart_order_user_list
-- ----------------------------
DROP TABLE IF EXISTS `walmart_order_user_list`;
CREATE TABLE `walmart_order_user_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL COMMENT '用户id',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_uid` (`uid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='沃尔玛下单队列';

-- ----------------------------
-- Table structure for week_leader_members
-- ----------------------------
DROP TABLE IF EXISTS `week_leader_members`;
CREATE TABLE `week_leader_members` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='周领导对等奖会员列表';

-- ----------------------------
-- Table structure for week_leader_members_queue
-- ----------------------------
DROP TABLE IF EXISTS `week_leader_members_queue`;
CREATE TABLE `week_leader_members_queue` (
  `uid` int(11) unsigned NOT NULL COMMENT '用户id',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for week_leader_preview
-- ----------------------------
DROP TABLE IF EXISTS `week_leader_preview`;
CREATE TABLE `week_leader_preview` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `name` varchar(100) NOT NULL DEFAULT '' COMMENT '用户姓名',
  `date` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '发奖日期，值为发奖周周一的日期',
  `child_reward` int(11) NOT NULL DEFAULT '0' COMMENT '下（两）级上周总奖金，单位：美分',
  `current_amount` int(11) NOT NULL DEFAULT '0' COMMENT '用户当前周对等奖金总额，单位：美分',
  `due_amount` int(11) NOT NULL DEFAULT '0' COMMENT '本周应得奖金，单位：美分',
  `percent` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '周领导对等奖比例',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '周领导对等奖发放状态 0未审核 1已审核 2已发放',
  `pay_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '周领导对等奖发放时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `date` (`date`)
) ENGINE=InnoDB AUTO_INCREMENT=128935 DEFAULT CHARSET=utf8 COMMENT='周领导对等奖预览';

-- ----------------------------
-- Table structure for week_share_qualified_list
-- ----------------------------
DROP TABLE IF EXISTS `week_share_qualified_list`;
CREATE TABLE `week_share_qualified_list` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `sale_amount_weight` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '销售额权重（销售额美分计数）',
  `sale_rank_weight` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '职称权重（资深店主1，市场主管2，高级市场主管3，市场总监4，市场副总裁5）',
  `store_rank_weight` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '店铺等级权重（银级店铺1，白金级店铺2，钻石级店铺3）',
  `share_point_weight` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分红点权重（分以红点*100，换算成分来计数）',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='每周团队分红发奖信息列表';

-- ----------------------------
-- Table structure for week_share_qualified_list_201704
-- ----------------------------
DROP TABLE IF EXISTS `week_share_qualified_list_201704`;
CREATE TABLE `week_share_qualified_list_201704` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `sale_amount_weight` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '销售额权重（销售额美分计数）',
  `sale_rank_weight` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '职称权重（资深店主1，市场主管2，高级市场主管3，市场总监4，市场副总裁5）',
  `store_rank_weight` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '店铺等级权重（银级店铺1，白金级店铺2，钻石级店铺3）',
  `share_point_weight` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分红点权重（分以红点*100，换算成分来计数）',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for week_share_qualified_list_201705
-- ----------------------------
DROP TABLE IF EXISTS `week_share_qualified_list_201705`;
CREATE TABLE `week_share_qualified_list_201705` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `sale_amount_weight` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '销售额权重（销售额美分计数）',
  `sale_rank_weight` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '职称权重（资深店主1，市场主管2，高级市场主管3，市场总监4，市场副总裁5）',
  `store_rank_weight` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '店铺等级权重（银级店铺1，白金级店铺2，钻石级店铺3）',
  `share_point_weight` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分红点权重（分以红点*100，换算成分来计数）',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for week_share_qualified_list_201706
-- ----------------------------
DROP TABLE IF EXISTS `week_share_qualified_list_201706`;
CREATE TABLE `week_share_qualified_list_201706` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `sale_amount_weight` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '销售额权重（销售额美分计数）',
  `sale_rank_weight` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '职称权重（资深店主1，市场主管2，高级市场主管3，市场总监4，市场副总裁5）',
  `store_rank_weight` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '店铺等级权重（银级店铺1，白金级店铺2，钻石级店铺3）',
  `share_point_weight` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分红点权重（分以红点*100，换算成分来计数）',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for withdraw_task
-- ----------------------------
DROP TABLE IF EXISTS `withdraw_task`;
CREATE TABLE `withdraw_task` (
  `uid` int(10) unsigned NOT NULL,
  `comm_type` tinyint(3) unsigned NOT NULL,
  `comm_year_month` mediumint(6) unsigned NOT NULL,
  `comm_amount` int(10) unsigned NOT NULL DEFAULT '0',
  `order_year_month` mediumint(6) unsigned NOT NULL COMMENT '订单年月',
  `sale_amount_lack` int(10) NOT NULL DEFAULT '0' COMMENT '单位：分。',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0=>默认值，1=>补单中',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`uid`,`comm_type`,`comm_year_month`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for __drds__system__lock__
-- ----------------------------
DROP TABLE IF EXISTS `__drds__system__lock__`;
CREATE TABLE `__drds__system__lock__` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `gmt_create` datetime NOT NULL COMMENT '创建时间',
  `gmt_modified` datetime NOT NULL COMMENT '修改时间',
  `name` varchar(255) NOT NULL COMMENT 'name',
  `token` varchar(255) NOT NULL COMMENT 'token',
  `identity` varchar(255) NOT NULL COMMENT 'identity',
  `operator` varchar(255) NOT NULL COMMENT 'operator',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_NAME` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=3254 DEFAULT CHARSET=utf8;

-- ----------------------------
-- View structure for amount_log_sum
-- ----------------------------
DROP VIEW IF EXISTS `amount_log_sum`;
CREATE ALGORITHM=UNDEFINED DEFINER=`tps138`@`%` SQL SECURITY DEFINER VIEW `amount_log_sum` AS select (sum(`commission_logs`.`amount`) * 100) AS `sum_amount` from `commission_logs` where (`commission_logs`.`type` in (5,3,6,24,1,8,2,25,7,23,4,26,16)) union select sum(`cash_account_log_201607`.`amount`) AS `sum_amount` from `cash_account_log_201607` where (`cash_account_log_201607`.`item_type` in (5,3,6,24,1,8,2,25,7,23,4,26,16)) union select sum(`cash_account_log_201608`.`amount`) AS `sum_amount` from `cash_account_log_201608` where (`cash_account_log_201608`.`item_type` in (5,3,6,24,1,8,2,25,7,23,4,26,16)) union select sum(`cash_account_log_201609`.`amount`) AS `sum_amount` from `cash_account_log_201609` where (`cash_account_log_201609`.`item_type` in (5,3,6,24,1,8,2,25,7,23,4,26,16)) union select sum(`cash_account_log_201610`.`amount`) AS `sum_amount` from `cash_account_log_201610` where (`cash_account_log_201610`.`item_type` in (5,3,6,24,1,8,2,25,7,23,4,26,16)) union select sum(`cash_account_log_201611`.`amount`) AS `sum_amount` from `cash_account_log_201611` where (`cash_account_log_201611`.`item_type` in (5,3,6,24,1,8,2,25,7,23,4,26,16)) union select sum(`cash_account_log_201612`.`amount`) AS `sum_amount` from `cash_account_log_201612` where (`cash_account_log_201612`.`item_type` in (5,3,6,24,1,8,2,25,7,23,4,26,16)) union select sum(`cash_account_log_201701`.`amount`) AS `sum_amount` from `cash_account_log_201701` where (`cash_account_log_201701`.`item_type` in (5,3,6,24,1,8,2,25,7,23,4,26,16)) union select sum(`cash_account_log_201702`.`amount`) AS `sum_amount` from `cash_account_log_201702` where (`cash_account_log_201702`.`item_type` in (5,3,6,24,1,8,2,25,7,23,4,26,16)) union select sum(`cash_account_log_201703`.`amount`) AS `sum_amount` from `cash_account_log_201703` where (`cash_account_log_201703`.`item_type` in (5,3,6,24,1,8,2,25,7,23,4,26,16)) ;

-- ----------------------------
-- View structure for cash_log_sum
-- ----------------------------
DROP VIEW IF EXISTS `cash_log_sum`;
CREATE ALGORITHM=UNDEFINED DEFINER=`tps138`@`%` SQL SECURITY DEFINER VIEW `cash_log_sum` AS select (sum(`commission_logs`.`amount`) * 100) AS `sum_amount` from `commission_logs` where (`commission_logs`.`uid` = 1380238828) union select sum(`cash_account_log_201607`.`amount`) AS `sum_amount` from `cash_account_log_201607` where (`cash_account_log_201607`.`uid` = 1380238828) union select sum(`cash_account_log_201608`.`amount`) AS `sum_amount` from `cash_account_log_201608` where (`cash_account_log_201608`.`uid` = 1380238828) union select sum(`cash_account_log_201609`.`amount`) AS `sum_amount` from `cash_account_log_201609` where (`cash_account_log_201609`.`uid` = 1380238828) union select sum(`cash_account_log_201610`.`amount`) AS `sum_amount` from `cash_account_log_201610` where (`cash_account_log_201610`.`uid` = 1380238828) union select sum(`cash_account_log_201611`.`amount`) AS `sum_amount` from `cash_account_log_201611` where (`cash_account_log_201611`.`uid` = 1380238828) union select sum(`cash_account_log_201612`.`amount`) AS `sum_amount` from `cash_account_log_201612` where (`cash_account_log_201612`.`uid` = 1380238828) union select sum(`cash_account_log_201701`.`amount`) AS `sum_amount` from `cash_account_log_201701` where (`cash_account_log_201701`.`uid` = 1380238828) union select sum(`cash_account_log_201702`.`amount`) AS `sum_amount` from `cash_account_log_201702` where (`cash_account_log_201702`.`uid` = 1380238828) union select sum(`cash_account_log_201703`.`amount`) AS `sum_amount` from `cash_account_log_201703` where (`cash_account_log_201703`.`uid` = 1380238828) ;

-- ----------------------------
-- View structure for ly_view
-- ----------------------------
DROP VIEW IF EXISTS `ly_view`;
CREATE ALGORITHM=UNDEFINED DEFINER=`tps138`@`%` SQL SECURITY DEFINER VIEW `ly_view` AS select `users`.`parent_id` AS `parent_id`,count(0) AS `num` from `users` where (`users`.`user_rank` <> 4) group by `users`.`parent_id` having (`num` > 25) order by `num` desc limit 20 ;

-- ----------------------------
-- View structure for ly_view2
-- ----------------------------
DROP VIEW IF EXISTS `ly_view2`;
CREATE ALGORITHM=UNDEFINED DEFINER=`tps138`@`%` SQL SECURITY DEFINER VIEW `ly_view2` AS select `b`.`id` AS `会员id`,`b`.`name` AS `会员姓名`,if((`b`.`sale_rank` = 5),'销售副总裁','市场总监') AS `会员职称`,`b`.`address` AS `地址`,`a`.`num` AS `直推的付费会员数量` from (`ly_view` `a` left join `users` `b` on((`a`.`parent_id` = `b`.`id`))) where (`b`.`sale_rank` > 3) limit 15 ;

-- ----------------------------
-- Procedure structure for 138_shar_prepare
-- ----------------------------
DROP PROCEDURE IF EXISTS `138_shar_prepare`;
DELIMITER ;;
CREATE DEFINER=`tps138`@`%` PROCEDURE `138_shar_prepare`(
OUT totalNum int(11),OUT totalSharNum int(11))
    SQL SECURITY INVOKER
BEGIN
/*Desc:为138分红准备数据*/

select count(*) into totalNum from user_qualified_for_138;/*统计138分红总人数*/
select x,y into @lastX,@lastY from user_coordinates order by id desc limit 1;/*获取138矩阵的最后一个坐标*/
insert into 138_grant_tmp(uid,num_share) select user_id,(@lastY-y)-if(x>@lastX,1,0) from user_qualified_for_138;/*根据
矩阵最后一个坐标计算每个id矩阵底下的人数，并将数据存入临时表*/
select sum(num_share) into totalSharNum from 138_grant_tmp;/*统计发奖临时表中所有人底下人数的总和*/

END
;;
DELIMITER ;

-- ----------------------------
-- Procedure structure for 138_shar_prepare2
-- ----------------------------
DROP PROCEDURE IF EXISTS `138_shar_prepare2`;
DELIMITER ;;
CREATE DEFINER=`tps138`@`%` PROCEDURE `138_shar_prepare2`(
OUT totalNum int(11),OUT totalSharNum int(11))
    SQL SECURITY INVOKER
BEGIN
/*Desc:为138分红准备数据*/

select count(*) into totalNum from user_qualified_for_138;/*统计138分红总人数*/
select x,y into @lastX,@lastY from user_coordinates order by id desc limit 1;/*获取138矩阵的最后一个坐标*/
insert into 138_grant_tmp2(uid,num_share) select user_id,(@lastY-y)-if(x>@lastX,1,0) from user_qualified_for_138;/*根据
矩阵最后一个坐标计算每个id矩阵底下的人数，并将数据存入临时表*/
select sum(num_share) into totalSharNum from 138_grant_tmp2;/*统计发奖临时表中所有人底下人数的总和*/

END
;;
DELIMITER ;

-- ----------------------------
-- Procedure structure for add_to_daily_elite_qualified_list
-- ----------------------------
DROP PROCEDURE IF EXISTS `add_to_daily_elite_qualified_list`;
DELIMITER ;;
CREATE DEFINER=`tps138`@`%` PROCEDURE `add_to_daily_elite_qualified_list`(
IN qualified_uid int(11))
    SQL SECURITY INVOKER
BEGIN
##############[添加到精英日分红合格列表]##################

DECLARE u_pro_set_amount int default 0;#套餐销售额
DECLARE u_sale_amount int DEFAULT 0;#普通订单销售额
DECLARE exit_qualified_day int DEFAULT -1;#表中已存在的合格日期

/*事务开始*/
DECLARE t_error int default 0;
DECLARE CONTINUE HANDLER FOR SQLEXCEPTION SET t_error=1;
set autocommit=0;

#获取用户当前月套餐销售额
select pro_set_amount into u_pro_set_amount FROM stat_intr_mem_month where uid=qualified_uid 
and `year_month`=DATE_FORMAT(now(),'%Y%m');

#获取用户当前月普通订单销售额
select sale_amount into u_sale_amount from users_store_sale_info_monthly where uid=qualified_uid 
and `year_month`=DATE_FORMAT(now(),'%Y%m');

#插入合格列表
select qualified_day into exit_qualified_day from daily_bonus_elite_qualified_list where uid=qualified_uid;
if exit_qualified_day=-1 THEN
	#如果不存在记录，则插入
	insert into daily_bonus_elite_qualified_list(uid,bonus_shar_weight,qualified_day) 
values(qualified_uid,u_pro_set_amount+u_sale_amount,DATE_FORMAT(now(),'%Y%m%d'));
elseif exit_qualified_day=DATE_FORMAT(now(),'%Y%m%d') THEN
	#如果已经存在记录，并且是同一天的，用新记录替换旧记录
	replace into daily_bonus_elite_qualified_list(uid,bonus_shar_weight,qualified_day) 
values(qualified_uid,u_pro_set_amount+u_sale_amount,DATE_FORMAT(now(),'%Y%m%d'));
end if;

/*事务结束*/
IF t_error = 1 THEN
	ROLLBACK;insert into error_log(content) values(concat('加入精英日分红合格列表失败，uid:',qualified_uid));
ELSE
	COMMIT;
END IF;
SET autocommit=1;

END
;;
DELIMITER ;

-- ----------------------------
-- Procedure structure for check_newmember_sale_rank
-- ----------------------------
DROP PROCEDURE IF EXISTS `check_newmember_sale_rank`;
DELIMITER ;;
CREATE DEFINER=`tps138`@`%` PROCEDURE `check_newmember_sale_rank`(
IN p_uid int(10))
    SQL SECURITY INVOKER
BEGIN
################核算新升级会员的自身职称######################
#参数说明
#p_uid 新升级的会员id

DECLARE g_vp_num int(10);#有市场总监的分支数
DECLARE g_sd_num int(10);#有高级市场主管的分支数
DECLARE g_sm_num int(10);#有市场主管的分支数
DECLARE g_mso_num int(10);#有资深店主的分支数
DECLARE g_payed_child_num int(10);#直推的付费店铺数

###检查自己的职称
select count(*) into g_vp_num from users_child_group_info where uid=p_uid and vp_num>0;
if g_vp_num>2 THEN

	update users set sale_rank=5,sale_rank_up_time=now() where id=p_uid;
ELSE

	select count(*) into g_sd_num from users_child_group_info where uid=p_uid and sd_num>0;
	if g_sd_num>2 THEN

		update users set sale_rank=4,sale_rank_up_time=now() where id=p_uid;
	ELSE
		
		select count(*) into g_sm_num from users_child_group_info where uid=p_uid and sm_num>0;
		if g_sm_num>2 THEN

			update users set sale_rank=3,sale_rank_up_time=now() where id=p_uid;
		ELSE

			select count(*) into g_mso_num from users_child_group_info where uid=p_uid and mso_num>0;
			if g_mso_num>2 THEN
				
				update users set sale_rank=2,sale_rank_up_time=now() where id=p_uid;
			ELSE
				
				select count(*) into g_payed_child_num from users where parent_id=p_uid and user_rank<>4;
				if g_payed_child_num>2 THEN

					update users set sale_rank=1,sale_rank_up_time=now() where id=p_uid;
				end if;
			end if;
		end if;
	end if;
end if;

END
;;
DELIMITER ;

-- ----------------------------
-- Procedure structure for comm_stat_init_ly
-- ----------------------------
DROP PROCEDURE IF EXISTS `comm_stat_init_ly`;
DELIMITER ;;
CREATE DEFINER=`tps138`@`%` PROCEDURE `comm_stat_init_ly`(
)
    SQL SECURITY INVOKER
BEGIN

DECLARE f_id int;
DECLARE comm int;
DECLARE comm_7 int;
DECLARE comm_8 int;
DECLARE comm_9 int;
DECLARE comm_total int;

DECLARE done int default 0;#控制主循环的结束符
DECLARE cur_queue CURSOR FOR SELECT id FROM users order by id LIMIT 180000,20000;
DECLARE CONTINUE HANDLER FOR NOT FOUND set done=1;
OPEN cur_queue;
FETCH cur_queue INTO f_id;
WHILE done<>1 do

	#统计精英日分红
	set comm=0;set comm_7=0;set comm_8=0;set comm_9=0;set comm_total=0;
	select round(sum(amount)*100) into comm from commission_logs where uid=f_id and `type`=24;
	select sum(amount) into comm_7 from cash_account_log_201607 where uid=f_id and item_type=24;
	select sum(amount) into comm_8 from cash_account_log_201608 where uid=f_id and item_type=24;
	select sum(amount) into comm_9 from cash_account_log_201609 where uid=f_id and item_type=24;
	if comm is not null THEN
		set comm_total=comm_total+comm;
	end if;
	if comm_7 is not null THEN
		set comm_total=comm_total+comm_7;
	end if;
	if comm_8 is not null THEN
		set comm_total=comm_total+comm_8;
	end if;
	if comm_9 is not null THEN
		set comm_total=comm_total+comm_9;
	end if;
	insert into user_comm_stat(uid,daily_bonus_elite) values(f_id,comm_total) on DUPLICATE KEY 
UPDATE daily_bonus_elite=daily_bonus_elite+comm_total;

	#统计138
	set comm=0;set comm_7=0;set comm_8=0;set comm_9=0;set comm_total=0;
	select round(sum(amount)*100) into comm from commission_logs where uid=f_id and `type`=2;
	select sum(amount) into comm_7 from cash_account_log_201607 where uid=f_id and item_type=2;
	select sum(amount) into comm_8 from cash_account_log_201608 where uid=f_id and item_type=2;
	select sum(amount) into comm_9 from cash_account_log_201609 where uid=f_id and item_type=2;
	if comm is not null THEN
		set comm_total=comm_total+comm;
	end if;
	if comm_7 is not null THEN
		set comm_total=comm_total+comm_7;
	end if;
	if comm_8 is not null THEN
		set comm_total=comm_total+comm_8;
	end if;
	if comm_9 is not null THEN
		set comm_total=comm_total+comm_9;
	end if;
	insert into user_comm_stat(uid,138_bonus) values(f_id,comm_total) on DUPLICATE KEY 
UPDATE 138_bonus=138_bonus+comm_total;

	#统计周领导奖
	set comm=0;set comm_7=0;set comm_8=0;set comm_9=0;set comm_total=0;
	select round(sum(amount)*100) into comm from commission_logs where uid=f_id and `type`=7;
	select sum(amount) into comm_7 from cash_account_log_201607 where uid=f_id and item_type=7;
	select sum(amount) into comm_8 from cash_account_log_201608 where uid=f_id and item_type=7;
	select sum(amount) into comm_9 from cash_account_log_201609 where uid=f_id and item_type=7;
	if comm is not null THEN
		set comm_total=comm_total+comm;
	end if;
	if comm_7 is not null THEN
		set comm_total=comm_total+comm_7;
	end if;
	if comm_8 is not null THEN
		set comm_total=comm_total+comm_8;
	end if;
	if comm_9 is not null THEN
		set comm_total=comm_total+comm_9;
	end if;
	insert into user_comm_stat(uid,week_bonus) values(f_id,comm_total) on DUPLICATE KEY 
UPDATE week_bonus=week_bonus+comm_total;

	set done=0;


FETCH cur_queue INTO f_id;
END WHILE;
CLOSE cur_queue;

END
;;
DELIMITER ;

-- ----------------------------
-- Procedure structure for count_charge_month_fee
-- ----------------------------
DROP PROCEDURE IF EXISTS `count_charge_month_fee`;
DELIMITER ;;
CREATE DEFINER=`tps138`@`%` PROCEDURE `count_charge_month_fee`(
IN curDay int(11),IN curMonthLastDay int(11))
    SQL SECURITY INVOKER
BEGIN
	DECLARE var_id,record_count  INT  DEFAULT 0; 
	DECLARE var_time TIMESTAMP ;
	DECLARE str_where VARCHAR(200);-- 定义查询当前需缴纳月费的会员条件  
	DECLARE t_error INTEGER DEFAULT 0;  
        DECLARE CONTINUE HANDLER FOR SQLEXCEPTION SET t_error=1; 
	SET curDay=IFNULL(curDay,0);-- 判断设置默认当前日期
	IF curDay=0 THEN
		SET curDay=DAY(NOW());
	END IF;
	SET curMonthLastDay=IFNULL(curMonthLastDay,0);
	IF curMonthLastDay = 0 THEN	
		SET curMonthLastDay = DATEDIFF(DATE_ADD(CURDATE() - DAY(CURDATE()) + 1, INTERVAL 1 MONTH), DATE_ADD(CURDATE(), INTERVAL - DAY(CURDATE()) + 1 DAY));
	END IF;	 
	SET str_where=CONCAT(" and upgrade_month_fee_time<'",DATE_FORMAT(NOW(),'%Y-%m'),'-',curDay,"'  ");
	IF curMonthLastDay=curDay THEN
		SET str_where=CONCAT(str_where,' and  month_fee_date>=',curDay);
	ELSE
		SET str_where=CONCAT(str_where,' and  month_fee_date=',curDay);     
	END IF;  
	CREATE TEMPORARY TABLE tmp_user_month_fee_table(id INT);-- 创建临时表   
	SET @sqlstr=CONCAT('insert into tmp_user_month_fee_table(id) select id from users where status in(1,3) AND id NOT IN (SELECT uid FROM users_month_fee_fail_info)   ',str_where );  -- 执行插入临时数据
        PREPARE stmt FROM @sqlstr; 
	EXECUTE stmt;  
	DEALLOCATE PREPARE stmt; 	    
	-- SELECT COUNT(id) INTO record_count FROM tmp_user_month_fee_table;-- 统计临时数据表记录条数 去掉这个判断无意义
	SET var_time=NOW();
	START TRANSACTION;
        -- IF record_count>0 THEN		-- 执行插入需扣费的会员数据
	INSERT INTO sync_charge_month_fee(uid,create_time) SELECT tmp.id,var_time FROM tmp_user_month_fee_table tmp  WHERE   tmp.id  NOT  IN(SELECT id FROM sync_charge_month_fee) ;
	DROP  TABLE tmp_user_month_fee_table;   
        --  END IF;  
	-- 记录调度日志
       IF t_error = 1 THEN  
            ROLLBACK;  
            INSERT INTO logs_cron(content,create_time)VALUES('统计交月费，欠月费会员.[执行失败]',var_time); 
        ELSE  
            COMMIT;   
            INSERT INTO logs_cron(content,create_time)VALUES('统计交月费，欠月费会员.[执行完成]',var_time); 
        END IF;  
END
;;
DELIMITER ;

-- ----------------------------
-- Procedure structure for do_daily_elite_shar_page
-- ----------------------------
DROP PROCEDURE IF EXISTS `do_daily_elite_shar_page`;
DELIMITER ;;
CREATE DEFINER=`tps138`@`%` PROCEDURE `do_daily_elite_shar_page`(
IN curpage int(11),IN pagesize int(11),IN totalSharAmount bigint(20),IN totalWeight bigint(20))
    SQL SECURITY INVOKER
BEGIN
################分页发放精英日分红######################

DECLARE t_error INT DEFAULT 0;#定义事务相关参数
DECLARE comm_amount int;#需发放的精英分红奖金，单位：分。
DECLARE li_uid int;#拿奖人id
DECLARE li_bonus_shar_weight int;#拿奖人权重点
DECLARE done int default 0;#控制主循环的结束符
DECLARE itemStart int default (curpage-1)*pagesize;
DECLARE cur_list CURSOR FOR select uid,bonus_shar_weight from daily_bonus_elite_qualified_list where qualified_day<
DATE_FORMAT(now(),'%Y%m%d') ORDER BY uid limit itemStart,pagesize;#按分页取出要发奖的人员列表（游标）
DECLARE CONTINUE HANDLER FOR NOT FOUND set done=1;#定义循环hander
DECLARE CONTINUE HANDLER FOR SQLEXCEPTION SET t_error=1;#定义事务hander

set autocommit=0;#定义不自动提交，为事务开启
OPEN cur_list;
FETCH cur_list INTO li_uid,li_bonus_shar_weight;
WHILE done=0 do

	select id into li_uid from users where id=li_uid and store_qualified=1;
	if done=0 THEN
		set comm_amount = round(totalSharAmount/totalWeight*li_bonus_shar_weight);
		call grant_comm_single(li_uid,comm_amount,24,'');
	ELSE
		set done=0;
	end if;

	FETCH cur_list INTO li_uid,li_bonus_shar_weight;
END WHILE;
CLOSE cur_list;#关闭游标
#------------循环结束--------------

/*事务结束*/
IF t_error = 1 THEN
	ROLLBACK;insert into error_log(content) values(concat('分页发放精英日分红失败。 curpage:',curpage,'; pagesize:',
pagesize,'; totalSharAmount:',totalSharAmount,'; totalWeight:',totalWeight));
ELSE
	COMMIT;
END IF;
SET autocommit=1;

END
;;
DELIMITER ;

-- ----------------------------
-- Procedure structure for for_temp_save_coupons
-- ----------------------------
DROP PROCEDURE IF EXISTS `for_temp_save_coupons`;
DELIMITER ;;
CREATE DEFINER=`tps138`@`%` PROCEDURE `for_temp_save_coupons`(
IN p_uid int(10),IN p_oid char(19))
    SQL SECURITY INVOKER
BEGIN
################处理升级订单代品券######################

DECLARE li_coupons_value int(11);#列表中的代品券面值
DECLARE li_coupons_num int(11);#列表中的代品券数量
DECLARE i int;#循环次数

DECLARE done int default 0;#定义循环的结束符
DECLARE cur_list CURSOR FOR select coupons_value,coupons_num from temp_save_coupons where order_id=p_oid and user_id=
p_uid;#取出信息列表（游标）
DECLARE CONTINUE HANDLER FOR NOT FOUND set done=1;#定义循环hander

#-----------循环开始-------------
OPEN cur_list;
FETCH cur_list INTO li_coupons_value,li_coupons_num;
WHILE done=0 do

	set i=0;
	while (i<li_coupons_num) do

		insert into user_suite_exchange_coupon(face_value,uid) values(li_coupons_value,p_uid);
		set i=i+1;
	end WHILE;

	FETCH cur_list INTO li_coupons_value,li_coupons_num;
END WHILE;
CLOSE cur_list;#关闭游标
#------------循环结束--------------

#清掉临时代品券表中的数据
delete from temp_save_coupons where user_id=p_uid and order_id=p_oid;

END
;;
DELIMITER ;

-- ----------------------------
-- Procedure structure for get_supplier_recommend_commission
-- ----------------------------
DROP PROCEDURE IF EXISTS `get_supplier_recommend_commission`;
DELIMITER ;;
CREATE DEFINER=`tps138`@`%` PROCEDURE `get_supplier_recommend_commission`(min_time TIMESTAMP,max_time TIMESTAMP,istest INT)
BEGIN    
	    DECLARE minct ,maxct,_supplier_recommend,_supplier_id,_user_rank,_user_id,l_done,_country_id,re_count,_minct,_maxct,_status,_minct_s,_maxct_s ,_num INT  DEFAULT 0;   
	    DECLARE _name,_goods_sn_main,_goods_sn,_goods_name,_str,table_name VARCHAR(255); 
	    DECLARE _percentage  DECIMAL(12,6) DEFAULT 0.01;               
	    DECLARE _total_number,_order_amount_usd,_order_type,_order_profit_usd,_last_insert_id INT DEFAULT 0;
	    DECLARE _goods_price,_mall_goods_price,_mall_purchase_price,goods_profit,_amount,_amount_total,_sale_number_total DECIMAL(12,6) DEFAULT 0; 
	    DECLARE _create_time  TIMESTAMP DEFAULT NULL;
      DECLARE t_error INTEGER DEFAULT 0;  
      DECLARE _remark TEXT DEFAULT '';
	    DECLARE _supplier_recommend_commission,total_sale_goods_number VARCHAR(255) DEFAULT '';  
	    DECLARE CONTINUE HANDLER FOR SQLEXCEPTION SET t_error=1;  
	    CREATE TEMPORARY  TABLE IF NOT EXISTS  mall_supplier_Data_Tmp(
	    `Tmp_Id` INT UNSIGNED NOT NULL AUTO_INCREMENT,  
	    `supplier_recommend` INT  UNSIGNED  NOT NULL,
	    PRIMARY KEY (`Tmp_Id`)  
	    )ENGINE=MYISAM DEFAULT CHARSET=utf8;  
	    CREATE  TEMPORARY TABLE IF NOT EXISTS  mall_goods_main_Data_Tmp(
	    `T_Id` INT UNSIGNED NOT NULL AUTO_INCREMENT,  
	    `goods_sn_main` VARCHAR(50) NOT NULL,
	    PRIMARY KEY (`T_Id`)  
	    )ENGINE=MYISAM DEFAULT CHARSET=utf8;  
	    CREATE TEMPORARY  TABLE IF NOT EXISTS  mall_s_supplier_Data_Tmp(
	    `Ts_Id` INT UNSIGNED NOT NULL AUTO_INCREMENT,  
	    `s_id`  SMALLINT(5)  UNSIGNED  NOT NULL,
	    PRIMARY KEY (`Ts_Id`)  
	    )ENGINE=MYISAM DEFAULT CHARSET=utf8;  
	    IF max_time IS NULL THEN
                SET max_time= DATE_FORMAT(CURDATE(),'%Y-%m-%d');     
             END IF;    
             IF min_time IS NULL THEN
                SET min_time= DATE_SUB(max_time, INTERVAL 3 MONTH);  
             END IF; 
             SET _create_time=NOW();
             TRUNCATE TABLE mall_supplier_Data_Tmp; -- 
            SET @sqlstr= 'INSERT INTO  mall_supplier_Data_Tmp(`supplier_recommend`) SELECT  DISTINCT `supplier_recommend` FROM mall_supplier WHERE   `supplier_recommend`<>0 ;';  -- 执行插入临时数据 
            PREPARE stmt FROM @sqlstr;    
            EXECUTE stmt;  
            DEALLOCATE PREPARE stmt;  
            SET table_name=CONCAT('cash_account_log_', DATE_FORMAT(CURDATE(),'%Y%m'));         
            SELECT MIN(`Tmp_Id`),MAX(`Tmp_Id`) INTO minct ,maxct FROM mall_supplier_Data_Tmp; -- 获取表最小id 最大id  
            WHILE minct <= maxct &&minct>0 DO    
		          START TRANSACTION;  
		          SET _supplier_recommend=0;SET _supplier_id=0;SET _user_id=0;SET _user_rank=0;SET _country_id=0;SET re_count=0;SET _percentage=0;SET _name='';SET _str='';
		          SET _goods_price=0;SET _goods_sn='';SET _total_number=0;SET _goods_name='';SET _order_amount_usd=0;SET _goods_sn_main='';SET _order_type=0;SET _order_profit_usd=0;
		          SET _mall_goods_price=0;SET _mall_purchase_price=0;
		          SELECT `supplier_recommend` INTO _supplier_recommend  FROM mall_supplier_Data_Tmp WHERE `Tmp_Id`=minct  LIMIT 1;
           	         IF _supplier_recommend>0 THEN 
           	         SELECT id,`user_rank`,`name`,`country_id`,`status` INTO _user_id,_user_rank,_name,_country_id,_status FROM users WHERE `id` = _supplier_recommend  LIMIT 1;     -- 获取推荐人基本信息       	         
           	         IF _user_id>0 THEN  
           	           -- 设置对应的会员等级的提成比例
           	            CASE _user_rank 
                	     WHEN 1 THEN SET _percentage=0.02;
                	     WHEN 2 THEN SET _percentage=0.015; 
                	     ELSE SET _percentage=0.01;
                	     END CASE;  
                	     SET _minct_s=0;SET _maxct_s=0;
                	     TRUNCATE   TABLE mall_s_supplier_Data_Tmp; 
                             INSERT INTO  mall_s_supplier_Data_Tmp(`s_id`) SELECT `supplier_id` FROM mall_supplier WHERE  `supplier_recommend`=_supplier_recommend;  -- 执行插入临时数据  
                             SELECT MIN(`Ts_Id`),MAX(`Ts_Id`) INTO _minct_s ,_maxct_s FROM mall_s_supplier_Data_Tmp; -- 获取表最小id 最大id 
			     WHILE _minct_s <= _maxct_s &&_minct_s>0 DO   
                	                    SET _supplier_id=0;  
				            SELECT `s_id` INTO _supplier_id  FROM mall_s_supplier_Data_Tmp WHERE `Ts_Id`=_minct_s  LIMIT 1;  
			 		                IF _supplier_id>0     THEN 
			 		               
			 		                      IF istest=1 THEN 
			                             TRUNCATE   TABLE mall_goods_main_Data_Tmp;
			                             SET _minct=0;  SET _maxct=0;  
			                          	INSERT INTO  mall_goods_main_Data_Tmp(`goods_sn_main`) SELECT DISTINCT `goods_sn_main` FROM mall_goods_main WHERE  `supplier_id`=_supplier_id ; 
			                            SELECT MIN(`T_Id`),MAX(`T_Id`) INTO _minct ,_maxct FROM mall_goods_main_Data_Tmp; -- 获取表最小id 最大id   
				                          WHILE _minct <= _maxct &&_minct>0 DO   
			                              SET _str='';
				                            SELECT `goods_sn_main` INTO _str  FROM mall_goods_main_Data_Tmp WHERE `T_Id`=_minct  LIMIT 1;  
				                            SET _str=TRIM(_str);	
			                              IF _str<>'' THEN  
			                              -- 查询当前的销售订单信息       
			                               SELECT SUM(tg.goods_number) AS total_number,tg.goods_price,tg.goods_name
		                                	INTO _total_number,_goods_price,_goods_name	
			 	                                  	FROM trade_orders t,trade_orders_goods tg  WHERE t.order_id = tg.order_id AND t.status IN (4,5,6) AND tg.goods_sn_main = _str  AND   t.pay_time <max_time AND t.pay_time>=min_time;	 
			        	  IF _total_number>0 THEN
					                          -- 商品售价
					                            SELECT  `price`,`purchase_price` INTO  _mall_goods_price,_mall_purchase_price  FROM mall_goods WHERE goods_sn_main=_str  LIMIT 1 ; 
					                          -- 商品成本价
					                           SET goods_profit=0;SET _amount=0;
					                           SET goods_profit = _mall_goods_price - _mall_purchase_price -_goods_price * 0.05;
                                     SET _amount = goods_profit * _total_number * _percentage;
                                     --  插入到推荐佣金表
                                     IF _amount>0 THEN      
							                            INSERT INTO user_recommend_commission_logs(uid,`name`,`supplier_id`,`amount`,`goods_sn_main`,`goods_name`,`sale_number`,created_time)
							                              VALUES
							                                (_supplier_recommend,_name,_supplier_id,_amount * 0.8,_str,_goods_name,_total_number,NOW()); 
                                        END IF;
				                        	END IF;    
                                    END IF;	        
			        
			                            SET _minct=_minct+1;
		                            END WHILE; 
			                      
			                         ELSE
			                       IF _status=1 || _status=3 THEN
			          -- 插入到佣金表
					SET _amount_total=0;
					SET _sale_number_total=0;	-- 发放会员推荐供应商佣金				
                                           SELECT SUM(`amount`)AS amount,SUM(`sale_number`) AS total INTO _amount_total,_sale_number_total FROM user_recommend_commission_logs WHERE `uid` = _supplier_recommend AND `supplier_id` = _supplier_id  AND     `created_time` <max_time AND  `created_time`>=min_time;				       				
					-- 1-English; 2-简体中文:3-繁体中文 3-韩文  
					SET _sale_number_total=IFNULL(_sale_number_total,0);
					SET _amount_total=IFNULL(_amount_total,0);
					SET _supplier_recommend_commission='';
					SET total_sale_goods_number='';
					 CASE   _country_id   
						WHEN  2 THEN  
							SET _supplier_recommend_commission='供应商推荐奖' ; 
							SET total_sale_goods_number='总销售数量:sale_number:件';
						WHEN  3 THEN 
							SET _supplier_recommend_commission='供應商推薦獎' ; 
							SET total_sale_goods_number='總銷售數量:sale_number:件'; 
						ELSE
							SET _supplier_recommend_commission='The bonuses for Recommended supplier' ; 
							SET total_sale_goods_number='Total sales of :sale_number: items'; 
				         END CASE;   
					 SET _remark=CONCAT(_supplier_recommend_commission,':',REPLACE (total_sale_goods_number,'sale_number',_sale_number_total));  
					 IF istest<>1 THEN 	
					         SET @sqlstr='';					 
						 SET @sqlstr= CONCAT('insert into ', table_name ,'(`uid`,`item_type`,`amount`,`create_time`,`remark`)values(',CONCAT_WS(',',_supplier_recommend,9,ROUND(_amount_total)*100),',"',NOW(),'","',_remark,'");');                                			      
						 PREPARE stmt FROM @sqlstr; 
						 EXECUTE stmt;  
						 DEALLOCATE PREPARE stmt;  -- 更新账户余额 
						 UPDATE  users SET `amount`=`amount`+_amount_total WHERE `id`=_supplier_recommend  ; 
					END IF;
			         END IF;
			                         END IF;
                        END IF; 
                         SET _minct_s=_minct_s+1;
               END WHILE;    
           	         END IF;  
           	       END IF;    
           	        IF t_error = 1 THEN  
            ROLLBACK;   
        ELSE  
            COMMIT;      
        END IF;
        SET minct=minct+1;
        END WHILE;   
    END
;;
DELIMITER ;

-- ----------------------------
-- Procedure structure for give_monthlyfee_coupon
-- ----------------------------
DROP PROCEDURE IF EXISTS `give_monthlyfee_coupon`;
DELIMITER ;;
CREATE DEFINER=`tps138`@`%` PROCEDURE `give_monthlyfee_coupon`(
IN p_uid int(10),IN p_coupon_num int(11))
    SQL SECURITY INVOKER
BEGIN
################送月费券######################
#参数说明
#p_coupon_num 月费券的数量

DECLARE old_coupon_num int(10) DEFAULT 0;#旧的月费券数量
DECLARE new_coupon_num int(10) DEFAULT 0;#新月费券数量
DECLARE i int(10) DEFAULT 0;#用来控制循环遍历的次数
DECLARE u_month_fee_pool decimal(14,2);#用户月费池金额

SELECT count(*) into old_coupon_num from monthly_fee_coupon where uid=p_uid;

WHILE (i<p_coupon_num) do
	
	insert into monthly_fee_coupon(uid) values(p_uid);
	set i=i+1;
END WHILE;

SELECT count(*) into new_coupon_num from monthly_fee_coupon where uid=p_uid;
select month_fee_pool into u_month_fee_pool from users where id=p_uid;

insert into month_fee_change(user_id,old_month_fee_pool,month_fee_pool,cash,create_time,type,old_coupon_num,coupon_num,
coupon_num_change) values(p_uid,u_month_fee_pool,u_month_fee_pool,0,now(),3,old_coupon_num,new_coupon_num,p_coupon_num);

END
;;
DELIMITER ;

-- ----------------------------
-- Procedure structure for grant_comm_single
-- ----------------------------
DROP PROCEDURE IF EXISTS `grant_comm_single`;
DELIMITER ;;
CREATE DEFINER=`tps138`@`%` PROCEDURE `grant_comm_single`(
IN comm_uid int(11),IN comm_amount int(11),IN comm_item_type tinyint(4),IN order_id varchar(35))
    SQL SECURITY INVOKER
BEGIN
################发放佣金（单个）######################

DECLARE cur_cash_tb_name varchar(30) default concat('cash_account_log_',DATE_FORMAT(now(),'%Y%m'));#资金变动表名
DECLARE f_comm_stat VARCHAR(35);
DECLARE comm_to_point int default 0;#佣金自动转分红点的金额（单位：分）
DECLARE u_proportion int default 0;#佣金自动转分红点比例(百分比的分子，整型)

#生成资金变动报表记录
set @STMT :=concat('insert into ',cur_cash_tb_name,'(uid,item_type,amount,order_id) 
values(',comm_uid,',',comm_item_type,',',comm_amount,",'",order_id,"')");
PREPARE STMT FROM @STMT;
EXECUTE STMT;

#用户奖金统计
case comm_item_type
	when 24 then
		set f_comm_stat='daily_bonus_elite';
	when 2 THEN
		set f_comm_stat='138_bonus';
	when 7 THEN
		set f_comm_stat='week_bonus';
	when 25 THEN
		set f_comm_stat='week_share_bonus';
	when 1 THEN
		set f_comm_stat='month_group_share';
	else set f_comm_stat='';
end case;
if f_comm_stat<>'' THEN
	set @STMT :=concat('insert into user_comm_stat(uid,',f_comm_stat,') values(',comm_uid,',',comm_amount,') on DUPLICATE KEY 
UPDATE ',f_comm_stat,'=',f_comm_stat,'+',comm_amount);
	PREPARE STMT FROM @STMT;
	EXECUTE STMT;
end if;

#判断是否设置了自动转分红点比例
select round(proportion) into u_proportion from profit_sharing_point_proportion where uid=comm_uid and proportion_type=1;
if u_proportion>0 THEN#有自动转分红比例

	set comm_to_point = ROUND(comm_amount * u_proportion/100);
	if comm_to_point>0 then

		#更新用户表分红点
		update users set profit_sharing_point=profit_sharing_point+comm_to_point/100 where id=comm_uid;

		#生成相应资金变动报表记录（佣金转分红点）
		set @STMT :=concat('insert into ',cur_cash_tb_name,'(uid,item_type,amount) 
values(',comm_uid,',17,',-1*comm_to_point,')');
		PREPARE STMT FROM @STMT;
		EXECUTE STMT;

		#生成分红变动记录
		insert into profit_sharing_point_add_log(uid,add_source,money,point,create_time) values(comm_uid,1,
comm_to_point/100,comm_to_point/100,unix_timestamp());
	end if;
end if;
		
#更新用户现金池
update users set amount=amount+(comm_amount-comm_to_point)/100 where id=comm_uid;

END
;;
DELIMITER ;

-- ----------------------------
-- Procedure structure for grant_comm_single_new
-- ----------------------------
DROP PROCEDURE IF EXISTS `grant_comm_single_new`;
DELIMITER ;;
CREATE DEFINER=`tps138`@`%` PROCEDURE `grant_comm_single_new`(in comm_uid int,in comm_amount int,in comm_item_type TINYINT,in order_id VARCHAR(35),in cs_time VARCHAR(30))
BEGIN
/**********新的发放佣金（单个）2017-2-9 *********/

DECLARE cur_cash_tb_name varchar(30); 
DECLARE f_comm_stat VARCHAR(35);
DECLARE comm_to_point int default 0;/*佣金自动转分红点的金额（单位：分）*/
DECLARE u_proportion int default 0;/*佣金自动转分红点比例(百分比的分子，整型)*/

IF  order_id is NULL THEN
SET order_id='';
END IF;

IF cs_time = '' THEN
	set cur_cash_tb_name = concat('cash_account_log_',DATE_FORMAT(now(),'%Y%m'));#资金变动表名
	#生成资金变动报表记录
	set @STMT :=concat('insert into ',cur_cash_tb_name,'(uid,item_type,amount,order_id) 
	values(',comm_uid,',',comm_item_type,',',comm_amount,",'",order_id,"')");
ELSE
	#生成资金变动报表记录
	set cur_cash_tb_name = concat('cash_account_log_',DATE_FORMAT(cs_time,'%Y%m'));#资金变动表名
	SET @STMT :=CONCAT('insert into ',cur_cash_tb_name,'(uid,item_type,amount,create_time,order_id) 
	values(',comm_uid,',',comm_item_type,',',comm_amount,",'",cs_time,"','",order_id,"')");
END IF;

PREPARE STMT FROM @STMT;
EXECUTE STMT;

#用户奖金统计
case comm_item_type
	when 24 then
		set f_comm_stat='daily_bonus_elite';
	when 2 THEN
		set f_comm_stat='138_bonus';
	when 7 THEN
		set f_comm_stat='week_bonus';
	when 25 THEN
		set f_comm_stat='week_share_bonus';
	when 1 THEN
		set f_comm_stat='month_group_share';
	else set f_comm_stat='';
end case;
if f_comm_stat<>'' THEN
	set @STMT :=concat('insert into user_comm_stat(uid,',f_comm_stat,') values(',comm_uid,',',comm_amount,') on DUPLICATE KEY 
UPDATE ',f_comm_stat,'=',f_comm_stat,'+',comm_amount);
	PREPARE STMT FROM @STMT;
	EXECUTE STMT;
end if;

#判断是否设置了自动转分红点比例
select round(proportion) into u_proportion from profit_sharing_point_proportion where uid=comm_uid and proportion_type=1;
if u_proportion>0 THEN#有自动转分红比例

	set comm_to_point = ROUND(comm_amount * u_proportion/100);
	if comm_to_point>0 then

		#更新用户表分红点
		update users set profit_sharing_point=profit_sharing_point+comm_to_point/100 where id=comm_uid;

		#生成相应资金变动报表记录（佣金转分红点）
		set @STMT :=concat('insert into ',cur_cash_tb_name,'(uid,item_type,amount) 
values(',comm_uid,',17,',-1*comm_to_point,')');
		PREPARE STMT FROM @STMT;
		EXECUTE STMT;

		#生成分红变动记录
		insert into profit_sharing_point_add_log(uid,add_source,money,point,create_time) values(comm_uid,1,
comm_to_point/100,comm_to_point/100,unix_timestamp());
	end if;
end if;
		
#更新用户现金池
update users set amount=amount+(comm_amount-comm_to_point)/100 where id=comm_uid;

END
;;
DELIMITER ;

-- ----------------------------
-- Procedure structure for grant_month_group_share
-- ----------------------------
DROP PROCEDURE IF EXISTS `grant_month_group_share`;
DELIMITER ;;
CREATE DEFINER=`tps138`@`%` PROCEDURE `grant_month_group_share`(in istest tinyint(1))
BEGIN
################发放每月团队组织分红######################


DECLARE totalProfit_wh BIGINT DEFAULT 0;#沃好利润，单位：分。
DECLARE totalProfit_tps BIGINT DEFAULT 0;#tps利润，单位：分。
DECLARE totalProfit_1di BIGINT DEFAULT 0;#1direct利润，单位：分。
DECLARE totalProfit BIGINT DEFAULT 0;#用于发奖的总利润，单位：分。
DECLARE tp_sale_amount_weight BIGINT DEFAULT 0;#用于销售额权重的利润，单位：分。
DECLARE tp_sale_rank_weight BIGINT DEFAULT 0;#用于职称权重的利润，单位：分。
DECLARE tp_store_rank_weight BIGINT DEFAULT 0;#用于店铺权重的利润，单位：分。
DECLARE tn_sale_amount_weight BIGINT DEFAULT 0;#销售额总权重。
DECLARE tn_sale_rank_weight BIGINT DEFAULT 0;#职称总权重。
DECLARE tn_store_rank_weight BIGINT DEFAULT 0;#店铺总权重。
DECLARE comm_amount int DEFAULT 0;#需发放的奖金，单位：分。
DECLARE li_uid int;#拿奖人id
DECLARE li_sale_amount_weight int;#拿奖人销售额权重点
DECLARE li_sale_rank_weight int;#拿奖人职称权重点
DECLARE li_store_rank_weight tinyint;#拿奖人店铺等级权重点

DECLARE t_error INT DEFAULT 0;#定义事务相关参数
DECLARE done int default 0;#控制主循环的结束符
DECLARE cur_list CURSOR FOR select uid,sale_amount_weight,sale_rank_weight,store_rank_weight from
 month_group_share_list;#取出发奖人员信息列表（游标）
DECLARE CONTINUE HANDLER FOR NOT FOUND set done=1;#定义循环hander
DECLARE CONTINUE HANDLER FOR SQLEXCEPTION SET t_error=1;#定义事务hander

#统计上个月的总利润额，每种权重的金额
select round(if(sum(order_profit_usd) is not null,sum(order_profit_usd),0)*100) into totalProfit_wh from mall_orders 
where create_time>='2017-02-01' and create_time<'2017-03-01';
select round(if(sum(order_profit_usd) is not null,sum(order_profit_usd),0)*100) into totalProfit_1di from 
one_direct_orders where create_time>='2017-02-01' and create_time<'2017-03-01';
select if(sum(order_profit_usd) is not null,sum(order_profit_usd),0) into totalProfit_tps from trade_orders 
where pay_time>='2017-02-01' and pay_time<'2017-03-01' and order_prop in('0','2') and `status` in('3','4','5','6');
set totalProfit = round((totalProfit_wh+totalProfit_1di+totalProfit_tps)*0.24709);
set tp_sale_amount_weight = round(totalProfit*0.1);
set tp_sale_rank_weight = round(totalProfit*0.8);
set tp_store_rank_weight = round(totalProfit*0.1);

#统计每种权重下总权重点
select sum(sale_amount_weight) into tn_sale_amount_weight from month_group_share_list;
select sum(sale_rank_weight) into tn_sale_rank_weight from month_group_share_list;
select sum(store_rank_weight) into tn_store_rank_weight from month_group_share_list;

set autocommit=0;#定义不自动提交，为事务开启
set done=0;
OPEN cur_list;
FETCH cur_list INTO li_uid,li_sale_amount_weight,li_sale_rank_weight,li_store_rank_weight;
WHILE done=0 do

	select id into li_uid from users where id=li_uid and store_qualified=1;
	if done=0 THEN
		set comm_amount = round(tp_sale_amount_weight/tn_sale_amount_weight*li_sale_amount_weight + 
tp_sale_rank_weight/tn_sale_rank_weight*li_sale_rank_weight + 
tp_store_rank_weight/tn_store_rank_weight*li_store_rank_weight);
		if istest=1 THEN
			call ly_debug(concat(li_uid,':',comm_amount/100));
		ELSE
			call grant_comm_single(li_uid,comm_amount,1,'');
		end if;
	ELSE
		set done=0;
	end if;

	FETCH cur_list INTO li_uid,li_sale_amount_weight,li_sale_rank_weight,li_store_rank_weight;
END WHILE;
CLOSE cur_list;#关闭游标
#------------循环结束--------------

/*事务结束*/
IF t_error = 1 THEN
 	ROLLBACK;insert into error_log(content) values('发放月团队组织分红失败.');
ELSE
 	COMMIT;insert into logs_cron(content) values('[Success] 发放月团队组织分红.');
END IF;
SET autocommit=1;

END
;;
DELIMITER ;

-- ----------------------------
-- Procedure structure for grant_team_sale_comm
-- ----------------------------
DROP PROCEDURE IF EXISTS `grant_team_sale_comm`;
DELIMITER ;;
CREATE DEFINER=`tps138`@`%` PROCEDURE `grant_team_sale_comm`(
IN p_uid int(10),IN p_oid char(19),IN p_order_profit int(11),IN grant_parent_ids char(32))
    SQL SECURITY INVOKER
BEGIN
################发放团队销售提成######################
#参数说明
#p_order_profit 用于发放团队提成的订单利润（分）
#grant_parent_ids 要发放团队提成的3代父id

DECLARE cur_cash_tb_name varchar(30) default concat('cash_account_log_',DATE_FORMAT(now(),'%Y%m'));#资金变动表名
DECLARE i int(10);#用来控制循环遍历的次数
DECLARE u_proportion decimal(5,2);#用户的佣金自动转分红比例
DECLARE comm_to_point int;#佣金自动转分红点的金额
DECLARE done_parent_ids int(1);#控制父id循环的结束符
DECLARE p_id varchar(10);#父id
DECLARE p_user_rank,p_store_qualified tinyint(1);#父id的等级,是否合格
DECLARE comm_parent int default 0;#团队提成金额(分)
DECLARE p_qualified_child_num int;#父id直推的合格分店数
DECLARE p_enable_floor_num TINYINT(2);#父id可以拿到的层数


set i=0;set done_parent_ids=0;
WHILE (i<3 and done_parent_ids=0) do
	set p_id = substring(grant_parent_ids,i*11+1,10);
	if (p_id<>'1380100217' and p_id!='') then
		select user_rank,store_qualified into p_user_rank,p_store_qualified from users where id=p_id;
		if p_user_rank is not null THEN#parent_id在数据库中存在

			set comm_parent=0;#初始化团队提成金额
			if i=0 THEN#第一代直接上级店铺无条件发放团队提成

				#根据等级计算提成
				if p_user_rank=1 then set comm_parent=round(p_order_profit*0.2);
				elseif p_user_rank=2 then set comm_parent=round(p_order_profit*0.15);
				elseif p_user_rank=3 then set comm_parent=round(p_order_profit*0.1);
				elseif p_user_rank=5 then set comm_parent=round(p_order_profit*0.07);
				else set comm_parent=round(p_order_profit*0.05);
				end if;
			ELSE#非第一代，要判断是否有资格拿(自己是否合格，层数是否满足)
				
				if p_store_qualified=1 then#合格了才能继续

					#查询该parent_id开了几个合格分店,根据分店和等级计算可拿的层数
					select count(*) into p_qualified_child_num from users where parent_id=p_id and user_rank<>4 
and store_qualified=1;
					case p_user_rank
						when 1 then
							if p_qualified_child_num>=3 then set p_enable_floor_num=10;
							elseif p_qualified_child_num=2 then set p_enable_floor_num=6;
							elseif p_qualified_child_num=1 then set p_enable_floor_num=3;
							else set p_enable_floor_num=1;end if;
						when 2 THEN
							if p_qualified_child_num>=2 then set p_enable_floor_num=6;
							elseif p_qualified_child_num=1 then set p_enable_floor_num=3;
							else set p_enable_floor_num=1;end if;
						when 3 THEN
							if p_qualified_child_num>=1 then set p_enable_floor_num=3;
							else set p_enable_floor_num=1;end if;
						when 5 THEN
							if p_qualified_child_num>=1 then set p_enable_floor_num=2;
							else set p_enable_floor_num=1;end if;
						else set p_enable_floor_num=1;
					end case;
					if p_enable_floor_num>=i+1 then#层数合格时，拿奖
						
						#计算团队提成比例金额。
						case p_user_rank
							when 1 THEN
								if i=1 then set comm_parent=round(p_order_profit*0.1);
								elseif i=2 then set comm_parent=round(p_order_profit*0.05);
								else set comm_parent=round(p_order_profit*0.02);end if;
							when 2 THEN
								if i=1 then set comm_parent=round(p_order_profit*0.08);
								elseif i=2 then set comm_parent=round(p_order_profit*0.05);
								else set comm_parent=round(p_order_profit*0.02);end if;
							when 3 THEN
								if i=1 then set comm_parent=round(p_order_profit*0.05);
								else set comm_parent=round(p_order_profit*0.03);end if;
							else set comm_parent=round(p_order_profit*0.05);
						end case;	
					end if;
				end if;
			end if;

			#发放团队提成
			if comm_parent>0 then

				#生成资金变动报表记录
				set @STMT :=concat('insert into ',cur_cash_tb_name,'(uid,item_type,amount,order_id,related_uid) 
		values(',p_id,',3,',comm_parent,",'",p_oid,"','",p_uid,"')");
				PREPARE STMT FROM @STMT;
				EXECUTE STMT;

				#判断是否设置了自动转分红点比例
				set comm_to_point=0;
				set u_proportion=0.00;
				select proportion/100 into u_proportion from profit_sharing_point_proportion where uid=p_id and 
proportion_type=1;
				if u_proportion>0.00 THEN

					#有自动转分红比例，执行佣金自动转分红
					set comm_to_point = ROUND(comm_parent * u_proportion);
					if comm_to_point>0 then

						#更新用户表分红点
						update users set profit_sharing_point=profit_sharing_point+comm_to_point/100,
		profit_sharing_point_from_sale=profit_sharing_point_from_sale+comm_to_point/100 where id=p_id;

						#生成相应资金变动报表记录（佣金转分红点）
						set @STMT :=concat('insert into ',cur_cash_tb_name,'(uid,item_type,amount) 
		values(',p_id,',17,',-1*comm_to_point,')');
						PREPARE STMT FROM @STMT;
						EXECUTE STMT;

						#生成分红变动记录
						insert into profit_sharing_point_add_log(uid,add_source,money,point,create_time) values(p_id,1,
		comm_to_point/100,comm_to_point/100,unix_timestamp());
					end if;
				end if;
				
				#更新用户现金池，提成统计
				update users set amount=amount+(comm_parent-comm_to_point)/100,team_commission=
		team_commission+comm_parent/100 where id=p_id;
			end if;
		end if;
	ELSE
		set done_parent_ids=1;
	end if;
	set i=i+1;
END WHILE;

END
;;
DELIMITER ;

-- ----------------------------
-- Procedure structure for grant_week_share
-- ----------------------------
DROP PROCEDURE IF EXISTS `grant_week_share`;
DELIMITER ;;
CREATE DEFINER=`tps138`@`%` PROCEDURE `grant_week_share`(in istest tinyint(1))
BEGIN
################发放每周团队销售分红######################

DECLARE t_error INT DEFAULT 0;#定义事务相关参数
DECLARE totalProfit_wh BIGINT DEFAULT 0;#沃好利润，单位：分。
DECLARE totalProfit_tps BIGINT DEFAULT 0;#tps利润，单位：分。
DECLARE totalProfit_1di BIGINT DEFAULT 0;#1direct利润，单位：分。
DECLARE totalProfit BIGINT DEFAULT 0;#用于发奖的总利润，单位：分。
DECLARE tp_sale_amount_weight BIGINT DEFAULT 0;#用于销售额权重的利润，单位：分。
DECLARE tp_sale_rank_weight BIGINT DEFAULT 0;#用于职称权重的利润，单位：分。
DECLARE tp_store_rank_weight BIGINT DEFAULT 0;#用于店铺权重的利润，单位：分。
DECLARE tp_share_point_weight BIGINT DEFAULT 0;#用于分红点权重的利润，单位：分。
DECLARE tn_sale_amount_weight BIGINT DEFAULT 0;#销售额总权重。
DECLARE tn_sale_rank_weight BIGINT DEFAULT 0;#职称总权重。
DECLARE tn_store_rank_weight BIGINT DEFAULT 0;#店铺总权重。
DECLARE tn_share_point_weight BIGINT DEFAULT 0;#分红点总权重。
DECLARE comm_amount int DEFAULT 0;#需发放的奖金，单位：分。
DECLARE n_week_time varchar(30);
DECLARE s_week_time varchar(30);
DECLARE li_uid int;#拿奖人id
DECLARE li_sale_amount_weight int;#拿奖人销售额权重点
DECLARE li_sale_rank_weight tinyint;#拿奖人职称权重点
DECLARE li_store_rank_weight tinyint;#拿奖人店铺等级权重点
DECLARE li_share_point_weight int;#拿奖人分红点权重点
DECLARE done int default 0;#控制主循环的结束符
DECLARE cur_list CURSOR FOR select uid,sale_amount_weight,sale_rank_weight,store_rank_weight,share_point_weight from
week_share_qualified_list WHERE create_time < CURDATE();#取出发奖人员信息列表（游标）
DECLARE CONTINUE HANDLER FOR NOT FOUND set done=1;#定义循环hander
DECLARE CONTINUE HANDLER FOR SQLEXCEPTION SET t_error=1;#定义事务hander

#统计上一周的总利润额，每总权重的金额
set n_week_time = subdate(curdate(),date_format(curdate(),'%w')-1); /**本周*/
set s_week_time = subdate(date_add( n_week_time, interval -2 day) ,date_format( date_add(n_week_time, interval -2 day),'%w' )-1); /**上周*/


select round(if(sum(order_profit_usd) is not null,sum(order_profit_usd),0)*100) into totalProfit_wh from mall_orders 
where create_time>=s_week_time and create_time<n_week_time;

select round(if(sum(order_profit_usd) is not null,sum(order_profit_usd),0)*100) into totalProfit_1di from 
one_direct_orders where create_time>=s_week_time and create_time<n_week_time;

select if(sum(order_profit_usd) is not null,sum(order_profit_usd),0) into totalProfit_tps from trade_orders 
where pay_time>=s_week_time and pay_time<n_week_time and order_prop in('0','2') and `status` in('3','4','5','6');


set totalProfit = round((totalProfit_wh+totalProfit_1di+totalProfit_tps)*0.19191);
set tp_sale_amount_weight = round(totalProfit*0.1);
set tp_sale_rank_weight = round(totalProfit*0.78);
set tp_store_rank_weight = round(totalProfit*0.1);
set tp_share_point_weight = round(totalProfit*0.02);

#统计每种权重下总权重点
select sum(sale_amount_weight) into tn_sale_amount_weight from week_share_qualified_list;
select sum(sale_rank_weight) into tn_sale_rank_weight from week_share_qualified_list;
select sum(store_rank_weight) into tn_store_rank_weight from week_share_qualified_list;
select sum(share_point_weight) into tn_share_point_weight from week_share_qualified_list;

set autocommit=0;#定义不自动提交，为事务开启
set done=0;
OPEN cur_list;
FETCH cur_list INTO li_uid,li_sale_amount_weight,li_sale_rank_weight,li_store_rank_weight,li_share_point_weight;
WHILE done=0 do

	select id into li_uid from users where id=li_uid and store_qualified=1;
	if done=0 THEN
		set comm_amount = round(tp_sale_amount_weight/tn_sale_amount_weight*li_sale_amount_weight + 
tp_sale_rank_weight/tn_sale_rank_weight*li_sale_rank_weight + 
tp_store_rank_weight/tn_store_rank_weight*li_store_rank_weight +
tp_share_point_weight/tn_share_point_weight*li_share_point_weight);
		if istest=1 THEN
			call ly_debug(concat(li_uid,':',comm_amount/100));
		ELSE
			call grant_comm_single(li_uid,comm_amount,25,'');
		end if;
	ELSE
		set done=0;
	end if;

	FETCH cur_list INTO li_uid,li_sale_amount_weight,li_sale_rank_weight,li_store_rank_weight,li_share_point_weight;
END WHILE;
CLOSE cur_list;#关闭游标
#------------循环结束--------------

/*事务结束*/
IF t_error = 1 THEN
 	ROLLBACK;insert into error_log(content) values('发放周团队销售分红失败.');
ELSE
 	COMMIT;insert into logs_cron(content) values('[Success] 发放周团队销售分红.');
END IF;
SET autocommit=1;

END
;;
DELIMITER ;

-- ----------------------------
-- Procedure structure for ly_debug
-- ----------------------------
DROP PROCEDURE IF EXISTS `ly_debug`;
DELIMITER ;;
CREATE DEFINER=`tps138`@`%` PROCEDURE `ly_debug`(
IN de_content text)
    SQL SECURITY INVOKER
BEGIN
################调试用的存储过程######################

insert into debug_logs(content) values(de_content);

END
;;
DELIMITER ;

-- ----------------------------
-- Procedure structure for month_begin
-- ----------------------------
DROP PROCEDURE IF EXISTS `month_begin`;
DELIMITER ;;
CREATE DEFINER=`tps138`@`%` PROCEDURE `month_begin`()
BEGIN
################每月初执行的任务######################

call stat_user_point_monthly();#统计用户分红点
call new_cash_log_tb();#创建资金log分表

END
;;
DELIMITER ;

-- ----------------------------
-- Procedure structure for new_138_bonus_list
-- ----------------------------
DROP PROCEDURE IF EXISTS `new_138_bonus_list`;
DELIMITER ;;
CREATE DEFINER=`tps138`@`%` PROCEDURE `new_138_bonus_list`(
)
    SQL SECURITY INVOKER
BEGIN
#[每月初生成新的138发奖列表]

/*事务开始*/
DECLARE t_error INT DEFAULT 0;
DECLARE CONTINUE HANDLER FOR SQLEXCEPTION SET t_error=1;
set autocommit=0;

#清空上个月拿奖的人员
DELETE from user_qualified_for_138 where create_time<DATE_FORMAT(now(),'%Y-%m-%d');

#筛选出本月拿奖人员并插入发奖列表（上月订单合格的会员）
insert ignore into user_qualified_for_138(user_id,user_rank,sale_amount) select a.uid,b.user_rank,a.sale_amount from 
users_store_sale_info_monthly a left join users b on a.uid=b.id where a.`year_month`=
DATE_FORMAT(date_add(curdate(),interval -1 month),'%Y%m') and a.sale_amount>=5000 and b.user_rank in(1,2,3,5);

#更新合格表中的x,y
update user_qualified_for_138 a,user_coordinates b set a.x=b.x,a.y=b.y where a.user_id=b.user_id and a.x=0 and a.y=0;

/*事务结束*/
IF t_error = 1 THEN
	ROLLBACK;insert into logs_cron(content) values('[Fail] 每月初生成新的138发奖列表.');
ELSE
	COMMIT;insert into logs_cron(content) values('[Success] 每月初生成新的138发奖列表.');
END IF;
SET autocommit=1;

END
;;
DELIMITER ;

-- ----------------------------
-- Procedure structure for new_cash_log_tb
-- ----------------------------
DROP PROCEDURE IF EXISTS `new_cash_log_tb`;
DELIMITER ;;
CREATE DEFINER=`tps138`@`%` PROCEDURE `new_cash_log_tb`(
)
    SQL SECURITY INVOKER
BEGIN
################每月初自动创建下个月的佣金记录分表######################

DECLARE new_tb_name VARCHAR(40);

set new_tb_name = CONCAT('cash_account_log_',DATE_FORMAT(date_add(now(),interval 1 month),'%Y%m'));
set @STMT :=concat('CREATE TABLE ',new_tb_name," (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL,
  `item_type` tinyint(1) unsigned NOT NULL COMMENT '资金变动项类型。',
  `amount` int(11) NOT NULL,
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `order_id` varchar(25) NOT NULL DEFAULT '0' COMMENT '订单号',
  `related_uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '相关uid',
  `related_id` varchar(35) NOT NULL DEFAULT '0' COMMENT '关联id',
  `remark` text,
  PRIMARY KEY (`id`),
  KEY `create_time` (`create_time`),
  KEY `uid` (`uid`) USING HASH,
  KEY `item_type` (`item_type`) USING HASH,
  KEY `order_id` (`order_id`) USING HASH,
  KEY `related_uid` (`related_uid`) USING HASH
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
PREPARE STMT FROM @STMT;
EXECUTE STMT;

END
;;
DELIMITER ;

-- ----------------------------
-- Procedure structure for new_daily_bonus_elite_list
-- ----------------------------
DROP PROCEDURE IF EXISTS `new_daily_bonus_elite_list`;
DELIMITER ;;
CREATE DEFINER=`tps138`@`%` PROCEDURE `new_daily_bonus_elite_list`(
)
    SQL SECURITY INVOKER
BEGIN
################每月初生成新的精英日分红发奖列表######################

/*事务开始*/
DECLARE t_error INT DEFAULT 0;
DECLARE CONTINUE HANDLER FOR SQLEXCEPTION SET t_error=1;
set autocommit=0;

#清空上个月拿奖的人员
delete from daily_bonus_elite_qualified_list where qualified_day<DATE_FORMAT(now(),'%Y%m%d');

#-----------筛选出本上月推荐人合格的会员并插入发奖列表,同时生成相应的销售额（套餐+零售）
#筛选人，然后权重字段写入相应套餐销售额
insert ignore into daily_bonus_elite_qualified_list(uid,bonus_shar_weight) select uid,pro_set_amount 
from stat_intr_mem_month where `year_month`=DATE_FORMAT(date_add(curdate(),interval -1 month),'%Y%m') 
and pro_set_amount>0 and uid<>1380100217;
#把普通订单销售额更新加入到权重字段
update daily_bonus_elite_qualified_list a,users_store_sale_info_monthly b set a.bonus_shar_weight=a.bonus_shar_weight
+b.sale_amount where b.`year_month`=DATE_FORMAT(date_add(curdate(),interval -1 month),'%Y%m') and a.uid=b.uid 
and a.qualified_day=0;

#------------筛选出上月零售订单合格的会员并插入发奖列表，同时生成相应的销售额（只有零售）
insert ignore into daily_bonus_elite_qualified_list(uid,bonus_shar_weight) select uid,sale_amount 
from users_store_sale_info_monthly where `year_month`=DATE_FORMAT(date_add(curdate(),interval -1 month),'%Y%m') 
and sale_amount>=25000;

/*事务结束*/
IF t_error = 1 THEN
	ROLLBACK;insert into error_log(content) values('每月初生成新的精英日分红发奖列表失败.');
ELSE
	COMMIT;insert into logs_cron(content) values('[Success] 每月初生成新的精英日分红发奖列表.');
END IF;
SET autocommit=1;

END
;;
DELIMITER ;

-- ----------------------------
-- Procedure structure for new_daily_bonus_list
-- ----------------------------
DROP PROCEDURE IF EXISTS `new_daily_bonus_list`;
DELIMITER ;;
CREATE DEFINER=`tps138`@`%` PROCEDURE `new_daily_bonus_list`(
)
    SQL SECURITY INVOKER
BEGIN
#[每月初生成新的日分红发奖列表]

/*事务开始*/
DECLARE t_error INT DEFAULT 0;
DECLARE CONTINUE HANDLER FOR SQLEXCEPTION SET t_error=1;
set autocommit=0;

#清空上个月拿奖的人员
DELETE from daily_bonus_qualified_list where qualified_day<DATE_FORMAT(now(),'%Y%m%d');

#筛选出本月拿奖人员并插入发奖列表（上月订单合格的会员）
insert ignore into daily_bonus_qualified_list(uid) select a.uid from users_store_sale_info_monthly a left join users b 
on a.uid=b.id where a.`year_month`=DATE_FORMAT(date_add(curdate(),interval -1 month),'%Y%m') and a.sale_amount>=2500 
and (a.sale_amount>=10000 or b.user_rank<>4);
#筛选出本月拿奖人员并插入发奖列表（上月推荐人合格的会员）
insert ignore into daily_bonus_qualified_list(uid) select uid from stat_intr_mem_month where `year_month`=
DATE_FORMAT(date_add(curdate(),interval -1 month),'%Y%m') 
and (member_bronze_num>0 or member_silver_num>0 or member_platinum_num>0 or member_diamond_num>0) and uid<>1380100217;

/*事务结束*/
IF t_error = 1 THEN
	ROLLBACK;insert into logs_cron(content) values('[Fail] 每月初生成新的日分红发奖列表.');
ELSE
	COMMIT;insert into logs_cron(content) values('[Success] 每月初生成新的日分红发奖列表.');
END IF;
SET autocommit=1;

END
;;
DELIMITER ;

-- ----------------------------
-- Procedure structure for new_month_group_share_list
-- ----------------------------
DROP PROCEDURE IF EXISTS `new_month_group_share_list`;
DELIMITER ;;
CREATE DEFINER=`tps138`@`%` PROCEDURE `new_month_group_share_list`(
)
    SQL SECURITY INVOKER
BEGIN
################每月初生成新的月团队组织分红发奖列表######################

/*事务开始*/
DECLARE t_error INT DEFAULT 0;
DECLARE CONTINUE HANDLER FOR SQLEXCEPTION SET t_error=1;
set autocommit=0;

#清空上个月拿奖的人员
truncate month_group_share_list;

insert ignore into month_group_share_list(uid,sale_amount_weight,sale_rank_weight,store_rank_weight) select a.uid,
a.sale_amount,(b.sale_rank+1)*(b.sale_rank+1),if(b.user_rank=5,1,if(b.user_rank=3,2,if(b.user_rank=2,3,4) ) ) from 
users_store_sale_info_monthly a left join users b 
on a.uid=b.id where a.`year_month`=DATE_FORMAT(date_add(curdate(),interval -1 month),'%Y%m') and b.user_rank in(5,3,2,1)
 and a.sale_amount>=7500;

/*事务结束*/
IF t_error = 1 THEN
	ROLLBACK;insert into error_log(content) values('每月初生成新的月团队组织分红发奖列表失败.');
ELSE
	COMMIT;insert into logs_cron(content) values('[Success] 每月初生成新的月团队组织分红发奖列表.');
END IF;
SET autocommit=1;

END
;;
DELIMITER ;

-- ----------------------------
-- Procedure structure for new_order_trigger
-- ----------------------------
DROP PROCEDURE IF EXISTS `new_order_trigger`;
DELIMITER ;;
CREATE DEFINER=`tps138`@`%` PROCEDURE `new_order_trigger`()
BEGIN
DECLARE now_time VARCHAR (20) ;
DECLARE end_time VARCHAR (20) DEFAULT '2017-04-01 00:00:00';#4.1个人日分红取消
DECLARE team_profit_end_time VARCHAR (20) DEFAULT '2017-03-01 00:00:00';#团队分红改变
DECLARE t_run_cycle_num smallint(5) default -1;
DECLARE order_ids_del text;
DECLARE f_oid VARCHAR(40);
DECLARE f_uid int(10);
DECLARE f_order_amount_usd int(10);
DECLARE f_order_profit_usd int(10);
DECLARE f_order_year_month mediumint(6);
DECLARE u_status TINYINT(1) default 1;#用户账户状态（是否休眠）
DECLARE u_monthfee_pool decimal(14,2) default 0.00;#用户月费池金额
DECLARE u_store_qualified TINYINT(1);#用户是否合格
DECLARE u_user_rank TINYINT(3);#用户等级
DECLARE u_sale_rank TINYINT(3);#用户职称
DECLARE cur_sale_amount int;#用户当前月的销售额
DECLARE total_sale_amount int(10);#用户总销售额
DECLARE comm_store_owner int;#订单的店主提成
DECLARE cur_year_month MEDIUMINT(6) default DATE_FORMAT(now(),'%Y%m');#当前的年月，如201609
DECLARE cur_cash_tb_name varchar(30) default concat('cash_account_log_',DATE_FORMAT(now(),'%Y%m'));#资金变动表名
DECLARE u_proportion decimal(5,2);#用户的佣金自动转分红比例
DECLARE comm_to_point int;#个人店铺提成中自动转分红点的金额
DECLARE u_parent_ids longtext;#用户的父id集合
DECLARE i int(10);#用来控制循环遍历的次数
DECLARE done_parent_ids int(1);#控制父id循环的结束符
DECLARE p_id varchar(10);#父id
DECLARE p_user_rank,p_store_qualified tinyint(1);#父id的等级,是否合格
DECLARE comm_parent int;#团队提成金额
DECLARE p_qualified_child_num int;#父id直推的合格分店数
DECLARE p_enable_floor_num TINYINT(2);#父id可以拿到的层数
DECLARE u_reward_id int(10);#奖励分红点id
DECLARE u_amount_profit_sharing_comm decimal(14,2);#用户拿的日分红统计额
DECLARE h_daily_bonus_elite int;#用户历史拿过的精英日分红金额（单位：分）
DECLARE h_138_bonus int;#用户历史拿过的138分红金额（单位：分）
DECLARE h_week_bonus int;#用户历史拿过的周领导对等奖金额（单位：分）
DECLARE u_x int;#用户的x坐标
DECLARE u_y int;#用户的y坐标
DECLARE group_floor_num TINYINT DEFAULT 3;#团队提成的层数

DECLARE t_error INT DEFAULT 0;#定义事务参数
DECLARE done int default 0;#控制主循环的结束符
DECLARE cur_queue CURSOR FOR SELECT oid,uid,order_amount_usd,order_profit_usd,order_year_month 
FROM new_order_trigger_queue where create_time<SUBDATE(now(),interval 180 second) LIMIT 500;
DECLARE CONTINUE HANDLER FOR NOT FOUND set done=1;#定义循环handel
DECLARE CONTINUE HANDLER FOR SQLEXCEPTION SET t_error=1;#定义事务handel

##单任务控制--检查本任务是否已经有在执行
select run_cycle_num into t_run_cycle_num from single_task_control where task_name='new_order_trigger';
if t_run_cycle_num=-1 THEN

	##单任务控制--没有正在执行的任务则插入一条任务记录
	insert into single_task_control(task_name) values('new_order_trigger');

	##-----begin业务逻辑
	SET now_time = UNIX_TIMESTAMP(NOW()) ;
	set end_time = UNIX_TIMESTAMP(end_time);
	set team_profit_end_time = UNIX_TIMESTAMP(team_profit_end_time);
	##----- 团队销售提成 3.1日后改为两层
	if now_time > end_time THEN
	  SET group_floor_num = 2;
	ELSE
	  SET  group_floor_num = 3;
  end if;

	set done=0;
	set autocommit=0;#定义不自动提交，为事务开启
	OPEN cur_queue;
	FETCH cur_queue INTO f_oid,f_uid,f_order_amount_usd,f_order_profit_usd,f_order_year_month;

	WHILE done<>1 do
		#----------更新用户店铺总业绩
		insert into users_store_sale_info(uid,sale_amount) values(f_uid,f_order_amount_usd) on DUPLICATE KEY 
	UPDATE sale_amount=sale_amount+f_order_amount_usd;
		
		#----------判断用户是否正在补单中，根据结果更新用户店铺月业绩
		SELECT order_year_month into f_order_year_month from withdraw_task where uid=f_uid and `status`=1 limit 1;
		if done=1 then#没有找到补单的记录
			set done=0;
		else#正在补单中，则执行补单操作
			delete from withdraw_task where uid=f_uid and `status`=1 and sale_amount_lack<=f_order_amount_usd;
			update withdraw_task set sale_amount_lack=sale_amount_lack-f_order_amount_usd where uid=f_uid and `status`=1;

			#根据订单号更新相应订单表中的业绩年月
			case substring(f_oid,1,2)
				when 'W-' THEN
					update mall_orders set score_year_month=f_order_year_month where order_id=f_oid;
				when 'O-' THEN
					update one_direct_orders set score_year_month=f_order_year_month where order_id=f_oid;
				when 'A-' THEN
					update walmart_orders set score_year_month=f_order_year_month where order_id=f_oid;
				else
					update trade_orders set score_year_month=f_order_year_month where order_id=f_oid;
			end case;
			
		end if;
		#更新月统计业绩
		insert into users_store_sale_info_monthly(uid,`year_month`,sale_amount) values(f_uid,f_order_year_month,
	f_order_amount_usd) on DUPLICATE KEY UPDATE sale_amount=sale_amount+f_order_amount_usd;
		call user_rank_change_week_comm(f_uid,0,0,2);

		#---------获取用户当前月销售额
		set cur_sale_amount=0;#初始化当前月销售额
		SELECT sale_amount into cur_sale_amount from users_store_sale_info_monthly where uid=f_uid and `year_month`=
	cur_year_month;
		if done=1 then set done=0;end if;

		#---------获取用户总销售额
		set total_sale_amount=0;#初始化当前月销售额
		SELECT sale_amount into total_sale_amount from users_store_sale_info where uid=f_uid;
		if done=1 then set done=0;end if;

		#---------获取用户相关信息
		select user_rank,sale_rank,`status`,month_fee_pool,left(parent_ids,109),store_qualified,amount_profit_sharing_comm
	into u_user_rank,u_sale_rank,u_status,u_monthfee_pool,u_parent_ids,u_store_qualified,u_amount_profit_sharing_comm
	from users where id=f_uid;

		#---------处理订单抵月费活动
		if u_status=2 THEN
			select id from users_april_plan where uid=f_uid and `type`=1;
			if done=1 then
				set done=0;
			else
				#用户休眠且参加了活动，开始订单抵月费处理
				insert into users_april_plan_order(uid,order_id) values(f_uid,f_oid);#记录活动订单信息
				insert into trade_order_remark_record(order_id,`type`,remark,admin_id) values(f_oid,1,'System: The retail orders
	for "the Initiatives to waive past due monthly fee(s)" can\'t be cancelled or returned',0);#记录订单备注1
				insert into trade_order_remark_record(order_id,`type`,remark,admin_id) values(f_oid,2,'System: The retail orders
	for "the Initiatives to waive past due monthly fee(s)" can\'t be cancelled or returned',0);#记录订单备注2
				if cur_sale_amount>=5000 then 
					#满了50美金，抵扣月费
					update users set `status`=1,store_qualified=1 where id=f_uid;#恢复欠月费状态为正常
					delete from users_month_fee_fail_info where uid=f_uid;#清楚扣月份失败记录
					insert into users_status_log(uid,front_status,back_status,`type`,create_time)
	values(f_uid,2,1,2,unix_timestamp());#记录用户状态变更
					insert into month_fee_change(user_id,old_month_fee_pool,month_fee_pool,cash,create_time,`type`) values(f_uid,
	u_monthfee_pool,u_monthfee_pool,0.00,now(),8);#生成月费池变动记录
					delete from users_april_plan where uid=f_uid;#从参加月费活动表中清除
				end if;
			end if;
		end if;

		#---------发放订单对应的个人店铺销售提成（店主提成）
		set comm_store_owner =ROUND(f_order_profit_usd*0.2);
		if comm_store_owner>0 then

			#生成资金变动报表记录（个人店铺提成）
			set @STMT :=concat('insert into ',cur_cash_tb_name,'(uid,item_type,amount,order_id)
	values(',f_uid,',5,',comm_store_owner,",'",f_oid,"')");
			PREPARE STMT FROM @STMT;
			EXECUTE STMT;

			#判断是否设置了自动转分红点比例
			set comm_to_point=0;
			set u_proportion=0.00;
			select proportion/100 into u_proportion from profit_sharing_point_proportion where uid=f_uid and proportion_type=1;
			if u_proportion>0.00 THEN

				#有自动转分红比例，执行佣金自动转分红
				set comm_to_point = ROUND(comm_store_owner * u_proportion);
				if comm_to_point>0 then

					#更新用户表分红点
					update users set profit_sharing_point=profit_sharing_point+comm_to_point/100,
	profit_sharing_point_from_sale=profit_sharing_point_from_sale+comm_to_point/100 where id=f_uid;

					#生成相应资金变动报表记录（佣金转分红点）
					set @STMT :=concat('insert into ',cur_cash_tb_name,'(uid,item_type,amount)
	values(',f_uid,',17,',-1*comm_to_point,')');
					PREPARE STMT FROM @STMT;
					EXECUTE STMT;

					#生成分红变动记录
					insert into profit_sharing_point_add_log(uid,add_source,money,point,create_time) values(f_uid,1,
	comm_to_point/100,comm_to_point/100,unix_timestamp());
				end if;
			else
				set done=0;
			end if;

			#更新用户现金池，个人店铺销售提成统计
			update users set amount=amount+(comm_store_owner-comm_to_point)/100,amount_store_commission=
	amount_store_commission+comm_store_owner/100 where id=f_uid;
		end if;

		#---------发放团队销售提成
		set i=0;set done_parent_ids=0;
		WHILE (i<group_floor_num and done_parent_ids=0) do
			set p_id = substring(u_parent_ids,i*11+1,10);
			if (p_id<>'1380100217' and p_id!='') then
				select user_rank,store_qualified into p_user_rank,p_store_qualified from users where id=p_id;
				if done=0 THEN#parent_id在数据库中存在

					set comm_parent=0;#初始化团队提成金额

					  #m by brady.wang 截止2017.03.01 新的团队分红方式
            #免费店铺：第一级店铺销售利润提成5%；
            #铜级店铺：第一级店铺销售利润提成10%，第二级店铺销售利润提成5%；
            #银级店铺：第一级店铺销售利润提成12%，第二级店铺销售利润提成7%；
            #白金店铺：第一级店铺销售利润提成15%，第二级店铺销售利润提成10%；
            #钻石店铺：第一级店铺销售利润提成20%，第二级店铺销售利润提成12%.

            IF now_time <= team_profit_end_time THEN
              if i=0 THEN#第一代直接上级店铺无条件发放团队提成

              #根据等级计算提成
              if p_user_rank=1 then set comm_parent=round(f_order_profit_usd*0.2);
              elseif p_user_rank=2 then set comm_parent=round(f_order_profit_usd*0.15);
              elseif p_user_rank=3 then set comm_parent=round(f_order_profit_usd*0.1);
              elseif p_user_rank=5 then set comm_parent=round(f_order_profit_usd*0.07);
              else set comm_parent=round(f_order_profit_usd*0.05);
              end if;
            ELSE#非第一代，要判断是否有资格拿(自己是否合格，层数是否满足)

              if p_store_qualified=1 then#合格了才能继续

                #查询该parent_id开了几个合格分店,根据分店和等级计算可拿的层数
                select count(*) into p_qualified_child_num from users where parent_id=p_id and user_rank<>4
      and store_qualified=1;
                case p_user_rank
                  when 1 then
                    if p_qualified_child_num>=3 then set p_enable_floor_num=10;
                    elseif p_qualified_child_num=2 then set p_enable_floor_num=6;
                    elseif p_qualified_child_num=1 then set p_enable_floor_num=3;
                    else set p_enable_floor_num=1;end if;
                  when 2 THEN
                    if p_qualified_child_num>=2 then set p_enable_floor_num=6;
                    elseif p_qualified_child_num=1 then set p_enable_floor_num=3;
                    else set p_enable_floor_num=1;end if;
                  when 3 THEN
                    if p_qualified_child_num>=1 then set p_enable_floor_num=3;
                    else set p_enable_floor_num=1;end if;
                  when 5 THEN
                    if p_qualified_child_num>=1 then set p_enable_floor_num=2;
                    else set p_enable_floor_num=1;end if;
                  else set p_enable_floor_num=1;
                end case;
                if p_enable_floor_num>=i+1 then#层数合格时，拿奖

                  #计算团队提成比例金额。
                  case p_user_rank
                    when 1 THEN
                      if i=1 then set comm_parent=round(f_order_profit_usd*0.1);
                      elseif i=2 then set comm_parent=round(f_order_profit_usd*0.05);
                      else set comm_parent=round(f_order_profit_usd*0.02);end if;
                    when 2 THEN
                      if i=1 then set comm_parent=round(f_order_profit_usd*0.08);
                      elseif i=2 then set comm_parent=round(f_order_profit_usd*0.05);
                      else set comm_parent=round(f_order_profit_usd*0.02);end if;
                    when 3 THEN
                      if i=1 then set comm_parent=round(f_order_profit_usd*0.05);
                      else set comm_parent=round(f_order_profit_usd*0.03);end if;
                    else set comm_parent=round(f_order_profit_usd*0.05);
                  end case;
                end if;
              end if;
            end if;
            ELSE #新团队方式
              if i=0 THEN#第一代直接上级店铺无条件发放团队提成
                if p_user_rank=1 then set comm_parent=round(f_order_profit_usd*0.2);
                elseif p_user_rank=2 then set comm_parent=round(f_order_profit_usd*0.15);
                elseif p_user_rank=3 then set comm_parent=round(f_order_profit_usd*0.12);
                elseif p_user_rank=5 then set comm_parent=round(f_order_profit_usd*0.1);
                else set comm_parent=round(f_order_profit_usd*0.05);
                end if;
              ELSE
                if p_user_rank=1 then set comm_parent=round(f_order_profit_usd*0.12);
                elseif p_user_rank=2 then set comm_parent=round(f_order_profit_usd*0.10);
                elseif p_user_rank=3 then set comm_parent=round(f_order_profit_usd*0.07);
                elseif p_user_rank=5 then set comm_parent=round(f_order_profit_usd*0.05);
                else set comm_parent=round(f_order_profit_usd*0.05);
                end if;
              END IF;
              #如果是新的 只发放两级
              if i = 1 THEN

                set  done_parent_ids = 1;
              end if;
            END IF;

					#发放团队提成
					if comm_parent>0 then

						#生成资金变动报表记录
						set @STMT :=concat('insert into ',cur_cash_tb_name,'(uid,item_type,amount,order_id,related_uid)
				values(',p_id,',3,',comm_parent,",'",f_oid,"','",f_uid,"')");
						PREPARE STMT FROM @STMT;
						EXECUTE STMT;

						#判断是否设置了自动转分红点比例
						set comm_to_point=0;
						set u_proportion=0.00;
						select proportion/100 into u_proportion from profit_sharing_point_proportion where uid=p_id and
	proportion_type=1;
						if u_proportion>0.00 THEN

							#有自动转分红比例，执行佣金自动转分红
							set comm_to_point = ROUND(comm_parent * u_proportion);
							if comm_to_point>0 then

								#更新用户表分红点
								update users set profit_sharing_point=profit_sharing_point+comm_to_point/100,
				profit_sharing_point_from_sale=profit_sharing_point_from_sale+comm_to_point/100 where id=p_id;

								#生成相应资金变动报表记录（佣金转分红点）
								set @STMT :=concat('insert into ',cur_cash_tb_name,'(uid,item_type,amount)
				values(',p_id,',17,',-1*comm_to_point,')');
								PREPARE STMT FROM @STMT;
								EXECUTE STMT;

								#生成分红变动记录
								insert into profit_sharing_point_add_log(uid,add_source,money,point,create_time) values(p_id,1,
				comm_to_point/100,comm_to_point/100,unix_timestamp());
							end if;
						else
							set done=0;
						end if;

						#更新用户现金池，提成统计
						update users set amount=amount+(comm_parent-comm_to_point)/100,team_commission=
				team_commission+comm_parent/100 where id=p_id;
					end if;
				ELSE#parent_id在数据库中不存在(find不到时done会被置为1)
					set done=0;
				end if;
			ELSE
				set done_parent_ids=1;
			end if;
			set i=i+1;
		END WHILE;

		#---------免费店铺满50美金合格，满100美金送100分红点。
		if u_user_rank=4 and total_sale_amount>=5000 THEN
			if u_store_qualified=0 THEN
				update users set store_qualified=1 where id=f_uid;
			end if;
			if total_sale_amount>=10000 THEN
				select id into u_reward_id from users_sharing_point_reward where uid=f_uid limit 1;
				if done=1 then
					set done=0;
					insert into users_sharing_point_reward(uid,point,end_time) values(f_uid,100,
	DATE_ADD(DATE_FORMAT(now(),'%Y-%m-%d'),INTERVAL 15 MONTH));
				end if;
			end if;
		end if;

		#----------日分红第一次满足，加入发奖列表
		#------------ m by brady.wang 取消全球日分红满足立马就加入 START


    if now_time < end_time THEN

        if u_amount_profit_sharing_comm=0 and total_sale_amount>=2500 then
          if u_user_rank<>4 or total_sale_amount>=10000 THEN

            INSERT IGNORE INTO daily_bonus_qualified_list(uid,qualified_day) VALUES (f_uid,DATE_FORMAT(now(),'%Y%m%d'));
          end if;
        end if;
    end if;
    #------------ m by brady.wang 取消全球日分红满足立马就加入 END
		#查询出用户历史总共拿过的精英分红、138、周奖
		set h_daily_bonus_elite=0;
		set h_138_bonus=0;
		set h_week_bonus=0;
		SELECT daily_bonus_elite,138_bonus,week_bonus into h_daily_bonus_elite,h_138_bonus,h_week_bonus from user_comm_stat
	where uid=f_uid;
		if done=1 then set done=0;end if;

		#----------第一次满足精英日分红，加入发奖列表
		#------------ m by brady.wang 取消精英日分红满足立马就加入 START
		if now_time < end_time THEN
      if cur_sale_amount>=25000 and h_daily_bonus_elite=0 THEN
        call add_to_daily_elite_qualified_list(f_uid);
      end if;
    end if;
    #------------ m by brady.wang 取消精英日分红满足立马就加入 END
		#----------第一次满足138分红，加入发奖列表
		if now_time < end_time THEN

      if h_138_bonus=0 and cur_sale_amount>=5000 and u_user_rank in(1,2,3,5) THEN
        set u_x=0;set u_y=0;
        select x,y into u_x,u_y from user_coordinates where user_id=f_uid;
        if done=1 then set done=0;end if;
        if u_x>0 and u_y>0 THEN
          insert IGNORE into user_qualified_for_138(user_id,user_rank,sale_amount,x,y) values(f_uid,u_user_rank,cur_sale_amount,u_x,u_y);
        end if;
      end if;
    end if;
		#----------收集处理完的order id,以便统一从队列删除
		if(order_ids_del is null) then
			set order_ids_del=concat('\'',f_oid,'\'');
		else
			set order_ids_del = concat(order_ids_del,',','\'',f_oid,'\'');
		end if;

		FETCH cur_queue INTO f_oid,f_uid,f_order_amount_usd,f_order_profit_usd,f_order_year_month;
	END WHILE;
	CLOSE cur_queue;

	#从列队表中删除已处理的订单记录
	if order_ids_del is not null then
		set @STMT :=concat('delete from new_order_trigger_queue where oid in(',order_ids_del,')');
		PREPARE STMT FROM @STMT;
		EXECUTE STMT;
	end if;

	/*事务结束*/
	IF t_error = 1 THEN
		ROLLBACK;
	ELSE
		COMMIT;
	END IF;
	SET autocommit=1;
	##-------end业务逻辑

	##单任务控制--任务结束后，删除任务记录
	delete from single_task_control where task_name='new_order_trigger';

ELSE

	#单任务控制--有正在执行的任务
	if t_run_cycle_num<30 then
		update single_task_control set run_cycle_num=run_cycle_num+1 where task_name='new_order_trigger';
	ELSE
		delete from single_task_control where task_name='new_order_trigger';
	end if;
end if;

END
;;
DELIMITER ;

-- ----------------------------
-- Procedure structure for new_week_share_list
-- ----------------------------
DROP PROCEDURE IF EXISTS `new_week_share_list`;
DELIMITER ;;
CREATE DEFINER=`tps138`@`%` PROCEDURE `new_week_share_list`(
)
    SQL SECURITY INVOKER
BEGIN
################每月初生成新的周团队分红发奖列表######################

/*事务开始*/
DECLARE t_error INT DEFAULT 0;
DECLARE CONTINUE HANDLER FOR SQLEXCEPTION SET t_error=1;
set autocommit=0;

#清空上个月拿奖的人员
truncate week_share_qualified_list;

insert ignore into week_share_qualified_list(uid,sale_amount_weight,sale_rank_weight,store_rank_weight,share_point_weight
) select a.uid,a.sale_amount,b.sale_rank*b.sale_rank,if(b.user_rank=3,1,if(b.user_rank=1,3,2)),round(b.profit_sharing_point*100) from users_store_sale_info_monthly a left join users b 
on a.uid=b.id where a.`year_month`=DATE_FORMAT(date_add(curdate(),interval -1 month),'%Y%m') and b.user_rank in(3,2,1) 
and b.sale_rank in(1,2,3,4,5) and a.sale_amount>=7500;

/*事务结束*/
IF t_error = 1 THEN
	ROLLBACK;insert into error_log(content) values('每月初生成新的周团队分红发奖列表失败.');
ELSE
	COMMIT;insert into logs_cron(content) values('[Success] 每月初生成新的周团队分红发奖列表.');
END IF;
SET autocommit=1;

END
;;
DELIMITER ;

-- ----------------------------
-- Procedure structure for nextSeqValue
-- ----------------------------
DROP PROCEDURE IF EXISTS `nextSeqValue`;
DELIMITER ;;
CREATE DEFINER=`rcfuxcca0`@`%` PROCEDURE `nextSeqValue`(
    IN  seqName VARCHAR(128),
    IN  size    INT UNSIGNED,
    IN  maxVal  BIGINT UNSIGNED,
    IN  cycle   TINYINT UNSIGNED,
    OUT newVal  BIGINT UNSIGNED)
    MODIFIES SQL DATA
    DETERMINISTIC
BEGIN
    DECLARE mysqlError  INT     DEFAULT 0;
    DECLARE mySqlState  INT     DEFAULT 0;
    DECLARE errorMsg    TEXT    DEFAULT 'N/A';

    DECLARE hasError    TINYINT DEFAULT 0;
    DECLARE rowsChanged TINYINT DEFAULT 0;
    DECLARE retry       TINYINT DEFAULT 1;

    DECLARE out_of_range CONDITION FOR 1690;
    DECLARE CONTINUE HANDLER FOR out_of_range
    BEGIN
        SET mysqlError = 1690;
        SET mySqlState = 22003;
        SET errorMsg   = 'Out of range: Attempting to update a value that exceeds maximum value allowed.';

        SET hasError = 1;
        IF cycle = 0 THEN
            SET retry = 0;
        END IF;
    END;

    DECLARE CONTINUE HANDLER FOR SQLEXCEPTION
    BEGIN
        SET mysqlError = -1;
        SET mySqlState = -1;
        SET errorMsg   = 'Hit some error when executing SQL statement.';

        SET hasError = 1;
        SET retry = 0;
    END;

    IF size <= 0 THEN
        SET size = 1;
    END IF;

    START TRANSACTION;

    UPDATE sequence_opt
    SET value = LAST_INSERT_ID(value + increment_by * (size - 1)) + increment_by, gmt_modified = NOW()
    WHERE name = seqName;

    error_handler: LOOP

        IF hasError = 0 THEN
            SELECT ROW_COUNT() INTO rowsChanged;

            IF rowsChanged = 0 THEN
                SET mysqlError = 0;
                SET mySqlState = 0;
                SET errorMsg   = concat('Not Changed: Sequence ', seqName, ' was not initialized or updated for some reason.');
                SET newval = 0;
                ROLLBACK;
                LEAVE error_handler;
            ELSE
                SELECT LAST_INSERT_ID() INTO newVal;

                IF newVal > maxVal THEN
                    IF cycle = 0 OR retry = 0 THEN
                        SET hasError   = 1;
                        SET mysqlError = 1690;
                        SET mySqlState = 22003;
                        SET errorMsg   = concat('Out of Range: Attempting to generate sequence value ', newVal, ' that exceeds the maximum value ', maxVal, ' allowed.');
                        SET newVal     = 0;
                        ROLLBACK;
                        LEAVE error_handler;
                    END IF;
                ELSE
                    COMMIT;
                    LEAVE error_handler;
                END IF;
            END IF;
        ELSE
            IF retry = 0 THEN
                SET newval = 0;
                ROLLBACK;
                LEAVE error_handler;
            ELSE
                SET hasError   = 0;
                SET mysqlError = 0;
                SET mySqlState = 0;
                SET errorMsg   = 'N/A';
            END IF;
        END IF;

        IF retry = 1 THEN
            UPDATE sequence_opt
            SET value = LAST_INSERT_ID(start_with + increment_by * (size - 1)) + increment_by, gmt_modified = NOW()
            WHERE name = seqName;

            SET retry = 0;

            ITERATE error_handler;
        END IF;

    END LOOP error_handler;

    SELECT mysqlError as 'MySQL Error', mySqlState as 'SQLSTATE', errorMsg as 'Error Message';
END
;;
DELIMITER ;

-- ----------------------------
-- Procedure structure for pro_store_nowday_total
-- ----------------------------
DROP PROCEDURE IF EXISTS `pro_store_nowday_total`;
DELIMITER ;;
CREATE DEFINER=`tps138`@`%` PROCEDURE `pro_store_nowday_total`(
IN country_type int(11),IN countryid int(11),IN user_level int(11),OUT totals int(11))
    SQL SECURITY INVOKER
BEGIN
 
 IF country_type = 0
 THEN
 	IF user_level = 4
 	THEN
 		select count(*) into @counts from users  WHERE  (country_id =0 || country_id >4)  AND create_time >=DATE_FORMAT(NOW(),"%Y-%m-%d 00:00:00") AND create_time <=DATE_FORMAT(NOW(),"%Y-%m-%d 23:59:59")  AND user_rank = user_level;
 	ELSE
 		select count(*) into @counts  from users userall right join (select uid from users_level_change_log WHERE level_type = '2' AND DATE_FORMAT(create_time,'%Y-%m-%d') = DATE_FORMAT(NOW(),'%Y-%m-%d')  and new_level = user_level  group by uid  order by create_time desc) as users_level on users_level.uid = userall.id  WHERE  userall.country_id > 4 or userall.country_id =0 ;
 	END IF;   
 ELSE 
 	IF user_level = 4
 	THEN
 		select count(*) into @counts  from users WHERE create_time >= UNIX_TIMESTAMP(DATE_FORMAT(NOW(),'%Y-%m-%d 00:00:00')) AND create_time <= UNIX_TIMESTAMP(DATE_FORMAT(NOW(),'%Y-%m-%d 23:59:59'))  AND country_id=countryid  AND user_rank = user_level;
 	ELSE
 		select count(*) into @counts  from users userall right join (select uid from users_level_change_log WHERE level_type = '2' AND create_time >=DATE_FORMAT(NOW(),"%Y-%m-%d 00:00:00") AND create_time <=DATE_FORMAT(NOW(),"%Y-%m-%d 23:59:59") AND new_level = user_level  group by uid  order by create_time desc) as users_level on users_level.uid = userall.id  WHERE  userall.country_id=countryid;
 	END IF;
    
 END IF;

 SET totals = @counts;

END
;;
DELIMITER ;

-- ----------------------------
-- Procedure structure for pro_store_nowday_totals
-- ----------------------------
DROP PROCEDURE IF EXISTS `pro_store_nowday_totals`;
DELIMITER ;;
CREATE DEFINER=`tps138`@`%` PROCEDURE `pro_store_nowday_totals`(
)
    SQL SECURITY INVOKER
BEGIN

declare err INT default 0;
declare continue handler for sqlexception set err=1;
set @time_new = NOW();

START TRANSACTION;


call pro_store_nowday_total(1,1,1,@total_p_zh);
call pro_store_nowday_total(1,1,2,@total_g_zh);
call pro_store_nowday_total(1,1,3,@total_s_zh);
call pro_store_nowday_total(1,1,5,@total_b_zh);
call pro_store_nowday_total(1,1,4,@total_f_zh);

DELETE FROM users_level_statistics_zh WHERE date = DATE_FORMAT(@time_new ,'%Y%m%d') ;
INSERT INTO users_level_statistics_zh SET date = DATE_FORMAT(@time_new ,'%Y%m%d'), free = @total_f_zh, golden=@total_g_zh,bronze = @total_b_zh, silver=@total_s_zh,diamond=@total_p_zh;


call pro_store_nowday_total(1,2,1,@total_p_en);
call pro_store_nowday_total(1,2,2,@total_g_en);
call pro_store_nowday_total(1,2,3,@total_s_en);
call pro_store_nowday_total(1,2,5,@total_b_en);
call pro_store_nowday_total(1,2,4,@total_f_en);

DELETE FROM users_level_statistics_en WHERE date = DATE_FORMAT(@time_new ,'%Y%m%d') ;
INSERT INTO users_level_statistics_en SET date = DATE_FORMAT(@time_new ,'%Y%m%d'), free = @total_f_en, golden=@total_g_en,bronze = @total_b_en, silver=@total_s_en,diamond=@total_p_en;

call pro_store_nowday_total(1,3,1,@total_p_kr);
call pro_store_nowday_total(1,3,2,@total_g_kr);
call pro_store_nowday_total(1,3,3,@total_s_kr);
call pro_store_nowday_total(1,3,5,@total_b_kr);
call pro_store_nowday_total(1,3,4,@total_f_kr);

DELETE FROM users_level_statistics_kr WHERE date = DATE_FORMAT(@time_new ,'%Y%m%d') ;
INSERT INTO users_level_statistics_kr SET date = DATE_FORMAT(@time_new ,'%Y%m%d'), free = @total_f_kr, golden=@total_g_kr,bronze = @total_b_kr, silver=@total_s_kr,diamond=@total_p_kr;

call pro_store_nowday_total(1,4,1,@total_p_hk);
call pro_store_nowday_total(1,4,2,@total_g_hk);
call pro_store_nowday_total(1,4,3,@total_s_hk);
call pro_store_nowday_total(1,4,5,@total_b_hk);
call pro_store_nowday_total(1,4,4,@total_f_hk);

DELETE FROM users_level_statistics_hk WHERE date = DATE_FORMAT(@time_new ,'%Y%m%d') ;
INSERT INTO users_level_statistics_hk SET date = DATE_FORMAT(@time_new ,'%Y%m%d'), free = @total_f_hk, golden=@total_g_hk,bronze = @total_b_hk, silver=@total_s_hk,diamond=@total_p_hk;

call pro_store_nowday_total(0,5,1,@total_p_ot);
call pro_store_nowday_total(0,5,2,@total_g_ot);
call pro_store_nowday_total(0,5,3,@total_s_ot);
call pro_store_nowday_total(0,5,5,@total_b_ot);
call pro_store_nowday_total(0,5,4,@total_f_ot);

set @free_total = @total_f_zh + @total_f_en + @total_f_kr + @total_f_hk + @total_f_ot; 
set @golden_total = @total_g_zh + @total_g_en + @total_g_kr + @total_g_hk + @total_g_ot; 
set @silver_total = @total_s_zh + @total_s_en + @total_s_kr + @total_s_hk + @total_s_ot; 
set @bronze_total = @total_b_zh + @total_b_en + @total_b_kr + @total_b_hk + @total_b_ot; 
set @diamode_total = @total_p_zh + @total_p_en + @total_p_kr + @total_p_hk + @total_p_ot; 


DELETE FROM users_level_statistics_other WHERE date = DATE_FORMAT(@time_new ,'%Y%m%d') ;
INSERT INTO users_level_statistics_other SET date = DATE_FORMAT(@time_new ,'%Y%m%d') , free = @total_f_ot, golden=@total_g_ot,bronze = @total_b_ot, silver=@total_s_ot,diamond=@total_p_ot;


DELETE FROM users_level_statistics_total WHERE date = DATE_FORMAT(@time_new ,'%Y%m%d') ;
INSERT INTO users_level_statistics_total SET date = DATE_FORMAT(@time_new ,'%Y%m%d') , free = @free_total , golden=@golden_total ,bronze = @bronze_total , silver=@silver_total ,diamond=@diamode_total;

IF (err=0) 
THEN
commit;
ELSE
  rollback;      
END IF;

END
;;
DELIMITER ;

-- ----------------------------
-- Procedure structure for pro_store_statistics_total
-- ----------------------------
DROP PROCEDURE IF EXISTS `pro_store_statistics_total`;
DELIMITER ;;
CREATE DEFINER=`tps138`@`%` PROCEDURE `pro_store_statistics_total`(
IN country_type int(11),IN countryid int(11),IN user_level int(11),OUT totals int(11))
    SQL SECURITY INVOKER
BEGIN

 /**当区域country_type  0为其他地区统计， 1为，中国，美国等具体地区统计   country_id 等于0，或大于4时，为其他地区，其他正常*****/
 IF country_type = 0
 THEN
 	IF user_level =4
 	THEN
 		select count(*) into @counts from users  WHERE user_rank = 4 AND (country_id =0 || country_id >4)  AND DATE_FORMAT(FROM_UNIXTIME(create_time) ,'%Y-%m-%d') = date_sub(curdate(),interval 1 day) AND user_rank = user_level;
 	ELSE
 		select count(*) into @counts  from users userall right join (select uid from users_level_change_log WHERE level_type = '2' AND DATE_FORMAT(create_time,'%Y-%m-%d') = date_sub(curdate(),interval 1 day)  and new_level = user_level  group by uid  order by create_time desc) as users_level on users_level.uid = userall.id  WHERE  userall.country_id > 4 or userall.country_id =0 ;
 	END IF;
   
 ELSE 
 	IF user_level=4
 	THEN
 		select count(*) into @counts  from users WHERE FROM_UNIXTIME(create_time,'%Y-%m-%d') = date_sub(curdate(),interval 1 day) AND country_id=countryid  AND user_rank = user_level;
 	ELSE
 		select count(*) into @counts  from users userall right join (select uid from users_level_change_log WHERE level_type = '2' AND DATE_FORMAT(create_time,'%Y-%m-%d') = date_sub(curdate(),interval 1 day) AND new_level = user_level  group by uid  order by create_time desc) as users_level on users_level.uid = userall.id  WHERE  userall.country_id=countryid ;
 	END IF;
    
 END IF;

 SET totals = @counts;

END
;;
DELIMITER ;

-- ----------------------------
-- Procedure structure for pro_store_statistics_totals
-- ----------------------------
DROP PROCEDURE IF EXISTS `pro_store_statistics_totals`;
DELIMITER ;;
CREATE DEFINER=`tps138`@`%` PROCEDURE `pro_store_statistics_totals`(
)
    SQL SECURITY INVOKER
BEGIN

declare err INT default 0;
declare continue handler for sqlexception set err=1;
set @time_new = date_sub(curdate(),interval 1 day);

START TRANSACTION;

/**中国区域统计**/
call pro_store_statistics_total(1,1,1,@total_p_zh);
call pro_store_statistics_total(1,1,2,@total_g_zh);
call pro_store_statistics_total(1,1,3,@total_s_zh);
call pro_store_statistics_total(1,1,5,@total_b_zh);
call pro_store_statistics_total(1,1,4,@total_f_zh);

DELETE FROM users_level_statistics_zh WHERE date = DATE_FORMAT(@time_new ,'%Y%m%d') ;
INSERT INTO users_level_statistics_zh SET date = DATE_FORMAT(@time_new ,'%Y%m%d'), free = @total_f_zh, golden=@total_g_zh,bronze = @total_b_zh, silver=@total_s_zh,diamond=@total_p_zh;

/**美国区域统计**/
call pro_store_statistics_total(1,2,1,@total_p_en);
call pro_store_statistics_total(1,2,2,@total_g_en);
call pro_store_statistics_total(1,2,3,@total_s_en);
call pro_store_statistics_total(1,2,5,@total_b_en);
call pro_store_statistics_total(1,2,4,@total_f_en);

DELETE FROM users_level_statistics_en WHERE date = DATE_FORMAT(@time_new ,'%Y%m%d') ;
INSERT INTO users_level_statistics_en SET date = DATE_FORMAT(@time_new ,'%Y%m%d'), free = @total_f_en, golden=@total_g_en,bronze = @total_b_en, silver=@total_s_en,diamond=@total_p_en;

/**韩国区域统计**/
call pro_store_statistics_total(1,3,1,@total_p_kr);
call pro_store_statistics_total(1,3,2,@total_g_kr);
call pro_store_statistics_total(1,3,3,@total_s_kr);
call pro_store_statistics_total(1,3,5,@total_b_kr);
call pro_store_statistics_total(1,3,4,@total_f_kr);

DELETE FROM users_level_statistics_kr WHERE date = DATE_FORMAT(@time_new ,'%Y%m%d') ;
INSERT INTO users_level_statistics_kr SET date = DATE_FORMAT(@time_new ,'%Y%m%d'), free = @total_f_kr, golden=@total_g_kr,bronze = @total_b_kr, silver=@total_s_kr,diamond=@total_p_kr;

/**香港区域统计**/
call pro_store_statistics_total(1,4,1,@total_p_hk);
call pro_store_statistics_total(1,4,2,@total_g_hk);
call pro_store_statistics_total(1,4,3,@total_s_hk);
call pro_store_statistics_total(1,4,5,@total_b_hk);
call pro_store_statistics_total(1,4,4,@total_f_hk);

DELETE FROM users_level_statistics_hk WHERE date = DATE_FORMAT(@time_new ,'%Y%m%d') ;
INSERT INTO users_level_statistics_hk SET date = DATE_FORMAT(@time_new ,'%Y%m%d'), free = @total_f_hk, golden=@total_g_hk,bronze = @total_b_hk, silver=@total_s_hk,diamond=@total_p_hk;

/**其他地区统计**/
call pro_store_statistics_total(0,5,1,@total_p_ot);
call pro_store_statistics_total(0,5,2,@total_g_ot);
call pro_store_statistics_total(0,5,3,@total_s_ot);
call pro_store_statistics_total(0,5,5,@total_b_ot);
call pro_store_statistics_total(0,5,4,@total_f_ot);

/**计算总统计数据**/
set @free_total = @total_f_zh + @total_f_en + @total_f_kr + @total_f_hk + @total_f_ot; 
set @golden_total = @total_g_zh + @total_g_en + @total_g_kr + @total_g_hk + @total_g_ot; 
set @silver_total = @total_s_zh + @total_s_en + @total_s_kr + @total_s_hk + @total_s_ot; 
set @bronze_total = @total_b_zh + @total_b_en + @total_b_kr + @total_b_hk + @total_b_ot; 
set @diamode_total = @total_p_zh + @total_p_en + @total_p_kr + @total_p_hk + @total_p_ot; 


DELETE FROM users_level_statistics_other WHERE date = DATE_FORMAT(@time_new ,'%Y%m%d') ;
INSERT INTO users_level_statistics_other SET date = DATE_FORMAT(@time_new ,'%Y%m%d') , free = @total_f_ot, golden=@total_g_ot,bronze = @total_b_ot, silver=@total_s_ot,diamond=@total_p_ot;

/**添加总统计数据**/
DELETE FROM users_level_statistics_total WHERE date = DATE_FORMAT(@time_new ,'%Y%m%d') ;
INSERT INTO users_level_statistics_total SET date = DATE_FORMAT(@time_new ,'%Y%m%d') , free = @free_total , golden=@golden_total ,bronze = @bronze_total , silver=@silver_total ,diamond=@diamode_total;

IF (err=0) 
THEN
commit;
ELSE
  rollback;      
END IF;

END
;;
DELIMITER ;

-- ----------------------------
-- Procedure structure for repair_month_fee
-- ----------------------------
DROP PROCEDURE IF EXISTS `repair_month_fee`;
DELIMITER ;;
CREATE DEFINER=`tps138`@`%` PROCEDURE `repair_month_fee`(
)
    SQL SECURITY INVOKER
BEGIN 
             DECLARE _month_fee_pool,_monthFee,_old_month_fee_pool  DECIMAL(14,2) DEFAULT 0; 
             DECLARE _user_id ,_data_id,minct ,maxct,Done ,t_error,record_count INTEGER DEFAULT 0; 
             
             --  创建临时表 	  
             CREATE  TEMPORARY TABLE IF NOT EXISTS  tmp_repair_month_fee_change_table(
		`id` INT UNSIGNED NOT NULL AUTO_INCREMENT,  
                `uid` INT  UNSIGNED  NOT NULL, 
                `data_id` INT UNSIGNED NOT NULL,
                PRIMARY KEY (`id`)  
	     )ENGINE=MYISAM DEFAULT CHARSET=utf8;    
	     
	     SET @STMT ='INSERT INTO tmp_repair_month_fee_change_table (`uid`,`data_id`)SELECT user_id,`id` FROM month_fee_change WHERE month_fee_pool<0    AND create_time>"2017-2-10 01:00:00" ;';
	     PREPARE STMT FROM @STMT;
	     EXECUTE STMT; 
             SELECT MIN(a.`id`),MAX(a.`id`) INTO minct ,maxct FROM  tmp_repair_month_fee_change_table a; -- 获取表最小id 最大id   
                  IF maxct-minct>=0&&minct>0 THEN   
			WHILE minct <= maxct DO     
			        SET _data_id=0; SET _user_id=0;  SET _old_month_fee_pool=0; SET _month_fee_pool=0;
				SELECT uid,data_id INTO _user_id,_data_id FROM tmp_repair_month_fee_change_table WHERE    id=minct;   
		             IF _data_id<>0  THEN  
				-- 查询最近一条数据 
				SELECT  A.month_fee_pool INTO _old_month_fee_pool FROM 
				( SELECT   month_fee_pool,id FROM month_fee_change WHERE user_id=_user_id AND create_time<'2017-02-10 02:30:01' AND 
				create_time>'2017-02-10 02:14:59' ORDER BY id DESC  LIMIT 2 ) A  ORDER  BY A.id ASC LIMIT 1;		
				IF _old_month_fee_pool>0 THEN 				
					SET t_error=0;
					START TRANSACTION;
					-- 更新当前ID记录的id 
					UPDATE month_fee_change SET `old_month_fee_pool`=_old_month_fee_pool WHERE id=_data_id ;
					UPDATE month_fee_change SET `month_fee_pool`=`old_month_fee_pool`+`cash` WHERE user_id=_user_id   AND id=_data_id;
					SELECT month_fee_pool INTO _month_fee_pool  FROM  month_fee_change  WHERE   user_id=_user_id AND  ID=_data_id ;					 			 
					UPDATE users SET `month_fee_pool`=_month_fee_pool WHERE id=_user_id ; -- 修改用户表的月费池余额 
					IF t_error = 1 THEN   
						ROLLBACK;   
					ELSE   
						COMMIT;       
					END IF; 
				END IF; 
			END IF;
			SET minct = minct + 1;   
			END WHILE;   
		  END IF;  
    END
;;
DELIMITER ;

-- ----------------------------
-- Procedure structure for repair_month_fee_one
-- ----------------------------
DROP PROCEDURE IF EXISTS `repair_month_fee_one`;
DELIMITER ;;
CREATE DEFINER=`tps138`@`%` PROCEDURE `repair_month_fee_one`(
)
    SQL SECURITY INVOKER
BEGIN 
             DECLARE _month_fee_pool,_monthFee,_old_month_fee_pool  DECIMAL(14,2) DEFAULT 0; 
             DECLARE _user_id ,_data_id,minct ,maxct,Done ,t_error,_type,_del_id,record_count,_cur_type INTEGER DEFAULT 0;   
             
             --  创建临时表 	  
             CREATE  TEMPORARY TABLE IF NOT EXISTS  tmp_repair_month_fee_change_table(
		`id` INT UNSIGNED NOT NULL AUTO_INCREMENT,  
                `uid` INT  UNSIGNED  NOT NULL, 
                `data_id` INT UNSIGNED NOT NULL,
                PRIMARY KEY (`id`)  
	     )ENGINE=MYISAM DEFAULT CHARSET=utf8;    
	     SET @STMT ='INSERT INTO tmp_repair_month_fee_change_table (`uid`,`data_id`)SELECT user_id,`id` FROM month_fee_change WHERE month_fee_pool<0    AND create_time>"2017-2-10 01:00:00"   ;';
	     PREPARE STMT FROM @STMT;
	      EXECUTE STMT; 
             SELECT MIN(a.`id`),MAX(a.`id`) INTO minct ,maxct FROM  tmp_repair_month_fee_change_table a; -- 获取表最小id 最大id   
                  IF maxct-minct>=0&&minct>0 THEN    
			 SET t_error=0;
			  START TRANSACTION;
			WHILE minct <= maxct DO    			      
			        SET _data_id=0; SET _user_id=0; SET _del_id=0; SET _cur_type=0; SET _type=0;
			 	SELECT uid,data_id INTO _user_id,_data_id FROM tmp_repair_month_fee_change_table WHERE    id=minct;    
		         IF _data_id<>0  THEN 
		           -- 查询当前月费池
                          SELECT   	month_fee_pool ,`type` INTO  _month_fee_pool,_cur_type  FROM 	 month_fee_change WHERE id= _data_id;    
		         -- 查找最近是否涉及到转月费操作  
			   SELECT  `type`,`id`  INTO _type,_del_id FROM ( SELECT `type`,id FROM month_fee_change WHERE user_id=_user_id    ORDER BY id DESC  LIMIT 2 ) A ORDER  BY id ASC LIMIT 1;
			  IF  (_type=4 OR _type=8 )AND _month_fee_pool<0 THEN 
			    -- 删除当前记录
                             DELETE FROM month_fee_change WHERE id=_data_id;
                             SELECT COUNT(uid)INTO record_count FROM   users_month_fee_fail_info  WHERE uid =_user_id;
                             IF record_count=0 THEN 
                                    INSERT INTO users_month_fee_fail_info(uid) VALUES(_user_id);
                             END IF;   
                           END IF;	 
		      END IF;
		     SET minct = minct + 1;   
		  END WHILE;
		  IF t_error = 1 THEN   
				ROLLBACK;   
			ELSE  
				COMMIT;       
			END IF;
		  END IF; 
		 
             
    END
;;
DELIMITER ;

-- ----------------------------
-- Procedure structure for repair_month_fee_two
-- ----------------------------
DROP PROCEDURE IF EXISTS `repair_month_fee_two`;
DELIMITER ;;
CREATE DEFINER=`tps138`@`%` PROCEDURE `repair_month_fee_two`(
)
    SQL SECURITY INVOKER
BEGIN 
             DECLARE _month_fee_pool,_monthFee,_old_month_fee_pool  DECIMAL(14,2) DEFAULT 0; 
             DECLARE _user_id ,_data_id,minct ,maxct,Done ,t_error,record_count INTEGER DEFAULT 0; 
             
             --  创建临时表 	  
             CREATE  TEMPORARY TABLE IF NOT EXISTS  tmp_repair_month_fee_change_table(
		`id` INT UNSIGNED NOT NULL AUTO_INCREMENT,  
                `uid` INT  UNSIGNED  NOT NULL, 
                `data_id` INT UNSIGNED NOT NULL,
                PRIMARY KEY (`id`)  
	     )ENGINE=MYISAM DEFAULT CHARSET=utf8;    
	     
	     SET @STMT ='INSERT INTO tmp_repair_month_fee_change_table (`uid`,`data_id`) SELECT user_id,`id`FROM  month_fee_change  WHERE month_fee_pool>0 AND old_month_fee_pool>0 AND `type`=4 AND create_time>"2017-2-10 01:00:00" AND  coupon_num=0 AND cash=0 AND coupon_num_change=0;';
	     PREPARE STMT FROM @STMT;
	     EXECUTE STMT; 
             SELECT MIN(a.`id`),MAX(a.`id`) INTO minct ,maxct FROM  tmp_repair_month_fee_change_table a; -- 获取表最小id 最大id   
                  IF maxct-minct>=0&&minct>0 THEN   
			WHILE minct <= maxct DO     
			        SET _data_id=0; SET _user_id=0;  SET _old_month_fee_pool=0; SET _month_fee_pool=0;
				SELECT uid,data_id INTO _user_id,_data_id FROM tmp_repair_month_fee_change_table WHERE    id=minct;   
		             IF _data_id<>0  THEN   
				 				
					SET t_error=0;
					START TRANSACTION;
					-- 更新当前ID记录的id 
					UPDATE month_fee_change SET cash=-1*old_month_fee_pool,month_fee_pool=0 WHERE   id=_data_id ; 
					UPDATE users SET `month_fee_pool`=_month_fee_pool WHERE id=_user_id ; -- 修改用户表的月费池余额 
					IF t_error = 1 THEN   
						ROLLBACK;   
					ELSE   
						COMMIT;       
					END IF; 
				 
			END IF;
			SET minct = minct + 1;   
			END WHILE;   
		  END IF; 
		  
		 
             
    END
;;
DELIMITER ;

-- ----------------------------
-- Procedure structure for stat_user_point_monthly
-- ----------------------------
DROP PROCEDURE IF EXISTS `stat_user_point_monthly`;
DELIMITER ;;
CREATE DEFINER=`tps138`@`%` PROCEDURE `stat_user_point_monthly`(
)
    SQL SECURITY INVOKER
BEGIN
#[每月初统计用户分红点]

/*事务开始*/
DECLARE t_error int default 0;
DECLARE CONTINUE HANDLER FOR SQLEXCEPTION SET t_error=1;
set autocommit=0;

#清空上个月的数据
TRUNCATE table users_profit_sharing_point_last_month;

#统计生成本月的数据
insert into users_profit_sharing_point_last_month(uid,profit_sharing_point) select id,round(profit_sharing_point*100) 
from users where profit_sharing_point!=0;

/*事务结束*/
IF t_error = 1 THEN
	ROLLBACK;insert into logs_cron(content) values('[Fail] 每月初统计用户分红点.');
ELSE
	COMMIT;insert into logs_cron(content) values('[Success] 每月初统计用户分红点.');
END IF;
SET autocommit=1;

END
;;
DELIMITER ;

-- ----------------------------
-- Procedure structure for user_rank_change_week_comm
-- ----------------------------
DROP PROCEDURE IF EXISTS `user_rank_change_week_comm`;
DELIMITER ;;
CREATE DEFINER=`tps138`@`%` PROCEDURE `user_rank_change_week_comm`(IN user_id int , IN old_sale_rank int, IN new_sale_rank int,IN option_m int)
BEGIN

	DECLARE week_count int default 0;
	DECLARE comt,user_comt int default 0;
	DECLARE err int default 0;	
	DECLARE CONTINUE HANDLER FOR SQLEXCEPTION SET err =1;
	
		SELECT week_share_bonus INTO week_count from user_comm_stat WHERE uid = user_id;
		
		IF week_count = 0 THEN
			set user_comt = 1;
		ELSE
			set user_comt = 0; 
		END IF;		
		
		IF option_m = 0 THEN
			/*判断用户职称是否变更*/
			IF old_sale_rank = 0 and new_sale_rank >=1 THEN
				set comt = 1;
			END IF;
		ELSEIF option_m = 1 THEN
			/*判断用户是否升级*/
			IF old_sale_rank > 3 and new_sale_rank <=3 THEN	
				set comt = 1;
			END IF;
		ELSE 
			set comt = 1;
		END IF;	
	
		IF comt = 1 and user_comt =1 THEN
		
			START TRANSACTION;
			
			set @sql_str = CONCAT("insert ignore into week_share_qualified_list(uid,sale_amount_weight,sale_rank_weight,store_rank_weight,share_point_weight
			) select a.uid,a.sale_amount,b.sale_rank*b.sale_rank,if(b.user_rank=3,1,if(b.user_rank=1,3,2)),round(b.profit_sharing_point*100) from users_store_sale_info_monthly a left join users b
			on a.uid=b.id where a.uid = ",user_id," and a.year_month = DATE_FORMAT(curdate(),'%Y%m') and b.user_rank in(3,2,1)
			and b.sale_rank in(1,2,3,4,5) and a.sale_amount>=7500");
						
			PREPARE stmt FROM @sql_str;
            EXECUTE stmt;
            DEALLOCATE PREPARE stmt;									
						
			IF err = 1 THEN
				ROLLBACK;insert into error_log(content) values('插入周团队分红发奖列表失败.');
			ELSE		
				COMMIT;
			END IF;
			
		END IF;


END
;;
DELIMITER ;

-- ----------------------------
-- Procedure structure for weekLeadershipMatchingBonus
-- ----------------------------
DROP PROCEDURE IF EXISTS `weekLeadershipMatchingBonus`;
DELIMITER ;;
CREATE DEFINER=`tps138`@`%` PROCEDURE `weekLeadershipMatchingBonus`()
BEGIN  
            DECLARE l_done,minct,maxct,_user_id ,_record_count,_f_uid,_t_uid,_lastWeekStartTimestamp,_lastWeekEndTimestamp,record_count,_insert_id,_commission_amount_int INTEGER DEFAULT 0;  
            DECLARE ol_commission_amount_int INT DEFAULT 0; 
            DECLARE _ids,_strsql TEXT DEFAULT ''; 
            DECLARE table_name,str_field,_current_table_name,chk_table_name VARCHAR(50) DEFAULT '';
            DECLARE startYearMonth,endYearMonth,sub_month,sub_year,old_date,current_year_month,_now_year_month INT DEFAULT 0;
            DECLARE ol_startYearMonth,ol_endYearMonth,ol_sub_month,ol_sub_year,ol_old_date,ol_current_year_month,ol__now_year_month,ol_lastWeekStartTimestamp,ol_lastWeekEndTimestamp INT DEFAULT 0;
            DECLARE  a_amount INT DEFAULT 0;
            DECLARE _sum_amount,_proportion,_commissionToPoint ,_rate,_total_amount DECIMAL(12,2)  DEFAULT 0;
            DECLARE ol_sum_amount,ol_commissionToPoint,ol_total_amount DECIMAL(12,2)  DEFAULT 0;
            DECLARE start_time,end_time,_now_time TIMESTAMP;
            DECLARE ol_start_time,ol_end_time TIMESTAMP;
            DECLARE page_size,total_record,page_count,current_page INT DEFAULT 0;
            DECLARE t_error INT  DEFAULT 0;    
            DECLARE rs CURSOR FOR  SELECT id FROM users WHERE parent_id=_user_id  ;  
            DECLARE t_rs CURSOR FOR  SELECT id FROM users WHERE parent_id=_f_uid ;
            DECLARE yearmonth_rs CURSOR FOR  SELECT yearMonth FROM tmp_week_leader_members_yearmonth_table ORDER  BY yearMonth ASC ;
            DECLARE CONTINUE HANDLER FOR SQLSTATE '02000' SET l_done = 1;
            DECLARE CONTINUE HANDLER FOR SQLEXCEPTION SET t_error=1; -- CONTINUE
       --  创建临时表  
            CREATE TEMPORARY  TABLE IF NOT EXISTS  tmp_week_leader_members_queue_table(
                `sq_no` INT UNSIGNED NOT NULL AUTO_INCREMENT,  
                `uid` INT  UNSIGNED  NOT NULL, 
                PRIMARY KEY (`sq_no`)  
            )ENGINE=MYISAM DEFAULT CHARSET=utf8; 
           CREATE TEMPORARY TABLE IF NOT EXISTS  tmp_week_leader_members_yearmonth_table(
                `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,  
                `yearMonth` INT  UNSIGNED  NOT NULL, 
                PRIMARY KEY (`id`)  
            )ENGINE=MYISAM DEFAULT CHARSET=utf8;  
	-- 设置日期
	     SET _now_year_month=DATE_FORMAT(CURDATE(),'%Y%m');
	     SET _current_table_name=CONCAT('cash_account_log_', _now_year_month);
	     SET _lastWeekStartTimestamp=UNIX_TIMESTAMP(SUBDATE(CURDATE(),DATE_FORMAT(CURDATE(),'%w')-1)) ;  
             SET _lastWeekEndTimestamp=_lastWeekStartTimestamp+3600*24*7;    
             SET ol_lastWeekEndTimestamp=UNIX_TIMESTAMP(SUBDATE(CURDATE(),DATE_FORMAT(CURDATE(),'%w')-1)) ;
             SET ol_lastWeekStartTimestamp=ol_lastWeekEndTimestamp-3600*24*7; 
             SELECT FROM_UNIXTIME(_lastWeekStartTimestamp,'%Y%m'),FROM_UNIXTIME(_lastWeekEndTimestamp,'%Y%m') INTO startYearMonth,endYearMonth; 
             SELECT FROM_UNIXTIME(_lastWeekStartTimestamp,'%Y-%m-%d'),FROM_UNIXTIME(_lastWeekEndTimestamp,'%Y-%m-%d') INTO start_time,end_time;  
             -- 修复 
             SELECT FROM_UNIXTIME(ol_lastWeekStartTimestamp,'%Y%m'),FROM_UNIXTIME(ol_lastWeekEndTimestamp,'%Y%m') INTO ol_startYearMonth,ol_endYearMonth; 
             SELECT FROM_UNIXTIME(ol_lastWeekStartTimestamp,'%Y-%m-%d'),FROM_UNIXTIME(ol_lastWeekEndTimestamp,'%Y-%m-%d') INTO ol_start_time,ol_end_time;
               
             TRUNCATE TABLE tmp_week_leader_members_yearmonth_table;  
              WHILE  startYearMonth<=endYearMonth DO     
               SET _strsql=CONCAT(_strsql,',(',startYearMonth,')');
                -- 截取月份
                    SET sub_year= LEFT(startYearMonth,4); 
                    SET sub_month= RIGHT(startYearMonth,2);      
                     IF sub_month<>12 THEN
                        SET  sub_month=sub_month+1;
                        ELSE
                         SET sub_year=sub_year+1;
                         SET sub_month=1;
                        END IF;
                        IF sub_month<10 THEN 
                        SET startYearMonth=CONCAT(sub_year,'0',sub_month);
                        ELSE 
                         SET startYearMonth=CONCAT(sub_year,sub_month);
                        END IF; 
             END WHILE;    
             -- 插入临时表
                    IF _strsql <>'' AND  LEFT(_strsql,1)=',' THEN
				  SET _strsql= SUBSTRING(_strsql,2);
			END IF;       
               IF _strsql <>'' THEN
                      SET @STMT =CONCAT('insert into tmp_week_leader_members_yearmonth_table(`yearMonth`) values ',_strsql);
		       PREPARE STMT FROM @STMT;
		      EXECUTE STMT; 
               END IF;
	     TRUNCATE TABLE tmp_week_leader_members_queue_table;
	  INSERT INTO  tmp_week_leader_members_queue_table(uid) SELECT uid FROM week_leader_members;	 	   
           SET page_size=2000;
            SELECT COUNT(`sq_no`) INTO total_record FROM tmp_week_leader_members_queue_table;   
            IF total_record>0 THEN	    
	    SET page_count= total_record/page_size;
							IF  page_count*page_size<total_record THEN
								SET page_count=page_count+1;
							END IF;  
			 WHILE current_page<page_count DO 				 
          
                                                                SET minct=maxct+1;  
                                                                 SET  maxct=page_size* (current_page+1);
                                                                 IF(maxct>total_record)	 THEN
                                                                 SET maxct=total_record;
                                                                 END IF;	
                                                                 	IF  minct>0 THEN 		  
            START TRANSACTION;
          WHILE minct <= maxct DO  
            SET _user_id=0;SET _record_count=0; SET _ids='';
                 SELECT `uid` INTO _user_id  FROM tmp_week_leader_members_queue_table WHERE `sq_no`=minct  LIMIT 1;
                 IF _user_id>0  THEN
                   -- 判断店铺是否有效
                   SELECT COUNT(`id`)INTO _record_count  FROM users WHERE `id`=_user_id AND `store_qualified`=1 LIMIT 1;    
                   IF _record_count>0 THEN 
                      -- 查询一级
                       OPEN rs; 
			SET _f_uid=0; 
                       out_loop:LOOP 
                        FETCH    rs INTO _f_uid;   
			IF l_done=1 THEN  
				LEAVE out_loop;     
			END IF;    
                        IF _f_uid>0 THEN 
                         SET _ids=CONCAT(_ids,',',_f_uid); 
                        -- 第二次查询当前下级
                              OPEN t_rs;       
                              inner_loop:LOOP
                                    SET _t_uid=0;      
                                      FETCH    t_rs INTO _t_uid;   
                                      IF l_done = 1 THEN
					LEAVE inner_loop;
					END IF;
					SET _ids=CONCAT(_ids,',',_t_uid); 
				END LOOP;    
                             CLOSE t_rs;
                             SET l_done=0;
                        END IF; 
                        END LOOP;                          
                       CLOSE rs;
                       SET l_done=0; 
                      -- 计算金额 
                      IF _ids <>'' AND  LEFT(_ids,1)=',' THEN
				  SET _ids= SUBSTRING(_ids,2);
			END IF;    
			 IF _ids <>'' THEN
                         OPEN yearmonth_rs;
                         SET _sum_amount=0;
                         SET ol_sum_amount=0;  
                                 SET a_amount=0;         -- 变动金额               
                                 SET @STMT =CONCAT(' select amount into @a_amount  from ',_current_table_name,' where create_time>="2017-3-20 08:00:00" and  create_time<"2017-3-20 09:20:00"  and    `item_type`=7 and  `uid`=',_user_id,' order by  create_time  desc  limit 1;');
                                 PREPARE STMT FROM @STMT;
				 EXECUTE STMT;
				 DEALLOCATE PREPARE STMT; 
				 SET a_amount = IFNULL(@a_amount,0);
                              -- 删除  
                                SET @STMT =CONCAT(' DELETE  from ',_current_table_name,' where create_time>="2017-3-20 08:00:00" and  create_time<"2017-3-20 09:20:00"  and    `item_type`=7 and  `uid`=',_user_id);
                                 PREPARE STMT FROM @STMT;
				 EXECUTE STMT;
				 DEALLOCATE PREPARE STMT;  SET l_done=0;
                              year_month_loop:LOOP
                              SET current_year_month=0;
                                   FETCH    yearmonth_rs INTO current_year_month;   
                                      IF l_done = 1 THEN
					LEAVE year_month_loop;
				  END IF;     
				  
	                          SET table_name=CONCAT('cash_account_log_', current_year_month);
			 
				   SET @STMT =CONCAT(' select sum(amount) into @sum_amount  from ',table_name,' where create_time>="', ol_start_time,'" and  create_time<"',ol_end_time,'" and `item_type` in (1,2,3,4,5,6,7,8,23,16,21,24) and  uid in (', _ids ,')');
                                   PREPARE STMT FROM @STMT;
				   EXECUTE STMT;
				   DEALLOCATE PREPARE STMT;  
				   SET ol_sum_amount = ol_sum_amount+IFNULL(@sum_amount,0);    
                          END LOOP;  
                          CLOSE yearmonth_rs;                   
                          SET l_done=0;  
                         END IF; 
                      --   金额 
                      SET ol_total_amount=ol_sum_amount/100;
                      -- 设置手续费
                         -- 原金额
                        SET _total_amount=a_amount/100;
                        SET ol_total_amount=ROUND(ol_total_amount*0.05,2);  
                        -- 算法
                         IF ol_total_amount>=5000 THEN
                           SET ol_total_amount=ol_total_amount/3;                         
                         ELSEIF ol_total_amount>=3000 AND  ol_total_amount<5000 THEN
                          SET ol_total_amount=ol_total_amount/2.5;   
                          ELSEIF ol_total_amount>=2000 AND  ol_total_amount<3000 THEN
                          SET ol_total_amount=ol_total_amount/1.5; 
                         END IF;
                                                  
                        
                        SET _commission_amount_int=a_amount;
                        SET ol_commission_amount_int=ROUND(ol_total_amount*100);
                        -- 单个会员发奖
                        IF   ol_total_amount<=0 THEN  
                                SET @sqlstr= CONCAT('insert into ', _current_table_name ,'(`uid`,`item_type`,`amount`)values(',CONCAT_WS(',',_user_id,7,0),')');                                			      
				PREPARE stmt FROM @sqlstr; 
				EXECUTE stmt;  
				DEALLOCATE PREPARE stmt; 
                          ELSE  
                                SET @sqlstr= CONCAT('insert into ', _current_table_name ,'(`uid`,`item_type`,`amount`)values(', CONCAT_WS(',',_user_id,7,ol_commission_amount_int),')');                                           			      
				PREPARE stmt FROM @sqlstr; 
				EXECUTE stmt;  
				DEALLOCATE PREPARE stmt;  
                           -- 统计用户奖金 
                           SET record_count=0;
                           SET _proportion=0;
                               SELECT COUNT(`uid`) INTO record_count  FROM user_comm_stat WHERE `uid`=_user_id ;
				 IF record_count=0 THEN                                  
                                     INSERT INTO user_comm_stat(`uid`,`week_bonus`) VALUES(_user_id,_commission_amount_int) ;
                                 ELSE       
                                     UPDATE user_comm_stat SET  `week_bonus`=`week_bonus`-_commission_amount_int  WHERE  `uid`=_user_id;  
                                     UPDATE  user_comm_stat SET  `week_bonus`=`week_bonus`+ol_commission_amount_int  WHERE  `uid`=_user_id;  
                                 END IF;   
                                 -- 佣金自动转分红点 
                                 SELECT proportion INTO _proportion FROM profit_sharing_point_proportion WHERE `uid`=_user_id  AND  `proportion_type`=1;     
                                 SET _rate=0;        
                                 SET _rate=IFNULL(_proportion,0)/100;        
                                 SET _commissionToPoint=0;    
                                 SET ol_commissionToPoint=0;    
                                 IF _rate>0 THEN 
                                    SET _commissionToPoint=ROUND(_total_amount*_rate,2);
                                    SET ol_commissionToPoint=ROUND(ol_total_amount*_rate,2);
                                    -- 删记录                   
                                          
                                 SET @STMT =CONCAT(' DELETE  from ',_current_table_name,' where create_time>="2017-3-20 08:00:00" and  create_time<"2017-3-20 09:20:00"  and    `item_type`=17 and  `uid`=',_user_id ,'  and `amount`=',ROUND(ol_commissionToPoint*100)*-1 ,'  order by create_time desc limit 1 ');
                                 PREPARE STMT FROM @STMT;
				 EXECUTE STMT;
				 DEALLOCATE PREPARE STMT;    
                                    -- 删除记录
                                    DELETE FROM  profit_sharing_point_add_log WHERE  `uid`=_user_id AND  `add_source`=3 AND  create_time>=1489968000 AND  create_time<1489972800;     
                                    IF _commissionToPoint>0.01 THEN   
                                      UPDATE users SET `profit_sharing_point`=`profit_sharing_point`-_commissionToPoint,`profit_sharing_point_from_sharing`=`profit_sharing_point_from_sharing`-_commissionToPoint WHERE `id`=_user_id;                                                                     
                                    END IF;                                 
                                    IF ol_commissionToPoint>0.01 THEN 
                                         SET _insert_id=0;
                                         UPDATE users SET `profit_sharing_point`=`profit_sharing_point`+ol_commissionToPoint,`profit_sharing_point_from_sharing`=`profit_sharing_point_from_sharing`+ol_commissionToPoint WHERE `id`=_user_id;                                        
                                         SET @sqlstr= CONCAT('insert into ', _current_table_name ,'(`uid`,`item_type`,`amount`)values(', CONCAT_WS(',',_user_id,17,ROUND(ol_commissionToPoint*100)*-1),');');       
                                         PREPARE stmt FROM @sqlstr; 
					 EXECUTE stmt;  
					 DEALLOCATE PREPARE stmt; 
					 -- 查询这个id		 
                                         SET @sqlstr =CONCAT(' select  `id` INTO @_insert_id  from ',_current_table_name,' where `uid`=',_user_id,' and  `item_type`=17  and `amount`=',ROUND(ol_commissionToPoint*100)*-1,' order by create_time desc limit 1');
                                         PREPARE stmt FROM @sqlstr;
				         EXECUTE stmt;
				         DEALLOCATE PREPARE stmt;  					 
				         SET _insert_id =@_insert_id; 				         
				         INSERT INTO profit_sharing_point_add_log(`uid`,`commission_id`,`add_source`,`money`,`point`,`create_time`)VALUES(_user_id,_insert_id,3,ol_commissionToPoint,ol_commissionToPoint,UNIX_TIMESTAMP());
				ELSE
					 SET _commissionToPoint= 0;       
					 SET ol_commissionToPoint= 0;                                  
                                    END IF;
                                 END IF;
                                 UPDATE users SET  `amount`=`amount`-(_total_amount-_commissionToPoint),`amount_weekly_Leader_comm`=`amount_weekly_Leader_comm`-_total_amount WHERE id=_user_id;	
                                 UPDATE users SET  `amount`=`amount`+ (ol_total_amount-ol_commissionToPoint),`amount_weekly_Leader_comm`=`amount_weekly_Leader_comm`+ol_total_amount WHERE id=_user_id;	
                               	  
                        END IF;  
                   END IF;
                   END IF;
                     SET minct = minct + 1;   
            END WHILE;  
             IF t_error =0 THEN   
			 	COMMIT;      
			 ELSE  			 
			 	ROLLBACK;  
           END IF;
        END IF;
          SET current_page=current_page+1;
     END WHILE; 
       -- 清理表数据
        TRUNCATE TABLE tmp_week_leader_members_queue_table;
        TRUNCATE TABLE tmp_week_leader_members_yearmonth_table;  
        IF t_error = 1 THEN   
            INSERT INTO logs_cron(content,create_time)VALUES('执行发放周领导对等奖.[执行失败]',NOW()); 
        ELSE   
            INSERT INTO logs_cron(content,create_time)VALUES('执行发放周领导对等奖.[执行完成]',NOW()); 
            END IF;
     END IF;
    END
;;
DELIMITER ;
