<?php 

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RawPdfController extends Controller
{
    public function save(Request $request)
    {
        $file = $request->file('pdf_file');
        $application_number = $request->input('application_number');
        $created_at = $request->input('created_at');
        $order_no = $request->input('order_no');
        $id = $request->input('id');
        $auth_fee = $request->input('auth_fee');
        $x = $request->input('x');
        $y = $request->input('y');
        $trn_no = $request->input('trn_no');
        $trn_date = $request->input('trn_date');

        if (!$file || !$application_number || !$order_no) {
            return response()->json(['error' => 'Invalid input'], 422);
        }

        $fileName = $application_number . '_' . $order_no . '.pdf';
        $path = $file->storeAs('downloaded_pdf_hc', $fileName, 'public');
        $relativeUrl = Storage::url('downloaded_pdf_hc/' . $fileName);

        return response()->json([
            'message' => 'saved',
            'pdf_path' => $relativeUrl,
            'file_name' => $fileName,
            'application_number' => $application_number,
            'created_at' => $created_at,
            'id' => $id,
            'auth_fee' => $auth_fee,
            'x' => $x,
            'y' => $y,
            'trn_no' => $trn_no,
            'trn_date' => $trn_date
        ]);
    }
}