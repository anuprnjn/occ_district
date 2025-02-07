@extends('public_layouts.app')

@section('content')

<section class="content-section">
    <h1 class="text-xl font-bold mb-4">Case Information</h1>

    <div id="caseDetails" class="border p-4 rounded bg-gray-100">
        <p class="text-gray-600">Loading case details...</p>
    </div>

    <h2 class="text-lg font-semibold mt-6">Orders</h2>
    <table class="min-w-full bg-white border border-gray-200" id="ordersTable">
        <thead>
            <tr class="bg-gray-200">
                <th class="py-2 px-4 border">Order No</th>
                <th class="py-2 px-4 border">Order Date</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</section>

@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const storedData = sessionStorage.getItem("caseInfo");

        if (storedData) {
            const data = JSON.parse(storedData);

            // Display case details
            const caseDetailsDiv = document.getElementById("caseDetails");
            caseDetailsDiv.innerHTML = `
                <p><strong>Case Number:</strong> ${data.cases[0].caseno}</p>
                <p><strong>CIN Number:</strong> ${data.cases[0].cino}</p>
                <p><strong>Petitioner Name:</strong> ${data.cases[0].pet_name}</p>
                <p><strong>Respondent Name:</strong> ${data.cases[0].res_name}</p>
                <p><strong>Case Status:</strong> ${data.cases[0].casestatus}</p>
            `;

            // Display orders in table
            const ordersTable = document.querySelector("#ordersTable tbody");
            ordersTable.innerHTML = ""; // Clear existing content

            data.orders.forEach(order => {
                const row = `
                    <tr>
                        <td class="py-2 px-4 border">${order.order_no}</td>
                        <td class="py-2 px-4 border">${order.order_dt}</td>
                    </tr>
                `;
                ordersTable.innerHTML += row;
            });
        } else {
            document.getElementById("caseDetails").innerHTML = `<p class="text-red-500">No data found in session storage.</p>`;
        }
    });
</script>
@endpush