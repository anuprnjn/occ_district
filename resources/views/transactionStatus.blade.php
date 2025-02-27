@extends('public_layouts.app')

@section('content')

<section class="content-section p-6 mb-20">
   <div class='flex justify-between items-center sm:flex-row flex-col sm:gap-0 gap-4'>
   <h3 class="font-semibold text-xl mb-8 -mt-8">Transaction Status</h3>
   <button class=" bg-teal-500 hover:bg-teal-600 px-6 rounded-md py-2 text-white mb-8 -mt-8 flex gap-2">
    <img src="{{ asset('passets/images/icons/print.svg')}}" alt="">
    Print Transaction</button>
   </div>

    <div class="overflow-x-auto">
        <table class="min-w-full shadow-md rounded-lg overflow-hidden">
            <thead>
                <tr class="bg-slate-800 text-white text-left border">
                    <th class="px-6 py-2">Transaction Details</th>
                    <th class="px-6 py-2">Value</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-300 border">
            <tr><td class="px-6 py-2 font-bold ">TRANSACTION ID</td><td class="px-6 py-2  ">TRN1234567891234567</td></tr>
                <tr><td class="px-6 py-2 font-bold ">NAME</td><td class="px-6 py-2 ">TEST</td></tr>
                <tr><td class="px-6 py-2 font-bold ">AMOUNT</td><td class="px-6 py-2 ">100.00 ₹</td></tr>
                <tr>
                    <td class="px-6 py-2 font-bold ">STATUS</td>
                    <td class="px-6 py-2">
                        <span class="font-bold tracking-wide {{ 'SUCCESS' == 'SUCCESS' ? 'text-green-600' : 'text-red-600' }}">SUCCESS
                        </span>
                    </td>
                </tr>
                <tr><td class="px-6 py-2 font-bold">PAYMENT STATUS MESSAGE</td><td class="px-6 py-2 ">SUCCESS</td></tr>
                <tr><td class="px-6 py-2 font-bold ">GRN NUMBER</td><td class="px-6 py-2 ">0000004386</td></tr>
                <tr><td class="px-6 py-2 font-bold ">CIN NUMBER</td><td class="px-6 py-2 ">10000032016040527029</td></tr>
                <tr><td class="px-6 py-2 font-bold ">REFERENCE NUMBER</td><td class="px-6 py-2 ">3623578311641</td></tr>
                <tr><td class="px-6 py-2 font-bold ">TXN_DATE</td><td class="px-6 py-2 ">2016-04-20 13:22:33</td></tr>
                <tr>
                    <td class="px-6 py-2 font-bold ">DOWNLOAD CHALLAN</td>
                    <td class="px-6 py-2">
                        <a href="https://59.145.222.36/jegras/frmdownloadchallan.aspx?PDetails=twgrN/P17So92NiA4JfLOd5JIEmn+OAvDozHn+Pztyw="
                            class="text-blue-500 border-b border-blue-500 font-bold"
                            target="_blank">
                            &#11015;  Download Challan
                        </a>
                    </td>
                </tr>
               
            </tbody>
        </table>
    </div>
</section>

@endsection

@push('scripts')

@endpush