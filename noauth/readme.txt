没有任何权限控制，随意输入密码


把php文件替换至tech/Dns/Home/Lib/Action下


首次登陆会提示没有权限访问需要操作下数据库
use internaldns
update user set ustat='on';

之后再登陆就可以了