
        <link rel="stylesheet" href="/css/newmain.css">
        <link rel="stylesheet" href="/bootstrap/css/bootstrap-select.css">
<?= $this->Html->css('/perfectscrollbar/css/perfect-scrollbar.min.css') ?>
<?= $this->Html->css('/css/newmain.css') ?>
<?= $this->Html->script('/perfectscrollbar/js/perfect-scrollbar.jquery.min.js') ?>

<style>
.col-lg-6+.fixblock {
  height: auto;
}
.fixblock.left {
  overflow-y: scroll;
  position: relative;
  padding-right: 15px;
}
.fixblock {
  overflow-y: visible;  
}
.nutrition-part {
  margin-bottom: 10px;
  width:100%;
}
.c1 {
  border: 1px solid #122b40;
    border-radius: 4px;
    border-color: #ddd;
    padding: 0px;
}
.dayheader a {
  color: #fff;
}
</style>

<div>
<?
        $this->Html->addCrumb('Список программ питания', ['controller' => 'nutritionprogram', 'action' => 'index']);
$this->Html->addCrumb('Редактирование программы питания', '#');
echo $this->Html->getCrumbList(array(
  'lastClass' => 'current',
  'id' => 'breadcrumb',
  'escape' => false
)); ?>
</div>
<section id="content">

</section>
</div>

<script>
    var foods = <?= json_encode($foods); ?>;
    var routines = <?= json_encode($routines); ?>;
    var existsproducts = <?= json_encode($eatings); ?>;
    var eatingprograminfo = <?= json_encode($eatingprograminfo); ?>;
    var mode = 'EDIT';
    var backurl = <?= $backurl; ?>;
    var bgunorm = <?= json_encode($bgunorm); ?>;
 /*   var foods = [
  {
    name: 'Овсяная каша',
    proteins: '3.2',
    fats: "4.1",
    hidrocarbonats: '14.2',
    colories: '102'
  },
  {
    name: 'Манная каша',
    proteins: '3.0',
    fats: "3.2",
    hidrocarbonats: '15.3',
    colories: '98'
  },
  {
    name: 'Овсяная каша',
    proteins: '3.2',
    fats: "4.1",
    hidrocarbonats: '14.2',
    colories: '102'
  },
  {
    name: 'Манная каша',
    proteins: '3.0',
    fats: "3.2",
    hidrocarbonats: '15.3',
    colories: '98'
  },
  {
    name: 'Овсяная каша',
    proteins: '3.2',
    fats: "4.1",
    hidrocarbonats: '14.2',
    colories: '102'
  },
  {
    name: 'Манная каша',
    proteins: '3.0',
    fats: "3.2",
    hidrocarbonats: '15.3',
    colories: '98'
  },
  {
    name: 'Овсяная каша',
    proteins: '3.2',
    fats: "4.1",
    hidrocarbonats: '14.2',
    colories: '102'
  },
  {
    name: 'Манная каша',
    proteins: '3.0',
    fats: "3.2",
    hidrocarbonats: '15.3',
    colories: '98'
  },
  {
    name: 'Овсяная каша',
    proteins: '3.2',
    fats: "4.1",
    hidrocarbonats: '14.2',
    colories: '102'
  },
  {
    name: 'Манная каша',
    proteins: '3.0',
    fats: "3.2",
    hidrocarbonats: '15.3',
    colories: '98'
  },
  {
    name: 'Овсяная каша',
    proteins: '3.2',
    fats: "4.1",
    hidrocarbonats: '14.2',
    colories: '102'
  },
  {
    name: 'Манная каша',
    proteins: '3.0',
    fats: "3.2",
    hidrocarbonats: '15.3',
    colories: '98'
  },
  {
    name: 'Овсяная каша',
    proteins: '3.2',
    fats: "4.1",
    hidrocarbonats: '14.2',
    colories: '102'
  },
  {
    name: 'Манная каша',
    proteins: '3.0',
    fats: "3.2",
    hidrocarbonats: '15.3',
    colories: '98'
  },
  {
    name: 'Овсяная каша',
    proteins: '3.2',
    fats: "4.1",
    hidrocarbonats: '14.2',
    colories: '102'
  },
  {
    name: 'Манная каша',
    proteins: '3.0',
    fats: "3.2",
    hidrocarbonats: '15.3',
    colories: '98'
  },
  {
    name: 'Овсяная каша',
    proteins: '3.2',
    fats: "4.1",
    hidrocarbonats: '14.2',
    colories: '102'
  },
  {
    name: 'Манная каша',
    proteins: '3.0',
    fats: "3.2",
    hidrocarbonats: '15.3',
    colories: '98'
  },
  {
    name: 'Овсяная каша',
    proteins: '3.2',
    fats: "4.1",
    hidrocarbonats: '14.2',
    colories: '102'
  },
  {
    name: 'Манная каша',
    proteins: '3.0',
    fats: "3.2",
    hidrocarbonats: '15.3',
    colories: '98'
  }
];*/
    
</script>
<script src="/js/react/react.js"></script>
    <script src="/js/react/react-dom.js"></script>
    <script src="/js/react/browser.min.js"></script>
    <script src="/js/nutritionprogram.js?<?php echo time(); ?>" type = "text/babel"></script>

</html>