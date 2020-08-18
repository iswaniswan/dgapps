			<!-- Page header -->
			<div class="page-header page-header-light">
			    <div class="page-header-content header-elements-md-inline">
			        <div class="page-title d-flex">
			            <h4><span class="font-weight-semibold">Suggestion</span></h4>
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
			                    <h5 class="card-title"></h5>
			                    <div class="header-elements">
			                        <div class="list-icons">
			                            <a class="list-icons-item" data-action="collapse"></a>
			                        </div>
			                    </div>
			                </div>

			                <div class="card-body">
                                <form action="<?= base_url();?>documentation/save/" method="POST">
			                    <div class="row">
			                            <div class="col-xl-12">
			                                <div class="row">
			                                    <label class="col-form-label col-lg-3">Customer Name</label>
			                                    <label class="col-form-label col-lg-9">:
			                                        <?= $data_saran->e_customer_name; ?></label>
			                                </div>
			                                <div class="row">
			                                    <label class="col-form-label col-lg-3">Staff Name</label>
			                                    <label class="col-form-label col-lg-9">: <?= $data_saran->e_name; ?></label>
			                                </div>
			                                <div class="row">
			                                    <label class="col-form-label col-lg-3">Suggestion Type</label>
			                                    <label class="col-form-label col-lg-9">:
			                                        <?= $data_saran->e_saran_typename; ?></label>
			                                </div>
			                                <div class="row">
			                                    <label class="col-form-label col-lg-3">Suggestion</label>
												<label class="col-form-label col-lg-9">: <?= $data_saran->e_saran; ?></label>
												<input type="hidden" name="i_customer" value="<?= $data_saran->i_customer; ?>">
												<input type="hidden" name="i_saran_type" value="<?= $data_saran->i_saran_type; ?>">
												<input type="hidden" name="d_saran" value="<?= $data_saran->d_saran; ?>">
			                                </div>
			                                <div class="row">
			                                    <label class="col-form-label col-lg-3">Response</label>
			                                    <label class="col-form-label col-lg-5"><textarea rows="5" cols="5"
			                                            class="form-control" name="response"><?= $data_saran->e_respons; ?></textarea></label>
			                                </div>
			                                <br>
			                                <div class="text-center">
			                                    <button type="submit" class="btn btn-primary">Save <i
			                                            class="icon-paperplane ml-2"></i></button>
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