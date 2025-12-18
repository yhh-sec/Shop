<?php
// 先引入会话配置
require_once('../../common/session_init.php');

// 再启动会话
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once('../../config/config.php');
require_once('../../common/common.php');
// 权限校验：仅管理员可访问
checkLogin(1);

// 获取商家ID
$seller_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($seller_id > 0) {
    // 检查商家是否存在且角色为商家(2)
    $check_sql = "SELECT * FROM user WHERE id = ? AND role = 2";
    $check_stmt = $pdo->prepare($check_sql);
    $check_stmt->execute([$seller_id]);
    $seller = $check_stmt->fetch(PDO::FETCH_ASSOC);

    if ($seller) {
        // 开始事务
        $pdo->beginTransaction();
        try {
            // 删除该商家的所有商品
            $delete_goods_sql = "DELETE FROM goods WHERE seller_id = ?";
            $delete_goods_stmt = $pdo->prepare($delete_goods_sql);
            $delete_goods_stmt->execute([$seller_id]);
            
            // 删除商家账户
            $delete_seller_sql = "DELETE FROM user WHERE id = ?";
            $delete_seller_stmt = $pdo->prepare($delete_seller_sql);
            $delete_seller_stmt->execute([$seller_id]);
            
            // 提交事务
            $pdo->commit();
            
            // 跳转回商家列表
            header("Location: seller_list.php?msg=删除成功");
            exit;
        } catch (Exception $e) {
            // 回滚事务
            $pdo->rollback();
            die("删除失败: " . $e->getMessage());
        }
    } else {
        die('商家不存在');
    }
} else {
    header("Location: seller_list.php?msg=无效的商家ID");
    exit;
}
?>