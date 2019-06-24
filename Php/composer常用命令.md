#### composer 自身更新
请经常执行 `composer selfupdate` 以保持 Composer 一直是最新版本。
`composer selfupdate` 等价于 `composer self-update`

#### composer dumpautoload 自动加载
当我们更改了 composer.json 文件中的 autoload 时，需要执行 composer dumpautoload，来让 autoload 立即生效。而不必执行 install 或 update 命令。

`composer dumpautoload`等价于`composer dump-autoload`

```
dumpautoload 命令有两个常用的选项：

--optimize (-o)： 转换 PSR-0/4 autoloading 到 classmap ，以获得更快的载入速度。这特别适用于生产环境，但可能需要一些时间来运行，因此它目前不是默认设置。
--no-dev： 禁用 autoload-dev 规则。
```

### composer install
```
依据当前目录下的 composer.lock（锁文件） 或 composer.json 文件，所定义的依赖关系，安装依赖包。

install 命令会先检查 composer.lock 锁文件是否存在，如果存在，它将下载 composer.lock 文件中指定的版本，而忽略 composer.json 文件中的定义。

# 查看 composer install 的帮助信息
`composer install -h`
 
# 只安装 require 中定义的依赖，而不安装 require-dev 中定义的依赖
`composer install --no-dev`
```

### composer update
如果你想更新你的依赖版本，或者你修改了 composer.json 中的依赖关系，想让 composer 按照 composer.json 文件中的定义执行更新操作，就用 update 命令。
```
composer update
```

### composer require
```
require 命令一般用来安装新的依赖包，并将依赖写入当前目录的 composer.json 文件中。

如果 composer.json 文件中，添加或改变了依赖，修改后的依赖关系将被安装或者更新。

composer require
你也可以直接在命令中指明需要安装的依赖包。

composer require barryvdh/laravel-ide-helper
--dev 选项和 require-dev 相对应。如果你的依赖包仅仅用于开发环境，建议加上 --dev 选项。

composer require --dev barryvdh/laravel-ide-helper
```

### composer create-project
```
你可以使用 create-project 从现有的包中创建一个新的项目。

它相当于执行了 git clone 命令后，将这个包的依赖安装到它自己的 vendor 目录。

此命令有几个常见的用途：

你可以快速的部署你的应用。
你可以检出任何资源包，并开发它的补丁。
多人开发项目，可以用它来加快应用的初始化。
```
# 安装 Laravel 项目
#### `composer create-project --prefer-dist laravel/laravel blog 5.5.*`
```
如果没有指定版本号，就默认安装最新的版本。

--prefer-dist: 当有可用的包时，从 dist 安装。
```
### `composer search`
```
search 命令可以搜索远程的依赖包，通常它只搜索 packagist.org 上的包，你可以简单的输入你的搜索条件。

composer search monolog
--only-name (-N)选项， 仅针对指定的名称搜索（完全匹配）。
```
#### `composer show`
```
列出所有可用的软件包，你可以使用 show 命令。

composer show
如果你想查看一个包的详细信息，你可以输入包名称。
```
#### `composer show monolog/monolog`
```
选项：
--installed (-i): 列出已安装的依赖包。
--platform (-p): 仅列出平台软件包（PHP 与它的扩展）。
--self (-s): 仅列出当前项目的信息。
config
config 命令允许你编辑 Composer 的一些基本设置，无论是本地的 composer.json 还是全局的 config.json 文件。
```



#### `composer config --list` 查看 Composer 的配置信息
```
语法：config [options] [setting-key] [setting-value1] ... [setting-valueN]

setting-key 是配置选项的名称，setting-value1 是配置的值。可以使用数组作为配置的值（像 github-protocols），多个 setting-value 是允许的。

例如，全局配置 Composer 的国内镜像：

composer config -g repo.packagist composer https://packagist.phpcomposer.com
选项：

--global (-g)： 操作 $COMPOSER_HOME/config.json 全局配置文件。如果不指定该参数，此命令将影响当前项目的 composer.json 文件，或 --file 参数所指向的文件。
--editor (-e)： 使用文本编辑器打开 composer.json 文件。默认情况下始终是打开当前项目的文件。当存在 --global 参数时，将会打开全局的 config.json 文件。
--unset； 移除由 setting-key 指定的配置选项。
--list (-l)： 查看当前配置选项的列表。当存在 --global 参数时，将会显示全局配置选项的列表。
--file="..." (-f)：在一个指定的文件上操作，而不是 composer.json。它不能与 --global 参数一起使用。
```

#### `composer run-script` 命令，可用来手动执行脚本，只需要指定脚本的名称即可。

假如，composer.json 中存在如下脚本。
```
{
    "scripts": {
        "post-update-cmd": "MyVendor\\MyClass::postUpdate",
        "post-package-install": [
            "MyVendor\\MyClass::postPackageInstall"
        ],
        "post-install-cmd": [
            "MyVendor\\MyClass::warmCache",
            "phpunit -c app/"
        ]
    }
}
```
运行所有 `post-install-cmd` 事件下定义的脚本：

`composer run-script post-install-cmd`