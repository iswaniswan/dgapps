<?php
defined('BASEPATH') or exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Style;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Conditional;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Style\Protection;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class Customer extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        cek_session();
        $this->load->model('M_customer');
    }

    public $folder = 'customer';

    public function index()
    {
        add_js(
            array(
                'global_assets/js/plugins/tables/datatables/datatables.min.js',
                'global_assets/js/plugins/notifications/sweet_alert.min.js',
                'global_assets/js/plugins/forms/selects/select2.min.js',
                'assets/js/customer/index.js?v=1',
            )
        );
        $this->Logger->write(null, null, 'Membuka Menu Customer');
        $this->template->load('template', $this->folder . '/index');
    }

    public function serverside()
    {
        echo $this->M_customer->serverside();
    }

    public function view()
    {
        $id = $this->uri->segment('3');
        $id = decrypt_url($id);
        if (!$id) {
            redirect('customer', 'refresh');
        }
        $cek_data = $this->M_customer->cek_data($id);

        if ($cek_data) {
            add_key(
                array(
                    "var i_customer = '$id';",
                )
            );
            add_js(
                array(
                    'global_assets/js/plugins/tables/datatables/datatables.min.js',
                    'global_assets/js/plugins/forms/selects/select2.min.js',
                    'global_assets/js/plugins/notifications/sweet_alert.min.js',
                    'assets/js/customer/view.js?v=2',
                )
            );
            $data = array(
                'data_customer' => $this->M_customer->data_customer($id)->row(),
            );
            $this->Logger->write(null, null, 'Membuka Menu Customer View ' . $id);
            $this->template->load('template', $this->folder . '/view', $data);
        } else {
            redirect('customer', 'refresh');
        }

    }

    public function view_serverside()
    {
        $id = $this->uri->segment('3');
        echo $this->M_customer->view_serverside($id);
    }

    public function getlocation()
    {
        $id = $this->input->post('i_customer');
        $data = $this->M_customer->data_customer($id)->result_array();

        echo json_encode($data);
    }

    public function change_address()
    {
        $i_customer = $this->input->post('i_customer');
        $data = $this->input->post('data');
        $address = $data[0];
        $latitude = $data[1];
        $longitude = $data[2];

        $pos1 = strpos($latitude, ",");
        $pos2 = strpos($longitude, ",");

        if ($latitude == '' || $longitude == '') {
            $status = 'failed';
        } else if ($pos1 !== false || $pos2 !== false) {
            $status = 'failed';
        } else {
            $this->M_customer->change_address($i_customer, $address, $latitude, $longitude);
            $status = 'success';
        }

        $data = array(
            'status' => $status,
            'url' => base_url('customer'),
        );
        $this->Logger->write(null, null, 'Mengganti Alamat Customer ' . $i_customer);
        echo json_encode($data);

    }

    public function change_status()
    {
        $i_customer = $this->input->post('i_customer');
        $i_company = $this->input->post('i_company', true);
        $f_status = $this->input->post('val', true);

        $data = array(
            'f_active' => $f_status,
            'modifiedat' => current_datetime(),
        );

        $this->db->where('i_customer', $i_customer);
        $this->db->where('i_company', $i_company);
        $this->db->update("tbl_customer", $data);

        $this->Logger->write(null, null, 'Update Status Pelanggan : ' . $i_customer . ' Menjadi : ' . $f_status);

        $data = array(
            'status' => true,
        );
        echo json_encode($data);
    }

    public function create_new_location()
    {
        $i_customer = $this->input->post('i_customer');
        $i_company = $this->session->userdata('i_company');
        $latitude = $this->input->post('latitude');
        $longitude = $this->input->post('longitude');
        $keterangan = $this->input->post('keterangan');
        $username = $this->session->userdata('username');

        $data = [
            'i_customer' => $i_customer,
            'i_company' => $i_company,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'keterangan' => $keterangan,
            'username' => $username
        ];
        $this->M_customer->create_new_location($data);
        redirect($_SERVER['HTTP_REFERER'], 'refresh');
    }

    public function edit_location()
    {
        $i_customer = $this->input->post('i_customer');
        $i_company = $this->session->userdata('i_company');
        $id = $this->input->post('id');
        $latitude = $this->input->post('latitude');
        $longitude = $this->input->post('longitude');
        $keterangan = $this->input->post('keterangan');
        $username = $this->session->userdata('username');

        $data = [
            'i_customer' => $i_customer,
            'i_company' => $i_company,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'keterangan' => $keterangan,
            'username' => $username,
            'd_update' => current_datetime()
        ];
        $this->M_customer->update_location($id, $data);
        redirect($_SERVER['HTTP_REFERER'], 'refresh');
    }

    public function view_all_location()
    {
        $id = $this->uri->segment('3');
        echo $this->M_customer->view_all_location($id);
    }

    public function delete_location()
    {
        $id = $this->input->post('id');
        $this->M_customer->delete_location($id);
        redirect($_SERVER['HTTP_REFERER'], 'refresh');
    }

    public function export()
    {
        /** Style And Create New Spreedsheet */
        $spreadsheet = new Spreadsheet;
        $sharedTitle = new Style();
        $sharedStyle1 = new Style();
        $sharedStyle2 = new Style();
        $sharedStyle3 = new Style();
        $sharedTitle->applyFromArray(
            [
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ],
                'font' => [
                    'name' => 'Arial',
                    'bold' => true,
                    'size' => 16
                ],
            ]
        );

        $sharedStyle1->applyFromArray(
            [
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ],
                'font' => [
                    'name' => 'Arial',
                    'bold' => true,
                    'italic' => false,
                    'size' => 14
                ],
            ]
        );

        $sharedStyle2->applyFromArray(
            [
                'font' => [
                    'name' => 'Arial',
                    'bold' => false,
                    'italic' => false,
                    'size' => 11
                ],
                /* 'borders' => [
                'left' => ['borderStyle' => Border::BORDER_THIN],
                'right' => ['borderStyle' => Border::BORDER_THIN]
                ], */
                'borders' => [
                    'top' => ['borderStyle' => Border::BORDER_THIN],
                    'bottom' => ['borderStyle' => Border::BORDER_THIN],
                    'left' => ['borderStyle' => Border::BORDER_THIN],
                    'right' => ['borderStyle' => Border::BORDER_THIN]
                ],
            ]

        );

        $sharedStyle3->applyFromArray(
            [
                'font' => [
                    'name' => 'Arial',
                    'bold' => true,
                    'italic' => false,
                    'size' => 11,
                ],
                'borders' => [
                    'top' => ['borderStyle' => Border::BORDER_THIN],
                    'bottom' => ['borderStyle' => Border::BORDER_THIN],
                    'left' => ['borderStyle' => Border::BORDER_THIN],
                    'right' => ['borderStyle' => Border::BORDER_THIN]
                ],
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ],
            ]
        );
        /** End Style */

        /** Start Sheet */
        $spreadsheet = new Spreadsheet();
        $spreadsheet->getActiveSheet()->setShowGridlines(false);
        $sheet = $spreadsheet->getActiveSheet();
        $abjad = range('A', 'Z');
        $satu = 1;
        $dua = 2;
        $tiga = 3;
        $h1 = 1;
        $h2 = 2;
        $h3 = 3;
        $h4 = 4;
        $h5 = 5;
        $header = [
            'No',
            'Kode Area',
            'Nama Area',
            'Kode Customer',
            'Nama Customer',
            'Kode Harga',
            'Nama Harga',
            'Kontak',
            'No. Telepon',
            'Alamat',
            'Kota',
            'Langitude',
            'Longitude',
            'Status Aktif',
        ];
        /* STYLE HEADER */
        $spreadsheet->getActiveSheet()->duplicateStyle($sharedTitle, $abjad[0] . $h1);
        $spreadsheet->getActiveSheet()->freezePane($abjad[5] . $h4);
        $spreadsheet->getActiveSheet()->setAutoFilter($abjad[0] . $h3 . ":" . $abjad[count($header) - 1] . $h3);

        /* SET HEADER */
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A1', "Data Customer");
        for ($i = 0; $i < count($header); $i++) {
            $spreadsheet->setActiveSheetIndex(0)->setCellValue($abjad[$i] . $h3, $header[$i]);
        }
        $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, $abjad[0] . $h3 . ":" . $abjad[count($header) - 1] . $h3);
        $spreadsheet->getActiveSheet()->getStyle($abjad[0] . $h3 . ":" . $abjad[count($header) - 1] . $h3)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('CCFFCC');
        $spreadsheet->getActiveSheet()->mergeCells($abjad[0] . $h1 . ":" . $abjad[count($header) - 1] . $h1);
        $spreadsheet->getActiveSheet()->setTitle('Customer');
        $spreadsheet->getActiveSheet()->getColumnDimension($abjad[0] . ":" . $abjad[count($header)])->setAutoSize(true);

        $i_company = $this->session->userdata('i_company');
        /* ISI */
        $j = 4;
        $x = 4;
        $no = 0;
        $query = $this->db->query(
            "SELECT b.i_area, b.e_area_name, i_customer, e_customer_name, a.i_price_group, c.e_price_groupname, 
                a.e_contact_name, a.e_phone_number, e_customer_address, d.e_city_name, a.latitude, a.longitude, 
                CASE WHEN a.f_active = TRUE THEN 'Aktif' ELSE 'Tidak Aktif' END AS status
            FROM tbl_customer a
            INNER JOIN tbl_area b ON (b.i_area = a.i_area AND a.i_company = b.i_company)
            INNER JOIN tbl_price_group c ON (c.i_price_group = a.i_price_group AND a.i_company = c.i_company)
            LEFT JOIN tbl_city d ON (d.i_city = a.i_city AND a.i_company = d.i_company)
            WHERE a.i_company = '$i_company'
            ORDER BY 1,2,4,3"
        );
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $no++;
                $isi = [
                    $no,
                    trim($row->i_area),
                    trim($row->e_area_name),
                    trim($row->i_customer),
                    trim($row->e_customer_name),
                    trim($row->i_price_group),
                    trim($row->e_price_groupname),
                    trim($row->e_contact_name),
                    trim($row->e_phone_number),
                    trim($row->e_customer_address),
                    trim($row->e_city_name),
                    trim($row->latitude),
                    trim($row->longitude),
                    trim($row->status),
                ];
                /* SET ISI */
                for ($i = 0; $i < count($isi); $i++) {
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue($abjad[$i] . $j, $isi[$i]);
                }
                $j++;
            }

            // die;
        }
        $y = $j - 1;
        $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle2, $abjad[0] . $x . ":" . $abjad[count($header) - 1] . $y);
        $writer = new Xls($spreadsheet);
        $nama_file = "Data_Customer.xls";
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename=' . $nama_file . '');
        header('Cache-Control: max-age=0');
        ob_end_clean();
        ob_start();
        $writer->save('php://output');
    }
}
