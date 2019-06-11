<?php
$site_from      = 'sale@brus-market.ru';
$site_webmaster = 'sale@brus-market.ru';

if ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {

    $name    = to_html(trim(mb_convert_encoding($_POST['name'], 'CP1251', 'UTF-8')));
    $phone   = to_html(trim(mb_convert_encoding($_POST['phone'], 'CP1251', 'UTF-8')));
    
    require ('htmlMimeMail5/htmlMimeMail5.php');

    $mail = new htmlMimeMail5();

    $mail->setTextCharset('windows-1251');
    $mail->setHtmlCharset('windows-1251');
    $mail->setHeadCharset('windows-1251');

    $mail->setFrom($GLOBALS['site_from']);
    $mail->setSubject('[Fundament] - запрос');

    $mail_html = "
    Имя: $name<br>
    Телефон: $phone<br>
    ";

    $mail->setHtml($mail_html);
    
    $to = array($GLOBALS['site_webmaster']);	
    $result = $mail->send($to);

    
    if ($result) {
        echo "1";
    }
    else {
        echo "0";
    }

}

function to_html($shtml) {
    $shtml = str_replace ("&","&amp;",$shtml);
    $shtml = str_replace ("'","&lsquo;",$shtml);
    $shtml = str_replace ("`","&rsquo;",$shtml);
    $shtml = str_replace ("\"","&quot;",$shtml);
    $shtml = str_replace (">","&gt;",$shtml);
    $shtml = str_replace ("<","&lt;",$shtml);
    return $shtml;
}
