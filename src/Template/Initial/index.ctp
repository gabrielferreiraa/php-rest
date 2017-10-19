<?php
$baseUrl = \Cake\Routing\Router::url('/', true);
?>
<section class="wrapper">
    <img src="<?= $this->Url->build('/img/logo.png', true); ?>" class="logo">
    <h1 class="title">Taller Challenge API</h1>
    <small class="api">
        <span>URL da API:</span> <?= $baseUrl . 'ws/' ?>
    </small>
</section>