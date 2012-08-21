<?php
//后台管理入口
    define('THINK_PATH','./ThinkPHP'     );
    define('APP_NAME','Admin');
    define('APP_PATH','./Admin');     
    require(THINK_PATH.'/ThinkPHP.php');
    
    $App= new App();
    $App->run();
?>