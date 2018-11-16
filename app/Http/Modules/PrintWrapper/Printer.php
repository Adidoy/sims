<?php

namespace App\Http\Modules\PrintWrapper;

use Barryvdh\Snappy\Facades\SnappyPdf as PDF;

class Printer
{
	protected $pdf;
	protected static $_instance;

	/**
	 * 
	 * Instantiates the print wrapper
	 *
	 * @param string $template
	 * @param array $data
	 * @return
	 */
    public static function make($template, array $data)
    {
		// create a new instance of the current
		// class
		if(self::$_instance === null) {
			self::$_instance = new self;
		}

		self::$_instance->pdf = PDF::loadView($template, $data);
		return self::$_instance;
	}
	
	/**
	 * Sets the footer of the printables
	 *
	 * @param string $template
	 * @return 
	 */
	public function setFooter($template = 'layouts.footer-numbering')
	{
		$footer = view($template);
		$this->printer = $this->printer->setOption('footer-html', $footer);

		return $this;
	}
	
	/**
	 * Creates a printable from the arguments given 
	 * by the user and use the print class from barry vdh
	 *
	 * @param string $orientation
	 * @param string $marginBottom
	 * @param integer $footerSpacing
	 * @param integer $footerFontSize
	 * @return object
	 */
    public function print($filename, $orientation = 'Portrait', $marginBottom = '15mm', $footerSpacing = 4, $footerFontSize = 7)
    {
        
		$this->printer = $this->printer->setOption('footer-center', 'Page [page] / [toPage]');
		$this->printer = $this->printer->setOption('margin-bottom', $marginBottom);
		$this->printer = $this->printer->setOption('footer-spacing', $footerSpacing);
		$this->printer = $this->printer->setOption('footer-font-size', $footerFontSize);
		$this->printer = $this->printer->setOption('orientation', $orientation);
		$this->printer = $this->printer->stream($filename , array('Attachment' => 0) );

		return $this->printer;

		// return $pdf
		// ->setOption('footer-center', 'Page [page] / [toPage]')
		// ->setOption('margin-bottom', '15mm')
		// ->setOption('footer-spacing', 4)
		// ->setOption('footer-font-size', '7')
		// ->setOption('orientation', $orientation)
		
		// ->stream($filename , array('Attachment' => 0) );
		// $pdf ->setTemporaryFolder(public_path('temp'));
	
		// ->setOption('footer-center', 'Page [page] / [toPage]')
		// ->setOption('margin-bottom', '15mm')
		// ->setOption('footer-spacing', 4)
		// ->setOption('footer-font-size','7')
		// ->setOption('orientation',$orientation);
		// ->stream($filename , array('Attachment'=>0) );

		// $content = $pdf -> output();
		// file_put_contents(storage_path('temp').'/Print.pdf', $content);
		// return response()->file(storage_path('temp').'/Print.pdf');
    }
}