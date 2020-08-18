<?php

defined('BASEPATH') or exit('No direct script access allowed');
class Custom
{
    public function password($password)
    {
        return hash_hmac('ripemd160', $password, 'WahyuAdamHusaeni');
    }

    public function runningnumber($i_modul, $i_company, $i_area, $e_periode)
    {
        $CI = &get_instance();
        $th = substr($e_periode, 0, 4);
        $thbl = substr($e_periode, 2, 2) . substr($e_periode, 4, 2);

        $CI->db->select(" n_modul_no as max from tbl_no
        where i_modul='$i_modul'
        and e_periode ='$e_periode'
        and i_area='$i_area' and i_company = '$i_company'", false);
        $query = $CI->db->get();
        if ($query->num_rows() > 0) {
            $terakhir = $query->row()->max;
            $nospb = $terakhir + 1;
            $CI->db->query(" update tbl_no
                              set n_modul_no=$nospb
                              where i_modul='$i_modul'
                              and e_periode ='$e_periode'
                             and i_area='$i_area' and i_company = '$i_company'", false);
            settype($nospb, "string");
            $a = strlen($nospb);
            while ($a < 5) {
                $nospb = "0" . $nospb;
                $a = strlen($nospb);
            }
            $nospb = "$i_modul-" . $thbl . "-" . $i_area . $nospb;
            return $nospb;
        } else {
            $nospb = "00001";
            $nospb = "$i_modul-" . $thbl . "-" . $i_area . $nospb;
            $CI->db->query(" insert into tbl_no(i_modul, i_company, i_area, e_periode, n_modul_no)
                             values ('$i_modul','$i_company','$i_area','$e_periode',1)");
            return $nospb;
        }
    }

    public function area_downline($cari)
    {
        $CI = &get_instance();
        $i_company = $CI->session->userdata('i_company');
        $CI->load->library('custom');
        $username_new = $CI->custom->username_downline($cari);
        $area_new = $CI->db->query("select i_area from tbl_user where i_company = '$i_company' and username in($username_new) group by i_area")->result();

        $data = [];
        foreach ($area_new as $row) {
            $data[] = $row->i_area;
        }
        $data_new = "'" . implode("','", $data) . "'";
        return $data_new;
    }

    public function area_downline2($cari, $i_company)
    {
        $CI = &get_instance();
        $CI->load->library('custom');
        $username_new = $CI->custom->username_downline($cari);
        $area_new = $CI->db->query("select i_area from tbl_user where i_company = '$i_company' and username in($username_new) group by i_area")->result();

        $data = [];
        foreach ($area_new as $row) {
            $data[] = $row->i_area;
        }
        $data_new = "'" . implode("','", $data) . "'";
        return $data_new;
    }

    public function username_downline($username)
    {
        $CI = &get_instance();
        $cari = array($username);
        $CI->load->library('custom');
        $username_new = $CI->custom->cari_username($cari);
        $username_new = array_merge($cari, $username_new);
        $username_new = "'" . implode("','", $username_new) . "'";
        return $username_new;
    }

    public function cari_username($username_upline, $no = 0)
    {
        $CI = &get_instance();
        $CI->load->library('custom');
        $i_company = $CI->session->userdata('i_company');
        $CI->db->or_where_in('username_upline', $username_upline);
        $query = $CI->db->get('tbl_user');
        if ($query->num_rows() > 0) {
            $new_username = array();
            $username = array();
            foreach ($query->result() as $row) {
                $username[$no] = $row->username; // etc

                $new_username[$no] = $row->username;
                $no++;
            }
            $username = array_merge($username, (array) $CI->custom->cari_username($new_username, $no));
            return $username;
        }
    }

    public function hitung_jarak(
        $latitudeFrom = 0, $longitudeFrom = 0, $latitudeTo = 0, $longitudeTo = 0, $earthRadius = 6371000) {
        // convert from degrees to radians
        $latFrom = deg2rad($latitudeFrom);
        $lonFrom = deg2rad($longitudeFrom);
        $latTo = deg2rad($latitudeTo);
        $lonTo = deg2rad($longitudeTo);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
            cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
        return $angle * $earthRadius;
    }

    public function cek_company($i_company)
    {
        $CI = &get_instance();
        $CI->db->select("i_company");
        $CI->db->from("tbl_company");
        $CI->db->where("i_company", $i_company);
        $CI->db->where("f_active", 'true');
        return $CI->db->get()->result();
    }

}