# 游戏辅助商城靶场



## 一、基础配置



### 0、环境

```
本项目用的是phpStudy+Mysql+Nginx环境，数据库管理工具为Navicat
```



### 1、数据库配置

```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');       // 默认root
define('DB_PWD', 'root');        // PHPStudy默认密码root（若修改过则对应修改）
define('DB_NAME', 'shop_db');    // 新建的数据库名
```



### 2、数据库文件导入

在数据库管理工具中新建一个名为`shop_db`的数据库，并将`backup.sql`导入运行



## 二、网站创建



### 1、中间件启动

`phpstudy`启动Nginx和Mysql中间件



### 2、创建网站

文件目录即项目所在目录，端口号设置8080，若占用或更改，`config.php`也要对应更改

```php
define('BASE_URL', 'http://localhost:8080'); // 新建的网站域名
```



## 三、网站运行

访问localhost:8080即可运行网站