<br>-----------<br>
<div class="green">
    E-mail:
    <?php foreach($sign_data['emails'] as $email):?>
        <br><a href="mail:<?= $email?>"><?= $email?></a>
    <?php endforeach;?>
    <br>Телефон:
    <?php foreach($sign_data['phones'] as $phone):?>
        <br><a href="phone:<?= $phone?>"><?= $phone?></a>
    <?php endforeach;?>
</div>