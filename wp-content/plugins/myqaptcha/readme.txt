=== Plugin Name ===

Contributors:      Clove
Donate link:       https://me.alipay.com/clove
Requires at least: 3.0.0
Tested up to:      3.8.1
Stable tag:        myQaptcha,Clove,30c.org,spam,post
Tags:              myQaptcha,Clove,30c.org,spam,post
License:	   GPLv2

给文章评论添加滑动解锁,防止机器人和垃圾评论.已支持手机和平板.
微信公众号 三十岁 


== Description ==

Add Qaptcha unlock tool to WordPress's Comment,against the spam comment,just like as akismet used to.

If you have any suggestions or questions ,Please visit http://blog.30c.org/2006.html 

If you want to support me or my development work , please visit http://blog.30c.org/donate Thank you!


在单页文章评论处添加滑动解锁,使用Ajax操作Session防止垃圾评论和机器人,让你不用整天忙于文章审核.纯绿色插件,不修改数据库、无需中转页面、无需加载任何第三方代码、安装简单卸载干净、轻巧迅速

为减少插件体积和服务器加载负担，所有jQuery公共api资源均调用的sina apis库

增加手机和平板等手持终端设备支持,已经测试android和ios自带浏览器,mobile chrome firefox opera 和UC

相关插件及任何问题可在我的插件发布页面反馈即： http://blog.30c.org/2006.html 

如果您要支持我继续开发或者完善,请访问 https://me.alipay.com/clove 谢谢！

微信公众号 三十岁 微信微社区 http://t.cn/8svzgmk

== Installation ==

To install this plugin , search myQaptcha online and enable it in the wordpress's admin board, without any set up and code changes!

Uninstall only need to delete, without any residue.

直接在后台搜索 myQaptcha 在线安装启用即可，无需任何设置和代码修改！

卸载也同样方便，直接删除即可，无任何数据库残留！


== Frequently Asked Questions ==

常见问题：

1.如果遇到与其他插件位置冲突,请改变插件激活次序

2.本插件使用jQuery1.7以上,并且使用wp_enqueue_script()以及wp_register_script()加载，请防止其他themes或者plugins的不规范jQuery加载造成冲突


== Upgrade Notice ==

直接在后台搜索 myQaptcha 在线安装启用即可，无需任何设置和代码修改！

卸载也同样方便，直接删除即可，无任何数据库残留！

== Changelog ==

= 2014-04-05 1.1.1 =
小Bug修正,欢迎提出建议,欢迎关注个人无聊做的微信公众号--三十岁

= 2014-04-04 1.1.0 =
我勒个去,一晃2年过去了,时间不等人啊.有一个哥们在我博客找了我一年,我实在不好意思了决定把插件更新好了.现在支持android和ios自带浏览器,手机chrome ff和uc的滑动解锁了,其他非主流手机浏览器没有测试环境.
还有就是修改了js加载方式,放弃了wp的注册加载(实在是有太多主题模板乱七八糟的加载jQuery),使用js判断加载.使用baidu的api源.
联系我请使用微信公众号 三十岁 微信微社区 http://t.cn/8svzgmk 博客准备转移到国内 http://30c.org

= 2012-05-04 1.0.4 =
青年节哎,解决重复加载jQuery问题.只要不是2次以上jQuery重复加载并且版本不低于1.4.3的都可以不用在乎冲突.部分主题小几率可能出现拖动条无法复位情况.

= 2012-03-02 1.0.3 =
针对Willin Kan的Ajax comments提交处理,完善验证方式.目前支持Ajax评论常见themes都做了测试.

= 2012-02-28 1.0.2 =
修正一个小Bug,firefox浏览器访问Unix服务器时,加载js不识别大小写.

= 2012-02-28 1.0.1 =
针对纵向排列模板做了一点小优化和判断处理。

= 2012-02-27 1.0.0 =
完成Qaptcha的插件改造和模板测试，实现滑动解锁功能

== Screenshots ==

1. myQaptcha for WordPress
2. myQaptcha for WordPress
3. mobile devices