<?php


namespace Hahadu\ThinkEditor\Upload;
use Hahadu\ThinkEditor\Traits\UploadTrait;
use think\facade\Filesystem;
use think\Collection;
use think\file\UploadedFile;

class BaseUploader
{
    use UploadTrait;

    protected $request;
    /*****
     * @var mixed|string
     */
    protected $disk;
    /****
     * @var \think\filesystem\Driver
     */
    protected $fileSystem;
    /****
     * @var array|UploadedFile|null
     */
    protected $file;
    /*****
     * @var string 表单名称
     */
    protected $fileField;
    /****
     * @var Collection;
     */
    protected $config = [
        'disk'=>'public',
        'putFile'=>'images',
        'water'=>[]
    ];
    /****
     * @var string 文件保存目录
     */
    protected $putFilePath;

    /****
     * @var string 磁盘下级目录
     */
    protected $pathUrl;

    /****
     * @var string 文件扩展名
     */
    protected $fileExt;

    /****
     * @var string 文件类型
     */
    protected $fileType;

    /*****
     * @var string 上传表单提交的文件名
     */
    protected $fileBaseName;

    /****
     * @var string 文件Mime
     */
    protected $fileMime;

    /****
     * @var string web可访问的文件路径
     */
    protected $fullName;

    /****
     * @var numeric 文件大小
     */
    protected $fileSize;

    /****
     * @var string 文件保存目录
     */
    protected $filePath;

    /**
     * 构造函数
     * @param array $config 配置项
     */
    public function __construct($config=[]){
        $this->request = request();
        $this->_config();
        if(null!=$config){
            $this->configure($config);
        }
        $this->config->offsetSet('water',config('water'));
        $this->disk = $this->config->offsetGet('disk');
    }

    public function configure($config){
        $this->config = $this->config->merge($config);
    }

    private function _init(){
        $this->file = $this->request->file($this->fileField);
        $this->fileSystem = Filesystem::disk($this->disk);
        $this->_FileBaseName();
        $this->_FileType();
        $this->_setPutFile();

        $this->_FileMime();
        $this->_PathUrl();
        $this->_FileExt();
        $this->_FullName();
        $this->_FileSize();
        $this->_filePath();
        if(null!=$this->config['water']['add_water_type'] && $this->fileType == 'image'){
            $this->_addWater();
        }
    }

    /****
     * 表单文件上传方法
     * @param string $fileField 表单名称
     */
    public function upload($fileField){
        $this->fileField = $fileField;
        $this->_init();
    }


}