#!/usr/bin/expect

set secret [lindex $argv 0]
set passwd [lindex $argv 1]

set timeout 10
spawn ssh gaonan01@relay.zuoyebang.cc
expect {
    "*yes/no" { 
        send "yes\r"
        exp_continue
    }
    "*code:" { 
        send "$secret\r"
        exp_continue
    }
    "Password:" { 
        send "$passwd\r"
    }
}
interact

