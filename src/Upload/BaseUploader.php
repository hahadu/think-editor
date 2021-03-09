<?php


namespace Hahadu\ThinkEditor\Upload;

use Hahadu\Helper\FilesHelper;
use Hahadu\ThinkEditor\Traits\UploadTrait;
use think\exception\ValidateException;
use think\facade\Filesystem;
use think\Collection;
use think\file\UploadedFile;

class BaseUploader
{
    use UploadTrait;

    private $request;
    /*****
     * @var mixed|string
     */
    private $disk;
    /****
     * @var \think\filesystem\Driver
     */
    private $fileSystem;
    /****
     * @var array|UploadedFile|null
     */
    private $file;
    /*****
     * @var string 表单名称
     */
    private $fileField;
    /****
     * @var Collection;
     */
    private $config = [
        'disk' => 'public',
        'putFile' => 'files',
        'upValidate'=> [
            'fileSize:10240000' //默认上传限制10M php.ini调整上传大小
        ],

        'water' => [
            'add_water_type' => 1, //添加水印 0不添加水印，1添加文字水印，2添加图片水印
        ],
        'base64' => [
            'extName' => 'jpg', //缓存名字后缀
        ],
    ];
    /****
     * @var array 上传验证规则
     */
    private $upValidate;

    /*****
     * @var numeric 文件上传大小限制
     */
    private $maxSize;

    /****
     * @var string 磁盘下级目录
     */
    private $pathUrl;

    /****
     * @var string 文件扩展名
     */
    private $fileExt;

    /****
     * @var string 文件类型
     */
    private $fileType;

    /*****
     * @var string 上传表单提交的文件名
     */
    private $fileBaseName;

    /****
     * @var string 文件Mime
     */
    private $fileMime;

    /****
     * @var string web可访问的文件路径
     */
    private $fullName;

    /****
     * @var numeric 文件大小
     */
    private $fileSize;

    /****
     * @var string 文件保存目录
     */
    private $filePath;

    /*****
     * @var array 水印配置
     */
    private $water;

    /*****
     * @var array base64配置信息
     */
    private $base64Config;

    /****
     * @var string 文件上传时间
     */
    private $fileCTime;

    /*****
     * @var ValidateException 错误信息
     */
    private $error;

    /**
     * 构造函数
     * @param array $config 配置项
     */
    public function __construct($config = [])
    {
        $this->request = request();
        $this->_config();
        if (null != $config) {
            $this->configure($config);
        }
        $this->config->offsetSet('water', config('water'));
        $this->disk = $this->config->offsetGet('disk');
        $this->base64Config = $this->config->offsetGet('base64');
        $this->upValidate = $this->config->offsetGet('upValidate');

    }

    public function configure($config)
    {
        $this->config = $this->config->merge($config);
    }

    private function _init()
    {
        if (null != $this->fileField) {
            $this->file = $this->request->file($this->fileField);

            try {
                validate(['file' => $this->upValidate])->check(['file' => $this->file]);
            } catch (\Exception $e) {
                $this->error = $e;
            }

        }
        $this->_FileBaseName();
        $this->fileSystem = Filesystem::disk($this->disk);
        $this->_FileType();
        $this->_setPutFile();

        $this->_FileMime();
        $this->_PathUrl();
        $this->_FileExt();
        $this->_FullName();
        $this->_FileSize();
        $this->_filePath();
        $this->_setWater();
        $this->_fileCTime();
    }

    /****
     * 表单文件上传方法
     * @param string $fileField 表单名称
     */
    public function upload($fileField)
    {
        $this->fileField = $fileField;
        $this->_init();
    }

    /**
     * 处理base64编码的图片上传
     * @param string $base64Data base64数据
     * @return mixed
     */
    public function upBase64($base64Data)
    {
        $this->file = base64_file_info($base64Data, $this->base64Config['extName']);
        $this->_init();
    }

    /****
     * @return string
     */
    public function getFullName(){
        return $this->fullName;
    }

    /****
     * @return string
     */
    public function getFileBaseName(){
        return $this->fileBaseName;
    }

    public function getPathUrl(){
        return $this->pathUrl;
    }
    public function getConfig(){
        return $this->config;
    }

    /*****
     * 原始文件对象
     * @return array|null|\SplFileInfo
     */
    public function getFileObject(){
        return $this->file;
    }
    public function getOriginalMime(){
        return $this->fileMime;
    }
    public function getFileSize(){
        return $this->fileSize;
    }
    public function getFilePath(){
        return $this->filePath;
    }
    public function getCTime(){
        return $this->fileCTime;
    }
    public function getOriginalExtension(){
        return $this->fileExt;
    }
    public function getFileType(){
        return $this->fileType;
    }
    /****
     * 错误信息
     * @return ValidateException
     */
    public function getError(){
        return $this->error;
    }



}