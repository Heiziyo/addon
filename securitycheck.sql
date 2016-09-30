create table app_securitycheck(
id smallint(6) unsigned primary key auto_increment,
newversion varchar(255) not null default '已是最新版',#版本标题
compatible varchar(255) not null default 'x2',#兼容版本
charsetnew varchar(30),#新版本语音编码
price varchar(255) not null default '免费',
file varchar(255) not null default "无应用",
remark varchar(255),
typeid varchar(255),
modstatus varchar(255) not null default '上架',
revisioninfo varchar(255) not null default '无',#版本介绍
revisionmemo  varchar(255) not null default '无'#版本说明

);
