<?php
/*
Plugin Name: CNZZ&51LA for WordPress
Plugin URI: http://blog.printf.com.cn/cnzz-and-51la-for-wordpress/
Description: 方便WordPress用户使用CNZZ和51LA的统计功能
Version: 1.0.1
Author: jprintf
Author URI: http://blog.printf.com.cn
*/
add_action('admin_menu', 'cnzz_for_wordpress_menu');
function cnzz_for_wordpress_menu() {
	add_options_page('请把cnzz或者51啦的统计代码复制进来', 'CNZZ&51LA for WordPress', 8, 'cnzz_for_wordpress_menu','cnzz_for_wordpress_set');
}

function cnzz_for_wordpress_set() {
	if(!empty($_POST["Submit"])){
		cnzz_for_wordpress_save();
	}
?>
<form action="" method="post" name="form1" id="form1">
	<p><input name="isShow" type="checkbox" id="isShow" value="1000"  
	<?php
		if(get_option("jprintf_cnzz_or_51la_ishow") != "-1000") {
			echo ' checked="checked" ';
		}
	?>/>隐藏统计的文字</p>
	<p><a href="http://www.cnzz.com/" target="_blank">CNZZ</a>或者<a href="http://www.51.la/" target="_blank">51LA</a>的统计代码：<br />
		<textarea name="cnzz_or_51la" rows="8" id="cnzz_or_51la" style="width:80%"><?php echo get_option("jprintf_cnzz_or_51la")?></textarea>
  </p>
	<p>
		<input type="submit" name="Submit" value="提交" />
	</p>
</form>
<?php
}

function cnzz_for_wordpress_save() {
	$seccess = false;
	if(!empty($_POST["isShow"])){
		if(get_option("jprintf_cnzz_or_51la_ishow")){
			update_option("jprintf_cnzz_or_51la_ishow",$_POST["isShow"]);
		}else{
			add_option("jprintf_cnzz_or_51la_ishow",$_POST["isShow"]);
		}
		$seccess = true;
	}else{
		if(get_option("jprintf_cnzz_or_51la_ishow")){
			update_option("jprintf_cnzz_or_51la_ishow","-1000");
		}else{
			add_option("jprintf_cnzz_or_51la_ishow","-1000");
		}
		$seccess = true;
	}

	if(!empty($_POST["cnzz_or_51la"]) && strlen(trim($_POST["cnzz_or_51la"]))!=0){
		$data = stripslashes(trim($_POST["cnzz_or_51la"]));

		if(get_option("jprintf_cnzz_or_51la")){
			update_option("jprintf_cnzz_or_51la",$data);
		}else{
			add_option("jprintf_cnzz_or_51la",$data);
		}

		$seccess = true;
	}else{
		delete_option("jprintf_cnzz_or_51la");
		$seccess = true;
	}
	if($seccess){
	?>
	<div style="color:#0000ff; padding:10px 0 10px 30px; font-size:16px">
		设置成功！
	</div>
	<?php
	}
}

add_action('wp_footer', 'cnzz_for_wordpress_show_footer');
function cnzz_for_wordpress_show_footer() {
	if(get_option("jprintf_cnzz_or_51la")){
		if(get_option("jprintf_cnzz_or_51la_ishow") == "1000"){
			echo '<div style="display:none">';
			echo get_option("jprintf_cnzz_or_51la");
			echo '</div>';
		}else{
			echo get_option("jprintf_cnzz_or_51la");
		}
	}
	echo '<div style="display:none">';
	echo '<a href="http://blog.printf.com.cn/" title="普人特福的博客">普人特福的博客</a>';
	echo '<a href="http://blog.printf.com.cn/" title="cnzz&51la for wordpress,cnzz for wordpress,51la for wordpress">cnzz&51la for wordpress,cnzz for wordpress,51la for wordpress</a>';
	echo '</div>';
}
?>
