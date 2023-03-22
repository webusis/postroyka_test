<?php
include 'components/autoload.php';

use models\People;
use components\Views;
use components\Mail;
use helpers\SignatureLetter;

$people = new People();
/**
 * Добавление записи в соответсвии с атриибутами
 * Может быть список полей из таблиц
 */
if(isset($_POST['save'])){
    $people->setAttributes($_POST);
    $data = $people->create();
    if(empty($data['errors'])){
        header('Location: /');
    }

}

/**
 * Удаление записи в соответсвии с атриибутами
 * Может быть список полей из таблиц
 */
if(isset($_POST['delete'])){
    $people->setAttributes($_POST);
    $people->delete();
    header('Location: /');
}

$people = new People();

/**
 * Принимаются 3 условия !=|<|>
 * Массив для сетера подходит для методов delete, find
 * [id => [!=|<|> => 'что-то']] [fname => [!= => 'что-то']] etc.
 */
$people->customSearch = [
    'id' => [
        '!=' => 2,
        '>' => 3
    ]
];

$data['mail_template'] = Views::show('mail/content', [], false);
$data['mail_signature'] = Views::show('mail/signature', [], false);

if(isset($_GET['setTmlm'])){
    setMailTemplate();
}

/**
 * Формирует шаблон писма исходя из выбраных отправителей из bd
 * @return void
 * @throws \components\ExceptionHandler
 */
function setMailTemplate() : void
{
    $data = $_POST;
    $people = new People();
    $people->customSearch = [
        'id' => [
            '>' => 3
        ]
    ];
    $data['people'] = $people->find();

    $data['cltr'] = $people->clTr();
    $sign = SignatureLetter::cases()[$data['mail_signature']];

    $data['sign_data'] = [];
    (isset($data['people_ids'])) ? $people->id = $data['people_ids'] : [];
    $s_people = $people->find();
    foreach ($s_people as $people) {
        $data['sign_data']['phones'][] = $people['phone'];
        $data['sign_data']['emails'][] = $people['email'];
    }

    $data['mail_template'] = Views::show('mail/content_' . $data['mail_template'], $data, false);
    $data['mail_signature'] = Views::show('mail/signature_' . $sign->SignColor(), $data, false);
    $crEmail = Views::show('create_mail', $data, false);

    if(isset($data['send']) && $data['send'] !== '0'){
        Mail::$to = 'webusis@gmail.com';
        Mail::$from = implode(',', $data['sign_data']['emails']);
        Mail::$subject = 'Проверка';
        Mail::$content = $crEmail;

        Mail::send();
    }

    die($crEmail);
}


/**
 * Выхлоп в шаблон принимает сам шаблон name и передоваемые параметры в него
 */

$data['people'] = $people->find();
$data['cltr'] = $people->clTr();
$data['mail_create'] = Views::show('create_mail', $data, false);
Views::show('wrap', $data, true);





