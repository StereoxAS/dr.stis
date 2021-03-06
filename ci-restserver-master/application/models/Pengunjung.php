<?php 
if(!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 
 */
class Pengunjung extends CI_Model
{
	
	function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->tabelPengunjung = 'pengunjung';
	}

	//Get Baris Pengunjung
	function getRows($params = array()) {
		$this->db->select('*');
		$this->db->from($this->tabelPengunjung);

		//fetch data by conditions
		
		if(array_key_exists("conditions", $params)) {
			foreach ($params['conditions'] as $key => $value) {
				//print_r($params['conditions']);
				$this->db->where($key,$value);
			}
		}

		if(array_key_exists("pengunjung_nim", $params)) {
			$this->db->where('pengunjung_nim',$params['pengunjung_nim']);
			$query = $this->db->get();
			$result = $query->row_array();
		}else{
			if(array_key_exists("start", $params)&&array_key_exists("limit", $params)) {
				$this->db->limit($params['limit'],$params['start']);
			}elseif (!array_key_exists("start",$params)&&array_key_exists("limit",$params)) {
				$this->db->limit($params['limit']);
			}

			if(array_key_exists("returnType", $params)&& $params['returnType']=='count') {
				$result = $this->db->count_all_results();
			}elseif(array_key_exists("returnType",$params)&& $params['returnType']=='single'){
				$query = $this->db->get();
				$result=($query->num_rows() > 0)?$query->row_array():false;
			}else{
				$query = $this->db->get();
				$result=($query->num_rows() > 0)?$query->result_array():false;
			}
		}
		return $result;
	}

	public function insertPengunjung($data) {
		$insert = $this->db->insert($this->tabelPengunjung, $data);
		//print_r($data);
		//print_r($this->tabelPengunjung);
		if(empty($insert)) {
			return false;
		}else{
			return true;
		};
	}

	public function update($data,$pengunjung_nim) {
		if(!array_key_exists('modifikasi', $data)){
			$data['modifikasi'] = date("Y-m-d H:i:s");
		}

		$update = $this->db->update($this->tabelPengunjung, $data, array('pengunjung_nim' => $pengunjung_nim));

		return $update?true:false;
	}

	public function delete($pengunjung_nim) {
		$delete = $this->db->delete('pengunjung', array('pengunjung_nim' => $pengunjung_nim));
		return $delete?true:false;
	}
}

 ?>