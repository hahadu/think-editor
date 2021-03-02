<?php


namespace Hahadu\ThinkEditor\Upload;


class Upload extends BaseUploader
{
    public function upload($fileField)
    {
        parent::upload($fileField);
        dump($this);
    }


}