#!/usr/bin/python
####################################################################
# Call config_record.py;ParseDnsConf.py
# Create Date :  2014-5-23
# Written by :shanks
# Organization:  DangDang
####################################################################
# -*- coding: UTF-8 -*-
import smtplib,email,email.MIMEMultipart,email.MIMEBase,email.MIMEText
import email.MIMEImage,base64
import sys,os,re,time,commands,MySQLdb,paramiko
masterlist="192.168.122.101:password"
config_record="/var/www/html/tech/Dns/Public/Script/config_record.py"
ParseDnsConf="/var/www/html/tech/Dns/Public/Script/ParseDnsConf.py"
log_file="/tmp/auto_dns.log"
lock_file="/tmp/auto_dns.lock"
DATA_NOW = time.strftime('%Y%m%d-%H%M%S')
report_status = 'OK'
tomail="dnsadmin@shanks.com"

def sendmail(mailmsg,tomail,title):
    femail=('dnschange@shanks.com')
    temail =re.split(r'[,]',tomail)
    msg=email.MIMEMultipart.MIMEMultipart()
    msg['From'] = femail
    msg['To'] = ";".join(temail)
    msg['Subject'] = title
    msg['Reply-To'] = femail
    body=email.MIMEText.MIMEText(mailmsg,_charset='gb2312')
    msg.attach(body)
    server = smtplib.SMTP('mail.shanks.com')
    #server = smtplib.SMTP('192.192.0.240')
    server.sendmail(femail,temail,msg.as_string())
    server.close()

def redo_dbdata():
    redo_result=commands.getoutput('/usr/bin/python '+ParseDnsConf)
    redo_result_=int(commands.getoutput('/bin/echo "'+redo_result+'" |grep "ERROR"|wc -l'))
    if redo_result_ == 0:
        print 'OK'
        c_log_file = file('%s' % log_file, 'a')
        c_log_file.write('redo mysql data ok.\n')
        c_log_file.close()
    else:
        c_log_file = file('%s' % log_file, 'a')
        c_log_file.write('redo mysql data error.\n')
        c_log_file.close()
        report_status='ERROR'
        fuck=commands.getoutput('/bin/cat '+log_file)
        print fuck

def ssh_(ip,passwd):
    #print ip+' passwd: '+passwd
    global result
    SSH = paramiko.SSHClient()
    SSH.set_missing_host_key_policy(paramiko.AutoAddPolicy())
    SSH.connect(ip,22,'root',passwd)
    stdin,stdout,stderr = SSH.exec_command('/usr/bin/python '+ config_record)
    print_arr = stdout.readlines()
    print_=''.join(print_arr)
    c_log_file = file('%s' % log_file, 'a')
    c_log_file.write(print_+'\n')
    c_log_file.close()
    result=int(commands.getoutput('/bin/echo "'+print_+'"|/bin/grep "ERROR"|/usr/bin/wc -l'))
    result=result+result

def init_log():
    #c_log_file = file('%s' % log_file, 'w')
    c_log_file=open(log_file,'w')
    c_log_file.write('======***%s***======\n' % DATA_NOW)
    c_log_file.close()

def main_():
    check_lock()
    for master in re.split(r',',masterlist):
        ip=re.split(r':',master)[0]
        passwd=re.split(r':',master)[1]
        ssh_(ip,passwd)
    if result == 0:
        #redo_dbdata()
        print 'update config ok.';
    else:
        c_log_file = file('%s' % log_file, 'a')
        c_log_file.write('some wrong here.\n')
        c_log_file.close()
        report_status='ERROR'
        fuck=commands.getoutput('/bin/cat '+log_file)
        print fuck
    rm_lock()

def check_lock():
    
    if os.path.isfile(lock_file):
        print 'control lockfile is exist!'
        sys.exit(0)
    else:
        init_log()
        create_lock = open(lock_file,'w')
        create_lock.close()
        if os.path.isfile(lock_file):
            c_log_file = file('%s' % log_file, 'a')
            c_log_file.write('create control lockfile OK.\n')
            c_log_file.close()
        else:
            c_log_file = file('%s' % log_file, 'a')
            c_log_file.write('create control lockfile ERROR.\n')
            c_log_file.close()
            report_status='ERROR'
            sys.exit(0)

def rm_lock():
    if os.path.isfile(lock_file):
        os.remove(lock_file)
        if os.path.isfile(lock_file):
            c_log_file = file('%s' % log_file, 'a')
            c_log_file.write('remove control lockfile ERROR.\n')
            c_log_file.close()
            sys.exit(0)
        a_log_file = file('%s' % log_file, 'a')
        a_log_file.write('remove control lockfile OK.\n')
        a_log_file.close()

main_()
title='['+report_status+'] change internal dns records result'
mailmsg=commands.getoutput('/bin/cat '+log_file)
sendmail(mailmsg,tomail,title)
