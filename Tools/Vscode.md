# Vscode 插件设置
### 1.用户设置
```json
{
  //设置语言为中文
  "locale":"zh-cn",
  // 关闭自动保存功能
  "files.autoSave": "off",
  // 设置默认生成的文件行结尾为"\n" 表示 LF，"\r\n" 表示 CRLF，auto 表示使用特定于操作系统的行尾字符
  "files.eol":  "\n",
  // 标签页的标题显示
  "window.title": "${dirty}${activeEditorShort}${separator}${activeEditorLong}",
  // 控制菜单栏的位置 在 left 或 right
  "workbench.sideBar.location": "left",
  // 编辑器默认 tab 键时2个空格
  "editor.tabSize": 2,
  "breadcrumbs.enabled": true,
  // git 自动更新
  "git.autofetch": true,
  // 设置打开终端时使用 windows 管理员的 powershell 命令执行
  "terminal.integrated.shell.windows": "C:\\WINDOWS\\System32\\WindowsPowerShell\\v1.0\\powershell.exe",
}
```

### 2. 换行快捷键 `alt + z`