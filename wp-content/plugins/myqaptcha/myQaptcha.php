<?php
/*
Plugin Name: myQaptcha
Version:     1.1.1
Plugin URI:  http://blog.30c.org/2006.html
Description: 在单页文章评论处添加滑动解锁,使用Session技术防止垃圾评论和机器人,让你不用整天忙于文章审核.纯绿色插件,不修改数据库、无需中转页面、无需加载任何第三方代码、安装简单卸载干净、轻巧迅速
Author:      Clove
Author URI:  http://blog.30c.org
License: GPL v2 - http://www.gnu.org/licenses/gpl.html
*/

function my_scripts_method() {
    wp_deregister_script( 'jquery' );
	wp_deregister_script( 'jquery ui' );
    //wp_register_script( 'jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js');
	//wp_register_script( 'jquery ui', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.min.js');
	wp_register_script( 'jquery', 'http://libs.baidu.com/jquery/2.0.0/jquery.min.js');
	wp_register_script( 'jquery ui', 'http://lib.sinaapp.com/js/jquery-ui/1.9.2/jquery-ui.min.js');
    wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'jquery ui' );
}

function myQaptcha_wp_footer() {
	if (is_singular() && !is_user_logged_in()) {
		$url = get_bloginfo("wpurl");
		$outer = '<link rel="stylesheet" href="' . $url . '/wp-content/plugins/myqaptcha/jquery/myQaptcha.jquery.css" type="text/css" />'."\n";
		$outer .= '<script type="text/javascript">function loadJS(filename){var fileref=document.createElement("script");fileref.setAttribute("type","text/javascript");fileref.setAttribute("src", filename); document.getElementsByTagName("head")[0].appendChild(fileref);} if (typeof jQuery == "undefined") loadJS("http://libs.baidu.com/jquery/2.0.0/jquery.min.js"); </script>';
		$outer.= '<script type="text/javascript" src="' . $url . '/wp-content/plugins/myqaptcha/jquery/jquery-ui.js"></script>'."\n";
		$outer.= '<script type="text/javascript" src="' . $url . '/wp-content/plugins/myqaptcha/jquery/jquery.ui.touch.js"></script>'."\n";
		$outer.= '<script type="text/javascript">var myQaptchaJqueryPage="' . $url . '/wp-content/plugins/myqaptcha/jquery/myQaptcha.jquery.php";</script>'."\n";
		$outer.= '<script type="text/javascript" src="' . $url . '/wp-content/plugins/myqaptcha/jquery/myqaptcha.jquery.js"></script>'."\n";
		$outer.= '<script type="text/javascript">var newQapTcha = document.createElement("div");newQapTcha.className="QapTcha";var tagIDComment=document.getElementById("comment");if(tagIDComment){tagIDComment.parentNode.insertBefore(newQapTcha,tagIDComment);}else{var allTagP = document.getElementsByTagName("p");for(var p=0;p<allTagP.length;p++){var allTagTA = allTagP[p].getElementsByTagName("textarea");if(allTagTA.length>0){allTagP[p].parentNode.insertBefore(newQapTcha,allTagP[p]);}}}jQuery(document).ready(function(){jQuery(\'.QapTcha\').QapTcha({disabledSubmit:true,autoRevert:true});});</script>'."\n";
		echo $outer;
	}
}

function myQaptcha_preprocess_comment($comment) {
	if (!is_user_logged_in()) {
		if(!session_id()) session_start();
		if ( isset($_SESSION['30corg']) && $_SESSION['30corg']) {
			unset($_SESSION['30corg']);
			return($comment);
		} else {
			if (isset($_POST['isajaxtype']) && $_POST['isajaxtype'] > -1) {
				//header('HTTP/1.1 405 Method Not Allowed');   clove   find some error with ajax submit  2012-03-02
				die("请滑动滚动条解锁");
			} else {
				if(function_exists('err'))
					err("请滑动滚动条解锁");
				else
					wp_die("请滑动滚动条解锁");
			}
		}
	} else {
		return($comment);
	}
}
//delete wp script register clove 20140404
//add_action('wp_enqueue_scripts', 'my_scripts_method');
add_action('wp_footer', 'myQaptcha_wp_footer');
add_action('preprocess_comment', 'myQaptcha_preprocess_comment');
?>