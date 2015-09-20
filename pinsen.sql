-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: 2015-09-18 17:21:56
-- 服务器版本： 5.6.21
-- PHP Version: 5.5.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `pinsen`
--

-- --------------------------------------------------------

--
-- 表的结构 `ts_action`
--

CREATE TABLE IF NOT EXISTS `ts_action` (
`id` int(11) unsigned NOT NULL COMMENT '主键',
  `name` char(30) NOT NULL DEFAULT '' COMMENT '行为唯一标识',
  `title` char(80) NOT NULL DEFAULT '' COMMENT '行为说明',
  `remark` char(140) NOT NULL DEFAULT '' COMMENT '行为描述',
  `rule` text NOT NULL COMMENT '行为规则',
  `type` tinyint(2) unsigned NOT NULL DEFAULT '1' COMMENT '类型',
  `status` tinyint(2) NOT NULL DEFAULT '0' COMMENT '状态',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '修改时间'
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='系统行为表';

--
-- 转存表中的数据 `ts_action`
--

INSERT INTO `ts_action` (`id`, `name`, `title`, `remark`, `rule`, `type`, `status`, `update_time`) VALUES
(1, 'user_login', '用户登录', '积分+10，每天一次', 'table:member|field:score|condition:uid={$self} AND status>-1|rule:score+10|cycle:24|max:1;', 2, 1, 1383295068),
(2, 'add_article', '发布文章', '积分+5，每天上限5次', 'table:member|field:score|condition:uid={$self}|rule:score+5|cycle:24|max:5', 1, 0, 1380173180),
(3, 'review', '评论', '评论积分+1，无限制', 'table:member|field:score|condition:uid={$self}|rule:score+1', 2, 1, 1383285646),
(4, 'add_document_blog', '发表博客', '积分+10，每天上限5次', 'table:member|field:score|condition:uid={$self}|rule:score+10|cycle:24|max:5', 2, 1, 1383285637),
(5, 'add_document_topic', '发表讨论', '积分+5，每天上限10次', 'table:member|field:score|condition:uid={$self}|rule:score+5|cycle:24|max:10', 2, 1, 1383285551),
(6, 'update_config', '更新配置', '新增或修改或删除配置', '', 1, 1, 1383294988),
(7, 'update_model', '更新模型', '新增或修改模型', '', 1, 1, 1383295057),
(8, 'update_attribute', '更新属性', '新增或更新或删除属性', '', 1, 1, 1383295963),
(9, 'update_channel', '更新导航', '新增或修改或删除导航', '', 1, 1, 1383296301),
(10, 'update_menu', '更新菜单', '新增或修改或删除菜单', '', 1, 1, 1383296392),
(11, 'update_category', '更新分类', '新增或修改或删除分类', '', 1, 1, 1383296765);

-- --------------------------------------------------------

--
-- 表的结构 `ts_action_log`
--

CREATE TABLE IF NOT EXISTS `ts_action_log` (
`id` int(10) unsigned NOT NULL COMMENT '主键',
  `action_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '行为id',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '执行用户id',
  `action_ip` bigint(20) NOT NULL COMMENT '执行行为者ip',
  `model` varchar(50) NOT NULL DEFAULT '' COMMENT '触发行为的表',
  `record_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '触发行为的数据id',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '日志备注',
  `status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '状态',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '执行行为的时间'
) ENGINE=MyISAM AUTO_INCREMENT=30 DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED COMMENT='行为日志表';

--
-- 转存表中的数据 `ts_action_log`
--

INSERT INTO `ts_action_log` (`id`, `action_id`, `user_id`, `action_ip`, `model`, `record_id`, `remark`, `status`, `create_time`) VALUES
(1, 1, 1, 2130706433, 'member', 1, '操作url：/onethink/index.php?s=/admin/public/login.html', 1, 1433383053),
(2, 1, 3, 1902544084, 'member', 3, '操作url：/index.php?s=/home/user/login.html', 1, 1436357167),
(3, 1, 4, 1902544084, 'member', 4, '操作url：/index.php?s=/home/user/login.html', 1, 1436357223),
(4, 1, 3, 1901539918, 'member', 3, '操作url：/index.php?s=/home/user/login.html', 1, 1436406624),
(5, 1, 3, 2032393506, 'member', 3, '操作url：/index.php?s=/admin/public/login.html', 1, 1436407093),
(6, 1, 1, 2032393506, 'member', 1, '操作url：/index.php?s=/admin/public/login.html', 1, 1436407192),
(7, 1, 4, 455489578, 'member', 4, '操作url：/index.php?s=/admin/public/login.html', 1, 1436883998),
(8, 1, 1, 1884956101, 'member', 1, '操作url：/index.php?s=/admin/public/login.html', 1, 1438179604),
(9, 1, 1, 989565464, 'member', 1, '操作url：/index.php?s=/admin/public/login.html', 1, 1438180017),
(10, 1, 1, 1884956157, 'member', 1, '操作url：/index.php?s=/admin/public/login.html', 1, 1438261646),
(11, 1, 1, 1884956157, 'member', 1, '操作url：/index.php?s=/home/user/login.html', 1, 1438261700),
(12, 1, 1, 1901914046, 'member', 1, '操作url：/index.php?s=/admin/public/login.html', 1, 1438529519),
(13, 10, 1, 1951212546, 'Menu', 118, '操作url：/index.php?s=/admin/menu/add/pid/2.html', 1, 1438529653),
(14, 10, 1, 455489594, 'Menu', 119, '操作url：/index.php?s=/admin/menu/add/pid/118.html', 1, 1438529675),
(15, 10, 1, 1901914046, 'Menu', 120, '操作url：/index.php?s=/admin/menu/add/pid/118.html', 1, 1438529692),
(16, 1, 1, 1902238366, 'member', 1, '操作url：/index.php?s=/admin/public/login.html', 1, 1439005537),
(17, 1, 1, 1902238366, 'member', 1, '操作url：/index.php?s=/admin/public/login.html', 1, 1439005563),
(18, 1, 1, 1902238366, 'member', 1, '操作url：/index.php?s=/admin/public/login.html', 1, 1439018905),
(19, 1, 1, 455520665, 'member', 1, '操作url：/index.php?s=/admin/public/login.html', 1, 1439041554),
(20, 1, 1, 714934870, 'member', 1, '操作url：/index.php?s=/admin/public/login.html', 1, 1439045308),
(21, 1, 1, 2742908385, 'member', 1, '操作url：/index.php?s=/admin/public/login.html', 1, 1439045851),
(22, 1, 1, 1947736967, 'member', 1, '操作url：/index.php?s=/admin/public/login.html', 1, 1439129344),
(23, 1, 1, 989767765, 'member', 1, '操作url：/index.php?s=/admin/public/login.html', 1, 1439217983),
(24, 1, 1, 1901558258, 'member', 1, '操作url：/index.php?s=/home/user/login.html', 1, 1439964806),
(25, 1, 1, 1901558258, 'member', 1, '操作url：/index.php?s=/admin/public/login.html', 1, 1439964817),
(26, 1, 1, 714934862, 'member', 1, '操作url：/index.php?s=/admin/public/login.html', 1, 1439993766),
(27, 1, 1, 989565603, 'member', 1, '操作url：/index.php?s=/admin/public/login.html', 1, 1440247932),
(28, 1, 1, 1884956133, 'member', 1, '操作url：/index.php?s=/admin/public/login.html', 1, 1441555074),
(29, 1, 1, 989565615, 'member', 1, '操作url：/index.php?s=/admin/public/login.html', 1, 1442150817);

-- --------------------------------------------------------

--
-- 表的结构 `ts_activity`
--

CREATE TABLE IF NOT EXISTS `ts_activity` (
  `id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL COMMENT '标题',
  `start_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '开始时间',
  `end_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '结束时间',
  `pharma_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '药厂id',
  `act_ico` varchar(200) NOT NULL COMMENT '图标',
  `sort` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `content` text NOT NULL COMMENT '内容',
  `drug_store_id` varchar(500) NOT NULL COMMENT '药店id（多个分店用,分割）',
  `store_id` int(11) NOT NULL DEFAULT '0' COMMENT '药店id',
  `sub_title` varchar(50) NOT NULL DEFAULT '' COMMENT '活动副标题',
  `wap_link` varchar(255) NOT NULL DEFAULT '' COMMENT '活动详情瓦wap地址链接'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='活动';

--
-- 转存表中的数据 `ts_activity`
--

INSERT INTO `ts_activity` (`id`, `title`, `start_time`, `end_time`, `pharma_id`, `act_ico`, `sort`, `content`, `drug_store_id`, `store_id`, `sub_title`, `wap_link`) VALUES
(1, '测试活动', 0, 0, 1, 'http://img6.cache.netease.com/ent/2015/7/27/2015072717473376879.jpg', 0, '这是手动插入的测试活动数据', '1', 1, '活动子标题', 'http://www.163.com'),
(2, '测试活动2', 0, 0, 1, 'http://img6.cache.netease.com/auto/2015/7/27/20150727114559c9ea6.jpg', 0, '这是测试活动22222', '1', 1, '活动子标题2', 'http://www.baidu.com');

-- --------------------------------------------------------

--
-- 表的结构 `ts_addons`
--

CREATE TABLE IF NOT EXISTS `ts_addons` (
`id` int(10) unsigned NOT NULL COMMENT '主键',
  `name` varchar(40) NOT NULL COMMENT '插件名或标识',
  `title` varchar(20) NOT NULL DEFAULT '' COMMENT '中文名',
  `description` text COMMENT '插件描述',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态',
  `config` text COMMENT '配置',
  `author` varchar(40) DEFAULT '' COMMENT '作者',
  `version` varchar(20) DEFAULT '' COMMENT '版本号',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '安装时间',
  `has_adminlist` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否有后台列表'
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8 COMMENT='插件表';

--
-- 转存表中的数据 `ts_addons`
--

INSERT INTO `ts_addons` (`id`, `name`, `title`, `description`, `status`, `config`, `author`, `version`, `create_time`, `has_adminlist`) VALUES
(15, 'EditorForAdmin', '后台编辑器', '用于增强整站长文本的输入和显示', 1, '{"editor_type":"4","editor_wysiwyg":"1","editor_height":"500px","editor_resize_type":"1"}', 'thinkphp', '0.1', 1383126253, 0),
(2, 'SiteStat', '站点统计信息', '统计站点的基础信息', 1, '{"title":"\\u7cfb\\u7edf\\u4fe1\\u606f","width":"1","display":"1","status":"0"}', 'thinkphp', '0.1', 1379512015, 0),
(3, 'DevTeam', '开发团队信息', '开发团队成员信息', 1, '{"title":"OneThink\\u5f00\\u53d1\\u56e2\\u961f","width":"2","display":"1"}', 'thinkphp', '0.1', 1379512022, 0),
(4, 'SystemInfo', '系统环境信息', '用于显示一些服务器的信息', 1, '{"title":"\\u7cfb\\u7edf\\u4fe1\\u606f","width":"2","display":"1"}', 'thinkphp', '0.1', 1379512036, 0),
(5, 'Editor', '前台编辑器', '用于增强整站长文本的输入和显示', 1, '{"editor_type":"2","editor_wysiwyg":"1","editor_height":"300px","editor_resize_type":"1"}', 'thinkphp', '0.1', 1379830910, 0),
(6, 'Attachment', '附件', '用于文档模型上传附件', 1, 'null', 'thinkphp', '0.1', 1379842319, 1);

-- --------------------------------------------------------

--
-- 表的结构 `ts_advertise`
--

CREATE TABLE IF NOT EXISTS `ts_advertise` (
`id` int(10) unsigned NOT NULL,
  `title` varchar(100) NOT NULL DEFAULT '' COMMENT '标题',
  `ad_ico` varchar(200) NOT NULL DEFAULT '' COMMENT '广告图标',
  `sort` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `content` text COMMENT '内容',
  `is_index` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否推荐到首页',
  `create_time` int(10) unsigned NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='广告';

--
-- 转存表中的数据 `ts_advertise`
--

INSERT INTO `ts_advertise` (`id`, `title`, `ad_ico`, `sort`, `content`, `is_index`, `create_time`) VALUES
(1, '广告1', 'test1.jpg', 1, '广告1广告1广告1广告1广告1', 0, 0),
(2, '广告2', 'test2.jpg', 2, '广告2广告2广告2', 1, 0),
(3, '广告1', 'test1.jpg', 1, '广告1广告1广告1广告1广告1', 0, 0),
(4, '广告2', 'test2.jpg', 2, '广告2广告2广告2', 1, 0);

-- --------------------------------------------------------

--
-- 表的结构 `ts_attachment`
--

CREATE TABLE IF NOT EXISTS `ts_attachment` (
`id` int(10) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `title` char(30) NOT NULL DEFAULT '' COMMENT '附件显示名',
  `type` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '附件类型',
  `source` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '资源ID',
  `record_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '关联记录ID',
  `download` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '下载次数',
  `size` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '附件大小',
  `dir` int(12) unsigned NOT NULL DEFAULT '0' COMMENT '上级目录ID',
  `sort` int(8) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='附件表\r\n@author   麦当苗儿\r\n@version  2013-06-19';

-- --------------------------------------------------------

--
-- 表的结构 `ts_attribute`
--

CREATE TABLE IF NOT EXISTS `ts_attribute` (
`id` int(10) unsigned NOT NULL,
  `name` varchar(30) NOT NULL DEFAULT '' COMMENT '字段名',
  `title` varchar(100) NOT NULL DEFAULT '' COMMENT '字段注释',
  `field` varchar(100) NOT NULL DEFAULT '' COMMENT '字段定义',
  `type` varchar(20) NOT NULL DEFAULT '' COMMENT '数据类型',
  `value` varchar(100) NOT NULL DEFAULT '' COMMENT '字段默认值',
  `remark` varchar(100) NOT NULL DEFAULT '' COMMENT '备注',
  `is_show` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否显示',
  `extra` varchar(255) NOT NULL DEFAULT '' COMMENT '参数',
  `model_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '模型id',
  `is_must` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否必填',
  `status` tinyint(2) NOT NULL DEFAULT '0' COMMENT '状态',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间'
) ENGINE=MyISAM AUTO_INCREMENT=187 DEFAULT CHARSET=utf8 COMMENT='模型属性表\r\n@author huajie';

--
-- 转存表中的数据 `ts_attribute`
--

INSERT INTO `ts_attribute` (`id`, `name`, `title`, `field`, `type`, `value`, `remark`, `is_show`, `extra`, `model_id`, `is_must`, `status`, `update_time`, `create_time`) VALUES
(1, 'uid', '用户ID', 'int(10) unsigned NOT NULL ', 'num', '0', '', 0, '', 1, 0, 1, 1384508362, 1383891233),
(2, 'name', '标识', 'char(40) NOT NULL ', 'string', '''''', '同一根节点下标识不重复', 1, '', 1, 0, 1, 1383894743, 1383891233),
(3, 'title', '标题', 'char(80) NOT NULL ', 'string', '''''', '文档标题', 1, '', 1, 0, 1, 1383894778, 1383891233),
(4, 'category_id', '所属分类', 'int(10) unsigned NOT NULL ', 'string', '', '', 0, '', 1, 0, 1, 1384508336, 1383891233),
(5, 'description', '描述', 'char(140) NOT NULL ', 'textarea', '''''', '', 1, '', 1, 0, 1, 1383894927, 1383891233),
(6, 'root', '根节点', 'int(10) unsigned NOT NULL ', 'num', '0', '该文档的顶级文档编号', 0, '', 1, 0, 1, 1384508323, 1383891233),
(7, 'pid', '所属ID', 'int(10) unsigned NOT NULL ', 'num', '0', '父文档编号', 0, '', 1, 0, 1, 1384508543, 1383891233),
(8, 'model_id', '内容模型ID', 'tinyint(3) unsigned NOT NULL ', 'num', '0', '该文档所对应的模型', 0, '', 1, 0, 1, 1384508350, 1383891233),
(9, 'type', '内容类型', 'tinyint(3) unsigned NOT NULL ', 'select', '2', '', 1, '1:目录\r\n2:主题\r\n3:段落', 1, 0, 1, 1384511157, 1383891233),
(10, 'position', '推荐位', 'smallint(5) unsigned NOT NULL ', 'checkbox', '0', '多个推荐则将其推荐值相加', 1, '1:列表推荐\r\n2:频道页推荐\r\n4:首页推荐', 1, 0, 1, 1383895640, 1383891233),
(11, 'link_id', '外链', 'int(10) unsigned NOT NULL ', 'num', '0', '0-非外链，大于0-外链ID,需要函数进行链接与编号的转换', 1, '', 1, 0, 1, 1383895757, 1383891233),
(12, 'cover_id', '封面', 'int(10) unsigned NOT NULL ', 'picture', '0', '0-无封面，大于0-封面图片ID，需要函数处理', 1, '', 1, 0, 1, 1384147827, 1383891233),
(13, 'display', '可见性', 'tinyint(3) unsigned NOT NULL ', 'radio', '0', '', 1, '0:不可见\r\n1:所有人可见', 1, 0, 1, 1383896023, 1383891233),
(14, 'dateline', '截至时间', 'int(10) unsigned NOT NULL ', 'datetime', '0', '0-永久有效', 1, '', 1, 0, 1, 1383895812, 1383891233),
(15, 'attach', '附件数量', 'tinyint(3) unsigned NOT NULL ', 'num', '0', '', 1, '', 1, 0, 1, 1383895825, 1383891233),
(16, 'view', '浏览量', 'int(10) unsigned NOT NULL ', 'num', '0', '', 1, '', 1, 0, 1, 1383895835, 1383891233),
(17, 'comment', '评论数', 'int(10) unsigned NOT NULL ', 'num', '0', '', 1, '', 1, 0, 1, 1383895846, 1383891233),
(18, 'extend', '扩展统计字段', 'int(10) unsigned NOT NULL ', 'num', '0', '根据需求自行使用', 0, '', 1, 0, 1, 1384508264, 1383891233),
(19, 'level', '优先级', 'int(10) unsigned NOT NULL ', 'num', '0', '越高排序越靠前', 1, '', 1, 0, 1, 1383895894, 1383891233),
(20, 'create_time', '创建时间', 'int(10) unsigned NOT NULL ', 'datetime', '0', '', 1, '', 1, 0, 1, 1383895903, 1383891233),
(21, 'update_time', '更新时间', 'int(10) unsigned NOT NULL ', 'datetime', '0', '', 0, '', 1, 0, 1, 1384508277, 1383891233),
(22, 'status', '数据状态', 'tinyint(4) NOT NULL ', 'radio', '0', '', 0, '-1:删除\r\n0:禁用\r\n1:正常\r\n2:待审核\r\n3:草稿', 1, 0, 1, 1384508496, 1383891233),
(23, 'parse', '内容解析类型', 'tinyint(3) unsigned NOT NULL ', 'select', '0', '', 0, '0:html\r\n1:ubb\r\n2:markdown', 2, 0, 1, 1384511049, 1383891243),
(24, 'content', '文章内容', 'text NOT NULL ', 'editor', '', '', 1, '', 2, 0, 1, 1383896225, 1383891243),
(25, 'template', '详情页显示模板', 'varchar(100) NOT NULL ', 'string', '''''', '参照display方法参数的定义', 1, '', 2, 0, 1, 1383896190, 1383891243),
(26, 'bookmark', '收藏数', 'int(10) unsigned NOT NULL ', 'num', '0', '', 1, '', 2, 0, 1, 1383896103, 1383891243),
(27, 'parse', '内容解析类型', 'tinyint(3) unsigned NOT NULL ', 'select', '0', '', 1, '0:html\r\n1:ubb\r\n2:markdown', 3, 0, 1, 1383896487, 1383891252),
(28, 'content', '下载详细描述', 'text NOT NULL ', 'editor', '', '', 1, '', 3, 0, 1, 1383896438, 1383891252),
(29, 'template', '详情页显示模板', 'varchar(100) NOT NULL ', 'string', '''''', '', 1, '', 3, 0, 1, 1383896429, 1383891252),
(30, 'file_id', '文件ID', 'int(10) unsigned NOT NULL ', 'file', '0', '需要函数处理', 1, '', 3, 0, 1, 1383896415, 1383891252),
(31, 'download', '下载次数', 'int(10) unsigned NOT NULL ', 'num', '0', '', 1, '', 3, 0, 1, 1383896380, 1383891252),
(32, 'size', '文件大小', 'bigint(20) unsigned NOT NULL ', 'num', '0', '单位bit', 1, '', 3, 0, 1, 1383896371, 1383891252),
(33, 'name', '行为唯一标识', 'char(30) NOT NULL ', 'string', '''''', '标识唯一', 1, '', 4, 0, 1, 1383897155, 1383891262),
(34, 'title', '行为说明', 'char(80) NOT NULL ', 'textarea', '''''', '', 1, '', 4, 0, 1, 1383897133, 1383891262),
(35, 'remark', '行为描述', 'char(140) NOT NULL ', 'textarea', '''''', '', 1, '', 4, 0, 1, 1383897032, 1383891262),
(36, 'rule', '行为规则', 'text NOT NULL ', 'textarea', '', '', 1, '', 4, 0, 1, 1383897011, 1383891262),
(37, 'type', '类型', 'tinyint(2) unsigned NOT NULL ', 'select', '1', '', 1, '1:系统\r\n2:用户', 4, 0, 1, 1383896955, 1383891262),
(38, 'status', '状态', 'tinyint(2) NOT NULL ', 'radio', '0', '', 1, '-1:已删除\r\n0:禁用\r\n1:正常', 4, 0, 1, 1383896868, 1383891262),
(39, 'update_time', '修改时间', 'int(11) unsigned NOT NULL ', 'datetime', '0', '', 1, '', 4, 0, 1, 1383896756, 1383891262),
(40, 'action_id', '行为id', 'int(10) unsigned NOT NULL ', 'num', '0', '', 1, '', 5, 0, 1, 1383897599, 1383891340),
(41, 'user_id', '执行用户id', 'int(10) unsigned NOT NULL ', 'num', '0', '', 1, '', 5, 0, 1, 1383897587, 1383891340),
(42, 'action_ip', '执行行为者ip', 'bigint(20) NOT NULL ', 'string', '', '', 1, '', 5, 0, 1, 1383897570, 1383891341),
(43, 'model', '触发行为的表', 'varchar(50) NOT NULL ', 'string', '''''', '', 1, '', 5, 0, 1, 1383897523, 1383891341),
(44, 'record_id', '触发行为的数据id', 'int(10) unsigned NOT NULL ', 'num', '0', '', 1, '', 5, 0, 1, 1383897512, 1383891341),
(45, 'remark', '日志备注', 'varchar(255) NOT NULL ', 'textarea', '''''', '', 1, '', 5, 0, 1, 1383897352, 1383891341),
(46, 'status', '状态', 'tinyint(2) NOT NULL ', 'checkbox', '1', '', 1, '-1:已删除\r\n0:禁用\r\n1:正常', 5, 0, 1, 1383897331, 1383891341),
(47, 'create_time', '执行行为的时间', 'int(10) unsigned NOT NULL ', 'datetime', '0', '', 1, '', 5, 0, 1, 1383897274, 1383891341),
(48, 'name', '插件名或标识', 'varchar(40) NOT NULL ', 'string', '', '区分大小写', 1, '', 6, 0, 1, 1383898041, 1383891396),
(49, 'title', '中文名', 'varchar(20) NOT NULL ', 'string', '''''', '', 1, '', 6, 0, 1, 1383898024, 1383891396),
(50, 'description', '插件描述', 'text NULL ', 'editor', '', '', 1, '', 6, 0, 1, 1383897787, 1383891396),
(51, 'status', '状态', 'tinyint(1) NOT NULL ', 'radio', '1', '', 1, '1:启用\r\n0:禁用\r\n-1:损坏', 6, 0, 1, 1383897771, 1383891396),
(52, 'config', '配置', 'text NULL ', 'textarea', '', '序列化存放', 1, '', 6, 0, 1, 1383897747, 1383891396),
(53, 'author', '作者', 'varchar(40) NULL ', 'string', '''''', '', 1, '', 6, 0, 1, 1383897731, 1383891396),
(54, 'version', '版本号', 'varchar(20) NULL ', 'string', '''''', '', 1, '', 6, 0, 1, 1383897715, 1383891396),
(55, 'create_time', '安装时间', 'int(10) unsigned NOT NULL ', 'datetime', '0', '', 1, '', 6, 0, 1, 1383897704, 1383891396),
(56, 'has_adminlist', '是否有后台列表', 'tinyint(1) unsigned NOT NULL ', 'bool', '0', '', 1, '1:有后台列表\r\n0:无后台列表', 6, 0, 1, 1383897682, 1383891396),
(57, 'uid', '用户ID', 'int(10) unsigned NOT NULL ', 'num', '0', '', 1, '', 7, 0, 1, 1383900457, 1383891410),
(58, 'title', '附件显示名', 'char(30) NOT NULL ', 'string', '''''', '', 1, '', 7, 0, 1, 1383900447, 1383891410),
(59, 'type', '附件类型', 'tinyint(3) unsigned NOT NULL ', 'select', '0', '', 1, '0:目录\r\n1:外链\r\n2:文件', 7, 0, 1, 1383900433, 1383891410),
(60, 'source', '资源ID', 'int(10) unsigned NOT NULL ', 'num', '0', '0-目录， 大于0-当资源为文件时其值为file_id,当资源为外链时其值为link_id', 1, '', 7, 0, 1, 1383900405, 1383891410),
(61, 'record_id', '关联记录ID', 'int(10) unsigned NOT NULL ', 'num', '0', '', 1, '', 7, 0, 1, 1383900384, 1383891410),
(62, 'download', '下载次数', 'int(10) unsigned NOT NULL ', 'num', '0', '', 1, '', 7, 0, 1, 1383900376, 1383891410),
(63, 'size', '附件大小', 'bigint(20) unsigned NOT NULL ', 'num', '0', '当附件为目录或外链时，该值为0', 1, '', 7, 0, 1, 1383900367, 1383891410),
(64, 'dir', '上级目录ID', 'int(12) unsigned NOT NULL ', 'num', '0', '0-根目录', 1, '', 7, 0, 1, 1383900283, 1383891410),
(65, 'sort', '排序', 'int(8) unsigned NOT NULL ', 'num', '0', '', 1, '', 7, 0, 1, 1383900264, 1383891410),
(66, 'create_time', '创建时间', 'int(10) unsigned NOT NULL ', 'datetime', '0', '', 1, '', 7, 0, 1, 1383900256, 1383891410),
(67, 'update_time', '更新时间', 'int(11) unsigned NOT NULL ', 'datetime', '0', '', 1, '', 7, 0, 1, 1383900249, 1383891410),
(68, 'status', '状态', 'tinyint(1) NOT NULL ', 'select', '0', '', 1, '-1:已删除\r\n0:禁用\r\n1:正常', 7, 0, 1, 1383900239, 1383891410),
(69, 'name', '字段名', 'varchar(30) NOT NULL ', 'string', '''''', '', 1, '', 8, 0, 1, 1383901456, 1383891441),
(70, 'title', '字段注释', 'varchar(100) NOT NULL ', 'textarea', '''''', '', 1, '', 8, 0, 1, 1383901439, 1383891441),
(71, 'field', '字段定义', 'varchar(100) NOT NULL ', 'string', '''''', '', 1, '', 8, 0, 1, 1383901414, 1383891441),
(72, 'type', '数据类型', 'varchar(20) NOT NULL ', 'string', '''''', '用于表单展示', 1, '', 8, 0, 1, 1383901399, 1383891441),
(73, 'value', '字段默认值', 'varchar(100) NOT NULL ', 'string', '''''', '', 1, '', 8, 0, 1, 1383901261, 1383891441),
(74, 'remark', '备注', 'varchar(100) NOT NULL ', 'textarea', '''''', '', 1, '', 8, 0, 1, 1383901247, 1383891441),
(75, 'is_show', '是否显示', 'tinyint(1) unsigned NOT NULL ', 'select', '1', '', 1, '0:不显示\r\n1:始终显示\r\n2:新增时显示\r\n3:编辑时显示', 8, 0, 1, 1383901027, 1383891441),
(76, 'extra', '参数', 'varchar(255) NOT NULL ', 'textarea', '''''', '表单显示', 1, '', 8, 0, 1, 1383900964, 1383891441),
(77, 'model_id', '模型id', 'int(10) unsigned NOT NULL ', 'num', '0', '', 1, '', 8, 0, 1, 1383900862, 1383891441),
(78, 'is_must', '是否必填', 'tinyint(1) unsigned NOT NULL ', 'bool', '0', '', 1, '0:否\r\n1:是', 8, 0, 1, 1383900852, 1383891441),
(79, 'status', '状态', 'tinyint(2) NOT NULL ', 'select', '0', '', 1, '-1:已删除\r\n0:禁用\r\n1:正常', 8, 0, 1, 1383900822, 1383891441),
(80, 'update_time', '更新时间', 'int(11) unsigned NOT NULL ', 'datetime', '0', '', 1, '', 8, 0, 1, 1383900771, 1383891441),
(81, 'create_time', '创建时间', 'int(11) unsigned NOT NULL ', 'datetime', '0', '', 1, '', 8, 0, 1, 1383900759, 1383891441),
(82, 'name', '标志', 'varchar(30) NOT NULL ', 'string', '', '', 1, '', 9, 0, 1, 1383901549, 1383891453),
(83, 'title', '标题', 'varchar(50) NOT NULL ', 'string', '', '', 1, '', 9, 0, 1, 1383891453, 1383891453),
(84, 'pid', '上级分类ID', 'int(10) unsigned NOT NULL ', 'num', '0', '', 1, '', 9, 0, 1, 1383901524, 1383891453),
(85, 'sort', '排序（同级有效）', 'int(10) unsigned NOT NULL ', 'num', '0', '', 1, '', 9, 0, 1, 1383902180, 1383891453),
(86, 'list_row', '列表每页行数', 'tinyint(3) unsigned NOT NULL ', 'num', '10', '', 1, '', 9, 0, 1, 1383902154, 1383891453),
(87, 'meta_title', 'SEO的网页标题', 'varchar(50) NOT NULL ', 'string', '''''', '', 1, '', 9, 0, 1, 1383902118, 1383891453),
(88, 'keywords', '关键字', 'varchar(255) NOT NULL ', 'textarea', '''''', '', 1, '', 9, 0, 1, 1383902106, 1383891453),
(89, 'description', '描述', 'varchar(255) NOT NULL ', 'textarea', '''''', '', 1, '', 9, 0, 1, 1383902089, 1383891453),
(90, 'template_index', '频道页模板', 'varchar(100) NOT NULL ', 'string', '', '', 1, '', 9, 0, 1, 1383891453, 1383891453),
(91, 'template_lists', '列表页模板', 'varchar(100) NOT NULL ', 'string', '', '', 1, '', 9, 0, 1, 1383891453, 1383891453),
(92, 'template_detail', '详情页模板', 'varchar(100) NOT NULL ', 'string', '', '', 1, '', 9, 0, 1, 1383891453, 1383891453),
(93, 'template_edit', '编辑页模板', 'varchar(100) NOT NULL ', 'string', '', '', 1, '', 9, 0, 1, 1383891453, 1383891453),
(94, 'model', '关联模型', 'varchar(100) NOT NULL ', 'string', '''''', '', 1, '', 9, 0, 1, 1383902070, 1383891453),
(95, 'type', '允许发布的内容类型', 'varchar(100) NOT NULL ', 'radio', '''''', '', 1, '1:目录\r\n2:主题\r\n3:段落', 9, 0, 1, 1383902026, 1383891453),
(96, 'link_id', '外链', 'int(10) unsigned NOT NULL ', 'num', '0', '0-非外链，大于0-外链ID', 1, '', 9, 0, 1, 1383902007, 1383891453),
(97, 'allow_publish', '是否允许发布内容', 'tinyint(3) unsigned NOT NULL ', 'radio', '0', '（0-不允许，1-允许）', 1, '0:不允许\r\n1:允许', 9, 0, 1, 1383900964, 1383891453),
(98, 'display', '可见性', 'tinyint(3) unsigned NOT NULL ', 'radio', '0', '', 1, '0:所有人可见\r\n1:管理员可见\r\n2:不可见', 9, 0, 1, 1383900863, 1383891453),
(99, 'reply', '是否允许回复', 'tinyint(3) unsigned NOT NULL ', 'radio', '0', '', 1, '1:允许\r\n0:不允许', 9, 0, 1, 1383900823, 1383891453),
(100, 'check', '发布的文章是否需要审核', 'tinyint(3) unsigned NOT NULL ', 'radio', '0', '0：不需要，1：需要', 1, '0:不需要\r\n1:需要', 9, 0, 1, 1383901976, 1383891453),
(101, 'reply_model', '', 'varchar(100) NOT NULL ', 'string', '''''', '', 1, '', 9, 0, 1, 1383901959, 1383891453),
(102, 'extend', '扩展设置', 'text NOT NULL ', 'textarea', '', 'JSON数据', 1, '', 9, 0, 1, 1383901931, 1383891453),
(103, 'create_time', '创建时间', 'int(10) unsigned NOT NULL ', 'datetime', '0', '', 1, '', 9, 0, 1, 1383900444, 1383891453),
(104, 'update_time', '更新时间', 'int(10) unsigned NOT NULL ', 'datetime', '0', '', 1, '', 9, 0, 1, 1383900436, 1383891453),
(105, 'status', '数据状态', 'tinyint(4) NOT NULL ', 'radio', '0', '-1-删除，0-禁用，1-正常，2-待审核', 1, '-1:删除\r\n0:禁用\r\n1:正常\r\n2:待审核', 9, 0, 1, 1383900393, 1383891453),
(106, 'pid', '上级频道ID', 'int(10) unsigned NOT NULL ', 'string', '0', '', 1, '', 10, 0, 1, 1383891460, 1383891460),
(107, 'title', '频道标题', 'char(30) NOT NULL ', 'string', '', '', 1, '', 10, 0, 1, 1383891460, 1383891460),
(108, 'url', '频道连接', 'char(100) NOT NULL ', 'string', '', '', 1, '', 10, 0, 1, 1383891460, 1383891460),
(109, 'sort', '导航排序', 'int(10) unsigned NOT NULL ', 'num', '0', '', 1, '', 10, 0, 1, 1383900256, 1383891460),
(110, 'create_time', '创建时间', 'int(10) unsigned NOT NULL ', 'datetime', '0', '', 1, '', 10, 0, 1, 1383900247, 1383891460),
(111, 'update_time', '更新时间', 'int(10) unsigned NOT NULL ', 'datetime', '0', '', 1, '', 10, 0, 1, 1383900239, 1383891460),
(112, 'status', '状态', 'tinyint(4) NOT NULL ', 'radio', '0', '', 1, '1:正常\r\n0:禁用', 10, 0, 1, 1383900228, 1383891460),
(113, 'name', '配置名称', 'varchar(30) NOT NULL ', 'string', '''''', '', 1, '', 11, 0, 1, 1383901902, 1383891466),
(114, 'type', '配置类型', 'tinyint(3) unsigned NOT NULL ', 'select', '0', '', 1, '0:数字\r\n1:字符\r\n2:文本\r\n3:数组\r\n4:枚举', 11, 0, 1, 1383901877, 1383891466),
(115, 'title', '配置说明', 'varchar(50) NOT NULL ', 'string', '''''', '', 1, '', 11, 0, 1, 1383901743, 1383891466),
(116, 'group', '配置分组', 'tinyint(3) unsigned NOT NULL ', 'radio', '0', '0-无分组，1-基本设置', 1, '0:无分组\r\n1:基本设置', 11, 0, 1, 1383901731, 1383891466),
(117, 'extra', '配置值', 'varchar(255) NOT NULL ', 'textarea', '''''', '', 1, '', 11, 0, 1, 1383901707, 1383891466),
(118, 'remark', '配置说明', 'varchar(100) NOT NULL ', 'textarea', '', '', 1, '', 11, 0, 1, 1383901666, 1383891466),
(119, 'create_time', '创建时间', 'int(10) unsigned NOT NULL ', 'datetime', '0', '', 1, '', 11, 0, 1, 1383899711, 1383891466),
(120, 'update_time', '更新时间', 'int(10) unsigned NOT NULL ', 'datetime', '0', '', 1, '', 11, 0, 1, 1383899704, 1383891466),
(121, 'status', '状态', 'tinyint(4) NOT NULL ', 'radio', '0', '', 1, '1:启用\r\n0:禁用', 11, 0, 1, 1383901650, 1383891466),
(122, 'value', '配置值', 'text NOT NULL ', 'textarea', '', '', 1, '', 11, 0, 1, 1383901634, 1383891466),
(123, 'sort', '排序', 'smallint(3) unsigned NOT NULL ', 'num', '0', '', 1, '', 11, 0, 1, 1383901610, 1383891467),
(124, 'name', '原始文件名', 'char(30) NOT NULL ', 'string', '''''', '', 1, '', 12, 0, 1, 1383902322, 1383891474),
(125, 'savename', '保存名称', 'char(20) NOT NULL ', 'string', '''''', '', 1, '', 12, 0, 1, 1383902308, 1383891474),
(126, 'savepath', '文件保存路径', 'char(30) NOT NULL ', 'string', '''''', '', 1, '', 12, 0, 1, 1383902297, 1383891474),
(127, 'ext', '文件后缀', 'char(5) NOT NULL ', 'string', '''''', '', 1, '', 12, 0, 1, 1383902285, 1383891474),
(128, 'mime', '文件mime类型', 'char(40) NOT NULL ', 'string', '''''', '', 1, '', 12, 0, 1, 1383902267, 1383891474),
(129, 'size', '文件大小', 'int(10) unsigned NOT NULL ', 'num', '0', '', 1, '', 12, 0, 1, 1383902259, 1383891474),
(130, 'md5', '文件md5', 'char(32) NOT NULL ', 'string', '''''', '', 1, '', 12, 0, 1, 1383902245, 1383891474),
(131, 'sha1', '文件 sha1编码', 'char(40) NOT NULL ', 'string', '''''', '', 1, '', 12, 0, 1, 1383902236, 1383891474),
(132, 'location', '文件保存位置', 'tinyint(3) unsigned NOT NULL ', 'checkbox', '0', '', 1, '0:本地\r\n1:FTP', 12, 0, 1, 1383899469, 1383891474),
(133, 'create_time', '上传时间', 'int(10) unsigned NOT NULL ', 'datetime', '', '', 1, '', 12, 0, 1, 1383899436, 1383891474),
(134, 'name', '钩子名称', 'varchar(40) NOT NULL ', 'string', '''''', '', 1, '', 13, 0, 1, 1383902516, 1383891480),
(135, 'description', '描述', 'text NOT NULL ', 'editor', '', '', 1, '', 13, 0, 1, 1383902505, 1383891480),
(136, 'type', '类型', 'tinyint(1) unsigned NOT NULL ', 'select', '1', '', 1, '1:Controller\r\n2:Widget', 13, 0, 1, 1383902484, 1383891480),
(137, 'update_time', '更新时间', 'int(10) unsigned NOT NULL ', 'datetime', '0', '', 1, '', 13, 0, 1, 1383902552, 1383891480),
(138, 'addons', '钩子挂载的插件 ''，''分割', 'varchar(255) NULL ', 'string', '', '', 1, '', 13, 0, 1, 1383891480, 1383891480),
(139, 'uid', '用户ID', 'int(10) unsigned NOT NULL ', 'num', '0', '', 1, '', 14, 0, 1, 1383898187, 1383891488),
(140, 'nickname', '昵称', 'char(16) NOT NULL ', 'string', '''''', '', 1, '', 14, 0, 1, 1383902646, 1383891488),
(141, 'sex', '性别', 'tinyint(3) unsigned NOT NULL ', 'radio', '0', '', 1, '0:女\r\n1:男', 14, 0, 1, 1383898158, 1383891488),
(142, 'birthday', '生日', 'date NOT NULL ', 'string', '0000-00-00', '', 1, '', 14, 0, 1, 1383891488, 1383891488),
(143, 'qq', 'qq号', 'char(10) NOT NULL ', 'string', '''''', '', 1, '', 14, 0, 1, 1383902605, 1383891488),
(144, 'score', '用户积分', 'mediumint(8) NOT NULL ', 'num', '0', '', 1, '', 14, 0, 1, 1383898066, 1383891488),
(145, 'login', '登录次数', 'int(10) unsigned NOT NULL ', 'num', '0', '', 1, '', 14, 0, 1, 1383898055, 1383891488),
(146, 'reg_ip', '注册IP', 'bigint(20) NOT NULL ', 'string', '0', '', 1, '', 14, 0, 1, 1383898045, 1383891488),
(147, 'reg_time', '注册时间', 'int(10) unsigned NOT NULL ', 'datetime', '0', '', 1, '', 14, 0, 1, 1383897783, 1383891488),
(148, 'last_login_ip', '最后登录IP', 'bigint(20) NOT NULL ', 'string', '0', '', 1, '', 14, 0, 1, 1383891488, 1383891488),
(149, 'last_login_time', '最后登录时间', 'int(10) unsigned NOT NULL ', 'datetime', '0', '', 1, '', 14, 0, 1, 1383897775, 1383891488),
(150, 'status', '会员状态', 'tinyint(4) NOT NULL ', 'radio', '0', '', 1, '1:正常\r\n0:禁用', 14, 0, 1, 1383898472, 1383891488),
(151, 'title', '标题', 'varchar(50) NOT NULL ', 'string', '''''', '', 1, '', 15, 0, 1, 1383902756, 1383891503),
(152, 'pid', '上级分类ID', 'int(10) unsigned NOT NULL ', 'num', '0', '', 1, '', 15, 0, 1, 1383897737, 1383891503),
(153, 'sort', '排序（同级有效）', 'int(10) unsigned NOT NULL ', 'num', '0', '', 1, '', 15, 0, 1, 1383902746, 1383891503),
(154, 'url', '链接地址', 'char(255) NOT NULL ', 'string', '''''', '', 1, '', 15, 0, 1, 1383902737, 1383891503),
(155, 'hide', '是否隐藏', 'tinyint(1) unsigned NOT NULL ', 'radio', '0', '', 1, '1:是\r\n0:否', 15, 0, 1, 1383902727, 1383891503),
(156, 'tip', '提示', 'varchar(255) NOT NULL ', 'textarea', '''''', '', 1, '', 15, 0, 1, 1383902703, 1383891503),
(157, 'group', '分组', 'varchar(50) NULL ', 'string', '''''', '', 1, '', 15, 0, 1, 1383902688, 1383891503),
(158, 'is_dev', '是否仅开发者模式可见', 'tinyint(1) unsigned NOT NULL ', 'radio', '0', '', 1, '1:开发者模式可见\r\n0:开发者模式不可见', 15, 0, 1, 1383902676, 1383891503),
(159, 'name', '模型标识', 'char(30) NOT NULL ', 'string', '''''', '', 1, '', 16, 0, 1, 1383903045, 1383891514),
(160, 'title', '模型名称', 'char(30) NOT NULL ', 'string', '''''', '', 1, '', 16, 0, 1, 1383903037, 1383891514),
(161, 'extend', '继承的模型', 'int(10) unsigned NOT NULL ', 'num', '0', '', 1, '', 16, 0, 1, 1383903026, 1383891514),
(162, 'relation', '继承与被继承模型的关联字段', 'varchar(30) NOT NULL ', 'string', '', '', 1, '', 16, 0, 1, 1383891514, 1383891514),
(163, 'need_pk', '新建表时是否需要主键字段', 'tinyint(1) unsigned NOT NULL ', 'radio', '1', '', 1, '1:是\r\n0:否', 16, 0, 1, 1383903004, 1383891514),
(164, 'field_sort', '表单字段排序', 'text NOT NULL ', 'textarea', '', '', 1, '', 16, 0, 1, 1383902978, 1383891514),
(165, 'field_group', '字段分组', 'varchar(255) NOT NULL ', 'string', '1:基础', '', 1, '', 16, 0, 1, 1383891514, 1383891514),
(166, 'attribute_list', '属性列表（表的字段）', 'text NOT NULL ', 'textarea', '', '', 1, '', 16, 0, 1, 1383902908, 1383891514),
(167, 'template_list', '列表模板', 'varchar(100) NOT NULL ', 'string', '''''', '', 1, '', 16, 0, 1, 1383902888, 1383891514),
(168, 'template_add', '新增模板', 'varchar(100) NOT NULL ', 'string', '''''', '', 1, '', 16, 0, 1, 1383902878, 1383891514),
(169, 'template_edit', '编辑模板', 'varchar(100) NOT NULL ', 'string', '''''', '', 1, '', 16, 0, 1, 1383902869, 1383891514),
(170, 'list_grid', '列表定义', 'varchar(255) NOT NULL ', 'textarea', '''''', '', 1, '', 16, 0, 1, 1383902861, 1383891514),
(171, 'list_row', '列表数据长度', 'smallint(2) unsigned NOT NULL ', 'num', '10', '', 1, '', 16, 0, 1, 1383897602, 1383891514),
(172, 'search_key', '默认搜索字段', 'varchar(50) NOT NULL ', 'string', '''''', '', 1, '', 16, 0, 1, 1383902840, 1383891514),
(173, 'search_list', '高级搜索的字段', 'varchar(255) NOT NULL ', 'string', '''''', '', 1, '', 16, 0, 1, 1383902828, 1383891514),
(174, 'create_time', '创建时间', 'int(10) unsigned NOT NULL ', 'datetime', '0', '', 1, '', 16, 0, 1, 1383897557, 1383891514),
(175, 'update_time', '更新时间', 'int(10) unsigned NOT NULL ', 'datetime', '0', '', 1, '', 16, 0, 1, 1383897549, 1383891514),
(176, 'status', '状态', 'tinyint(3) unsigned NOT NULL ', 'radio', '0', '', 1, '-1:已删除\r\n0:禁用\r\n1:正常', 16, 0, 1, 1383902811, 1383891514),
(177, 'path', '路径', 'varchar(255) NOT NULL ', 'string', '''''', '', 1, '', 17, 0, 1, 1383903110, 1383891522),
(178, 'url', '图片链接', 'varchar(255) NOT NULL ', 'string', '''''', '', 1, '', 17, 0, 1, 1383903103, 1383891522),
(179, 'md5', '文件md5', 'char(32) NOT NULL ', 'string', '''''', '', 1, '', 17, 0, 1, 1383903093, 1383891522),
(180, 'sha1', '文件 sha1编码', 'char(40) NOT NULL ', 'string', '''''', '', 1, '', 17, 0, 1, 1383903084, 1383891522),
(181, 'status', '状态', 'tinyint(2) NOT NULL ', 'radio', '0', '', 1, '-1:已删除\r\n0:禁用\r\n1:正常', 17, 0, 1, 1383903075, 1383891522),
(182, 'create_time', '创建时间', 'int(10) unsigned NOT NULL ', 'datetime', '0', '', 1, '', 17, 0, 1, 1383897491, 1383891522),
(183, 'url', '链接地址', 'char(255) NOT NULL ', 'string', '''''', '', 1, '', 18, 0, 1, 1383903175, 1383891532),
(184, 'short', '短网址', 'char(100) NOT NULL ', 'string', '''''', '', 1, '', 18, 0, 1, 1383903162, 1383891532),
(185, 'status', '状态', 'tinyint(2) NOT NULL ', 'radio', '2', '', 1, '-1:已删除\r\n0:禁用\r\n1:正常\r\n2:未审核', 18, 0, 1, 1383903153, 1383891532),
(186, 'create_time', '创建时间', 'int(10) unsigned NOT NULL ', 'datetime', '0', '', 1, '', 18, 0, 1, 1383903130, 1383891532);

-- --------------------------------------------------------

--
-- 表的结构 `ts_auth_extend`
--

CREATE TABLE IF NOT EXISTS `ts_auth_extend` (
  `group_id` mediumint(10) unsigned NOT NULL COMMENT '用户id',
  `extend_id` mediumint(8) unsigned NOT NULL COMMENT '扩展表中数据的id',
  `type` tinyint(1) unsigned NOT NULL COMMENT '扩展类型标识 1:栏目分类权限;2:模型权限'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户组与分类的对应关系表';

--
-- 转存表中的数据 `ts_auth_extend`
--

INSERT INTO `ts_auth_extend` (`group_id`, `extend_id`, `type`) VALUES
(1, 1, 1),
(1, 1, 2),
(1, 2, 1),
(1, 2, 2),
(1, 3, 1),
(1, 3, 2),
(1, 4, 1),
(1, 37, 1);

-- --------------------------------------------------------

--
-- 表的结构 `ts_auth_group`
--

CREATE TABLE IF NOT EXISTS `ts_auth_group` (
`id` mediumint(8) unsigned NOT NULL COMMENT '用户组id,自增主键',
  `module` varchar(20) NOT NULL COMMENT '用户组所属模块',
  `type` tinyint(4) NOT NULL COMMENT '组类型',
  `title` char(20) NOT NULL DEFAULT '' COMMENT '用户组中文名称',
  `description` varchar(80) NOT NULL DEFAULT '' COMMENT '描述信息',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '用户组状态：为1正常，为0禁用,-1为删除',
  `rules` varchar(500) NOT NULL DEFAULT '' COMMENT '用户组拥有的规则id，多个规则 , 隔开'
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ts_auth_group`
--

INSERT INTO `ts_auth_group` (`id`, `module`, `type`, `title`, `description`, `status`, `rules`) VALUES
(1, 'admin', 1, '默认用户组', '', 1, '1,2,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49,50,51,52,53,54,55,56,57,58,59,60,61,62,63,64,65,66,67,68,69,70,71,72,73,74,79,80,81,82,83,84,86,87,88,89,90,91,92,93,94,95,96,97,100,102,103,104,105,106');

-- --------------------------------------------------------

--
-- 表的结构 `ts_auth_group_access`
--

CREATE TABLE IF NOT EXISTS `ts_auth_group_access` (
  `uid` int(10) unsigned NOT NULL COMMENT '用户id',
  `group_id` mediumint(8) unsigned NOT NULL COMMENT '用户组id'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ts_auth_group_access`
--

INSERT INTO `ts_auth_group_access` (`uid`, `group_id`) VALUES
(3, 1);

-- --------------------------------------------------------

--
-- 表的结构 `ts_auth_rule`
--

CREATE TABLE IF NOT EXISTS `ts_auth_rule` (
`id` mediumint(8) unsigned NOT NULL COMMENT '规则id,自增主键',
  `module` varchar(20) NOT NULL COMMENT '规则所属module',
  `type` tinyint(2) NOT NULL DEFAULT '1' COMMENT '1-url;2-主菜单',
  `name` char(80) NOT NULL DEFAULT '' COMMENT '规则唯一英文标识',
  `title` char(20) NOT NULL DEFAULT '' COMMENT '规则中文描述',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否有效(0:无效,1:有效)',
  `condition` varchar(300) NOT NULL DEFAULT '' COMMENT '规则附加条件'
) ENGINE=MyISAM AUTO_INCREMENT=207 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ts_auth_rule`
--

INSERT INTO `ts_auth_rule` (`id`, `module`, `type`, `name`, `title`, `status`, `condition`) VALUES
(1, 'admin', 2, 'Admin/Index/index', '首页', 1, ''),
(2, 'admin', 2, 'Admin/Article/mydocument', '内容', 1, ''),
(3, 'admin', 2, 'Admin/User/index', '用户', 1, ''),
(4, 'admin', 2, 'Admin/Addons/index', '扩展', 1, ''),
(5, 'admin', 2, 'Admin/Config/group', '系统', 1, ''),
(6, 'admin', 1, 'Admin/Index/index', '首页', -1, ''),
(7, 'admin', 1, 'Admin/article/add', '新增', 1, ''),
(8, 'admin', 1, 'Admin/article/edit', '编辑', 1, ''),
(9, 'admin', 1, 'Admin/article/setStatus', '改变状态', 1, ''),
(10, 'admin', 1, 'Admin/article/update', '保存', 1, ''),
(11, 'admin', 1, 'Admin/article/autoSave', '保存草稿', 1, ''),
(12, 'admin', 1, 'Admin/article/move', '移动', 1, ''),
(13, 'admin', 1, 'Admin/article/copy', '复制', 1, ''),
(14, 'admin', 1, 'Admin/article/paste', '粘贴', 1, ''),
(15, 'admin', 1, 'Admin/article/permit', '还原', 1, ''),
(16, 'admin', 1, 'Admin/article/clear', '清空', 1, ''),
(17, 'admin', 1, 'Admin/article/index', '文档列表', 1, ''),
(18, 'admin', 1, 'Admin/article/recycle', '回收站', 1, ''),
(19, 'admin', 1, 'Admin/User/addAction', '新增用户行为', 1, ''),
(20, 'admin', 1, 'Admin/User/editAction', '编辑用户行为', 1, ''),
(21, 'admin', 1, 'Admin/User/saveAction', '保存用户行为', 1, ''),
(22, 'admin', 1, 'Admin/User/setStatus', '变更行为状态', 1, ''),
(23, 'admin', 1, 'Admin/User/changeStatus?method=forbidUser', '禁用会员', 1, ''),
(24, 'admin', 1, 'Admin/User/changeStatus?method=resumeUser', '启用会员', 1, ''),
(25, 'admin', 1, 'Admin/User/changeStatus?method=deleteUser', '删除会员', 1, ''),
(26, 'admin', 1, 'Admin/User/index', '用户信息', 1, ''),
(27, 'admin', 1, 'Admin/User/action', '用户行为', 1, ''),
(28, 'admin', 1, 'Admin/AuthManager/changeStatus?method=deleteGroup', '删除', 1, ''),
(29, 'admin', 1, 'Admin/AuthManager/changeStatus?method=forbidGroup', '禁用', 1, ''),
(30, 'admin', 1, 'Admin/AuthManager/changeStatus?method=resumeGroup', '恢复', 1, ''),
(31, 'admin', 1, 'Admin/AuthManager/createGroup', '新增', 1, ''),
(32, 'admin', 1, 'Admin/AuthManager/editGroup', '编辑', 1, ''),
(33, 'admin', 1, 'Admin/AuthManager/writeGroup', '保存用户组', 1, ''),
(34, 'admin', 1, 'Admin/AuthManager/group', '授权', 1, ''),
(35, 'admin', 1, 'Admin/AuthManager/access', '访问授权', 1, ''),
(36, 'admin', 1, 'Admin/AuthManager/user', '成员授权', 1, ''),
(37, 'admin', 1, 'Admin/AuthManager/removeFromGroup', '解除授权', 1, ''),
(38, 'admin', 1, 'Admin/AuthManager/addToGroup', '保存成员授权', 1, ''),
(39, 'admin', 1, 'Admin/AuthManager/category', '分类授权', 1, ''),
(40, 'admin', 1, 'Admin/AuthManager/addToCategory', '保存分类授权', 1, ''),
(41, 'admin', 1, 'Admin/AuthManager/index', '权限管理', 1, ''),
(42, 'admin', 1, 'Admin/Addons/create', '创建', 1, ''),
(43, 'admin', 1, 'Admin/Addons/checkForm', '检测创建', 1, ''),
(44, 'admin', 1, 'Admin/Addons/preview', '预览', 1, ''),
(45, 'admin', 1, 'Admin/Addons/build', '快速生成插件', 1, ''),
(46, 'admin', 1, 'Admin/Addons/config', '设置', 1, ''),
(47, 'admin', 1, 'Admin/Addons/disable', '禁用', 1, ''),
(48, 'admin', 1, 'Admin/Addons/enable', '启用', 1, ''),
(49, 'admin', 1, 'Admin/Addons/install', '安装', 1, ''),
(50, 'admin', 1, 'Admin/Addons/uninstall', '卸载', 1, ''),
(51, 'admin', 1, 'Admin/Addons/saveconfig', '更新配置', 1, ''),
(52, 'admin', 1, 'Admin/Addons/adminList', '插件后台列表', 1, ''),
(53, 'admin', 1, 'Admin/Addons/execute', 'URL方式访问插件', 1, ''),
(54, 'admin', 1, 'Admin/Addons/index', '插件管理', 1, ''),
(55, 'admin', 1, 'Admin/Addons/hooks', '钩子管理', 1, ''),
(56, 'admin', 1, 'Admin/model/add', '新增', 1, ''),
(57, 'admin', 1, 'Admin/model/edit', '编辑', 1, ''),
(58, 'admin', 1, 'Admin/model/setStatus', '改变状态', 1, ''),
(59, 'admin', 1, 'Admin/model/update', '保存数据', 1, ''),
(60, 'admin', 1, 'Admin/Model/index', '模型管理', 1, ''),
(61, 'admin', 1, 'Admin/Config/edit', '编辑', 1, ''),
(62, 'admin', 1, 'Admin/Config/del', '删除', 1, ''),
(63, 'admin', 1, 'Admin/Config/add', '新增', 1, ''),
(64, 'admin', 1, 'Admin/Config/save', '保存', 1, ''),
(65, 'admin', 1, 'Admin/Config/group', '网站设置', 1, ''),
(66, 'admin', 1, 'Admin/Config/index', '配置管理', 1, ''),
(67, 'admin', 1, 'Admin/Channel/add', '新增', 1, ''),
(68, 'admin', 1, 'Admin/Channel/edit', '编辑', 1, ''),
(69, 'admin', 1, 'Admin/Channel/del', '删除', 1, ''),
(70, 'admin', 1, 'Admin/Channel/index', '导航管理', 1, ''),
(71, 'admin', 1, 'Admin/Category/edit', '编辑', 1, ''),
(72, 'admin', 1, 'Admin/Category/add', '新增', 1, ''),
(73, 'admin', 1, 'Admin/Category/remove', '删除', 1, ''),
(74, 'admin', 1, 'Admin/Category/index', '分类管理', 1, ''),
(75, 'admin', 1, 'Admin/file/upload', '上传控件', -1, ''),
(76, 'admin', 1, 'Admin/file/uploadPicture', '上传图片', -1, ''),
(77, 'admin', 1, 'Admin/file/download', '下载', -1, ''),
(94, 'admin', 1, 'Admin/AuthManager/modelauth', '模型授权', 1, ''),
(79, 'admin', 1, 'Admin/article/batchOperate', '导入', 1, ''),
(80, 'admin', 1, 'Admin/Database/index?type=export', '备份数据库', 1, ''),
(81, 'admin', 1, 'Admin/Database/index?type=import', '还原数据库', 1, ''),
(82, 'admin', 1, 'Admin/Database/export', '备份', 1, ''),
(83, 'admin', 1, 'Admin/Database/optimize', '优化表', 1, ''),
(84, 'admin', 1, 'Admin/Database/repair', '修复表', 1, ''),
(86, 'admin', 1, 'Admin/Database/import', '恢复', 1, ''),
(87, 'admin', 1, 'Admin/Database/del', '删除', 1, ''),
(88, 'admin', 1, 'Admin/User/add', '新增用户', 1, ''),
(89, 'admin', 1, 'Admin/Attribute/index', '属性管理', 1, ''),
(90, 'admin', 1, 'Admin/Attribute/add', '新增', 1, ''),
(91, 'admin', 1, 'Admin/Attribute/edit', '编辑', 1, ''),
(92, 'admin', 1, 'Admin/Attribute/setStatus', '改变状态', 1, ''),
(93, 'admin', 1, 'Admin/Attribute/update', '保存数据', 1, ''),
(95, 'admin', 1, 'Admin/AuthManager/addToModel', '保存模型授权', 1, ''),
(96, 'admin', 1, 'Admin/Category/move', '移动', 1, ''),
(97, 'admin', 1, 'Admin/Category/merge', '合并', 1, ''),
(98, 'admin', 1, 'Admin/Config/menu', '后台菜单管理', -1, ''),
(99, 'admin', 1, 'Admin/Article/mydocument', '内容', -1, ''),
(100, 'admin', 1, 'Admin/Menu/index', '菜单管理', 1, ''),
(101, 'admin', 1, 'Admin/other', '其他', -1, ''),
(102, 'admin', 1, 'Admin/Menu/add', '新增', 1, ''),
(103, 'admin', 1, 'Admin/Menu/edit', '编辑', 1, ''),
(104, 'admin', 1, 'Admin/Think/lists?model=article', '文章管理', 1, ''),
(105, 'admin', 1, 'Admin/Think/lists?model=download', '下载管理', 1, ''),
(106, 'admin', 1, 'Admin/Think/lists?model=config', '配置管理', 1, ''),
(107, 'admin', 1, 'Admin/Action/actionlog', '行为日志', 1, ''),
(108, 'admin', 1, 'Admin/User/updatePassword', '修改密码', 1, ''),
(109, 'admin', 1, 'Admin/User/updateNickname', '修改昵称', 1, ''),
(110, 'admin', 1, 'Admin/action/edit', '查看行为日志', 1, ''),
(205, 'admin', 1, 'Admin/think/add', '新增数据', 1, ''),
(111, 'admin', 2, 'Admin/article/index', '文档列表', -1, ''),
(112, 'admin', 2, 'Admin/article/add', '新增', -1, ''),
(113, 'admin', 2, 'Admin/article/edit', '编辑', -1, ''),
(114, 'admin', 2, 'Admin/article/setStatus', '改变状态', -1, ''),
(115, 'admin', 2, 'Admin/article/update', '保存', -1, ''),
(116, 'admin', 2, 'Admin/article/autoSave', '保存草稿', -1, ''),
(117, 'admin', 2, 'Admin/article/move', '移动', -1, ''),
(118, 'admin', 2, 'Admin/article/copy', '复制', -1, ''),
(119, 'admin', 2, 'Admin/article/paste', '粘贴', -1, ''),
(120, 'admin', 2, 'Admin/article/batchOperate', '导入', -1, ''),
(121, 'admin', 2, 'Admin/article/recycle', '回收站', -1, ''),
(122, 'admin', 2, 'Admin/article/permit', '还原', -1, ''),
(123, 'admin', 2, 'Admin/article/clear', '清空', -1, ''),
(124, 'admin', 2, 'Admin/User/add', '新增用户', -1, ''),
(125, 'admin', 2, 'Admin/User/action', '用户行为', -1, ''),
(126, 'admin', 2, 'Admin/User/addAction', '新增用户行为', -1, ''),
(127, 'admin', 2, 'Admin/User/editAction', '编辑用户行为', -1, ''),
(128, 'admin', 2, 'Admin/User/saveAction', '保存用户行为', -1, ''),
(129, 'admin', 2, 'Admin/User/setStatus', '变更行为状态', -1, ''),
(130, 'admin', 2, 'Admin/User/changeStatus?method=forbidUser', '禁用会员', -1, ''),
(131, 'admin', 2, 'Admin/User/changeStatus?method=resumeUser', '启用会员', -1, ''),
(132, 'admin', 2, 'Admin/User/changeStatus?method=deleteUser', '删除会员', -1, ''),
(133, 'admin', 2, 'Admin/AuthManager/index', '权限管理', -1, ''),
(134, 'admin', 2, 'Admin/AuthManager/changeStatus?method=deleteGroup', '删除', -1, ''),
(135, 'admin', 2, 'Admin/AuthManager/changeStatus?method=forbidGroup', '禁用', -1, ''),
(136, 'admin', 2, 'Admin/AuthManager/changeStatus?method=resumeGroup', '恢复', -1, ''),
(137, 'admin', 2, 'Admin/AuthManager/createGroup', '新增', -1, ''),
(138, 'admin', 2, 'Admin/AuthManager/editGroup', '编辑', -1, ''),
(139, 'admin', 2, 'Admin/AuthManager/writeGroup', '保存用户组', -1, ''),
(140, 'admin', 2, 'Admin/AuthManager/group', '授权', -1, ''),
(141, 'admin', 2, 'Admin/AuthManager/access', '访问授权', -1, ''),
(142, 'admin', 2, 'Admin/AuthManager/user', '成员授权', -1, ''),
(143, 'admin', 2, 'Admin/AuthManager/removeFromGroup', '解除授权', -1, ''),
(144, 'admin', 2, 'Admin/AuthManager/addToGroup', '保存成员授权', -1, ''),
(145, 'admin', 2, 'Admin/AuthManager/category', '分类授权', -1, ''),
(146, 'admin', 2, 'Admin/AuthManager/addToCategory', '保存分类授权', -1, ''),
(147, 'admin', 2, 'Admin/AuthManager/modelauth', '模型授权', -1, ''),
(148, 'admin', 2, 'Admin/AuthManager/addToModel', '保存模型授权', -1, ''),
(149, 'admin', 2, 'Admin/Addons/create', '创建', -1, ''),
(150, 'admin', 2, 'Admin/Addons/checkForm', '检测创建', -1, ''),
(151, 'admin', 2, 'Admin/Addons/preview', '预览', -1, ''),
(152, 'admin', 2, 'Admin/Addons/build', '快速生成插件', -1, ''),
(153, 'admin', 2, 'Admin/Addons/config', '设置', -1, ''),
(154, 'admin', 2, 'Admin/Addons/disable', '禁用', -1, ''),
(155, 'admin', 2, 'Admin/Addons/enable', '启用', -1, ''),
(156, 'admin', 2, 'Admin/Addons/install', '安装', -1, ''),
(157, 'admin', 2, 'Admin/Addons/uninstall', '卸载', -1, ''),
(158, 'admin', 2, 'Admin/Addons/saveconfig', '更新配置', -1, ''),
(159, 'admin', 2, 'Admin/Addons/adminList', '插件后台列表', -1, ''),
(160, 'admin', 2, 'Admin/Addons/execute', 'URL方式访问插件', -1, ''),
(161, 'admin', 2, 'Admin/Addons/hooks', '钩子管理', -1, ''),
(162, 'admin', 2, 'Admin/Model/index', '模型管理', -1, ''),
(163, 'admin', 2, 'Admin/model/add', '新增', -1, ''),
(164, 'admin', 2, 'Admin/model/edit', '编辑', -1, ''),
(165, 'admin', 2, 'Admin/model/setStatus', '改变状态', -1, ''),
(166, 'admin', 2, 'Admin/model/update', '保存数据', -1, ''),
(167, 'admin', 2, 'Admin/Attribute/index', '属性管理', -1, ''),
(168, 'admin', 2, 'Admin/Attribute/add', '新增', -1, ''),
(169, 'admin', 2, 'Admin/Attribute/edit', '编辑', -1, ''),
(170, 'admin', 2, 'Admin/Attribute/setStatus', '改变状态', -1, ''),
(171, 'admin', 2, 'Admin/Attribute/update', '保存数据', -1, ''),
(172, 'admin', 2, 'Admin/Config/index', '配置管理', -1, ''),
(173, 'admin', 2, 'Admin/Config/edit', '编辑', -1, ''),
(174, 'admin', 2, 'Admin/Config/del', '删除', -1, ''),
(175, 'admin', 2, 'Admin/Config/add', '新增', -1, ''),
(176, 'admin', 2, 'Admin/Config/save', '保存', -1, ''),
(177, 'admin', 2, 'Admin/Menu/index', '菜单管理', -1, ''),
(178, 'admin', 2, 'Admin/Channel/index', '导航管理', -1, ''),
(179, 'admin', 2, 'Admin/Channel/add', '新增', -1, ''),
(180, 'admin', 2, 'Admin/Channel/edit', '编辑', -1, ''),
(181, 'admin', 2, 'Admin/Channel/del', '删除', -1, ''),
(182, 'admin', 2, 'Admin/Category/index', '分类管理', -1, ''),
(183, 'admin', 2, 'Admin/Category/edit', '编辑', -1, ''),
(184, 'admin', 2, 'Admin/Category/add', '新增', -1, ''),
(185, 'admin', 2, 'Admin/Category/remove', '删除', -1, ''),
(186, 'admin', 2, 'Admin/Category/move', '移动', -1, ''),
(187, 'admin', 2, 'Admin/Category/merge', '合并', -1, ''),
(188, 'admin', 2, 'Admin/Database/index?type=export', '备份数据库', -1, ''),
(189, 'admin', 2, 'Admin/Database/export', '备份', -1, ''),
(190, 'admin', 2, 'Admin/Database/optimize', '优化表', -1, ''),
(191, 'admin', 2, 'Admin/Database/repair', '修复表', -1, ''),
(192, 'admin', 2, 'Admin/Database/index?type=import', '还原数据库', -1, ''),
(193, 'admin', 2, 'Admin/Database/import', '恢复', -1, ''),
(194, 'admin', 2, 'Admin/Database/del', '删除', -1, ''),
(195, 'admin', 2, 'Admin/other', '其他', 1, ''),
(196, 'admin', 2, 'Admin/Menu/add', '新增', -1, ''),
(197, 'admin', 2, 'Admin/Menu/edit', '编辑', -1, ''),
(198, 'admin', 2, 'Admin/Think/lists?model=article', '应用', 1, ''),
(199, 'admin', 2, 'Admin/Think/lists?model=download', '下载管理', -1, ''),
(200, 'admin', 2, 'Admin/Think/lists?model=config', '配置管理', -1, ''),
(201, 'admin', 2, 'Admin/Action/actionlog', '行为日志', -1, ''),
(202, 'admin', 2, 'Admin/User/updatePassword', '修改密码', -1, ''),
(203, 'admin', 2, 'Admin/User/updateNickname', '修改昵称', -1, ''),
(204, 'admin', 2, 'Admin/action/edit', '查看行为日志', -1, ''),
(206, 'admin', 1, 'Admin/think/edit', '编辑数据', 1, '');

-- --------------------------------------------------------

--
-- 表的结构 `ts_category`
--

CREATE TABLE IF NOT EXISTS `ts_category` (
`id` int(10) unsigned NOT NULL COMMENT '分类ID',
  `name` varchar(30) NOT NULL COMMENT '标志',
  `title` varchar(50) NOT NULL COMMENT '标题',
  `pid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级分类ID',
  `sort` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '排序（同级有效）',
  `list_row` tinyint(3) unsigned NOT NULL DEFAULT '10' COMMENT '列表每页行数',
  `meta_title` varchar(50) NOT NULL DEFAULT '' COMMENT 'SEO的网页标题',
  `keywords` varchar(255) NOT NULL DEFAULT '' COMMENT '关键字',
  `description` varchar(255) NOT NULL DEFAULT '' COMMENT '描述',
  `template_index` varchar(100) NOT NULL COMMENT '频道页模板',
  `template_lists` varchar(100) NOT NULL COMMENT '列表页模板',
  `template_detail` varchar(100) NOT NULL COMMENT '详情页模板',
  `template_edit` varchar(100) NOT NULL COMMENT '编辑页模板',
  `model` varchar(100) NOT NULL DEFAULT '' COMMENT '关联模型',
  `type` varchar(100) NOT NULL DEFAULT '' COMMENT '允许发布的内容类型',
  `link_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '外链',
  `allow_publish` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否允许发布内容',
  `display` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '可见性',
  `reply` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否允许回复',
  `check` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '发布的文章是否需要审核',
  `reply_model` varchar(100) NOT NULL DEFAULT '',
  `extend` text NOT NULL COMMENT '扩展设置',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '数据状态'
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='分类表\r\n@author   麦当苗儿\r\n@version  2013-05-21';

--
-- 转存表中的数据 `ts_category`
--

INSERT INTO `ts_category` (`id`, `name`, `title`, `pid`, `sort`, `list_row`, `meta_title`, `keywords`, `description`, `template_index`, `template_lists`, `template_detail`, `template_edit`, `model`, `type`, `link_id`, `allow_publish`, `display`, `reply`, `check`, `reply_model`, `extend`, `create_time`, `update_time`, `status`) VALUES
(1, 'blog', '博客', 0, 0, 10, '', '', '', '', '', '', '', '2', '2,1', 0, 0, 1, 0, 0, '1', '', 1379474947, 1382701539, 1),
(2, 'default_blog', '默认分类', 1, 1, 10, '', '', '', '', '', '', '', '2,3', '2,1,3', 0, 1, 1, 0, 1, '1', '', 1379475028, 1384423564, 1),
(3, 'topic', '讨论', 0, 0, 10, '', '', '', 'Article/index_topic', 'Article/lists_topic', 'Article/Article/detail_topic', '', '2', '2', 0, 0, 1, 0, 0, '1', '', 1379475049, 1382701899, 1),
(4, 'default_topic', '默认分类', 3, 0, 10, '', '', '', '', 'Article/lists_topic', 'Article/Article/detail_topic', '', '2,3', '2,3', 0, 1, 1, 1, 1, '1', '', 1379475068, 1383878121, 1);

-- --------------------------------------------------------

--
-- 表的结构 `ts_channel`
--

CREATE TABLE IF NOT EXISTS `ts_channel` (
`id` int(10) unsigned NOT NULL COMMENT '频道ID',
  `pid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级频道ID',
  `title` char(30) NOT NULL COMMENT '频道标题',
  `url` char(100) NOT NULL COMMENT '频道连接',
  `sort` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '导航排序',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态'
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ts_channel`
--

INSERT INTO `ts_channel` (`id`, `pid`, `title`, `url`, `sort`, `create_time`, `update_time`, `status`) VALUES
(1, 0, '首页', 'Index/index', 2, 1379475111, 1379923177, 1),
(2, 0, '博客', 'Article/index?category=blog', 0, 1379475131, 1379483713, 1),
(3, 0, '讨论', 'Article/index?category=topic', 0, 1379475154, 1379483726, 1);

-- --------------------------------------------------------

--
-- 表的结构 `ts_config`
--

CREATE TABLE IF NOT EXISTS `ts_config` (
`id` int(10) unsigned NOT NULL COMMENT '配置ID',
  `name` varchar(30) NOT NULL DEFAULT '' COMMENT '配置名称',
  `type` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '配置类型',
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '配置说明',
  `group` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '配置分组',
  `extra` varchar(255) NOT NULL DEFAULT '' COMMENT '配置值',
  `remark` varchar(100) NOT NULL COMMENT '配置说明',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态',
  `value` text NOT NULL COMMENT '配置值',
  `sort` smallint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序'
) ENGINE=MyISAM AUTO_INCREMENT=33 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ts_config`
--

INSERT INTO `ts_config` (`id`, `name`, `type`, `title`, `group`, `extra`, `remark`, `create_time`, `update_time`, `status`, `value`, `sort`) VALUES
(1, 'WEB_SITE_TITLE', 1, '网站标题', 1, '', '网站标题前台显示标题', 1378898976, 1379235274, 1, '品森培训', 0),
(2, 'WEB_SITE_DESCRIPTION', 2, '网站描述', 1, '', '网站搜索引擎描述', 1378898976, 1379235841, 1, '品森培训', 1),
(3, 'WEB_SITE_KEYWORD', 2, '网站关键字', 1, '', '网站搜索引擎关键字', 1378898976, 1381390100, 1, '药店,品森培训', 3),
(4, 'WEB_SITE_CLOSE', 4, '关闭站点', 1, '0:关闭,1:开启', '站点关闭后其他用户不能访问，管理员可以正常访问', 1378898976, 1379235296, 1, '1', 0),
(9, 'CONFIG_TYPE_LIST', 3, '配置类型列表', 4, '', '主要用于数据解析和页面表单的生成', 1378898976, 1379235348, 1, '0:数字\r\n1:字符\r\n2:文本\r\n3:数组\r\n4:枚举', 0),
(10, 'WEB_SITE_ICP', 1, '网站备案号', 1, '', '设置在网站底部显示的备案号，如“沪ICP备12007941号-2', 1378900335, 1379235859, 1, '', 4),
(11, 'DOCUMENT_POSITION', 3, '文档推荐位', 2, '', '文档推荐位，推荐到多个位置KEY值相加即可', 1379053380, 1379235329, 1, '1:列表页推荐\r\n2:频道页推荐\r\n4:网站首页推荐', 0),
(12, 'DOCUMENT_DISPLAY', 3, '文档可见性', 2, '', '文章可见性仅影响前台显示，后台不收影响', 1379056370, 1379235322, 1, '0:所有人可见\r\n1:仅注册会员可见\r\n2:仅管理员可见', 0),
(13, 'COLOR_STYLE', 4, '后台色系', 1, 'default_color:默认\r\nblue_color:紫罗兰', '后台颜色风格', 1379122533, 1379235904, 1, 'blue_color', 5),
(20, 'CONFIG_GROUP_LIST', 3, '配置分组', 4, '', '配置分组', 1379228036, 1384418383, 1, '1:基本\r\n2:内容\r\n3:用户\r\n4:系统', 0),
(21, 'HOOKS_TYPE', 3, '钩子的类型', 4, '', '类型 1-用于扩展显示内容，2-用于扩展业务处理', 1379313397, 1379313407, 1, '1:视图\r\n2:控制器', 0),
(22, 'AUTH_CONFIG', 3, 'Auth配置', 4, '', '自定义Auth.class.php类配置', 1379409310, 1379409564, 1, 'AUTH_ON:1\r\nAUTH_TYPE:2', 0),
(23, 'OPEN_DRAFTBOX', 4, '是否开启草稿功能', 2, '0:关闭草稿功能\r\n1:开启草稿功能\r\n', '新增文章时的草稿功能配置', 1379484332, 1379484591, 1, '1', 0),
(24, 'AOTUSAVE_DRAFT', 0, '自动保存草稿时间', 2, '', '自动保存草稿的时间间隔，单位：秒', 1379484574, 1379484574, 1, '60', 0),
(25, 'LIST_ROWS', 0, '后台每页记录数', 2, '', '后台数据每页显示记录数', 1379503896, 1380427745, 1, '10', 5),
(26, 'USER_ALLOW_REGISTER', 4, '是否允许用户注册', 3, '0:关闭注册\r\n1:允许注册', '是否开放用户注册', 1379504487, 1379504580, 1, '1', 0),
(27, 'CODEMIRROR_THEME', 4, '预览插件的CodeMirror主题', 4, '3024-day:3024 day\r\n3024-night:3024 night\r\nambiance:ambiance\r\nbase16-dark:base16 dark\r\nbase16-light:base16 light\r\nblackboard:blackboard\r\ncobalt:cobalt\r\neclipse:eclipse\r\nelegant:elegant\r\nerlang-dark:erlang-dark\r\nlesser-dark:lesser-dark\r\nmidnight:midnight', '详情见CodeMirror官网', 1379814385, 1380440451, 1, '3024-day', 0),
(28, 'DATA_BACKUP_PATH', 1, '数据库备份根路径', 4, '', '路径必须以 / 结尾', 1381482411, 1381482411, 1, './Data/', 0),
(29, 'DATA_BACKUP_PART_SIZE', 0, '数据库备份卷大小', 4, '', '该值用于限制压缩后的分卷最大长度。单位：B；建议设置20M', 1381482488, 1381729564, 1, '20971520', 0),
(30, 'DATA_BACKUP_COMPRESS', 4, '数据库备份文件是否启用压缩', 4, '0:不压缩\r\n1:启用压缩', '压缩备份文件需要PHP环境支持gzopen,gzwrite函数', 1381713345, 1381729544, 1, '1', 0),
(31, 'DATA_BACKUP_COMPRESS_LEVEL', 4, '数据库备份文件压缩级别', 4, '1:普通\r\n4:一般\r\n9:最高', '数据库备份文件的压缩级别，该配置在开启压缩时生效', 1381713408, 1381713408, 1, '9', 0),
(32, 'DEVELOP_MODE', 4, '开启开发者模式', 4, '0:关闭\r\n1:开启', '是否开启开发者模式', 1383105995, 1383291877, 1, '1', 0);

-- --------------------------------------------------------

--
-- 表的结构 `ts_course`
--

CREATE TABLE IF NOT EXISTS `ts_course` (
`id` int(11) unsigned NOT NULL,
  `title` varchar(200) NOT NULL COMMENT '标题',
  `type` tinyint(4) unsigned NOT NULL COMMENT '类型',
  `pharma_id` int(11) unsigned NOT NULL COMMENT '活动药厂',
  `drug_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '药品',
  `subbranch_id` int(11) unsigned NOT NULL COMMENT '药店',
  `gold` int(11) unsigned NOT NULL COMMENT '金币',
  `comment_count` int(11) unsigned NOT NULL COMMENT '点评分数',
  `play_count` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '点播数',
  `create_time` int(11) unsigned NOT NULL COMMENT '发布日期',
  `expire_time` int(11) unsigned NOT NULL COMMENT '有效日期',
  `sort` mediumint(9) unsigned NOT NULL COMMENT '排序',
  `course_ico` varchar(100) NOT NULL COMMENT '图标',
  `video_url` varchar(100) NOT NULL COMMENT '视频地址',
  `describe` text NOT NULL COMMENT '简介',
  `exam_id` int(10) unsigned NOT NULL COMMENT '试题id',
  `is_recom` tinyint(3) NOT NULL DEFAULT '0' COMMENT '课程是否推荐：0，否；1，是',
  `score` int(11) NOT NULL DEFAULT '0' COMMENT '观看视频所需积分'
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='课件';

--
-- 转存表中的数据 `ts_course`
--

INSERT INTO `ts_course` (`id`, `title`, `type`, `pharma_id`, `drug_id`, `subbranch_id`, `gold`, `comment_count`, `play_count`, `create_time`, `expire_time`, `sort`, `course_ico`, `video_url`, `describe`, `exam_id`, `is_recom`, `score`) VALUES
(1, '测试课程1', 4, 2, 2, 1, 10, 5, 2, 0, 0, 0, 'http://img1.gtimg.com/news/pics/hv1/140/114/1877/122081135.jpg', 'http://pinsenqihang.com/Data/vadio/1.mp4', 'wwwwwwwwwwwwwwwwwwwwww', 93, 1, 0),
(2, '测试课程2', 2, 1, 1, 1, 0, 0, 6, 8, 0, 0, 'http://img1.gtimg.com/news/pics/hv1/167/96/1887/122726822.jpg', 'http://pinsenqihang.com/Data/vadio/1.mp4', '活到老，学到老', 0, 0, 8),
(3, '测试课程3', 3, 1, 1, 1, 0, 5, 4, 0, 0, 0, 'http://img1.gtimg.com/news/pics/hv1/52/191/1889/122880982.jpg', 'http://pinsenqihang.com/Data/vadio/1.mp4', '掌握行业动态，提高自身能力', 0, 0, 10),
(4, '测试课程4', 4, 2, 1, 1, 0, 8, 5, 0, 0, 0, 'http://img1.gtimg.com/news/pics/hv1/52/191/1889/122880982.jpg', 'http://pinsenqihang.com/Data/vadio/1.mp4', 'ttttdddddd', 95, 1, 10);

-- --------------------------------------------------------

--
-- 表的结构 `ts_course_comment`
--

CREATE TABLE IF NOT EXISTS `ts_course_comment` (
`id` int(11) unsigned NOT NULL COMMENT '自增主键',
  `uid` int(11) NOT NULL COMMENT '评论人id',
  `course_id` int(11) NOT NULL COMMENT '被评论课程id',
  `course_level` tinyint(3) NOT NULL DEFAULT '1' COMMENT '被评论课程级别（1：企业产品，2:平台）',
  `comment_score` int(11) NOT NULL DEFAULT '0' COMMENT '评论分数',
  `comment_content` varchar(255) NOT NULL DEFAULT '' COMMENT '评论内容',
  `comment_time` int(11) NOT NULL COMMENT '评论时间'
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COMMENT='课程评论表';

--
-- 转存表中的数据 `ts_course_comment`
--

INSERT INTO `ts_course_comment` (`id`, `uid`, `course_id`, `course_level`, `comment_score`, `comment_content`, `comment_time`) VALUES
(1, 3, 1, 2, 4, '你好', 1439373930),
(2, 3, 1, 2, 4, '陪陪', 1439377502),
(3, 3, 1, 2, 4, '那你呢', 1439392875),
(4, 4, 1, 1, 4, '嘎嘎嘎', 1440599055),
(5, 4, 0, 1, 5, 'ggg', 1441634705),
(6, 4, 0, 1, 5, 'ggg', 1441634719),
(7, 4, 0, 1, 5, 'eee', 1441634845),
(8, 3, 1, 1, 5, '很好！', 1441636256),
(9, 4, 0, 1, 5, '回家江河湖海', 1441639658),
(10, 4, 1, 1, 5, '将空间', 1441639983),
(11, 4, 1, 1, 5, '好解决', 1441640399);

-- --------------------------------------------------------

--
-- 表的结构 `ts_course_record`
--

CREATE TABLE IF NOT EXISTS `ts_course_record` (
`id` int(11) unsigned NOT NULL COMMENT '自增主键',
  `uid` int(11) NOT NULL COMMENT '用户id',
  `course_id` int(11) NOT NULL COMMENT '课程视频id',
  `status` tinyint(3) NOT NULL COMMENT '状态：1，未考试；2，未通过；3，已通过',
  `update_time` int(11) NOT NULL COMMENT '最后更新时间',
  `level` tinyint(3) NOT NULL DEFAULT '1' COMMENT '课程级别：1，企业产品培训；2，平台培训'
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8 COMMENT='课程学习记录表';

--
-- 转存表中的数据 `ts_course_record`
--

INSERT INTO `ts_course_record` (`id`, `uid`, `course_id`, `status`, `update_time`, `level`) VALUES
(1, 3, 1, 3, 1441636227, 1),
(2, 4, 1, 2, 1441640394, 1),
(3, 4, 0, 1, 1439821878, 1),
(4, 4, 0, 1, 1439821959, 1),
(5, 4, 1, 2, 1441640394, 1),
(6, 4, 3, 1, 1440494643, 0),
(7, 4, 3, 1, 1440494782, 2),
(8, 4, 3, 1, 1440507740, 2),
(9, 4, 2, 1, 1440508183, 2),
(10, 4, 1, 2, 1441640394, 1),
(11, 4, 92, 1, 1440598969, 1),
(12, 4, 92, 1, 1440598992, 1),
(13, 4, 92, 1, 1440599036, 1),
(14, 4, 0, 1, 1441548392, 0),
(15, 3, 2, 1, 1441636826, 2),
(16, 3, 4, 1, 1441691737, 2);

-- --------------------------------------------------------

--
-- 表的结构 `ts_course_type`
--

CREATE TABLE IF NOT EXISTS `ts_course_type` (
`id` int(11) unsigned NOT NULL COMMENT '自增主键',
  `name` varchar(255) DEFAULT '' COMMENT '课程视频类型名称',
  `level` tinyint(1) NOT NULL DEFAULT '1' COMMENT '课程级别：1，企业产品培训；2，平台培训',
  `is_del` tinyint(1) DEFAULT '0' COMMENT '是否删除',
  `is_index` tinyint(1) DEFAULT '0' COMMENT '是否推荐到首页',
  `add_time` int(11) NOT NULL COMMENT '添加时间',
  `update_time` int(11) NOT NULL COMMENT '更新时间'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='课程类型表';

-- --------------------------------------------------------

--
-- 表的结构 `ts_document`
--

CREATE TABLE IF NOT EXISTS `ts_document` (
`id` int(10) unsigned NOT NULL COMMENT '文档ID',
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `name` char(40) NOT NULL DEFAULT '' COMMENT '标识',
  `title` char(80) NOT NULL DEFAULT '' COMMENT '标题',
  `category_id` int(10) unsigned NOT NULL COMMENT '所属分类',
  `description` char(140) NOT NULL DEFAULT '' COMMENT '描述',
  `root` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '根节点',
  `pid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属ID',
  `model_id` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '内容模型ID',
  `type` tinyint(3) unsigned NOT NULL DEFAULT '2' COMMENT '内容类型',
  `position` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '推荐位',
  `link_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '外链',
  `cover_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '封面',
  `display` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '可见性',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '截至时间',
  `attach` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '附件数量',
  `view` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '浏览量',
  `comment` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '评论数',
  `extend` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '扩展统计字段',
  `level` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '优先级',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '数据状态'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='文档模型基础表\r\n@author   麦当苗儿\r\n@version  2013-05-21';

-- --------------------------------------------------------

--
-- 表的结构 `ts_document_article`
--

CREATE TABLE IF NOT EXISTS `ts_document_article` (
  `id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '文档ID',
  `parse` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '内容解析类型',
  `content` text NOT NULL COMMENT '文章内容',
  `template` varchar(100) NOT NULL DEFAULT '' COMMENT '详情页显示模板',
  `bookmark` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '收藏数'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='文档模型文章表\r\n@author   麦当苗儿\r\n@version  2013-05-24';

-- --------------------------------------------------------

--
-- 表的结构 `ts_document_download`
--

CREATE TABLE IF NOT EXISTS `ts_document_download` (
  `id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '文档ID',
  `parse` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '内容解析类型',
  `content` text NOT NULL COMMENT '下载详细描述',
  `template` varchar(100) NOT NULL DEFAULT '' COMMENT '详情页显示模板',
  `file_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '文件ID',
  `download` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '下载次数',
  `size` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '文件大小'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='文档模型下载表\r\n@author   麦当苗儿\r\n@version  2013-05-24';

-- --------------------------------------------------------

--
-- 表的结构 `ts_drug`
--

CREATE TABLE IF NOT EXISTS `ts_drug` (
`id` int(10) unsigned NOT NULL,
  `pharma_id` int(10) unsigned NOT NULL COMMENT '药厂id',
  `title` varchar(200) NOT NULL COMMENT '名称',
  `bar_code` varchar(50) NOT NULL COMMENT '条形码',
  `create_time` int(10) unsigned NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='药品';

--
-- 转存表中的数据 `ts_drug`
--

INSERT INTO `ts_drug` (`id`, `pharma_id`, `title`, `bar_code`, `create_time`) VALUES
(1, 1, '药品一', '1111121', 0),
(2, 2, '思密达(蒙脱石散)', '222222', 0);

-- --------------------------------------------------------

--
-- 表的结构 `ts_drug_store`
--

CREATE TABLE IF NOT EXISTS `ts_drug_store` (
`id` int(11) unsigned NOT NULL,
  `name` varchar(100) NOT NULL COMMENT '名称',
  `region` varchar(30) NOT NULL COMMENT '地区',
  `address` varchar(255) NOT NULL COMMENT '详细地址',
  `contacts` varchar(50) NOT NULL COMMENT '联系人',
  `email` varchar(50) NOT NULL COMMENT '邮箱地址',
  `phone` varchar(20) NOT NULL COMMENT '联系电话',
  `subbranch_count` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '门店数量',
  `employee_count` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '员工数量'
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='药店';

--
-- 转存表中的数据 `ts_drug_store`
--

INSERT INTO `ts_drug_store` (`id`, `name`, `region`, `address`, `contacts`, `email`, `phone`, `subbranch_count`, `employee_count`) VALUES
(1, '药店一', '1', '宝安西乡', '药店一药店一药店一', '123@qq.com', '12423534', 12, 11),
(2, '广州市善邻药业有限公司', '1', '广州市越秀区环市东路498号', '张杰', '234@qq.com', '020-5988324324', 44, 55);

-- --------------------------------------------------------

--
-- 表的结构 `ts_exam`
--

CREATE TABLE IF NOT EXISTS `ts_exam` (
`exam_id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL COMMENT '试卷标题',
  `note` varchar(1000) DEFAULT NULL COMMENT '简介',
  `cat_id` int(11) DEFAULT NULL COMMENT '对应的学习内容',
  `state` char(1) NOT NULL DEFAULT '1' COMMENT '状态 1有效 0 无效',
  `end_time` varchar(20) DEFAULT NULL COMMENT '开始时间',
  `start_time` varchar(20) DEFAULT NULL COMMENT '结束时间',
  `create_time` varchar(20) NOT NULL COMMENT '插入时间',
  `upd_time` varchar(20) DEFAULT NULL COMMENT '修改时间',
  `e_time` int(3) DEFAULT '0' COMMENT '时间限定,单位分钟，0 不限时',
  `rate` int(2) NOT NULL DEFAULT '6' COMMENT '试题及合格率'
) ENGINE=MyISAM AUTO_INCREMENT=97 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ts_exam`
--

INSERT INTO `ts_exam` (`exam_id`, `title`, `note`, `cat_id`, `state`, `end_time`, `start_time`, `create_time`, `upd_time`, `e_time`, `rate`) VALUES
(92, '考卷名称test', NULL, 1, '1', '2015-06-20', '2015-06-04', '2015-06-12 21:46:37', '2015-06-12 21:46:37', 0, 6),
(94, 'ddddddddddddddd', NULL, NULL, '1', '0', '0', '2015-06-22 21:13:40', '2015-06-22 21:13:40', 0, 6),
(95, 'bbbbbbbbbbbbbb', NULL, NULL, '1', '0', '0', '2015-06-22 21:20:40', '2015-06-22 21:20:40', 0, 6),
(96, 'cccccccccccccccccc', NULL, 6, '1', '0', '0', '2015-06-22 21:21:14', '2015-06-22 21:21:14', 0, 6);

-- --------------------------------------------------------

--
-- 表的结构 `ts_exam_options`
--

CREATE TABLE IF NOT EXISTS `ts_exam_options` (
`opt_id` int(11) NOT NULL,
  `opt_name` varchar(50) DEFAULT NULL COMMENT '选项名称',
  `opt_value` varchar(100) DEFAULT NULL COMMENT '分值',
  `quest_id` int(11) NOT NULL COMMENT '问题ID',
  `sort_no` int(11) DEFAULT NULL COMMENT '排序',
  `state` char(1) DEFAULT NULL COMMENT '状态',
  `exam_id` int(11) DEFAULT NULL COMMENT '考卷ID'
) ENGINE=MyISAM AUTO_INCREMENT=1615 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ts_exam_options`
--

INSERT INTO `ts_exam_options` (`opt_id`, `opt_name`, `opt_value`, `quest_id`, `sort_no`, `state`, `exam_id`) VALUES
(1595, 'aaaaaaaaaaaaaa--true', '2', 125, 1, '1', 92),
(1596, 'bbbbbbbbbbbbbb', '0', 125, 2, '1', 92),
(1597, 'ccccccccccccccc', '0', 126, 3, '1', 92),
(1598, 'aaaaaaaaaaaaaa--true', '2', 126, 1, '1', 92),
(1599, 'bbbbbbbbbbbbbb', '0', 127, 2, '1', 92),
(1600, 'ccccccccccccccc--true', '2', 127, 3, '1', 92),
(1601, 'aaaaaaaaaaaaaa--true', '2', 128, 1, '1', 92),
(1602, 'bbbbbbbbbbbbbb', '0', 128, 2, '1', 92),
(1603, 'ccccccccccccccc--true', '1', 129, 3, '1', 92),
(1604, 'tttttttttt', '0', 129, 0, '1', 92),
(1605, '111111--true', '1', 129, 1, '1', 92),
(1606, '2222', '0', 130, 2, '1', 92),
(1607, '333333--true', '1', 130, 3, '1', 92),
(1608, '444444--true', '1', 130, 4, '1', 92),
(1609, '中国--true', '1', 131, 0, '1', 92),
(1610, '美国', '0', 131, 1, '1', 92),
(1611, '英国--true', '1', 131, 2, '1', 92),
(1612, '南方', '0', 132, 0, '1', 92),
(1613, '北方--true', '1', 132, 2, '1', 92),
(1614, '东方--true', '1', 132, 2, '1', 92);

-- --------------------------------------------------------

--
-- 表的结构 `ts_exam_question`
--

CREATE TABLE IF NOT EXISTS `ts_exam_question` (
`quest_id` int(11) NOT NULL,
  `title` varchar(100) DEFAULT NULL COMMENT '问题标题',
  `note` varchar(50) DEFAULT NULL COMMENT '简介',
  `exam_id` int(11) DEFAULT NULL COMMENT '考卷ID',
  `state` char(1) DEFAULT NULL COMMENT '状态',
  `upd_time` varchar(20) DEFAULT NULL COMMENT '修改时间',
  `max_value` varchar(50) DEFAULT NULL COMMENT '分值',
  `sort_no` int(11) DEFAULT NULL COMMENT '排序',
  `quest_type` int(11) DEFAULT NULL COMMENT '考题类型，1单选，2多选'
) ENGINE=MyISAM AUTO_INCREMENT=142 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ts_exam_question`
--

INSERT INTO `ts_exam_question` (`quest_id`, `title`, `note`, `exam_id`, `state`, `upd_time`, `max_value`, `sort_no`, `quest_type`) VALUES
(125, '考题名称1', NULL, 92, '1', '2015-06-14 22:14:27', '2', NULL, 1),
(126, '考题名称2', NULL, 92, '1', '2015-06-14 22:15:52', '2', NULL, 1),
(127, '考题名称3', NULL, 92, '1', '2015-06-14 22:17:30', '2', 0, 1),
(128, '考题名称4', NULL, 92, '1', '2015-06-14 22:18:16', '2', 0, 1),
(129, '考题名称5', NULL, 92, '1', '2015-06-14 22:19:38', '2', 0, 2),
(130, '考题名称6', NULL, 92, '1', '2015-06-14 22:21:49', '2', 0, 2),
(131, '考题名称7', NULL, 92, '1', '2015-06-14 22:22:17', '2', 0, 2),
(132, '考题名称8', NULL, 92, '1', '2015-06-14 22:22:39', '2', 0, 2),
(133, '考题名称11111', NULL, 95, '1', '2015-06-14 22:23:16', '5', 0, 2),
(134, '考题名称11111', NULL, 95, '1', '2015-06-14 22:24:40', '5', 0, 2),
(135, '考题名称11111', NULL, 95, '1', '2015-06-14 22:25:57', '5', 0, 2),
(136, '考题名称11111', NULL, 95, '1', '2015-06-14 22:26:15', '5', 0, 2),
(137, '考题名称11111', NULL, 95, '1', '2015-06-14 22:26:45', '5', 0, 2),
(138, '考题名称222222', NULL, 95, '1', '2015-06-14 22:28:54', '2', 0, 1),
(139, '考题名称222222', NULL, 95, '1', '2015-06-14 22:30:41', '2', 0, 1),
(140, '考题名称222222', NULL, 95, '1', '2015-06-14 22:31:30', '2', 0, 1),
(141, 'ccccc1111', NULL, 95, '1', '2015-06-22 21:23:23', '5', 0, 2);

-- --------------------------------------------------------

--
-- 表的结构 `ts_exam_survey`
--

CREATE TABLE IF NOT EXISTS `ts_exam_survey` (
`survey_id` int(11) NOT NULL,
  `exam_id` int(11) NOT NULL COMMENT '考卷ID',
  `attend_time` varchar(20) DEFAULT NULL COMMENT '参加考试时间',
  `attend_user_id` int(11) DEFAULT NULL COMMENT '参见考试用户ID',
  `attend_user` varchar(20) DEFAULT NULL COMMENT '参见考试用户',
  `state` char(1) DEFAULT '0' COMMENT '状态',
  `end_time` int(12) DEFAULT '0' COMMENT '提交时间'
) ENGINE=MyISAM AUTO_INCREMENT=204 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `ts_exam_survey_detl`
--

CREATE TABLE IF NOT EXISTS `ts_exam_survey_detl` (
`detl_id` int(11) NOT NULL,
  `survey_id` int(11) NOT NULL COMMENT '答卷ID',
  `exam_id` int(11) DEFAULT NULL COMMENT '考卷ID',
  `quest_id` int(11) NOT NULL COMMENT '题目ID',
  `opt_id` int(11) DEFAULT NULL COMMENT '答题ID ',
  `opt_name` varchar(50) DEFAULT NULL COMMENT '答题名称',
  `input_value` varchar(2000) DEFAULT NULL COMMENT '答题分数',
  `q_value` int(3) DEFAULT '0' COMMENT '得分',
  `state` char(1) DEFAULT '0' COMMENT '状态'
) ENGINE=MyISAM AUTO_INCREMENT=878 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `ts_file`
--

CREATE TABLE IF NOT EXISTS `ts_file` (
`id` int(10) unsigned NOT NULL COMMENT '文件ID',
  `name` char(30) NOT NULL DEFAULT '' COMMENT '原始文件名',
  `savename` char(20) NOT NULL DEFAULT '' COMMENT '保存名称',
  `savepath` char(30) NOT NULL DEFAULT '' COMMENT '文件保存路径',
  `ext` char(5) NOT NULL DEFAULT '' COMMENT '文件后缀',
  `mime` char(40) NOT NULL DEFAULT '' COMMENT '文件mime类型',
  `size` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '文件大小',
  `md5` char(32) NOT NULL DEFAULT '' COMMENT '文件md5',
  `sha1` char(40) NOT NULL DEFAULT '' COMMENT '文件 sha1编码',
  `location` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '文件保存位置',
  `create_time` int(10) unsigned NOT NULL COMMENT '上传时间'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='文件表\r\n@author   麦当苗儿\r\n@version  2013-05-21';

-- --------------------------------------------------------

--
-- 表的结构 `ts_gift_record`
--

CREATE TABLE IF NOT EXISTS `ts_gift_record` (
`id` int(11) unsigned NOT NULL COMMENT '自增主键',
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '兑奖人用户id',
  `oid` int(11) NOT NULL DEFAULT '0' COMMENT '礼品id',
  `score` int(11) NOT NULL DEFAULT '0' COMMENT '所花费的积分',
  `gold` int(11) NOT NULL DEFAULT '0' COMMENT '所花费的金币',
  `pay_type` tinyint(3) NOT NULL DEFAULT '1' COMMENT '支付类型：1，积分；2，金币',
  `gift_type` tinyint(3) NOT NULL DEFAULT '1' COMMENT '礼品类型：1，普通礼品；2，金币礼品',
  `time` int(11) NOT NULL DEFAULT '0' COMMENT '兑换时间'
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COMMENT='礼品兑换记录表';

--
-- 转存表中的数据 `ts_gift_record`
--

INSERT INTO `ts_gift_record` (`id`, `uid`, `oid`, `score`, `gold`, `pay_type`, `gift_type`, `time`) VALUES
(1, 3, 8, 0, 0, 1, 1, 1439879053),
(2, 3, 8, 0, 0, 1, 1, 1439879066),
(3, 3, 8, 0, 0, 1, 1, 1439879126),
(4, 3, 5, 0, 0, 1, 1, 1439879127),
(5, 3, 8, 0, 0, 1, 1, 1441105770),
(6, 3, 7, 0, 0, 1, 1, 1441105774),
(7, 3, 8, 0, 0, 1, 1, 1441684086),
(8, 3, 8, 0, 0, 1, 1, 1442191618);

-- --------------------------------------------------------

--
-- 表的结构 `ts_hooks`
--

CREATE TABLE IF NOT EXISTS `ts_hooks` (
`id` int(10) unsigned NOT NULL COMMENT '主键',
  `name` varchar(40) NOT NULL DEFAULT '' COMMENT '钩子名称',
  `description` text NOT NULL COMMENT '描述',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '类型',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `addons` varchar(255) NOT NULL DEFAULT '' COMMENT '钩子挂载的插件 ''，''分割'
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ts_hooks`
--

INSERT INTO `ts_hooks` (`id`, `name`, `description`, `type`, `update_time`, `addons`) VALUES
(1, 'pageHeader', '页面header钩子，一般用于加载插件CSS文件和代码', 1, 0, ''),
(2, 'pageFooter', '页面footer钩子，一般用于加载插件JS文件和JS代码', 1, 0, 'ReturnTop'),
(3, 'documentEditForm', '添加编辑表单的 扩展内容钩子', 1, 0, 'Attachment'),
(4, 'documentDetailAfter', '文档末尾显示', 1, 0, 'Attachment,SocialComment'),
(5, 'documentDetailBefore', '页面内容前显示用钩子', 1, 0, ''),
(6, 'documentSaveComplete', '保存文档数据后的扩展钩子', 2, 0, 'Attachment'),
(7, 'documentEditFormContent', '添加编辑表单的内容显示钩子', 1, 0, 'Editor'),
(8, 'adminArticleEdit', '后台内容编辑页编辑器', 1, 1378982734, 'EditorForAdmin'),
(13, 'AdminIndex', '首页小格子个性化显示', 1, 1382596073, 'SiteStat,SystemInfo,DevTeam,QiuBai'),
(14, 'topicComment', '评论提交方式扩展钩子。', 1, 1380163518, 'Editor'),
(16, 'app_begin', '应用开始', 2, 1384481614, 'Iswaf');

-- --------------------------------------------------------

--
-- 表的结构 `ts_kejian`
--

CREATE TABLE IF NOT EXISTS `ts_kejian` (
`id` int(11) NOT NULL,
  `title` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '课件名称',
  `kj_type` int(11) DEFAULT NULL COMMENT '可见类型',
  `kj_yc` int(11) DEFAULT NULL COMMENT '课件药厂ID',
  `state` int(11) DEFAULT '1' COMMENT '状态',
  `sort_no` int(11) DEFAULT '0' COMMENT '排序',
  `upd_time` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '修改时间',
  `create_time` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '插入时间',
  `gold` int(11) DEFAULT '0' COMMENT '金币',
  `kj_yd` int(11) DEFAULT NULL COMMENT '药店',
  `integral` int(11) DEFAULT '0' COMMENT '积分',
  `number` int(11) DEFAULT '0' COMMENT '点播次数',
  `grade` int(11) DEFAULT '0' COMMENT '分数',
  `start_time` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '发布日期',
  `end_time` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '有效日期',
  `img` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '图片',
  `video` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '视频',
  `description` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '简介',
  `yd_ids` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '适用药店'
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AVG_ROW_LENGTH=1 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;

--
-- 转存表中的数据 `ts_kejian`
--

INSERT INTO `ts_kejian` (`id`, `title`, `kj_type`, `kj_yc`, `state`, `sort_no`, `upd_time`, `create_time`, `gold`, `kj_yd`, `integral`, `number`, `grade`, `start_time`, `end_time`, `img`, `video`, `description`, `yd_ids`) VALUES
(1, 'test', 1, 1, 1, 1, '2015-06-17 12:12:00', '2015-06-17 12:12:00', 55, NULL, 44, 333, 3, '2015-06-02', '2015-06-23', NULL, 'Attachment/2015-06-22/5587f687c7da3.jpg', 'idssssssssssssddddddddddddddddddddcccccdddddd', '1,2,'),
(2, 'ttttt', 1, 2, 1, 0, NULL, NULL, 0, NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL),
(3, 'test22222', 3, NULL, 1, 21, NULL, NULL, 22, NULL, 10, 100, 30, '2015-06-22', '2015-06-23', 'Picture/2015-06-22/5587f4116b4d7.jpg', '', 'test22222222222222222222222222222222', NULL),
(4, 'ddddddddddddddd', 1, 1, 1, 0, NULL, NULL, 0, NULL, 0, 0, 0, '', '', NULL, NULL, '', NULL),
(5, 'bbbbbbbbbbbbbb', 1, 2, 1, 0, NULL, NULL, 0, NULL, 0, 0, 0, '', '', NULL, NULL, '', NULL),
(6, 'cccccccccccccccccc', 1, 1, 1, 0, NULL, NULL, 0, NULL, 0, 0, 0, '', '', NULL, NULL, '', NULL);

-- --------------------------------------------------------

--
-- 表的结构 `ts_member`
--

CREATE TABLE IF NOT EXISTS `ts_member` (
`uid` int(10) unsigned NOT NULL COMMENT '用户ID',
  `nickname` char(16) NOT NULL DEFAULT '' COMMENT '昵称',
  `sex` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '性别',
  `birthday` date NOT NULL DEFAULT '0000-00-00' COMMENT '生日',
  `qq` char(10) NOT NULL DEFAULT '' COMMENT 'qq号',
  `score` mediumint(8) NOT NULL DEFAULT '0' COMMENT '用户积分',
  `gold` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '金币',
  `login` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '登录次数',
  `reg_ip` bigint(20) NOT NULL DEFAULT '0' COMMENT '注册IP',
  `reg_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '注册时间',
  `last_login_ip` bigint(20) NOT NULL DEFAULT '0' COMMENT '最后登录IP',
  `last_login_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后登录时间',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '会员状态',
  `truename` varchar(20) NOT NULL DEFAULT '' COMMENT '真实姓名',
  `head` varchar(255) NOT NULL DEFAULT '' COMMENT '头像',
  `job` varchar(30) NOT NULL DEFAULT '' COMMENT '职位',
  `subbranch_id` int(11) NOT NULL DEFAULT '0' COMMENT '分店id',
  `login_times` tinyint(3) NOT NULL DEFAULT '0' COMMENT '连续登陆次数',
  `login_score` int(11) NOT NULL DEFAULT '0' COMMENT '累积登陆获得的积分数'
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='会员表\r\n@author   麦当苗儿\r\n@version  2013-05-27';

--
-- 转存表中的数据 `ts_member`
--

INSERT INTO `ts_member` (`uid`, `nickname`, `sex`, `birthday`, `qq`, `score`, `gold`, `login`, `reg_ip`, `reg_time`, `last_login_ip`, `last_login_time`, `status`, `truename`, `head`, `job`, `subbranch_id`, `login_times`, `login_score`) VALUES
(1, 'Administrator', 0, '0000-00-00', '', 100, 0, 22, 0, 1433383014, 989565615, 1442150817, 1, '', '', '', 0, 0, 0),
(3, '18316852257', 1, '0000-00-00', '', 40, 30, 18, 1902544084, 1436357167, 235978400, 1441929598, 1, '张三', 'http://y1.ifengimg.com/a/2015_33/331b789ca556b8e.jpg', '店长', 1, 2, 10),
(4, '18682077706', 0, '0000-00-00', '', 63, 20, 13, 1902544084, 1436357223, 3659273354, 1442470391, 1, '', '', '', 1, 1, 8);

-- --------------------------------------------------------

--
-- 表的结构 `ts_menu`
--

CREATE TABLE IF NOT EXISTS `ts_menu` (
`id` int(10) unsigned NOT NULL COMMENT '文档ID',
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '标题',
  `pid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级分类ID',
  `sort` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '排序（同级有效）',
  `url` char(255) NOT NULL DEFAULT '' COMMENT '链接地址',
  `hide` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否隐藏',
  `tip` varchar(255) NOT NULL DEFAULT '' COMMENT '提示',
  `group` varchar(50) DEFAULT '' COMMENT '分组',
  `is_dev` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否仅开发者模式可见'
) ENGINE=InnoDB AUTO_INCREMENT=143 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ts_menu`
--

INSERT INTO `ts_menu` (`id`, `title`, `pid`, `sort`, `url`, `hide`, `tip`, `group`, `is_dev`) VALUES
(1, '首页', 0, 0, 'Index/index', 0, '', NULL, 0),
(2, '内容', 0, 0, 'Article/mydocument', 0, '', NULL, 0),
(3, '文档列表', 2, 0, 'article/index', 1, '', '内容', 0),
(4, '新增', 3, 0, 'article/add', 0, '', NULL, 0),
(5, '编辑', 3, 0, 'article/edit', 0, '', NULL, 0),
(6, '改变状态', 3, 0, 'article/setStatus', 0, '', NULL, 0),
(7, '保存', 3, 0, 'article/update', 0, '', NULL, 0),
(8, '保存草稿', 3, 0, 'article/autoSave', 0, '', NULL, 0),
(9, '移动', 3, 0, 'article/move', 0, '', NULL, 0),
(10, '复制', 3, 0, 'article/copy', 0, '', NULL, 0),
(11, '粘贴', 3, 0, 'article/paste', 0, '', NULL, 0),
(12, '导入', 3, 0, 'article/batchOperate', 0, '', NULL, 0),
(13, '回收站', 2, 0, 'article/recycle', 1, '', '内容', 0),
(14, '还原', 13, 0, 'article/permit', 0, '', NULL, 0),
(15, '清空', 13, 0, 'article/clear', 0, '', NULL, 0),
(16, '用户', 0, 0, 'User/index', 0, '', NULL, 0),
(17, '用户信息', 16, 0, 'User/index', 0, '', '用户管理', 0),
(18, '新增用户', 17, 0, 'User/add', 0, '添加新用户', NULL, 0),
(19, '用户行为', 16, 0, 'User/action', 0, '', '用户管理', 0),
(20, '新增用户行为', 19, 0, 'User/addAction', 0, '"用户->用户行为"中的新增', NULL, 0),
(21, '编辑用户行为', 19, 0, 'User/editAction', 0, '"用户->用户行为"点击标题进行编辑', NULL, 0),
(22, '保存用户行为', 19, 0, 'User/saveAction', 0, '"用户->用户行为"保存编辑和新增的用户行为', NULL, 0),
(23, '变更行为状态', 19, 0, 'User/setStatus', 0, '"用户->用户行为"中的启用,禁用和删除权限', NULL, 0),
(24, '禁用会员', 19, 0, 'User/changeStatus?method=forbidUser', 0, '"用户->用户信息"中的禁用', NULL, 0),
(25, '启用会员', 19, 0, 'User/changeStatus?method=resumeUser', 0, '"用户->用户信息"中的启用', NULL, 0),
(26, '删除会员', 19, 0, 'User/changeStatus?method=deleteUser', 0, '"用户->用户信息"中的删除', NULL, 0),
(27, '权限管理', 16, 0, 'AuthManager/index', 0, '', '', 0),
(28, '删除', 27, 0, 'AuthManager/changeStatus?method=deleteGroup', 0, '删除用户组', NULL, 0),
(29, '禁用', 27, 0, 'AuthManager/changeStatus?method=forbidGroup', 0, '禁用用户组', NULL, 0),
(30, '恢复', 27, 0, 'AuthManager/changeStatus?method=resumeGroup', 0, '恢复已禁用的用户组', NULL, 0),
(31, '新增', 27, 0, 'AuthManager/createGroup', 0, '创建新的用户组', NULL, 0),
(32, '编辑', 27, 0, 'AuthManager/editGroup', 0, '编辑用户组名称和描述', NULL, 0),
(33, '保存用户组', 27, 0, 'AuthManager/writeGroup', 0, '新增和编辑用户组的"保存"按钮', NULL, 0),
(34, '授权', 27, 0, 'AuthManager/group', 0, '"后台 \\ 用户 \\ 用户信息"列表页的"授权"操作按钮,用于设置用户所属用户组', NULL, 0),
(35, '访问授权', 27, 0, 'AuthManager/access', 0, '"后台 \\ 用户 \\ 权限管理"列表页的"访问授权"操作按钮', NULL, 0),
(36, '成员授权', 27, 0, 'AuthManager/user', 0, '"后台 \\ 用户 \\ 权限管理"列表页的"成员授权"操作按钮', NULL, 0),
(37, '解除授权', 27, 0, 'AuthManager/removeFromGroup', 0, '"成员授权"列表页内的解除授权操作按钮', NULL, 0),
(38, '保存成员授权', 27, 0, 'AuthManager/addToGroup', 0, '"用户信息"列表页"授权"时的"保存"按钮和"成员授权"里右上角的"添加"按钮)', NULL, 0),
(39, '分类授权', 27, 0, 'AuthManager/category', 0, '"后台 \\ 用户 \\ 权限管理"列表页的"分类授权"操作按钮', NULL, 0),
(40, '保存分类授权', 27, 0, 'AuthManager/addToCategory', 0, '"分类授权"页面的"保存"按钮', NULL, 0),
(41, '模型授权', 27, 0, 'AuthManager/modelauth', 0, '"后台 \\ 用户 \\ 权限管理"列表页的"模型授权"操作按钮', NULL, 0),
(42, '保存模型授权', 27, 0, 'AuthManager/addToModel', 0, '"分类授权"页面的"保存"按钮', NULL, 0),
(43, '扩展', 0, 5, 'Addons/index', 0, '', '', 0),
(44, '插件管理', 43, 0, 'Addons/index', 0, '', '扩展', 0),
(45, '创建', 44, 0, 'Addons/create', 0, '服务器上创建插件结构向导', NULL, 0),
(46, '检测创建', 44, 0, 'Addons/checkForm', 0, '检测插件是否可以创建', NULL, 0),
(47, '预览', 44, 0, 'Addons/preview', 0, '预览插件定义类文件', NULL, 0),
(48, '快速生成插件', 44, 0, 'Addons/build', 0, '开始生成插件结构', NULL, 0),
(49, '设置', 44, 0, 'Addons/config', 0, '设置插件配置', NULL, 0),
(50, '禁用', 44, 0, 'Addons/disable', 0, '禁用插件', NULL, 0),
(51, '启用', 44, 0, 'Addons/enable', 0, '启用插件', NULL, 0),
(52, '安装', 44, 0, 'Addons/install', 0, '安装插件', NULL, 0),
(53, '卸载', 44, 0, 'Addons/uninstall', 0, '卸载插件', NULL, 0),
(54, '更新配置', 44, 0, 'Addons/saveconfig', 0, '更新插件配置处理', NULL, 0),
(55, '插件后台列表', 44, 0, 'Addons/adminList', 0, '', NULL, 0),
(56, 'URL方式访问插件', 44, 0, 'Addons/execute', 0, '控制是否有权限通过url访问插件控制器方法', NULL, 0),
(57, '钩子管理', 43, 0, 'Addons/hooks', 0, '', '扩展', 0),
(58, '模型管理', 68, 0, 'Model/index', 0, '', '系统设置', 0),
(59, '新增', 58, 0, 'model/add', 0, '', NULL, 0),
(60, '编辑', 58, 0, 'model/edit', 0, '', NULL, 0),
(61, '改变状态', 58, 0, 'model/setStatus', 0, '', NULL, 0),
(62, '保存数据', 58, 0, 'model/update', 0, '', NULL, 0),
(63, '属性管理', 68, 0, 'Attribute/index', 1, '', '', 0),
(64, '新增', 63, 0, 'Attribute/add', 0, '', NULL, 0),
(65, '编辑', 63, 0, 'Attribute/edit', 0, '', NULL, 0),
(66, '改变状态', 63, 0, 'Attribute/setStatus', 0, '', NULL, 0),
(67, '保存数据', 63, 0, 'Attribute/update', 0, '', NULL, 0),
(68, '系统', 0, 0, 'Config/group', 0, '', NULL, 0),
(69, '网站设置', 68, 0, 'Config/group', 0, '', '系统设置', 0),
(70, '配置管理', 68, 0, 'Config/index', 0, '', '系统设置', 0),
(71, '编辑', 70, 0, 'Config/edit', 0, '新增编辑和保存配置', NULL, 0),
(72, '删除', 70, 0, 'Config/del', 0, '删除配置', NULL, 0),
(73, '新增', 70, 0, 'Config/add', 0, '新增配置', NULL, 0),
(74, '保存', 70, 0, 'Config/save', 0, '保存配置', NULL, 0),
(75, '菜单管理', 68, 0, 'Menu/index', 0, '', '系统设置', 0),
(76, '导航管理', 68, 0, 'Channel/index', 0, '', '系统设置', 0),
(77, '新增', 76, 0, 'Channel/add', 0, '', NULL, 0),
(78, '编辑', 76, 0, 'Channel/edit', 0, '', NULL, 0),
(79, '删除', 76, 0, 'Channel/del', 0, '', NULL, 0),
(80, '分类管理', 68, 0, 'Category/index', 0, '', '系统设置', 0),
(81, '编辑', 80, 0, 'Category/edit', 0, '编辑和保存栏目分类', NULL, 0),
(82, '新增', 80, 0, 'Category/add', 0, '新增栏目分类', NULL, 0),
(83, '删除', 80, 0, 'Category/remove', 0, '删除栏目分类', NULL, 0),
(84, '移动', 80, 0, 'Category/move', 0, '移动栏目分类', NULL, 0),
(85, '合并', 80, 0, 'Category/merge', 0, '合并栏目分类', NULL, 0),
(86, '备份数据库', 68, 0, 'Database/index?type=export', 0, '', '数据备份', 0),
(87, '备份', 86, 0, 'Database/export', 0, '备份数据库', NULL, 0),
(88, '优化表', 86, 0, 'Database/optimize', 0, '优化数据表', NULL, 0),
(89, '修复表', 86, 0, 'Database/repair', 0, '修复数据表', NULL, 0),
(90, '还原数据库', 68, 0, 'Database/index?type=import', 0, '', '数据备份', 0),
(91, '恢复', 90, 0, 'Database/import', 0, '数据库恢复', NULL, 0),
(92, '删除', 90, 0, 'Database/del', 0, '删除备份文件', NULL, 0),
(93, '其他', 0, 0, 'other', 1, '', NULL, 0),
(96, '新增', 75, 0, 'Menu/add', 0, '', '系统设置', 0),
(98, '编辑', 75, 0, 'Menu/edit', 0, '', '', 0),
(102, '应用', 0, 0, 'Think/lists?model=config', 0, '', '', 0),
(104, '下载管理', 102, 0, 'Think/lists?model=download', 0, '', '', 0),
(105, '配置管理', 102, 0, 'Think/lists?model=config', 0, '', '', 0),
(106, '行为日志', 16, 0, 'Action/actionlog', 0, '', '行为管理', 0),
(108, '修改密码', 16, 0, 'User/updatePassword', 1, '', '', 0),
(109, '修改昵称', 16, 0, 'User/updateNickname', 1, '', '', 0),
(110, '查看行为日志', 106, 0, 'action/edit', 1, '', '', 0),
(112, '新增数据', 58, 0, 'think/add', 1, '', '', 0),
(113, '编辑数据', 58, 0, 'think/edit', 1, '', '', 0),
(114, '导入', 75, 0, 'Menu/import', 0, '', '', 0),
(115, '生成', 58, 0, 'Model/generate', 0, '', '', 0),
(116, '新增钩子', 57, 0, 'Addons/addHook', 0, '', '', 0),
(117, '编辑钩子', 57, 0, 'Addons/edithook', 0, '', '', 0),
(118, '课件管理', 2, 2, 'Course/Index', 0, '课件管理', '培训管理', 0),
(119, '新增课件', 118, 0, 'Course/add', 0, '新增课件', '', 0),
(120, '删除课件', 118, 2, 'Course/delete', 0, '删除课件', '', 0),
(121, '考卷管理', 2, 2, 'Exam/index', 0, '考卷管理', '培训管理', 0),
(122, '题目管理', 121, 0, 'Exam/question', 0, '题目管理', '', 0),
(123, '添加考卷', 121, 0, 'Exam/addExam', 0, '添加考卷', '', 0),
(124, '修改考卷', 121, 0, 'Exam/editExam', 0, '修改考卷', '', 0),
(125, '删除考卷', 121, 0, 'Exam/delExam', 0, '删除考卷', '', 0),
(126, '添加试题', 121, 0, 'Exam/addQuest', 0, '添加试题', '', 0),
(127, '删除试题', 121, 0, 'Exam/delQuest', 0, '删除试题', '', 0),
(128, '药店管理', 2, 3, 'DrugStore/Index', 0, '药店管理', '药店管理', 0),
(129, '新增课件', 128, 0, 'DrugStore/add', 0, '新增药店', '', 0),
(130, '删除课件', 128, 2, 'DrugStore/delete', 0, '删除药店', '', 0),
(131, '药品管理', 2, 4, 'Drug/Index', 0, '药品管理', '药品管理 ', 0),
(132, '新增药品', 131, 0, 'Drug/add', 0, '新增药品', '', 0),
(133, '删除药品', 131, 2, 'Drug/delete', 0, '删除药品', '', 0),
(134, '药厂管理', 2, 4, 'Pharma/Index', 0, '药厂管理', '药厂管理 ', 0),
(135, '新增药厂', 134, 0, 'Pharma/add', 0, '新增药厂', '', 0),
(136, '删除药厂', 134, 2, 'Pharma/delete', 0, '删除药厂', '', 0),
(137, '活动管理', 2, 5, 'Activity/index', 0, '活动管理', '活动管理', 0),
(138, '新增活动', 137, 0, 'Activity/add', 0, '新增活动', '', 0),
(139, '删除活动', 137, 2, 'Activity/delete', 0, '删除活动', '', 0),
(140, '广告管理', 2, 0, 'Advertise/index', 0, '广告管理', '广告管理', 0),
(141, '新增广告', 140, 0, 'Advertise/add', 0, '新增广告', '', 0),
(142, '删除广告', 140, 2, 'Advertise/delete', 0, '删除广告', '', 0);

-- --------------------------------------------------------

--
-- 表的结构 `ts_model`
--

CREATE TABLE IF NOT EXISTS `ts_model` (
`id` int(10) unsigned NOT NULL COMMENT '模型ID',
  `name` char(30) NOT NULL DEFAULT '' COMMENT '模型标识',
  `title` char(30) NOT NULL DEFAULT '' COMMENT '模型名称',
  `extend` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '继承的模型',
  `relation` varchar(30) NOT NULL DEFAULT '' COMMENT '继承与被继承模型的关联字段',
  `need_pk` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '新建表时是否需要主键字段',
  `field_sort` text NOT NULL COMMENT '表单字段排序',
  `field_group` varchar(255) NOT NULL DEFAULT '1:基础' COMMENT '字段分组',
  `attribute_list` text NOT NULL COMMENT '属性列表（表的字段）',
  `template_list` varchar(100) NOT NULL DEFAULT '' COMMENT '列表模板',
  `template_add` varchar(100) NOT NULL DEFAULT '' COMMENT '新增模板',
  `template_edit` varchar(100) NOT NULL DEFAULT '' COMMENT '编辑模板',
  `list_grid` text NOT NULL COMMENT '列表定义',
  `list_row` smallint(2) unsigned NOT NULL DEFAULT '10' COMMENT '列表数据长度',
  `search_key` varchar(50) NOT NULL DEFAULT '' COMMENT '默认搜索字段',
  `search_list` varchar(255) NOT NULL DEFAULT '' COMMENT '高级搜索的字段',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态'
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=utf8 COMMENT='文档模型表\r\n@author   麦当苗儿\r\n@version  2013-06-19';

--
-- 转存表中的数据 `ts_model`
--

INSERT INTO `ts_model` (`id`, `name`, `title`, `extend`, `relation`, `need_pk`, `field_sort`, `field_group`, `attribute_list`, `template_list`, `template_add`, `template_edit`, `list_grid`, `list_row`, `search_key`, `search_list`, `create_time`, `update_time`, `status`) VALUES
(1, 'document', '基础文档', 0, '', 1, '{"1":["1","2","3","4","5","6","7","8","9","10","11","12","13","14","15","16","17","18","19","20","21","22"]}', '1:基础', '', '', '', '', 'id:编号\r\ntitle:标题:article/index?cate_id=[category_id]&pid=[id]\r\ntype|get_document_type:类型\r\nlevel:优先级\r\nupdate_time|time_format:最后更新\r\nstatus_text:状态\r\nview:浏览\r\nid:操作:[EDIT]&cate_id=[category_id]|编辑,article/setstatus?status=-1&ids=[id]|删除', 0, '', '', 1383891233, 1384507827, 1),
(2, 'article', '文章文档', 1, '', 1, '{"1":["2","3","5","24"],"2":["23","14","25","11","9","15","16","10","17","19","13","20","12","26"]}', '1:基础,2:扩展', '', '', '', '', 'id:编号\r\ntitle:标题:article/edit?cate_id=[category_id]&id=[id]\r\ncontent:内容', 0, '', '', 1383891243, 1384510970, 1),
(3, 'download', '下载文档', 1, '', 1, '{"1":["27","28","29","30","31","32","1","2","3","4","5","6","7","8","9","10","11","12","13","14","15","16","17","18","19","20","21","22"]}', '1:基础,2:扩展', '', '', '', '', 'id:编号\r\ntitle:标题', 0, '', '', 1383891252, 1384225274, 1),
(4, 'action', '行为定义', 0, '', 1, '{"1":["33","34","35","36","37","38","39"]}', '1:基础', '', '', '', '', 'id:编号', 0, '', '', 1383891262, 1383891784, 1),
(5, 'action_log', '行为日志', 0, '', 1, '{"1":["40","41","42","43","44","45","46","47"]}', '1:基础', '', '', '', '', 'id:编号', 0, '', '', 1383891340, 1383891802, 1),
(6, 'addons', '插件', 0, '', 1, '{"1":["48","49","50","51","52","53","54","55","56"]}', '1:基础', '', '', '', '', 'id:编号', 0, '', '', 1383891396, 1383891828, 1),
(7, 'attachment', '附件', 0, '', 1, '{"1":["57","58","59","60","61","62","63","64","65","66","67","68"]}', '1:基础', '', '', '', '', 'id:编号', 0, '', '', 1383891410, 1383891847, 1),
(8, 'attribute', '属性', 0, '', 1, '{"1":["69","70","71","72","73","74","75","76","77","78","79","80","81"]}', '1:基础', '', '', '', '', 'id:编号', 0, '', '', 1383891441, 1383891888, 1),
(9, 'category', '分类', 0, '', 1, '{"1":["82","83","84","85","86","87","88","89","90","91","92","93","94","95","96","97","98","99","100","101","102","103","104","105"]}', '1:基础', '', '', '', '', 'id:编号', 0, '', '', 1383891453, 1383891904, 1),
(10, 'channel', '前台菜单', 0, '', 1, '{"1":["106","107","108","109","110","111","112"]}', '1:基础', '', '', '', '', 'id:编号', 0, '', '', 1383891460, 1383891939, 1),
(11, 'config', '配置', 0, '', 1, '{"1":["113","114","115","116","117","118","119","120","121","122","123"]}', '1:基础', '', '', '', '', 'id:编号\r\nname:名称:edit?id=[id]&model=[MODEL]\r\ntitle:配置', 0, 'title|name', '', 1383891466, 1384248402, 1),
(12, 'file', '文件上传', 0, '', 1, '{"1":["124","125","126","127","128","129","130","131","132","133"]}', '1:基础', '', '', '', '', 'id:编号', 0, '', '', 1383891474, 1383891964, 1),
(13, 'hooks', '钩子', 0, '', 1, '{"1":["134","135","136","137","138"]}', '1:基础', '', '', '', '', 'id:编号', 0, '', '', 1383891480, 1384495820, 1),
(14, 'member', '用户信息', 0, '', 1, '{"1":["139","140","141","142","143","144","145","146","147","148","149","150"]}', '1:基础', '', '', '', '', 'id:编号', 0, '', '', 1383891488, 1384151998, 1),
(15, 'menu', '后台菜单', 0, '', 1, '{"1":["151","152","153","154","155","156","157","158"]}', '1:基础', '', '', '', '', 'id:编号', 0, '', '', 1383891503, 1383892107, 1),
(16, 'model', '模型', 0, '', 1, '{"1":["159","160","161","162","163","164","165","166","167","168","169","170","171","172","173","174","175","176"]}', '1:基础', '', '', '', '', 'id:编号', 0, '', '', 1383891514, 1383892121, 1),
(17, 'picture', '图片上传', 0, '', 1, '{"1":["177","178","179","180","181","182"]}', '1:基础', '', '', '', '', 'id:编号', 0, '', '', 1383891522, 1383892140, 1),
(18, 'url', '链接管理', 0, '', 1, '{"1":["183","184","185","186"]}', '1:基础', '', '', '', '', 'id:编号', 0, '', '', 1383891531, 1383892155, 1);

-- --------------------------------------------------------

--
-- 表的结构 `ts_order`
--

CREATE TABLE IF NOT EXISTS `ts_order` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL COMMENT '名称',
  `score` int(11) NOT NULL COMMENT '积分',
  `order_ico` varchar(200) NOT NULL COMMENT '图标',
  `sort` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `is_hide` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否隐藏',
  `type` tinyint(3) NOT NULL DEFAULT '1' COMMENT '礼品类型：1，普通礼品；2，金币礼品'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='订单';

--
-- 转存表中的数据 `ts_order`
--

INSERT INTO `ts_order` (`id`, `name`, `score`, `order_ico`, `sort`, `is_hide`, `type`) VALUES
(1, '普通礼品1', 3, 'http://material.mediav.com/upload/20150817/0.751931001439796008.jpg', 0, 0, 1),
(2, '普通礼品2', 5, 'http://material.mediav.com/upload/20150817/0.751931001439796008.jpg', 1, 0, 1),
(3, '普通礼品3', 2, 'http://material.mediav.com/upload/20150817/0.751931001439796008.jpg', 2, 0, 1),
(4, '普通礼品4', 3, 'http://material.mediav.com/upload/20150817/0.751931001439796008.jpg', 3, 0, 1),
(5, '金币礼品1', 10, 'http://material.mediav.com/upload/20150817/0.751931001439796008.jpg', 4, 0, 2),
(6, '金币礼品2', 20, 'http://material.mediav.com/upload/20150817/0.751931001439796008.jpg', 5, 0, 2),
(7, '金币礼品3', 10, 'http://material.mediav.com/upload/20150817/0.751931001439796008.jpg', 6, 0, 2),
(8, '金币礼品4', 30, 'http://material.mediav.com/upload/20150817/0.751931001439796008.jpg', 7, 0, 2);

-- --------------------------------------------------------

--
-- 表的结构 `ts_pharma`
--

CREATE TABLE IF NOT EXISTS `ts_pharma` (
`id` int(10) unsigned NOT NULL,
  `title` varchar(100) NOT NULL COMMENT '厂名',
  `region` varchar(30) NOT NULL COMMENT '地区',
  `address` varchar(200) NOT NULL COMMENT '详细地址',
  `create_time` int(10) unsigned NOT NULL,
  `update_time` int(10) unsigned NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='药厂';

--
-- 转存表中的数据 `ts_pharma`
--

INSERT INTO `ts_pharma` (`id`, `title`, `region`, `address`, `create_time`, `update_time`) VALUES
(1, '山东淮坊海王医药有限公司', '山东', '', 0, 0),
(2, '上海雷允上药业有限公司', '上海市', '', 0, 0),
(3, '杭州华东医药集团有限公司 ', '', '', 0, 0);

-- --------------------------------------------------------

--
-- 表的结构 `ts_picture`
--

CREATE TABLE IF NOT EXISTS `ts_picture` (
`id` int(10) unsigned NOT NULL COMMENT '主键id自增',
  `path` varchar(255) NOT NULL DEFAULT '' COMMENT '路径',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT '图片链接',
  `md5` char(32) NOT NULL DEFAULT '' COMMENT '文件md5',
  `sha1` char(40) NOT NULL DEFAULT '' COMMENT '文件 sha1编码',
  `status` tinyint(2) NOT NULL DEFAULT '0' COMMENT '状态',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- 表的结构 `ts_port`
--

CREATE TABLE IF NOT EXISTS `ts_port` (
`interface_id` int(11) NOT NULL,
  `interface_type` int(11) NOT NULL DEFAULT '0' COMMENT '接口类型',
  `interface_name` varchar(200) NOT NULL DEFAULT '' COMMENT '接口名称',
  `controller_name` varchar(200) NOT NULL DEFAULT '' COMMENT '控制器名称',
  `action_name` varchar(200) NOT NULL DEFAULT '' COMMENT '方法名称',
  `interface_intro` varchar(512) NOT NULL DEFAULT '' COMMENT '接口说明',
  `mtime` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `ts_port_category`
--

CREATE TABLE IF NOT EXISTS `ts_port_category` (
`cate_id` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL DEFAULT '0' COMMENT '父id',
  `cate_name` varchar(200) NOT NULL DEFAULT '' COMMENT '分类名称',
  `cate_state` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态',
  `mtime` int(11) NOT NULL DEFAULT '0' COMMENT '修改时间'
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ts_port_category`
--

INSERT INTO `ts_port_category` (`cate_id`, `parent_id`, `cate_name`, `cate_state`, `mtime`) VALUES
(1, 0, '文章', 0, 0),
(2, 0, '试题', 0, 0),
(3, 0, '视频', 0, 0),
(4, 0, '药店', 0, 0),
(5, 0, '积分', 0, 0),
(6, 0, '活动', 0, 0),
(7, 0, '用户', 0, 0),
(8, 0, '搜索', 0, 0);

-- --------------------------------------------------------

--
-- 表的结构 `ts_port_value`
--

CREATE TABLE IF NOT EXISTS `ts_port_value` (
`port_id` int(11) NOT NULL,
  `interface_id` int(11) NOT NULL DEFAULT '0' COMMENT '接口类型id',
  `port_key` varchar(200) NOT NULL DEFAULT '' COMMENT '字段名称',
  `port_value` varchar(200) NOT NULL DEFAULT '' COMMENT '字段默认值',
  `prot_name` varchar(200) NOT NULL DEFAULT '' COMMENT '字段描述',
  `is_must` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否必填'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `ts_subbranch`
--

CREATE TABLE IF NOT EXISTS `ts_subbranch` (
`id` int(10) unsigned NOT NULL,
  `name` varchar(60) NOT NULL DEFAULT '' COMMENT '分店名称',
  `region` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '地区',
  `address` varchar(200) NOT NULL DEFAULT '' COMMENT '详细地址',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0',
  `store_id` int(11) NOT NULL DEFAULT '0' COMMENT '药店id'
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='分店';

--
-- 转存表中的数据 `ts_subbranch`
--

INSERT INTO `ts_subbranch` (`id`, `name`, `region`, `address`, `create_time`, `update_time`, `store_id`) VALUES
(1, '亚洲大药房东滨路分店', 1, '深圳市南山区东滨路分店', 0, 0, 1);

-- --------------------------------------------------------

--
-- 表的结构 `ts_ucenter_admin`
--

CREATE TABLE IF NOT EXISTS `ts_ucenter_admin` (
`id` int(10) unsigned NOT NULL COMMENT '管理员ID',
  `member_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '管理员用户ID',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '管理员状态'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='管理员表';

-- --------------------------------------------------------

--
-- 表的结构 `ts_ucenter_app`
--

CREATE TABLE IF NOT EXISTS `ts_ucenter_app` (
`id` int(10) unsigned NOT NULL COMMENT '应用ID',
  `title` varchar(30) NOT NULL COMMENT '应用名称',
  `url` varchar(100) NOT NULL COMMENT '应用URL',
  `ip` char(15) NOT NULL COMMENT '应用IP',
  `auth_key` varchar(100) NOT NULL COMMENT '加密KEY',
  `sys_login` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '同步登陆',
  `allow_ip` varchar(255) NOT NULL COMMENT '允许访问的IP',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '应用状态'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='应用表';

-- --------------------------------------------------------

--
-- 表的结构 `ts_ucenter_member`
--

CREATE TABLE IF NOT EXISTS `ts_ucenter_member` (
`id` int(10) unsigned NOT NULL COMMENT '用户ID',
  `username` char(16) NOT NULL COMMENT '用户名',
  `password` char(32) NOT NULL COMMENT '密码',
  `email` char(32) NOT NULL COMMENT '用户邮箱',
  `mobile` char(15) NOT NULL COMMENT '用户手机',
  `reg_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '注册时间',
  `reg_ip` bigint(20) NOT NULL DEFAULT '0' COMMENT '注册IP',
  `last_login_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后登录时间',
  `last_login_ip` bigint(20) NOT NULL DEFAULT '0' COMMENT '最后登录IP',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(4) DEFAULT '0' COMMENT '用户状态'
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='用户表';

--
-- 转存表中的数据 `ts_ucenter_member`
--

INSERT INTO `ts_ucenter_member` (`id`, `username`, `password`, `email`, `mobile`, `reg_time`, `reg_ip`, `last_login_time`, `last_login_ip`, `update_time`, `status`) VALUES
(1, 'Administrator', '61e583a0bc8d881e9c8dec32ed735881', 'tanggsh@163.com', '', 1433383014, 2130706433, 1442150817, 989565615, 1433383014, 1),
(2, 'test', 'fdcc80357df7b0c13c82890ec62ab84f', 'test@qq.com', '', 0, 0, 0, 0, 0, 1),
(3, '18316852257', 'fdcc80357df7b0c13c82890ec62ab84f', '18316852257@qq.com', '18316852257', 1436357135, 1902544084, 1441929598, 235978400, 1436357135, 1),
(4, '18682077706', 'fdcc80357df7b0c13c82890ec62ab84f', '18682077706@qq.com', '18682077706', 1436357208, 1902544084, 1442470391, 3659273354, 1436357208, 1);

-- --------------------------------------------------------

--
-- 表的结构 `ts_ucenter_setting`
--

CREATE TABLE IF NOT EXISTS `ts_ucenter_setting` (
`id` int(10) unsigned NOT NULL COMMENT '设置ID',
  `type` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '配置类型（1-用户配置）',
  `value` text NOT NULL COMMENT '配置数据'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='设置表';

-- --------------------------------------------------------

--
-- 表的结构 `ts_url`
--

CREATE TABLE IF NOT EXISTS `ts_url` (
`id` int(11) unsigned NOT NULL COMMENT '链接唯一标识',
  `url` char(255) NOT NULL DEFAULT '' COMMENT '链接地址',
  `short` char(100) NOT NULL DEFAULT '' COMMENT '短网址',
  `status` tinyint(2) NOT NULL DEFAULT '2' COMMENT '状态',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='链接表';

-- --------------------------------------------------------

--
-- 表的结构 `ts_user_focus`
--

CREATE TABLE IF NOT EXISTS `ts_user_focus` (
`id` int(11) unsigned NOT NULL,
  `item_id` int(11) DEFAULT NULL,
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `focus_time` int(11) NOT NULL DEFAULT '0' COMMENT '关注时间',
  `focus_type` tinyint(3) NOT NULL DEFAULT '1' COMMENT '关注类型：1，课程；2，活动'
) ENGINE=MyISAM AUTO_INCREMENT=25 DEFAULT CHARSET=utf8 COMMENT='课程关注表';

--
-- 转存表中的数据 `ts_user_focus`
--

INSERT INTO `ts_user_focus` (`id`, `item_id`, `uid`, `focus_time`, `focus_type`) VALUES
(24, 3, 4, 1441693879, 1),
(8, 2, 1, 1439219159, 2),
(18, 1, 1, 1439823210, 1),
(19, 1, 3, 1439879140, 1),
(23, 1, 4, 1441639618, 1);

-- --------------------------------------------------------

--
-- 表的结构 `ts_yaochang`
--

CREATE TABLE IF NOT EXISTS `ts_yaochang` (
`id` int(11) NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '药厂名称',
  `province_id` int(11) DEFAULT NULL COMMENT '省份',
  `city_id` int(11) DEFAULT NULL COMMENT '城市',
  `area_id` int(11) DEFAULT NULL COMMENT '区',
  `address` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '详细地址',
  `upd_time` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '更新时间',
  `create_time` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '创建时间',
  `state` int(1) DEFAULT NULL COMMENT '状态',
  `sort_no` int(11) DEFAULT '1' COMMENT '排序',
  `delete` int(11) DEFAULT '0' COMMENT '1 删除'
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;

--
-- 转存表中的数据 `ts_yaochang`
--

INSERT INTO `ts_yaochang` (`id`, `name`, `province_id`, `city_id`, `area_id`, `address`, `upd_time`, `create_time`, `state`, `sort_no`, `delete`) VALUES
(1, '测试药厂', 1, 2, 3, '测试地址', '2015-07-05 17:27:15', '2015-06-29 10:10:00', 0, 0, 0),
(2, '测试药厂222223333', 1, 2, 3, '测试药厂222223333', '2015-07-05 18:15:05', '2015-06-29 10:10:00', 0, 1, 0),
(4, '测试3', 1, 1, 1, '测试3地址', '2015-07-05 17:26:29', '2015-06-29 10:10:00', 0, 2, 1);

-- --------------------------------------------------------

--
-- 表的结构 `ts_yaodian`
--

CREATE TABLE IF NOT EXISTS `ts_yaodian` (
`id` int(11) NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '药店名称',
  `province_id` int(11) DEFAULT NULL COMMENT '省份',
  `city_id` int(11) DEFAULT NULL COMMENT '城市',
  `area_id` int(11) DEFAULT NULL COMMENT '区域',
  `address` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '地址',
  `linkman` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '联系人',
  `email` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '邮件',
  `phone` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '号码',
  `md_number` int(11) DEFAULT NULL COMMENT '门店数量',
  `yg_number` int(11) DEFAULT NULL COMMENT '员工数量',
  `state` int(1) DEFAULT NULL COMMENT '状态',
  `upd_time` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '修改时间',
  `create_time` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '创建时间',
  `sort_no` int(11) DEFAULT '1' COMMENT '排序',
  `delete` int(11) DEFAULT '0' COMMENT '1 删除'
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- 转存表中的数据 `ts_yaodian`
--

INSERT INTO `ts_yaodian` (`id`, `name`, `province_id`, `city_id`, `area_id`, `address`, `linkman`, `email`, `phone`, `md_number`, `yg_number`, `state`, `upd_time`, `create_time`, `sort_no`, `delete`) VALUES
(1, '测试药店111', 1, 11, 1, '测试药店111', '杨生', 'test@admin.com', '13777777777', 52, 100, 1, '2015-07-05 19:18:45', '2015-06-29 10:10:00', 1, 0),
(2, '测试药店22', 1, 2, 3, '测试药店地址', '杨生', 'test@admin.com', '13777777777', 5, 100, 0, '2015-07-05 18:16:32', '2015-06-29 10:10:00', 1, 1),
(3, 'ttssssssfs', NULL, NULL, NULL, 'ttssssssfs', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ts_action`
--
ALTER TABLE `ts_action`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ts_action_log`
--
ALTER TABLE `ts_action_log`
 ADD PRIMARY KEY (`id`), ADD KEY `action_id_ix` (`action_id`), ADD KEY `user_id_ix` (`user_id`), ADD KEY `action_ip_ix` (`action_ip`);

--
-- Indexes for table `ts_activity`
--
ALTER TABLE `ts_activity`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ts_addons`
--
ALTER TABLE `ts_addons`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ts_advertise`
--
ALTER TABLE `ts_advertise`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ts_attachment`
--
ALTER TABLE `ts_attachment`
 ADD PRIMARY KEY (`id`), ADD KEY `idx_record_status` (`record_id`,`status`);

--
-- Indexes for table `ts_attribute`
--
ALTER TABLE `ts_attribute`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ts_auth_extend`
--
ALTER TABLE `ts_auth_extend`
 ADD UNIQUE KEY `group_extend_type` (`group_id`,`extend_id`,`type`), ADD KEY `uid` (`group_id`), ADD KEY `group_id` (`extend_id`);

--
-- Indexes for table `ts_auth_group`
--
ALTER TABLE `ts_auth_group`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ts_auth_group_access`
--
ALTER TABLE `ts_auth_group_access`
 ADD UNIQUE KEY `uid_group_id` (`uid`,`group_id`), ADD KEY `uid` (`uid`), ADD KEY `group_id` (`group_id`);

--
-- Indexes for table `ts_auth_rule`
--
ALTER TABLE `ts_auth_rule`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `name` (`module`,`name`,`type`);

--
-- Indexes for table `ts_category`
--
ALTER TABLE `ts_category`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `uk_name` (`name`);

--
-- Indexes for table `ts_channel`
--
ALTER TABLE `ts_channel`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ts_config`
--
ALTER TABLE `ts_config`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `uk_name` (`name`);

--
-- Indexes for table `ts_course`
--
ALTER TABLE `ts_course`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ts_course_comment`
--
ALTER TABLE `ts_course_comment`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ts_course_record`
--
ALTER TABLE `ts_course_record`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ts_course_type`
--
ALTER TABLE `ts_course_type`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ts_document`
--
ALTER TABLE `ts_document`
 ADD PRIMARY KEY (`id`), ADD KEY `idx_name` (`name`), ADD KEY `idx_category_status` (`category_id`,`status`), ADD KEY `idx_status_type_pid` (`status`,`type`,`pid`);

--
-- Indexes for table `ts_document_article`
--
ALTER TABLE `ts_document_article`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ts_document_download`
--
ALTER TABLE `ts_document_download`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ts_drug`
--
ALTER TABLE `ts_drug`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ts_drug_store`
--
ALTER TABLE `ts_drug_store`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ts_exam`
--
ALTER TABLE `ts_exam`
 ADD PRIMARY KEY (`exam_id`);

--
-- Indexes for table `ts_exam_options`
--
ALTER TABLE `ts_exam_options`
 ADD PRIMARY KEY (`opt_id`), ADD KEY `opt_id` (`opt_id`) USING BTREE;

--
-- Indexes for table `ts_exam_question`
--
ALTER TABLE `ts_exam_question`
 ADD PRIMARY KEY (`quest_id`);

--
-- Indexes for table `ts_exam_survey`
--
ALTER TABLE `ts_exam_survey`
 ADD PRIMARY KEY (`survey_id`), ADD KEY `sry_IX_exam_id` (`exam_id`);

--
-- Indexes for table `ts_exam_survey_detl`
--
ALTER TABLE `ts_exam_survey_detl`
 ADD PRIMARY KEY (`detl_id`), ADD KEY `sdetl_FK_survey_id` (`survey_id`), ADD KEY `sdetl_FK_quest_id` (`quest_id`), ADD KEY `sdetl_IX_opt_id` (`opt_id`) USING BTREE;

--
-- Indexes for table `ts_file`
--
ALTER TABLE `ts_file`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `uk_md5` (`md5`);

--
-- Indexes for table `ts_gift_record`
--
ALTER TABLE `ts_gift_record`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ts_hooks`
--
ALTER TABLE `ts_hooks`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `搜索索引` (`name`);

--
-- Indexes for table `ts_kejian`
--
ALTER TABLE `ts_kejian`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `id` (`id`), ADD UNIQUE KEY `id_2` (`id`), ADD KEY `id_3` (`id`), ADD KEY `id_4` (`id`), ADD KEY `id_5` (`id`);

--
-- Indexes for table `ts_member`
--
ALTER TABLE `ts_member`
 ADD PRIMARY KEY (`uid`), ADD UNIQUE KEY `ix_uid` (`uid`);

--
-- Indexes for table `ts_menu`
--
ALTER TABLE `ts_menu`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ts_model`
--
ALTER TABLE `ts_model`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ts_order`
--
ALTER TABLE `ts_order`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ts_pharma`
--
ALTER TABLE `ts_pharma`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ts_picture`
--
ALTER TABLE `ts_picture`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ts_port`
--
ALTER TABLE `ts_port`
 ADD PRIMARY KEY (`interface_id`);

--
-- Indexes for table `ts_port_category`
--
ALTER TABLE `ts_port_category`
 ADD PRIMARY KEY (`cate_id`);

--
-- Indexes for table `ts_port_value`
--
ALTER TABLE `ts_port_value`
 ADD PRIMARY KEY (`port_id`);

--
-- Indexes for table `ts_subbranch`
--
ALTER TABLE `ts_subbranch`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ts_ucenter_admin`
--
ALTER TABLE `ts_ucenter_admin`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ts_ucenter_app`
--
ALTER TABLE `ts_ucenter_app`
 ADD PRIMARY KEY (`id`), ADD KEY `status` (`status`);

--
-- Indexes for table `ts_ucenter_member`
--
ALTER TABLE `ts_ucenter_member`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `username` (`username`), ADD UNIQUE KEY `email` (`email`), ADD KEY `status` (`status`);

--
-- Indexes for table `ts_ucenter_setting`
--
ALTER TABLE `ts_ucenter_setting`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ts_url`
--
ALTER TABLE `ts_url`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `idx_url` (`url`);

--
-- Indexes for table `ts_user_focus`
--
ALTER TABLE `ts_user_focus`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ts_yaochang`
--
ALTER TABLE `ts_yaochang`
 ADD PRIMARY KEY (`id`), ADD KEY `id` (`id`);

--
-- Indexes for table `ts_yaodian`
--
ALTER TABLE `ts_yaodian`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `id` (`id`), ADD KEY `id_2` (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ts_action`
--
ALTER TABLE `ts_action`
MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `ts_action_log`
--
ALTER TABLE `ts_action_log`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',AUTO_INCREMENT=30;
--
-- AUTO_INCREMENT for table `ts_addons`
--
ALTER TABLE `ts_addons`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT for table `ts_advertise`
--
ALTER TABLE `ts_advertise`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `ts_attachment`
--
ALTER TABLE `ts_attachment`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `ts_attribute`
--
ALTER TABLE `ts_attribute`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=187;
--
-- AUTO_INCREMENT for table `ts_auth_group`
--
ALTER TABLE `ts_auth_group`
MODIFY `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户组id,自增主键',AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `ts_auth_rule`
--
ALTER TABLE `ts_auth_rule`
MODIFY `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '规则id,自增主键',AUTO_INCREMENT=207;
--
-- AUTO_INCREMENT for table `ts_category`
--
ALTER TABLE `ts_category`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '分类ID',AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `ts_channel`
--
ALTER TABLE `ts_channel`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '频道ID',AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `ts_config`
--
ALTER TABLE `ts_config`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '配置ID',AUTO_INCREMENT=33;
--
-- AUTO_INCREMENT for table `ts_course`
--
ALTER TABLE `ts_course`
MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `ts_course_comment`
--
ALTER TABLE `ts_course_comment`
MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增主键',AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `ts_course_record`
--
ALTER TABLE `ts_course_record`
MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增主键',AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT for table `ts_course_type`
--
ALTER TABLE `ts_course_type`
MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增主键';
--
-- AUTO_INCREMENT for table `ts_document`
--
ALTER TABLE `ts_document`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '文档ID';
--
-- AUTO_INCREMENT for table `ts_drug`
--
ALTER TABLE `ts_drug`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `ts_drug_store`
--
ALTER TABLE `ts_drug_store`
MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `ts_exam`
--
ALTER TABLE `ts_exam`
MODIFY `exam_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=97;
--
-- AUTO_INCREMENT for table `ts_exam_options`
--
ALTER TABLE `ts_exam_options`
MODIFY `opt_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1615;
--
-- AUTO_INCREMENT for table `ts_exam_question`
--
ALTER TABLE `ts_exam_question`
MODIFY `quest_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=142;
--
-- AUTO_INCREMENT for table `ts_exam_survey`
--
ALTER TABLE `ts_exam_survey`
MODIFY `survey_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=204;
--
-- AUTO_INCREMENT for table `ts_exam_survey_detl`
--
ALTER TABLE `ts_exam_survey_detl`
MODIFY `detl_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=878;
--
-- AUTO_INCREMENT for table `ts_file`
--
ALTER TABLE `ts_file`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '文件ID';
--
-- AUTO_INCREMENT for table `ts_gift_record`
--
ALTER TABLE `ts_gift_record`
MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增主键',AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `ts_hooks`
--
ALTER TABLE `ts_hooks`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT for table `ts_kejian`
--
ALTER TABLE `ts_kejian`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `ts_member`
--
ALTER TABLE `ts_member`
MODIFY `uid` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户ID',AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `ts_menu`
--
ALTER TABLE `ts_menu`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '文档ID',AUTO_INCREMENT=143;
--
-- AUTO_INCREMENT for table `ts_model`
--
ALTER TABLE `ts_model`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '模型ID',AUTO_INCREMENT=19;
--
-- AUTO_INCREMENT for table `ts_pharma`
--
ALTER TABLE `ts_pharma`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `ts_picture`
--
ALTER TABLE `ts_picture`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id自增';
--
-- AUTO_INCREMENT for table `ts_port`
--
ALTER TABLE `ts_port`
MODIFY `interface_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `ts_port_category`
--
ALTER TABLE `ts_port_category`
MODIFY `cate_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `ts_port_value`
--
ALTER TABLE `ts_port_value`
MODIFY `port_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `ts_subbranch`
--
ALTER TABLE `ts_subbranch`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `ts_ucenter_admin`
--
ALTER TABLE `ts_ucenter_admin`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '管理员ID';
--
-- AUTO_INCREMENT for table `ts_ucenter_app`
--
ALTER TABLE `ts_ucenter_app`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '应用ID';
--
-- AUTO_INCREMENT for table `ts_ucenter_member`
--
ALTER TABLE `ts_ucenter_member`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户ID',AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `ts_ucenter_setting`
--
ALTER TABLE `ts_ucenter_setting`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '设置ID';
--
-- AUTO_INCREMENT for table `ts_url`
--
ALTER TABLE `ts_url`
MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '链接唯一标识';
--
-- AUTO_INCREMENT for table `ts_user_focus`
--
ALTER TABLE `ts_user_focus`
MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=25;
--
-- AUTO_INCREMENT for table `ts_yaochang`
--
ALTER TABLE `ts_yaochang`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `ts_yaodian`
--
ALTER TABLE `ts_yaodian`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
