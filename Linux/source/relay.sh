#!/usr/bin/expect

# set passwd [lindex $argv 0]
set passwd 11111111
set verification [exec sh -c {/usr/bin/python3 /Users/gn/authy.py SDFESE2SDFD4SAAZ}]

set timeout 10
spawn echo $verification
spawn echo $passwd
spawn ssh gaonan01@relay.zuoyebang.cc
expect {
    "*yes/no" { 
        send "yes\r"
        exp_continue
    }
    "*Verification code:" { 
        send "$verification\r"
        exp_continue
    }
    "Password:" { 
        send "$passwd\r"
    }
}
interact