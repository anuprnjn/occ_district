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
                        <h3 class="mb-0">Civilcourt Other Copy Application Details</h3>
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
                        <div class="card card-info card-outline mb-4">
                            <div class="card-header">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        <h5 class="card-title mb-0 me-2">Application Details</h5>
                                        <button onclick="printDiv('printablearea')" class="btn btn-primary me-2">
                                            <i class="bi bi-printer"></i> Print
                                        </button>
                                        <button onclick="rejectApplication()" class="btn btn-danger" data-bs-toggle="modal"
                                            data-bs-target="#exampleModal"
                                            @if ($dcuser->document_status == 1) disabled @endif>
                                            <i class="bi bi-x-circle"></i> Reject Application
                                        </button>
                                    </div>
                                    <a href="{{ route('dc_other_copy') }}" class="btn btn-info">
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
                        <div class="card card-info card-outline mb-4">
                            <div class="card-header">
                                <h5 class="card-title">Upload Document (Civilcourt Other Copy)</h5>
                            </div>
                            <div class="card-body">
                                <form id="documentUploadForm">
                                    @csrf
                                    <div class="d-flex justify-content-end mt-2">
                                        <button type="button" class="btn btn-success" id="addDocumentField"> <i
                                                class="bi bi-plus-lg"></i>Add
                                        </button>
                                    </div>
                                    <input type="hidden" name="application_number"
                                        value="{{ $dcuser->application_number }}">
                                    <fieldset class="border p-3 mt-3">
                                        <div id="documentFields">
                                            <div class="row document-group mb-3"> <!-- Added mb-3 here -->
                                                <div class="col-md-5">
                                                    <label class="form-label">Document Type</label>
                                                    <input type="text" name="document_types[]" class="form-control"
                                                        required>
                                                </div>
                                                <div class="col-md-5">
                                                    <label class="form-label">Select PDF Document</label>
                                                    <input type="file" name="documents[]" class="form-control"
                                                        accept=".pdf" required>
                                                </div>
                                                <div class="col-md-2 d-flex align-items-end">
                                                    <button type="button" class="btn btn-danger remove-document"><i
                                                            class="bi bi-trash"></i> Remove</button>
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>
                                    <button type="submit" class="btn btn-primary mt-2"
                                        @if ($dcuser->document_status == 1) disabled @endif><i
                                            class="bi bi-upload"></i>Upload</button>
                                </form>
                                <h5 class="card-title mt-4">Uploaded Documents</h5> <!-- Added margin-top -->
                                <table class="table table-bordered" id="documentTable">
                                    <thead>
                                        <tr>
                                            <th>Document Type</th>
                                            <th>Number of Pages</th>
                                            <th>Amount (₹)</th>
                                            <th>File</th>
                                            <th>Action</th>
                                            
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($documents as $doc)
                                            <tr id="documentRow_{{ $doc->id }}">
                                                <td>{{ $doc->document_type }}</td>
                                                <td>{{ $doc->number_of_page }}</td>
                                                <td>₹{{ number_format($doc->amount, 2) }}</td>
                                                <td>
                                                    <a href="javascript:void(0)"
                                                        onclick="viewPDF('{{ Storage::url ('district_other_copies/' . strtolower(session('user.dist_name')) . '/' . strtolower(now()->format('F')) . now()->format('y') . '/' . $doc->file_name) }}')">View</a>
                                                </td>

                                                <td>
                                                     <button onclick="deleteDocument({{ $doc->id }})"
                                                         class="btn btn-danger btn-sm"
                                                         @if ($dcuser->document_status == 1) disabled @endif>
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
                                <input type="hidden" name="application_number"
                                    value="{{ $dcuser->application_number }}">

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






    <div class="modal fade" id="pdfViewerModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="pdfViewerModalLabel">Document Preview</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <iframe id="pdfViewerFrame" src="" width="100%" height="600px"></iframe>
                </div>
            </div>
        </div>
    </div>

    <!-- Rejction Modal -->
    <form action="{{ route('dc-other-copy.reject') }}" method="POST">
        @csrf
        <input type="hidden" name="application_number" value="{{ $dcuser->application_number }}">
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title" id="exampleModalLabel">
                            <i class="bi bi-x-circle"></i> Reject Application
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <label for="rejectionRemarks" class="form-label fw-bold">Rejection Remarks</label>
                        <textarea class="form-control" id="rejectionRemarks" name="rejection_remarks" rows="4" placeholder="Enter rejection reason here..."></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x"></i> Close
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Submit
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>

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
            KOD00307042502_1744025140

            function viewPDF(pdfUrl) {
                console.log(pdfUrl);
                document.getElementById('pdfViewerFrame').src = pdfUrl;
                var myModal = new bootstrap.Modal(document.getElementById('pdfViewerModal'));
                myModal.show();
            }
        </script>

        <script>
            $(document).ready(function() {
                // Add new document upload field
                $("#addDocumentField").click(function() {
                    let fieldHTML = `
        <div class="row document-group mb-3"> <!-- Added mb-3 for spacing -->
            <div class="col-md-5">
                <input type="text" name="document_types[]" class="form-control" required>
            </div>
            <div class="col-md-5">
                <input type="file" name="documents[]" class="form-control" accept=".pdf" required>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="button" class="btn btn-danger remove-document"><i class="bi bi-trash"></i>Remove</button>
            </div>
        </div>`;
                    $("#documentFields").append(fieldHTML);
                });

                // Remove document field
                $(document).on("click", ".remove-document", function() {
                    if ($(".document-group").length > 1) {
                        $(this).closest(".document-group").remove();
                    } else {
                        alert("At least one document field must remain.");
                    }
                });

                // Handle file upload via AJAX
                $("#documentUploadForm").submit(function(e) {
                    e.preventDefault();
                    let formData = new FormData(this);

                    $.ajax({
                        url: "{{ route('upload.dcdocument') }}",
                        type: "POST",
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            alert(response.success);
                            location.reload();
                        },
                        error: function(xhr) {
                            alert(xhr.responseJSON.error);
                        }
                    });
                });

            });
        </script>


        <script>
            $(document).ready(function() {
                // Ensure deleteDocument function is called properly
                $(document).on("click", ".btn-danger", function() {
                    var documentId = $(this).attr("onclick").match(/\d+/)[0]; // Extract ID
                    deleteDocument(documentId);
                });

                // Delete document function
                function deleteDocument(id) {
                    if (confirm("Are you sure you want to delete this document?")) {
                        $.post("{{ route('delete.dcdocument') }}", {
                                document_id: id,
                                _token: "{{ csrf_token() }}"
                            })
                            .done(function(response) {
                                alert(response.success);
                                // $("#documentRow_" + id).remove(); // Remove row on success
                                location.reload();
                            })
                            .fail(function(xhr) {
                                alert(xhr.responseJSON.error);
                            });
                    }
                }
            });
        </script>
    @endpush
@endsection
