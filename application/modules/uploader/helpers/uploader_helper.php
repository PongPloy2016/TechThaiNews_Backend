<?php
/**
 * Created by PhpStorm.
 * User: amine
 * Date: 12/28/18
 * Time: 22:28
 */




class UploaderHelper
{

    private $files;
    private $ext;
    private $size;
    private $errors;
    private $namedir;
    private $download;

    private $isExternalStorageEnabled = true;


    public function __construct($data = NULL, $type = '')
    {

        if(ConfigManager::getValue('APP_STORAGE')=='local'){
            $this->isExternalStorageEnabled = false;
        }else{
            $this->isExternalStorageEnabled = true;
        }

        $this->files = $data;
        $this->ext = array("image/jpg", "image/jpeg", "image/png", "image/gif");
        $this->size = (1048576 * MAX_IMAGE_UPLOAD);
        $this->errors = array();

        if(is_array($this->files)){
            $filename = explode(".", $this->files['name']);

            $this->namedir = $this->parse_slug($filename[0]) .'-'. rand(000, 99999);
        }else{
            $this->namedir = time() . rand(000, 99999);
        }

        $this->download = FALSE;
        $this->createDir();

    }


    private function parse_slug($str, $delimiter = '-'){
        $slug = Text::removeSymbols($str);
        $slug = preg_replace("`[^\w]+`", "-", $slug);
        $slug = str_replace(" ", "-", $slug);
        return trim($slug, '-');
    }



    public function start64()
    {

        $ext = "jpeg";
        $filePath = Path::getPath(array("uploads", "images", $this->namedir, "full.jpeg"));

        file_put_contents($filePath, base64_decode($this->files));

        if (!file_exists($filePath)) {
            return array();
        }

        $result =  $this->uploadToLocalStorage($filePath);

        if($this->isExternalStorageEnabled){
            $result = $this->uploadToExternalStorage($this->namedir);
        }

        return $result;
    }


    public function start()
    {


        if ($this->files["error"] == UPLOAD_ERR_NO_TMP_DIR) {
            $this->errors['dir'] = "Error (no tmp dir) UPLOAD_ERR_NO_TMP_DIR:6";
        }

        if (isset($this->files['type']) AND !in_array(strtolower($this->files['type']), $this->ext)) {
            $this->errors['type'] = "The type's image isn't valid! " . $this->files['type'];
        }

        if (isset($this->files['size']) AND $this->files['size'] > $this->size) {
            $this->errors['size'] = "Error in size, the max size :" . MAX_IMAGE_UPLOAD." MB";
        }


        if (empty($this->errors)) {

            $ar = explode(".", $this->files['name']);
            $ext = end($ar);
            $filePath = Path::getPath(array("uploads", "images", $this->namedir, "full." . strtolower($ext)));

            if (!move_uploaded_file($this->files['tmp_name'], $filePath)) {
                return array();
            }

            if (!file_exists($filePath)) {
                return array();
            }


            $result =  $this->uploadToLocalStorage($filePath);

            if($this->isExternalStorageEnabled){
                $result = $this->uploadToExternalStorage($this->namedir);
            }

            return $result;

        }

        return array();
    }

    private function uploadToExternalStorage($fileName){

        Modules::run('uploader/ExternalStorage/upload',$fileName);

        //remove image from local storage
        _removeDir($fileName);

        return $this->result(_getAllSizes($fileName));

    }


    private function uploadToLocalStorage($file){

        $ar = explode(".", $this->files['name']);

        $image = new SimpleImage();
        $image->load($file);
        $image->resizeToWidth(560);

        $newpath = Path::getPath(array("uploads", "images",$this->namedir, "560_560." . $image->getExt()));
        $image->save($newpath);

        $image->resizeToWidth(300);
        $newpath = Path::getPath(array("uploads", "images", $this->namedir, "300_300." . $image->getExt()));
        $image->save($newpath);

        $image->resizeToWidth(200);
        $newpath = Path::getPath(array("uploads", "images", $this->namedir, "200_200." . $image->getExt()));
        $image->save($newpath);

        $image->resizeToWidth(100);
        $newpath = Path::getPath(array("uploads", "images", $this->namedir, "100_100." . $image->getExt()));
        $image->save($newpath);
        $image->destroy();

        $imageData = _getAllSizes($this->namedir);

        return $this->result($imageData);
    }

    private function result($file){


        if (!empty($file) && isset($file['200_200'])) {

            $imageData['html'] = ""
                . "<div data-id=\"".$file['name']."\" class=\"image-uploaded cursor-draggable item_" . $file['name'] . "\">
                        <i class='index'></i><a  id=\"image-preview\">    
                           <img src='" . $file['200_200']['url'] . "' alt=''/>
                        </a>
                        
                        <div class=\"clear\"></div>
                        <a href=\"#\"  data=\"".$file['name']."\" id=\"delete\"><i class=\"fa fa-trash\"></i>&nbsp;&nbsp;"._lang("Delete")."</a></div>"
                . "<input id=\"image-data\" type=\"hidden\" value=\"" . md5($file['name']) . "\">";

            $imageData['type'] = "image/" . strtolower($file['200_200']['ext']);
            $imageData['image_data'] = md5($file['name']);
            $imageData['image'] = $file['name'];

            return $imageData;
        }
    }


    public function getErrors()
    {
        return $this->errors;
    }

    private function createDir()
    {
        @mkdir(Path::getPath(array("uploads", "images", $this->namedir)));
        return;
    }


}

class ImageManagerUtils{

    public static function check($images){

        $validatedImages = array();
        $i = 0;

        try {
            if (!empty($images)) {
                foreach ($images as $value) {
                    $validatedImages[$i] = $value;
                    $i++;
                }
                $validatedImages = json_encode($validatedImages, JSON_FORCE_OBJECT);
            }
        } catch (Throwable $e) {

        }

        return $validatedImages;
    }


    public static function parseFirstImages($object, $size = ImageManagerUtils::IMAGE_SIZE_100){
        try {
            if (empty($object)) {
                return adminAssets("images/def_logo.png");
            }

            if (is_object($object)) {
                $object = json_decode(json_encode($object), true);
            }

            if (is_string($object)) {
                $decoded = json_decode($object, true);
                if (json_last_error() === JSON_ERROR_NONE && (is_array($decoded) || is_object($decoded))) {
                    $object = json_decode(json_encode($decoded), true);
                } else {
                    if (strpos($object, 'http://') === 0 || strpos($object, 'https://') === 0) {
                        return $object;
                    }
                    $object = [$object];
                }
            }

            if (!is_array($object)) {
                return adminAssets("images/def_logo.png");
            }

            $images = $object;

            if (isset($images[$size]['url'])) {
                return $images[$size]['url'];
            } elseif (isset($images['url'])) {
                return $images['url'];
            } elseif (isset($images['full']['url'])) {
                return $images['full']['url'];
            }

            foreach ($images as $k => $image) {
                if (is_object($image)) {
                    $image = json_decode(json_encode($image), true);
                    $images[$k] = $image;
                }

                if (is_array($image)) {
                    if (!isset($image[$size])) {
                        if (isset($image['name'])) {
                            $images[$k] = _openDir($image['name']);
                        }
                    }
                } elseif (is_string($image) || is_numeric($image)) {
                    $imgStr = (string)$image;
                    if (strpos($imgStr, 'http://') === 0 || strpos($imgStr, 'https://') === 0) {
                        $images[$k] = [$size => ['url' => $imgStr], 'url' => $imgStr];
                    } else {
                        $images[$k] = _openDir($imgStr);
                    }
                }
            }

            if (isset($images[0])) {
                $first = $images[0];
                if (is_object($first)) {
                    $first = json_decode(json_encode($first), true);
                }

                if (is_array($first)) {
                    if (isset($first[$size]['url'])) {
                        return $first[$size]['url'];
                    } elseif (isset($first['200_200']['url'])) {
                        return $first['200_200']['url'];
                    } elseif (isset($first['full']['url'])) {
                        return $first['full']['url'];
                    } elseif (isset($first['url'])) {
                        return $first['url'];
                    }
                } elseif (is_string($first) && (strpos($first, 'http://') === 0 || strpos($first, 'https://') === 0)) {
                    return $first;
                }
            }

            return adminAssets("images/def_logo.png");

        } catch (Throwable $e) {
            return adminAssets("images/def_logo.png");
        }
    }


    const IMAGE_SIZE_200 = "200_200";
    const IMAGE_SIZE_300 = "300_300";
    const IMAGE_SIZE_560 = "560_560";
    const IMAGE_SIZE_100 = "100_100";
    const IMAGE_FULL_SIZE = "full";

    public static function getImage($dir, $size = self::IMAGE_SIZE_200){
        try {
            $images = _openDir($dir);
            if (is_object($images)) $images = json_decode(json_encode($images), true);
            if (is_array($images) && isset($images[$size]['url'])){
                return $images[$size]['url'];
            }
        } catch (Throwable $e) {}
        return "";
    }

    public static function getFirstImage($images, $size = self::IMAGE_SIZE_200){
        try {
            if (is_object($images)) $images = json_decode(json_encode($images), true);
            if (is_string($images)) {
                $decoded = json_decode($images, true);
                if (json_last_error() === JSON_ERROR_NONE && (is_array($decoded) || is_object($decoded))) {
                    $images = json_decode(json_encode($decoded), true);
                }
            }

            if (is_string($images) && $images != "") {
                if (strpos($images, 'http://') === 0 || strpos($images, 'https://') === 0) return $images;
                $res = _openDir($images);
                if (is_array($res) && isset($res[$size]['url'])) return $res[$size]['url'];
            } else if (isset($images[0])) {
                $first = $images[0];
                if (is_object($first)) $first = json_decode(json_encode($first), true);
                if (is_string($first) || is_numeric($first)) {
                    $strFirst = (string)$first;
                    if (strpos($strFirst, 'http://') === 0 || strpos($strFirst, 'https://') === 0) return $strFirst;
                    $res = _openDir($strFirst);
                    if (is_array($res) && isset($res[$size]['url'])) return $res[$size]['url'];
                } else if (is_array($first)) {
                    if (isset($first[$size]['url'])) return $first[$size]['url'];
                    if (isset($first['url'])) return $first['url'];
                }
            }
        } catch (Throwable $e) {}

        return NULL;
    }


    public static function checkAndClearImages(){

        $context = &get_instance();




    }

    public static function imageHTML($images){
        if (is_object($images)) $images = json_decode(json_encode($images), true);
        if (is_array($images) && isset($images['200_200']['url'])) {
            return '<img src="' . $images['200_200']['url'] . '" width="100%" alt="Product Image">';
        } else {
            return '<img src="' . adminAssets("images/def_logo.png") . '" width="100%" alt="Product Image">';
        }
    }

    public static function getValidImages($userImageStr){
        if (empty($userImageStr)) return array();

        if (is_object($userImageStr)) {
            $userImageStr = json_decode(json_encode($userImageStr), true);
        }

        if (!is_array($userImageStr) && !preg_match('#^([0-9]+)$#', (string)$userImageStr)) {
            $userImage = json_decode((string)$userImageStr, true);
        } else if (!is_array($userImageStr) && preg_match('#^([0-9]+)$#', (string)$userImageStr)) {
            $userImage = array($userImageStr);
        } else if (is_array($userImageStr)) {
            $userImage = $userImageStr;
        }

        $array = array();

        if (isset($userImage) && is_array($userImage)) {
            foreach ($userImage as $dirName) {
                if (is_array($dirName) && (isset($dirName['full']) || isset($dirName['200_200']) || isset($dirName['url']))) {
                    $array[] = $dirName;
                } else {
                    $openImg = _openDir($dirName);
                    if (!empty($openImg))
                        $array[] = $openImg;
                }
            }
        } else {
            $array = is_array($userImageStr) ? $userImageStr : array();
        }

        $new_arrays = array();
        if (is_array($array)) {
            foreach ($array as $key => $img) {
                if (empty($img))
                    unset($array[$key]);
                else
                    $new_arrays[] = $img;
            }
        }

        return $new_arrays;
    }


    public static function unused_images($new,$old){
        $ctx = &get_instance();
        if (!isset($ctx->uploader_model)) {
            $ctx->load->model('uploader/uploader_model', 'uploader_model');
        }
        $ctx->uploader_model->images_to_clear($new,$old);
    }

    public static function validate($image,$manager="unknown"){
        $ctx = &get_instance();
        if (!isset($ctx->uploader_model)) {
            $ctx->load->model('uploader/uploader_model', 'uploader_model');
        }
        $ctx->uploader_model->validate($image,$manager);
    }

    public static function checkRequestValidity($key){

        $ctx = &get_instance();

        $errors = array();


        if(!preg_match('#^([a-zA-Z0-9]+)$#i',  ($key!=null?$key:"")    )){
            $errors['error'] = Translate::sprint('Invalidate Upload Function');
        }else{
            $limit = $ctx->uploader->getUploaderSession($key);
            $limit_saved = $ctx->uploader->getUploaderSession('saved-'.$key);
            if($limit==$limit_saved){
                $errors[] = Translate::sprint("You have exceeded the maximum number of files");
            }
        }

        return $errors;

    }

    public static function increaseRequest($key,$id){

        $ctx = &get_instance();

        $errors = array();

        if(!preg_match('#^([a-zA-Z]+)$#i',  ($key!=null?$key:"")    )){
            $errors['error'] = Translate::sprint('Invalidate Upload Function');
        }else{
            $limit = $ctx->uploader->getUploaderSession($key);
            $limit_saved = $ctx->uploader->getUploaderSession('saved-'.$key);
            if($limit==$limit_saved){
                $errors[] = Translate::sprint("You have exceeded the maximum number of files");
            }
        }

        if(!empty($errors))
            return $errors;

        $limit_saved++;
        $ctx->uploader->setUploaderSession('saved-'.$key,$limit_saved);

        $list = $ctx->uploader->getUploaderSession('loaded-images'.$key);
        $list[] = $id;

        $ctx->uploader->setUploaderSession('loaded-images'.$key,$list);

        return TRUE;
    }

}




interface UploaderInterface{
    public function onClearFolder();
}
