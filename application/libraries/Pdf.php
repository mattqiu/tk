<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

include APPPATH."third_party/mPDF/mpdf.php";

class Pdf
{
	private $mpdf = null;

	public function __construct()
	{
		$this->mpdf = new mPDF('zh-CN','A3');

		$this->mpdf->useAdobeCJK = true;

		$this->mpdf->SetDisplayMode('fullpage');

	}

	public function write_pdf_html($html)
	{
		$this->mpdf->WriteHTML($html);

		return TRUE;
	}

	public function output_pdf()
	{
		$this->mpdf->Output();
		exit;
	}

	/**
	 * 将订单收据输出到文件夹
	 */
	public function order_receipt_file($order_id)
	{
		if (!is_dir('upload/order_receipt/')) {
			mkdir('upload/order_receipt/', DIR_READ_MODE); // 使用0755创建文件
		}
		$this->mpdf->Output("upload/order_receipt/{$order_id}.pdf", "F");
		return true;
	}
}
