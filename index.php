<?php
//入口
    define('THINK_PATH','./ThinkPHP'     );
    define('APP_NAME','Home');
    define('APP_PATH','./Home');     
    require(THINK_PATH.'/ThinkPHP.php');
    
    $App= new App();
    $App->run();
?>