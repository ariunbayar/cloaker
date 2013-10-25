<?php
function login_controller()
{
    if (isset($_POST['login']))
    {
        $loginQuery = mysql_query("SELECT id, user_level FROM users WHERE username = '".mysql_real_escape_string($_POST['username'])."' AND password = '".md5($_POST['password'])."'");
        if (mysql_num_rows($loginQuery) == 1)
        {
            $user = mysql_fetch_array($loginQuery);
            $_SESSION['logged_in'] = true;
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_level'] = $user['user_level'];
            header('Location: '.ADMIN_URL);
            exit;
        }
    }
    View('login');
}


function logout_controller()
{
    unset($_SESSION['logged_in']);
    header('Location: '.ADMIN_URL);
    exit;
}
?>
<?php // {# vim: set ts=4 sw=4 sts=4 fdn=20 : #} ?>
