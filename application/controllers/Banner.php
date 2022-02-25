<?php
defined('BASEPATH') or exit('No direct script access allowed');


class Banner extends CI_Controller
{
    var $label = 'Banner';
    var $link  = 'banner';

    public function __construct()
    {
        parent::__construct();
        cek_session();
        $this->id_company = $this->session->userdata('i_company');
        $this->load->model('M_banner');
    }

    public $folder = 'banner';

    public function index()
    {
        add_js(
            array(
                'global_assets/js/plugins/tables/datatables/datatables.min.js',
                'global_assets/js/plugins/notifications/sweet_alert.min.js',
                'global_assets/js/plugins/forms/styling/uniform.min.js',
                'global_assets/js/plugins/forms/selects/select2.min.js',
                'assets/js/banner/index.js',
            )
        );
        $this->Logger->write(null, null, 'Membuka Menu Banner');
        $this->template->load('template', $this->folder . '/index');
    }

    public function serverside()
    {
        echo $this->M_banner->serverside();
    }

    public function add()
    {
        $this->Logger->write(null, null, 'Membuka Menu Tambah banner');
        $this->template->load('template', $this->folder . '/add');
    }

    public function simpan()
    {
        $image      = $_FILES['image']['name'];
        $note       = $this->input->post('note');
        $d_start    = $this->input->post('d_start');
        $d_end      = $this->input->post('d_end');
        if ($image != '' && $d_start != '' && $d_end != '') {
            $exsten   = pathinfo($image, PATHINFO_EXTENSION);
            $filename = '';
            $filename = substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 1, 5) . '.' . $exsten;
            $url      = base_url('assets/images/banner/' . $filename);
            $tmp_file = $_FILES['image']['tmp_name'];
            if (!empty($filename)) {
                if ($tmp_file != "") {
                    $kop = "./assets/images/banner/" . $filename;
                    $pattern = "/^.*\.(" . $exsten . ")$/i";
                    if (preg_match_all($pattern, $kop) >= 1) {
                        if (move_uploaded_file($tmp_file, $kop)) {
                            @chmod("./assets/images/banner/" . $filename, 0777);
                        } else {
                            $this->session->set_flashdata('message', '<div class="alert alert-danger alert-styled-left alert-arrow-left alert-dismissible"><button type="button" class="close" data-dismiss="alert"><span>×</span></button><span class="font-weight-semibold">Gagal</span></div>');
                            redirect(site_url($this->link));
                        }
                    } else {
                        $this->session->set_flashdata('message', '<div class="alert alert-danger alert-styled-left alert-arrow-left alert-dismissible"><button type="button" class="close" data-dismiss="alert"><span>×</span></button><span class="font-weight-semibold">Gagal</span></div>');
                        redirect(site_url($this->link));
                    }
                } else {
                    $this->session->set_flashdata('message', '<div class="alert alert-danger alert-styled-left alert-arrow-left alert-dismissible"><button type="button" class="close" data-dismiss="alert"><span>×</span></button><span class="font-weight-semibold">Gagal</span></div>');
                    redirect(site_url($this->link));
                }
            }

            $this->M_banner->simpan($url, $note, $d_start, $d_end);

            $this->Logger->write(null, null, 'Tambah banner ' . $url);
            $this->session->Set_flashdata('message', '<div class="alert alert-success alert-styled-left alert-arrow-left alert-dismissible"><button type="button" class="close" data-dismiss="alert"><span>×</span></button><span class="font-weight-semibold">Success.</span></div>');
        } else {
            $this->session->Set_flashdata('message', '<div class="alert alert-danger alert-styled-left alert-arrow-left alert-dismissible"><button type="button" class="close" data-dismiss="alert"><span>×</span></button><span class="font-weight-semibold">Gagal Disimpan.</span></div>');
        }
        redirect('banner', 'refresh');
    }

    public function edit()
    {
        $id = decrypt_url($this->uri->segment(3));
        $this->Logger->write(null, null, 'Membuka Menu Edit banner');
        $data = array(
            'data' => $this->db->get_where('tbl_banner', ['e_path' => $id, 'id_company' => $this->id_company])->row(),
        );
        $this->template->load('template', $this->folder . '/edit', $data);
    }

    public function update()
    {
        $image      = $_FILES['image']['name'];
        $image_old  = $this->input->post('image_old');
        $note       = $this->input->post('note');
        $d_start    = $this->input->post('d_start');
        $d_end      = $this->input->post('d_end');
        if ($image_old != '' && $d_start != '' && $d_end != '') {
            if ($image != '') {
                $exsten   = pathinfo($image, PATHINFO_EXTENSION);
                $filename = '';
                $filename = substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 1, 5) . '.' . $exsten;
                $url      = base_url('assets/images/banner/' . $filename);
                $tmp_file = $_FILES['image']['tmp_name'];
                if (!empty($filename)) {
                    if ($tmp_file != "") {
                        $kop = "./assets/images/banner/" . $filename;
                        $pattern = "/^.*\.(" . $exsten . ")$/i";
                        if (preg_match_all($pattern, $kop) >= 1) {
                            if (move_uploaded_file($tmp_file, $kop)) {
                                @chmod("./assets/images/banner/" . $filename, 0777);
                            } else {
                                $this->session->set_flashdata('message', '<div class="alert alert-danger alert-styled-left alert-arrow-left alert-dismissible"><button type="button" class="close" data-dismiss="alert"><span>×</span></button><span class="font-weight-semibold">Gagal</span></div>');
                                redirect(site_url($this->link));
                            }
                        } else {
                            $this->session->set_flashdata('message', '<div class="alert alert-danger alert-styled-left alert-arrow-left alert-dismissible"><button type="button" class="close" data-dismiss="alert"><span>×</span></button><span class="font-weight-semibold">Gagal</span></div>');
                            redirect(site_url($this->link));
                        }
                    } else {
                        $this->session->set_flashdata('message', '<div class="alert alert-danger alert-styled-left alert-arrow-left alert-dismissible"><button type="button" class="close" data-dismiss="alert"><span>×</span></button><span class="font-weight-semibold">Gagal</span></div>');
                        redirect(site_url($this->link));
                    }
                }
                $this->M_banner->update($url, $image_old, $note, $d_start, $d_end);
                unlink("./assets/images/banner/" . pathinfo($image_old,PATHINFO_BASENAME));
                $this->Logger->write(null, null, 'Update banner ' . $url);
                $this->session->Set_flashdata('message', '<div class="alert alert-success alert-styled-left alert-arrow-left alert-dismissible"><button type="button" class="close" data-dismiss="alert"><span>×</span></button><span class="font-weight-semibold">Success.</span></div>');
            }else{
                $this->M_banner->update($image_old, $image_old, $note, $d_start, $d_end);

                $this->Logger->write(null, null, 'Update banner ' . $image_old);
                $this->session->Set_flashdata('message', '<div class="alert alert-success alert-styled-left alert-arrow-left alert-dismissible"><button type="button" class="close" data-dismiss="alert"><span>×</span></button><span class="font-weight-semibold">Success.</span></div>');
            }
        } else {
            $this->session->Set_flashdata('message', '<div class="alert alert-danger alert-styled-left alert-arrow-left alert-dismissible"><button type="button" class="close" data-dismiss="alert"><span>×</span></button><span class="font-weight-semibold">Gagal Disimpan.</span></div>');
        }
        redirect('banner', 'refresh');
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
            $this->M_banner->changestatus($id);
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
                $this->Logger->write(null, null, 'Merubah Status Banner : ' . $id);
            }
        }
        echo json_encode($data);
    }
}
