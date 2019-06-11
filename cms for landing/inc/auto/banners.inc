<?php
/**
* Форма обратной связи
*
*/

$site_contact_email = array($GLOBALS['site_webmaster']);

if (EDITOR) {
    $registered_functions['feedback_form'] = "Форма обратной связи";
}

/**
 * Форма обратной связи
 *
 * @param string $required_fields_param список обязательных полей
 * @return string HTML
 */
function feedback_form($required_fields_param = 'name,email,add_info') {
    global $smarty, $http_category_values;

    /**
    * обязательные поля
    */
    $required_fields = array_map('trim', explode(',', $required_fields_param));
    $smarty->assign('feedback_required', $required_fields);
    $smarty->assign('current_category', $GLOBALS['current_category']);

    $act = isset($http_category_values[0]) ? trim($http_category_values[0]) : '';
    if ($_POST['act2'] == 'send') {
		
        /**
        * получатель сообщения
        */
        $msg_recipients = $GLOBALS['site_contact_email'];

        $msg_subj = "[ЗАЯВКА С САЙТА] - анкета";
        $msg_from = (isset($_POST['email']) && trim($_POST['email']) != '' && preg_match("/^[^@\s]+@([-a-z0-9]+\.)+[a-z]{2,}$/i", trim($_POST['email']))) ? trim($_POST['email']) : $GLOBALS['site_webmaster'];

        $feedback_error = array();
        foreach ($required_fields as $fbr) {
            if (!isset($_POST[$fbr]) || trim($_POST[$fbr]) == '') {
                 $feedback_error[$fbr] = 'empty';
            }
        }
        $smarty->assign('feedback_error', $feedback_error);

        $smarty->assign('form', $_POST);

        if (count($feedback_error) == 0) {
            require_once (SITE_DIR.'/htmlMimeMail.php');
            $mail = new htmlMimeMail();
            $mail->setFrom($msg_from);
            $mail->setSubject($msg_subj);
            $mail->setText($smarty->fetch('feedback_mail_text.tpl'));
            $mail->setHtml($smarty->fetch('feedback_mail_html.tpl'));
            $result = $mail->send($msg_recipients, $GLOBALS['site_mail_protocol']);

            if (!$result) {
                $message  = "<h3>Ошибка отправки письма</h3>\n";
                $message .= "Адрес емайл: ".$msg_from."<br>\n";
                $message .= "<ul><li>".implode("\n<li>", $mail->errors)."\n<ul>";
                $smarty->assign('feedback_message', $message);
            }
            else {
                $smarty->assign('feedback_status_send', true);
            }
        }
    }

    return $smarty->fetch('feedback.tpl');
}
