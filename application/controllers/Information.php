<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Information extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        cek_session();
        $this->load->model('M_information');
        $this->i_company = $this->session->userdata('i_company');
    }

    public $folder = 'information';

    public function index()
    {
        add_js(
            array(
                'global_assets/js/plugins/tables/datatables/datatables.min.js',
                'global_assets/js/plugins/notifications/sweet_alert.min.js',
                'global_assets/js/plugins/forms/styling/uniform.min.js',
                'global_assets/js/plugins/forms/selects/select2.min.js',
                'assets/js/' . $this->folder . '/index.js',
                'assets/js/custom.js',
            )
        );
        $this->Logger->write(null, null, 'Membuka Menu Information');
        $this->template->load('template', $this->folder . '/index');
    }

    public function serverside()
    {
        echo $this->M_information->serverside();
    }

    public function add()
    {
        add_js(
            array(
                'global_assets/js/plugins/forms/selects/select2.min.js',
                'global_assets/js/plugins/forms/validation/validate.min.js',
                'global_assets/js/plugins/forms/styling/uniform.min.js',
                'assets/js/bootstrap-datepicker.min.js',
                'assets/js/' . $this->folder . '/add.js',
            )
        );
        add_css(
            array(
                'assets/css/bootstrap-datepicker.min.css',
            )
        );
        $this->Logger->write(null, null, 'Membuka Menu Tambah Information');
        $this->template->load('template', $this->folder . '/add');
    }


    /** Get Data Type */
    public function get_type()
    {
        $filter = [];
        $data = $this->M_information->get_type(str_replace("'", "", $this->input->get('q')));
        foreach ($data->result() as $row) {
            $filter[] = array(
                'id'   => $row->id,
                'text' => $row->e_type_name,
            );
        }
        echo json_encode($filter);
    }

    public function simpan()
    {
        $this->M_information->simpan();
        $this->Logger->write(null, null, 'Tambah Information : ' . $this->input->post('e_title'));

        $this->session->Set_flashdata('message', '<div class="alert alert-success alert-styled-left alert-arrow-left alert-dismissible">
            <button type="button" class="close" data-dismiss="alert"><span>??</span></button>
            <span class="font-weight-semibold">Success.</span> Title : <span class="font-weight-semibold">' . $this->input->post('e_title') . '</span>
        </div>');
        redirect($this->folder, 'refresh');
    }

    public function edit()
    {
        $id = $this->uri->segment('3');
        $id = decrypt_url($id);
        if (!$id) {
            redirect($this->folder, 'refresh');
        }
        $cek_data = $this->M_information->cek_data($id);

        if ($cek_data) {
            add_js(
                array(
                    'global_assets/js/plugins/forms/selects/select2.min.js',
                    'global_assets/js/plugins/forms/validation/validate.min.js',
                    'global_assets/js/plugins/forms/styling/uniform.min.js',
                    'assets/js/bootstrap-datepicker.min.js',
                    'assets/js/' . $this->folder . '/add.js',
                )
            );
            add_css(
                array(
                    'assets/css/bootstrap-datepicker.min.css',
                )
            );
            $data = array(
                'data'     => $this->M_information->data_edit($id)->row(),
            );
            $this->Logger->write(null, null, 'Membuka Menu Edit Information ' . $id);
            $this->template->load('template', $this->folder . '/update', $data);
        } else {
            redirect($this->folder, 'refresh');
        }
    }

    public function update()
    {
        $this->Logger->write(null, null, 'Update Information ID : ' . $this->input->post('id'));

        $this->M_information->update();

        $this->session->Set_flashdata('message', '<div class="alert alert-success alert-styled-left alert-arrow-left alert-dismissible">
		<button type="button" class="close" data-dismiss="alert"><span>??</span></button>
		<span class="font-weight-semibold">Success.</span></div>');
        redirect($this->folder, 'refresh');
    }

    /** Update Status */
    public function changestatus()
    {
        $id = decrypt_url($this->input->post('id', TRUE));
        if (empty($id)) {
            $data = array(
                'sukses' => false,
            );
        } else {
            $this->db->trans_begin();
            $this->M_information->changestatus($id);
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $data = array(
                    'sukses' => false,
                );
            } else {
                $this->db->trans_commit();
                $data = array(
                    'sukses' => true,
                );
                $this->Logger->write(null, null, 'Merubah Status Information ID : ' . $id);
            }
        }
        echo json_encode($data);
    }
}
