<?php
// 先引入会话配置
require_once('../../common/session_init.php');

// 再启动会话
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once('../../config/config.php');
require_once('../../common/common.php');
// 权限校验：仅商家可访问
checkLogin(2);

$seller_id = $_SESSION['user_id'];

// 获取商品ID
$goods_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($goods_id > 0) {
    // 验证商品是否属于当前商家
    $check_sql = "SELECT * FROM goods WHERE id = ? AND seller_id = ?";
    $check_stmt = $pdo->prepare($check_sql);
    $check_stmt->execute([$goods_id, $seller_id]);
    $goods = $check_stmt->fetch(PDO::FETCH_ASSOC);

    if ($goods) {
        // 删除商品
        $delete_sql = "DELETE FROM goods WHERE id = ? AND seller_id = ?";
        $delete_stmt = $pdo->prepare($delete_sql);
        $delete_stmt->execute([$goods_id, $seller_id]);
        
        // 跳转回商品列表
        header("Location: goods_list.php?msg=删除成功");
        exit;
    } else {
        die('商品不存在或无权删除');
    }
} else {
    header("Location: goods_list.php?msg=无效的商品ID");
    exit;
}
?>