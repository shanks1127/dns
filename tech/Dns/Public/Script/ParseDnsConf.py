#!/usr/bin/python
#encoding:gb2312
##########################
#读取DNS服务器配置文件   #
#获取IP-Domain的对应关系 #
#########################

import pexpect,MySQLdb
import sys,re
import commands
import smtplib,email,email.MIMEMultipart,email.MIMEBase,email.MIMEText
import email.MIMEImage,base64

class ExecuteSQL(object):
    def __init__(self, host, user, password, db, port):
        self.host = host
        self.user = user
        self.password = password
        self.db = db
        self.port = port
        
    def Insert(self,sql):
        try:
            conn=MySQLdb.connect(host=self.host,user=self.user,passwd=self.password,port=self.port,db=self.db)
            cursor=conn.cursor()
            cursor.execute(sql)
            conn.commit()
        except Exception,e:
            conn.rollback()
            print "INSERT ERROR!"
            sys.exit(0)
        cursor.close()
        conn.close()
    def Truncate(self,table):
        try:
            conn=MySQLdb.connect(host=self.host,user=self.user,passwd=self.password,port=self.port,db=self.db)
            cursor=conn.cursor()
            sql='truncate table '+table
            cursor.execute(sql)
            conn.commit()
        except Exception,e:
            conn.rollback()
            print e
        cursor.close()
        conn.close()
    def Select(self,sql):
        try:
            conn=MySQLdb.connect(host=self.host,user=self.user,passwd=self.password,port=self.port,db=self.db)
            cursor=conn.cursor()
            cursor.execute(sql)
            rs=cursor.fetchall()
        except Exception,e:
            conn.rollback()
            print e
        cursor.close()
        conn.close()
        
        return rs

        
def ssh_login(host,user,password,command):
    ssh_newkey='Are you sure you want to continue connecting'
    # 为 ssh 命令生成一个 spawn 类的子程序对象
    child=pexpect.spawn('ssh -l %s %s %s' % (user,host,command))
    i=child.expect([pexpect.TIMEOUT,ssh_newkey,'password: '])
    #如果登录超时，打印出错信息，并退出
    if i==0:
        print 'ERROR!'
        print 'SSH could not login. Here is what SSH said:'
        print child.before, child.after
        return None
    #如果 ssh 没有 public key，接受它
    if i==1:
        child.sendline('yes')
        child.expect('password: ')
        i=child.expect([pexpect.TIMEOUT,'password: '])
        if i==0:
            print 'ERROR!'
            print 'SSH could not login. Here is what SSH said:'
            print child.before, child.after
            return None
    #输入密码
    child.sendline(password)
    # 匹配 pexpect.EOF
    child.expect(pexpect.EOF)
    #命令结果
    return child.before
    
def ParseOutput(outstr,suffix):
    #过滤含ip地址的数据
    cmd="echo '"+outstr+"' |grep '[0-9]\{1,3\}\.[0-9]\{1,3\}\.[0-9]\{1,3\}\.[0-9]\{1,3\}'"
    
    conf_str=commands.getoutput(cmd)
    conf_str=re.sub(r'\t+',',',conf_str)
    conf_str=re.sub(r'[ ]+',',',conf_str)
    conf_str=re.sub(r'[,]+',',',conf_str)
    conf_str=re.sub(r' ',',',conf_str)
    conf_str=re.sub(r'[\r\s]','~',conf_str)
    conf_str=re.sub(r'~,(MX|CNAME|NS|SOA|A|AAAA|PTR),',':',conf_str)
    conf_str=re.sub(r'~','\n',conf_str)
    conf_str=re.sub(r',:',':',conf_str)
    conf_str=re.sub(r',\n','\n',conf_str)
    #print conf_str
    #sys.exit(0)
    #conf_str=re.sub(r'~','',conf_str)
    rows=conf_str.split('\n')
    #print rows
    for row in rows:
        cols=row.split(',')
        #domain=cols[0]+'.'+suffix
        domain=cols[0]
        dns_type=cols[1]
        ips=cols[2].split(':')
        #print domain,ips 
        for ip in ips:
            sql="insert into dns_records(zone,host,dnstype,data,level) values('%s','%s','%s','%s','common')" % (suffix,domain,dns_type,ip)
            #print sql
            operation.Insert(sql)
 
def SendMail(mailmsg,tomail,title):
    mail_recv=re.split('[,;]',tomail)
    femail=('dnschange@shanks.com')
    temail =tomail
    msg=email.MIMEMultipart.MIMEMultipart()
    msg['From'] = femail
    msg['To'] = temail
    msg['Subject'] = title
    msg['Reply-To'] = femail
    body=email.MIMEText.MIMEText(mailmsg,_charset='gb2312')
    msg.attach(body)
    server = smtplib.SMTP('mail.shanks.com')
    server.sendmail(femail,mail_recv,msg.as_string())
    server.close()
    print 'All mail were sended!'   
    
if __name__=='__main__':
    global host,user,password,db,operation
    #目标数据库信息
    host='192.168.122.101'
    user='dns'
    password='password'
    db='internaldns'
    operation=ExecuteSQL(host,user,password,db,3306)
    try:
        #清空表
        operation.Truncate('dns_records')
    
        path='/var/named/chroot/etc/'
        #获取DNS配置文件
        command='grep "file" '+path+'view.conf'
        output=commands.getoutput(command)
        #output = ssh_login (host, user, password, command)
        output=re.sub('file|[;" \r]','',output)
        output=output.strip('\n')
        flist=output.split('\n')
        for conf in flist:
            m = re.search('IDC\d', conf,re.I) 
            dddb = re.search('DB', conf,re.I) 
            ddapi = re.search('API', conf,re.I) 
            if m:
                suffix=m.group().lower()#域名后缀为.IDCX
            elif dddb:
                suffix='DB'
            elif ddapi:
                suffix='API'
            else:
                suffix='shanks.com'
            #print suffix
            #cmd="grep \'[0-9]\{1,3\}\.[0-9]\{1,3\}\.[0-9]\{1,3\}\.[0-9]\{1,3\}\' " +path+conf
            #print cmd
            cmd="cat "+path+conf
            #outstr = ssh_login (host, user, password, cmd)
            outstr=commands.getoutput(cmd)
            ParseOutput(outstr,suffix)
            
            #sys.exit(0)
        print "The End."
    except Exception,e:
        errmsg="ERROR:"+str(e)
        print errmsg
        #sendmail
        title="MonAlert-DNS配置管理数据库同步出错"
        tomail='dnsadmin@shanks.com'
        SendMail(errmsg,tomail,title)
        
