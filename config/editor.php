<?php
//editor配置文件
return [
    'disk'=>'public',

    'wangEditor'=>[

    ],
    'UEditor'=>[
        'catcher'=>[

            "allowFiles" =>  [ //设置支持的文件格式
                "png", "jpg", "jpeg", "gif", "bmp"
            ],
            "oriName" =>  md5(time().rand(10,99)).'.png'

        ],

        'manager'=>[
            'file'=>[
                /* 列出指定目录下的文件 */
                "allowFiles"=>  [
                    ".png", ".jpg", ".jpeg", ".gif", ".bmp",
                    ".flv", ".swf", ".mkv", ".avi", ".rm", ".rmvb", ".mpeg", ".mpg",
                    ".ogg", ".ogv", ".mov", ".wmv", ".mp4", ".webm", ".mp3", ".wav", ".mid",
                    ".rar", ".zip", ".tar", ".gz", ".7z", ".bz2", ".cab", ".iso",
                    ".doc", ".docx", ".xls", ".xlsx", ".ppt", ".pptx", ".pdf", ".txt", ".md", ".xml"
                ] /* 列出的文件类型 */
            ],
            'image'=>[
                /* 列出指定目录下的图片 */
                "allowFiles"=>  [".png", ".jpg", ".jpeg", ".gif", ".bmp"], /* 列出的文件类型 */

            ],
        ],

    ],
];