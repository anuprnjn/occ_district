@extends('admin.layouts.app')

@section('title', 'High Court of Jharkhand || Web Copy Application Details')

@section('content')
    @php
        use Illuminate\Support\Facades\Crypt;
    @endphp

    <!--begin::App Main-->
    <main class="app-main">
        <!--begin::App Content Header-->
        <div class="app-content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <h3 class="mb-0">Web Copy Application Details</h3>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-end">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Application Details</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <!--end::App Content Header-->

        <!--begin::App Content-->
        <div class="app-content">
            <div class="container-fluid">
                <div class="row g-4">
                    <div class="col-md-12">
                        <div class="card card-primary card-outline mb-4">
                            <div class="card-header">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        <h5 class="card-title mb-0 me-2">Application Details</h5>
                                        <button onclick="printDiv('printablearea')" class="btn btn-success">
                                            <i class="bi bi-printer"></i> Print
                                        </button>
                                    </div>
                                    <a href="{{ route('hc_web_application_list') }}" class="btn btn-primary"><i
                                            class="bi bi-arrow-left"></i>Back</a>
                                </div>
                            </div>
                            <div class="card-body">
                                @if (session('success'))
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                                            aria-label="Close"></button>
                                    </div>
                                @endif

                                @if (session('error'))
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        <i class="bi bi-exclamation-triangle-fill"></i> {{ session('error') }}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                                            aria-label="Close"></button>
                                    </div>
                                @endif

                                <div id="printablearea">
                                    <div class="row">
                                        <!-- Applicant Details -->
                                        <h4 class="text-center">
                                            <strong><u>Orders And Judgement Copy</u></strong>
                                        </h4>
                                        <div class="col-md-4">
                                            <p class="fw-bold text-success">Applicant Details</p>
                                            <table class="table table-bordered">
                                                <tr>
                                                    <th class="fw-bold">Application No</th>
                                                    <td>{{ $hcuser->application_number }}</td>
                                                </tr>
                                                <tr>
                                                    <th class="fw-bold">Name</th>
                                                    <td>{{ $hcuser->applicant_name }}</td>
                                                </tr>
                                                <tr>
                                                    <th class="fw-bold">Mobile No</th>
                                                    <td>{{ $hcuser->mobile_number }}</td>
                                                </tr>
                                                <tr>
                                                    <th class="fw-bold">Email</th>
                                                    <td>{{ $hcuser->email }}</td>
                                                </tr>
                                            </table>
                                        </div>

                                        <!-- Case Details -->
                                        <div class="col-md-4">
                                            <p class="fw-bold text-success">Case Details</p>
                                            <table class="table table-bordered">
                                                <tr>
                                                    <th class="fw-bold">Case No</th>
                                                    <td>{{ $hcuser->type_name }}/{{ $hcuser->case_number }}/{{ $hcuser->case_year }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th class="fw-bold">Filing No</th>
                                                    <td>{{ $hcuser->type_name }}/{{ $hcuser->filing_number }}/{{ $hcuser->filing_year }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th class="fw-bold">Request Mode</th>
                                                    <td>{{ $hcuser->request_mode }}</td>
                                                </tr>

                                            </table>
                                        </div>

                                        <!-- Payment Details -->
                                        <div class="col-md-4">
                                            <p class="fw-bold text-success">Payment Details</p>
                                            <table class="table table-bordered">
                                                <tr>
                                                    <th class="fw-bold">Payment Status</th>
                                                    <td>{{ $hcuser->payment_status }}</td>
                                                </tr>
                                                <tr>
                                                    <th class="fw-bold">Applied By</th>
                                                    <td>{{ $hcuser->applied_by }}</td>
                                                </tr>
                                                <tr>
                                                    <th class="fw-bold">Created At</th>
                                                    <td>{{ $hcuser->created_at }}</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>

                                    <!-- Order Details -->
                                    <div class="row">
                                        <div class="col-md-12">
                                            <p class="fw-bold text-success">Transaction Details</p>
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th class="fw-bold">Transaction Number</th>
                                                        <th class="fw-bold">Transaction Date</th>
                                                        <th class="fw-bold">Amount</th>
                                                        <th class="fw-bold">Payment Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @if ($transaction_details)
                                                        <tr>
                                                            <td>{{ $transaction_details->transaction_no ?? 'N/A' }}</td>
                                                            <td>{{ $transaction_details->transaction_date ?? 'N/A' }}</td>
                                                            <td>₹{{ $transaction_details->amount ?? 'N/A' }}</td>
                                                            <td class="fw-bold {{ $transaction_details && $transaction_details->transaction_status == 'SUCCESS' ? 'text-success' : 'text-danger' }}">
                                                                {{ $transaction_details->transaction_status ?? 'N/A' }}
                                                            </td>
                                                        </tr>
                                                    @else
                                                        <tr>
                                                            <td colspan="4" class="text-center">No transaction details found for this application.</td>
                                                        </tr>
                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
   
                                    <!-- Order Details -->
                                    <div class="row">
                                        <div class="col-md-12">
                                            <p class="fw-bold text-success">Order Details</p>
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th class="fw-bold">Order No</th>
                                                        <th class="fw-bold">Order Date</th>
                                                        <th class="fw-bold">Page No</th>
                                                        <th class="fw-bold">Amount</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse($ordersdata as $key => $order)
                                                        <tr>
                                                            <td>{{ $order->order_number }}</td>
                                                            <td>{{ $order->order_date }}</td>
                                                            <td>{{ $order->number_of_page }}</td>
                                                            <td>{{ number_format($order->amount, 2) }}</td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="4" class="text-center">No orders found for this
                                                                application.</td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <!-- New Section: Order And Judgment Copy (Web Copy) -->
                        <div class="card card-success card-outline mb-4"> <!-- Added mb-4 here -->
                            <div class="card-header">
                                <h5 class="card-title">Upload Document (Web Copy)</h5>
                            </div>
                            <div class="card-body">
                                @if ($hcuser->document_status == 1)
                                    <div class="alert alert-success mt-2">
                                        <i class="bi bi-check-circle-fill"></i> All documents for this application have been
                                        uploaded.
                                    </div>
                                @endif
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th class="fw-bold">Order No</th>
                                            <th class="fw-bold">Order Date</th>
                                            <th class="fw-bold">Page No</th>
                                            <th class="fw-bold">Download</th>
                                            <th class="fw-bold">Upload</th>
                                            <th class="fw-bold">Status</th>
                                            <th class="fw-bold">Actions</th>
                                            <th class="fw-bold">New Page No</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($ordersdata as $order)
                                            <tr>
                                                <td>{{ $order->order_number }}</td>
                                                <td>{{ $order->order_date }}</td>
                                                <td>{{ $order->number_of_page }}</td>
                                                    <td class="extra-data">
                                                <div class="mb-3">
                                                    <label class="form-label">Bottom Stamp Offset (X and Y) (Centered default)</label>
                                                    <div class="d-flex gap-2">
                                                        <input 
                                                            type="number" 
                                                            name="bottom_stamp_x" 
                                                            class="form-control bottom_stamp_x" 
                                                            placeholder="X: +20 , X: -20"
                                                        />
                                                        <input 
                                                            type="number" 
                                                            name="bottom_stamp_y" 
                                                            class="form-control bottom_stamp_y" 
                                                            min="0" 
                                                            max="300" 
                                                            placeholder="Y: default 60"
                                                        />
                                                    </div>
                                                </div>
                                                <div class="mb-2">
                                                    <label class="form-label">Enter Authentication Fee (default: 15Rs)</label>
                                                    <input 
                                                        type="number" 
                                                        name="auth_fee" 
                                                        class="form-control auth_fee" 
                                                        value="15" 
                                                        min="0" 
                                                        max="300" 
                                                    />
                                                </div>
                                                <button 
                                                    id="downloadBtn_{{ $order->order_number }}" 
                                                    class="mt-2 btn btn-outline-info btn-sm" 
                                                    onclick="getPdf(this, '{{ $hcuser->cino }}', '{{ $order->order_number }}', '{{ $hcuser->application_number }}', '{{ $hcuser->created_at }}', '{{ $order->order_number }}', '{{ $transaction_details->transaction_no }}', '{{ $transaction_details->transaction_date }}')">
                                                    Download <i class="bi bi-download"></i>
                                                </button>
                                                    </td>
                                                <td>
                                                    <form action="{{ route('admin.uploadOrderCopy') }}" method="POST"
                                                        enctype="multipart/form-data">
                                                        @csrf
                                                        <input type="hidden" name="application_number"
                                                            value="{{ $order->application_number }}">
                                                        <input type="hidden" name="order_number"
                                                            value="{{ $order->order_number }}">
                                                        <input type="file" name="pdf_file" class="form-control mb-2"
                                                            required>
                                                       
                                                            @if ($errors->has('pdf_file') && old('order_number') == $order->order_number)
                                                                <span
                                                                    class="text-danger">{{ $errors->first('pdf_file') }}</span>
                                                            @endif
                                                        
                                                        <button type="submit" class="btn btn-sm btn-success" @if ($hcuser->deficit_status == 1 or $hcuser->certified_copy_ready_status) disabled @endif>
                                                            <i class="bi bi-upload"></i> Upload
                                                        </button>
                                                    </form>
                                                </td>
                                                <td>
                                                    @if ($order->upload_status)
                                                        <span class="badge bg-success">Uploaded</span>
                                                    @else
                                                        <span class="badge bg-danger">Not Uploaded</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($order->upload_status)
                                                        <a href="javascript:void(0);" class="w-100 mb-2 btn btn-sm btn-primary"
                                                            onclick="viewPDF('{{ route('admin.downloadOrderCopy', $order->file_name) }}')">
                                                            <i class="bi bi-eye"></i> View
                                                        </a>
                                                        <a href="{{ route('admin.deleteOrderCopy', ['application_number' => $order->application_number, 'order_number' => $order->order_number]) }}"
                                                            class="w-100 btn btn-sm btn-danger @if ($hcuser->deficit_status == 1 or $hcuser->certified_copy_ready_status) disabled @endif"
                                                            onclick="return confirm('Are you sure want to delete ?')"
                                                            @if ($hcuser->deficit_status == 1 or $hcuser->certified_copy_ready_status) onclick="return false;" @endif>
                                                            <i class="bi bi-trash"></i> Delete
                                                         </a>
                                                    @else
                                                        <span class="text-muted">No File</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($order->upload_status)
                                                        <span
                                                            class="badge bg-success">{{ $order->new_page_no ?? 'N/A' }}</span>
                                                    @else
                                                        <span class="badge bg-danger">Not Uploaded</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center">No orders found.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Deficit Amount Section -->
                        @if ($hcuser->document_status == 1 && $totaldiff > 0)
                            <div class="card card-success card-outline">
                                <div class="card-header">
                                    <h5 class="card-title">Deficit Amount</h5>
                                </div>
                                <div class="card-body">
                                    @php
                                        $total_deficit_amount = 0;
                                        $perpagefee = $perpagefee ?? 0; // Ensure perpagefee is defined
                                    @endphp

                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th class="fw-bold">Order No</th>
                                                <th class="fw-bold">Order Date</th>
                                                <th class="fw-bold">New Page No</th>
                                                <th class="fw-bold">Page No</th>
                                                <th class="fw-bold">Deficit Page</th>
                                                <th class="fw-bold">Deficit Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($ordersdata as $order)
                                                @php
                                                    $pagediff = max(0, $order->new_page_no - $order->number_of_page);
                                                    $deficit_amt = $pagediff * $perpagefee;
                                                    $total_deficit_amount += $deficit_amt;
                                                @endphp
                                                <tr>
                                                    <td>{{ $order->order_number }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($order->order_date)->format('d-m-Y') }}
                                                    </td>
                                                    <td>{{ $order->new_page_no }}</td>
                                                    <td>{{ $order->number_of_page }}</td>
                                                    <td>{{ $pagediff }}</td>
                                                    <td>{{ $pagediff }} × {{ $perpagefee }} =
                                                        {{ number_format($deficit_amt, 2) }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="6" class="text-center">No orders found</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="5" class="text-end fw-bold">Total Deficit Amount:</td>
                                                <td class="fw-bold">{{ number_format($total_deficit_amount, 2) }}</td>
                                            </tr>
                                        </tfoot>
                                    </table>

                                    @if ($hcuser->deficit_status == 1)
                                        <div class="alert mt-3">
                                            @if ($hcuser->deficit_payment_status == 0)
                                                <span class="alert alert-warning">Deficit Payment Pending.</span>
                                            @elseif ($hcuser->deficit_payment_status == 1)
                                                <span class="alert alert-success">Deficit Payment received.</span>
                                            @endif

                                            <span class=" alert alert-success"><i class="bi bi-check-circle-fill"></i>
                                                Deficit Amount Notification sent successfully.</span>
                                        </div>
                                    @else
                                        <form action="{{ route('hc-web-application.send-deficit-notification') }}"
                                            method="POST">
                                            @csrf
                                            <input type="hidden" name="application_number"
                                                value="{{ $hcuser->application_number }}">
                                            <input type="hidden" name="total_deficit_amount"
                                                value="{{ $total_deficit_amount }}">
                                            <button type="submit" class="btn btn-danger mt-3">Send Notification</button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        @endif

                        @if ($hcuser->certified_copy_ready_status == 1)
                            <div class="alert alert-success mt-3">
                                <i class="bi bi-check-circle-fill"></i> Certified Copy Download Notification sent
                                successfully.
                            </div>
                        @elseif ($hcuser->document_status == 1 && $totaldiff == 0)
                            <form action="{{ route('hc-web-application.send-ready-notification') }}" method="POST">
                                @csrf
                                <input type="hidden" name="application_number"
                                    value="{{ $hcuser->application_number }}">
                                <button type="submit" class="btn btn-primary mt-3">Send Certified Copy</button>
                            </form>
                        @elseif ($hcuser->document_status == 1 && ($hcuser->deficit_status == 1 && $hcuser->deficit_payment_status == 1))
                            <form action="{{ route('hc-web-application.send-ready-notification') }}" method="POST">
                                @csrf
                                <input type="hidden" name="application_number"
                                    value="{{ $hcuser->application_number }}">
                                <button type="submit" class="btn btn-primary mt-3">Send Certified Copy</button>
                            </form>
                        @endif

                    </div>
                </div>
            </div>
        </div>
        
    </main>

    <!-- PDF Viewer Modal -->
    <div class="modal fade" id="pdfViewerModal" tabindex="-1" aria-labelledby="pdfViewerModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="pdfViewerModalLabel">Document Preview</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <iframe id="pdfViewerFrame" src="" width="100%" height="600px"
                        style="border: none;"></iframe>
                </div>
            </div>
        </div>
    </div>
    
    @push('scripts')
<script>
    function getPdf(buttonEl, cino, order_no, application_number, created_at, id, trn_no, trn_date) {
    const button = buttonEl;
    const originalHtml = button.innerHTML;
    button.disabled = true;
    button.innerHTML = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...`;

    fetch("/admin/get-pdf", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document.querySelector('meta[name=csrf-token]').getAttribute("content")
        },
        body: JSON.stringify({ cino, order_no })
    })
    .then(res => res.json())
    .then(data => {
        if (data.message === 'success' && data.pdf_data) {
            
            const row = button.closest("tr");
            const x = row.querySelector(".bottom_stamp_x")?.value || 0;
            const y = row.querySelector(".bottom_stamp_y")?.value || 60;
            const auth_fee = row.querySelector(".auth_fee")?.value || 15;

            const base64Data = data.pdf_data;
            const blob = base64ToBlob(base64Data.split(',')[1], 'application/pdf');
            const file = new File([blob], `${application_number}_${order_no}.pdf`, { type: 'application/pdf' });

            const formData = new FormData();
            formData.append("pdf_file", file);
            formData.append("application_number", application_number);
            formData.append("order_no", order_no);

            const formattedDate = new Date(created_at).toLocaleDateString('en-GB').replace(/\//g, '-');
            formData.append("created_at", formattedDate);

            formData.append("id", id);
            formData.append("auth_fee", auth_fee);
            formData.append("x", x);
            formData.append("y", y);
            formData.append("trn_no", trn_no);

            const dateParts = trn_date.split("/");
            const formattedDateTR = `${dateParts[0]}-${dateParts[1]}-${dateParts[2]}`;
            formData.append("trn_date", formattedDateTR);

            return fetch("/admin/save-raw-pdf", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('meta[name=csrf-token]').getAttribute("content")
                },
                body: formData
            });
        } else {
            throw new Error("PDF not found.");
        }
    })
    .then(res => res.json())
    .then(data => {
        if (data.message === 'saved' && data.pdf_path) {
            return fetch("/admin/hc-process-pdf", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name=csrf-token]').getAttribute("content")
                },
                body: JSON.stringify({
                    pdf_path: data.pdf_path,
                    application_number: data.application_number,
                    doc_id: data.id,
                    createdAt: data.created_at,
                    auth_fee: data.auth_fee,
                    transaction_no: data.trn_no,
                    transaction_date: data.trn_date,
                    x: data.x,
                    y: data.y
                })
            });
        } else {
            throw new Error("PDF save failed.");
        }
    })
    .then(res => res.blob())
    .then(finalBlob => {
        const pdfUrl = URL.createObjectURL(finalBlob);
        document.getElementById('pdfViewerFrame').src = pdfUrl;
        let modal = new bootstrap.Modal(document.getElementById('pdfViewerModal'));
        modal.show();
    })
    .catch(err => {
        console.error(err);
        alert("Something went wrong.");
    })
    .finally(() => {
        button.disabled = false;
        button.innerHTML = originalHtml;
    });
}

    // Helper: Convert base64 to Blob
    function base64ToBlob(base64, mime) {
        const byteCharacters = atob(base64);
        const byteNumbers = new Array(byteCharacters.length).fill().map((_, i) => byteCharacters.charCodeAt(i));
        const byteArray = new Uint8Array(byteNumbers);
        return new Blob([byteArray], { type: mime });
    }
</script> 
        <script>
            function printDiv(divId) {
                var printContents = document.getElementById(divId).innerHTML;
                var originalContents = document.body.innerHTML;

                document.body.innerHTML = printContents; // Replace body with printable content
                window.print();
                document.body.innerHTML = originalContents; // Restore original content after printing
                location.reload(); // Reload the page to restore event handlers
            }
        </script>

        <script>
            function viewPDF(pdfUrl) {
                // Clear the iframe source first
                document.getElementById('pdfViewerFrame').src = '';

                // Set the new PDF URL
                document.getElementById('pdfViewerFrame').src = pdfUrl;

                // Open the modal
                var myModal = new bootstrap.Modal(document.getElementById('pdfViewerModal'));
                myModal.show();
            }

            $('#pdfViewerModal').on('hidden.bs.modal', function() {
                document.getElementById('pdfViewerFrame').src = ''; // Clear the iframe source
            });
        </script>
    @endpush
@endsection
