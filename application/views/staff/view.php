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
    <div class="sidebar sidebar-light sidebar-component sidebar-component-left bg-transparent border-0 shadow-0 sidebar-expand-md">

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
                <?php
                if ($list_user) {
                  foreach ($list_user->result() as $row) { ?>
                    <li class="nav-item">
                      <a href="<?= base_url('staff/view/' . encrypt_url($row->username)); ?>" class="nav-link"><i class="fas fa-user"></i> <?= $row->e_name; ?></a>
                    </li>
                <?php }
                } ?>
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
          <h5 class="card-title">Staff Information</h5>
          <div class="header-elements">
            <div class="list-icons">
              <a class="list-icons-item" data-action="collapse"></a>
            </div>
          </div>
        </div>

        <div class="card-body">
          <div class="row">
            <div class="col-xl-4">
              <a href="#" class="d-inline-block mb-3" onclick="return false;">
                <img src="<?= base_url(); ?>global_assets/images/placeholders/placeholder.jpg" class="rounded-round" alt="" width="150" height="150">
              </a>
            </div>
            <div class="col-xl-8">
              <div class="row">
                <label class="col-form-label col-lg-3">Staff ID</label>
                <label class="col-form-label col-lg-9">: <?= $data_staff->i_staff; ?></label>
              </div>
              <div class="row">
                <label class="col-form-label col-lg-3">Name</label>
                <label class="col-form-label col-lg-9">: <?= $data_staff->e_name; ?></label>
              </div>
              <div class="row">
                <label class="col-form-label col-lg-3">Phone Number</label>
                <label class="col-form-label col-lg-9">: <?= $data_staff->phone; ?></label>
              </div>
              <div class="row">
                <label class="col-form-label col-lg-3">Role</label>
                <label class="col-form-label col-lg-9">: <?= $data_staff->e_role_name; ?></label>
              </div>
            </div>
          </div>

        </div>
      </div>
      <div class="card card-collapsed">
        <div class="card-header header-elements-inline">
          <h5 class="card-title">Activity List</h5>
          <div class="header-elements">
            <div class="list-icons">
              <a class="list-icons-item" data-action="collapse"></a>
            </div>
          </div>
        </div>

        <div class="card-body">
          <div class="row">
            <div class="col-xl-12">

              <div class="card">

                <div class="card-body">
                  <table class="table table-striped table-hover table-bordered" id="serverside">
                    <thead>
                      <tr>
                        <th>Customer Name</th>
                        <th>Check-In</th>
                        <th>Check-Out</th>
                        <th>Duration</th>
                        <th>&nbsp;</th>
                      </tr>
                    </thead>
                    <tbody>
                    </tbody>
                  </table>
                </div>
              </div>

            </div>

          </div>

        </div>

      </div>
      <div class="card card-collapsed">
        <div class="card-header header-elements-inline">
          <h5 class="card-title">Check In Maps</h5>
          <div class="header-elements">
            <div class="list-icons">
              <a class="list-icons-item" data-action="collapse"></a>
            </div>
          </div>
        </div>

        <div class="card-body">
          <div class="row">
            <div class="col-xl-12">

              <div class="card">

                <div class="card-body">
                  <div class="input-group">
                    <span class="input-group-prepend">
                      <span class="input-group-text">
                        <i class="icon-calendar"></i>
                      </span>
                    </span>
                    <input type="text" class="form-control" readonly value="<?= date('d-m-Y'); ?>" id="datepicker-checkin">
                  </div>
                  <br>
                  <div class="map-container" id="container"></div>
                </div>
              </div>

            </div>

          </div>

        </div>

      </div>

      <div class="card card-collapsed">
        <div class="card-header header-elements-inline">
          <h5 class="card-title">Tracking In Maps</h5>
          <div class="header-elements">
            <div class="list-icons">
              <a class="list-icons-item" data-action="collapse"></a>
            </div>
          </div>
        </div>

        <div class="card-body">
          <div class="row">
            <div class="col-xl-12">

              <div class="card">

                <div class="card-body">
                  <div class="input-group">
                    <span class="input-group-prepend">
                      <span class="input-group-text">
                        <i class="icon-calendar"></i>
                      </span>
                    </span>
                    <input type="text" class="form-control" readonly value="<?= date('d-m-Y'); ?>" id="datepicker-tracking">
                  </div>
                  <br>
                  <div class="map-container" id="map-tracking"></div>
                </div>
              </div>

            </div>

          </div>

        </div>

      </div>

      <?php if ($data_staff->f_summary_sales=='t') {?>
      <div class="card card-collapsed">
        <div class="card-header header-elements-inline">
          <h5 class="card-title">Summary of Sales</h5>
          <div class="header-elements">
            <div class="list-icons">
              <a class="list-icons-item" data-action="collapse"></a>
            </div>
          </div>
        </div>

        <div class="card-body">
          <div class="row">
            <div class="col-xl-12">

              <div class="card">

                <!-- <div class="card-body">
                  <div class="input-group"> -->
                    <?php
                    $dfrom = date('Y-m-d', strtotime('sunday last week'));
                    $dto = date('Y-m-d');
                    $wrap = wrap($data_staff->i_staff);
                    // $id_user_db = wrap($data_staff->i_user_db);
                    ?>
                    <a href="https://53.dialoguegroup.net/bcl/portal/salesmansummary?id=<?= $wrap;?>&d1=<?= $dfrom;?>&d2=<?= $dto;?>" onclick="basicPopup(this.href);return false" class="btn btn-sm btn-block bg-primary-800"><i class="icon-paperplane mr-3 icon-2x mr-2"></i> Summary of Sales <?= $data_staff->e_name; ?></a>
                  <!-- </div>
                </div> -->
              </div>

            </div>

          </div>

        </div>

      </div>
      <?php } ?>
    </div>
    <!-- /right content -->

  </div>
  <!-- /inner container -->

  <div class="row">
    <div class="col-xl-12">
      <div class="card card-uncollapsed" id="journey_id">
        <div class="card-header header-elements-inline">
          <h5 class="card-title">Journey Plan</h5>
          <div class="header-elements">
            <div class="list-icons">
              <a class="list-icons-item" data-action="collapse"></a>
            </div>
          </div>
        </div>

        <div class="card-body">
          <div class="row">
            <div class="col-xl-12">

              <div class="card">

                <div class="card-body">

                  <div class="fullcalendar-event-colors"></div>
                  <br>
                  <div class="text-center">
                    <table border="0" width="100%">
                      <tr>
                        <td width="5%"></td>
                        <td width="5%"></td>
                        <td width="5%"></td>
                        <td width="4%" bgcolor="#00BCD4"></td>
                        <td width="1%"></td>
                        <td width="10%">Journey Plan</td>
                        <td width="5%"></td>
                        <td width="5%"></td>
                        <td width="5%"></td>
                        <td width="4%" bgcolor="#2196F3"></td>
                        <td width="1%"></td>
                        <td width="10%">Visited</td>
                        <td width="5%"></td>
                        <td width="5%"></td>
                        <td width="5%"></td>
                        <td width="4%" bgcolor="#4CAF50"></td>
                        <td width="1%"></td>
                        <td width="10%">Sales Order</td>
                        <td width="5%"></td>
                        <td width="5%"></td>
                        <td width="5%"></td>
                      </tr>
                    </table>
                  </div>
                </div>
              </div>

            </div>

          </div>

        </div>

      </div>
    </div>
  </div>
</div>