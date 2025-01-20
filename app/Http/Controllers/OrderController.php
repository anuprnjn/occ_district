<?php

// app/Http/Controllers/OrderController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        // Validate the incoming data
        $validated = $request->validate([
            'dist_code' => 'required|string|max:255',
            'dist_name' => 'required|string|max:255',
            'est_code'=> 'required|string|max:255',
            'name' => 'required|string|max:255',
            'mobile' => 'required|string|max:15',
            'email' => 'required|email',
            'confirm_email' => 'required|same:email',
            'case_or_filing' => 'required|string',
            'case_type' => 'required|string',
            'filing_no' => 'nullable|string',
            'filing_year' => 'nullable|string',
            'case_no' => 'nullable|string',
            'case_year' => 'nullable|string',
            'request_mode' => 'required|string',
            'required_document' => 'required|string',
            'apply_by' => 'required|string',
            'advocate_registration' => 'nullable|string',
        ]);

        // Extract the case_or_filing data
        $case_or_filing = $request->input('case_or_filing');

        // Prepare the data to send back
        $formData = [
            'dist_code' => $validated['district_code'],
            'dist_name' => $validated['district_name'],
            'est_code' => $validated['esta_id'],
            'name' => $validated['name'],
            'mobile' => $validated['mobile'],
            'email' => $validated['email'],
            'confirm_email' => $validated['confirm_email'],
            'case_type' => $validated['case_type'],
            'request_mode' => $validated['request_mode'],
            'required_document' => $validated['required_document'],
            'apply_by' => $validated['apply_by'],
        ];

        // Conditionally add either case or filing data
        if ($case_or_filing === 'case') {
            // If case is selected, send case number and case year, filing data as null
            $formData['case_no'] = $validated['case_no'];
            $formData['case_year'] = $validated['case_year'];
            $formData['filing_no'] = null;
            $formData['filing_year'] = null;
        } elseif ($case_or_filing === 'filing') {
            // If filing is selected, send filing number and filing year, case data as null
            $formData['filing_no'] = $validated['filing_no'];
            $formData['filing_year'] = $validated['filing_year'];
            $formData['case_no'] = null;
            $formData['case_year'] = null;
        }

        // Include advocate registration number if applicable
        if ($validated['apply_by'] === 'advocate') {
            $formData['advocate_registration'] = $validated['advocate_registration'];
        }

        // Return the data back to the view
        return view('index')->with('formData', $formData);
    }
}
