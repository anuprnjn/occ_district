@extends('public_layouts.app')

@section('content')

<section class="content-section p-6 mb-20">
    <div class='flex justify-between items-center sm:flex-row flex-col sm:gap-0 gap-4'>
        <h3 class="font-semibold text-xl mb-8 -mt-8">Transaction Status</h3>
        <!-- Button to Trigger Print Dialog -->
        <button id="printBtn" class="bg-teal-500 hover:bg-teal-600 px-6 rounded-md py-2 text-white mb-8 -mt-8 flex gap-2">
            <img src="{{ asset('passets/images/icons/print.svg')}}" alt="">
            Print Transaction
        </button>
    </div>

    <div id="printSection" class="overflow-x-auto">
        <table class="min-w-full shadow-md rounded-lg overflow-hidden">
            <thead>
                <tr class="bg-slate-800 text-white text-left border">
                    <th class="px-6 py-2">Transaction Details</th>
                    <th class="px-6 py-2">Value</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-300 border">
                @if(!empty($responseData))
                    <tr>
                        <td class="px-6 py-2 font-bold">TRANSACTION ID</td>
                        <td class="px-6 py-2">{{ $responseData['depttranid'] ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="px-6 py-2 font-bold">APPLICATION NUMBER</td>
                        <td class="px-6 py-2">{{ $responseData['addinfo1'] ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="px-6 py-2 font-bold">NAME</td>
                        <td class="px-6 py-2">{{ $responseData['depositername'] ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="px-6 py-2 font-bold">AMOUNT</td>
                        <td class="px-6 py-2">₹{{ $responseData['amount'] ?? 'N/A' }}.00</td>
                    </tr>
                    <tr>
                        <td class="px-6 py-2 font-bold">STATUS</td>
                        <td class="px-6 py-2">
                            <span class="font-bold tracking-wide {{ ($responseData['status'] ?? '') == 'SUCCESS' ? 'text-green-600' : 'text-red-600' }}">
                                {{ $responseData['status'] ?? 'N/A' }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td class="px-6 py-2 font-bold">PAYMENT STATUS MESSAGE</td>
                        <td class="px-6 py-2">{{ $responseData['paymentstatusmessage'] }}</td>
                    </tr>
                    <tr>
                        <td class="px-6 py-2 font-bold">GRN NUMBER</td>
                        <td class="px-6 py-2">{{ $responseData['grn'] ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="px-6 py-2 font-bold">CIN NUMBER</td>
                        <td class="px-6 py-2">{{ $responseData['cin'] ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="px-6 py-2 font-bold">REFERENCE NUMBER</td>
                        <td class="px-6 py-2">{{ $responseData['ref_no'] ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="px-6 py-2 font-bold">MODE OF PAYMENT</td>
                        <td class="px-6 py-2">{{ $responseData['pmode'] ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="px-6 py-2 font-bold">TXN DATE</td>
                        <td class="px-6 py-2">{{ $responseData['txn_date'] ?? 'N/A' }}</td>
                    </tr>
                   
                @else
                    <tr>
                        <td class="px-6 py-2 font-bold text-center" colspan="2">No Data Available</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</section>

@endsection

@push('scripts')
<script>
    document.getElementById('printBtn').addEventListener('click', function() {
        const printSection = document.getElementById('printSection');
        const currentUrl = window.location.href; // Get the current page URL
        
        const newWindow = window.open('', '', 'height=600,width=800');
        
        // Remove any unwanted headers, footers, and prevent unwanted elements in the printout
        newWindow.document.write('<html><head><meta charset="UTF-8"><style>');
        
        newWindow.document.write(`
            body {
                font-family: Arial, sans-serif;
                margin-top: 100px;
                margin-bottom: 0;
                position: relative;
            }
            table {
                width: 100%;
                border-collapse: collapse;
            }
            th, td {
                padding: 10px;
                border: 1px solid #ddd;
                text-align: start;
            }
            th {
                background-color: #f4f4f4;
            }
            td {
                text-align: left;
            }
            .text-green-600 {
                color: green;
            }
            .text-red-600 {
                color: red;
            }
            .transaction-details {
                width: 30%;
            }
            .transaction-value {
                width: 70%;
            }
            /* Remove page footer and unwanted parts */
            @page {
                margin: 30px;
            }
            /* Remove header */
            .header {
                display: none;
            }
            .footer {
                display: none;
            }
        `);
        
        newWindow.document.write('</style></head><body>');
        
        // Insert the content into the new window
        newWindow.document.write(printSection.innerHTML);
        
        newWindow.document.write('</body></html>');
        
        newWindow.document.close();
        newWindow.print();
    });
</script>
<script>
    window.onload = function() {
        try {
            fetch('/clear-session', {
                method: 'GET',
                credentials: 'same-origin'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log("Session storage cleared");
                }
            })
            .catch(error => console.error('Error:', error));
        } catch (error) {
            console.error('Try-catch error:', error);
        }
    };
</script>    
@endpush