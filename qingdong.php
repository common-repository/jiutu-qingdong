<?php
/*
Plugin Name: WP支付小商店
Plugin URI: https://wordpress.org/plugins/jiutu_qingdong/
Description: Wordpress商城系统
Version:1.0.9
Author: jiutu_
Author URI: https://wpapi.aliluv.cn/
*/

if (defined('ABSPATH') && !defined('RWMB_VER')) {
    require_once dirname(__FILE__) . '/inc/loader.php';
    $rwmb_loader = new RWMB_Loader();
    $rwmb_loader->init();
}

require_once plugin_dir_path(__FILE__) . 'admin/classes/setup.class.php';

add_action('admin_menu', function () {
    add_menu_page('我的小店', '我的小店', 'administrator', 'qd_shop', '', 'dashicons-products', 25);
});


add_action('init', function () {
    register_post_type('products', array(
        'labels' => array(
            'menu_name'          => '商品管理',
            'name'               => '所有商品',
            'add_new'            => '添加商品',
            'add_new_item'       => '添加商品',
            'new_item'           => '添加商品',
            'edit_item'          => '编辑商品',
            'view_item'          => '商品信息',
            'update_item'        => '更新商品',
            'all_items'          => '商品管理',
            'search_items'       => '查找商品',
        ),
        'public'              => false,
        'exclude_from_search' => false,
        'publicly_queryable'  => true,
        'show_ui'             => true,
        'show_in_nav_menus'   => true,
        'show_in_admin_bar'   => true,
        'show_in_rest'        => true,
        'capability_type'     => 'page',
        'hierarchical'        => false,
        'has_archive'         => true,
        'query_var'           => true,
        'can_export'          => true,
        'rewrite_no_front'    => false,
        'show_in_menu'        => 'qd_shop',
        'menu_icon'           => 'dashicons-products',
        'supports' => array('title', 'comments', 'revisions', 'thumbnail'),
        'rewrite' => true
    ));
    register_post_type('orders', array(
        'labels' => array(
            'menu_name'          => '订单管理',
            'name'               => '所有订单',
            'add_new'            => '添加订单',
            'add_new_item'       => '添加订单',
            'new_item'           => '添加订单',
            'edit_item'          => '编辑订单',
            'view_item'          => '订单信息',
            'update_item'        => '更新订单',
            'all_items'          => '订单管理',
            'search_items'       => '查找订单',
        ),
        'public'              => false,
        'exclude_from_search' => false,
        'publicly_queryable'  => true,
        'show_ui'             => true,
        'show_in_nav_menus'   => true,
        'show_in_admin_bar'   => true,
        'show_in_rest'        => true,
        'capability_type'     => 'page',
        'hierarchical'        => false,
        'has_archive'         => true,
        'query_var'           => true,
        'can_export'          => true,
        'rewrite_no_front'    => false,
        'show_in_menu'        => 'qd_shop',
        'menu_icon'           => 'dashicons-editor-ol',
        'supports' => array(''),
        'rewrite' => true
    ));
});

add_filter('rwmb_meta_boxes', function ($meta_boxes) {
    $prefix = 'qd_goods_';
    $meta_boxes[] = array(
        'title'      => '商品详情',
        'post_types' => array('products'),
        'autosave'   => true,
        'fields'     => array(
            array('type' => 'image_advanced',  'id'   => $prefix . 'image'),
            array('type' => 'textarea', 'name' => '商品介绍', 'id' => $prefix . 'introduce'),
            array('type' => 'divider'),
            array('type' => 'text', 'name' => '商品金额', 'id' => $prefix . 'price', 'size' => 15),
            array('type' => 'text', 'name' => '库存', 'id' => $prefix . 'stock', 'size' => 15, 'std' => 999),
            array('type' => 'text', 'name' => '已出售', 'id' => $prefix . 'sell', 'size' => 15, 'std' => 0),
            array('type' => 'divider'),
            array('type' => 'checkbox', 'name' => '虚拟商品', 'id' => $prefix . 'fictitious'),
            array('type' => 'textarea', 'name' => '虚拟商品信息', 'id' => $prefix . 'fictitious_data', 'desc' => '上方打勾后,用户购买成功后使用凭证查询将返回此信息')

        ),
        'validation' => array(
            'rules'  => array(
                $prefix . 'image' => array('required'  => true),
                $prefix . 'price' => array('required'  => true),
                $prefix . 'stock' => array('required'  => true, 'digits' => true),
            ),
            'messages' => array(
                $prefix . 'image' => array('required'  => '商品图是必须的,至少一张'),
                $prefix . 'price' => array('required'  => '商品价格是必须的', 'digits' => '价格必须为数字'),
                $prefix . 'stock' => array('required'  => '商品库存是必须的', 'digits' =>  '库存必须为数字'),
            )
        )
    );

    return $meta_boxes;
});


// function qd_get_orders_status($type = 'all')
// {
//     $status = array(
//         '1' => '待付款',
//         '2' => '正在处理',
//         '3' => '已完成',
//         '4' => '已取消',
//         '5' => '已退款',
//     );
//     if ($type == 'all') {
//         return $status;
//     }
//     if (array_key_exists($type, $status)) {
//         return  $status[$type];
//     } else {
//         return '';
//     }
// }
add_filter('rwmb_meta_boxes', function ($meta_boxes) {
    $prefix = 'qd_orders_';
    $order = (isset($_GET['post'])) ? $_GET['post'] : '保存后可查看';
    $meta_boxes[] = array(
        'title'      => '订单信息',
        'post_types' => array('orders'),
        'autosave'   => true,
        'fields'     => array(
            array(
                'type' => 'custom_html',
                'std'  => '<h3 style="font-size: 21px;">订单 #' . $order . ' 详情</h3>',
            ), array('type' => 'divider'),

            array(
                'name'            => '订单状态',
                'id'              =>  $prefix . 'status',
                'type'            => 'select_tree',
                'flatten' => true,
                'std' => 1,
                'options'         => array(
                    '1' => '待付款',
                    '2' => '正在处理',
                    '3' => '已完成',
                    '4' => '已取消',
                    '5' => '已退款',
                )
            ),
            array(
                'type'       => 'user',
                'name'       => '用户',
                'id'         => $prefix . 'user',
                'field_type' => 'select_tree',
                'ajax'       => true,
            ),
            array('type' => 'divider'),
            array(
                'name'    => '订单信息',
                'id'      => $prefix . 'data',
                'type'    => 'fieldset_text',
                'options' => array(
                    'name'    => '收件人姓名',
                    'telephone' => '收件人电话',
                    'address'   => '收件人地址',
                )
            ), array(
                'name'        => '订单凭证',
                'id'          => $prefix .  'voucher',
                'desc'        => '用户可通过此凭证查询订单信息',
                'type'        => 'text',
            ),
            array(
                'name'        => '关联商品',
                'id'          => $prefix . 'goods',
                'type'        => 'post',
                'post_type'   => 'products',
                'field_type'  => 'select_tree',
                'query_args'  => array(
                    'post_status'    => 'publish',
                    'posts_per_page' => -1,
                )
            ),

        )
    );
    return $meta_boxes;
});





add_filter('rwmb_meta_boxes', function ($meta_boxes) {
    $fields[] =  array(
        'type'       => 'button',
        'std'        => '更新',
        'attributes' => array('type' => 'submit', 'style' => 'float:right;')
    );
    // var_dump($fields);
    if (isset($_GET['post'])) {
        // var_dump($_GET['post']);
        $arr['p'] = $_GET['post'];
        $arr['post_type']  = 'orders'; //获取的类型，比如文章或者页面
        $arr['post_status'] = 'publish'; //文章状态
        $res =  query_posts($arr);
        // var_dump($res);
        if ($res != []) {
            array_unshift(
                $fields,
                array(
                    'type' => 'custom_html',
                    'std'  => '<div class="alert alert-warning">创建时间:<br/>' . $res[0]->post_date . '</div><hr/>',
                ),

            );
        };
    }

    array_unshift(
        $fields,
        [
            'id'   => 'post_status',
            'type' => 'hidden',
            // Hidden field must have predefined value
            'std'  => 'publish',
        ]

    );

    // get_post_meta(391, 'qd_orders_pay_time', true);
    $meta_boxes[] = array(
        'title'   => '创建',
        'id'      => 'submit',
        'post_types' => array('orders'),
        'context' => 'side',
        'fields'  =>  $fields
    );
    return $meta_boxes;
});


if (class_exists('CSF')) {
    $prefix = 'qd_admin';

    CSF::createOptions($prefix, array(
        'menu_title' => '商店设置',
        'menu_slug'  => 'qd_admin',
        'framework_title'   => '商店设置',
        'theme'             => 'light',
        'menu_icon'         => 'dashicons-store',
        'footer_text'       => '任何使用问题可联系QQ:781272314',
        'nav'               => 'inline',
        'show_reset_all'    => false,
        'show_reset_section' => false,
        'show_all_options'  => false,
        'menu_position'     => 90,
    ));
    CSF::createSection($prefix, array(
        'title'  => '常规设置',
        'fields' => array(
            array(
                'id'    => 'title',
                'type'  => 'text',
                'title' => '1、商品首页标题',
                'default' => '小商城',
            ),
            array(
                'id'    => 'introduce',
                'type'  => 'textarea',
                'title' => '2、商品首页介绍',
                'default' => '这是一个小商城!此wp插件永久免费使用、快速安装:后台搜索 ·jiutu-qingdong· 即可安装',
            ),
            array(
                'id'    => 'margin-top',
                'type'  => 'spacing',
                'title' => '3、商品首页顶部间距',
                'left'  => false,
                'right' => false,
                'bottom' => false,
                'units' => array('px', 'em', '%', 'cm', 'pt'),
                'desc'  => '自行根据主题情况进行调',
                'default' => '0'
            ),
            array(
                'id'    => 'show_product_introduction',
                'type'  => 'switcher',
                'title' => '4、是否显示商品介绍',
                'default' => true
            ),
            array(
                'id'    => 'bootstrap',
                'type'  => 'switcher',
                'title' => '5、启用本插件Bootstrap',
                'default' => false,
                'desc'  => '如果使用的主题自带Bootstrap,可关闭此选项、避免样式冲突!(大多数主题都是用Bootstrap,默认关闭)',
            ),
        )
    ));
    CSF::createSection($prefix, array(
        'title'  => '支付设置',
        'fields' => array(
            array(
                'type'    => 'subheading',
                'content' => 'tip:目前只支持配置支付宝.(因为没有微信支付商户测试代码)'
            ),
            array(
                'id'        => 'alipay',
                'type'      => 'fieldset',
                'title'     => '支付宝支付配置',
                'dependency' => array('usepay', '==', 'false'),
                'fields'    => array(
                    array(
                        'id'    => 'appid',
                        'type'  => 'text',
                        'title' => 'APPID',
                        'desc'  => 'https://open.alipay.com 账户中心->密钥管理->开放平台密钥、填写添加了电脑,手机网站支付的应用的APPID',
                    ),
                    array(
                        'id'    => 'rsaprivatekey',
                        'type'  => 'textarea',
                        'title' => '商户私钥',
                        'desc'  => '商户私钥,填写 RSA2 签名算法类型的私钥,如何生成密钥参考:https://docs.open.alipay.com/291/105971 和 https://docs.open.alipay.com/200/105310'
                    ),
                    array(
                        'id'    => 'alipaypublickey',
                        'type'  => 'textarea',
                        'title' => '支付宝公钥',
                        'desc'  => '账户中心->密钥管理->开放平台密钥，找到添加了支付功能的应用，根据你的加密类型，查看支付宝公钥',
                    ),
                    array(
                        'id'    => 'alipay_return_url',
                        'type'  => 'text',
                        'title' => '同步通知地址',
                        'desc'  => '用户付款成功后跳转到此地址(如不懂保持默认即可',
                        'default' => home_url('/wp-json/payment/return'),
                    ),
                    array(
                        'id'    => 'alipay_notify_url',
                        'type'  => 'text',
                        'title' => '异步通知地址',
                        'desc'  => '用户付款成功后、支付宝通知地址!用于处理订单状态或其他逻辑(如不懂保持默认即可',
                        'default' => home_url('/wp-json/payment/notify'),
                    ),
                ),
            ),
            array(
                'id'        => 'wechat',
                'type'      => 'fieldset',
                'title'     => '微信支付配置',
                'dependency' => array('usepay', '==', 'false'),
                'fields'    => array(
                    array(
                        'type'    => 'subheading',
                        'content' => '待开发..'
                    ),

                ),
            ),

            array(
                'id'    => 'usepay',
                'type'  => 'switcher',
                'title' => '对接JiuPay支付',
                'desc'  => 'JiuPay设立门槛注册商户,为防止并禁止商户用于违法商品行为!!!',
            ),

            array(
                'id'        => 'thirdpay',
                'type'      => 'fieldset',
                'title'     => '第三方支付配置',
                'dependency' => array('usepay', '==', 'true'),
                'fields'    => array(
                    array(
                        'id'         => 'merchantkey',
                        'type'       => 'text',
                        'title'      => '商户ID',
                        'desc'  => '商户ID 请前往 <a href="https://pay.iien.cn/">JiuPay</a> 注册获取!',
                    ),
                    array(
                        'id'         => 'merchantsecret',
                        'type'       => 'text',
                        'title'      => '商户密钥',
                        'desc'  => '商户密钥 请前往 <a href="https://pay.iien.cn/">JiuPay</a> 注册获取!',
                    ),
                    array(
                        'id'    => 'return_url',
                        'type'  => 'text',
                        'title' => '同步通知地址',
                        'desc'  => '用户付款成功后跳转到此地址(如不懂保持默认即可',
                        'default' => home_url('/wp-json/payment/return'),
                    ),
                    array(
                        'id'    => 'notify_url',
                        'type'  => 'text',
                        'title' => '异步通知地址',
                        'desc'  => '用户付款成功后、支付平台同步通知地址!用于处理订单状态或其他逻辑(如不懂保持默认即可',
                        'default' => home_url('/wp-json/payment/notify'),
                    ),
                ),

            ),

        )
    ));
    CSF::createSection($prefix, array(
        'title'  => '常见问题/介绍',
        'fields' => array(
            array(
                'type'    => 'subheading',
                'content' => '<h3>此插件永久免费使用!任何使用问题、建议可联系反馈</h3>演示:<a href="https://wpapi.aliluv.cn/products/">点击打开</a><br/><br/>
                1、 你的商店地址: <a href="' . home_url('/products') . '"> ' . home_url('/products') . '</a> (如果打开显示404页面,请先到后台 <a href="' . admin_url('options-permalink.php') . '"> ‘设置’ </a> -> ‘固定链接’ 设置成 ’文章名’ (其他形式是否可以、自行尝试) )
                 <br/><br/>
                2、如果商店地址样式显示出乱,请到·常规设置·第5步进行调试!
                 '
            ),

        )
    ));
}
add_filter('template_include', function ($template_path) {
    if (get_post_type() == 'products') {
        if (is_single()) {
            return plugin_dir_path(__FILE__) . 'templates/single-products.php';
        } elseif (is_archive()) {
            return plugin_dir_path(__FILE__) . 'templates/archive-products.php';
        }
    }
    return $template_path;
}, 1);


add_action('admin_init', function () {
    remove_meta_box('submitdiv', 'orders', 'normal');
    remove_meta_box('slugdiv', 'orders', 'normal');
});



add_filter('manage_orders_posts_columns', function ($columns) {
    $new_columns['cb'] = '<input type="checkbox" />';
    $new_columns['order_id'] = '订单号';
    $new_columns['order_status'] = '状态';
    $new_columns['order_user'] = '客户';
    $new_columns['order_goods'] = '商品';
    // $new_columns['money'] = '金额';
    return $new_columns;
});




add_action('manage_orders_posts_custom_column', function ($column_name, $post_id) {
    // var_dump($column_name);
    // global $wpdb;
    switch ($column_name) {
        case 'order_id':
            echo '<strong style="font-size: 16px;">#' . $post_id . '</strong>';
            break;
        case 'order_status':
            // wp_tag_cloud('smallest=12&largest=18&unit=px&number=0');
            echo '<span class="comment-count-approved">待付款</span>';

            // rwmb_the_value('qd_orders_status', [], $post_id);
            break;
        case 'order_user':
            rwmb_the_value('qd_orders_user', [], $post_id);
            break;
        case 'order_goods':
            rwmb_the_value('qd_orders_goods', [], $post_id);
            break;
        default:
            break;
    }
}, 10, 2);




add_filter('post_row_actions', function ($actions, $post) {
    // var_dump($actions);
    if ($post->post_type == 'orders' && isset($actions['inline hide-if-no-js'])) {
        unset($actions['inline hide-if-no-js']);
        unset($actions['view']);
    }
    return $actions;
}, 10, 2);



add_action('rest_api_init', function () {
    register_rest_route('payment', '/return', array(
        'methods' => 'GET',
        'callback' => function ($request) {
            if (qd_payment_handle($request->get_params())) {
                echo '支付成功';
            } else {
                echo '支付失败';
            }
            return;
        }
    ));

    register_rest_route('payment', '/notify', array(
        'methods' => 'POST',
        'callback' => function ($request) {
            if (qd_payment_handle($request->get_params())) {
                ////处理你的逻辑，例如获取订单号$_POST['out_trade_no']，订单金额$_POST['total_amount']等
                //程序执行完后必须打印输出“success”（不包含引号）。如果商户反馈给支付宝的字符不是success这7个字符，支付宝服务器会不断重发通知，直到超过24小时22分钟。一般情况下，25小时以内完成8次通知（通知的间隔频率一般是：4m,10m,10m,1h,2h,6h,15h）；
                if ($request->get_param('trade_status') == 'TRADE_SUCCESS') {
                    // $user_id = $request->get_param('passback_params');
                    $out_trade_no = $request->get_param('out_trade_no');
                    jiutu_update_orders($out_trade_no);
                    echo 'success';
                } else {
                    echo 'error';
                }
            } else {
                echo 'error';
            }
            return;
        }
    ));
});

/**
 * 处理订单状态
 *
 * @param   [type]  $out_trade_no  [$out_trade_no description]
 *
 * @return  [type]                 [return description]
 */
function jiutu_update_orders($out_trade_no)
{
    update_post_meta($out_trade_no, 'qd_orders_status', 3);
}


add_action('wp_ajax_nopriv_qd_add_order_api', 'qd_add_order_api');
add_action('wp_ajax_qd_add_order_api', 'qd_add_order_api');
function qd_add_order_api()
{
    // var_dump($_POST);
    if (!isset($_POST['qd_orders_goods'])) {
        exit(json_encode(array(
            'result' => false,
            'msg' => '商品ID不能为空'
        )));
    }
    $goods = query_posts(array(
        'p' => $_POST['qd_orders_goods'],
        'post_type'  => 'products',
        'post_status' => 'publish'
    ));
    if ($goods == []) {
        exit(json_encode(array(
            'result' => false,
            'msg' => '商品不存在'
        )));
    }
    $fictitious = get_post_meta($_POST['qd_orders_goods'], 'qd_goods_fictitious', true);
    // var_dump(get_post_meta($_POST['qd_orders_goods'], 'qd_goods_fictitious', true));
    // exit;

    if ($fictitious == '1') {
        // var_dump('sfd');
        if (!isset($_POST['qd_orders_voucher']) || $_POST['qd_orders_voucher'] == '') {
            exit(json_encode(array(
                'result' => false,
                'msg' => '订单凭证不能为空'
            )));
        }
    } else {
        if (!isset($_POST['name']) || $_POST['name'] == '') {
            exit(json_encode(array(
                'result' => false,
                'msg' => '收件人不能为空'
            )));
        }
        if (!isset($_POST['telephone']) || $_POST['telephone'] == '') {
            exit(json_encode(array(
                'result' => false,
                'msg' => '联系电话不能为空'
            )));
        }
        if (!isset($_POST['address']) || $_POST['address'] == '') {
            exit(json_encode(array(
                'result' => false,
                'msg' => '收件地址不能为空'
            )));
        }
    }

    $post_id = wp_insert_post(array(
        'post_author' => 1, //作者编号
        'post_date' => date('Y-m-d H:i:s', time()), //文章编辑日期
        'post_status' => 'publish', //新文章的状态 qd_goods_price
        'post_title' => '新订单', //文章标题，必填
        'post_type' => 'orders', //文章类型：文章、页面、链接、菜单、其他定制类型
    ));
    if ($post_id == 0) {
        exit(json_encode(array(
            'result' => false,
            'msg' => '新增订单失败'
        )));
    }

    /**
     * 更新数据
     *
     * @var [type]
     */
    jiutu_update_products($post_id, $fictitious, $_POST);
    $payAmount = rwmb_meta('qd_goods_price', [], $goods[0]->ID);
    $admin_config = get_option('qd_admin');

    if ($admin_config['usepay'] == '1') { //判断是否使用第三方支付 
        //请在这里编写支付逻辑

        $thirdpay = $admin_config['thirdpay']; //获取第三方支付数据
        // var_dump($thirdpay);
        $Jiupay = new RWMB_Jiupay($thirdpay['merchantsecret']);


        //构造要请求的参数数组，无需改动
        $parameter = array(
            "pid" => trim($thirdpay['merchantkey']),
            "notify_url" => $thirdpay['notify_url'],
            "return_url" => $thirdpay['return_url'],
            "out_trade_no" => $post_id,
            "name" => $goods[0]->post_title,
            "type" => 'alipay',
            "money" => $payAmount,
        );

        //建立请求
        $parameter["sign"] = $Jiupay->generateSign($parameter);
        $url = $Jiupay->buildRequestForm($parameter);
        exit(json_encode(array(
            'result' => true,
            'msg' => '新增订单成功',
            'url' =>  $url
        )));
    }


    //现在只有支付宝支付,所有目前所有购买都默认先调用支付宝支付
    //

    $aliPay = new RWMB_Alipay();
    if (wp_is_mobile()) {
        $method = 'alipay.trade.wap.pay';
    } else {
        $method = 'alipay.trade.page.pay';
    }




    $aliPay->setAppid($admin_config['alipay']['appid']);
    $aliPay->setRsaPrivateKey($admin_config['alipay']['rsaprivatekey']);
    $aliPay->setReturnUrl($admin_config['alipay']['alipay_return_url']);
    $aliPay->setNotifyUrl($admin_config['alipay']['alipay_notify_url']);
    $aliPay->setTotalFee($payAmount);
    $aliPay->setOutTradeNo($post_id);
    $aliPay->setOrderName($goods[0]->post_title);
    $aliPay->setUserId(get_current_user_id());
    $aliPay->setMethod($method);
    $sHtml = $aliPay->doPay();
    exit(json_encode(array(
        'result' => true,
        'msg' => '新增订单成功',
        'url' =>  $sHtml
    )));
}


/**
 * 更新产品信息
 *
 * @return  [type]  [return description]
 */
function jiutu_update_products($post_id, $fictitious, $data)
{
    add_post_meta($post_id, 'qd_orders_user', get_current_user_id());
    add_post_meta($post_id, 'qd_orders_status',  1);
    add_post_meta($post_id, 'qd_orders_goods', $data['qd_orders_goods']);
    if ($fictitious == '1') {
        add_post_meta($post_id, 'qd_orders_voucher', $data['qd_orders_voucher']);
    } else {
        add_post_meta($post_id, 'qd_orders_data', array(
            'name'      => $data['name'],
            'telephone' => $data['telephone'],
            'address'   => $data['address']
        ));
    }
}



/**
 * 通知处理
 *
 * @param   [type]  $parameter  [$parameter description]
 *
 * @return  [type]              [return description]
 */
function qd_payment_handle($parameter)
{
    $admin = get_option('qd_admin');
    // var_dump($admin);
    $aliPay = new RWMB_Alipay();
    $aliPay->set_alipayPublicKey($admin['alipay']['alipaypublickey']);
    $result = $aliPay->rsaCheck($parameter, $parameter['sign_type']);
    if ($result === true) {
        return true;
    } else {
        return false;
    }
}




add_filter('rwmb_meta_boxes', function ($meta_boxes) {
    $prefix = 'sfsf';
    $meta_boxes[] = array(
        'title'      => '内容付款可见',
        'post_types' => array('post'),
        'autosave'   => true,
        'fields'     => array(
            array(
                'id'        => 'enable_slider',
                'name'      => '功能开关?',
                'type'      => 'switch',
                'style'     => 'rounded',
                'on_label'  => 'Yes',
                'off_label' => 'No',
            ),
            array('type' => 'textarea', 'name' => '商品介绍', 'id' => $prefix . 'introduce'),
            array('type' => 'divider'),
            array('type' => 'text', 'name' => '商品金额', 'id' => $prefix . 'price', 'size' => 15),
            array('type' => 'text', 'name' => '库存', 'id' => $prefix . 'stock', 'size' => 15, 'std' => 999),
            array('type' => 'text', 'name' => '已出售', 'id' => $prefix . 'sell', 'size' => 15, 'std' => 0),
            array('type' => 'divider'),
            array('type' => 'checkbox', 'name' => '虚拟商品', 'id' => $prefix . 'fictitious'),
            array('type' => 'textarea', 'name' => '虚拟商品信息', 'id' => $prefix . 'fictitious_data', 'desc' => '上方打勾后,用户购买成功后使用凭证查询将返回此信息')

        )

    );

    return $meta_boxes;
});

/**
 * 插件激活期间运行的代码。
 *
 * @return  [type]  [return description]
 */
register_activation_hook(__FILE__, function () {
    jiutu_qingdong_weixin_send('商店插件被激活');
});


/**
 * 插件停用期间运行的代码。
 *
 * @return  [type]  [return description]
 */
register_deactivation_hook(__FILE__, function () {

    jiutu_qingdong_weixin_send('商店插件被停用');
});


/**
 * 微信通知
 *
 * @param   [type]  $title    [$title description]
 * @param   [type]  $content  [$content description]
 *
 * @return  [type]            [return description]
 */
function jiutu_qingdong_weixin_send($title, $content = '通知:')
{
    $request = new WP_Http;
    $request->request('https://wpapi.aliluv.cn/wp-admin/admin-ajax.php', array(
        'method' => 'GET',
        'body' => array(
            'action' => 'jiutu_weixin_send',
            'title' => $title,
            'content' => $content . date("Y-m-d H:i:s", time())
        )
    ));
}
