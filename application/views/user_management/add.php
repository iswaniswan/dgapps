			<!-- Page header -->
			<div class="page-header page-header-light">
			  <div class="page-header-content header-elements-md-inline">
			    <div class="page-title d-flex">
			      <h4><span class="font-weight-semibold"><?=$this->lang->line('user-management');?> - Add User</span></h4>
			    </div>
			  </div>
			</div>
			<!-- /page header -->


			<!-- Content area -->
			<div class="content">

			  <div class="row">
			    <div class="col-xl-12">

			      <div class="card">
			        <div class="card-header header-elements-inline">
			          <h5 class="card-title">User Information</h5>
			          <div class="header-elements">
			            <div class="list-icons">
			              <a class="list-icons-item" data-action="collapse"></a>
			            </div>
			          </div>
			        </div>

			        <div class="card-body">
			          <form action="<?= base_url(); ?>user-management/simpan" method="POST" class="form-validate">
			            <div class="row">
			              <div class="col-xl-6">
			                <div class="form-group row">
			                  <label class="col-form-label col-lg-2">Role</label>
			                  <div class="col-lg-10">
			                    <select class="form-control select-search" data-fouc name="i_role" required>
			                      <?php if($data_role->num_rows() > 0){
													foreach ($data_role->result() as $row) { ?>
			                      <option value="<?= $row->i_role; ?>">
			                        <?= $row->e_role_name; ?></option>
			                      <?php } }?>
			                    </select>
			                  </div>
			                </div>
			                <div class="form-group row">
			                  <label class="col-form-label col-lg-2">Area</label>
			                  <div class="col-lg-10">
			                    <select class="form-control select-search" data-fouc name="i_area" required>
			                      <?php if($data_area->num_rows() > 0){
													foreach ($data_area->result() as $row) { ?>
			                      <option value="<?= $row->i_area; ?>">
			                        <?= $row->e_area_name; ?></option>
			                      <?php } }?>
			                    </select>
			                  </div>
			                </div>
			                <div class="form-group row">
			                  <label class="col-form-label col-lg-2">Status</label>
			                  <div class="col-lg-10">
			                    <select class="form-control select-search" data-fouc name="f_active" required>
			                      <option value="t">Active
			                      </option>
			                      <option value="f">Inactive
			                      </option>
			                    </select>
			                  </div>
			                </div>
			                <div class="form-group row">
			                  <label class="col-form-label col-lg-2">Home Address</label>
			                  <div class="col-lg-10">
			                    <input type="text" class="form-control" placeholder="Home Address" name="address" required>
			                  </div>
			                </div>
                              <div class="form-group row">
                                  <label class="col-form-label col-lg-2">Upline</label>
                                  <div class="col-lg-10">
                                      <select class="form-control select-search" data-fouc name="username_upline" required>
                                          <?php foreach ($data_upline as $row) { ?>
                                              <option value="<?= $row->username ?>"><?= $row->e_name . ' - ' . $row->e_role_name ?></option>
                                          <?php } ?>
                                      </select>
                                  </div>
                              </div>
                              <div class="form-group row">
                                  <label class="col-form-label col-lg-2">Area</label>
                                  <div class="col-lg-10">
                                      <select class="form-control select-search" data-fouc name="array_area[]" multiple="multiple" required>
                                          <?php if($data_area->num_rows() > 0){
                                              foreach ($data_area->result() as $row) { ?>
                                                  <option value="<?= $row->i_area; ?>">
                                                      <?= $row->e_area_name; ?></option>
                                              <?php }
                                          }?>
                                      </select>
                                  </div>
                              </div>
			              </div>
			              <div class="col-xl-6">
			                <div class="form-group row">
			                  <label class="col-form-label col-lg-2">Staff ID</label>
			                  <div class="col-lg-10">
			                    <input type="text" class="form-control" name="i_staff">
			                  </div>
			                </div>
			                <div class="form-group row">
			                  <label class="col-form-label col-lg-2">Full Name</label>
			                  <div class="col-lg-10">
			                    <input type="text" class="form-control" placeholder="Full Name" name="e_name" required>
			                  </div>
			                </div>
			                <div class="form-group row">
			                  <label class="col-form-label col-lg-2">Password</label>
			                  <div class="col-lg-10">
			                    <input type="password" class="form-control" placeholder="Password" name="e_password" required>
			                  </div>
			                </div>
			                <div class="form-group row">
			                  <label class="col-form-label col-lg-2">Phone no</label>
			                  <div class="col-lg-10">
			                    <input type="text" class="form-control" placeholder="Phone no" name="phone" required>
			                  </div>
			                </div>
			                <div class="form-group row">
			                  <label class="col-form-label col-lg-2">Email</label>
			                  <div class="col-lg-10">
			                    <input type="text" class="form-control" placeholder="Email" name="email" required>
			                  </div>
			                </div>
			                <div class="d-flex justify-content-end align-items-center">
			                  <button type="submit" class="btn bg-blue ml-3">SAVE</button>
			                </div>
			              </div>

			            </div>
			          </form>
			        </div>
			      </div>

			    </div>

			  </div>

			</div>
			<!-- /content area -->