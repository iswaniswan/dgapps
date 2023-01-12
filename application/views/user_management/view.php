			<!-- Page header -->
			<div class="page-header page-header-light">
			  <div class="page-header-content header-elements-md-inline">
			    <div class="page-title d-flex">
			      <h4><span class="font-weight-semibold"><?=$this->lang->line('user-management');?> - Edit User</span></h4>
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
			          <form action="<?= base_url(); ?>user-management/update" method="POST" class="form-validate">
			            <div class="row">
			              <div class="col-xl-6">
			                <div class="form-group row">
			                  <label class="col-form-label col-lg-2">Role</label>
			                  <div class="col-lg-10">
			                    <select class="form-control select-search" data-fouc name="i_role" required>
			                      <?php if($data_role->num_rows() > 0){
													foreach ($data_role->result() as $row) { ?>
			                      <option value="<?= $row->i_role; ?>"
			                        <?php if($row->i_role == $data_user->i_role){ echo "selected"; } ?>>
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
			                      <option value="<?= $row->i_area; ?>"
			                        <?php if($row->i_area == $data_user->i_area){ echo "selected"; } ?>>
			                        <?= $row->e_area_name; ?></option>
			                      <?php } }?>
			                    </select>
			                  </div>
			                </div>
			                <!-- <div class="form-group row">
											<label class="col-form-label col-lg-2">Upline</label>
											<div class="col-lg-10">
												<select class="form-control select-search" data-fouc readonly>
													<option value="<?= $data_user->username_upline; ?>"><?= $data_user->username_upline; ?></option>
												</select>
											</div>
										</div> -->
			                <div class="form-group row">
			                  <label class="col-form-label col-lg-2">Status</label>
			                  <div class="col-lg-10">
			                    <select class="form-control select-search" data-fouc name="f_active" required>
			                      <option value="t" <?php if($data_user->f_active == 't'){ echo "selected"; } ?>>Active
			                      </option>
			                      <option value="f" <?php if($data_user->f_active == 'f'){ echo "selected"; } ?>>Inactive
			                      </option>
			                    </select>
			                  </div>
			                </div>
			                <div class="form-group row">
			                  <label class="col-form-label col-lg-2">Home Address</label>
			                  <div class="col-lg-10">
			                    <input type="text" class="form-control" placeholder="Home Address"
			                      value="<?= $data_user->address; ?>" name="address" required>
			                  </div>
			                </div>
                              <div class="form-group row">
                                  <label class="col-form-label col-lg-2">Upline</label>
                                  <div class="col-lg-10">
                                      <?php $disabled = $data_user->f_active == 'f' ? 'disabled' : ''; ?>
                                      <select class="form-control select-search" data-fouc name="username_upline" required>
                                          <?php foreach ($data_upline as $row) { ?>
                                                <?= $selected = $row->username == $data_user->username_upline ? 'selected' : '' ?>
                                              <option value="<?= $row->username ?>" <?= $selected ?>><?= $row->e_name . ' - ' . $row->e_role_name ?></option>
                                          <?php } ?>
                                      </select>
                                  </div>
                              </div>
                              <div class="form-group row">
                                  <label class="col-form-label col-lg-2">Coverage Area</label>
                                  <div class="col-lg-10">
                                      <div class="div" style="display: flex">
                                          <span class="mr-2">Pilih Semua</span>
                                          <input type="checkbox" id="coverage_area_checkbox" class="" style="align-self: center">
                                      </div>
                                      <select class="form-control select-search" id="coverage_area_select" data-fouc name="coverage_area[]" multiple="multiple" <?= $disabled ?>  required>
                                          <?php if($data_area->num_rows() > 0){
                                              foreach ($data_area->result() as $row) { ?>
                                                      <?php $selected = in_array($row->i_area, $data_user_area) ? 'selected' : '' ?>
                                                  <option value="<?= $row->i_area; ?>" <?= $selected ?>><?= $row->e_area_name; ?></option>
                                              <?php }
                                          }?>
                                      </select>
                                  </div>
                              </div>
			              </div>
			              <div class="col-xl-6">
			                <div class="form-group row">
			                  <label class="col-form-label col-lg-2">Username</label>
			                  <div class="col-lg-10">
			                    <input type="text" class="form-control" readonly value="<?= $data_user->username; ?>"
			                      name="username" required>
			                  </div>
			                </div>
			                <div class="form-group row">
			                  <label class="col-form-label col-lg-2">Staff ID</label>
			                  <div class="col-lg-10">
			                    <input type="text" class="form-control" readonly value="<?= $data_user->i_staff; ?>"
			                      name="i_staff" required>
			                  </div>
			                </div>
			                <div class="form-group row">
			                  <label class="col-form-label col-lg-2">Full Name</label>
			                  <div class="col-lg-10">
			                    <input type="text" class="form-control" placeholder="Full Name"
			                      value="<?= $data_user->e_name; ?>" name="e_name" required>
			                  </div>
			                </div>
			                <div class="form-group row">
			                  <label class="col-form-label col-lg-2">Phone no</label>
			                  <div class="col-lg-10">
			                    <input type="text" class="form-control" placeholder="Phone no" value="<?= $data_user->phone; ?>"
			                      name="phone" required>
			                  </div>
			                </div>
			                <div class="form-group row">
			                  <label class="col-form-label col-lg-2">Email</label>
			                  <div class="col-lg-10">
			                    <input type="text" class="form-control" placeholder="Email" value="<?= $data_user->email; ?>"
			                      name="email" required>
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