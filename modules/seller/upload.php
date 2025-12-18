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

// 文件上传逻辑（增强安全性）
$error = '';
$img_path = '';
if (isset($_FILES['goods_img']) && $_FILES['goods_img']['error'] == 0) {
    $file = $_FILES['goods_img'];
    $file_name = $file['name'];
    $file_tmp = $file['tmp_name'];
    $file_size = $file['size'];
    $file_type = $file['type'];

    //添加文件上传漏洞点
    
    // 文件类型校验（只允许图片）
    // $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    // if (!in_array($file_type, $allowed_types)) {
    //     $error = '只允许上传 JPG、PNG、GIF、WebP 格式的图片！';
    // } 
    // // 文件大小校验（最大5MB）
    // elseif ($file_size > 5 * 1024 * 1024) {
    //     $error = '单个文件大小不能超过 5MB！';
    // } 
    // else {
        // 生成唯一的文件名
        $file_extension = pathinfo($file_name, PATHINFO_EXTENSION);
        $unique_filename = uniqid() . '_' . time() . '.' . $file_extension;
        
        // 设置上传目录
        $upload_dir = '../../static/upload/';
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
        $target_path = $upload_dir . $unique_filename;
        
        // 移动文件到目标位置
        if (move_uploaded_file($file_tmp, $target_path)) {
            // 保存相对路径到数据库（相对于网站根目录）
            $img_path = 'static/upload/' . $unique_filename;
            $error = '上传成功！';
        } else {
            $error = '上传失败！';
        }
//     }
} else {
    $error = '未选择文件或上传出错！';
}

// 返回上传结果（供goods_add.php调用）
echo json_encode([
    'code' => $error == '上传成功！' ? 200 : 400,
    'msg' => $error,
    'data' => $img_path
]);
exit;
?>