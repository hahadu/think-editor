<?php


namespace Hahadu\ThinkEditor\Client;

use Hahadu\Helper\JsonHelper;
use Hahadu\ThinkEditor\Upload\BaseUploader;

class UEditor extends BaseUploader
{
    private $_config;

    /*****
     * @param string $fileField
     * @param string $fileField
     * @return array|\think\response\Json
     */
    public function upload($fileField){
        parent::upload($fileField);

        return $this->_setDataArr();

    }

    /******
     * @return string|\think\response\Json
     */
    public function ueditor(){

        $action = request()->param('action');
        $this->_config = $this->getConfig()->offsetGet('UEditor');
        file_put_contents('aaaaasss.txt',JsonHelper::json_encode(request()->param()));

        switch ($action) {
            case 'config':
                $result = $this->conf_die();
                break;

            /* 上传图片 */
            case 'uploadimage':
                /* 上传涂鸦 */
            case 'uploadscrawl':
                /* 上传视频 */
            case 'uploadvideo':

                /* 上传文件 */
            case 'uploadfile':
                $result = $this->upload('upfile');

                break;

            /* 列出文件 */
            case 'listfile':
                /* 列出图片 */
            case 'listimage':
                $result = $this->list($action);
                break;

            /* 抓取远程文件 */
            case 'catchimage':
                $result = $this->crawler();
                break;

            default:
                $result = array(
                    'state'=> '请求地址出错'
                );
                break;
        }


        return json($result);
    }

    private function list($action){

        $manager = $this->_config['manager'];
        $user_path = '';
        $listSize = $this->getConfig()->offsetGet('listSize');


        switch ($action) {
            /* 列出文件 */
            case 'listfile':
                $allowFiles = $manager['file']['allowFiles'];
                break;
            /* 列出图片 */
            case 'listimage':
            default:
                $allowFiles = $manager['image']['allowFiles'];
        }
        $path = $this->getFileSystem()->getConfig()->get('url').DIRECTORY_SEPARATOR.$user_path;


        $allowFiles = substr(str_replace(".", "|", join("", $allowFiles)), 1);

        /* 获取参数 */
        $size=request()->param('size');
        $start=request()->param('start');
        $size = isset($size) ? htmlspecialchars($size) : $listSize;
        $start = isset($start) ? htmlspecialchars($start) : 0;
        $end = $start + $size;
        $path = request()->server('DOCUMENT_ROOT') . (substr($path, 0, 1) == "/" ? "":"/") . $path;
        $files = $this->getfiles($path, $allowFiles);


        //dump($files);
        if (!count($files)) {
            return array(
                "state" => "no match file",
                "list" => array(),
                "start" => $start,
                "total" => count($files)
            );
        }

        /* 获取指定范围的列表 */
        $len = count($files);
        for ($i = min($end, $len) - 1, $list = array(); $i < $len && $i >= 0 && $i >= $start; $i--){
            $list[] = $files[$i];
        }

        /* 返回数据 */
        $result = array(
            "state" => "SUCCESS",
            "list" => $list,
            "start" => $start,
            "total" => count($files)
        );

        return $result;

    }
    public function getfiles($path, $allowFiles, &$files = array())
    {
        if (!is_dir($path)) return null;

        if(substr($path, strlen($path) - 1) != '/') $path .= '/';
        $handle = opendir($path);

        while (false !== ($file = readdir($handle))) {
            if ($file != '.' && $file != '..') {
                $path2 = $path . $file;
                if (is_dir($path2)) {
                    $this->getfiles($path2, $allowFiles, $files);
                } else {
                    if (preg_match("/\.(".$allowFiles.")$/i", $file)) {
                        $files[] = array(
                            'url'=> substr($path2, strlen($_SERVER['DOCUMENT_ROOT'])),
                            'mtime'=> filemtime($path2)
                        );
                    }
                }
            }
        }
        return $files;
    }

    private function crawler(){
        $fieldName = 'source';

        /* 抓取远程图片 */
        $list = array();
        if (!empty(request()->post($fieldName))) {
            $source = request()->post($fieldName);
        } else {
            $source = request()->param($fieldName);
        }
        foreach ($source as $imgUrl) {
            $this->remote($fieldName);
            $info = $this->_setDataArr();

            array_push($list, array(
                "state" => $info["state"],
                "url" => $info["url"],
                "size" => $info["size"],
                "title" => htmlspecialchars($info["title"]),
                "original" => htmlspecialchars($info["original"]),
                "source" => htmlspecialchars($imgUrl)
            ));
        }

        /* 返回抓取数据 */
        return array(
            'state'=> count($list) ? 'SUCCESS':'ERROR',
            'list'=> $list
        );
    }





    /****
     * 编辑器需要一个原生的配置项来完成验证，所以这个文件是假的，不要动
     * @return false|string
     */
    private function conf_die(){
        $json = '{"imageActionName":"uploadimage","imageFieldName":"upfile","imageMaxSize":2048000,"imageAllowFiles":[".png",".jpg",".jpeg",".gif",".bmp"],"imageCompressEnable":true,"imageCompressBorder":1600,"imageInsertAlign":"none","imageUrlPrefix":"","imagePathFormat":"/upload/images/{yyyy}{mm}{dd}/{time}{rand:6}","scrawlActionName":"uploadscrawl","scrawlFieldName":"upfile","scrawlPathFormat":"/upload/images/{yyyy}{mm}{dd}/{time}{rand:6}","scrawlMaxSize":2048000,"scrawlUrlPrefix":"","scrawlInsertAlign":"none","snapscreenActionName":"uploadimage","snapscreenPathFormat":"/upload/images/{yyyy}{mm}{dd}/{time}{rand:6}","snapscreenUrlPrefix":"","snapscreenInsertAlign":"none","catcherLocalDomain":["127.0.0.1","localhost","img.baidu.com"],"catcherActionName":"catchimage","catcherFieldName":"source","catcherPathFormat":"/upload/images/{yyyy}{mm}{dd}/{time}{rand:6}","catcherUrlPrefix":"","catcherMaxSize":2048000,"catcherAllowFiles":[".png",".jpg",".jpeg",".gif",".bmp"],"videoActionName":"uploadvideo","videoFieldName":"upfile","videoPathFormat":"/upload/videos/{yyyy}{mm}{dd}/{time}{rand:6}","videoUrlPrefix":"","videoMaxSize":102400000,"videoAllowFiles":[".flv",".swf",".mkv",".avi",".rm",".rmvb",".mpeg",".mpg",".ogg",".ogv",".mov",".wmv",".mp4",".webm",".mp3",".wav",".mid"],"fileActionName":"uploadfile","fileFieldName":"upfile","filePathFormat":"/upload/files/{yyyy}{mm}{dd}/{time}{rand:6}","fileUrlPrefix":"","fileMaxSize":51200000,"fileAllowFiles":[".png",".jpg",".jpeg",".gif",".bmp",".flv",".swf",".mkv",".avi",".rm",".rmvb",".mpeg",".mpg",".ogg",".ogv",".mov",".wmv",".mp4",".webm",".mp3",".wav",".mid",".rar",".zip",".tar",".gz",".7z",".bz2",".cab",".iso",".doc",".docx",".xls",".xlsx",".ppt",".pptx",".pdf",".txt",".md",".xml"],"imageManagerActionName":"listimage","imageManagerListPath":"/upload/images/","imageManagerListSize":20,"imageManagerUrlPrefix":"","imageManagerInsertAlign":"none","imageManagerAllowFiles":[".png",".jpg",".jpeg",".gif",".bmp"],"fileManagerActionName":"listfile","fileManagerListPath":"/upload/files/","fileManagerUrlPrefix":"","fileManagerListSize":20,"fileManagerAllowFiles":[".png",".jpg",".jpeg",".gif",".bmp",".exe",".flv",".swf",".mkv",".avi",".rm",".rmvb",".mpeg",".mpg",".ogg",".ogv",".mov",".wmv",".mp4",".webm",".mp3",".wav",".mid",".rar",".zip",".tar",".gz",".7z",".bz2",".cab",".iso",".doc",".docx",".xls",".xlsx",".ppt",".pptx",".pdf",".txt",".md",".xml"]}';
        $CONFIG = json_decode(preg_replace("/\/\*[\s\S]+?\*\//", "", $json), true);

        return $CONFIG;
    }

    private function _setDataArr(){
        return array(
            "state" => (null!=$this->getError())?$this->getError():'SUCCESS',
            "url" => $this->getFullName(),
            "title" => $this->getFileBaseName(),
            "original" => $this->getFileBaseName(),
            "type" => '.'.$this->getOriginalExtension(),
            "size" => $this->getFileSize()
        );
    }

}