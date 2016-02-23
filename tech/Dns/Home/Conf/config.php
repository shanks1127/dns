<?php
return array(
	//'配置项'=>'配置值'
    'URL_PATHINFO_DEPER'=>'/',//修改URL分隔符
    'TMPL_PARSE_STRING'=>array(
        '__CSS__'=>__ROOT__.'/Public/Css',
        '__JS__'=>__ROOT__.'/Public/Js',
    ),
    'URL_MODEL'=>1,
    'DB_HOST'=>'192.168.122.101',
    'DB_NAME'=>'internaldns',
    'DB_USER'=>'dns',
    'DB_PWD'=>'password',
    'DB_PORT'=>'3306',
    'SHOW_PAGE_TRACE'=>false,//开启页面Trace
    'DB_PREFIX'=>'',//表名前缀
    'TMPL_L_DELIM'=>'<{',
    'TMPL_R_DELIM'=>'}>',//修改左右定界符
    'SHOW_ERROR_MSG' =>true,
    //自定义全局变量
    'LDAP_HOST'=>'192.168.122.80', //LDAP HOST
    'LDAP_USER_SUFFIX'=>'shanks.com',//LDAP 用户后缀
    'COOKIE_TIMEOUT'=>'604800',//COOKIE 保留时间
);
?>
