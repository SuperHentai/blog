<?php $options = get_neutral_option(); ?>
 <div id="footer">
  <ul id="copyright">
   <li style="background:none;"><a href="<?php echo esc_url(home_url('/')); ?>"><?php bloginfo('name'); ?></a></li>
   <li><a href="http://www.mono-lab.net/" class="target_blank"><?php _e('Theme designed by mono-lab','neutral'); ?></a></li>
   <li><a href="http://wordpress.org/" class="target_blank"><?php _e('Powered by WordPress','neutral'); ?></a></li>
  </ul>
  <?php if ($options['show_return_top']) : ?>
  <a href="#wrapper" id="return_top"><?php _e('Return top','neutral'); ?></a>
  <?php endif; ?>
 </div><!-- END #footer -->

</div><!-- END #wrapper -->
<?php wp_footer(); ?>
<script src="http://s5.cnzz.com/z_stat.php?id=1000336629&web_id=1000336629" language="JavaScript"></script>
<script type="text/javascript">var cnzz_protocol = (("https:" == document.location.protocol) ? " https://" : " http://");document.write(unescape("%3Cspan id='cnzz_stat_icon_1000336629'%3E%3C/span%3E%3Cscript src='" + cnzz_protocol + "s5.cnzz.com/z_stat.php%3Fid%3D1000336629%26show%3Dpic1' type='text/javascript'%3E%3C/script%3E"));</script>
<script type="text/javascript">var cnzz_protocol = (("https:" == document.location.protocol) ? " https://" : " http://");document.write(unescape("%3Cspan id='cnzz_stat_icon_1000336629'%3E%3C/span%3E%3Cscript src='" + cnzz_protocol + "s5.cnzz.com/z_stat.php%3Fid%3D1000336629%26show%3Dpic' type='text/javascript'%3E%3C/script%3E"));</script>
</html>