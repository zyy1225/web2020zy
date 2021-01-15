# web2020zy
商品抢购秒杀系统，包含后台管理系统和用户抢购平台
## 一、环境
后台管理系统使用thinkPHP框架和X-admin2.0框架，用html和PHP编写前后端。
用户抢购平台使用html，css，JavaScript，Vue搭建前端，利用PHP，Go结合Redis和RabbitMQ搭建后端。
## 二、具体功能
#### 商品抢购后台管理系统具体功能如下表所示
##### 登录	
登录、输入三次需要输入验证码、权限检查、按角色显示菜单（Redis、session、cookie）
人员管理设置功能	
##### 用户管理
查看列表，分页显示，添加用户，修改用户信息，删除用户，权限管理查看
##### 系统管理	
菜单管理：添加父级菜单，启用或停用该菜单，编辑菜单信息，添加子菜单，删除菜单
日志管理：日志查看，分页显示
##### 商品购买管理	
买家信息查看、买家信息修改、买家信息删除、商品信息查看
##### 个人设置	
修改密码
##### 退出登录
#### 抢购系统功能具体包括如下所示
##### 登录	
验证码登录、权限检查、免登录（token实现）
##### 注册	
前端对输入进行检查
##### 权限管理查看
##### 商品抢购	
PHP后端进行初步检测，通过Redis检查库存量，判断当前ID是否已经参与购买，将数据传送给Go程序，Go语言结合RabbitMQ处理
查看当前ID购买情况，查表获取当前购买状态，对用户进行反馈。
##### 退出登录
## 三、部署
sql文件夹中为sql语句，运行即可,
websys.com为后台服务系统的全部代码，前后端代码在websys.com\application\admin下的controller和view文件夹下,
finalsys_user文件夹为用户抢购平台的全部源码;
#### 运行源码需要做的：
##### 配置好数据库环境，将代码中的数据库名称及密码修改为部署环境所用的数据库名与密码
##### 部署thinkPHP环境，将websys.com代码部署完毕
##### 部署站点，将finalsys_user文件夹下所有代码复制到所部属站点下
##### 安装Redis和RabbitMQ，初始需新设置Redis中的两个字段，即login_nums（用以完成三次输入错误进行验证码登录），rabbit_nums（用以完成抢购）
##### 即可通过浏览器访问

