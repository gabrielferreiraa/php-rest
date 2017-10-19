<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        Taller Challenge
    </title>

    <?php
    $this->append('css', $this->Html->css([
        'https://fonts.googleapis.com/css?family=Alegreya:400,400i,700,700i,900,900i',
        'initial'
    ]));
    ?>

    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>
</head>
<body>
<?= $this->Flash->render() ?>
<div class="container clearfix">
    <?= $this->fetch('content') ?>
</div>
</body>
</html>
