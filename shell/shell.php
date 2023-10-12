<?php
$dir = __DIR__."/../libs/src/open";
$target = __DIR__."/../src/open";
$namespace = "Nicelizhi\\Doudian\\Open\\";

function list_file($date, $target){
    global $namespace;
    //1、首先先读取文件夹
    $temp=scandir($date);
    //遍历文件夹
    foreach($temp as $v){
        $a=$date.'/'.$v;
        $b = $target."/".$v;
        
        if(is_dir($a)){//如果是文件夹则执行
        
            if($v=='.' || $v=='..'){//判断是否为系统隐藏的文件.和..  如果是则跳过否则就继续往下走，防止无限循环再这里。
                continue;
            }
            echo $a."\r\n";
            echo $b."\r\n";

            // 目录是否存在
            if(!file_exists($b)) {
                mkdir($b, 0777, true);
            }

            
            list_file($a, $b);//因为是文件夹所以再次调用自己这个函数，把这个文件夹下的文件遍历出来
        }else{
            echo $a,"\r\n";
           // var_dump(explode("/", $a));
            var_dump($b);
            //检查对应的新文件是否存在
            if(!file_exists($b)) {
                $acontent = file_get_contents($a);
                $ext_namespace = get_ext_namespace($a);
                $namespace2 =$namespace.implode("\\", $ext_namespace);

                $bcontent = str_replace("<?php","<?php \r\n Namespace ".$namespace2.";", $acontent); //

                $bcontent = str_replace("SignUtil::", $namespace."SignUtil::", $bcontent);
                $bcontent = str_replace("HttpClient::", $namespace."Core\\Http\\HttpClient::", $bcontent);


                file_put_contents($b, $bcontent);
                //var_dump($acontent, $bcontent);
                //exit;
                
            }
        }
        
    }
}
list_file($dir, $target);

function get_ext_namespace($path) {
    $path_arr = explode('/', $path);
    array_pop($path_arr);
    $ret = [];
    $add = 0;
    foreach($path_arr as $key=>$p_arr) {
        if($p_arr=="open") {
            $add = 1;
            continue;
        }

        if($add==1) {
            $ret[] = ucfirst($p_arr);
        }
        
    }
    return $ret;
}