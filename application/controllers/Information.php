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
            <button type="button" class="close" data-dismiss="alert"><span>×</span></button>
            <span class="font-weight-semibold">Success.</span> Title : <span class="font-weight-semibold">' . $this->input->post('e_title') . '</span>
        </div>');
        redirect($this->folder, 'refresh');
    }

    public function edit()
    {
        $id = $this->uri->segment('3');
        $id = decrypt_url($id);
        if (!$id) {
            redirect('user-customer', 'refresh');
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
		<button type="button" class="close" data-dismiss="alert"><span>×</span></button>
		<span class="font-weight-semibold">Success.</span></div>');
        redirect($this->folder, 'refresh');
    }

    public function view()
    {
        $username = $this->uri->segment('3');
        $username = decrypt_url($username);
        if (!$username) {
            redirect('user-management', 'refresh');
        }
        $cek_data = $this->M_information->cek_data($username);

        if ($cek_data) {
            add_js(
                array(
                    'global_assets/js/plugins/forms/selects/select2.min.js',
                    'global_assets/js/plugins/forms/validation/validate.min.js',
                    'global_assets/js/plugins/forms/styling/uniform.min.js',
                    'assets/js/user_customer/view.js',
                )
            );
            $data = array(
                'data'     => $this->M_information->data_toko($username)->row(),
                'customer' => $this->M_information->data_customer($username),
            );
            $this->Logger->write(null, null, 'Membuka Menu User Customer View ' . $username);
            $this->template->load('template', $this->folder . '/view', $data);
        } else {
            redirect('user-customer', 'refresh');
        }
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

    public function download_user()
    {
        $query = $this->M_information->download_user();

        if ($query->num_rows() > 0) {

            $spreadsheet = new Spreadsheet;
            $title = new Style();
            $sharedStyle1 = new Style();
            $sharedStyle2 = new Style();
            $sharedStyle3 = new Style();
            $spreadsheet->getActiveSheet()->getStyle('B2')->getAlignment()->applyFromArray(
                [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER, 'textRotation' => 0, 'wrapText' => TRUE
                ]
            );

            $sharedStyle1->applyFromArray(
                [
                    'font' => [
                        'name'  => 'Calibri',
                        'bold'  => false,
                        'italic' => false,
                        'size'  => 10,
                    ],
                    'alignment' => [
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                    'borders' => [
                        'top'    => ['borderStyle' => Border::BORDER_THIN],
                        'bottom' => ['borderStyle' => Border::BORDER_THIN],
                        'left'   => ['borderStyle' => Border::BORDER_THIN],
                        'right'  => ['borderStyle' => Border::BORDER_THIN]
                    ],
                ]
            );

            $title->applyFromArray(
                [
                    'font' => [
                        'name'  => 'Calibri',
                        'bold'  => true,
                        'italic' => false,
                        'size'  => 12
                    ],
                ]
            );

            $sharedStyle2->applyFromArray(
                [
                    'font' => [
                        'name'  => 'Calibri',
                        'bold'  => false,
                        'italic' => false,
                        'size'  => 10
                    ],
                    'borders' => [
                        'top'    => ['borderStyle' => Border::BORDER_THIN],
                        'bottom' => ['borderStyle' => Border::BORDER_THIN],
                        'left'   => ['borderStyle' => Border::BORDER_THIN],
                        'right'  => ['borderStyle' => Border::BORDER_THIN]
                    ],
                    'alignment' => [
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                ]
            );


            $sharedStyle3->applyFromArray(
                [
                    'font' => [
                        'name'  => 'Calibri',
                        'bold'  => false,
                        'italic' => false,
                        'size'  => 10
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                ]
            );
            $spreadsheet->getDefaultStyle()
                ->getFont()
                ->setName('Calibri')
                ->setSize(9);
            foreach (range('A', 'E') as $columnID) {
                $spreadsheet->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
            }
            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A1', 'User Customer');
            $spreadsheet->getActiveSheet()->duplicateStyle($title, 'A1');
            $spreadsheet->getActiveSheet()->setTitle('User Customer');
            $spreadsheet->getActiveSheet()->mergeCells("A1:E1");
            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A2', 'No')
                ->setCellValue('B2', 'Username')
                ->setCellValue('C2', 'Nama Toko')
                ->setCellValue('D2', 'Password')
                ->setCellValue('E2', 'Status');

            $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle1, 'A2:E2');
            $spreadsheet->getActiveSheet()->getStyle('A2:E2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('CCFFFF');

            $kolom = 3;
            $nomor = 1;
            foreach ($query->result() as $row) {
                $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue('A' . $kolom, $nomor)
                    ->setCellValue('B' . $kolom, $row->username)
                    ->setCellValue('C' . $kolom, $row->e_name)
                    ->setCellValue('D' . $kolom, decrypt_password($row->e_password))
                    ->setCellValue('E' . $kolom, $row->f_active);
                $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle2, 'A' . $kolom . ':E' . $kolom);

                $kolom++;
                $nomor++;
            }
            $writer = new Xls($spreadsheet);
            $nama_file = "User_customer.xls";
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename=' . $nama_file . '');
            header('Cache-Control: max-age=0');
            ob_end_clean();
            ob_start();
            $writer->save('php://output');
        } else {
            echo "<center><h1>Tidak Ada Data :)</h1></center>";
        }
    }
}
