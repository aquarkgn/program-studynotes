# SSH自动输入Verification验证码登录

## 获取`google authenticator`二维码中秘钥 `secret`

使用[二维码扫秒工具](https://www.baidu.com/link?url=RQPSLNJpouLelTZACNsaspIRkYRxp5Ta5JBaQEpR_Uu&wd=&eqid=82224d5200122ddb000000035f2a7c84)
识别二维码信息`otpauth://totp/user@tt.lll.com?secret=SDFESE2SDFD4SAAZ&issuer=tt.lll.com`，
取得 `secret` 秘钥信息: `SDFESE2SDFD4SAAZ`

## 验证码生成脚本 [google authenticator验证码生成脚本](./source/authy.py)

## ssh登录脚本 [SSH登录脚本](./source/relay.sh)

```bash
./relay.sh
```
