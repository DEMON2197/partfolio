	#!/usr/bin/php -q
<?php
/**
 * Актуализация ответственных по договоам балансодержателей
 *
 * @author Dmitry Rasskazov
 * @ticket ECC-6318
 */

include_once(__DIR__.'/../../../app/lib/php/cron.php');

class HoldersOtvUpdate extends xCron {

  function start() {
    $pid = $this->getKeyParam('pid');

    if ($pid == 'updateOtvDoc') {
      // Актуализация ответственных по договору балансодержателей
      $result = $this->updateOtvDocs();
      if($result === true) {
        $this->_setState(true);
        return;
      } else {
        $this->_setState($result);
      }     
    }
  }

    
  /**
   * Обновление ответственных по договорам балансодержателей
   * @author Dmitry Rasskazov
   * @ticket ECC-6318
   * @return mixed
   */
  function updateOtvDocs() {

    $xServices       = FC('services');
    $xClients        = FC('clients');
    $xEmp            = FC('emp');
    $xDocs           = FC('docs');

    FC()->logger->info('[HOLDERS_UPDATE] Начало актуализации ответственных по договорам балансодержателей.', logger::TYPE_DB);

    $services = $xServices->cl_services->get([
      'service_id'   => \xServices::SERVICE_ID_ASSET_HOLDER,
      'status_id !=' => \xServices::SERVICE_STATUS_CLOSED
    ]);

    if(!$services) {
      return 'Не найдены незавершённые услуги по сотрудничеству с балансодержателем!';
    }

    FC()->db->begin();
    foreach($services as $service) {
      // Получаем актуальный договор клиента по балансодержателям, с ненулевой суммой
      $docs = $xDocs->get([
        'storona2' => $service->client_id,
        'type_id'  => \xDocs::DOCUMENT_TYPE_CONTRACT,
        'name_id'  => \xDocs::DOCUMENT_NAME_HOLDERS_COOPERATION,
        'actual'   => 't',
        'summa !=' => 0
      ]);
      
      if(!empty($docs)) {
        $cl_service = $xServices->cl_services->one($service);
        $cl_service->save([
          'emp_id' => $docs->emp_soprov
        ]);
      }
      echo $docs->id; echo "\n";
    }
    FC()->db->commit();

    FC()->logger->info('[HOLDERS_UPDATE] Окончание актуализации.', logger::TYPE_DB);

    return true;
  }

}

(new HoldersOtvUpdate())->run();


==============================================================================================


/**
   * Обзвон клиентов КТВ из отчёта на отключение
   * @author Dmirty Rasskazov
   * @ticket ECC-6384
   */
  public function clientCalls() {
    FC('services,money,projects,clients');
    FC()->logger->info("Calls to debtors at the CTV");
    // Подключение к базе asterick для обзвона должников КТВ
    $db_aster = new db([
      'host'     => DEB_HOST,
      'user'     => DEB_USER,
      'password' => DEB_PASS,
      'dbname'   => DEB_BASE,
      'charset'  => DEB_CHAR,
      'new'      => NULL
    ]);
    $prev_moth_time = mktime(0, 0, 0, date("m") - 1, 1 ,date("Y"));
    $ym = date("Ym", $prev_moth_time);
    $prev_moth_table = "garant_ctv_{$ym}";

    // проверка наличия плана запуска в этом месяце
    $q_check = $db_aster->query("
      SELECT
        id
      FROM public.autodial 
      WHERE 
        date_month(starting::date) = date_month(now()::date) 
        and date_month(ending::date) = date_month(now()::date)
        and type = 3 -- КТВ
    ");
    $check_at_month = $q_check->row();

    // если нет плана запуска на текущий месяц - шлем пламенный аларм в АО
    if (!$check_at_month) {
      $managers = FC('configs')->item('ao_main_managers');
      foreach ($managers as $manager) {
        FC('messenger')->mm($manager->value, "Необходимо запланировать дату и время КТВ на текущий месяц!!!");
        FC('emp')->mail($manager->value, 'Уведомление. Необходимо запланировать дату и время КТВ на текущий месяц!!!');
      }
      return;
    }

    // берем план запуска на текущий месяц
    $q_check = $db_aster->query("
      SELECT
       id
      FROM public.autodial 
      WHERE starting::date = now()::date and ending > now()
        and type = 3");
    $check_at = $q_check->row();

    if (!$check_at) {
      return;
    }

    // Запрос взят из отчёта 'noPayKtvForCreateProject'
    $sql = "
      WITH tmp_pays_itog AS (
        SELECT client_id, ROUND(SUM(summa)::numeric) AS \"summa\"
        from cl_payments_itog
        WHERE client_id in (select client_id from cl_services where service_id = " . \xServices::SERVICE_ID_CABLE_TV . " ) -- добавил некую оптимизацию
        AND client_id NOT IN (33, 8518, 147136)
        AND service_id = " . \xMoney::ACNT_CABLE_TV . "
        GROUP BY client_id
        HAVING SUM(summa) <= " . \xMoney::SUMM_DEBT_GARANT_CTV . "
      )
      SELECT
        cl.id AS client_id,
        COALESCE(phone.number::bigint, client.sms_to_phone::bigint) AS phone,
        pays.summa AS summa
      FROM clients.search_info cl
        INNER JOIN address.search_info  addr  ON cl.address_id = addr.id
        INNER JOIN cl_services        clserv  ON cl.id = clserv.client_id AND clserv.service_id = " . \xServices::SERVICE_ID_CABLE_TV . " 
                                                                          AND clserv.status_id not in (
                                                                            " . \xServices::SERVICE_STATUS_SUSPEND . ", 
                                                                            " . \xServices::SERVICE_STATUS_CLOSED . "
                                                                          )
        INNER JOIN tmp_pays_itog        pays  ON cl.id = pays.client_id
        INNER JOIN clients            client  ON cl.id = client.id
        LEFT  JOIN client_phones       phone  ON cl.id = phone.client_id
        LEFT  JOIN clients as cl2_check ON cl2_check.address_id = client.address_id and cl2_check.kvart = client.kvart and cl2_check.id != client.id and cl2_check.deleted = false
      WHERE true
        AND cl.station_id IN (
          " . \xClients::CLIENT_STATION_ASBEST . "
        )
        AND client.id NOT IN (
      select
          pma.client_id
          from
            projects.pr_main pma
          INNER JOIN projects.subprojects spa ON spa.project_id = pma.id AND spa.type_id = 160 AND spa.status_id IN (
              " . \xProjects::STATUS_CREATING . ",
              " . \xProjects::STATUS_WAITING . ",
              " . \xProjects::STATUS_IN_PROCESS . "
            )
          WHERE true --pma.client_id in (select id from clients where t_kl = 't')
      )
      GROUP BY cl.id, phone.number, client.sms_to_phone, pays.summa
      HAVING phone.number IS NOT NULL
      ORDER BY cl.id";
    $clients = FC()->db->query($sql)->result();
    $result  = $db_aster->query("SELECT * FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_NAME = '{$prev_moth_table}'")->result();

    if($result) {
      $db_aster->query("DELETE FROM garant_ctv");
    } else {
      FC()->logger->info("Table {$prev_moth_table} not exists. Create it.");
      $db_aster->begin();
      $db_aster->query("
        CREATE TABLE {$prev_moth_table} AS
        SELECT * FROM garant_ctv
      ");
      $db_aster->query("DELETE FROM garant_ctv");
      $db_aster->commit();
    }
  
    $count = 0;
    $error = 0;
    foreach ($clients as $item) {
      $res = $db_aster->insert("garant_ctv", [
        'client_id'   => $item->client_id,
        'phone'       => $item->phone,
        'rec_payment' => '01',
        'balance'     => "{$item->summa}"
      ]);
      if (!$res) {
        $error++;
      }
      $count++;
    }
    FC()->logger->info("Count: {$count} / failed: {$error}.");
    

  }

==================================================================================================================================



/**
   * Устанавливает статус ОТК. таблица projects.works, в значение true, создаёт подпроект и работу в нём (ОТК), и связывает подпроект ОТК
   * @author Rasskazov Dmitry
   * @return void
   */
    public function setOTK(){

      $work_id = $this->input->post('work_id');

      if(!$work_id) {
        echo get_json(['error' => 'Не передан план работ для изменения']);
        return;
      }

      $xProjects = FC('projects');  //Модель с проектами

      //Получаем работу
      $work = $xProjects->work->one(['id' => $work_id]);

      //Проверить, существует ли такая работа
      if(!$work){
        echo get_json(['error' => 'Не существует такой план работ']);
        return;
      }

      if($work->otk == 't') {
        echo get_json(['error' => 'Нвозможно переназначить статус ОТК']);
        return;
      }

      $subproject = $xProjects->subproject->one([
        'id' => $work->subproject_id
      ]);

      if(!$subproject){
        echo get_json(['error' => 'Ошибка! Не существует подпроект с тиким ID']);
        return;
      }

      $address_info = FC('address')->home_search_info->one([
        'id' => $subproject->address_id
      ]);

      if(!$address_info && 0){
        echo get_json(['error' => 'Ошибка! Не существует информаци по адресу']);
        return;
      }

      $project = $xProjects->one(['id' => $subproject->project_id]);
      if(!$project){
        echo get_json(['error' => 'Ошибка! Не существует такого проекта']);
        return;
      }

      $project = $xProjects->values_of_subprojects->one([
        'subproject_id'  => $subproject->id,
        'field_id'       => \xProjects::SUBPROJECT_FIELD_OTK
      ]);
      if($project){
        echo get_json(['error' => 'Для этой работы уже создан подпроект "Проверка ОТК"']);
        return;
      }

      FC()->db->begin();
      //Обновление статуса работы
      try {

        $work->save(['otk' => 't']);

        //Создаём новый подпроект "Проверка ОТК"
        $sub_id = $xProjects->subproject->save([
          'project_id' => $subproject->project_id,
          'name'       => 'Проверка ОТК',
          'address_id' => $subproject->address_id,
          'type_id'    => \xProjects::SUBPROJECT_TYPE_CHECK_OTK, //Тип подпроекта 'Проверка ОТК'
          'emp_otv_id' => $this->emp_id,
          'status_id'  => \xProjects::WORK_STATUS_IN_PROCESS     //Статус подпроекта "Выполняется"
        ]);

        $sub_field = $xProjects->values_of_subprojects->save([
          'subproject_id'  => $subproject->id,
          'text_value'     => $sub_id,
          'field_id'       => \xProjects::SUBPROJECT_FIELD_OTK,
          'project_id'     => $subproject->project_id
        ]);

        $emp_info = FC('emp')->emp->one(['id' => $this->emp_id]);
        if(!$emp_info){
          echo get_json(['error' => 'Нет информации по сотруднику']);
          return;
        }

        $otk_work = $xProjects->work->save([
          'subproject_id'  => $sub_id,
          'worktype_id'    => \xProjects::WORK_TYPE_CHECK_OTK,            // Тип работы Проверка ОТК
          'data'           => date("Y-m-d"),                              // Загружаем текущую дату с нулевым временем
          'times'          => '00:00',
          'status_id'      => \xProjects::WORK_STATUS_WAITING,            // Установить статус ожидание
          'event'          => serialize([
            'address'     => $address_info . ", кв. " . $project->kvart,
            'times'       => '00:00',
            'wid'         => $work_id,
            'workers'     => $emp_info->name                              // Имя сотрудника, выполняющего проверку
          ]),
          'act'            => serialize(['act_name' => "Проверка ОТК"]),  // Установка имени акта
          'in_dayplan'     => 't'
        ]);

        //Добавляем работника к подпроекту
        $worker_id = $xProjects->workers->save([
          'work_id' => $otk_work,
          'emp_id'  => $this->emp_id
        ]);

      }catch (Exception $e){
        FC()->db->rollback();
        echo $e->getMessage();
        return;
      }
      FC()->db->commit();
      //Отправка ответа клиенту
      echo get_json(['result' => $otk_work]);

    }
	
===================================================================================================================================


<?php

namespace Emps\UserBundle\Controller;

use Backend\FormsBundle\Model\FormsConstructorQuery;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\View;
use mysql_xdevapi\Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use Frontend\AppBundle\Annotation\Secure;

//Класс обработчик формы по умолчанию класс контроллера формы
use Backend\FormsBundle\Controller\FormController;
use Symfony\Component\HttpFoundation\Response;

/**
 * @RouteResource("CallBackRequest")
 * @author Dmitry Rasskazov
 */
class CallBackRequestController extends FOSRestController
{

    /**
     * @View()
     * @Secure(roles="ROLE_MAIN_ESS2")
     * @param Request $request
     * @return JsonResponse
     * @author Dmitry Rasskazov
     *
     * Получает данные от формы заявки на обратный звонок, посылает стандартному обработчику и добавляет комментарий в карточку клиента
     */
    public function postCommentAction(Request $request)
    {
      $process = $this->get('JSONService')->initProcess();
      $inputs = $process->fetchDataFromRequest();

      if(isset($inputs['action']) && $inputs['action'] != 'add') {
        $process->setError("Ошибка выполнения запроса 'Неверное действие'. Обратитесь в технический отдел.");
        return $process->response();
      }

      foreach($inputs['row'] as $field) {
        if(isset($field['Input']) && !!$field['Input']) {
          $data[$field['Name']] = $field['Input'];
        }
      }
      FC()->db->begin();
      try{
        if($data['client_id']) {
          // Создать заявку на перезвон
          $request_id = FC('clients')->callback_request->save($data);

          //Добавить комментарий в карточку клиента об оставленной заявке
          FC('clients')->comment->save(array(
            'comment' => "Cоздана заявка на перезвон №" . $request_id . ". <a href=\"/v5/app/#/reports/reportAoRecall/\" target=\"top\">Таблица заявок</a>",
            'client_id' => $data['client_id'],
            'emp_id'    => $data['emp_creator_id'],
            'type_id'   => \xClients::COMMENT_TYPE_COMMERCIAL
          ));

          FC()->logger->info("[FORM] Вставка записи в таблицу clients.callback_request. Данные: " . \library_template::varToLog($data));
        } else {
          throw new Exception("Не передан ID клиента");
        }
        FC()->db->commit();
      }catch (\Exception $e){
        $message = $e->getMessage();
        if (strpos($message, 'повторяющееся значение ключа нарушает ограничение уникальности') !== false) {
          $message = 'строка с данными, которые вы указали, уже существует';
        }
        FC()->db->rollback();
        $process->setError("Ошибка: {$message}");
        return $process->response();
      }

      $form = FormsConstructorQuery::create()
        ->leftJoinWith('FormsField')
        ->findById($inputs['form_id'])
        ->toArray();

      if (count($form) == 0) {
        $process->setError('Указанная форма не найдена');
        return $process->response();
      }

      $process->setArray($form);
      return $process->response();
    }

  /**
   * Проверка на существование необработанных заявок
   * ticket 5740
   * @author Dmitry Rasskazov
   * @param Request $request
   * @Secure(roles="ROLE_MAIN_ESS2")
   * @return JsonResponse
   */

  public function postCheckrequestAction(Request $request)
  {
    FC('emp,clients/requests');
    $process = $this->get('JSONService')->initProcess();
    $inputs = $process->fetchDataFromRequest();


    if(isset($inputs['action']) && $inputs['action'] != 'add') {
      $process->setError("Ошибка выполнения запроса 'Неверное действие'. Обратитесь в технический отдел.");
      return $process->response();
    }

    foreach($inputs['row'] as $field) {
      if(isset($field['Input']) && !!$field['Input']) {
        $data[$field['Name']] = $field['Input'];
      }
    }

    $checkRequest = FC('clients')->callback_request->one([
      'client_id'     => $data['client_id'],
      'dept_id'      => \xEmp::DEPT_STP,
      'status_id !=' => \xRequests::REQUEST_CALLBACK_STATUS_DONE
    ]);

    if($checkRequest) {
      $process->setError("Существует необработыннй запрос на перезвон для этого клиента!");
      return $process->response();
    }

    FC()->db->begin();
    try{
      if($data['client_id']) {
        // Создать заявку на перезвон
        $request_id = FC('clients')->callback_request->save($data);

        FC()->logger->info("[FORM] Вставка записи в таблицу clients.callback_request. Данные: " . \library_template::varToLog($data));
      } else {
        throw new Exception("Не передан ID клиента");
      }
      FC()->db->commit();
    }catch (\Exception $e){
      $message = $e->getMessage();
      if (strpos($message, 'повторяющееся значение ключа нарушает ограничение уникальности') !== false) {
        $message = 'строка с данными, которые вы указали, уже существует';
      }
      FC()->db->rollback();
      $process->setError("Ошибка: {$message}");
      return $process->response();
    }

    // Подгрузка формы
    $form = FormsConstructorQuery::create()
      ->leftJoinWith('FormsField')
      ->findById($inputs['form_id'])
      ->toArray();

    if (count($form) == 0) {
      $process->setError('Указанная форма не найдена');
      return $process->response();
    }

    $process->setArray($form);
    return $process->response();
  }
}


=================================================================================================================


/**
   * Отправить сообщение на email, либо sms на телефон с контактными данными
   * ticket 5486
   * @author Dmitry Rasskazov
   *
   * @Secure(roles="ROLE_MAIN_ESS2")
   * @Route("/app/api/requestconnect/postsendinstantmessage", name="send_message_from_request", methods={"post"})
   *
   * @return object
   */
  public function postSendInstantMessage(){
    $process = $this->get('JSONService')->initProcess();
    $request = $process->fetchDataFromRequest();

    //Првоеряем принятые параметры
    if(empty($request['id']) || empty($request['type_id']) || empty($request['value']) || empty($request['request_id'])) {
      $process->setError('Не передан контакт для отправки');
      return new JsonResponse($process->responseArray());
    }

    $request_id = $request['request_id'];
    $type_id    = $request['type_id'];
    $contact    = $request['value'];

    $crm = FC('clients/crm');

    if($type_id == \xCrm::REQUEST_CONTACT_TYPE_EMAIL && $type_id == \xCrm::REQUEST_CONTACT_TYPE_PHONE) {
      $process->setError('Передан неверный тип контактных данных');
      return new JsonResponse($process->responseArray());
    }

    $params_crm = [
      'request_id'  => $request_id,
      'contact'     => $contact,
      'type'        => ($type_id == \xCrm::REQUEST_CONTACT_TYPE_PHONE ? 'sms' : 'email')
    ];

    $result = $crm->sendEmpContactMessage($params_crm);

    $process->setArray($result);
    $result = $process->responseArray();
    return new JsonResponse($result);
  }
  
  
=======================================================================================================================


