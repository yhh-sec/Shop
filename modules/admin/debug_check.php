<?php
/*
 * 调试脚本：用于诊断管理员权限验证问题
 */

// 启动会话
session_start();

// 引入配置和公共函数
require_once('../../config/config.php');
require_once('../../common/common.php');

// 显示当前会话状态
echo "<h2>管理员权限验证调试信息</h2>";
echo "<p>Session Status: " . session_status() . "</p>";
echo "<p>Session ID: " . session_id() . "</p>";
echo "<p>Session Name: " . session_name() . "</p>";

// 显示会话变量
echo "<h3>当前会话变量:</h3>";
echo "<pre>";
print_r($_SESSION);
echo "</pre>";

// 检查必要的会话变量
echo "<h3>权限验证检查:</h3>";
if (isset($_SESSION['user_id'])) {
    echo "<p style='color:green;'>✓ user_id 已设置: " . $_SESSION['user_id'] . "</p>";
} else {
    echo "<p style='color:red;'>✗ user_id 未设置</p>";
}

if (isset($_SESSION['role'])) {
    echo "<p style='color:green;'>✓ role 已设置: " . $_SESSION['role'] . "</p>";
    
    if ($_SESSION['role'] == 1) {
        echo "<p style='color:green;'>✓ 角色是管理员 (role=1)</p>";
    } else {
        echo "<p style='color:red;'>✗ 角色不是管理员 (role=" . $_SESSION['role'] . ")</p>";
    }
} else {
    echo "<p style='color:red;'>✗ role 未设置</p>";
}

// 手动调用权限验证函数并捕获输出
echo "<h3>权限验证函数测试:</h3>";
echo "<p>调用 checkLogin(1) 的结果:</p>";

// 临时关闭重定向以便观察结果
ob_start();
checkLogin(1);
$redirect_output = ob_get_contents();
ob_end_clean();

if (empty($redirect_output)) {
    echo "<p style='color:green;'>✓ 权限验证通过，无重定向</p>";
} else {
    echo "<p style='color:red;'>✗ 权限验证失败，触发重定向</p>";
    echo "<p>重定向输出: " . htmlspecialchars($redirect_output) . "</p>";
}

echo "<p><a href='seller_list.php'>点击这里测试访问商家列表页面</a></p>";
echo "<p><a href='../user/login.php'>返回登录页面</a></p>";
?>