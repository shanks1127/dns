以下操作，均在dns-master节点上进行。
1、安装nginx，并将nginx.conf和vhost.conf上传
2、上传tech目录至nginx的wwwroot目录
3、编辑tech/Dns/Public/Script/auto_dns.py	该脚本是用作在有多个master时批量执行拉dns配置生成文件用的
	将第12行masterlist的值改为你环境中的master节点的ip和root密码，多个master用逗号分隔
	如果你的nginxwwwroot目录不是/var/www/html的话，修改第13/14行
4、编辑tech/Dns/Public/Script/config_record.py	该脚本是读取mysql生成本机bind配置文件用的
	根据实际情况编辑第23行
	根据第26行的配置，创建备份目录
	编辑第31~35行数据库配置
5、编辑tech/Dns/Public/Script/ParseDnsConf.py	该脚本是首次使用，读取本地配置文件，生成mysql数据用的，也可以在mysql产生脏数据时，从本地重新恢复用
	编辑139~142行数据库配置
6、编辑tech/Dns/Home/Conf/config.php
	编辑10~14行数据库配置
	编辑21~22行ldap配置
7、建数据库
	建库
	CREATE DATABASE `internaldns` /*!40100 DEFAULT CHARACTER SET utf8 */;
	建记录表
	CREATE TABLE `dns_records` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `zone` varchar(50) DEFAULT NULL,
  `host` varchar(100) DEFAULT NULL,
  `dnstype` varchar(50) DEFAULT NULL,
  `data` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `zone` (`zone`,`host`,`dnstype`,`data`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
	建用户表
	CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) DEFAULT NULL,
  `role` enum('user','admin') DEFAULT 'user',
  `ustat` varchar(10) DEFAULT NULL,
  `logintime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
	建审计表
	CREATE TABLE `log` (
  `logid` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) DEFAULT NULL,
  `content` varchar(2000) DEFAULT NULL,
  `logtime` datetime DEFAULT NULL,
  PRIMARY KEY (`logid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;