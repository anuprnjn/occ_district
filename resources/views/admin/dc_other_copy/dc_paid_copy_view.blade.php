@extends('admin.layouts.app')

@section('title', 'High Court of Jharkhand || Other Copy Application Details')

@section('content')
@php
    use Illuminate\Support\Facades\Crypt;
@endphp

<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">DC Other Copy Paid Application Details</h3>
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

    <div class="app-content">
        <div class="container-fluid">
            <div class="row g-4">
                <div class="col-md-12">
                    <div class="card card-success card-outline mb-4">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <h5 class="card-title mb-0 me-2">Application Details</h5>
                                    <button onclick="printDiv('printablearea')" class="btn btn-primary me-2">
                                        <i class="bi bi-printer"></i> Print
                                    </button>
                                </div>
                                <a href="{{ route('dc_other_copy_paid_application') }}" class="btn btn-success">
                                    <i class="bi bi-arrow-left"></i> Back
                                </a>
                            </div>
                        </div>

                        <div class="card-body">
                            @if (session('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            @if (session('error'))
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <i class="bi bi-exclamation-triangle-fill"></i> {{ session('error') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif
                            <div id="printablearea">
                                <div class="row">
                                    <h4 class="text-center">
                                        <strong><u>Others Types Of Copy</u></strong>
                                    </h4>

                                    <div class="col-md-12">
                                        <p class="fw-bold text-success">Application Details</p>
                                        <table class="table table-bordered">
                                            <tr>
                                                <th>Application No</th>
                                                <td>{{ $dcuser->application_number }}</td>
                                                <th>Application Date</th>
                                                <td>{{ $dcuser->created_at }}</td>
                                                <th>Request Mode</th>
                                                <td>{{ $dcuser->request_mode }}</td>
                                            </tr>
                                            <tr>
                                                <th>Name</th>
                                                <td>{{ $dcuser->applicant_name }}</td>
                                                <th>Mobile No</th>
                                                <td>{{ $dcuser->mobile_number }}</td>
                                                <th>Email</th>
                                                <td>{{ $dcuser->email }}</td>
                                            </tr>
                                        </table>
                                    </div>

                                    <div class="col-md-12">
                                        <p class="fw-bold text-success">Case Details</p>
                                        <table class="table table-bordered">
                                            <tr>
                                                <th>Case No/Filing No</th>
                                                <td>
                                                    {{ $dcuser->case_type_name . '/' . $dcuser->case_filling_number . '/' . $dcuser->case_filling_year }}
                                                    @if ($dcuser->selected_method == 'F')
                                                        (Filing No)
                                                    @elseif ($dcuser->selected_method == 'C')
                                                        (Case No)
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Required Document</th>
                                                <td>{{ $dcuser->required_document }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Upload Section -->
                    <div class="card card-success card-outline mb-4">
                        <div class="card-header">
                            <h5 class="card-title">Upload Document (Dc Other Copy)</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered" id="documentTable">
                                <thead>
                                    <tr>
                                        <th>Document Type</th>
                                        <th>Number of Pages</th>
                                        <th>File</th>
                                        <th>Upload Certified Copy</th>
                                        <th>View Certified Copy</th>
                                        <th>Delete Certified Copy</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($documents as $doc)
                                        <tr id="documentRow_{{ $doc->id }}">
                                            <td>{{ $doc->document_type }}</td>
                                            <td>{{ $doc->number_of_page }}</td>
                                            <td style="min-width: 400px;" class="pdf-row">
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
                                                    type="button" 
                                                    class="btn btn-link p-2" 
                                                    onclick="processPDF(
                                                        '{{ Storage::url('district_other_copies/' . strtolower(session('user.dist_name')) . '/' . strtolower(\Carbon\Carbon::parse($doc->uploaded_date)->format('Fy')) . '/' . $doc->file_name) }}',
                                                        '{{ $dcuser->created_at }}',
                                                        this,
                                                        '{{ $dcuser->application_number }}',
                                                        {{ $doc->id }},
                                                        '{{ $transaction_details->transaction_no ?? 'TRNTEST12345' }}',
                                                        '{{ \Carbon\Carbon::parse($transaction_details->transaction_date ?? '2025-04-09')->format('Y-m-d') }}'
                                                    )"
                                                >
                                                    Download
                                                </button>
                                            </td>
                                            <td>
                                            <form class="certified-copy-form" action="{{ route('upload.certified.copy', ['id' => Crypt::encrypt($doc->id)]) }}" method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    <input type="hidden" name="id" value="{{ Crypt::encrypt($doc->id) }}">
                                                    <input type="hidden" name="application_number" value="{{ $doc->application_number }}">
                                                    <input type="hidden" name="document_id" value="{{ $doc->id }}">
                                                    <input type="file" name="document" required class="mb-2">
                                                    <!-- <h1>{{$doc->certified_copy_upload_status}}</h1> -->
                                                    <button type="submit" class="btn btn-success btn-sm" 
                                                        @if ($doc->certified_copy_upload_status == 1) disabled @endif>
                                                        <i class="bi bi-upload"></i> Upload
                                                    </button>
                                                </form>
                                            </td>
                                            <td>
                                            <button 
                                                type="button" 
                                                class="btn btn-primary pl-2 pr-2 view-btn" 
                                                data-document-id="{{ $doc->id }}"
                                                onclick="viewPDF('{{ Storage::url('district_certified_other_copies/' . strtolower(session('user.dist_name')) . '/' . strtolower(now()->format('F')) . now()->format('y') . '/' . $doc->certified_copy_file_name) }}')"
                                                @if ($doc->certified_copy_upload_status != 1) disabled @endif
                                            >
                                                <i class="bi bi-eye"></i> View
                                            </button>
                                            </td>
                                            <td>
                                            <button 
                                                type="button" 
                                                class="btn btn-danger pl-2 pr-2 view-btn delete-btn" 
                                                data-id="{{ Crypt::encrypt($doc->id) }}"
                                                @if ($doc->certified_copy_upload_status != 1) disabled @endif
                                            >
                                                <i class="bi bi-trash"></i> Delete
                                            </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                    @if ($documents->isEmpty())
                                        <tr>
                                            <td colspan="5" class="text-center">No documents uploaded.</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>

                    @if ($dcuser->document_status == 1)
                        <p class="text-success mt-3"><i class="bi bi-check-circle"></i> Notification already sent.</p>
                    @elseif(!$documents->isEmpty())
                        <form action="{{ route('dc-other-copy.send-notification') }}" method="POST">
                            @csrf
                            <input type="hidden" name="application_number" value="{{ $dcuser->application_number }}">
                            <button type="submit" class="btn btn-danger mt-3">
                                <i class="bi bi-bell"></i> Send Notification
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- Loader Overlay -->
<div id="pdfLoader" class="d-none position-fixed top-0 start-0 w-100 h-100 bg-white bg-opacity-75 d-flex justify-content-center align-items-center" style="z-index: 1050;">
    <div class="text-center">
        <div class="spinner-border text-primary mb-3" role="status"></div>
        <div class="fw-bold text-dark">Processing PDF, please wait...</div>
    </div>
</div>
</main>

<!-- Modal for PDF -->
<div class="modal fade" id="pdfViewerModal" tabindex="-1" aria-labelledby="pdfViewerModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl" style="max-width: 80%;">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Preview PDF</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <iframe id="pdfViewerFrame" src="" width="100%" height="600px" frameborder="0"></iframe>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
    function printDiv(divId) {
        var printContents = document.getElementById(divId).innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
        location.reload();
    }
    function viewPDF(pdfUrl) {
                document.getElementById('pdfViewerFrame').src = pdfUrl;
                var myModal = new bootstrap.Modal(document.getElementById('pdfViewerModal'));
                myModal.show();
            }

    function processPDF(pdfUrl, createdAt, button, application_number, id, trn_no, trn_date) {
        const row = button.closest('.pdf-row');
        const x = row.querySelector(".bottom_stamp_x")?.value || 0;
        const y = row.querySelector(".bottom_stamp_y")?.value || 60;
        const auth_fee = row.querySelector(".auth_fee")?.value || 15;

        // Check PDF compatibility
        fetch("{{ route('admin.checkPdfCompatibility') }}", {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                "Content-Type": "application/json"
            },
            body: JSON.stringify({ pdf_path: pdfUrl })
        })
        .then(res => res.json())
        .then(data => {
            if (data.compatible) {
                showPdf(pdfUrl, createdAt, x, y, auth_fee, application_number, false, id, trn_no, trn_date);
            } else {
                document.getElementById('pdfLoader').classList.remove('d-none');
                showPdf(pdfUrl, createdAt, x, y, auth_fee, application_number, true, id, trn_no, trn_date);
            }
        })
        .catch(err => {
            alert("Failed to check PDF compatibility.");
            console.error(err);
        });
    }

    function showPdf(pdfUrl, createdAt, x, y, auth_fee, application_number, forceConvert = false, id, trn_no, trn_date) {
        const date = new Date(createdAt);

        const hours = date.getHours();
        const minutes = String(date.getMinutes()).padStart(2, '0');
        const ampm = hours >= 12 ? 'PM' : 'AM';
        const formattedHour = String(hours % 12 || 12).padStart(2, '0');

        const formattedTime = `${date.getFullYear()}-${String(date.getMonth() + 1).padStart(2, '0')}-${String(date.getDate()).padStart(2, '0')} ${formattedHour}:${minutes} ${ampm}`;

        fetch("{{ route('admin.attachStampAndHeader') }}", {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                "Content-Type": "application/json"
            },
            body: JSON.stringify({
                pdf_path: pdfUrl,
                createdAt: formattedTime,
                bottom_stamp_y: y !== '' ? Number(y) : 60,
                bottom_stamp_x: x !== '' ? Number(x) : null,
                force_convert: forceConvert,
                auth_fee: Number(auth_fee) || 15,
                application_number: application_number,
                doc_id: id,
                transaction_no: trn_no,
                transaction_date: trn_date
            })
        })
        .then(response => {
            if (!response.ok) throw new Error("PDF processing failed.");
            return response.blob();
        })
        .then(blob => {
            const pdfBlobUrl = URL.createObjectURL(blob);
            document.getElementById('pdfViewerFrame').src = pdfBlobUrl;

            const myModal = new bootstrap.Modal(document.getElementById('pdfViewerModal'));
            myModal.show();
        })
        .catch(error => {
            alert("Something went wrong while processing the PDF.");
            console.error(error);
        })
        .finally(() => {
            document.getElementById('pdfLoader').classList.add('d-none');
        });
    }
    $(document).ready(function () {
        // Handle all forms with class `.certified-copy-form`
        $('.certified-copy-form').on('submit', function (e) {
            e.preventDefault();

            const form = this;
            const formData = new FormData(form);

            $.ajax({
                url: $(form).attr('action'),
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function (response) {
                    console.log(response);
                    alert(response.success);
                    window.location.reload();    
                    const docId = response.id;
                    const filename = response.certified_copy_file_name; 

                    // Build new path
                    const district = "{{ strtolower(session('user.dist_name')) }}";
                    const month = "{{ strtolower(now()->format('F')) }}" + "{{ now()->format('y') }}";
                    const fileUrl = `/storage/district_certified_other_copies/${district}/${month}/${filename}`;

                    // Find the matching view button and update its onclick
                    const viewBtn = $(`.view-btn[data-document-id="${docId}"]`);
                    viewBtn.attr('onclick', `viewPDF('${fileUrl}')`);
                },
                error: function (xhr) {
                    const message = xhr.responseJSON?.error || 'Something went wrong!';
                    alert(message);
                    window.location.reload();
                }
            });
        });

        $('.delete-btn').click(function () {
        if (!confirm("Are you sure you want to delete this certified copy?")) return;

        const encryptedId = $(this).data('id'); // Already encrypted in Blade

        $.ajax({
            url: `/delete-certified-copy/${encryptedId}`,
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
            },
            success: function (response) {
                alert(response.success);
                window.location.reload(); 
            },
            error: function (xhr) {
                const message = xhr.responseJSON?.message || 'Failed to delete!';
                alert(message);
            }
        });
    });

    });
</script>
@endpush

@endsection