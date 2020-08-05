#!/usr/bin/python
# coding:utf-8

# 通过secret 生成 Google Authenticator  验证码
import hmac, base64, struct, hashlib, time, sys

secretKey = sys.argv[1]

def calGoogleCode(secretKey):
    input = int(time.time())//30
    key = base64.b32decode(secretKey)
    msg = struct.pack(">Q", input)
    googleCode = hmac.new(key, msg, hashlib.sha1).digest()
    o = googleCode[19] & 15
    googleCode = str((struct.unpack(">I", googleCode[o:o+4])[0] & 0x7fffffff) % 1000000)
    if len(googleCode) == 5:
        googleCode = '0' + googleCode
    print(googleCode)

calGoogleCode(secretKey)
