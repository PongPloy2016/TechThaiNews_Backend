<?php


function loadAllImages()
{

    $path = Path::getPath(array("uploads", "images"));

    $data = array();

    if (!is_dir($path))
        return array();

    if ($handle = opendir($path) AND $path != "" AND is_dir($path)) {
        while (false !== ($entry = readdir($handle))) {
            if ($entry != "." && $entry != "..") {

                $data[] = $entry;

            }
        }
        closedir($handle);
    }


    return $data;

}


function _formatImageData($dir_arr)
{
    if (is_object($dir_arr)) {
        $dir_arr = json_decode(json_encode($dir_arr), true);
    }
    if (!is_array($dir_arr)) {
        return array();
    }

    $dir_arr = json_decode(json_encode($dir_arr), true);

    $fallbackUrl = '';
    if (isset($dir_arr['200_200']['url']) && is_string($dir_arr['200_200']['url'])) $fallbackUrl = $dir_arr['200_200']['url'];
    elseif (isset($dir_arr['url']) && is_string($dir_arr['url'])) $fallbackUrl = $dir_arr['url'];
    elseif (isset($dir_arr['full']['url']) && is_string($dir_arr['full']['url'])) $fallbackUrl = $dir_arr['full']['url'];
    elseif (isset($dir_arr['100_100']['url']) && is_string($dir_arr['100_100']['url'])) $fallbackUrl = $dir_arr['100_100']['url'];

    if (empty($fallbackUrl)) {
        return $dir_arr;
    }

    if (empty($dir_arr['name'])) {
        $dir_arr['name'] = md5($fallbackUrl);
    }
    if (!isset($dir_arr['200_200']['url'])) {
        $dir_arr['200_200'] = array('url' => $fallbackUrl);
    }
    if (!isset($dir_arr['100_100']['url'])) {
        $dir_arr['100_100'] = array('url' => $fallbackUrl);
    }
    if (!isset($dir_arr['full']['url'])) {
        $dir_arr['full'] = array('url' => $fallbackUrl);
    }
    if (!isset($dir_arr['url'])) {
        $dir_arr['url'] = $fallbackUrl;
    }

    return $dir_arr;
}

function _openDir($dir = "")
{
    if (is_string($dir) && (strpos($dir, 'http://') === 0 || strpos($dir, 'https://') === 0)) {
        return _formatImageData(array('url' => $dir));
    }

    if (is_object($dir) || is_array($dir)) {
        $dir_arr = json_decode(json_encode($dir), true);
        if (is_array($dir_arr)) {
            if (isset($dir_arr[0]) && is_array($dir_arr[0])) {
                $dir_arr = $dir_arr[0];
            }
            if (isset($dir_arr['full']) || isset($dir_arr['200_200']) || isset($dir_arr['100_100']) || isset($dir_arr['url'])) {
                return _formatImageData($dir_arr);
            }
            foreach ($dir_arr as $d) {
                if (is_string($d) || is_numeric($d)) {
                    $dir = $d;
                    break;
                }
            }
            if (is_array($dir) || is_object($dir)) {
                return array();
            }
        }
    }

    if (!is_string($dir) && !is_numeric($dir)) {
        return array();
    }

    if (strpos((string)$dir, 'http://') === 0 || strpos((string)$dir, 'https://') === 0) {
        return _formatImageData(array('url' => (string)$dir));
    }

    $verified = textVerify($dir);
    if (!is_string($verified) || $verified == "")
        return array();

    if (!preg_match("#^[0-9]+$#", $verified) && $verified != "") {
        $dir_ = json_decode($dir, JSON_OBJECT_AS_ARRAY);
        if (is_array($dir_)) {
            if (isset($dir_[0]) && (is_array($dir_[0]) || is_object($dir_[0]))) {
                $dir_ = (array)$dir_[0];
            }
            if (isset($dir_['full']) || isset($dir_['200_200']) || isset($dir_['100_100']) || isset($dir_['url'])) {
                return _formatImageData($dir_);
            }
            foreach ($dir_ as $d) {
                if (is_string($d) || is_numeric($d)) {
                    $dir = $d;
                }
            }
        }
    }


    $path = Path::getPath(array("uploads", "images", $dir));
    $data = array();

    if (!is_dir($path)){
        $extResult = @Modules::run('uploader/externalStorage/check',$dir);
        if (is_array($extResult) && !empty($extResult)) {
            return _formatImageData($extResult);
        }
        return array();
    }


    if ($handle = opendir($path) AND $path != "" AND is_dir($path)) {
        while (false !== ($entry = readdir($handle))) {
            if ($entry != "." && $entry != "..") {

                $path_2 = Path::addPath($path, array($entry));

                if (is_file($path_2)) {

                    $ar = explode(".", $entry);
                    $index = reset($ar);
                    $ext = end($ar);


                    $data[$index] = array(
                        "name" => $entry,
                        "path" => $path_2,
                        "url" => IMAGES_BASE_URL . $dir . "/$entry",
                        "ext" => $ext
                    );


                }

            }
        }
        closedir($handle);
    }

    if (!empty($data)) {
        $data["name"] = $dir;
    }

    return $data;

}


function _getAllSizes($id = "")
{

    if ($id != "") {
        $userDir = _openDir($id);
        return $userDir;

    }


}

function _removeDir($dir = '',$local=true)
{


    $path = Path::getPath(array("uploads", "images", $dir));
    $data = _openDir($dir);

    if ($handle = opendir($path) AND $path != "" AND is_dir($path)) {
        while (false !== ($entry = readdir($handle))) {
            if ($entry != "." && $entry != "..") {

                $path_2 = Path::addPath($path, array($entry));
                if (is_file($path_2)) {
                    @unlink($path_2);
                }

            }
        }
        closedir($handle);
        rmdir($path);
    }


    return $data;

}


function getAllImgFolder()
{
    $path = Path::getPath(array("uploads", "images"));

    $data = array();

    if ($handle = opendir($path) AND $path != "" AND is_dir($path)) {
        while (false !== ($entry = readdir($handle))) {
            if ($entry != "." && $entry != "..") {

                $data[] = $entry;

            }
        }
        closedir($handle);
    }

    return $data;

}


function hex2RGB($hexStr, $returnAsString = false, $seperator = ',')
{
    $hexStr = preg_replace("/[^0-9A-Fa-f]/", '', $hexStr); // Gets a proper hex string
    $rgbArray = array();
    if (strlen($hexStr) == 6) { //If a proper hex code, convert using bitwise operation. No overhead... faster`enter code here`
        $colorVal = hexdec($hexStr);
        $rgbArray['red'] = 0xFF & ($colorVal >> 0x10);
        $rgbArray['green'] = 0xFF & ($colorVal >> 0x8);
        $rgbArray['blue'] = 0xFF & $colorVal;
    } elseif (strlen($hexStr) == 3) { //if shorthand notation, need some string manipulations
        $rgbArray['red'] = hexdec(str_repeat(substr($hexStr, 0, 1), 2));
        $rgbArray['green'] = hexdec(str_repeat(substr($hexStr, 1, 1), 2));
        $rgbArray['blue'] = hexdec(str_repeat(substr($hexStr, 2, 1), 2));
    } else {
        return false; //Invalid hex color code
    }
    return $returnAsString ? implode($seperator, $rgbArray) : $rgbArray;
    die;
}

function parse_file_name($str, $delimiter = '-'){
    $slug = Text::removeSymbols($str);
    $slug = preg_replace("`[^\w]+`", "-", $slug);
    $slug = str_replace(" ", "-", $slug);
    return trim($slug, '-');
}
