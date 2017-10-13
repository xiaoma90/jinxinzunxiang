/*
Navicat MySQL Data Transfer

Source Server         : test
Source Server Version : 50709
Source Host           : 127.0.0.1:3306
Source Database       : jxzx

Target Server Type    : MYSQL
Target Server Version : 50709
File Encoding         : 65001

Date: 2017-06-04 22:30:35
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for jxzx_account
-- ----------------------------
DROP TABLE IF EXISTS `jxzx_account`;
CREATE TABLE `jxzx_account` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '账号id',
  `uid` int(11) DEFAULT NULL COMMENT '会员UID',
  `money` decimal(16,2) DEFAULT NULL COMMENT '支出,收入钱',
  `source` varchar(255) DEFAULT NULL COMMENT '来源id',
  `message` varchar(255) DEFAULT NULL COMMENT '信息备注(1:''余额提现'',2:''购买权限'')',
  `createtime` varchar(255) DEFAULT NULL COMMENT '创建时间',
  `remark` varchar(255) DEFAULT NULL COMMENT '备注',
  `paymenttype` varchar(255) DEFAULT NULL COMMENT '支付方式(1:余额支付,2:线下微信支付,3:线下支付宝支付,4:线下银行卡支付)',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COMMENT='财务对账台表';

-- ----------------------------
-- Records of jxzx_account
-- ----------------------------
INSERT INTO `jxzx_account` VALUES ('10', '26', '59.40', '27', '余额提现', '1496167895', null, '1');
INSERT INTO `jxzx_account` VALUES ('11', '27', '59.40', '28', '分佣金额', '1496368275', null, '2');
INSERT INTO `jxzx_account` VALUES ('12', '26', '19.80', '28', '分佣金额', '1496308275', null, '4');
INSERT INTO `jxzx_account` VALUES ('13', '30', '0.00', null, '', '1496507472', null, '4');

-- ----------------------------
-- Table structure for jxzx_adminuser
-- ----------------------------
DROP TABLE IF EXISTS `jxzx_adminuser`;
CREATE TABLE `jxzx_adminuser` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '管理员id',
  `username` varchar(255) NOT NULL COMMENT '账号(姓名)',
  `password` varchar(255) NOT NULL COMMENT '密码',
  `IP` varchar(255) DEFAULT NULL COMMENT '登录IP',
  `createtime` varchar(255) DEFAULT NULL COMMENT '创建时间',
  `status` varchar(255) DEFAULT '1' COMMENT '1为可用,2为不可用',
  `isdel` varchar(255) DEFAULT '0' COMMENT '是否删除 默认为0 , 删除是1',
  `phone` varchar(255) DEFAULT NULL COMMENT '管理员手机号',
  `rolename` varchar(255) DEFAULT NULL COMMENT '角色姓名',
  `roleid` int(11) DEFAULT NULL,
  `logintime` varchar(255) DEFAULT NULL COMMENT '登录时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='后台管理员';

-- ----------------------------
-- Records of jxzx_adminuser
-- ----------------------------
INSERT INTO `jxzx_adminuser` VALUES ('1', 'admin', '21232f297a57a5a743894a0e4a801fc3', '::1', null, '1', '0', '18238095607', '', '1', '1496562678');
INSERT INTO `jxzx_adminuser` VALUES ('2', '张钰郴', 'dc483e80a7a0bd9ef71d8cf973673924', '115.57.133.100', null, '1', '0', '18238095600', null, '1', '1496451564');
INSERT INTO `jxzx_adminuser` VALUES ('3', 'kobe', '86e96a45acb8029b9603fdcbc66baa7a', null, '2017-05-12 15:40:03', '1', '1', '18238095606', null, '1', null);
INSERT INTO `jxzx_adminuser` VALUES ('4', 'qwer', '86e96a45acb8029b9603fdcbc66baa7a', null, '2017-05-15 09:27:40', '1', '1', '18238095607', null, '1', null);

-- ----------------------------
-- Table structure for jxzx_codes
-- ----------------------------
DROP TABLE IF EXISTS `jxzx_codes`;
CREATE TABLE `jxzx_codes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `code` int(11) NOT NULL COMMENT '验证码',
  `createtime` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of jxzx_codes
-- ----------------------------

-- ----------------------------
-- Table structure for jxzx_config
-- ----------------------------
DROP TABLE IF EXISTS `jxzx_config`;
CREATE TABLE `jxzx_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `one` varchar(255) DEFAULT NULL COMMENT '一级',
  `two` varchar(255) DEFAULT NULL COMMENT '二级',
  `three` varchar(255) DEFAULT NULL COMMENT '三级',
  `four` varchar(255) DEFAULT NULL COMMENT '四级',
  `five` varchar(255) DEFAULT NULL COMMENT '五级',
  `cash_commission` varchar(255) DEFAULT NULL COMMENT '提现手续费',
  `lower_cash` varchar(255) DEFAULT NULL COMMENT '最低发起提现额度',
  `cycle` varchar(255) DEFAULT NULL COMMENT '提现发放周期',
  `unit_price` varchar(255) DEFAULT NULL COMMENT '单价',
  `person_day` varchar(255) DEFAULT NULL COMMENT '每人每日限购',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COMMENT='数据分销奖金晋级规则设置';

-- ----------------------------
-- Records of jxzx_config
-- ----------------------------
INSERT INTO `jxzx_config` VALUES ('1', '8%', '5%', '2%', '2%', '3%', '5%', '100', '2', '50', '100');

-- ----------------------------
-- Table structure for jxzx_course
-- ----------------------------
DROP TABLE IF EXISTS `jxzx_course`;
CREATE TABLE `jxzx_course` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '课程视频id',
  `classid` int(11) NOT NULL COMMENT '类别id',
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户uid 0：管理员',
  `title` varchar(255) NOT NULL COMMENT '标题',
  `looknum` int(11) NOT NULL DEFAULT '0' COMMENT '观看数',
  `thumbnail` varchar(255) DEFAULT NULL COMMENT '缩小图片',
  `video` varchar(255) NOT NULL COMMENT '视频',
  `level` int(11) NOT NULL DEFAULT '1' COMMENT '等级(1为免费(学童),2为学霸,3位讲师,4位合伙人)',
  `author` varchar(255) DEFAULT NULL COMMENT '作者',
  `content` varchar(255) NOT NULL COMMENT '内容',
  `createtime` varchar(255) NOT NULL,
  `isdel` varchar(255) DEFAULT '0' COMMENT '0为正常,1为删除',
  `phone` varchar(255) NOT NULL COMMENT '手机号',
  `checkbox` varchar(255) NOT NULL COMMENT '选择框',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=85 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of jxzx_course
-- ----------------------------
INSERT INTO `jxzx_course` VALUES ('76', '4', '30', '学习', '162', 'http://hr2.hongrunet.com/headimg/201706021008361720000002746384528.png', 'http://opzthvc7x.bkt.clouddn.com/Fowx9vkTxooew1xRMlwAEvUrje4E', '1', null, '学习好', '1496369331', '0', '13213152964', '1');
INSERT INTO `jxzx_course` VALUES ('79', '2', '0', '店内部分', '0', 'http://hr.hongrunet.com/Uploads/2017-06-02/5930dfdc1cb60.jpg', 'http://opzthvc7x.bkt.clouddn.com/14963752648708195039', '3', '工号', '符号都不能', '1496265270', '0', '', '');
INSERT INTO `jxzx_course` VALUES ('80', '6', '0', '地方还是中国', '0', 'http://hr.hongrunet.com/Uploads/2017-06-02/5930e266b59ee.jpg', 'http://opzthvc7x.bkt.clouddn.com/14963754816258819074', '4', '的各项环保', '地方还是个地方', '1496375911', '0', '', '');
INSERT INTO `jxzx_course` VALUES ('83', '8', '28', '28467584', '2', 'http://hr2.hongrunet.com/headimg/20170602152631172000000740173137.png', 'http://opzthvc7x.bkt.clouddn.com/FjmEjkcIS1xOUUTFex7Zf0paDl6A', '1', '岳颖超', '天会', '1496389862', '0', '15638201281', '1');
INSERT INTO `jxzx_course` VALUES ('84', '2', '28', '348676', '29', 'http://hr2.hongrunet.com/headimg/20170602155258172000000181233189.png', 'http://opzthvc7x.bkt.clouddn.com/FjmEjkcIS1xOUUTFex7Zf0paDl6A', '2', '岳颖超', '几位特瑞', '1496389996', '0', '15638201281', '1');

-- ----------------------------
-- Table structure for jxzx_course_class
-- ----------------------------
DROP TABLE IF EXISTS `jxzx_course_class`;
CREATE TABLE `jxzx_course_class` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '视频类别id',
  `name` varchar(255) DEFAULT NULL COMMENT '视频名称',
  `remark` varchar(255) DEFAULT NULL COMMENT '备注',
  `createtime` varchar(255) DEFAULT NULL,
  `isdel` varchar(255) DEFAULT '0' COMMENT '0为正常,1为删除',
  `isenable` varchar(255) DEFAULT '1' COMMENT '1为可用,0为不可用',
  `icon` varchar(255) DEFAULT NULL COMMENT '图标',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of jxzx_course_class
-- ----------------------------
INSERT INTO `jxzx_course_class` VALUES ('1', '大咖专访', '2', null, '0', '1', null);
INSERT INTO `jxzx_course_class` VALUES ('2', '双创平台', null, null, '0', '1', 'icon-pingtai');
INSERT INTO `jxzx_course_class` VALUES ('3', '人际沟通', null, null, '0', '1', 'icon-goutong');
INSERT INTO `jxzx_course_class` VALUES ('4', '团队企管', null, null, '0', '1', 'icon-qiyegaoguan');
INSERT INTO `jxzx_course_class` VALUES ('5', '农建商道', null, null, '0', '1', 'icon-nong');
INSERT INTO `jxzx_course_class` VALUES ('6', '健康养生', null, null, '0', '1', 'icon-ye');
INSERT INTO `jxzx_course_class` VALUES ('7', '家庭教育', null, null, '0', '1', 'icon-jiating');
INSERT INTO `jxzx_course_class` VALUES ('8', '微商制造', null, null, '0', '1', 'icon-shang');

-- ----------------------------
-- Table structure for jxzx_deliver
-- ----------------------------
DROP TABLE IF EXISTS `jxzx_deliver`;
CREATE TABLE `jxzx_deliver` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '发货ID',
  `uid` int(11) NOT NULL COMMENT '发货人id',
  `sid` int(11) NOT NULL COMMENT '收货人id',
  `address` varchar(255) DEFAULT NULL COMMENT '收货地址',
  `receiver` varchar(255) DEFAULT NULL COMMENT '收货人',
  `mobile` varchar(255) DEFAULT NULL COMMENT '收货人电话',
  `status` varchar(255) DEFAULT NULL COMMENT '是否发货：（1：已发货，2：未发货）',
  `createtime` varchar(255) DEFAULT NULL COMMENT '发货时间',
  `isdel` varchar(255) DEFAULT NULL COMMENT '是否删除',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='发货表';

-- ----------------------------
-- Records of jxzx_deliver
-- ----------------------------
INSERT INTO `jxzx_deliver` VALUES ('1', '24', '3', '郑州市', '张三丰', '18238095607', '1', '1496503583', '0');
INSERT INTO `jxzx_deliver` VALUES ('2', '25', '4', '金水区', '蒙毅', '15673865378', '2', '1496573583', null);

-- ----------------------------
-- Table structure for jxzx_goods
-- ----------------------------
DROP TABLE IF EXISTS `jxzx_goods`;
CREATE TABLE `jxzx_goods` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '商品id',
  `photo` varchar(255) DEFAULT NULL COMMENT '商品图片',
  `title` varchar(255) DEFAULT NULL COMMENT '标题',
  `remark` varchar(255) DEFAULT NULL COMMENT '备注 ',
  `content` text COMMENT '内容',
  `description` varchar(255) DEFAULT NULL COMMENT '描述',
  `price` varchar(255) DEFAULT NULL COMMENT '商品价格',
  `isdel` varchar(255) DEFAULT '0' COMMENT '是否删除',
  `createtime` varchar(255) DEFAULT NULL COMMENT '创建日期',
  `author` varchar(255) DEFAULT NULL COMMENT '发布人',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='商品表';

-- ----------------------------
-- Records of jxzx_goods
-- ----------------------------
INSERT INTO `jxzx_goods` VALUES ('1', '/jxzx/jinxin/Uploads/2017-06-03/59328b9f48e94.jpg', '平凡的世界', '哈哈哈哈', '&lt;p&gt;按时吃 阿萨德成都市&lt;/p&gt;', '卡圣诞节', '50', '0', '1496484878', '科比');
INSERT INTO `jxzx_goods` VALUES ('2', '/jxzx/jinxin/Uploads/2017-06-03/59328ffa22861.jpg', '湖人王朝', '喂喂', null, '屌傻屌', '666', '0', '1496584878', '布莱恩特');
INSERT INTO `jxzx_goods` VALUES ('4', '/jxzx/jinxin/Uploads/2017-06-04/5933d775ad36f.jpg', '下雨了', null, '&lt;p&gt;我仓库vis当过兵V刹较好的上班&lt;/p&gt;&lt;p&gt;&lt;img src=&quot;/jxzx/jinxin/Uploads/ueditor/image/20170604/5933d7876205b.jpg&quot; style=&quot;&quot; title=&quot;5933d7876205b.jpg&quot;/&gt;&lt;/p&gt;&lt;p&gt;&lt;img src=&quot;/jxzx/jinxin/Uploads/ueditor/image/20170604/5933d7876d026.jpg&quot; style=&quot;&quot; title=&quot;5933d7876d026.jpg&quot;/&gt;&lt;/p&gt;&lt;p&gt;啊加长版的怀抱&lt;br/&gt;&lt;/p&gt;', '阿卡成绩不好的凯撒', '无价', '0', '1496569743', '小科比');

-- ----------------------------
-- Table structure for jxzx_info
-- ----------------------------
DROP TABLE IF EXISTS `jxzx_info`;
CREATE TABLE `jxzx_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '公司介绍id',
  `content` text COMMENT '公司介绍',
  `isdel` varchar(255) DEFAULT '0' COMMENT '是否删除 默认为0 , 删除是1',
  `createtime` varchar(255) DEFAULT NULL COMMENT '创建时间',
  `classid` int(11) DEFAULT '1' COMMENT '1为新手指南,2为通知公告,3为公司简介',
  `title` varchar(255) NOT NULL COMMENT '标题',
  `author` varchar(255) DEFAULT NULL COMMENT '作者',
  `thumbnail` varchar(255) DEFAULT NULL COMMENT '缩小图片',
  `description` varchar(255) DEFAULT NULL COMMENT '描述',
  `looknum` int(11) DEFAULT NULL COMMENT '观看数',
  `remark` varchar(255) DEFAULT NULL COMMENT '备注',
  PRIMARY KEY (`id`,`title`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COMMENT='新闻信息';

-- ----------------------------
-- Records of jxzx_info
-- ----------------------------
INSERT INTO `jxzx_info` VALUES ('1', '&lt;p&gt;希仔扒车&lt;/p&gt;&lt;p&gt;「扒皮汽车圈边儿上那些事」&lt;/p&gt;&lt;p&gt;「让实用更有趣」&lt;/p&gt;&lt;p&gt;明天就是世界无烟日，不禁让希仔想起了那笔账单，不知道多少人看过。每天两包烟×10年=丢辆宝马。真的不是玩笑，每个烟民的胸中（或者说肺中）都藏着辆豪华车，这次咱们再来细细算一下，保证看到数字，还不相信的烟民会目瞪口呆，心服口服。&lt;/p&gt;&lt;p&gt;&lt;img src=&quot;http://localhost/hongru/Uploads/ueditor/image/20170531/592e22f894fdd.gif&quot; style=&quot;&quot; title=&quot;592e22f894fdd.gif&quot;/&gt;&lt;/p&gt;&lt;p&gt;&lt;br/&gt;&lt;/p&gt;&lt;p&gt;不用按太贵的高档烟算，就按一般抽的比较多的，22块的单价来算：&lt;/p&gt;&lt;p&gt;&lt;br/&gt;&lt;/p&gt;&lt;p&gt;&amp;nbsp;&lt;/p&gt;&lt;p&gt;每天1包烟一年的费用是8030。「这些钱，可以买部iPhone7plus+一部千元机」&lt;/p&gt;&lt;p&gt;&amp;nbsp;&lt;/p&gt;&lt;p&gt;烟龄10年就是80300元。「这些钱，可以买辆博越、长安CS35等等」&lt;/p&gt;&lt;p&gt;20年就是160600元。「这些钱，可以买辆吉普自由光、汉兰达、迈腾等」&lt;/p&gt;&lt;p&gt;30年240900元。「这些钱，可以买辆吉普自由光、汉兰达、迈腾等」&lt;/p&gt;&lt;p&gt;而假如每天2两包烟，30年吸烟的费用就是481800元。「这些钱，奔驰E级、宝马3系5系基本上都随便选了」&lt;/p&gt;&lt;p&gt;假如一个烟民从20岁开始吸烟，假设寿命80岁，烟龄就是60年，60年如果每天1包烟，还是平均每包烟22元。那么，他一辈子吸烟的花费就是=60年×每年80300元=481800元。「同上」&lt;/p&gt;&lt;p&gt;而如果他每天吸2包烟，那么，一辈子吸烟的花费就是963600元。「这些钱，奔驰AMG、路虎揽胜、保时捷Macan、捷豹XJ都是你的可选对象」&lt;/p&gt;&lt;p&gt;注意：这些还只是个人抽烟的费用，抽烟对肺部的直接伤害人尽皆知，如果因为抽烟得了什么病，进到医院的花销那也不是一笔小数目……额，简直不能再算下去了……&lt;/p&gt;&lt;p&gt;&lt;img src=&quot;http://localhost/hongru/Uploads/ueditor/image/20170531/592e233937e65.gif&quot; title=&quot;592e233937e65.gif&quot; alt=&quot;ce3e-fyfrfvv4666232.gif&quot;/&gt;&lt;/p&gt;&lt;p&gt;&lt;br/&gt;&lt;/p&gt;&lt;p&gt;所以说每个烟民都是隐形土豪，只是他们自愿放弃了成为豪车车主的机会，他们的胸中其实都藏着一辆豪华车，并非危言耸听，今天的世界无烟日，如果你是烟民，从今天起是否可以来一个新的开始，把自己即将要丢掉的“豪车”捡回来呢？如果你不是烟民，让你身边的烟民朋友看看，别把自己“胸”中的豪华车丢掉了。&lt;/p&gt;&lt;p&gt;&lt;br/&gt;&lt;/p&gt;&lt;p&gt;&lt;img src=&quot;http://localhost/hongru/Uploads/ueditor/image/20170531/592e22f8a2aa0.gif&quot; style=&quot;&quot; title=&quot;592e22f8a2aa0.gif&quot;/&gt;&lt;/p&gt;', '0', '1496196203', '2', '听说，每个烟民胸中都藏着辆豪华车', 'kobesmart', '/Uploads/2017-05-31/592e245b92811.jpg', '尽人事听天命', '99', null);
INSERT INTO `jxzx_info` VALUES ('9', '&lt;p&gt;腾讯体育5月31日讯 &lt;br/&gt;&lt;/p&gt;&lt;p&gt;科比、&lt;a class=&quot;a-tips-Article-QQ&quot; href=&quot;http://nba.stats.qq.com/player/?id=3704&quot; target=&quot;_blank&quot;&gt;詹姆斯&lt;/a&gt;、&lt;a class=&quot;a-tips-Article-QQ&quot; href=&quot;http://nba.stats.qq.com/player/?id=4244&quot; target=&quot;_blank&quot;&gt;杜兰特&lt;/a&gt;三大顶级球星，谁是NBA联盟最难防守的球员，不同的人有着不同的答案。近日接受采访时，&lt;a class=&quot;a-tips-Article-QQ&quot; href=&quot;http://nba.stats.qq.com/team/?id=suns&quot; target=&quot;_blank&quot;&gt;太阳&lt;/a&gt;前锋杜德利也被问及了这一问题，而他给出的答案是科比。&lt;/p&gt;&lt;p&gt;&lt;br/&gt;&lt;/p&gt;&lt;p style=&quot;TEXT-INDENT: 2em&quot;&gt;有时候，NBA最好的球员评估者其实就是球员，他们在场上和那些超级球星面对面的接触，会有着和球迷不一样的感受。太阳前锋杜德利已经在联盟中效力了10年，也算是一名老兵了，他也是一名在防守端非常强硬的球员。&lt;/p&gt;&lt;p style=&quot;TEXT-INDENT: 2em&quot;&gt;近日接受采访时，当记者问及杜德利，科比、詹姆斯、杜兰特，谁是联盟中最难防守的球员时，杜德利选择了科比。谈及原因的时候，杜德利谈及了科比的曼巴精神，在杜德利看来，科比就是一位冷血杀手，每场比赛他都有着那种不可思议的篮球心态，用那种无情的态度去摧毁对手。&lt;/p&gt;&lt;p style=&quot;TEXT-INDENT: 2em&quot;&gt;值得一提的是，杜德利认为，三角进攻也是科比难以被限制的原因之一，听到这样的话，禅师菲尔-杰克逊应该会很开心，如今三角进攻在纽约是一个非常有争议的话题。杜德利认为，科比始终都处于攻击模式，这让防守人没有太多喘息的机会。当然，杜德利也夸赞了詹姆斯和杜兰特的个人表现。&lt;/p&gt;&lt;p style=&quot;TEXT-INDENT: 2em&quot;&gt;整个职业生涯，科比在面对杜德利的比赛中，场均可以拿下29.7分6.3个篮板6.1次助攻，投篮命中率为50%。詹姆斯在面对杜德利的比赛中，场均可以拿下25.4分8.4个篮板6.9次助攻，投篮命中率为48.7%。而杜兰特职业生涯在面对杜德利的比赛中，场均可以拿下26.5分6.6个篮板4.2次助攻，投篮命中率为46.3%。&lt;/p&gt;&lt;p&gt;&lt;br/&gt;&lt;/p&gt;', '0', '1496280531', '1', '科比詹皇杜兰特谁最难防？太阳老兵选择飞侠。', 'NBA', '/Uploads/2017-05-31/592e385692837.jpg', '一生的信阳，曼巴精神！', '4', null);
INSERT INTO `jxzx_info` VALUES ('5', '&lt;p&gt;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&lt;span style=&quot;font-size: 18px;&quot;&gt;上周日进行的意甲联赛收官轮中，罗马主场3-2力克热那亚，这也是托蒂代表红狼的最后一场比赛。1993年，年仅16岁的托蒂在罗马完成了个人职业生涯的处子秀，他职业生涯全都奉献给了罗马。罗马官方宣布托蒂正式进入俱乐部的名人堂，罗马主席帕洛塔表示托蒂是有史以来最伟大的球员之一，托蒂称这让他感到无比光荣。随着时代的更迭，大合同漫天飞，体育始终是商业联盟，早已没有一个人一座城、孤胆英雄厮杀的气氛了，不是说以前有多好，现在有多差，只是曾经的那种感觉再也回不来了。&lt;/span&gt;&lt;/p&gt;&lt;p&gt;&lt;img src=&quot;/Uploads/ueditor/image/20170531/592e3eea68eb0.jpg&quot; title=&quot;592e3eea68eb0.jpg&quot; alt=&quot;19722356_980x1200_0.jpg&quot; style=&quot;width: 461px; height: 409px;&quot; width=&quot;461&quot; height=&quot;409&quot;/&gt;&lt;/p&gt;&lt;p&gt;&lt;span style=&quot;font-size: 20px;&quot;&gt;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;【20年坚守一座城&amp;nbsp;科比诠释紫金意义】2016年，科比宣布退役，全世界的球迷为之哗然。NBA戎马生涯20载，均效力与紫金王朝的科比，带着5枚总冠军戒指，两届NBA总决赛最有价值球员，18次全明星的殊荣离开。当他身旁的人换了一批又一批，他却诠释了一生为紫金的含义。科比是NBA最好的得分手之一，突破、投篮、罚球、三分球他都驾轻就熟，几乎没有进攻盲区，再加上脚步华丽，投篮姿势如教科书般流畅自如，科比成为了继乔丹之后，NBA又一领军人物。即使在职业生涯末期经常“打铁”，但球迷们始终不会忘记他对于湖人王朝建立所作出的一切。&lt;/span&gt;&lt;/p&gt;&lt;p&gt;&lt;br/&gt;&lt;/p&gt;&lt;p&gt;&lt;img src=&quot;/Uploads/ueditor/image/20170531/592e3efde33fd.jpg&quot; title=&quot;592e3efde33fd.jpg&quot; alt=&quot;19722355_980x1200_0.jpg&quot; style=&quot;width: 429px; height: 398px;&quot; width=&quot;429&quot; height=&quot;398&quot;/&gt;&lt;/p&gt;&lt;p&gt;&lt;span style=&quot;font-size: 20px;&quot;&gt;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;【一支状元签用20年&amp;nbsp;“石佛”邓肯造就圣城神话】1997年的马刺，用状元签选中了蒂姆-邓肯，这近20载，邓肯共出战1392场常规赛，其中首发1389场，场均上场34分钟，能够得到19分10.8篮板2.2盖帽2.4次失误；共出战251场季后赛，全部首发，场均出战37.3分钟，能够得到20.6分11.4篮板3.0助攻2.3盖帽。他给马刺带来了5次NBA总冠军，3个总决赛MVP，2届常规赛MVP，15次全明星，15次最佳阵容，15次最佳防守阵容等等数不胜数...凭借这些荣誉，历史第一大前锋，非邓肯莫属。而圣城的灵魂人物，也非邓肯莫属。&lt;/span&gt;&lt;/p&gt;&lt;p&gt;&lt;img src=&quot;/Uploads/ueditor/image/20170531/592e3f189c043.jpg&quot; title=&quot;592e3f189c043.jpg&quot; alt=&quot;19722354_980x1200_0.jpg&quot; style=&quot;width: 437px; height: 368px;&quot; width=&quot;437&quot; height=&quot;368&quot;/&gt;&lt;/p&gt;&lt;p&gt;&lt;span id=&quot;tatolNum&quot;&gt;&lt;/span&gt;&lt;/p&gt;&lt;p&gt;&lt;span style=&quot;font-size: 20px;&quot;&gt;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;【达拉斯标志人物&amp;nbsp;“老司机”诺维斯基当之无愧】说起达拉斯小牛，你首先会想到什么？我相信绝大多数人都会是这一个答案：诺维斯基。在NBA历史上，曾至少10次入选全明星并手握常规赛MVP+总决赛MVP的球员总共只有12个，分别是贾巴尔、伯德、科比、张伯伦、邓肯、勒布朗、“魔术师”约翰逊、乔丹、摩西-马龙、沙克-奥尼尔和奥拉朱旺，还差一个是谁？没错，就是诺维斯基。&lt;/span&gt;&lt;/p&gt;&lt;p&gt;&lt;br/&gt;&lt;/p&gt;', '0', '1496279137', '0', '20年的守护?', '忠诚', '/Uploads/2017-05-31/592e4af263a3a.jpg', null, '55', null);
INSERT INTO `jxzx_info` VALUES ('13', '&lt;p&gt;按时吃 阿萨德成都市&lt;/p&gt;', '0', '1496485899', '2', '噢噢', '啊啊', '/jxzx/jinxin/Uploads/2017-06-03/59328ffa22861.jpg', '阿是错的', null, null);
INSERT INTO `jxzx_info` VALUES ('12', '&lt;p&gt;&lt;img src=&quot;/jxzx/jinxin/Uploads/ueditor/image/20170603/59328ba97676b.jpg&quot; title=&quot;59328ba97676b.jpg&quot; alt=&quot;u=940808502,1621875186&amp;amp;fm=23&amp;amp;gp=0.jpg&quot;/&gt;&lt;/p&gt;&lt;p&gt;卡萨丁付款共和党VC夸奖哈三点半按理说从v垃圾卡离开就爱山东济南阿卡丽就是吃亏赛格vie人vkjwsvkjs我还是觉得烦vkjsdb看数据的v看很快进入阿萨德句酷&lt;/p&gt;&lt;p&gt;&lt;img src=&quot;/jxzx/jinxin/Uploads/ueditor/image/20170603/59328bc74f545.jpg&quot; title=&quot;59328bc74f545.jpg&quot; alt=&quot;timg.jpg&quot;/&gt;&lt;/p&gt;&lt;p&gt;阿萨德结过婚的&lt;/p&gt;', '0', '1496484878', '3', 'as', 'ueqw', '/jxzx/jinxin/Uploads/2017-06-03/59328b9f48e94.jpg', '开开开', null, null);

-- ----------------------------
-- Table structure for jxzx_lunbo
-- ----------------------------
DROP TABLE IF EXISTS `jxzx_lunbo`;
CREATE TABLE `jxzx_lunbo` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '轮播id',
  `img` varchar(255) DEFAULT NULL COMMENT '图片',
  `sort` varchar(255) DEFAULT NULL COMMENT '分类 1为顶部轮播图  2为底部轮播图',
  `imgurl` varchar(255) DEFAULT NULL COMMENT '图片地址',
  `createtime` varchar(255) DEFAULT NULL,
  `isdel` varchar(255) DEFAULT '0' COMMENT '0为默认正常,1为删除',
  `isenable` varchar(255) DEFAULT '1' COMMENT '1为可用,0为不可用',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of jxzx_lunbo
-- ----------------------------
INSERT INTO `jxzx_lunbo` VALUES ('1', '../images/kll_banner01.png', '1', '/upload', null, '0', '1');
INSERT INTO `jxzx_lunbo` VALUES ('2', '../images/bannerAdd02.png', '2', null, null, '0', '1');
INSERT INTO `jxzx_lunbo` VALUES ('3', '../images/kll_banner2.png', '3', null, null, '0', '1');
INSERT INTO `jxzx_lunbo` VALUES ('4', '../images/kll_banner3.png', '4', null, null, '0', '1');

-- ----------------------------
-- Table structure for jxzx_order
-- ----------------------------
DROP TABLE IF EXISTS `jxzx_order`;
CREATE TABLE `jxzx_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_num` varchar(255) NOT NULL COMMENT '订单号',
  `uid` int(11) NOT NULL COMMENT '订单生成用户id',
  `money` decimal(16,2) DEFAULT NULL COMMENT '订单金额',
  `status` int(11) DEFAULT NULL COMMENT '订单状态（1:未支付,2:已支付,3:待提现,4:已提现）',
  `payment` varchar(255) DEFAULT NULL COMMENT '支付方式(1:余额支付,2:线下支付宝支付,3:线下微信支付,4:线下银行卡支付)',
  `message` varchar(255) DEFAULT NULL COMMENT '订单内容',
  `type` int(11) DEFAULT NULL COMMENT '奖学金状态：（1：在线，2：出局）',
  `createtime` varchar(255) DEFAULT NULL COMMENT '购买日期',
  `getname` varchar(255) DEFAULT NULL COMMENT '收货人',
  `getphone` varchar(255) DEFAULT NULL COMMENT '收货人手机号',
  `num` int(11) DEFAULT NULL COMMENT '订单数量',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of jxzx_order
-- ----------------------------
INSERT INTO `jxzx_order` VALUES ('27', '201706149637343269', '24', '10.00', '1', '微信', '', '1', '1496373432', '张无忌', '18239566789', '8');
INSERT INTO `jxzx_order` VALUES ('31', '201706149637358392', '25', '20.00', '2', '微信', '打赏', '2', '1496373583', null, null, null);
INSERT INTO `jxzx_order` VALUES ('32', '201706048263868766', '26', '66.00', '1', null, null, '1', '1496493583', '好', '15689061234', null);
INSERT INTO `jxzx_order` VALUES ('33', '201706048273647632', '27', '88.00', '2', null, null, '2', '1496573583', '科比', '18888866666', null);

-- ----------------------------
-- Table structure for jxzx_role
-- ----------------------------
DROP TABLE IF EXISTS `jxzx_role`;
CREATE TABLE `jxzx_role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rolename` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT '1' COMMENT '1为可用,2为不可用',
  `remark` varchar(255) DEFAULT NULL COMMENT '备注',
  `isdel` varchar(255) DEFAULT '0' COMMENT '0为正常,1为删除',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of jxzx_role
-- ----------------------------
INSERT INTO `jxzx_role` VALUES ('1', '超级管理员', '1', '123456', '0');

-- ----------------------------
-- Table structure for jxzx_user
-- ----------------------------
DROP TABLE IF EXISTS `jxzx_user`;
CREATE TABLE `jxzx_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '用户会员id',
  `pid` varchar(11) DEFAULT NULL COMMENT '上级id',
  `phone` varchar(255) DEFAULT NULL COMMENT '会员手机号账号',
  `password` varchar(255) DEFAULT NULL COMMENT '密码',
  `nickname` varchar(255) DEFAULT NULL COMMENT '昵称',
  `rname` varchar(255) DEFAULT NULL COMMENT '真实姓名',
  `headimg` text COMMENT '用户头像',
  `sex` varchar(255) DEFAULT NULL COMMENT '性别',
  `age` int(11) DEFAULT NULL COMMENT '年龄',
  `openid` varchar(255) DEFAULT NULL COMMENT '微信号id',
  `isdel` int(11) DEFAULT '0' COMMENT '是否删除 默认为0 , 删除是1',
  `createtime` varchar(255) DEFAULT NULL COMMENT '创建时间',
  `remark` varchar(255) DEFAULT NULL COMMENT '备注',
  `is_cost` int(11) DEFAULT '0' COMMENT '0为未消费,1为首次消费,>1为多次消费 (用户升级会员修改状态)',
  `qrcode` varchar(255) DEFAULT NULL COMMENT '二维码',
  `wx_qrcode` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`nickname`)
) ENGINE=MyISAM AUTO_INCREMENT=33 DEFAULT CHARSET=utf8 COMMENT='会员';

-- ----------------------------
-- Records of jxzx_user
-- ----------------------------
INSERT INTO `jxzx_user` VALUES ('1', '1', '13027799747', '96e79218965eb72c92a549dd5a330112', '昵称13027799747', '', 'http://hr2.hongrunet.com/headimg/20170601203850171000000351171533.gif', '', '0', null, '0', '1496369547', null, '0', 'http://hr.hongrunet.com/Uploads/qrcode/qrcode1.png', null);
INSERT INTO `jxzx_user` VALUES ('24', '25', '18937036101', 'e10adc3949ba59abbe56e057f20f883e', '春雷', '', 'http://hr2.hongrunet.com/headimg/201706021138141720000002717101710.jpg', '女', '0', null, '0', '1496366079', null, '0', 'http://hr.hongrunet.com/Uploads/qrcode/qrcode24.png', 'gQEo8TwAAAAAAAAAAS5odHRwOi8vd2VpeGluLnFxLmNvbS9xLzAyV1A2aGRxalJkXzQxMDAwMDAwNzcAAgQJ4zBZAwQAAAAA');
INSERT INTO `jxzx_user` VALUES ('25', '24', '18238095607', 'e10adc3949ba59abbe56e057f20f883e', '小科比', '', 'http://hr2.hongrunet.com/headimg/201706020938101720000005042283319.gif', '', '0', null, '0', '1496366130', null, '0', 'http://hr.hongrunet.com/Uploads/qrcode/qrcode25.png', null);
INSERT INTO `jxzx_user` VALUES ('26', '25', '15617676125', 'e10adc3949ba59abbe56e057f20f883e', '鸭脖', '鸭脖', 'http://hr2.hongrunet.com/headimg/20170602092003172000000937151247.jpg', '女', '24', null, '0', '1496366177', null, '1', 'http://hr.hongrunet.com/Uploads/qrcode/qrcode26.png', 'gQGW8DwAAAAAAAAAAS5odHRwOi8vd2VpeGluLnFxLmNvbS9xLzAyeWJVa2NOalJkXzQxMDAwMGcwN2QAAgQrvTBZAwQAAAAA');
INSERT INTO `jxzx_user` VALUES ('27', '26', '13213152964', 'e10adc3949ba59abbe56e057f20f883e', '秦焓_', null, 'http://wx.qlogo.cn/mmopen/CHfkia17hKz1EicW1B6gjgBaXbr3q2NaKGRAUtH3ibLXvhWqCbtjLRQcn4cFBvv2SLIQzQXJenn8fngWydhJviaDQ5QNwwu9MnpL/0', '女', null, 'o0zV_xOM44XhWVS3u_bew9PuvYl0', '0', '1496366407', null, '1', 'http://hr.hongrunet.com/Uploads/qrcode/qrcode27.png', null);
INSERT INTO `jxzx_user` VALUES ('28', '27', '15638201281', 'e67c10a4c8fbfc0c400e047bb9a056a1', '岳颖超', '', 'http://hr2.hongrunet.com/headimg/201706020935471720000001121303222.jpg', '女', '0', null, '0', '1496366633', null, '1', 'http://hr.hongrunet.com/Uploads/qrcode/qrcode28.png', null);
INSERT INTO `jxzx_user` VALUES ('29', '24', '18003963898', 'e10adc3949ba59abbe56e057f20f883e', '昵称18003963898', null, 'http://hr2.hongrunet.com/lf_img/lf_portrait.png', null, null, null, '0', '1496368377', null, '0', 'http://hr.hongrunet.com/Uploads/qrcode/qrcode29.png', null);
INSERT INTO `jxzx_user` VALUES ('23', '24', '15955166726', '25f9e794323b453885f5181f1b624d0b', '昵称15955166726', null, 'http://hr2.hongrunet.com/lf_img/lf_portrait.png', null, null, null, '0', '1496365810', null, '0', 'http://hr.hongrunet.com/Uploads/qrcode/qrcode23.png', null);
INSERT INTO `jxzx_user` VALUES ('30', '1', '18865582171', 'e10adc3949ba59abbe56e057f20f883e', '孤剑', '', 'http://hr2.hongrunet.com/headimg/20170602101525172000000024203332.jpg', '', '0', 'o0zV_xD672RfL5cOD1Tr8Fv4XnPc', '0', '1496369577', null, '0', 'http://hr.hongrunet.com/Uploads/qrcode/qrcode30.png', null);
INSERT INTO `jxzx_user` VALUES ('31', '25', '15538147923', 'e10adc3949ba59abbe56e057f20f883e', '戒尺', null, '/Uploads/', '1', null, 'o0zV_xN78iLDGChK2nKP176BDsbw', '0', '1496369918', null, '0', 'http://hr.hongrunet.com/Uploads/qrcode/qrcode31.png', null);
INSERT INTO `jxzx_user` VALUES ('32', '24', '18695842873', 'e10adc3949ba59abbe56e057f20f883e', 'null', null, 'http://wx.qlogo.cn/mmopen/7fFW1wNcgR8KfDibIFWeZMaGSFIic8BaOpa0xCjDibVrJOgc6rtkArEKwqzbWNibG7MNbu1RpEwhusDKMJfTzc7LN0iaytADHDVoh/0', '男', null, 'o0zV_xCPcqEmJq3US-tz32mfa_U4', '0', '1496376130', null, '0', 'http://hr.hongrunet.com/Uploads/qrcode/qrcode32.png', null);

-- ----------------------------
-- Table structure for jxzx_userlevel
-- ----------------------------
DROP TABLE IF EXISTS `jxzx_userlevel`;
CREATE TABLE `jxzx_userlevel` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '用户等级id(user表里面的grade)',
  `level` int(11) DEFAULT NULL COMMENT '等级',
  `name` varchar(255) DEFAULT NULL COMMENT '等级名称',
  `money` varchar(255) DEFAULT NULL COMMENT '升级价格',
  `remark` varchar(255) DEFAULT NULL COMMENT '备注',
  `score` varchar(255) DEFAULT NULL COMMENT '积分',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='用户等级表';

-- ----------------------------
-- Records of jxzx_userlevel
-- ----------------------------
INSERT INTO `jxzx_userlevel` VALUES ('1', '1', '学童', '0', null, null);
INSERT INTO `jxzx_userlevel` VALUES ('2', '2', '学霸', '198', null, null);
INSERT INTO `jxzx_userlevel` VALUES ('3', '3', '讲师', '2980', null, null);
INSERT INTO `jxzx_userlevel` VALUES ('4', '4', '合伙人', '15800', null, null);

-- ----------------------------
-- Table structure for jxzx_withdrawals
-- ----------------------------
DROP TABLE IF EXISTS `jxzx_withdrawals`;
CREATE TABLE `jxzx_withdrawals` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '提现id',
  `uid` int(11) NOT NULL COMMENT '提现会员UID',
  `money` varchar(255) DEFAULT NULL COMMENT '提现金额',
  `fee` varchar(255) DEFAULT NULL COMMENT '提现手续费',
  `realmoney` varchar(255) DEFAULT NULL COMMENT '实际到账金额',
  `payment` varchar(255) DEFAULT NULL COMMENT '收款方式（1：微信，2：支付宝，3：银行卡）',
  `account` varchar(255) DEFAULT NULL COMMENT '收款账号',
  `status` varchar(255) DEFAULT NULL COMMENT '状态（1：同意，2：驳回）',
  `createtime` varchar(255) DEFAULT NULL COMMENT '创建日期',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='提现表';

-- ----------------------------
-- Records of jxzx_withdrawals
-- ----------------------------
INSERT INTO `jxzx_withdrawals` VALUES ('1', '27', '2000', '20', '1980', '1', 'kobezyc123456', '1', '1496563583');
INSERT INTO `jxzx_withdrawals` VALUES ('2', '24', '10000', '100', '9900', '2', '873409395kjnw', '2', '1496363583');
INSERT INTO `jxzx_withdrawals` VALUES ('3', '25', '5000', '50', '4950', '3', '1234567890123456789', '1', '1496763583');
