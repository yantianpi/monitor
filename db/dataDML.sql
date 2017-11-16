/*Data for the table `project_list`*/
INSERT INTO `project_list` (`Id`, `Name`, `Status`, `AddTime`, `UpdateTime`, `Timestamp`) VALUES ('1', 'pagemonitor', 'ACTIVE', '2017-07-19 17:17:58', '2017-07-19 17:18:01', '2017-07-19 17:18:03');


/*Data for the table `attribute_list` */

insert  into `attribute_list`(`Id`,`CategoryId`,`Name`,`Alias`,`ContentType`,`DefaultMessage`,`Status`,`AddTime`,`UpdateTime`,`Timestamp`) values (1,1,'url','链接','STRING','链接访问正常','ACTIVE','2017-07-06 10:29:18','2017-07-06 10:30:38','2017-07-11 16:31:53'),(2,1,'type','页面类型','STRING','','ACTIVE','2017-07-06 10:29:18','2017-07-06 10:30:38','2017-07-11 16:31:53'),(3,1,'method','提交类型','STRING','','ACTIVE','2017-07-06 10:29:18','2017-07-06 10:30:38','2017-07-11 16:31:53'),(4,1,'params','参数','STRING','参数正确','ACTIVE','2017-07-06 10:29:18','2017-07-06 10:30:38','2017-07-11 16:31:53'),(5,1,'httpCode','返回码','INT','返回码正常','ACTIVE','2017-07-06 10:29:18','2017-07-06 10:30:38','2017-07-11 16:31:53'),(6,1,'header','头部信息','STRING','','ACTIVE','2017-07-06 10:29:18','2017-07-06 10:30:38','2017-07-11 16:31:53'),(7,1,'responseTime','响应时间','INT','响应时间正常','ACTIVE','2017-07-06 10:29:18','2017-07-06 10:30:38','2017-07-11 17:28:56'),(8,1,'contentSize','内容大小','INT','内容返回正常','ACTIVE','2017-07-06 10:29:18','2017-07-06 10:30:38','2017-07-11 16:31:53'),(9,1,'whiteList','白名单1','REGEX','','ACTIVE','2017-07-06 10:29:18','2017-07-06 10:30:38','2017-07-11 16:31:53'),(10,1,'whiteList2','白名单2','REGEX','','ACTIVE','2017-07-06 10:29:18','2017-07-06 10:30:38','2017-07-11 16:31:53'),(11,1,'whiteList3','白名单3','STRING','','ACTIVE','2017-07-06 10:29:18','2017-07-06 10:30:38','2017-07-11 16:31:53'),(12,1,'blackList','黑名单','REGEX','','ACTIVE','2017-07-06 10:29:18','2017-07-06 10:30:38','2017-07-11 16:31:53'),(13,1,'blackList2','黑名单2','STRING','','ACTIVE','2017-07-06 10:29:18','2017-07-06 10:30:38','2017-07-11 16:31:53');

/*Data for the table `batch_list` */

insert  into `batch_list`(`Id`,`Name`,`Alias`,`Crontime`,`Throughput`,`Status`,`AddTime`,`UpdateTime`,`Timestamp`) values (1,'per10minute','每10分钟','*/10 * * * *',100,'ACTIVE','2017-07-05 18:58:19','2017-07-05 18:58:23','2017-07-05 18:58:25'),(2,'per30minute','每30分钟','*/30 * * * *',100,'ACTIVE','2017-07-05 18:59:49','2017-07-05 18:59:52','2017-07-05 18:59:55');

/*Data for the table `category_list` */

insert  into `category_list`(`Id`,`Name`,`Alias`,`Script`,`Status`,`AddTime`,`UpdateTime`,`Timestamp`) values (1,'graburl','url抓取','monitorUrlInformation.php','ACTIVE','2017-07-05 18:56:18','2017-07-05 18:56:23','2017-07-07 11:24:11');
