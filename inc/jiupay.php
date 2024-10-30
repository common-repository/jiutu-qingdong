<?php

class RWMB_Jiupay
{

    protected $merchantsecret;

    public function __construct($arr)
    {
        $this->merchantsecret = $arr;
    }
    /**
     * 建立请求，以表单HTML形式构造（默认）
     * @param $para_temp 请求参数数组
     * @param $method 提交方式。两个值可选：post、get
     * @param $button_name 确认按钮显示文字
     * @return 提交表单HTML文本
     */
    // public function buildRequestForm($para_temp)
    // {
    //     $sHtml = "<form id='alipaysubmit' name='alipaysubmit' action='https://pay.iien.cn/submit.php?_input_charset=utf-8' method='POST'>";
    //     foreach ($para_temp as $key => $val) {
    //         if (false === $this->checkEmpty($val)) {
    //             $val = str_replace("'", "&apos;", $val);
    //             $sHtml .= "<input type='hidden' name='" . $key . "' value='" . $val . "'/>";
    //         }
    //     }
    //     $sHtml = $sHtml . "<input type='submit' value='正在跳转'></form><script>document.forms['alipaysubmit'].submit();</script>";
    //     return $sHtml;
    // }


    public function buildRequestForm($para_temp)
    {
        $url = 'https://pay.iien.cn/submit.php?' . urldecode(http_build_query($para_temp)); //禁止字符串转义和中文字符乱码
        return $url;
    }

    public function generateSign($params)
    {
        return md5($this->getSignContent($params) . $this->merchantsecret);
    }



    /**
     * 校验$value是否非空
     *  if not set ,return true;
     *    if is null , return true;
     **/
    protected function checkEmpty($value)
    {
        if (!isset($value)) {
            return true;
        }
        if ($value === null) {
            return true;
        }
        if (trim($value) === "") {
            return true;
        }

        return false;
    }

    public function getSignContent($params)
    {
        // unset($params['name']);
        ksort($params);
        $stringToBeSigned = "";
        $i = 0;
        foreach ($params as $k => $v) {
            if (false === $this->checkEmpty($v) && "@" != substr($v, 0, 1)) {
                $v = $this->characet($v, $this->charset);
                if ($i == 0) {
                    $stringToBeSigned .= "$k" . "=" . "$v";
                } else {
                    $stringToBeSigned .= "&" . "$k" . "=" . "$v";
                }
                $i++;
            }
        }
        unset($k, $v);
        // var_dump($stringToBeSigned);
        return $stringToBeSigned;
    }

    /**
     * 转换字符集编码
     * @param $data
     * @param $targetCharset
     * @return string
     */
    function characet($data, $targetCharset)
    {
        if (!empty($data)) {
            $fileType = $this->charset;
            if (strcasecmp($fileType, $targetCharset) != 0) {
                $data = mb_convert_encoding($data, $targetCharset, $fileType);
            }
        }
        return $data;
    }
}
