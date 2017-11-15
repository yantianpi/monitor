# monitor
使用php开发的定时任务监控执行系统

## 项目背景

本人所在公司的PHP项目中有爬虫，页面监控，数量多而繁杂，不好管理，希望能有一个系统可以进行统一管理（小型监控cms）,因此做了这个项目。项目核心借助cronjob(一个主cronjob周期执行根据cms系统配置，批量生成子cronjob列表更新现有cronjob)，监控任务有分类，每个分类对应不同处理程序(处理程序不是本系统重点)，本系统支持任务项细粒度配置，可以配置通知类型，不同通知类型有不同通知程序(通知程序不是本系统重点)。任务支持批量任务执行及单任务执行。有log记录过往执行情况。

## 功能特点

* 统一管理多种类任务项。
* 秒级定时器，使用crontab的时间表达式。
* 可随时更新任务。
* 记录每次任务的执行有日志记录。
* 执行结果邮件通知。
* 扩展性强(可随时添加其他种类监控，添加相关任务项配置，处理程序，通知程序即可)

## 任务列表截图

![monitor](https://github.com/yantianpi/monitor/tree/master/screenshot.png)


## 安装说明

系统需要安装Git,Php和MySQL。

获取源码

	$ git clone git@github.com:yantianpi/monitor.git
	

创建数据库github_monitor，再依次导入initDDL.sql，dataDML.sql

运行
	
	配置相关服务器，访问即可

