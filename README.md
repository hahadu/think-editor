# think-editor
thinkphp 集成富文本编辑器服务端处理模块


##wangEditor编辑器上传服务端配置:
###引入文件:
```php
use Hahadu\ThinkEditor\Client\WangEditor;
$wE = new WangEditor();
```
###上传文件：
在wangEditor配置
```javascript
const E = window.wangEditor
const editor = new E('#div1')

// 配置 server 接口地址
editor.config.uploadImgServer = '';
//设置上传表单名称 以files为例
editor.config.uploadFileName = "files" 

editor.create()

```
文件上传处理方法,返回json
```php
//服务端配置
$config = [
        'disk' => 'public', //filesystem 中的disk配置 默认public
     //   'putFilePath' => 'files', //disk/url目录的下级目录，不设置则根据文件类型自动选择
        'upValidate'=> [ //上传文件验证规则
            'fileSize:10240000', //默认上传限制10M php.ini调整上传大小
            //'fileExt:jpg,png,gif,mp4', //允许上传的文件格式
        ],

        'water' => [ //水印设置
            'add_water_type' => 1, //添加水印 0不添加水印，1添加文字水印，2添加图片水印
        ],
        'base64' => [ //base64文件上传设置
            'extName' => 'jpg', //缓存名字后缀
        ],

];

$fileFiled = 'files'; // 文件上传表单名称
return $wE->uploader($fileFiled);
```
###转换base64方法上传文件的base64文件为本地文件
表单提交地址（非文件上传的server地址）主处理方法，返回转换后的文本内容
```php
//假设提交文本内容的表单名称为content
$content = request()->post('content');

echo $wE->base64Img($content);
```