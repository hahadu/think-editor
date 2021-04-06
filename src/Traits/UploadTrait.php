<?php


namespace Hahadu\ThinkEditor\Traits;


use Hahadu\Helper\FilesHelper;
use think\Collection;
use think\facade\Config;
use think\facade\Filesystem;

trait UploadTrait
{
    /****
     * pathUrl
     */
    private function _PathUrl()
    {
        if($this->config->offsetExists('pathUrl')){
            $this->pathUrl = $this->config->offsetExists('pathUrl');
        }
        $this->pathUrl = $this->fileSystem->getConfig()->get('url');
    }

    /****
     * 获取文件后缀
     */
    private function _FileExt()
    {
        $this->fileExt = $this->file->getOriginalExtension();
    }

    /****
     * 原始文件名
     */
    private function _FileBaseName()
    {
        $this->fileBaseName = $this->file->getOriginalName();
    }

    /****
     * 获取文件类型
     */
    private function _FileType()
    {
        $this->fileType = FilesHelper::get_file_type($this->fileBaseName);
    }

    /*****
     * 获取web可访问的文件路径
     */
    private function _FullName()
    {
        $this->fullName = $this->pathUrl . DIRECTORY_SEPARATOR . $this->fileSystem->putFile($this->putFilePath, $this->file);
    }

    /****
     * 设置文件保存目录
     */
    private function _setPutFile()
    {
        switch ($this->fileType) {
            case 'video':
                $filePut = 'videos';
                break;
            case 'image':
                $filePut = 'images';
                break;
            default:
                $filePut = 'files';
                break;
        }
        $this->config->offsetSet('putFile', $filePut);
        $uid = $this->config->offsetGet['user'];
        if ($this->config->offsetExists('putFilePath')) {
            $this->putFilePath = $uid.DIRECTORY_SEPARATOR.$this->config->offsetGet('putFilePath');
        } else {
            $this->putFilePath = $uid.DIRECTORY_SEPARATOR.$this->config->offsetGet('putFile');
        }
    }

    private function _FileSize()
    {
        $this->fileSize = $this->file->getSize();
    }

    private function _config()
    {
        $this->config = Collection::make($this->config)->merge(Config::get('editor'));
        //$this->config;
    }

    private function _FileMime()
    {
        $this->fileMime = $this->file->getOriginalMime();
    }

    private function _filePath()
    {
        $path_info = pathinfo($this->fullName);
        $this->filePath = $path_info['dirname'];
    }

    /*****
     * 添加水印
     */
    protected function _addWater()
    {
        if (null != Config::get('water.add_water_type')) {
            if (!empty($this->fullName)) {
                $this->fullName = add_water('.' . $this->fullName);
            }
        }
    }
    private function _setWater(){
        $this->water = Collection::make($this->config['water']);
        if(null!=$this->water['add_water_type'] && $this->fileType == 'image'){
            $this->_addWater();
        }
    }
    private function _fileCTime(){
        $this->fileCTime = $this->file->getCTime();
    }

    private function _fileSystem(){
        $this->fileSystem = Filesystem::disk($this->disk);
    }

}