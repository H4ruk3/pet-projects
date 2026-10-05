<?= $this->start('auth'); ?>
<?= $this->element('Auth/login'); ?>
<?= $this->end(); ?>

<?= $this->start('title'); ?>
Главная
<?= $this->end(); ?>
<div class="whitebg">
<div id="todo">
    <div class="todo-text">
        Что необходимо сделать?
    </div>
    <div class="todo-a color-blue">
        Внести личные данные
    </div>
    <div class="todo-a color-blue">
        Указать цель занятий
    </div>
    <img id="muscular" src="/img/muscular.png">
</div>
</div>

<div id="content">
<div id="about-service">
    <div id="about-service-text">
        Что делает сервис
    </div>

    <div class="service-iteration left1">
        <img class="service-iteration-icon" src="/img/physiology.png">
        <div class="service-iteration-number">1</div>
        <h3 class="service-iteration-text">Определяет физиологические особенности</h3>
    </div>

    <div class="service-iteration left2">
        <img class="service-iteration-icon" src="/img/routine.png">
        <div class="service-iteration-number">2</div>
        <h3 class="service-iteration-text">Составляет распорядок дня</h3>
    </div>

    <div class="service-iteration left3">
        <img class="service-iteration-icon" src="/img/feeding program.png">
        <div class="service-iteration-number">3</div>
        <h3 class="service-iteration-text">Формирует программу питания</h3>
    </div>

    <div class="service-iteration left4">
        <img class="service-iteration-icon" src="/img/training facilities.png">
        <div class="service-iteration-number">4</div>
        <h3 class="service-iteration-text">Составляет тренировочный комплекс</h3>
    </div>

    <div class="service-iteration left5">
        <img class="service-iteration-icon" src="/img/training program.png">
        <div class="service-iteration-number">5</div>
        <h3 class="service-iteration-text">Дает рекомендации, подсказки и указания</h3>
    </div>
    <img id="thin" src="/img/thin.png">

</div>
</div>