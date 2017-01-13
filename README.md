# EasyApi
-------
- 通过简单配置，自动生成简易的在线API文档。[体验点击我](http://123.206.34.198/nll-front/api/index)
- 本代码适用于较简单的REST风格的项目，可帮助快速生成简易API文档，方便前后端快速开发。较为复杂的接口设计，可根据自己需求对代码进行修改，便于自己的开发。

















## 配置方法


![test](https://s23.postimg.org/8y2wom93v/QQ_20170113103359.jpg)

```
<?php
namespace frontend\controllers;

use common\lib\ApiCommon;
use Yii;
use common\lib\MyCommon;
use yii\web\Controller;


class ApiController extends Controller
{
    public $enableCsrfValidation = false;

    public function actionIndex(){
        ApiCommon::showContent($this->theRules());
    }
    public function actionDetail(){
        ApiCommon::showDetail($_GET["flag"],$this->theRules());
    }

    private function theRules(){
        return array(
            "part"=>"front",
            "member"=>$this->memberPart(),
            "help"=>$this->helpPart(),
            "common"=>$this->commonPart(),
        );
    }

    private function commonPart(){
        return array(
            '发送验证码' => array(
                "with"=>"/send-verify-code",
                'method' => "POST",
                "params" => array(
                    "phone"=> array(
                        'desc' => '手机号',
                        'max' => '32',
                    ),
                    "type"=> array(
                        'desc' => '验证码类型',
                        'type'=>"enum",
                        "range"=>array(
                            1=>"register",
                            2=>"forget-password",
                        ),
                        "other"=>"分别代表注册时验证码、忘记密码时验证码。",
                    ),
                ),
                "noToken"
            ),
            '单文件上传' => array(
                "with"=>"/upfile",
                'method' => "POST",
                'content-type' => "multipart/form-data",
                "params" => array(
                    "theFile"=> array(
                        'desc' => '文件',
                        'type' => 'file',
                    ),
                ),
                "noToken"
            ),
        );

    }
    private function helpPart(){
        return array(
            '帮助信息' => array(
                "with"=>"/{id}",
                "noToken"
            ),
            '帮助列表' => array(
                "noToken"
            ),
        );

    }

    private function memberPart(){
        return array(
            '注册' => array(
                'method' => "POST",
                "params" => array(
                    "nickName"=>"昵称",
                    "trueName"=> "真实姓名",
                    "password"=> array(
                        'desc' => '密码',
                        'min' => '6',
                    ),
                    "phone"=> array(
                        'desc' => '手机号',
                        'max' => '32',
                    ),
                    "verifyCode"=>"验证码",
                    'email' => '邮箱',
                ),
                "noToken"
            ),
            '登录' => array(
                "with"=>"/login",
                'method' => "POST",
                "params" => array(
                    "phone"=> array(
                        'desc' => '手机号',
                        'max' => '32',
                    ),
                    "password"=> array(
                        'desc' => '密码',
                        'min' => '6',
                    ),
                ),
                "noToken"
            ),
            '修改信息' => array(
                "with"=>"/{id}",
                'method' => "PUT",
                "params" => array(
                    "nickName"=>"昵称",
                    "trueName"=> "真实姓名",
                    "email"=> "邮箱",
                )
            ),
            '修改密码' => array(
                "with"=>"/update-password",
                'method' => "POST",
                "params" => array(
                    "password"=> array(
                        'desc' => '原始密码',
                        'min' => '6',
                    ),
                    "newPassword"=> array(
                        'desc' => '密码',
                        'min' => '6',
                    ),
                )
            ),
            '忘记密码时重置密码' => array(
                "with"=>"/update-password-forget",
                'method' => "POST",
                "params" => array(
                    "phone"=> array(
                        'desc' => '手机号',
                        'max' => '32',
                    ),
                    "newPassword"=> array(
                        'desc' => '密码',
                        'min' => '6',
                    ),
                    "verifyCode"=>"验证码",
                ),
                "noToken"
            ),
            '用户信息' => array(
                "with"=>"/{id}",
            ),

        );
    }
}
```
