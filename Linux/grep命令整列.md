# grep 命令整理

```bash
fsh 192.168.133.208 fwyymis ssh "grep -P '19:00' /home/homework/log/ral/ral-worker.log.wf.2020080319 | grep 'err_info=Talk.*?Failed' | grep 'log_type=E_SUM' | grep 'module=assistantdesk' | wc -l"

```