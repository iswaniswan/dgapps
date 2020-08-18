<div class="page-header page-header-light">
    <div class="page-header-content header-elements-md-inline">
        <div class="page-title d-flex">
            <h4><span class="font-weight-semibold">Staff</span></h4>
            <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
        </div>
    </div>
</div>

<div class="content">

    <!-- Inner container -->
    <div class="d-md-flex align-items-md-start">

        <!-- Left sidebar component -->
        <div
            class="sidebar sidebar-light sidebar-component sidebar-component-left bg-transparent border-0 shadow-0 sidebar-expand-md">

            <!-- Sidebar content -->
            <div class="sidebar-content">

                <!-- Sidebar search -->
                <div class="card">
                    <div class="card-header bg-transparent header-elements-inline">
                        <span class="text-uppercase font-size-sm font-weight-semibold">Search</span>
                        <div class="header-elements">
                            <div class="list-icons">
                                <a class="list-icons-item" data-action="collapse"></a>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <form action="#" method="POST">
                            <div class="form-group-feedback form-group-feedback-right">
                                <input type="search" class="form-control" placeholder="Search" id="cari" name="cari">
                                <div class="form-control-feedback">
                                    <i class="icon-search4 font-size-base text-muted"></i>
                                </div>
                            </div>
                        </form>
                        <div class="card-body p-0">
                            <ul class="nav nav-sidebar" data-nav-type="accordion">
                            <?php if($list_user){
                                foreach ($list_user->result() as $row) { ?>
                                    <li class="nav-item">
                                        <a href="<?= base_url('staff/view/'.encrypt_url($row->username)); ?>" class="nav-link"><i class="fas fa-user"></i> <?= $row->e_name; ?></a>
                                    </li>
                            <?php } } ?>
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- /sidebar search -->



            </div>
            <!-- /sidebar content -->

        </div>
        <!-- /left sidebar component -->


        <!-- Right content -->
        <div class="w-100">

            <!-- Basic card -->
            <div class="card">
                <div class="card-header header-elements-inline">
                    <h5 class="card-title">&nbsp;</h5>
                    <div class="header-elements">
                        <div class="list-icons">
                            <a class="list-icons-item" data-action="collapse"></a>
                        </div>
                    </div>
                </div>

                <div class="card-body text-center">
                    <img src="<?= base_url(); ?>global_assets/images/user.svg" width="150" width="150">
                    <h2>Work Harder Work Smarter</h2>
                    <h3>The productivity of work is the responsibility<br>
                        of the worker and the leader</h3>
                </div>
            </div>
            <!-- /basic card -->


        </div>
        <!-- /right content -->

    </div>
    <!-- /inner container -->

</div>
