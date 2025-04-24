@extends('admin.layouts.app')

@section('title', 'High Court of Jharkhand || Digital Signature')

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
          <div class="col-sm-6"><h3 class="mb-0">NAPIX Pdf & Attach Digital Signature - Test</h3></div>
          
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-end">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Digital Signature</li>
            </ol>
          </div>
        </div>
      </div>
    </div>
    <!--end::App Content Header-->
    <div class="app-content">
      <div class="container-fluid">
        <div class="row g-4">
          <div class="col-md-12">
            <div class="card card-success card-outline mb-4">
            <div class="mt-3 p-2">
            <label for="cino">Enter CINO</label>
            <input
                type="text"
                class="form-control p-2 mb-4"
                id="cino"
                aria-describedby="cino"
                value="JHRN010147322024"
            />
            <label for="date">Enter Order Date</label>
            <input
                type="date"
                class="form-control p-2 mb-4"
                id="order_date"
                aria-describedby="date"
                value="2024-12-23"
            />
            <label for="ono">Enter Order no</label>
            <input
                type="text"
                class="form-control p-2 mb-4"
                id="order_no"
                aria-describedby="ono"
                value="1"
            />
            <button id="napixBtn" class="btn btn-success" onclick="getnapixPdf()">
                <span id="napixBtnText">Get PDF ( napix API )</span>
                <span id="napixBtnSpinner" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
            </button>
            </div>
            </div>
          </div>
        </div>
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

@push('styles')
@endpush

@push('scripts')
<script>
    function getnapixPdf() {
        const cino = document.getElementById('cino').value;
        const order_date = document.getElementById('order_date').value;
        const order_no = document.getElementById('order_no').value;

        const button = document.getElementById('napixBtn');
        const btnText = document.getElementById('napixBtnText');
        const btnSpinner = document.getElementById('napixBtnSpinner');
        button.disabled = true;
        btnText.textContent = "Loading...";
        btnSpinner.classList.remove('d-none');

        fetch("{{ route('digital_signature.pdf') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name=\"csrf-token\"]').getAttribute('content')
            },
            body: JSON.stringify({ cino, order_date, order_no })
        })
        .then(res => res.json())
        .then(data => {
            button.disabled = false;
            btnText.textContent = "Get PDF ( napix API )";
            btnSpinner.classList.add('d-none');

            if (data.status === "success") {
                // Display the signed PDF URL in the iframe
                document.getElementById('pdfViewerFrame').src = data.PdfUrl;
                const myModal = new bootstrap.Modal(document.getElementById('pdfViewerModal'));
                myModal.show();
            } else {
                alert("Error: " + data.message);
            }
        })
        .catch(err => {
            console.error(err);
            button.disabled = false;
            btnText.textContent = "Get PDF ( napix API )";
            btnSpinner.classList.add('d-none');
        });
    }
</script>
@endpush

@endsection