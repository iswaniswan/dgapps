<?php
defined('BASEPATH') or exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Report extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        cek_session();
    }

    public function index()
    {
        add_js(
            array(
                'global_assets/js/plugins/extensions/jquery_ui/interactions.min.js',
                'global_assets/js/plugins/extensions/jquery_ui/widgets.min.js',
                'global_assets/js/plugins/buttons/spin.min.js',
                'global_assets/js/plugins/buttons/ladda.min.js',
                'global_assets/js/plugins/forms/selects/select2.min.js',
                'assets/js/report/index.js?v=1',
            )
        );
        $this->Logger->write(null, null, 'Membuka Menu Report');
        $this->template->load('template', 'report/index');
    }

    public function num2alpha($n){
        for($r = ""; $n >= 0; $n = intval($n / 26) - 1)
            $r = chr($n%26 + 0x41) . $r;
        return $r;
    }

    public function export()
    {
        $dfrom = date("Y-m-d", strtotime($this->input->post('dfrom')));
        $dto = date("Y-m-d", strtotime($this->input->post('dto')));
        $type = $this->input->post('type');
        $tahun = $this->input->post('tahun');

        $p_dfrom = date("Ym", strtotime($this->input->post('dfrom')));
        $p_dto = date("Ym", strtotime($this->input->post('dto')));

        $this->load->library('custom');
        $username = $this->session->userdata('username');
        $username_downline = $this->custom->username_downline($username);
        $i_company = $this->session->userdata('i_company');

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $styleArray = [
            'font' => [
                'bold' => true,
            ],
            'borders' => [
                'bottom' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ];

        if ($type == 'sfa_attendance') {

            $sheet->setCellValue('A1', 'No');
            $sheet->setCellValue('B1', 'Username');
            $sheet->setCellValue('C1', 'Staff ID');
            $sheet->setCellValue('D1', 'Staff Name');
            $sheet->setCellValue('E1', 'HP');
            $sheet->setCellValue('F1', 'Role');
            $sheet->setCellValue('G1', 'Upline');
            $sheet->setCellValue('H1', 'Date');
            $sheet->setCellValue('I1', 'Login Time');
            $sheet->getStyle('A1:I1')->applyFromArray($styleArray);

            foreach (range('A', 'I') as $columnID) {
                $sheet->getColumnDimension($columnID)->setAutoSize(true);
            }

            $target_absen = $this->db->query("select count(username) as jumlah from tbl_user where i_company = '$i_company' and username in($username_downline)")->row()->jumlah;
            $date1 = new DateTime($dfrom);
            $date2 = new DateTime($dto);
            $jumlah_tgl = $date1->diff($date2)->d + 1;

            $target_absen = $target_absen * $jumlah_tgl;

            $no = 1;
            $i = 2;
            $hadir = 0;

            $query = $this->db->query("select a.username, b.i_staff, b.e_name, b.phone, c.e_role_name, b.username_upline, a.d_login, a.createdat from tbl_user_login a, tbl_user b, tbl_user_role c
			where
			a.i_company = b.i_company
			and a.i_company = c.i_company
			and b.i_company = c.i_company
			and a.username = b.username
			and b.i_role = c.i_role
			and a.d_login >= '$dfrom'
			and a.d_login <= '$dto'
			and a.i_company = '$i_company'
			and a.username in($username_downline)");

            if ($query->num_rows() > 0) {
                foreach ($query->result() as $row) {

                    $sheet->setCellValue('A' . $i, $no);
                    $sheet->setCellValue('B' . $i, $row->username);
                    $sheet->setCellValue('C' . $i, $row->i_staff);
                    $sheet->setCellValue('D' . $i, $row->e_name);
                    $sheet->setCellValue('E' . $i, $row->phone);
                    $sheet->setCellValue('F' . $i, $row->e_role_name);
                    $sheet->setCellValue('G' . $i, $row->username_upline);
                    $sheet->setCellValue('H' . $i, date("d-m-Y", strtotime($row->d_login)));
                    $sheet->setCellValue('I' . $i, date("d-m-Y H:i:s", strtotime($row->createdat)));
                    $i++;
                    $no++;
                    $hadir++;
                }
            }

            $i = $i + 3;
            $sheet->setCellValue('A' . $i, 'Start Date');
            $sheet->setCellValue('B' . $i, 'End Date');
            $sheet->setCellValue('C' . $i, 'Target Absen');
            $sheet->setCellValue('D' . $i, 'Jumlah Hadir');
            $sheet->setCellValue('E' . $i, 'Jumlah yang tidak Hadir');
            $sheet->getStyle('A' . $i . ':E' . $i)->applyFromArray($styleArray);

            $i++;
            $sheet->setCellValue('A' . $i, date("d-m-Y", strtotime($dfrom)));
            $sheet->setCellValue('B' . $i, date("d-m-Y", strtotime($dto)));
            $sheet->setCellValue('C' . $i, $target_absen);
            $sheet->setCellValue('D' . $i, $hadir);
            $sheet->setCellValue('E' . $i, $target_absen - $hadir);

            $writer = new Xlsx($spreadsheet);

            $filename = 'SFA Attendance';

            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
            header('Cache-Control: max-age=0');
            ob_start();
            $writer->save('php://output');
            $xlsData = ob_get_contents();
            ob_end_clean();

            $response = array(
                'name' => $filename . '.xlsx',
                'file' => "data:application/vnd.ms-excel;base64," . base64_encode($xlsData),
            );
            $this->Logger->write(null, null, 'Download Report Kehadiran');
            echo json_encode($response);

        } elseif ($type == 'sales_order') {

            $sheet->setCellValue('A1', 'No Order');
            $sheet->setCellValue('B1', 'Date Order');
            $sheet->setCellValue('C1', 'Username');
            $sheet->setCellValue('D1', 'Staff ID');
            $sheet->setCellValue('E1', 'Staff Name');
            $sheet->setCellValue('F1', 'Customer ID');
            $sheet->setCellValue('G1', 'Customer Name');
            $sheet->setCellValue('H1', 'Area');
            $sheet->setCellValue('I1', 'Sales Order Total');
            $sheet->setCellValue('J1', 'Discount Total');
            $sheet->setCellValue('K1', 'Nett Total');
            $sheet->setCellValue('L1', 'Product ID');
            $sheet->setCellValue('M1', 'Product Name');
            $sheet->setCellValue('N1', 'Price/Unit');
            $sheet->setCellValue('O1', 'Order Qty');
            $sheet->setCellValue('P1', 'Subtotal');
            $sheet->setCellValue('Q1', 'Comment');
            $sheet->setCellValue('R1', 'Status');
            $sheet->getStyle('A1:R1')->applyFromArray($styleArray);

            foreach (range('A', 'R') as $columnID) {
                $sheet->getColumnDimension($columnID)->setAutoSize(true);
            }

            $i = 2;

            $query = $this->db->query("select a.i_spb, b.d_spb, b.username, c.i_staff, c.e_name, b.i_customer, d.e_customer_name, b.i_area, b.v_spb_gross, b.v_spb_discounttotal, b.v_spb_netto,
			a.i_product, a.e_product_name, a.v_unit_price, a.n_order, (a.v_unit_price * a.n_order) as subtotal, a.e_remark, b.f_spb_cancel, b.f_status_transfer
			from tbl_spb_item a, tbl_spb b, tbl_user c, tbl_customer d
			where a.i_company = b.i_company
			and a.i_company = c.i_company
			and a.i_company = d.i_company
			and b.i_company = c.i_company
			and b.i_company = d.i_company
			and c.i_company = d.i_company
			and a.i_spb = b.i_spb
			and a.i_area = b.i_area
			and b.username = c.username
			and b.i_customer = d.i_customer
			and a.i_company = '$i_company'
			and b.d_spb >= '$dfrom'
			and b.d_spb <= '$dto'
			and b.username in($username_downline)");

            if ($query->num_rows() > 0) {
                foreach ($query->result() as $row) {

                    if ($row->f_spb_cancel == 't') {
                        $status = 'Cancel';
                    } else {
                        if ($row->f_status_transfer == 't') {
                            $status = 'Transfer';
                        } else {
                            $status = 'Pending';
                        }
                    }

                    $sheet->setCellValue('A' . $i, $row->i_spb);
                    $sheet->setCellValue('B' . $i, $row->d_spb);
                    $sheet->setCellValue('C' . $i, $row->username);
                    $sheet->setCellValue('D' . $i, $row->i_staff);
                    $sheet->setCellValue('E' . $i, $row->e_name);
                    $sheet->setCellValue('F' . $i, $row->i_customer);
                    $sheet->setCellValue('G' . $i, $row->e_customer_name);
                    $sheet->setCellValue('H' . $i, $row->i_area);
                    $sheet->setCellValue('I' . $i, $row->v_spb_gross);
                    $sheet->setCellValue('J' . $i, $row->v_spb_discounttotal);
                    $sheet->setCellValue('K' . $i, $row->v_spb_netto);
                    $sheet->setCellValue('L' . $i, $row->i_product);
                    $sheet->setCellValue('M' . $i, $row->e_product_name);
                    $sheet->setCellValue('N' . $i, $row->v_unit_price);
                    $sheet->setCellValue('O' . $i, $row->n_order);
                    $sheet->setCellValue('P' . $i, $row->subtotal);
                    $sheet->setCellValue('Q' . $i, $row->e_remark);
                    $sheet->setCellValue('R' . $i, $status);
                    $sheet->getStyle('I' . $i)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    $sheet->getStyle('J' . $i)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    $sheet->getStyle('K' . $i)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    $sheet->getStyle('N' . $i)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    $sheet->getStyle('O' . $i)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    $sheet->getStyle('P' . $i)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    $i++;
                }
            }

            $i = $i + 3;
            $sheet->setCellValue('A' . $i, 'Start Date');
            $sheet->setCellValue('B' . $i, 'End Date');
            $sheet->getStyle('A' . $i . ':B' . $i)->applyFromArray($styleArray);

            $i++;
            $sheet->setCellValue('A' . $i, date("d-m-Y", strtotime($dfrom)));
            $sheet->setCellValue('B' . $i, date("d-m-Y", strtotime($dto)));

            $writer = new Xlsx($spreadsheet);

            $filename = 'Sales Order';

            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
            header('Cache-Control: max-age=0');
            ob_start();
            $writer->save('php://output');
            $xlsData = ob_get_contents();
            ob_end_clean();

            $response = array(
                'name' => $filename . '.xlsx',
                'file' => "data:application/vnd.ms-excel;base64," . base64_encode($xlsData),
            );
            $this->Logger->write(null, null, 'Download Report Sales Order');
            echo json_encode($response);

          } elseif ($type == 'call_report') {

            $sheet->setCellValue('A1', 'No');
            $sheet->setCellValue('B1', 'Area');
            $sheet->setCellValue('C1', 'Kode Sales');
            $sheet->setCellValue('D1', 'Nama Sales');
            $sheet->setCellValue('E1', 'Juml Kunjungan');
            $sheet->setCellValue('F1', 'Juml Order');
            $sheet->setCellValue('G1', '% Hasil Kunjungan');
            $sheet->setCellValue('H1', 'Hari Kerja');
            $sheet->setCellValue('I1', 'Rata2 Kunjungan Perhari');
            // $sheet->setCellValue('I1', 'Efektif Kunjungan');
            $sheet->getStyle('A1:I1')->applyFromArray($styleArray);

            foreach (range('A', 'I') as $columnID) {
                $sheet->getColumnDimension($columnID)->setAutoSize(true);
            }

            $i = 2;

            $where = '';
            if ($this->session->userdata('i_role') != '1' || $this->session->userdata('i_role') != '2') {
                $where = ' and b.username in($username_downline)';
            }
            $query = $this->db->query("
                WITH cte AS (
                    select  i_company,username, d_checkin, i_customer, 1 as kunjungan from tbl_customer_checkin a 
                    where i_company = '$i_company' and d_checkin between '$dfrom' and '$dto'
                    group by  i_company,username, d_checkin, i_customer
                    order by  username, d_checkin,i_customer
                )

                select x.*, cast((x.n_order * 100 /  x.kunjungan)::numeric as decimal(10,2)) as persen, string_agg(c.e_area_name,', ') as area, e_name, i_staff   from (
                    select i_company, username, sum(kunjungan) as kunjungan,  sum(n_order) as n_order from (
                        select i_company, username, sum(kunjungan) as kunjungan, 0 as n_order from cte group by i_company, username
                        union all
                        select i_company, username, 0 as kunjungan, sum(n_order) as n_order from (
                            select a.i_company, a.username, 1 as n_order from tbl_spb a
                            inner join cte b on (a.i_company = b.i_company and a.username = b.username and a.d_spb = b.d_checkin and a.i_customer = b.i_customer)
                            where d_spb between '$dfrom' and '$dto' and a.i_company = '$i_company' and a.f_status_transfer = 't' group by a.i_company, a.username, a.d_spb, a.i_customer
                        ) as x
                        group by i_company, username
                    ) as x 
                    group by i_company, username
                ) as x
                inner join tbl_user_area a on (x.i_company = a.i_company and x.username = a.username)
                inner join tbl_user b on (x.i_company = b.i_company and x.username = b.username)
                inner join tbl_area c on (a.i_area = c.i_area and a.i_company = c.i_company)
                group by x.i_company, x.username, x.kunjungan, x.n_order, b.e_name, b.i_staff

            ");

            if ($query->num_rows() > 0) {

                foreach ($query->result() as $row) {

                    $sheet->setCellValue('A' . $i, $i-1);
                    $sheet->setCellValue('B' . $i, $row->area);
                    $sheet->setCellValue('C' . $i, $row->i_staff);
                    $sheet->setCellValue('D' . $i, $row->e_name);
                    $sheet->setCellValue('E' . $i, $row->kunjungan);
                    $sheet->setCellValue('F' . $i, $row->n_order);
                    $sheet->setCellValue('G' . $i, $row->persen);
                    $sheet->setCellValue('H' . $i, "");
                    $sheet->setCellValue('I' . $i, '=E'.$i.'/'.'H'.$i );
                    // $sheet->getStyle('I' . $i)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    // $sheet->getStyle('J' . $i)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    // $sheet->getStyle('K' . $i)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    // $sheet->getStyle('N' . $i)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    // $sheet->getStyle('O' . $i)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    // $sheet->getStyle('P' . $i)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    $i++;
                }
            }

            $i = $i + 3;
            $sheet->setCellValue('A' . $i, 'Start Date');
            $sheet->setCellValue('B' . $i, 'End Date');
            $sheet->getStyle('A' . $i . ':B' . $i)->applyFromArray($styleArray);

            $i++;
            $sheet->setCellValue('A' . $i, date("d-m-Y", strtotime($dfrom)));
            $sheet->setCellValue('B' . $i, date("d-m-Y", strtotime($dto)));

            $writer = new Xlsx($spreadsheet);

            $filename = 'Sales Order';

            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
            header('Cache-Control: max-age=0');
            ob_start();
            $writer->save('php://output');
            $xlsData = ob_get_contents();
            ob_end_clean();

            $response = array(
                'name' => $filename . '.xlsx',
                'file' => "data:application/vnd.ms-excel;base64," . base64_encode($xlsData),
            );
            $this->Logger->write(null, null, 'Download Report Sales Order');
            echo json_encode($response);

        } elseif ($type == 'calldetail_report') {

            $sheet->setCellValue('A1', 'No');
            $sheet->setCellValue('B1', 'Kode Area');
            $sheet->setCellValue('C1', 'Nama Area');
            $sheet->setCellValue('D1', 'Kode Sales');
            $sheet->setCellValue('E1', 'Nama Sales');
            $sheet->setCellValue('F1', 'Kode Customer');
            $sheet->setCellValue('G1', 'Nama Customer');
            $sheet->setCellValue('H1', 'Tanggal');
            $sheet->setCellValue('I1', 'Sesuai RRKH');
            $sheet->setCellValue('J1', 'Waktu Check In');
            $sheet->setCellValue('K1', 'Waktu Check Out');
            $sheet->setCellValue('L1', 'Durasi');
            $sheet->setCellValue('M1', 'Net Order');
            $sheet->setCellValue('N1', 'Status SPB');
            $sheet->setCellValue('O1', 'Dokumentasi');
            $sheet->setCellValue('P1', 'Tipe Saran');
            $sheet->setCellValue('Q1', 'Saran');
            // $sheet->setCellValue('I1', 'Efektif Kunjungan');
            $sheet->getStyle('A1:Q1')->applyFromArray($styleArray);

            foreach (range('A', 'Q') as $columnID) {
                $sheet->getColumnDimension($columnID)->setAutoSize(true);
            }

            $i = 2;

            $where = '';
            if ($this->session->userdata('i_role') != '1' || $this->session->userdata('i_role') != '2') {
                //$where = ' and c.username in ($username_downline)';
            }
            $query = $this->db->query("
                select a.i_area, b.e_area_name, c.i_staff , c.e_name, a.i_customer, i.e_customer_name ,a.d_checkin,
                case when d_rrkh is not null then 'Ya' else 'Tidak' end as rrkh ,
                a.createdat_checkin, a.createdat_checkout , to_char((a.createdat_checkout - a.createdat_checkin), 'HH24:MI') as durasi,
                e.v_spb_netto , case when e.f_spb_cancel = 't' then 'Batal' when e.f_spb_cancel = 'f' and v_spb_netto is not null then 'Transfer' else '' end as status,
                f.e_foto , h.e_saran_typename , g.e_saran
                from tbl_customer_checkin a 
                inner join tbl_user c on (a.username = c.username and c.i_company = a.i_company)
                left join tbl_rrkh d on (a.username = d.username and a.i_company = d.i_company and a.d_checkin = d.d_rrkh and a.i_customer = d.i_customer)
                left join tbl_spb e on (a.username = e.username and a.i_company = e.i_company and a.d_checkin = e.d_spb and a.i_customer = e.i_customer)
                left join tbl_customer_dokumentasi f on (a.username = f.username and a.i_company = f.i_company and a.d_checkin = f.d_dokumentasi and a.i_customer = f.i_customer)
                left join tbl_customer_saran g on (a.username = g.username and a.i_company = g.i_company and a.d_checkin = g.d_saran and a.i_customer = g.i_customer)
                left join tbl_saran_type h on (g.i_company = h.i_company and g.i_saran_type = h.i_saran_type)
                left join tbl_customer i on (a.i_company = i.i_company and a.i_customer = i.i_customer)
                inner join tbl_area b on (i.i_area = b.i_area and b.i_company = i.i_company)
                where a.i_company = '$i_company' and a.d_checkin between '$dfrom' and '$dto' 
                and a.i_area in (select i_area from tbl_user_area where username = '$username' and i_company = '$i_company')
                order by d_checkin, i_area, e_name

            ");

            if ($query->num_rows() > 0) {

                foreach ($query->result() as $row) {

                    $sheet->setCellValue('A1', 'No');
                    $sheet->setCellValue('B1', 'Kode Area');
                    $sheet->setCellValue('C1', 'Nama Area');
                    $sheet->setCellValue('D1', 'Kode Sales');
                    $sheet->setCellValue('E1', 'Nama Sales');
                    $sheet->setCellValue('F1', 'Kode Customer');
                    $sheet->setCellValue('G1', 'Nama Customer');
                    $sheet->setCellValue('H1', 'Tanggal');
                    $sheet->setCellValue('I1', 'Sesuai RRKH');
                    $sheet->setCellValue('J1', 'Waktu Check In');
                    $sheet->setCellValue('K1', 'Waktu Check Out');
                    $sheet->setCellValue('L1', 'Durasi');
                    $sheet->setCellValue('M1', 'Net Order');
                    $sheet->setCellValue('N1', 'Status SPB');
                    $sheet->setCellValue('O1', 'Dokumentasi');
                    $sheet->setCellValue('P1', 'Tipe Saran');
                    $sheet->setCellValue('Q1', 'Saran');

                    $sheet->setCellValue('A' . $i, $i-1);
                    $sheet->setCellValue('B' . $i, $row->i_area);
                    $sheet->setCellValue('C' . $i, $row->e_area_name);
                    $sheet->setCellValue('D' . $i, $row->i_staff);
                    $sheet->setCellValue('E' . $i, $row->e_name);
                    $sheet->setCellValue('F' . $i, $row->i_customer);
                    $sheet->setCellValue('G' . $i, $row->e_customer_name);
                    $sheet->setCellValue('H' . $i, $row->d_checkin);
                    $sheet->setCellValue('I' . $i, $row->rrkh);
                    $sheet->setCellValue('J' . $i, $row->createdat_checkin);
                    $sheet->setCellValue('K' . $i, $row->createdat_checkout);
                    $sheet->setCellValue('L' . $i, $row->durasi);
                    $sheet->setCellValue('M' . $i, $row->v_spb_netto);
                    $sheet->setCellValue('N' . $i, $row->status);
                    $sheet->setCellValue('O' . $i, $row->e_foto);
                    $sheet->setCellValue('P' . $i, $row->e_saran_typename);
                    $sheet->setCellValue('Q' . $i, $row->e_saran);
                    // $sheet->getStyle('I' . $i)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    // $sheet->getStyle('J' . $i)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    // $sheet->getStyle('K' . $i)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    // $sheet->getStyle('N' . $i)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    // $sheet->getStyle('O' . $i)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    // $sheet->getStyle('P' . $i)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    $i++;
                }
            }

            $i = $i + 3;
            $sheet->setCellValue('A' . $i, 'Start Date');
            $sheet->setCellValue('B' . $i, 'End Date');
            $sheet->getStyle('A' . $i . ':B' . $i)->applyFromArray($styleArray);

            $i++;
            $sheet->setCellValue('A' . $i, date("d-m-Y", strtotime($dfrom)));
            $sheet->setCellValue('B' . $i, date("d-m-Y", strtotime($dto)));

            $writer = new Xlsx($spreadsheet);

            $filename = 'Sales Call Detail';

            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
            header('Cache-Control: max-age=0');
            ob_start();
            $writer->save('php://output');
            $xlsData = ob_get_contents();
            ob_end_clean();

            $response = array(
                'name' => $filename . '.xlsx',
                'file' => "data:application/vnd.ms-excel;base64," . base64_encode($xlsData),
            );
            $this->Logger->write(null, null, 'Download Report Sales Call Detail');
            echo json_encode($response);

        } elseif ($type == 'customer_report') {

            $sheet->setCellValue('A1', 'Kode Toko');
            $sheet->setCellValue('B1', 'Nama Toko');
            $sheet->setCellValue('C1', 'Alamat');
            $sheet->setCellValue('D1', 'Kota');
            $sheet->setCellValue('E1', 'Provinsi');
            $sheet->setCellValue('F1', 'Status');
            $sheet->getStyle('A1:F1')->applyFromArray($styleArray);

            foreach (range('A', 'F') as $columnID) {
                $sheet->getColumnDimension($columnID)->setAutoSize(true);
            }

            $i = 2;

            $query = $this->db->query("select a.i_customer, a.e_customer_name, a.e_customer_address, b.e_city_name, c.e_area_name, a.f_active
			      from tbl_customer a, tbl_city b, tbl_area c
			      where a.i_company = b.i_company
			      and a.i_company = c.i_company
			      and b.i_company = c.i_company
			      and a.i_area = c.i_area
			      and a.i_city = b.i_city
			      and a.i_company = '$i_company'
            order by a.i_customer");

            if ($query->num_rows() > 0) {
                foreach ($query->result() as $row) {

                    if ($row->f_active == 't') {
                        $status = 'Aktif';
                    } else {
                        $status = 'Tidak Aktif';
                    }

                    $sheet->setCellValue('A' . $i, $row->i_customer);
                    $sheet->setCellValue('B' . $i, $row->e_customer_name);
                    $sheet->setCellValue('C' . $i, $row->e_customer_address);
                    $sheet->setCellValue('D' . $i, $row->e_city_name);
                    $sheet->setCellValue('E' . $i, $row->e_area_name);
                    $sheet->setCellValue('F' . $i, $status);
                    $i++;
                }
            }

            $i = $i + 3;

            $writer = new Xlsx($spreadsheet);

            $filename = 'Customer';

            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
            header('Cache-Control: max-age=0');
            ob_start();
            $writer->save('php://output');
            $xlsData = ob_get_contents();
            ob_end_clean();

            $response = array(
                'name' => $filename . '.xlsx',
                'file' => "data:application/vnd.ms-excel;base64," . base64_encode($xlsData),
            );
            $this->Logger->write(null, null, 'Download Report Customer');
            echo json_encode($response);

        } elseif ($type == 'lastvisit') {

            $sheet->setCellValue('A1', 'No');
            $sheet->setCellValue('B1', 'Kode Toko');
            $sheet->setCellValue('C1', 'Terakhir Kunjungan');
            // $sheet->setCellValue('I1', 'Efektif Kunjungan');
            $sheet->getStyle('A1:C1')->applyFromArray($styleArray);

            foreach (range('A', 'C') as $columnID) {
                $sheet->getColumnDimension($columnID)->setAutoSize(true);
            }

            $i = 2;

            $query = $this->db->query("

                select i_customer, max(d_checkin) as d_checkin from tbl_customer_checkin  
                where i_company = '$i_company' and i_area in (select i_area from tbl_user_area where username= '$username' and i_company = '$i_company')
                group by 1 order by 1

            ");

            if ($query->num_rows() > 0) {

                foreach ($query->result() as $row) {

                    $sheet->setCellValue('A' . $i, $i-1);
                    $sheet->setCellValue('B' . $i, $row->i_customer);
                    $sheet->setCellValue('C' . $i, $row->d_checkin);
                    $i++;
                }
            }

            $i = $i + 3;
            $sheet->setCellValue('A' . $i, 'Start Date');
            $sheet->setCellValue('B' . $i, 'End Date');
            $sheet->getStyle('A' . $i . ':B' . $i)->applyFromArray($styleArray);

            $i++;
            $sheet->setCellValue('A' . $i, '-');
            $sheet->setCellValue('B' . $i, '-');

            $writer = new Xlsx($spreadsheet);

            $filename = 'KunjunganToko';

            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
            header('Cache-Control: max-age=0');
            ob_start();
            $writer->save('php://output');
            $xlsData = ob_get_contents();
            ob_end_clean();

            $response = array(
                'name' => $filename . '.xlsx',
                'file' => "data:application/vnd.ms-excel;base64," . base64_encode($xlsData),
            );
            $this->Logger->write(null, null, 'Download Report Sales Order');
            echo json_encode($response);

        } elseif ($type == 'targettoko_report') {

            $data = $this->db->query("
                 select a.username, a.e_name , b.i_customer, coalesce(c.v_nota_target,0) as v_nota_target, c.i_periode from tbl_user_toko a
                 inner join tbl_user_toko_item b on (a.username = b.username)
                 inner join tbl_customer_target c on (b.i_customer = c.i_customer and b.id_company = c.id_company)
                 where c.i_periode = '$tahun' and b.id_company = '$i_company' and a.f_active = true and b.f_active = true 
            ", FALSE);

            $arrayTxt = '';
            $list_customer = array();
            if ($data->num_rows() > 0) {
                foreach ($data->result() as $row) {
                    array_push($list_customer, "''".$row->i_customer."''");
                }
                $arrayTxt = implode(',', $list_customer);
            }

            $sheet->setCellValue('A1', 'No');
            $sheet->setCellValue('B1', 'Nama Group');
            $sheet->setCellValue('C1', 'Target');
            $sheet->setCellValue('D1', 'Januari');
            $sheet->setCellValue('E1', 'Februari');
            $sheet->setCellValue('F1', 'Maret');
            $sheet->setCellValue('G1', 'April');
            $sheet->setCellValue('H1', 'Mei');
            $sheet->setCellValue('I1', 'Juni');
            $sheet->setCellValue('J1', 'July');
            $sheet->setCellValue('K1', 'Agustus');
            $sheet->setCellValue('L1', 'September');
            $sheet->setCellValue('M1', 'Oktober');
            $sheet->setCellValue('N1', 'November');
            $sheet->setCellValue('O1', 'Desember');
            $sheet->setCellValue('P1', 'Total Nota');
            $sheet->setCellValue('Q1', 'Sisa Target');
            $sheet->setCellValue('R1', '%');
            // $sheet->setCellValue('I1', 'Efektif Kunjungan');
            $sheet->getStyle('A1:R1')->applyFromArray($styleArray);

            foreach (range('A', 'R') as $columnID) {
                $sheet->getColumnDimension($columnID)->setAutoSize(true);
            }

            $i = 2;
            $date = $tahun. "-01-01";
            $query = $this->db->query("
                with cte as (
                     select a.username , a.e_name,  sum(coalesce(c.v_nota_target, 0)) as v_target
                     from tbl_user_toko a
                     inner join tbl_user_toko_item b on (a.username = b.username)
                     left join tbl_customer_target c on (b.i_customer = c.i_customer and b.id_company = c.id_company)
                     where b.id_company = '$i_company' and a.f_active = true and b.f_active = true 
                     group by 1, 2
                )
                select e_name, v_target, jan, feb, mar, apr, may, jun, jul, aug, sep, oct, nov, des,
                (jan + feb + mar + apr + may + jun + jul + aug + sep + oct + nov + des)  as total,
                v_target - (jan + feb + mar + apr + may + jun + jul + aug + sep + oct + nov + des) as sisa,
                (((jan + feb + mar + apr + may + jun + jul + aug + sep + oct + nov + des) / nullif(v_target,0))  * 100) as persen
                from (
                     SELECT x.username, y.e_name, y.v_target, coalesce(jan,0) as jan,
                     coalesce(feb,0) as feb,
                     coalesce(mar,0) as mar,
                     coalesce(apr,0) as apr,
                     coalesce(may,0) as may,
                     coalesce(jun,0) as jun,
                     coalesce(jul,0) as jul,
                     coalesce(aug,0) as aug,
                     coalesce(sep,0) as sep,
                     coalesce(oct,0) as oct,
                     coalesce(nov,0) as nov,
                     coalesce(des,0) as des from CROSSTAB (
                     $$
                         select username , bln::numeric , sum(v_nota_netto)::numeric 
                          from dblink('host=192.168.0.93 user=dedy password=g#>m[J2P^^ dbname=bcl port=5432',
                           '
                            select ''$i_company'' as id_company, i_customer,  to_number(to_char(d_nota, ''mm''), ''99'') AS bln, coalesce(sum(v_nota_netto), 0) as v_nota_netto from tm_nota 
                            where f_nota_cancel = false and to_char(d_nota , ''yyyy'') = ''$tahun'' and i_customer in ($arrayTxt)
                            group by 1,2,3
                           '
                           ) AS a (
                               id_company varchar(20), i_customer varchar(20), bln numeric, v_nota_netto numeric
                           ) 
                           inner join tbl_user_toko_item b on (a.id_company = b.id_company and a.i_customer =  b.i_customer)
                           group by 1,2
                     $$,
                     $$ SELECT (
                                select EXTRACT(MONTH from date_trunc('month', '$date'::date)::date + s.a * '1 month'::interval)
                           ) from generate_series(0, 11) as s(a)
                     $$
                      ) as x (
                        username text, jan numeric, feb numeric, mar numeric, apr numeric, may numeric, 
                        jun numeric, jul numeric, aug numeric, sep numeric, oct numeric, nov numeric, des numeric )
                     right join cte y on (x.username = y.username)
                ) as final
                order by 1 asc

            ");

            // var_dump($query->result());
            // die();
            if ($query->num_rows() > 0) {

                foreach ($query->result() as $row) {

                    $sheet->setCellValue('A' . $i, $i-1);
                    $sheet->setCellValue('B' . $i, $row->e_name);
                    $sheet->setCellValue('C' . $i, $row->v_target);
                    $sheet->setCellValue('D' . $i, $row->jan);
                    $sheet->setCellValue('E' . $i, $row->feb);
                    $sheet->setCellValue('F' . $i, $row->mar);
                    $sheet->setCellValue('G' . $i, $row->apr);
                    $sheet->setCellValue('H' . $i, $row->may);
                    $sheet->setCellValue('I' . $i, $row->jun);
                    $sheet->setCellValue('J' . $i, $row->jul);
                    $sheet->setCellValue('K' . $i, $row->aug);
                    $sheet->setCellValue('L' . $i, $row->sep);
                    $sheet->setCellValue('M' . $i, $row->oct);
                    $sheet->setCellValue('N' . $i, $row->nov);
                    $sheet->setCellValue('O' . $i, $row->des);
                    $sheet->setCellValue('P' . $i, $row->total);
                    $sheet->setCellValue('Q' . $i, $row->sisa);
                    $sheet->setCellValue('R' . $i, number_format($row->persen,2). " %");
                    // $sheet->setCellValue('I' . $i, '=E'.$i.'/'.'H'.$i );

                    // $sheet->getStyle('A1:C1')
                    $sheet->getStyle('C' . $i.':Q'.$i)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED);
                    // $sheet->getStyle('R' . $i)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_PERCENTAGE_00);
                    // $sheet->getStyle('K' . $i)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    // $sheet->getStyle('N' . $i)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    // $sheet->getStyle('O' . $i)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    // $sheet->getStyle('P' . $i)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    $i++;
                }
            }

            // $i = $i + 3;
            // $sheet->setCellValue('A' . $i, 'Start Date');
            // $sheet->setCellValue('B' . $i, 'End Date');
            // $sheet->getStyle('A' . $i . ':B' . $i)->applyFromArray($styleArray);

            // $i++;
            // $sheet->setCellValue('A' . $i, '-');
            // $sheet->setCellValue('B' . $i, '-');

            $writer = new Xlsx($spreadsheet);

            $filename = 'PencapaianToko_'.$tahun;

            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
            header('Cache-Control: max-age=0');
            ob_start();
            $writer->save('php://output');
            $xlsData = ob_get_contents();
            ob_end_clean();

            $response = array(
                'name' => $filename . '.xlsx',
                'file' => "data:application/vnd.ms-excel;base64," . base64_encode($xlsData),
            );
            $this->Logger->write(null, null, 'Download Report Sales Order');
            echo json_encode($response);

        } elseif ($type == 'targettoko_detail_report') {

            $data = $this->db->query("
                 select a.username, a.e_name , b.i_customer, coalesce(c.v_nota_target,0) as v_nota_target, c.i_periode from tbl_user_toko a
                 inner join tbl_user_toko_item b on (a.username = b.username)
                 inner join tbl_customer_target c on (b.i_customer = c.i_customer and b.id_company = c.id_company)
                 where c.i_periode = '$tahun' and b.id_company = '$i_company' and a.f_active = true and b.f_active = true 
            ", FALSE);

            $arrayTxt = '';
            $list_customer = array();
            if ($data->num_rows() > 0) {
                foreach ($data->result() as $row) {
                    array_push($list_customer, "''".$row->i_customer."''");
                }
                $arrayTxt = implode(',', $list_customer);
            }

            $sheet->setCellValue('A1', 'No');
            $sheet->setCellValue('B1', 'Nama Group');
            $sheet->setCellValue('C1', 'Kode Customer');
            $sheet->setCellValue('D1', 'Nama Customer');
            $sheet->setCellValue('E1', 'Target');
            $sheet->setCellValue('F1', 'Januari');
            $sheet->setCellValue('G1', 'Februari');
            $sheet->setCellValue('H1', 'Maret');
            $sheet->setCellValue('I1', 'April');
            $sheet->setCellValue('J1', 'Mei');
            $sheet->setCellValue('K1', 'Juni');
            $sheet->setCellValue('L1', 'July');
            $sheet->setCellValue('M1', 'Agustus');
            $sheet->setCellValue('N1', 'September');
            $sheet->setCellValue('O1', 'Oktober');
            $sheet->setCellValue('P1', 'November');
            $sheet->setCellValue('Q1', 'Desember');
            $sheet->setCellValue('R1', 'Total Nota');
            $sheet->setCellValue('S1', 'Sisa Target');
            $sheet->setCellValue('T1', '%');
            // $sheet->setCellValue('I1', 'Efektif Kunjungan');
            $sheet->getStyle('A1:T1')->applyFromArray($styleArray);

            foreach (range('A', 'T') as $columnID) {
                $sheet->getColumnDimension($columnID)->setAutoSize(true);
            }

            $i = 2;
            $date = $tahun. "-01-01";
            $query = $this->db->query("
                with cte as (
                     select a.username , a.e_name, b.i_customer, d.e_customer_name ,sum(coalesce(c.v_nota_target, 0)) as v_target
                     from tbl_user_toko a
                     inner join tbl_user_toko_item b on (a.username = b.username)
                     inner join tbl_customer d on (b.i_customer = d.i_customer and b.id_company = d.i_company)
                     left join tbl_customer_target c on (b.i_customer = c.i_customer and b.id_company = c.id_company)
                     where b.id_company = '$i_company' and a.f_active = true and b.f_active = true 
                     group by 1, 2,3,4
                )
                select e_name, i_customer, e_customer_name, v_target, jan, feb, mar, apr, may, jun, jul, aug, sep, oct, nov, des,
                (jan + feb + mar + apr + may + jun + jul + aug + sep + oct + nov + des)  as total,
                v_target - (jan + feb + mar + apr + may + jun + jul + aug + sep + oct + nov + des) as sisa,
                (((jan + feb + mar + apr + may + jun + jul + aug + sep + oct + nov + des) / nullif(v_target,0))  * 100) as persen
                from (
                     SELECT y.e_name, datas[2] as i_customer,y.e_customer_name, y.v_target, coalesce(jan,0) as jan,
                     coalesce(feb,0) as feb,
                     coalesce(mar,0) as mar,
                     coalesce(apr,0) as apr,
                     coalesce(may,0) as may,
                     coalesce(jun,0) as jun,
                     coalesce(jul,0) as jul,
                     coalesce(aug,0) as aug,
                     coalesce(sep,0) as sep,
                     coalesce(oct,0) as oct,
                     coalesce(nov,0) as nov,
                     coalesce(des,0) as des from CROSSTAB (
                     $$
                         select  Array[username::text, b.i_customer::text] as datas, bln::numeric , sum(v_nota_netto)::numeric 
                          from dblink('host=192.168.0.93 user=dedy password=g#>m[J2P^^ dbname=bcl port=5432',
                           '
                            select ''$i_company'' as id_company, i_customer,  to_number(to_char(d_nota, ''mm''), ''99'') AS bln, coalesce(sum(v_nota_netto), 0) as v_nota_netto from tm_nota 
                            where f_nota_cancel = false and to_char(d_nota , ''yyyy'') = ''$tahun'' and i_customer in ($arrayTxt)
                            group by 1,2,3
                           '
                           ) AS a (
                               id_company varchar(20), i_customer varchar(20), bln numeric, v_nota_netto numeric
                           ) 
                           inner join tbl_user_toko_item b on (a.id_company = b.id_company and a.i_customer =  b.i_customer)
                           group by 1,2
                     $$,
                     $$ SELECT (
                                select EXTRACT(MONTH from date_trunc('month', '$date'::date)::date + s.a * '1 month'::interval)
                           ) from generate_series(0, 11) as s(a)
                     $$
                      ) as x (
                        datas text[], jan numeric, feb numeric, mar numeric, apr numeric, may numeric, 
                        jun numeric, jul numeric, aug numeric, sep numeric, oct numeric, nov numeric, des numeric )
                     inner join cte y on (x.datas[1] = y.username and x.datas[2] = y.i_customer)
                ) as final
                order by 1,2,3 asc

            ");

            // var_dump($query->result());
            // die();
            if ($query->num_rows() > 0) {

                foreach ($query->result() as $row) {

                    $sheet->setCellValue('A' . $i, $i-1);
                    $sheet->setCellValue('B' . $i, $row->e_name);
                    $sheet->setCellValue('C' . $i, $row->i_customer);
                    $sheet->setCellValue('D' . $i, $row->e_customer_name);
                    $sheet->setCellValue('E' . $i, $row->v_target);
                    $sheet->setCellValue('F' . $i, $row->jan);
                    $sheet->setCellValue('G' . $i, $row->feb);
                    $sheet->setCellValue('H' . $i, $row->mar);
                    $sheet->setCellValue('I' . $i, $row->apr);
                    $sheet->setCellValue('J' . $i, $row->may);
                    $sheet->setCellValue('K' . $i, $row->jun);
                    $sheet->setCellValue('L' . $i, $row->jul);
                    $sheet->setCellValue('M' . $i, $row->aug);
                    $sheet->setCellValue('N' . $i, $row->sep);
                    $sheet->setCellValue('O' . $i, $row->oct);
                    $sheet->setCellValue('P' . $i, $row->nov);
                    $sheet->setCellValue('Q' . $i, $row->des);
                    $sheet->setCellValue('R' . $i, $row->total);
                    $sheet->setCellValue('S' . $i, $row->sisa);
                    $sheet->setCellValue('T' . $i, number_format($row->persen,2). " %");
                    // $sheet->setCellValue('I' . $i, '=E'.$i.'/'.'H'.$i );

                    // $sheet->getStyle('A1:C1')
                    $sheet->getStyle('E' . $i.':T'.$i)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED);
                    // $sheet->getStyle('R' . $i)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_PERCENTAGE_00);
                    // $sheet->getStyle('K' . $i)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    // $sheet->getStyle('N' . $i)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    // $sheet->getStyle('O' . $i)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    // $sheet->getStyle('P' . $i)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    $i++;
                }
            }

            // $i = $i + 3;
            // $sheet->setCellValue('A' . $i, 'Start Date');
            // $sheet->setCellValue('B' . $i, 'End Date');
            // $sheet->getStyle('A' . $i . ':B' . $i)->applyFromArray($styleArray);

            // $i++;
            // $sheet->setCellValue('A' . $i, '-');
            // $sheet->setCellValue('B' . $i, '-');

            $writer = new Xlsx($spreadsheet);

            $filename = 'PencapaianToko_Detail_'.$tahun;

            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
            header('Cache-Control: max-age=0');
            ob_start();
            $writer->save('php://output');
            $xlsData = ob_get_contents();
            ob_end_clean();

            $response = array(
                'name' => $filename . '.xlsx',
                'file' => "data:application/vnd.ms-excel;base64," . base64_encode($xlsData),
            );
            $this->Logger->write(null, null, 'Download Report Sales Order');
            echo json_encode($response);

        } elseif ($type == 'customer_salesman') {

            $begin = new DateTime($dfrom);
            $end   = new DateTime($dto);

            $sheet->setCellValueByColumnAndRow(1, 1, 'No');
            $sheet->setCellValueByColumnAndRow(2, 1, 'Salesman');
            $sheet->setCellValueByColumnAndRow(3, 1, 'Provinsi');
            $sheet->setCellValueByColumnAndRow(4, 1, 'Kota');
            $sheet->setCellValueByColumnAndRow(5, 1, 'KD LANG');
            $sheet->setCellValueByColumnAndRow(6, 1, 'Nama Toko');

            $columnName = 7;

            for($i = $begin; $i <= $end; $i->modify('+1 day')){
                //echo $i->format("Y-m-d"). '<br>';
                $sheet->setCellValueByColumnAndRow($columnName, 1, $i->format("d M"));
                $columnName++;
            }

            // $max = chr($columnName+65);
            // foreach (range('A', $max) as $columnID) {
            //     $sheet->getColumnDimension($columnID)->setAutoSize(true);
            // }

            // $sheet->setCellValue('I1', 'Efektif Kunjungan');
            //$sheet->getStyle('1')->applyFromArray($styleArray);
            $sheet->getStyle('A1:'.$this->num2alpha($columnName-2).'1')->applyFromArray($styleArray);

            $i = 2;

            $query = $this->db->query("
                select x.i_customer, x.i_salesman, x.i_area, x.e_city_name, x.e_name, x.hari, x.kegiatan, y.e_customer_name , z.e_area_name from (
                     select a.i_customer, a.i_salesman, a.i_area, e_city_name, b.e_name, jsonb_agg(hari) as hari, 
                      jsonb_agg(v_spb_netto::varchar || case when c.e_saran is not null then ' | ' || c.e_saran else '' end || case when d.e_foto is not null then ' | Dokumentasi' else '' end) as kegiatan
                      from dblink('host=192.168.0.93 user=dedy password=g#>m[J2P^^ dbname=bcl port=5432',
                      $$
                          with cte as (
                               select a.i_customer, a.i_salesman, a.i_area,d.e_city_name ,a.e_periode, e.hari from tr_customer_salesman a 
                               inner join tr_customer c on (a.i_customer = c.i_customer and a.i_area = c.i_area)
                               left join tr_city d on (c.i_city = d.i_city and c.i_area = d.i_area)
                               inner join (
                                    SELECT hari::date, to_char(hari, 'yyyymm') as e_periode FROM generate_series(timestamp '$dfrom', '$dto', '1 day') hari 
                               ) as e on (a.e_periode = e.e_periode)
                               where 
                               a.e_periode between '$p_dfrom' and '$p_dto' and a.i_product_group = '01' /*and a.i_salesman = '14' and a.i_customer = '04326'*/
                          )
                          select a.i_customer, a.i_salesman, a.i_area,a.e_city_name, a.hari, coalesce(sum(b.v_spb - b.v_spb_discounttotal),0) as v_spb_netto from cte a
                          left join tm_spb b on (a.hari = b.d_spb and a.i_customer = b.i_customer and a.i_salesman = b.i_salesman)
                          group by 1,2,3,4,5
                      $$
                      ) AS a (
                           i_customer bpchar(5), i_salesman bpchar(2), i_area bpchar(2), e_city_name varchar(50), hari date, v_spb_netto numeric
                      )
                      inner join tbl_user b on (a.i_salesman = b.i_staff and b.i_company = '$i_company' )
                      left join tbl_customer_saran c on (b.i_company = c.i_company and b.username = c.username and a.i_customer = c.i_customer and a.hari = d_saran)
                      left join tbl_customer_dokumentasi d on (b.i_company = d.i_company and b.username = d.username and a.i_customer = d.i_customer and a.hari = d_dokumentasi)
                      group by 1,2,3,4,5
                ) as x
                left join tbl_customer y on (x.i_customer = y.i_customer and y.i_company = '$i_company')
                left join tbl_area z on (x.i_area = z.i_area and z.i_company = '$i_company')
                order by x.e_name, x.i_customer 
            ");

            // var_dump($query->result());
            // die();
            if ($query->num_rows() > 0) {

                foreach ($query->result() as $row) {
                    //x.hari, x.kegiatan

                    $sheet->setCellValueByColumnAndRow(1 , $i, $i-1);
                    $sheet->setCellValueByColumnAndRow(2 , $i, $row->e_name. ' - '.$row->i_salesman);
                    $sheet->setCellValueByColumnAndRow(3 , $i, $row->e_area_name);
                    $sheet->setCellValueByColumnAndRow(4 , $i, $row->e_city_name);
                    $sheet->setCellValueByColumnAndRow(5 , $i, $row->i_customer);
                    $sheet->setCellValueByColumnAndRow(6 , $i, $row->e_customer_name);

                    $columnName = 7;

                    foreach (json_decode($row->kegiatan) as $kegiatan) {
                        $sheet->setCellValueByColumnAndRow($columnName , $i, $kegiatan);
                        $columnName++;
                    }
                    $i++;
                }
            }

            $writer = new Xlsx($spreadsheet);

            $filename = 'SalesmanPerCustomer_'.$dfrom.'_'.$dto;

            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
            header('Cache-Control: max-age=0');
            ob_start();
            $writer->save('php://output');
            $xlsData = ob_get_contents();
            ob_end_clean();

            $response = array(
                'name' => $filename . '.xlsx',
                'file' => "data:application/vnd.ms-excel;base64," . base64_encode($xlsData),
            );
            $this->Logger->write(null, null, 'Download Report Sales Order');
            echo json_encode($response);

        }
















    }
}
