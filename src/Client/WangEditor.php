<?php


namespace Hahadu\ThinkEditor\Client;


use Hahadu\ThinkEditor\Upload\BaseUploader;
use QL\QueryList;
use think\response\Json;

class WangEditor extends BaseUploader
{
    /**
     * wangEditor 表单文件上传方法
     * @param $fileField
     * @return Json
     */
    public function wangEditorUploader($fileField)
    {
        parent::upload($fileField);
        $data = $this->_setDataArr();

        return json($data);
    }

    /*****
     * wangEditor 文本内容base64图片转换为本地文件
     * @param string $html 表单提交的文本内容
     * @return mixed
     */
    public function wangEditorBase64Img($html){
        $ql = new QueryList();
        $html = $ql->html($html);

        $html->find('img')->map(function($item){
            $src = $item->attr('src');
            $alt = $item->attr('alt');
            if(strstr($src,';base64,')){
                $this->_deBase64($src);
                $img = $this->getFullName();
                if($src==$alt){
                    $alt = $this->getFileBaseName();
                }

                $item->attr('src',$img);
                $item->attr('alt',$alt);
            }
            return $item;
        });

        return $html->getHtml();
    }

    /*****
     * @param string $base64Data
     */
    protected function _deBase64(string $base64Data):void
    {
        $dataArr = explode(';base64,', $base64Data);
        $base64Data = $dataArr[1];
        parent::upBase64($base64Data);
    }

    private function _setDataArr(){
        if(null!=$this->getFullName()){
            $data = [
                'errno' => 0,
                'data' => [
                    [
                        'url' => $this->getFullName(),
                        'alt' => $this->getFileBaseName(),
                        'href' => '',
                    ],
                ],
            ];
        }else{
            $data = [
                'errno' => 1,
                'data' => [ [], ],
            ];
        }
        return $data;
    }

}