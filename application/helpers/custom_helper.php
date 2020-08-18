<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

//-- check logged user
function cek_session()
{
    $ci = &get_instance();
    $username = $ci->session->userdata('username');
    if ($username == '') {
        $ci->session->sess_destroy();
        redirect(base_url('auth'));
    }

    $set_language = $ci->session->userdata('language');
    if ($set_language) {
        $ci->lang->load('app_lang', $set_language);
    } else {
        $ci->lang->load('app_lang', 'english');
    }

}

function cek_login()
{
    $ci = &get_instance();
    $username = $ci->session->userdata('username');
    if ($username != '') {
        redirect(base_url('dashboard'));
    }
}

if (!function_exists('check_role')) {
    function check_role($i_menu, $id)
    {
        $ci = get_instance();

        $ci->load->model('M_custom');
        $option = $ci->M_custom->cek_role($i_menu, $id);

        return $option;
    }
}

//-- current date time function
if (!function_exists('current_datetime')) {
    function current_datetime()
    {
        $ci = get_instance();
        $query = $ci->db->query("SELECT current_timestamp as c");
        $row = $query->row();
        $waktu = $row->c;
        return $waktu;
    }
}

if (!function_exists('add_js')) {
    function add_js($file = '')
    {
        $str = '';
        $ci = &get_instance();
        $footer_js = $ci->config->item('footer_js');

        if (empty($file)) {
            return;
        }

        if (is_array($file)) {
            if (!is_array($file) && count($file) <= 0) {
                return;
            }
            foreach ($file as $item) {
                $footer_js[] = $item;
            }
            $ci->config->set_item('footer_js', $footer_js);
        } else {
            $str = $file;
            $footer_js[] = $str;
            $ci->config->set_item('footer_js', $footer_js);
        }
    }
}

if (!function_exists('add_css')) {
    function add_css($file = '')
    {
        $str = '';
        $ci = &get_instance();
        $header_css = $ci->config->item('header_css');

        if (empty($file)) {
            return;
        }

        if (is_array($file)) {
            if (!is_array($file) && count($file) <= 0) {
                return;
            }
            foreach ($file as $item) {
                $header_css[] = $item;
            }
            $ci->config->set_item('header_css', $header_css);
        } else {
            $str = $file;
            $header_css[] = $str;
            $ci->config->set_item('header_css', $header_css);
        }
    }
}

if (!function_exists('add_key')) {
    function add_key($file = '')
    {
        $str = '';
        $ci = &get_instance();
        $key = $ci->config->item('key');

        if (empty($file)) {
            return;
        }

        if (is_array($file)) {
            if (!is_array($file) && count($file) <= 0) {
                return;
            }
            foreach ($file as $item) {
                $key[] = $item;
            }
            $ci->config->set_item('key', $key);
        } else {
            $str = $file;
            $key[] = $str;
            $ci->config->set_item('key', $key);
        }
    }
}

if (!function_exists('put_headers')) {
    function put_headers()
    {
        $str = '';
        $ci = &get_instance();
        $header_css = $ci->config->item('header_css');

        foreach ($header_css as $item) {
            $str .= '<link href="' . base_url() . '' . $item . '" type="text/css" />' . "\n";
        }
        return $str;
    }
}
if (!function_exists('put_footer')) {
    function put_footer()
    {
        $str = '';
        $ci = &get_instance();
        $key = $ci->config->item('key');
        $item_key = '<script>';
        foreach ($key as $item) {
            $item_key .= $item;
        }
        $item_key .= '</script>';
        $footer_js = $ci->config->item('footer_js');
        foreach ($footer_js as $item) {
            $str .= '<script src="' . base_url() . '' . $item . '"></script>' . "\n";
        }
        return $item_key . "\n" . $str;
    }
}

function encrypt_url($string)
{
    $output = false;
    $secret_key = 'wahyu';
    $secret_iv = 'adam';
    $encrypt_method = 'aes-256-cbc';
    $key = hash("sha256", $secret_key);
    $iv = substr(hash("sha256", $secret_iv), 0, 16);
    $result = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
    $output = base64_encode($result);
    $output = str_replace('=', '', $output);
    return $output;
}
function decrypt_url($string)
{
    $output = false;
    $secret_key = 'wahyu';
    $secret_iv = 'adam';
    $encrypt_method = 'aes-256-cbc';
    $key = hash("sha256", $secret_key);
    $iv = substr(hash("sha256", $secret_iv), 0, 16);
    $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
    return $output;
}

function replace($str = '', $sp = '')
{
    $replace_string = '';

    if (!empty($str)) {
        $q_separator = preg_quote($sp, '#');

        $trans = array(
            '_' => $sp,
            '&.+?;' => '',
            '[^\w\d -]' => '',
            '\s+' => $sp,
            '(' . $q_separator . ')+' => $sp,
        );

        $str = strip_tags($str);

        foreach ($trans as $key => $val) {
            $str = preg_replace('#' . $key . '#i' . (UTF8_ENABLED ? 'u' : ''), $val, $str);
        }

        $str = strtolower($str);
        $replace_string = trim(trim($str, $sp));
    }

    return $replace_string;
}
