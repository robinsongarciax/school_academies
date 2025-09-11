<hr class="mt-0 mb-4">
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="d-sm-flex align-items-center justify-content-between">
                <h3 class="h6 m-0 font-weight-bold text-primary-cm"><?= __('Conectado como:') . ' ' . $this->Identity->get('name') ?></h3>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-7 d-none d-lg-block">
                    <div class="d-flex align-items-center justify-content-center" style="height: 440px;">
                        <?= $this->element('carousel-welcome') ?>
                    </div>
                    
                </div>
                <div class="col-lg-5">
                    <div class="p-4">
                        <div class="d-flex align-items-center justify-content-center">
                            <?= $this->Html->image('escudo _cumbres_2.png', ['alt' => 'Cumbres Mérida', 'id' => 'welcome-logo']) ?>
                        </div>
                        <div class="text-center">
                            <h2 class="h4 text-gray-900 mb-4"><?= __('Inscripciones <br> a academias') ?></h2>
                        </div>

                        <p class="text-center mb-4 text-gray-900">
                            <?= __('Bienvenido al sistema') . ' '?><strong><?=$this->Identity->get('name')?></strong> 
                        </p>
                        <p class="text-center mb-2">
                            <?= $this->Time->i18nFormat(date('Y-m-d'), [\IntlDateFormatter::FULL, \IntlDateFormatter::NONE])?>
                        </p>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>