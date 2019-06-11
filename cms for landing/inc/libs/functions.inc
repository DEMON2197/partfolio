<?php





function sql_error ($sql = '', $link = false, $file = '', $line = '') {
    if (!$link) { 
        global $link;
    }
    echo "[query: $sql <br>error: ".mysql_error ($link);
    echo ($file != '' && $line != '') ? '<br>in file: '.$file.' at line: '.$line : '';
    echo "]\n";
}

?>