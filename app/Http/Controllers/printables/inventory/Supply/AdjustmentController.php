<?php

namespace App\Http\Controllers\printables\inventory\Supply;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdjustmentController extends Controller
{
	public function print($id)
	{

      $id = $this->sanitizeString($id);
      $adjustment = App\Adjustment::find($id);
      $orientation = 'Portrait';
      $data = [
        'adjustment' => $adjustment
      ];

      $filename = "AdjustmentReport-".Carbon\Carbon::now()->format('mdYHm')."-$adjustment->code".".pdf";
      $view = "adjustment.print_show";

      return $this->printPreview($view,$data,$filename,$orientation);
	}
}
