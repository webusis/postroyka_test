<link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-3.6.0.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
<script>
    $(function() {
        $.datepicker.regional['ru'] = {
            closeText: 'Закрыть',
            prevText: 'Предыдущий',
            nextText: 'Следующий',
            currentText: 'Сегодня',
            monthNames: ['Январь','Февраль','Март','Апрель','Май','Июнь','Июль','Август','Сентябрь','Октябрь','Ноябрь','Декабрь'],
            monthNamesShort: ['Янв','Фев','Мар','Апр','Май','Июн','Июл','Авг','Сен','Окт','Ноя','Дек'],
            dayNames: ['воскресенье','понедельник','вторник','среда','четверг','пятница','суббота'],
            dayNamesShort: ['вск','пнд','втр','срд','чтв','птн','сбт'],
            dayNamesMin: ['Вс','Пн','Вт','Ср','Чт','Пт','Сб'],
            weekHeader: 'Не',
            dateFormat: 'yy-mm-dd',
            firstDay: 1,
            isRTL: false,
            showMonthAfterYear: false,
            yearSuffix: ''
        };

        $.datepicker.setDefaults($.datepicker.regional['ru']);
        $( "#birthday" ).datepicker();
    });
    function setLetter(subm = 0) {
        $.ajax({
            url: '/?setTmlm',
            method: 'post',
            dataType: 'text',
            data: {
                mail_signature: $('#mail_signature').val(),
                mail_template: $('#mail_template').val(),
                people_ids: $('#people_ids').val(),
                send: subm
            },
            success: function (data) {
                $('#mail_create').html(data);
                if(subm == 1){
                    alert('Письмо отправлено');
                }
            }
        });
    }
</script>
<style>
    .red, .red a {
        color: red;
    }
    .green, .green a {
        color: green;
    }
</style>
<table>
    <tr>
        <td><?= $cltr[0]?></td>
        <td><?= $cltr['fname']?></td>
        <td><?= $cltr['sname']?></td>
        <td><?= $cltr['birthday']?></td>
        <td><?= $cltr['gender']?></td>
        <td><?= $cltr['city']?></td>
        <td><?= $cltr['phone']?></td>
        <td><?= $cltr['email']?></td>
    </tr>
<?php foreach($people as $k => $p):?>
    <tr>
        <td><?= $p['id']?></td>
        <td><?= $p['fname']?></td>
        <td><?= $p['sname']?></td>
        <td><?= $p['birthday']?></td>
        <td><?= $p['gender'] ?></td>
        <td><?= $p['city']?></td>
        <td><?= $p['phone']?></td>
        <td><?= $p['email']?></td>
    </tr>
<?php endforeach;?>
</table>
<hr>
Создать
<form method="post" action="/">
    Имя <input name="fname" value="<?= (isset($_POST['fname'])) ? $_POST['fname'] : ''?>"><?= (isset($errors['fname'])) ? $errors['fname'] : ''?><br>
    Фамилия <input name="sname" value="<?= (isset($_POST['sname'])) ? $_POST['sname'] : ''?>"><?= (isset($errors['sname'])) ? $errors['sname'] : ''?><br>
    День рождения <input id="birthday" name="birthday" value="<?= (isset($_POST['birthday'])) ? $_POST['birthday'] : ''?>"><?= (isset($errors['birthday'])) ? $errors['birthday'] : ''?><br>
    Пол <select name="gender">
        <option value="1">Мужской</option>
        <option value="0">Женский</option>
    </select><br>
    Город <input name="city" value="<?= (isset($_POST['city'])) ? $_POST['city'] : ''?>"><?= (isset($errors['city'])) ? $errors['city'] : ''?><br>
    Телефон в формате +375255357505 <input name="phone" value="<?= (isset($_POST['phone'])) ? $_POST['phone'] : ''?>"><?= (isset($errors['phone'])) ? $errors['phone'] : ''?><br>
    E-mail <input name="email" value="<?= (isset($_POST['email'])) ? $_POST['email'] : ''?>"><?= (isset($errors['email'])) ? $errors['email'] : ''?><br>
    <input name="save" type="submit" value="Добавить">
</form>
<hr>
Удалить
<form method="post" action="/">
    ID <input name="id" value=""><br>
    <input name="delete" type="submit" value="Удалить">
</form>

<hr>
<select id="people_ids" multiple name="people_ids[]" onchange="setLetter()">
    <?php foreach($people as $k => $p):?>
        <option value="<?= $p['id']?>"><?= $p['fname'].' '.$p['sname']?></option>
    <?php endforeach;?>
</select>
Шаблон писма
<select id="mail_template" name="mail_template" onchange="setLetter()">
    <option value="0">Первый шаблон</option>
    <option value="1">Второй шаблон</option>
</select>
Шаблон подписи
<select id="mail_signature" name="mail_signature" onchange="setLetter()">
    <option value="0">Красный</option>
    <option value="1">Зеленый</option>
</select>
<hr>
<div id="mail_create">
    <?= $mail_create?>
</div>
<button onclick="setLetter(1)">Отправить</button>