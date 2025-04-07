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
                                            <td>
                                                <button 
                                                    type="button" 
                                                    class="btn btn-link p-0" 
                                                    onclick="viewPDF('{{ Storage::url('district_other_copies/' . strtolower(session('user.dist_name')) . '/' . strtolower(now()->format('Fy')) . '/' . $doc->file_name) }}')"
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
                                                    <input type="file" name="document" required>
                                                    <button type="submit" class="btn btn-success btn-sm"><i class="bi bi-upload"></i> Upload</button>
                                                </form>
                                            </td>
                                            <td>
                                            <button 
                                                type="button" 
                                                class="btn btn-link p-0 view-btn" 
                                                data-document-id="{{ $doc->id }}"
                                                onclick="viewPDF('{{ Storage::url('district_certified_other_copies/' . strtolower(session('user.dist_name')) . '/' . strtolower(now()->format('F')) . now()->format('y') . '/' . $doc->certified_copy_file_name) }}')"
                                            >
                                                <i class="bi bi-eye"></i> View
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
</main>

<!-- Modal for PDF Viewer -->
<div class="modal fade" id="pdfViewerModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content"> 
            <div class="modal-body">
                <iframe id="pdfViewerFrame" src="" width="100%" height="600px"></iframe>
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
        console.log(pdfUrl);
        document.getElementById('pdfViewerFrame').src = pdfUrl;
       
        const myModal = new bootstrap.Modal(document.getElementById('pdfViewerModal'));
        myModal.show();
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
                    alert(response.success);
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
                }
            });
        });
    });
</script>
@endpush

@endsection