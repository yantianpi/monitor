/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50636
Source Host           : localhost:3306
Source Database       : test

Target Server Type    : MYSQL
Target Server Version : 50636
File Encoding         : 65001

Date: 2017-07-05 18:54:32
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for attribute_list
-- ----------------------------
DROP TABLE IF EXISTS `attribute_list`;
CREATE TABLE `attribute_list` (
  `Id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增主键',
  `CategoryId` int(11) NOT NULL DEFAULT '0' COMMENT '分类id',
  `Name` varchar(255) NOT NULL DEFAULT '' COMMENT '属性名',
  `Alias` varchar(255) NOT NULL DEFAULT '' COMMENT '别名',
  `ContentType` enum('INT','STRING') NOT NULL DEFAULT 'INT' COMMENT '内容类型',
  `DefaultMessage` varchar(255) NOT NULL DEFAULT '' COMMENT '默认消息',
  `Status` enum('ACTIVE','INACTIVE') NOT NULL DEFAULT 'ACTIVE' COMMENT '状态',
  `AddTime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `UpdateTime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '更新时间',
  `Timestamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '系统更新时间戳',
  PRIMARY KEY (`Id`),
  UNIQUE KEY `CategoryId` (`CategoryId`,`Name`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='属性表';

ALTER TABLE `attribute_list`
MODIFY COLUMN `ContentType`  enum('INT','STRING','REGEX') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'INT' COMMENT '内容类型' AFTER `Alias`;

ALTER TABLE `attribute_list` CHANGE ContentType ContentType ENUM('INT','STRING','REGEX','FLOAT') NOT NULL DEFAULT "INT";
-- ----------------------------
-- Table structure for category_list
-- ----------------------------
DROP TABLE IF EXISTS `category_list`;
CREATE TABLE `category_list` (
  `Id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增主键',
  `Name` varchar(255) NOT NULL DEFAULT '' COMMENT '类型名',
  `Alias` varchar(255) NOT NULL DEFAULT '' COMMENT '别名',
  `Status` enum('ACTIVE','INACTIVE') NOT NULL DEFAULT 'ACTIVE' COMMENT '状态',
  `AddTime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `UpdateTime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '更新时间',
  `Timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '系统更新时间戳',
  PRIMARY KEY (`Id`),
  UNIQUE KEY `Name` (`Name`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='类型表';

ALTER TABLE `category_list`
ADD COLUMN `Script`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '处理脚本' AFTER `Alias`;

-- ----------------------------
-- Table structure for batch_list
-- ----------------------------
DROP TABLE IF EXISTS `batch_list`;
CREATE TABLE `batch_list` (
  `Id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增主键',
  `Name` varchar(255) NOT NULL DEFAULT '' COMMENT '快捷运行时间名',
  `Alias` varchar(255) NOT NULL DEFAULT '' COMMENT '别名',
  `Content` varchar(255) NOT NULL DEFAULT '' COMMENT 'cron时间串',
  `Status` enum('ACTIVE','INACTIVE') NOT NULL DEFAULT 'ACTIVE' COMMENT '状态',
  `AddTime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `UpdateTime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '更新时间',
  `Timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '系统更新时间戳',
  PRIMARY KEY (`Id`),
  UNIQUE KEY `Name` (`Name`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='时间快捷选项表';

ALTER TABLE `batch_list`
ADD COLUMN `Throughput`  int(11) NOT NULL DEFAULT 100 COMMENT '吞吐量-批次处理数据每次处理量' AFTER `Content`;

ALTER TABLE `batch_list`
CHANGE COLUMN `Content` `Crontime`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'cron时间串' AFTER `Alias`;

-- ----------------------------
-- Table structure for log_list
-- ----------------------------
DROP TABLE IF EXISTS `log_list`;
CREATE TABLE `log_list` (
  `Id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增主键',
  `Type` enum('SET','RUN','NOTIFY','OTHER') NOT NULL DEFAULT 'OTHER' COMMENT '日志类型',
  `Name` varchar(255) NOT NULL DEFAULT '' COMMENT 'name',
  `Action` varchar(255) NOT NULL DEFAULT '' COMMENT '动作',
  `Content` text NOT NULL COMMENT '内容',
  `HasAlert` enum('NO','YES') NOT NULL DEFAULT 'NO' COMMENT '是否产生预警',
  `AddTime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `UpdateTime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '更新时间',
  `Timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '系统更新时间戳',
  PRIMARY KEY (`Id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='日志列表';

ALTER TABLE `log_list`
ADD COLUMN `Genre`  enum('RECORD','ATTRIBUTE','CATEGORY','OTHER') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'OTHER' COMMENT '记录类型' AFTER `Id`,
ADD COLUMN `MapId`  int(11) NOT NULL DEFAULT 0 COMMENT 'mapid' AFTER `Genre`,
ADD INDEX (`MapId`) USING BTREE ;

-- ----------------------------
-- Table structure for mail_list
-- ----------------------------
DROP TABLE IF EXISTS `mail_list`;
CREATE TABLE `mail_list` (
  `Id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增主键',
  `Name` varchar(255) NOT NULL DEFAULT '' COMMENT 'name',
  `Mail` varchar(255) NOT NULL DEFAULT '' COMMENT 'mail',
  `Status` enum('ACTIVE','INACTIVE') NOT NULL DEFAULT 'ACTIVE' COMMENT '状态',
  `AddTime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `UpdateTime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '更新时间',
  `Timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '系统更新时间戳',
  PRIMARY KEY (`Id`),
  UNIQUE KEY `Mail` (`Mail`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='邮件列表';

-- ----------------------------
-- Table structure for record_list
-- ----------------------------
DROP TABLE IF EXISTS `record_list`;
CREATE TABLE `record_list` (
  `Id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增主键',
  `Name` varchar(255) NOT NULL DEFAULT '' COMMENT 'name',
  `Description` text NOT NULL COMMENT '描述',
  `CategoryId` int(11) NOT NULL DEFAULT '0' COMMENT '分类id',
  `Content` text NOT NULL COMMENT '内容（json格式）',
  `CronTimeId` int(11) NOT NULL DEFAULT '0' COMMENT '快捷时间id',
  `NotifyType` enum('MAIL') NOT NULL DEFAULT 'MAIL' COMMENT '推送类型',
  `NotifyObject` text NOT NULL COMMENT '推送对象（json格式）',
  `RunStatus` enum('PENDING','PROCESSING','RESLOVED') NOT NULL DEFAULT 'PENDING' COMMENT '处理状态',
  `Status` enum('ACTIVE','INACTIVE') NOT NULL DEFAULT 'ACTIVE' COMMENT '状态',
  `AddTime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `UpdateTime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '更新时间',
  `Timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '系统更新时间戳',
  PRIMARY KEY (`Id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='记录列表';

ALTER TABLE `record_list`
ADD COLUMN `StartTime`  datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '开始时间' AFTER `Status`,
ADD COLUMN `EndTime`  datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '结束时间' AFTER `StartTime`;

ALTER TABLE `record_list`
CHANGE COLUMN `CronTimeId` `batch`  int(11) NOT NULL DEFAULT 0 COMMENT 'cron批次，0代表单独cron处理，其他对应批处理' AFTER `Content`,
ADD COLUMN `CronTime`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'cron时间' AFTER `Content`;

ALTER TABLE `record_list`
CHANGE COLUMN `batch` `Batch`  int(11) NOT NULL DEFAULT 0 COMMENT 'cron批次，0代表单独cron处理，其他对应批处理' AFTER `CronTime`;

ALTER TABLE `record_list`
MODIFY COLUMN `Batch`  int(11) NOT NULL DEFAULT 0 COMMENT 'cron批次，0代表单独cron处理；其他表示批处理对应batch_list里id' AFTER `CronTime`,
ADD COLUMN `ProjectId`  int(11) NOT NULL DEFAULT 0 COMMENT '所属项目' AFTER `Description`,
ADD COLUMN `MonitorCount`  int(11) NOT NULL DEFAULT 0 COMMENT '监控次数' AFTER `NotifyObject`,
ADD COLUMN `AlertCount`  int(11) NOT NULL DEFAULT 0 COMMENT '预警次数' AFTER `MonitorCount`,
ADD COLUMN `SeriesAlertCount`  int(11) NOT NULL DEFAULT 0 COMMENT '连续预警次数' AFTER `AlertCount`,
ADD COLUMN `AlertLimit`  int(11) NOT NULL DEFAULT 10 COMMENT '预警上限，超过上限，不产生预警' AFTER `SeriesAlertCount`,
ADD COLUMN `LastMonitorTime`  datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '最近监控时间' AFTER `Status`,
ADD COLUMN `LastAlertTime`  datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '最近预警时间' AFTER `LastMonitorTime`;

DROP TABLE IF EXISTS `project_list`;
CREATE TABLE `project_list` (
  `Id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增主键',
  `Name` varchar(255) NOT NULL DEFAULT '' COMMENT '项目名',
  `Status` enum('ACTIVE','INACTIVE') NOT NULL DEFAULT 'INACTIVE' COMMENT '状态',
  `AddTime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `UpdateTime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '更新时间',
  `Timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '时间戳',
  PRIMARY KEY (`Id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET FOREIGN_KEY_CHECKS=1;
