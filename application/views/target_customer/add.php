			<!-- Page header -->
			<div class="page-header page-header-light">
			  <div class="page-header-content header-elements-md-inline">
			    <div class="page-title d-flex">
			      <h4><span class="font-weight-semibold"><?=$this->lang->line('Customer Target');?> - Add Target</span></h4>
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
			          <h5 class="card-title">Target Information</h5>
			          <div class="header-elements">
			            <div class="list-icons">
			              <a class="list-icons-item" data-action="collapse"></a>
			            </div>
			          </div>
			        </div>

			        <div class="card-body">
			          <form action="<?= base_url(); ?>target_customer/simpan" method="POST" class="form-validate">
			            <div class="row">
			              <div class="col-xl-6">
			                <div class="form-group row">
			                  <label class="col-form-label col-lg-2">Customer</label>
			                  <div class="col-lg-10">
			                    <select class="form-control select-search" data-fouc name="i_customer" id="i_customer" data-placeholder="Select Customer" required>
			                      	<option value=""></option>
			                    </select>
			                  </div>
			                </div>
			                <div class="form-group row">
			                  <label class="col-form-label col-lg-2">Periode</label>
			                  <div class="col-lg-10">
			                    <input type="number" maxlength="4" min="2022" class="form-control" placeholder="YYYY" name="i_periode" required>
			                  </div>
			                </div>
			              </div>
			              <div class="col-xl-6">
			                <div class="form-group row">
			                  <label class="col-form-label col-lg-2">Target SPB</label>
			                  <div class="col-lg-10">
			                    <input type="number" class="form-control" name="v_spb_target" min="100000" placeholder="Rp. xxx.xxx.xxx.xxx" required>
			                  </div>
			                </div>
			                <div class="form-group row">
			                  <label class="col-form-label col-lg-2">Target Nota</label>
			                  <div class="col-lg-10">
							  	<input type="number" class="form-control" name="v_nota_target" min="100000" placeholder="Rp. xxx.xxx.xxx.xxx" required>
			                  </div>
			                </div>
			                <div class="d-flex justify-content-end align-items-center">
			                  <button type="submit" class="btn bg-blue ml-3"><i class="fas fa-paper-plane mr-2 fa-lg"></i>SAVE</button>
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