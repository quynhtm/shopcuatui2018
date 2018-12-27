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
    $text = post_db_parse_html($text);
    $text = stripUnicode($text);
    $text = _name_cleaner($text, $kytu);
    $text = str_replace("----", $kytu, $text);
    $text = str_replace("---", $kytu, $text);
    $text = str_replace("--", $kytu, $text);
    $text = trim($text, $kytu);

    if ($text) {
        return $text;
    } else {
        return "shop";
    }
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

function numberFormat($number = 0)
{
    if ($number >= 1000) {
        return number_format($number, 0, ',', '.');
    }
    return $number;
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

function viewLanguage($key) // ngôn ngữ hiển thị
{
    //Session để lưu trữ giá trị cần lưu trữ của người dùng thường được dùng trong các trường hợp đăng nhập -đăng ký-giỏ hàng
    $lang = Session::get('languageSite');    // lưu trữ trang web ngôn ngữ được truyền vào
    $lang = ((int)$lang > 0) ? $lang : VIETNAM_LANGUAGE;
    $path = storage_path() . "/language/" . Define::$arrLanguage[$lang] . ".json"; //lưu trữ định nghĩa của 1 ngon ngữ
    $json = file_get_contents($path);
    $json = mb_convert_encoding($json, 'UTF8', 'auto');  //chuyển đổi mã hóa ngôn ngữ thành utf8
    $language = json_decode($json, true);  //nhận và giải mã chuỗi mã hóa
    return isset($language[$key]) ? $language[$key] : $key;  //nếu tồn tại biến language[$key] thì ( ? ) lấy $language[$key] nếu không( : ) thì lấy $key
//hàm isset là để kiểm tra xem biến đã được khởi tạo trong bộ nhớ chưa
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

