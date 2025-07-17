<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class FeeMasterController extends Controller
{
    public function fee_master(Request $request)
    {
        $feeMasters = DB::table('fee_master')
            ->orderBy('fee_id', 'ASC')
            ->get();
        
        return view('admin.feemaster.fee_master', compact('feeMasters'));
    }

    public function create()
    {
        return view('admin.feemaster.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'fee_type' => 'required|string|max:50',
            'amount' => 'required|numeric|min:0'
        ]);

        try {
            DB::table('fee_master')->insert([
                'fee_type' => $request->fee_type,
                'amount' => $request->amount
            ]);

            return redirect()->route('fee_master')->with('success', 'Fee master created successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error creating fee master: ' . $e->getMessage());
        }
    }

 public function edit($encryptedId)
    {
        try {
            // Decrypt the ID
            $id = Crypt::decrypt($encryptedId);
            
            $feeMaster = DB::table('fee_master')->where('fee_id', $id)->first();
            
            if (!$feeMaster) {
                return redirect()->route('fee_master')->with('error', 'Fee master not found!');
            }
            
            return view('admin.feemaster.edit', compact('feeMaster'));
        } catch (\Exception $e) {
            return redirect()->route('fee_master')->with('error', 'Invalid request!');
        }
    }
    
    public function update(Request $request, $encryptedId)
    {
        try {
            // Decrypt the ID
            $id = Crypt::decrypt($encryptedId);
            
            $request->validate([
                'fee_type' => 'required|string|max:50',
                'amount' => 'required|numeric|min:0'
            ]);
            
            $updated = DB::table('fee_master')
                ->where('fee_id', $id)
                ->update([
                    'fee_type' => $request->fee_type,
                    'amount' => $request->amount
                ]);
            
            if ($updated) {
                return redirect()->route('fee_master')->with('success', 'Fee master updated successfully!');
            } else {
                return redirect()->back()->with('error', 'Fee master not found or no changes made!');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Invalid request or error updating fee master!');
        }
    }


}