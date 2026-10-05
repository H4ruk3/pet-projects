<?= $this->Html->css('/bootstrap/css/bootstrap.min.css') ?>

<?= $this->Html->script('jquery-1.10.0.min') ?>
<?= $this->Html->script('/bootstrap/js/bootstrap.min.js') ?>

<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?= $this->fetch('title') ?>
    </title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

    <?= $this->Html->css('frontpage.css') ?>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>

    <link rel="shortcut icon" type="image/x-icon" href="/img/favicon.ico">
</head>
<body>
<!-- Yandex.Metrika counter -->
<script type="text/javascript" >
   (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
   m[i].l=1*new Date();k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
   (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

   ym(53036506, "init", {
        clickmap:true,
        trackLinks:true,
        accurateTrackBounce:true,
        webvisor:true
   });
</script>
<noscript><div><img src="https://mc.yandex.ru/watch/53036506" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->
<div id="page">
    <div class="bg">
    <div id="header">
        <img id="header-logo" src="/img/logo header.png">
        <div id="header-contacts">
            <div class="header-contact-record">
                <i class="fa fa-phone fa-2x color-blue" aria-hidden="true"></i>
                <div class="header-contacts color-blue">+7 (4862) 42-36-12</div>
            </div>
            <div class="header-contact-record">
                <i class="fa fa-envelope-o fa-2x color-blue" aria-hidden="true"></i>
                <div class="header-contacts color-blue">support@coach-me.ru</div>
            </div>
            <div style="width:100%">
            <a href="https://play.google.com/store/apps/details?id=ru.itconcept.mypersonaltrainer" target="_blank" style="float:right"><img class="gp" src = "/img/gp.png"></img></a>
            </div>
        </div>
        <div id="navigation">
            <div class="current-navigation-page">
                <a href="auth/index">
                    <i class="fa fa-user fa-2x navigation-icon" aria-hidden="true"></i>
                    <div class="navigation-text">Войдите на сайт</div>
                </a>
            </div>
            <div class="current-navigation-page">
                <a href="auth/registration">
                    <div class="navigation-text">Зарегистрируйтесь</div>
                </a>
            </div>
        </div>
    </div>
    </div>

    <div id="carousel" class="carousel slide" data-ride="carousel">
  <div class="carousel-inner">
    <div class="item active slide1">
      <div class="container">
      <div class="row">
        <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 slidecontainer">
            <img src="/img/slide1-1.png" alt="...">
            <div class="frame">
            <H2>Не знаете с чего начать тренировки?</H2>
            <div class="rightblock">
                <ul>
                    <li>Боитесь идти в зал?</li>
                    <li>Хотите добиться поставленных целей?</li>
                </ul>
                <b>Тогда</b>
                <ul>
                    <li>Регистрируйтесь!</li>
                    <li>Создавайте тренировочные планы и <b>занимайтесь с ВЛТ!</b></li>
                    <li>Добивайтесь поставленных целей сами или с тренером!</li>
                </ul>
            </div>
            </div>
        </div>
      </div>
      </div>
    </div>
    <div class="item slide2">
      <div class="container">
      <div class="row">
        <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 slidecontainer">
            <img src="/img/slide2-1.png" alt="...">
            <div class="frame">
            <H2>Знаете как создать тренировочный план?</H2>
            <div class="rightblock">
                <ul>
                    <li>Любите и умеете тренировать?</li>
                    <li>Хотите увеличить клиентскую базу?</li>
                </ul>
                <b>Тогда</b>
                <ul>
                    <li>Содавайте учётную запись тренера!</li>
                    <li>Находите и добавляйте клиентов!</b></li>
                    <li>Создавайте для них тренировочные планы!</li>
                </ul>
            </div>
            </div>
        </div>
      </div>
      </div>
    </div>
    <div class="item slide3">
      <div class="container">
      <div class="row">
        <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 slidecontainer">
            <img src="/img/slide3-1.png" alt="...">
            <div class="frame">
            <H2>Дорожите репутацией клуба?</H2>
            <div class="rightblock">
                <ul>
                    <li>Хотите чтобы Ваши клиенты добивались поставленных целей?</li>
                    <li>Желаете привлечь клиентов новой услугой?</li>
                </ul>
                <b>Тогда</b>
                <ul>
                    <li>Регистрируйте фитнес-центр!</li>
                    <li>Добавляйте тренеров и клиентов в систему!</b></li>
                    <li>Отслеживайте работу тренеров и отзывы клиентов!</li>
                </ul>
            </div>
            </div>
        </div>
      </div>
      </div>
    </div>
  </div>
  <!-- Элементы управления -->
  <a class="left carousel-control" href="#carousel" role="button" data-slide="prev">
    <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
    <span class="sr-only">Предыдущий</span>
  </a>
  <a class="right carousel-control" href="#carousel" role="button" data-slide="next">
    <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
    <span class="sr-only">Следующий</span>
  </a>
</div>

    <!--<div id="authorization">
        <div id="auth-content">
            <h1 id="auth-first-string">Ваш личный тренер -</h1>
            <h2 id="auth-second-string">сервис для достижения успеха!</h2>
        </div>

    </div>-->
    <div class="bg">
    
        <?= $this->fetch('content') ?>
    </div>
    <div class="footer1">
    <div id="footer">
        <img id="footer-logo" src="/img/logo footer.png">
        <div class="footer-text">
            <h3 class="footer-text-h">
                Ваш личный тренер
            </h3>
            <div class="footer-text-content">
                Это сервис, позволяющий выяснить индивидуальные особенности строения тела пользователя
                и составить для него распорядок дня, подходящий рацион питания, программу тренировок,
                учитывая предпочтения и пожелания. Для удобства пользователей взаимодействие с сервисом
                реализовано не только с помощью веб-сайта, но и с помощью мобильного приложения.
            </div>
        </div>
    </div>
    </div>
    <div class="footer3">
        <div id="footertext">
        <img src="/img/phone.png" id="phone">
        <h3>С помощью мобильного приложения Вы сможете:</H3>
        <ul>
        <li>зарегистрироваться; </li>
        <li>просмотреть созданные программы тренировок, питания и распорядок дня;</li>
        <li>вести дневники тренировок и питания; </li>
        <li>просмотреть справочники; </li>
        <li>пользоваться калькуляторами.</li>
        </ul>
        <div class="header-contact-record" id="googleplaybutton">
            <a href="https://play.google.com/store/apps/details?id=ru.itconcept.mypersonaltrainer" target="_blank"><img class="gp" src = "/img/gp.png"></img></a>
            </div>
        </div>
    </div>
    <div class="footer2">
    <div id="supported-by">
        <img id="supported-by-logo" src="/img/innovation promotion fund.png">
        <h3 class="supported-by-text">
            Проект разрабатывается при поддержке Фонда содействия развитию малых форм предприятий в
            научно-технической сфере.
            <a class="supported-by-links" target="_blank" href="http://fasie.ru">fasie.ru</a>
        </h3>
    </div>
    </div>
</div>
</body>
</html>

