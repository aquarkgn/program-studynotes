
 ## 新增字段示例：
 ```sql
alter table table_example add `istransaction` tinyint(1) unsigned NOT NULL DEFAULT 0 COMMENT '是否已完成交易，0否，1是';
alter table table_example add `turnover` decimal(10) unsigned NOT NULL DEFAULT 0 COMMENT '交易额度';
alter table table_example add `tradingparty` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '交易方';
alter table table_example add `copyrighttype` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '出售版权';
alter table table_example add `authorcontent` mediumtext CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '游戏开发者简介';
alter table table_example add `operation` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '游戏运行平台，0未知,1移动端游戏，2非移动端游戏';
alter table table_example add `website` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '游戏官网地址';
alter table table_example add `stime` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '文旅项目开始时间';
alter table table_example add `etime` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '文旅项目结束时间';
alter table table_example add `address` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '文旅项目地址';
alter table table_example add `company` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '影视出品公司名称';
alter table table_example add `filmtype` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '影视类型';
```