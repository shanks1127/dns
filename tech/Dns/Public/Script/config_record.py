#!/usr/bin/python
####################################################################
# Create bind's conf.
# Create Date :  2014-5-22
# Written by :shanks
# Organization:  DangDang
####################################################################
# -*- coding: UTF-8 -*-
import sys
import os
import re
import time
import MySQLdb
import commands

#######################################
#
#	!!!Run on the master!!!
#
#######################################
#mail list.
#The path of config files.
bind_etc_path="/var/named/chroot/etc/"
#log_file="/var/log/config_record.log"
DATA_NOW = time.strftime('%Y%m%d-%H%M%S')
backup_target = "/home/work/public/opdir/backup/"
lock_file="/var/lock/config_dns.lock"
#View.conf's name.
view_conf="view.conf"
#Set connect mysql.
dbhost='192.168.122.101'
dbuser='dns'
dbpasswd='password'
dbname='internaldns'
dbport=3306
#set serial.
serial = int(time.strftime('%s'))

def end():
    title='change internal dns records result'
    #mailmsg=commands.getoutput('cat '+log_file)
    #sendmail(mailmsg,tomail,title)
    if os.path.isfile(lock_file):
        os.remove(lock_file)
    print 'config named ERROR!!!'
    sys.exit(0)

def init_log():
    #c_log_file = file('%s' % log_file, 'w')
    #c_log_file.close()
    print '===192.168.122.101===***%s***======' % DATA_NOW

def check_env():
    #check system env.
    if os.path.exists(bind_etc_path) != True:
        #c_log_file = file('%s' % log_file, 'a')
        #c_log_file.write(bind_etc_path+' not found.\n')
        #c_log_file.close()
        print bind_etc_path+' not found.'
        end()
    
def backup_config():
#Backup the config files,by tar.
    backup_cmd = 'rsync -avq '+bind_etc_path+' '+backup_target+DATA_NOW
    if os.system(backup_cmd) == 0:
        #c_log_file = file('%s' % log_file, 'a')
        #c_log_file.write('backup '+bind_etc_path+' to '+backup_target+'/'+DATA_NOW+' OK.\n')
        #c_log_file.close()
        print 'backup '+bind_etc_path+' to '+backup_target+'/'+DATA_NOW+' OK.'
    else:
        #c_log_file = file('%s' % log_file, 'a')
        #c_log_file.write('backup '+bind_etc_path+' to '+backup_target+'/'+DATA_NOW+' ERROR.\n')
        #c_log_file.close()
        print 'backup '+bind_etc_path+' to '+backup_target+'/'+DATA_NOW+' ERROR.'
        end()

def select_mysql():
#define connect mysql,get the zonename.
    try:
	      conn = MySQLdb.connect(host=dbhost,user=dbuser,db=dbname,passwd=dbpasswd,port=dbport)
	      cursor = conn.cursor(cursorclass = MySQLdb.cursors.DictCursor)
	      #Get the zone_list from mysql.
	      cursor.execute('select distinct zone from dns_records')
	      cursor.scroll(0,mode='absolute')
	      #Set zone_list to global.
	      global zone_list
	      zone_list = cursor.fetchall()
	      cursor.close()
	      conn.close()
    except MySQLdb.Error,e:
        #c_log_file = file('%s' % log_file, 'a')
        #c_log_file.write('Mysql Error %d: %s\n' % (e.args[0], e.args[1]))
        #c_log_file.close()
        print 'Mysql Error %d: %s' % (e.args[0], e.args[1])
        end()

def init_zone_conf_data():
#Init *.zone file path data.
    try:
        conn = MySQLdb.connect(host=dbhost,user=dbuser,db=dbname,passwd=dbpasswd,port=dbport)
        cursor = conn.cursor(cursorclass = MySQLdb.cursors.DictCursor)
        for zone_name in zone_list:
            zone_ = zone_name['zone']
            #Get the data for *.zone from mysql.
            cursor.execute('select host,zone,data,dnstype from dns_records where zone="%s" order by host' %zone_)
            cursor.scroll(0,mode='absolute')
            zone_data = cursor.fetchall()
            #Uppercase zone name
            if zone_ == 'dangdang.com':
                zone_upp=zone_
                #set the zonefile global.
                m_zone_file = file('%s/%s.zone' %(bind_etc_path,zone_upp) , 'w')
                m_zone_file.write('$ORIGIN .\n$TTL 3600\n%s\tIN SOA  dns246. hostmaster. (\n\t%s\t;serial\n\t900\t;refresh\n\t600\t;retry\n\t86400\t;expire\n\t3600\t;minimum\n\t)\n\tNS\tdns246.\n\tMX  10  mail.dangdang.com.\n$ORIGIN %s.\n\n' %(zone_,serial,zone_))
                m_zone_file.close()
            else:
                zone_upp=zone_.upper()
                #set the zonefile global.
                m_zone_file = file('%s/%s.zone' %(bind_etc_path,zone_upp) , 'w')
                m_zone_file.write('$ORIGIN .\n$TTL 3600\n%s\tIN SOA  dns246. hostmaster. (\n\t%s\t;serial\n\t900\t;refresh\n\t600\t;retry\n\t86400\t;expire\n\t3600\t;minimum\n\t)\n\tNS\tdns246.\n$ORIGIN %s.\n\n' %(zone_,serial,zone_))
                m_zone_file.close()
            m_zone_file = file('%s/%s.zone' %(bind_etc_path,zone_upp) , 'a')
            for zone_data_ in zone_data:
                zone_data_host = zone_data_['host']
                zone_data_data = zone_data_['data']
                zone_data_type = zone_data_['dnstype']
                if zone_data_type == 'MX':
                    zone_data_type = 'MX\t10'
                m_zone_file.write('%s\t%s\t%s\n'  %(zone_data_host,zone_data_type,zone_data_data))
            m_zone_file.close()
        cursor.close()
        conn.close()
    except MySQLdb.Error,e:
        #c_log_file = file('%s' % log_file, 'a')
        #c_log_file.write('Mysql Error %d: %s\n' % (e.args[0], e.args[1]))
        #c_log_file.close()
        print 'Mysql Error %d: %s' % (e.args[0], e.args[1])
        end()

def check_config():
#Check the config format.
    #check the format.
    for zone in zone_list:
        zone_ = zone['zone']
        #Uppercase zone name
        if zone_ == 'dangdang.com':
            zone_upp=zone_
        else:
            zone_upp=zone_.upper()
        checkconf_cmd = 'named-checkzone -q -k ignore '+zone_+' '+bind_etc_path+zone_upp+'.zone '
        if os.system(checkconf_cmd) == 0:
            #c_log_file = file('%s' % log_file, 'a')
            #c_log_file.write('check '+bind_etc_path+zone_upp+'.zone format OK\n')
            #c_log_file.close()
            print 'check '+bind_etc_path+zone_upp+'.zone format OK'
        else:
            #c_log_file = file('%s' % log_file, 'a')
            #c_log_file.write('check '+bind_etc_path+zone_upp+'.zone format ERROR\n')
            #c_log_file.close()
            print 'check '+bind_etc_path+zone_upp+'.zone format ERROR'
            roback_config()
            end()
    reload_config()

def roback_config():
    roback_cmd = 'rsync -avq '+backup_target+DATA_NOW+'/* '+bind_etc_path
    if os.system(roback_cmd) == 0:
        #c_log_file = file('%s' % log_file, 'a')
        #c_log_file.write('roback '+backup_target+'/'+DATA_NOW+' to '+bind_etc_path+' OK.\n')
        #c_log_file.close()
        print 'roback '+backup_target+'/'+DATA_NOW+' to '+bind_etc_path+' OK.'
    else:
        #c_log_file = file('%s' % log_file, 'a')
        #c_log_file.write('roback '+backup_target+'/'+DATA_NOW+' to '+bind_etc_path+' ERROR.\n')
        #c_log_file.close()
        print 'roback '+backup_target+'/'+DATA_NOW+' to '+bind_etc_path+' ERROR.'
    end()

def reload_config():
    reload_cmd = 'rndc reload'
    if os.system(reload_cmd) == 0:
        #c_log_file = file('%s' % log_file, 'a')
        #c_log_file.write('reload conf OK.\n')
        #c_log_file.close()
        print 'reload conf OK.'
    else:
        #c_log_file = file('%s' % log_file, 'a')
        #c_log_file.write('reload conf ERROR.\n')
        #c_log_file.close()
        print 'reload conf ERROR.'
        end()

def check_lock():
    if os.path.isfile(lock_file):
        print 'LOCKED'
        end()
    else:
        init_log()
        create_lock = open(lock_file,'w')
        create_lock.close()
        if os.path.isfile(lock_file):
            #c_log_file = file('%s' % log_file, 'a')
            #c_log_file.write('create lockfile OK.\n')
            #c_log_file.close()
            print 'create masterdns lockfile OK.'
        else:
            #c_log_file = file('%s' % log_file, 'a')
            #c_log_file.write('create lockfile ERROR.\n')
            #c_log_file.close()
            print 'create masterdns lockfile ERROR.'
            end()

def rm_lock():
    if os.path.isfile(lock_file):
        os.remove(lock_file)
        if os.path.isfile(lock_file):
            #c_log_file = file('%s' % log_file, 'a')
            #c_log_file.write('remove lockfile ERROR.\n')
            #c_log_file.close()
            print 'remove masterdns lockfile ERROR.'
            end()
        #a_log_file = file('%s' % log_file, 'a')
        #a_log_file.write('remove lockfile OK.\n')
        #a_log_file.close()
        print 'remove masterdns lockfile OK.'
    print 'config named OK'

def main_():
#The main function.
    check_lock()
    check_env()
    backup_config()
    select_mysql()
    init_zone_conf_data()
    check_config()
    #title='change internal dns records result'
    #mailmsg=commands.getoutput('cat '+log_file)
    #sendmail(mailmsg,tomail,title)
    rm_lock()

main_()
