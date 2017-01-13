<?php
namespace common\lib;
use Yii;

/**
 * Created by PhpStorm.
 * User: adeng
 * Date: 2016/6/05
 * Time: 17:47
 */
class ApiCommon{

    const URL="http://xxx.playwolf719.com";
    const TOKEN_NAME="access-token";

    private static $heatHtml=<<<EOT
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>接口文档</title>
        
    <!-- 新 Bootstrap 核心 CSS 文件 -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
</head>
<body>
        
<br />
        
<div class="container">
        
<div class="jumbotron">
EOT;

    private static $tableHeadHtml=<<<EOT
    <hr>
    <h2>请求字段</h2>
    <table class="table table-striped" >
    <tr><th>参数名字</th><th>说明</th><th>类型</th><th>是否必须</th><th>默认值</th><th>其他</th></tr>

EOT;

    private static $listParamHtml=<<<EOT
    <tr><td>page</td><td>请求的页数</td><td>整型</td><td>选填</td><td>1</td><td></td></tr>
    <tr><td>per-page</td><td>单页最多展示条数</td><td>整型</td><td>选填</td><td>20</td><td>最大暂定50</td></tr>
    <tr><td>sort</td><td>排序字段</td><td>字符串</td><td>选填</td><td>id</td><td>id为asc，-id为desc</td></tr>
    <tr><td>id</td><td>按id搜索</td><td>字符串</td><td>选填</td><td>空</td><td></td></tr>
    <tr><td>xxx</td><td>按xxx字段搜索</td><td>类型</td><td>选填</td><td>空</td><td></td></tr>
EOT;


    private static $actRetTail=<<<EOT
<hr>
<h2>响应类型</h2>
application/json
<hr>
<h2>响应字段</h2>
<table class="table table-striped" >
<tr><th>响应字段</th><th>类型</th><th>说明</th></tr>
<tr><td>ret</td><td>整型</td><td>成功为0，失败非0。401代表未登录，请做相应操作。</td></tr>
<tr><td>msg</td><td>字符串</td><td>如“操作成功！”等消息。</td></tr>
<tr><td>data</td><td>字符串</td><td>若该接口无需返回结果，则返回空；反之，请以实际返回结果为准。</td></tr>
<tr><td>other</td><td>字符串</td><td>用作扩展</td></tr>
</table>

</div><!--container-->
</div><!--jumbotron-->
</body>
EOT;

    private static $listRetTail=<<<EOT
<hr>
<h2>返回结果</h2>
<table class="table table-striped" >
<tr><th>返回字段</th><th>类型</th><th>说明</th></tr>

<tr><td>ret</td><td>整型</td><td>成功为0，失败非0。401代表未登录，请做相应操作。</td></tr>
<tr><td>msg</td><td>字符串</td><td>如“操作成功！”等消息。</td></tr>
<tr><td>data</td><td>数组</td><td>若该接口无需返回结果，则返回空字符串；反之，请以实际返回结果为准。</td></tr>
<tr><td>data.items</td><td>数组</td><td>数据列表</td></tr>
<tr><td>data.links</td><td>数组</td><td>上下页</td></tr>
<tr><td>data.meta</td><td>数组</td><td>分页信息</td></tr>

</table>

</div><!--container-->
</div><!--jumbotron-->
</body>
EOT;


    private static $tailHtml=<<<EOT
</div><!--container-->
</div><!--jumbotron-->
</body>
EOT;


    /**
     * 展示目录
     */
    public static function showContent($theRules) {
        $output="";
        $part="";
        $output.=self::$heatHtml;
        if(array_key_exists("part",$theRules)){
            $part=$theRules["part"];
        }
        $output.=<<<EOT
<h1>{$part}接口列表</h1>
EOT;
        foreach ($theRules as $key => $value) {
            if(in_array($key,array("part"))){
                continue;
            }
            //注意：$arr[0]为空
            $output.=<<<EOT
<hr>
<h2>{$key}对象</h2>
<table class="table table-hover table-striped">
<tr><th>接口服务</th><th>描述</th></tr>
EOT;
            foreach ($value as $key1 => $value1) {
                $output.=<<<EOT
        <tr>
<td><a href="detail?flag={$key}/{$key1}"  >{$key1}</a></td>
EOT;
                if(array_key_exists("desc",$value1)){
                    $output.=<<<EOT
                    <td>{$value1['desc']}</td></tr>
EOT;
                }else{
                    $output.=<<<EOT
                    <td></td></tr>
EOT;

                }
            }
            $output.="</table>";
        }
        $output.=self::$tailHtml;
        echo $output;
    }

    public static function showDetail($flag,$theRules){
        $output="";
        $temp=explode("/",$flag);
        $theRules = $theRules[$temp[0]][$temp[1]];
        //请求URL
        if(!empty($theRules["with"]) ){
            $url = self::URL.Yii::getAlias("@web") . "/$temp[0]".$theRules["with"];
        }else{
            $url = self::URL.Yii::getAlias("@web") . "/$temp[0]";
        }
        $output.=self::$heatHtml;
        //请求方式
        if(empty($theRules["method"])){
            $theRules["method"]="GET";
        }
        //请求格式
        if(empty($theRules["content-type"])){
            $theRules["content-type"]="application/json";
        }
        $output .= <<<EOT
    <h1>接口~{$flag}</h1>
    <hr>
    <h2>请求URL</h2>
    <a href='{$url}' >{$url}</a>
    <hr>
    <h2>请求方法</h2>
    {$theRules["method"]}
    <hr>
    <h2>请求类型</h2>
    {$theRules["content-type"]}
EOT;
        $output.=self::$tableHeadHtml;
        if(!in_array("noToken",$theRules) ){
            $output.='<tr><td>access-token</td><td>该值通过url传递</td><td>字符串</td><td><font color="red">必填</font></td><td></td><td></td></tr>';
        }else{
            $output.='<tr><td>key-token</td><td>密钥，该值通过url传递</td><td>字符串</td><td><font color="red">必填</font></td><td></td><td>测试阶段该值为111111</td></tr>';
            $output.='<tr><td>timestamp</td><td>当前时间戳，该值通过url传递</td><td>字符串</td><td><font color="red">必填</font></td><td></td><td>如1483062510</td></tr>';
        }
        if(self::isRetList($temp[1])&& !array_key_exists("params",$theRules ) ){
            $output.=self::$listParamHtml;
        }else if(array_key_exists("params",$theRules )){
            //多参数
            foreach ($theRules as $key => $value) {
                //参数相关
                if ($key === "params") {
                    //单个参数的详细信息
                    foreach ($theRules["params"] as $key1 => $value) {
                        $res = array("paramName" => $key1);
                        //默认部分
                        $res['paramType'] = "字符串";
                        $res['paramDef'] = "";
                        if(self::isRetList($temp[1])&&$key1=="page"){
                            $res['paramDef'] = 1;
                        }
                        $res['paramDesc'] = '';
                        $res['paramOther'] = '';
                        //无论类型是什么，theRules()中必须存在的参数
                        //type,require,desc,
//                        var_dump($key1);
                        self::handle($res, $value);
                        $output.= <<<EOT
    <tr><td>{$res['paramName']}</td><td>{$res['paramDesc']}</td><td>{$res['paramType']}</td><td>{$res['paramReq']}</td>
    <td>{$res['paramDef']}</td><td>{$res['paramOther']}</td></tr>
EOT;
                    }
                }
            }
            if(self::isRetList($temp[1])){
                $output.=self::$listParamHtml;
            }
        }

        $output.='</table>';
        if(self::isRetList($temp[1]) ){
            $output.=self::$listRetTail;
        }else{
            $output.=self::$actRetTail;
        }
        echo $output;
    }

    //该接口是否是列表类型
    public static function isRetList($interName){
        if(strpos($interName, 'list') !== false||strpos($interName, '列表')!==false){
            return true;
        }else{
            return false;
        }
    }

    public static function handle(&$res,$flag){
        //对rule初始化
        if(!is_array($flag)){
            $flag=array(
                "type"=>"string",
                "require"=>true,
                "desc"=>$flag,
            );
        }else{
            if(empty($flag["type"])){
                $flag["type"]="string";
            }
        }

        if(!isset($flag["require"]) || $flag["require"]){
            $res["paramReq"]='<font color="red">必填</font>';
        }else{
            $res["paramReq"]='选填';
        }

        $res['paramType']=self::getTypeMap()[$flag["type"]];
        $res["paramDesc"]=$flag["desc"];
        switch ($flag["type"]) {
            case 'string':
                if(array_key_exists("min",$flag)){
                    $res['paramOther'].="最小长度为".$flag["min"]."字节；";
                }
                if(array_key_exists("max",$flag)){
                    $res['paramOther'].="最大长度为".$flag["max"]."字节；";
                }else{
                    $res['paramOther'].="最大长度为255字节；";
                }
                break;
            case 'int':
                break;
            case 'enum':
                $res['paramOther']="范围：";
                
                foreach ($flag["range"] as $key => $val){
                    $res['paramOther'].="/".$key.'-'.$val;
                }
                break;
            case 'float':
                break;
            case 'array':
                break;
            case 'file':
                break;
            case 'date':
                $res['paramOther']='形如"2016-02-20"';
                break;
            default:
                break;
        }
        if(array_key_exists("default",$flag)){
            $res['paramDef']=$flag["default"];
        }
        if(array_key_exists('other',$flag)){
            if(!empty($res['paramOther'])){
                $res['paramOther'].="||".$flag["other"];
            }else{
                $res['paramOther']=$flag["other"];
            }
        }

    }


    public static function getTypeMap()
    {
        return array(
            'string' => '字符串',
            'text' => '文本',
            'int' => '整型',
            'float' => '浮点型',
            'boolean' => '布尔型',
            'date' => '日期',
            'datetime' => '时间',
            'array' => '数组',
            'enum' => '枚举类型',
            'object' => '对象',
            "file"=>"文件",
            // 'object' => '对象' 
        );
    }

}