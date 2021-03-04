# think-editor
thinkphp 集成富文本编辑器服务端处理模块


##wangEditor编辑器上传服务端配置:
###引入文件:
```php
use Hahadu\ThinkEditor\Client\WangEditor;
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
$wE = new WangEditor();
$fileFiled = 'files'; // 文件上传表单名称
return $wE->wangEditorUploader($fileFiled);
```
###转换base64方法上传文件的base64文件为本地文件
表单提交地址（非文件上传的server地址）主处理方法，返回转换后的文本内容
```php
//假设提交文本内容的表单名称为content
$content = request()->post('content');
$wE = new WangEditor();
echo $wE->wangEditorBase64Img($content);
```