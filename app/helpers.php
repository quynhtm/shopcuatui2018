<?php
/**
 * Created by PhpStorm.
 * User: QuynhTM
 * Date: 10/17/2016
 * Time: 2:06 PM
 */

use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use App\library\AdminFunction\Define;
use Illuminate\Support\Facades\Redirect;

/*
 * Encrypt given string using AES encryption standard
 */
function getEncrypt($secret)
{
    if (!strlen(trim($secret))) return $secret;

    $key = substr(hash('sha256', env('CYPHER_KEY', '$;cnqvM]A2}.zB:$gX#,Lt*Q@<+]v9F')), 0, 32);
    $iv = substr(hash('sha256', env('CYPHER_IV', '7Tj?k&Xyn')), 0, 16);
    $method = env('CYPHER_METHOD', "AES-256-CFB");
    $blocksize = env('CYPHER_BLOCK_SIZE', 32);
    $padwith = env('CYPHER_PAD_WITH', '`');

    try {
        $padded_secret = $secret . str_repeat($padwith, ($blocksize - strlen($secret) % $blocksize));
        $encrypted_string = openssl_encrypt($padded_secret, $method, $key, OPENSSL_RAW_DATA, $iv);
        $encrypted_secret = base64_encode($encrypted_string);

        return $encrypted_secret;
    } catch (Exception $e) {
        throw $e;
    }
}

/*
 * Decrypt given string using AES standard
 */
function getDecrypt($secret)
{
    if (!strlen(trim($secret))) return $secret;

    $key = substr(hash('sha256', env('CYPHER_KEY', '$;cnqvM]A2}.zB:$gX#,Lt*Q@<+]v9F')), 0, 32);
    $iv = substr(hash('sha256', env('CYPHER_IV', '7Tj?k&Xyn')), 0, 16);
    $method = env('CYPHER_METHOD', "AES-256-CFB");
    $padwith = env('CYPHER_PAD_WITH', '`');

    try {
        $decoded_secret = base64_decode($secret);
        $decrypted_secret = openssl_decrypt($decoded_secret, $method, $key, OPENSSL_RAW_DATA, $iv);
        return rtrim($decrypted_secret, $padwith);
    } catch (Exception $e) {
        throw $e;
    }
}

function vmDebug($data, $is_die = true)
{
    echo '<pre>';
    array_map(function ($data) {
        print_r($data);
    }, func_get_args());
    echo '</pre>';

    if ($is_die) {
        die('This is data current');
    }
}

function limit_text_word($text, $limit)
{
    if (str_word_count($text, 0) > $limit) {
        $words = str_word_count($text, 2);
        $pos = array_keys($words);
        $text = substr($text, 0, $pos[$limit]) . '...';
    }
    return $text;
}

/**
 * build html select option
 *
 * @param array $options_array
 * @param int $selected
 * @param array $disabled
 */
function getOption($options_array, $selected, $disabled = array())
{
    $input = '';
    if ($options_array)
        foreach ($options_array as $key => $text) {
            $input .= '<option value="' . $key . '"';
            if (!in_array($selected, $disabled)) {
                if ($key === '' && $selected === '') {
                    $input .= ' selected';
                } else
                    if ($selected !== '' && $key == $selected) {
                        $input .= ' selected';
                    }
            }
            if (!empty($disabled)) {
                if (in_array($key, $disabled)) {
                    $input .= ' disabled';
                }
            }
            $input .= '>' . $text . '</option>';
        }
    return $input;
}

/**
 * build html select option mutil
 *
 * @param array $options_array
 * @param array $arrSelected
 */
function getOptionMultil($options_array, $arrSelected)
{
    $input = '';
    if ($options_array)
        foreach ($options_array as $key => $text) {
            $input .= '<option value="' . $key . '"';
            if ($key === '' && empty($arrSelected)) {
                $input .= ' selected';
            } else
                if (!empty($arrSelected) && in_array($key, $arrSelected)) {
                    $input .= ' selected';
                }
            $input .= '>' . $text . '</option>';
        }
    return $input;
}

function sortArrayASC(&$array, $key)
{
    $sorter = array();
    $ret = array();
    reset($array);
    foreach ($array as $ii => $va) {
        $sorter[$ii] = $va[$key];
    }
    asort($sorter);
    foreach ($sorter as $ii => $va) {
        $ret[$ii] = $array[$ii];
    }
    $array = $ret;
}

function safe_title($text, $kytu = '-')
{
    if (trim($text) == '') return '';

    $text = post_db_parse_html($text);
    $text = stripUnicode($text);
    $text = _name_cleaner($text, $kytu);
    $text = str_replace("----", $kytu, $text);
    $text = str_replace("---", $kytu, $text);
    $text = str_replace("--", $kytu, $text);
    $text = trim($text, $kytu);

    return ($text) ? $text : "vaymuon_code";

}

//cackysapxepgannhau
function stringtitle($text)
{
    $text = post_db_parse_html($text);
    $text = stripUnicode($text);
    $text = _name_cleaner($text, "-");
    $text = str_replace("----", "-", $text);
    $text = str_replace("---", "-", $text);
    $text = str_replace("--", "-", $text);
    $text = str_replace("-", "", $text);
    $text = trim($text);

    if ($text) {
        return $text;
    } else {
        return "shop";
    }
}

function post_db_parse_html($t = "")
{
    if ($t == "") {
        return $t;
    }

    $t = str_replace("&#39;", "'", $t);
    $t = str_replace("&#33;", "!", $t);
    $t = str_replace("&#036;", "$", $t);
    $t = str_replace("&#124;", "|", $t);
    $t = str_replace("&amp;", "&", $t);
    $t = str_replace("&gt;", ">", $t);
    $t = str_replace("&lt;", "<", $t);
    $t = str_replace("&quot;", '"', $t);

    $t = preg_replace("/javascript/i", "j&#097;v&#097;script", $t);
    $t = preg_replace("/alert/i", "&#097;lert", $t);
    $t = preg_replace("/about:/i", "&#097;bout:", $t);
    $t = preg_replace("/onmouseover/i", "&#111;nmouseover", $t);
    $t = preg_replace("/onmouseout/i", "&#111;nmouseout", $t);
    $t = preg_replace("/onclick/i", "&#111;nclick", $t);
    $t = preg_replace("/onload/i", "&#111;nload", $t);
    $t = preg_replace("/onsubmit/i", "&#111;nsubmit", $t);
    $t = preg_replace("/applet/i", "&#097;pplet", $t);
    $t = preg_replace("/meta/i", "met&#097;", $t);

    return $t;
}

function stripUnicode($str)
{
    if (!$str)
        return false;
    $marTViet = array("à", "á", "ạ", "ả", "ã", "â", "ầ", "ấ", "ậ", "ẩ", "ẫ", "ă",
        "ằ", "ắ", "ặ", "ẳ", "ẵ", "è", "é", "ẹ", "ẻ", "ẽ", "ê", "ề"
    , "ế", "ệ", "ể", "ễ",
        "ì", "í", "ị", "ỉ", "ĩ",
        "ò", "ó", "ọ", "ỏ", "õ", "ô", "ồ", "ố", "ộ", "ổ", "ỗ", "ơ"
    , "ờ", "ớ", "ợ", "ở", "ỡ",
        "ù", "ú", "ụ", "ủ", "ũ", "ư", "ừ", "ứ", "ự", "ử", "ữ",
        "ỳ", "ý", "ỵ", "ỷ", "ỹ",
        "đ",
        "À", "Á", "Ạ", "Ả", "Ã", "Â", "Ầ", "Ấ", "Ậ", "Ẩ", "Ẫ", "Ă"
    , "Ằ", "Ắ", "Ặ", "Ẳ", "Ẵ",
        "È", "É", "Ẹ", "Ẻ", "Ẽ", "Ê", "Ề", "Ế", "Ệ", "Ể", "Ễ",
        "Ì", "Í", "Ị", "Ỉ", "Ĩ",
        "Ò", "Ó", "Ọ", "Ỏ", "Õ", "Ô", "Ồ", "Ố", "Ộ", "Ổ", "Ỗ", "Ơ"
    , "Ờ", "Ớ", "Ợ", "Ở", "Ỡ",
        "Ù", "Ú", "Ụ", "Ủ", "Ũ", "Ư", "Ừ", "Ứ", "Ự", "Ử", "Ữ",
        "Ỳ", "Ý", "Ỵ", "Ỷ", "Ỹ",
        "Đ");

    $marKoDau = array("a", "a", "a", "a", "a", "a", "a", "a", "a", "a", "a"
    , "a", "a", "a", "a", "a", "a",
        "e", "e", "e", "e", "e", "e", "e", "e", "e", "e", "e",
        "i", "i", "i", "i", "i",
        "o", "o", "o", "o", "o", "o", "o", "o", "o", "o", "o", "o"
    , "o", "o", "o", "o", "o",
        "u", "u", "u", "u", "u", "u", "u", "u", "u", "u", "u",
        "y", "y", "y", "y", "y",
        "d",
        "A", "A", "A", "A", "A", "A", "A", "A", "A", "A", "A", "A"
    , "A", "A", "A", "A", "A",
        "E", "E", "E", "E", "E", "E", "E", "E", "E", "E", "E",
        "I", "I", "I", "I", "I",
        "O", "O", "O", "O", "O", "O", "O", "O", "O", "O", "O", "O"
    , "O", "O", "O", "O", "O",
        "U", "U", "U", "U", "U", "U", "U", "U", "U", "U", "U",
        "Y", "Y", "Y", "Y", "Y",
        "D");

    $str = str_replace($marTViet, $marKoDau, $str);
    return $str;
}

function _name_cleaner($name, $replace_string = "_")
{
    return preg_replace("/[^a-zA-Z0-9\-\_]/", $replace_string, $name);
}

/**
 * convert from str to array
 *
 * @param string $str_item
 */
function standardizeCartStr($str_item)
{
    if (empty($str_item))
        return 0;
    $str_item = trim(preg_replace('#([\s]+)|(,+)#', ',', trim($str_item)));
    $data = explode(',', $str_item);
    $arrItem = array();
    foreach ($data as $item) {
        if ($item != '')
            $arrItem[] = $item;
    }
    if (empty($arrItem))
        return 0;
    else
        return $arrItem;
}

function numberFormat($number = 0, $decimal = ".", $thousand_point = ",", $per = 0)
{
    $number = (float)$number;
    return number_format($number, $per, $decimal, $thousand_point);
}

function checkRegexEmail($str = '')
{
    if ($str != '') {
        $regex = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/';
        if (!preg_match($regex, $str)) {
            return false;
        }
        return true;
    }
    return false;
}

function substring($str, $length = 100, $replacer = '...')
{
    $str = strip_tags($str);
    if (strlen($str) <= $length) {
        return $str;
    }
    $str = trim(@substr($str, 0, $length));
    $posSpace = strrpos($str, ' ');
    $replacer = "...";
    return substr($str, 0, $posSpace) . $replacer;
}

function viewLanguage($key)
{
    $lang = Session::get('languageSite');
    $lang = ((int)$lang > 0) ? $lang : VIETNAM_LANGUAGE;
    $path = storage_path() . "/language/" . Define::$arrLanguage[$lang] . ".json";
    $json = file_get_contents($path);
    $json = mb_convert_encoding($json, 'UTF8', 'auto');
    $language = json_decode($json, true);
    return isset($language[$key]) ? $language[$key] : $key;
}

function khoangcachngay($p_strngay1, $p_strngay2, $p_strkieu = 'ngay')
{
    $m_arrngay1 = explode('/', $p_strngay1);
    $m_arrngay2 = explode('/', $p_strngay2);
    $m_intngay1 = mktime(0, 0, 0, $m_arrngay1[1], $m_arrngay1[0], $m_arrngay1[2]);
    $m_intngay2 = mktime(0, 0, 0, $m_arrngay2[1], $m_arrngay2[0], $m_arrngay2[2]);

    $m_int = abs($m_intngay1 - $m_intngay2);
    switch ($p_strkieu) {
        case 'ngay':
            $m_int /= 86400;
            break;
        case 'gio' :
            $m_int /= 3600;
            break;
        case 'phut':
            $m_int /= 60;
            break;
        default :
            break;
    }
    return $m_int;
}

function khoangcachngay2($p_strngay1, $p_strngay2)
{
    $end = Carbon::parse($p_strngay1);
    $now = $p_strngay2;
    $length = $end->diffInDays($now);
    return $length;
}

/**
 * QuynhTM add
 * @param $id
 * @return string
 */
function setStrVar($string)
{
    return base64_encode(randomString() . '_' . $string . '_' . randomString());
}

function getStrVar($string)
{
    $stringOut = 0;
    if (trim($string) != '') {
        $strId = base64_decode($string);
        $result = explode('_', $strId);
        if (!empty($result)) {
            $stringOut = isset($result[1]) ? (int)$result[1] : 0;
        }
    }
    return $stringOut;
}

function randomString($length = 5)
{
    $str = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $strLength = strlen($str);
    $random_string = '';
    for ($i = 0; $i <= $length; $i++) {
        $random_string .= $str[rand(0, $strLength - 1)];
    }
    return $random_string;
}

function createSequence($prefix = '', $id = 0)
{
    $str = '';
    if ($id > 0 && $prefix != '') {
        if ($id < 10) {
            $str = $prefix . '00' . $id;
        } elseif ($id >= 10 && $id < 100) {
            $str = $prefix . '0' . $id;
        } else {
            $str = $prefix . '' . $id;
        }
    }
    return $str;
}

//hàm đổi tiếng việt có dấu thành không dấu
function convert_vi_to_en($str)
{
    $str = preg_replace("/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/", 'a', $str);
    $str = preg_replace("/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/", 'e', $str);
    $str = preg_replace("/(ì|í|ị|ỉ|ĩ)/", 'i', $str);
    $str = preg_replace("/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/", 'o', $str);
    $str = preg_replace("/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/", 'u', $str);
    $str = preg_replace("/(ỳ|ý|ỵ|ỷ|ỹ)/", 'y', $str);
    $str = preg_replace("/(đ)/", 'd', $str);
    $str = preg_replace("/(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)/", 'A', $str);
    $str = preg_replace("/(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)/", 'E', $str);
    $str = preg_replace("/(Ì|Í|Ị|Ỉ|Ĩ)/", 'I', $str);
    $str = preg_replace("/(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)/", 'O', $str);
    $str = preg_replace("/(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)/", 'U', $str);
    $str = preg_replace("/(Ỳ|Ý|Ỵ|Ỷ|Ỹ)/", 'Y', $str);
    $str = preg_replace("/(Đ)/", 'D', $str);

//    $str = ucwords($str);
//      $str = str_replace(" ", $replace_with, str_replace("&*#39;","",$str));
    $str = str_replace(" ", '_', strtolower($str));
    return $str;
}

function getSubDate($date, $number)
{
    date_default_timezone_set('Asia/Bangkok');
    $date = date_create($date);
    date_sub($date, date_interval_create_from_date_string($number . ' days'));
    return date_format($date, 'Y-m-d');
}

function getAddDate2($date, $number)
{
    date_default_timezone_set('Asia/Bangkok');
    $date = date_create($date);
    date_add($date, date_interval_create_from_date_string($number . ' days'));
    return date_format($date, 'Y-m-d H:i:s');
}

function getCurrentDate()
{
    date_default_timezone_set('Asia/Bangkok');
    return date("Y-m-d");
}

function getParamDate($type = '')
{
    date_default_timezone_set('Asia/Bangkok');
    $result = '';
    switch ($type) {
        case 'd':
            $result = date("d");
            break;
        case 'm':
            $result = date("m");
            break;
        case 'Y':
            $result = date("Y");
            break;
        default:
            $result = date("Y-m-d");
            break;
    }
    return $result;
}

function getIntDateYMD()
{
    date_default_timezone_set('Asia/Bangkok');
    return (int)date("Ymd");
}

function calculate($rate, $duration, $type_duration, $amount)
{
    if ($type_duration == NGAY) {
        return round($amount * $rate / 100 / TOTAL_DAY * $duration);
    }
    return round($amount * $rate / 100 / TOTAL_DAY * $duration);
}

function calculateFree($rate, $duration, $type_duration, $amount, $promotion = 0)
{
    if ($type_duration == 'ngay' || $type_duration == '') {
        return round($amount * $rate / 100 / TOTAL_DAY * $duration) - $promotion;
    }
    return 0;
}

function convertDate($date)
{
    $step1 = getSubDate($date, 0) . " 00:00:00";
    $step2 = new \DateTime($step1);
    return $step2;
}

function getAddDate($date, $number)
{
    date_default_timezone_set('Asia/Bangkok');
    $date = date_create($date);
    date_add($date, date_interval_create_from_date_string($number . ' days'));
    return date_format($date, 'Y-m-d');
}

function calculateNTN($rate)
{
    return round(1000000 * $rate / 100 / 360, 2);
}

function calculateFeeRateNTN($fee_rate)
{
    return calculateNTN($fee_rate);
}

function calculateEnsureRateNTN($ensure_rate)
{
    return calculateNTN($ensure_rate);
}

function calculateTotalSaleInterestRate($amount, $duration, $interest_rate, $sale_interest_rate, $type_duration)
{
    return calculate($interest_rate * $sale_interest_rate / 100, $duration, $type_duration, $amount);
}

function getCurrentDateTime()
{
    date_default_timezone_set('Asia/Bangkok');
    return date("Y-m-d H:i:s");
}

function getAddDate3($date, $number)
{
    date_default_timezone_set('Asia/Bangkok');
    $date = date_create($date);
    date_add($date, date_interval_create_from_date_string($number . ' days'));
    return date_format($date, 'd-m-Y');
}

function totalNeedToPay($loan)
{
    return $loan->approve_amount + totalRateAmount($loan) - $loan->promotion_value - $loan->reference_value - $loan->charge_value;
}

function totalRateAmount($loan)
{
    return calculate((float)$loan->fee_rate + (float)$loan->interest_rate + (float)$loan->ensure_rate - (float)$loan->sale_interest_rate * (float)$loan->interest_rate / 100, (float)$loan->approve_duration, (float)$loan->type_duration, (float)$loan->approve_amount);
}

function debugLog($data, $nameFile = 'debug_log_1.log', $name_folder = '')
{
    $folder_logs = (trim($name_folder) != '') ? storage_path() . '/logs/' . $name_folder : storage_path() . '/logs';
    if (!is_dir($folder_logs)) {
        @mkdir($folder_logs, 0755, true);
        @chmod($folder_logs, 0755);
    }
    $csv_filename = $folder_logs . '/' . $nameFile;
    if (!is_file($csv_filename)) {
        $fp = fopen($csv_filename, 'a');
        if ($fp) {
            fclose($fp);
        }
    }
    file_put_contents($csv_filename, print_r(getCurrentDateTime(), true) . "\n", FILE_APPEND);
    file_put_contents($csv_filename, print_r($data, true). "\n", FILE_APPEND);
}

function endLog($nameFile = 'debug_log_1.log', $name_folder = '')
{
    $folder_logs = (trim($name_folder) != '') ? storage_path() . '/logs/' . $name_folder : storage_path() . '/logs';
    if (!is_dir($folder_logs)) {
        @mkdir($folder_logs, 0755, true);
        @chmod($folder_logs, 0755);
    }
    $csv_filename = $folder_logs . '/' . $nameFile;
    if (!is_file($csv_filename)) {
        $fp = fopen($csv_filename, 'a');
        if ($fp) {
            fclose($fp);
        }
    }
    file_put_contents($csv_filename, "\n=================================================================================End " . getCurrentDateTime() . " =================================================================\n\n", FILE_APPEND);
}

function calculateTotalRate($loan)
{
    return calculate($loan->fee_rate + $loan->interest_rate + $loan->ensure_rate - $loan->interest_rate * $loan->sale_interest_rate / 100, $loan->duration, $loan->type_duration, $loan->amount);
}

function calculateTotalRateLoanApprove($loan)
{
    return (($loan->fee_rate + $loan->interest_rate + $loan->ensure_rate) * $loan->approve_amount * $loan->approve_duration) / (12 * 30 * 100);
}

function calculateTotalRateProduct($product, $loan)
{
    return (($product->fee_rate + $product->interest_rate + $product->ensure_rate) * $loan->amount * $loan->duration) / (12 * 30 * 100);
}

function checkFileExt($str = '')
{
    $ext = '';
    if ($str != '') {
        $ext = @end(explode('.', $str));
    }
    return $ext;
}

function cutPhoneNumber($phone = '')
{
    if ($phone != '') {
        $phone = trim($phone);
        $phone = str_replace(array('^', '$', '\\', '/', '(', ')', '|', '?', '_', '-', '+', '.', ' ', '*', '[', ']', '{', '}', ',', '%', '<', '>', '=', '"', '“', '”', '!', ':', ';', '&', '~', '#', '`', "'", '@'), array(''), $phone);
        if (strlen(trim($phone)) > 10) {
            $phone = str_replace('84', '', $phone);
        }
        if (strlen($phone) == 10) {
            $phone = ltrim($phone, '0');
        }
        if (strlen($phone) == 11) {
            $phone = ltrim($phone, '0');
        }
    }
    return $phone;
}

/**
 * QuynhTM
 * Chuyển đổi chuối thành số, chuyển đổi string tiền thành số
 * @param $subject
 * @param string $search
 * @param string $replace
 * @return int
 */
function convertNumberFromString($subject, $search = ',', $replace = '')
{
    $number = 0;
    if (trim($subject) != '') {
        $number = (int)str_replace($search, $replace, $subject);;
    }
    return $number;
}

function getConvertDateTime($datetime)
{
    return date('Y-m-d', strtotime($datetime));
}

function getCurrentDateDMY()
{
    date_default_timezone_set('Asia/Bangkok');
    return date("d-m-Y");
}

function getCurrentFull()
{
    date_default_timezone_set('Asia/Bangkok');
    return date("Y-m-d H:i:s", time());
}

function getDate3($date)
{
    date_default_timezone_set('Asia/Bangkok');
    $date = date_create($date);
    return date_format($date, "d-m-Y");
}

function calculateTotalFeeRate($amount, $duration, $fee_rate, $type_duration)
{
    return calculate($fee_rate, $duration, $type_duration, $amount);
}

function calculateTotalEnsureRate($amount, $duration, $ensure_rate, $type_duration)
{
    return calculate($ensure_rate, $duration, $type_duration, $amount);
}

function calculateTotalInterestRate($amount, $duration, $interest_rate, $type_duration)
{
    return calculate($interest_rate, $duration, $type_duration, $amount);
}

function calculateFeeRate($fee_rate = 0)
{
    return $fee_rate / (12 * 30);
}

function calculateFeeRateDate($fee_rate = 0, $approve_amount = 0, $approve_duration = 0)
{
    return ($fee_rate * $approve_amount * $approve_duration) / (12 * 30 * 100);
}

function calculateEnsureRateDate($ensure_rate = 0)
{
    return $ensure_rate / (12 * 30);
}

function calculateEnsureRate($ensure_rate = 0, $approve_amount = 0, $approve_duration = 0)
{
    return ($ensure_rate * $approve_amount * $approve_duration) / (12 * 30 * 100);
}

function calculateDiscount($sale_interest_rate = 0, $approve_duration = 0, $approve_amount = 0, $interest_rate = 0)
{
    return ($sale_interest_rate * $approve_duration * $approve_amount * $interest_rate) / (12 * 30 * 100 * 100);
}

function calculateInterestRate($interest_rate = 0, $approve_amount = 0, $approve_duration = 0)
{
    return ($interest_rate * $approve_amount * $approve_duration) / (12 * 30 * 100);
}

function calculateContractInterestRate($interest_rate = 0)
{
    return $interest_rate / (12 * 30);
}

function calculateFeeRateAndEnsureRate($fee_rate = 0, $ensure_rate = 0)
{
    return ($fee_rate + $ensure_rate) * 1000000 / (12 * 30 * 100);
}

function calculateTotalPhi($dataSum = 0)
{
    return $dataSum / (12 * 30 * 100);
}

function discountMoneyLoan($sale_interest_rate = 0, $interest_rate = 0, $approve_amount = 0, $approve_duration = 0)
{
    return ($sale_interest_rate * $interest_rate * $approve_amount * $approve_duration) / (12 * 30 * 100 * 100);
}

function showMessage($status = 'status', $mess = '')
{
    if ($status != '') {
        if (is_array($mess) && !empty($mess)) {
            $mess = implode('<br>', $mess);
        }
        if ($mess != '') {
            Session::flash($status, $mess);
        }
    }
}

//Get https or http
function getBaseUrl()
{
    return env('APP_URL','https://beta4.vaymuon.vn/');//QuynhTM đóng lại để dùng https

    /*if (isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1) || isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') {
        $protocol = 'https://';
    } else {
        $protocol = 'http://';
    }
    $base_url = str_replace('\\', '/', $protocol . $_SERVER['HTTP_HOST'] . (dirname($_SERVER['SCRIPT_NAME']) ? dirname($_SERVER['SCRIPT_NAME']) : ''));
    $base_url .= $base_url[strlen($base_url) - 1] != '/' ? '/' : '';
    return $base_url;*/

}

//Get root path
function getRootPath(){
    $dir_root = str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT'] . (dirname($_SERVER['SCRIPT_NAME']) ? dirname($_SERVER['SCRIPT_NAME']) : ''));
    $dir_root .= $dir_root[strlen($dir_root) - 1] != '/' ? '/' : '';
    return $dir_root;
}

function getBrowser(){
    $u_agent = $_SERVER['HTTP_USER_AGENT'];
    $bname = 'Unknown';
    $platform = 'Unknown';
    $version = "";
    if (preg_match('/linux/i', $u_agent)) {
        $platform = 'linux';
    } elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
        $platform = 'mac';
    } elseif (preg_match('/windows|win32/i', $u_agent)) {
        $platform = 'windows';
    }

    if (preg_match('/MSIE/i', $u_agent) && !preg_match('/Opera/i', $u_agent)) {
        $bname = 'Internet Explorer';
        $ub = "MSIE";
    } elseif (preg_match('/Firefox/i', $u_agent)) {
        $bname = 'Mozilla Firefox';
        $ub = "Firefox";
    } elseif (preg_match('/Chrome/i', $u_agent)) {
        $bname = 'Google Chrome';
        $ub = "Chrome";
    } elseif (preg_match('/Safari/i', $u_agent)) {
        $bname = 'Apple Safari';
        $ub = "Safari";
    } elseif (preg_match('/Opera/i', $u_agent)) {
        $bname = 'Opera';
        $ub = "Opera";
    } elseif (preg_match('/Netscape/i', $u_agent)) {
        $bname = 'Netscape';
        $ub = "Netscape";
    }

    $known = array('Version', $ub, 'other');
    $pattern = '#(?<browser>' . join('|', $known) . ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
    if (!preg_match_all($pattern, $u_agent, $matches)) {

    }
    $i = count($matches['browser']);
    if ($i != 1) {
        if (strripos($u_agent, "Version") < strripos($u_agent, $ub)) {
            $version = $matches['version'][0];
        } else {
            $version = $matches['version'][1];
        }
    } else {
        $version = $matches['version'][0];
    }
    if ($version == null || $version == "") {
        $version = "?";
    }
    return array(
        'userAgent' => $u_agent,
        'name' => $bname,
        'version' => $version,
        'platform' => $platform,
        'pattern' => $pattern
    );
}

/**
 * QuynhTM add
 * @param $table
 * @param $arrInput
 * @return string
 */
function buildSqlInsertMultiple($table, $arrInput)
{
    if (!empty($arrInput)) {
        $arrSql = array();
        $arrField = isset($arrInput[0]) ? array_keys($arrInput[0]) : [];
        if (empty($arrField))
            return '';

        foreach ($arrInput as $k => $row) {
            $strVals = '';
            foreach ($row as $field => $valu) {
                $strVals .= "'" . trim($valu) . '\',';
            }
            if ($strVals != '')
                $strVals = rtrim($strVals, ',');
            if ($strVals != '')
                $arrSql[] = '(' . $strVals . ')';
        }

        $fields = implode(',', $arrField);
        if (!empty($arrSql)) {
            $query = 'INSERT INTO `' . $table . '` (' . $fields . ') VALUES ' . implode(',', $arrSql);
            return $query;
        }
    }
    return '';
}

/**
 * @param $data
 * @param string $name_key
 * @return array
 */
function getArrayByKeyToObject($data,$name_key = 'loaner_id'){
    $result = array();
    if(is_object($data) && $data->count() > 0){
        foreach($data as $item){
            $result[$item->$name_key] = $item->$name_key;
        }
    }
    return $result;
}

/**
 * Xem các trạng thái theo user_postion
 * @param $user_postion
 * @param $status_default
 * @return misc status
 */
function getLoanStatusViewList($checkPermisRoot, $user_postion = -1, $status_default = ''){
    if($checkPermisRoot != STATUS_SHOW){
        if($status_default != ''){
            if($user_postion == USER_POSITION_VH1){
                return [STATUS_STRING_CHO_DUYET_CAP_1];
            }
            if($user_postion == USER_POSITION_VH2){
                return [STATUS_STRING_CHO_KHE_UOC, STATUS_STRING_DA_DUYET, STATUS_HOAN_THANH];
            }
            return $status_default;
        }
        if($user_postion == USER_POSITION_CSKH){
            return [STATUS_STRING_MOI, STATUS_STRING_DANG_CAP_NHAT];
        }
        if($user_postion == USER_POSITION_VH1){
            return [STATUS_STRING_CHO_DUYET_CAP_1];
        }
        if($user_postion == USER_POSITION_VH2){
            return [STATUS_STRING_CHO_KHE_UOC, STATUS_STRING_DA_DUYET, STATUS_HOAN_THANH];
        }
    }else{
        if($status_default != ''){
            return $status_default;
        }
    }
}
