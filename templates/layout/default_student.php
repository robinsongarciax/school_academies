<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 * @var \App\View\AppView $this
 */

$cakeDescription = __('Cake Description');;
?>
<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>
        <?= $cakeDescription ?>:
        <?= $this->fetch('title') ?>
    </title>
    <?= $this->Html->meta('icon') ?>

    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">


    <?= $this->Html->css(['cake', 
        'sb-admin-2', 
        'vendor/fontawesome-free/css/all.min',
        'vendor/datatables/dataTables.bootstrap4.min.css',
        'custom-home']) ?>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>
</head>
<body>
    <?php $controller = $this->request->getParam('controller'); ?>
    <?php $action = $this->request->getParam('action'); ?>
    <?php
        $show = '';
        if ($action == 'index' || $action == 'add') {
            $show = $controller . '_show';
        }
    ?>
    <?php $action = $controller . '_' . $action; ?>
    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <?= $this->Html->link($this->Html->image('cake-logo.png', ['alt' => 'Cumbres Mérida']),
                ['controller' => 'Pages', 'action' => 'display', 'home'],
                ['class' => 'sidebar-brand d-flex align-items-center justify-content-center',
                    'escape' => false
                ]) ?>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Home -->
            <li class="nav-item active">
                <?= $this->Html->link("<i class=\"fas fa-fw fa-home\"></i>
                    <span>" . __('Home') . "</span>", 
                    ['controller' => 'Pages', 'action' => 'display', 'home'],
                    ['class' => 'nav-link', 'escape' => false]) ?>
            </li>
            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                <?= __('Subjects') ?>
            </div>

            <!-- Nav Item - Pages Collapse Menu -->
            <li class="nav-item <?= ($controller == 'Documents' || $controller == 'Teachers') ? 'active' : ''?>">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseOne"
                    aria-expanded="true" aria-controls="collapseOne">
                    <i class="fas fa-fw fa-chalkboard"></i>
                    <span><?= __('Subjects') ?></span>
                </a>
                <div id="collapseOne" class="collapse <?= ($controller == 'Subjects' || $controller == 'Teachers') ? 'show' : ''?>" aria-labelledby="headingOne" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header"><?= __('School Courses') ?></h6>
                        <?= $this->Html->link(__('School Courses'), ['controller' => 'SchoolCourses', 'action' => 'index'], ['class' => 'collapse-item' . ($action == 'Documents_index' ? ' active' : '')]) ?>
                        <?= $this->Html->link(__('Subjects'), ['controller' => 'Subjects', 'action' => 'index'], ['class' => 'collapse-item' . ($controller == 'Subjects' ? ' active' : '')]) ?>
                        <?= $this->Html->link(__('Teachers'), ['controller' => 'Teachers', 'action' => 'index'], ['class' => 'collapse-item' . ($controller == 'Teachers' ? ' active' : '')]) ?>
                    </div>
                </div>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

            <!-- Sidebar Message -->
            <!-- <div class="sidebar-card d-none d-lg-flex">

                <img class="sidebar-card-illustration mb-2" src="img/undraw_rocket.svg" alt="...">
                <p class="text-center mb-2"><strong>Powered by</strong></p>
                <a class="btn btn-success btn-sm" href="https://cssoft.mx">CodeStudio!</a>
            </div> -->

        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar static-top shadow">
                    <?= $this->Html->link("<i class=\"fas fa-backward fa-sm text-gray-50\"></i>" . __(' Back'), 'javascript:history.back()', ['class' => '', 'escape' => false]) ?>
                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <!-- Nav Item - Search Dropdown (Visible Only XS) -->
                        <li class="nav-item dropdown no-arrow d-sm-none">
                            <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-search fa-fw"></i>
                            </a>
                            <!-- Dropdown - Messages -->
                            <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in"
                                aria-labelledby="searchDropdown">
                                <form class="form-inline mr-auto w-100 navbar-search">
                                    <div class="input-group">
                                        <input type="text" class="form-control bg-light border-0 small"
                                            placeholder="Search for..." aria-label="Search"
                                            aria-describedby="basic-addon2">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="button">
                                                <i class="fas fa-search fa-sm"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </li>

                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?= $this->Identity->get('name') ?></span>
                                <?= $this->Html->image('undraw_profile.svg', ["class" => "img-profile rounded-circle"]) ?>
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <?= $this->Html->link("<i class=\"fas fa-user fa-sm fa-fw mr-2 text-gray-400\"></i>" . __('Configurar perfil'), ['controller' => 'Users', 'action' => 'edit', $this->Identity->get('id')], ['class' => 'dropdown-item', 'escape' => false]) ?>
                                <?= $this->Html->link("<i class=\"fas fa-efirma fa-sm fa-fw mr-2 text-gray-400\"></i>" . __('Configurar efirma'), ['controller' => 'Efirmas', 'action' => 'index', $this->Identity->get('id')], ['class' => 'dropdown-item', 'escape' => false]) ?>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Cerrar sesi&oacute;n
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div id="layoutSidenav_content">
                    <main>
                        <?= $this->Flash->render() ?>
                        <?= $this->fetch('content') ?>
                    </main>
                </div>

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; <?= date('Y') ?> <?= __('Application Copyright') ?> | Powered by <a href="https://cssoft.mx">cssoft.mx</a></span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">¿Listo para salir?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Precione "<?=__('Logout')?>" para salir del sistema.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal"><?=__('Cancel')?></button>
                    <?= $this->Html->link(__('Logout'), ['controller' => 'Users', 'action' => 'logout'], ['class' => 'btn btn-primary']) ?>
                </div>
            </div>
        </div>
    </div>

    <?= $this->Html->script([
        'vendor/jquery/jquery.min',
        'vendor/bootstrap/js/bootstrap.bundle.min',
        'vendor/jquery-easing/jquery.easing.min',
        'vendor/datatables/jquery.dataTables.min.js',
        'vendor/datatables/dataTables.bootstrap4.min.js',
        'demo/datatables-demo.js',
        'sb-admin-2',
        'admin-app']) ?>

        <!-- 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js' -->
</body>
</html>
