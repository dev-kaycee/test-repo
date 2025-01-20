@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Edit Quote</h2>

        <form action="{{ route('tenant.quotes.update', $quote) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-6">
                    <h3>Client Information</h3>
                    <input type="text" name="client_name" class="form-control" placeholder="Client Name" value="{{ $quote->client_name }}" required>
                    <input type="text" name="client_address" class="form-control" placeholder="Client Address" value="{{ $quote->client_address }}" required>
                </div>

                <div class="col-md-6">
                    <h3>Company Information</h3>
                    <input type="text" name="company_name" class="form-control" placeholder="Company Name" value="{{ $quote->company_name }}" required>
                    <input type="text" name="company_address" class="form-control" placeholder="Company Address" value="{{ $quote->company_address }}" required>
                    <input type="text" name="vat_number" class="form-control" placeholder="VAT Number" value="{{ $quote->vat_number }}" required>
                    <input type="date" name="issue_date" class="form-control" placeholder="Issue Date" value="{{ $quote->issue_date->format('Y-m-d') }}" required>
                    <input type="date" name="expiry_date" class="form-control" placeholder="Expiry Date" value="{{ $quote->expiry_date->format('Y-m-d') }}" required>
                    <input type="text" name="quote_number" class="form-control" placeholder="Quote Number" value="{{ $quote->quote_number }}" readonly>
                </div>
            </div>

            <br>

            <h3>Items</h3>
            <table class="table table-bordered" id="items_table">
                <thead>
                <tr>
                    <th>Description</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                    <th>VAT Rate</th>
                    <th>Amount (ZAR)</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach($quote->items as $index => $item)
                    <tr>
                        <td><input type="text" name="items[{{ $index }}][description]" class="form-control" value="{{ $item->description }}" required></td>
                        <td><input type="number" name="items[{{ $index }}][quantity]" class="form-control quantity" min="1" value="{{ $item->quantity }}" required></td>
                        <td><input type="number" name="items[{{ $index }}][unit_price]" class="form-control unit-price" step="0.01" min="0" value="{{ $item->unit_price }}" required></td>
                        <td><input type="number" name="items[{{ $index }}][vat_rate]" class="form-control vat-rate" step="0.01" min="0" value="{{ $item->vat_rate }}" required></td>
                        <td><input type="number" name="items[{{ $index }}][amount]" class="form-control amount" step="0.01" min="0" value="{{ $item->amount }}" readonly></td>
                        <td><button type="button" class="btn btn-danger remove-item">Remove</button></td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            <button type="button" class="btn btn-primary" id="add_item">Add Item</button>
            <input type="hidden" name="total_amount" id="total_amount" value="{{ $quote->total_amount }}">
            <button type="submit" class="btn btn-success">Update Quote</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            let itemIndex = {{ count($quote->items) }};

            function updateTotalAmount() {
                let total = 0;
                $('.amount').each(function() {
                    total += parseFloat($(this).val()) || 0;
                });
                $('#total_amount').val(total.toFixed(2));
            }

            $("#add_item").click(function() {
                $("#items_table tbody").append(`
                <tr>
                    <td><input type="text" name="items[${itemIndex}][description]" class="form-control" required></td>
                    <td><input type="number" name="items[${itemIndex}][quantity]" class="form-control quantity" min="1" required></td>
                    <td><input type="number" name="items[${itemIndex}][unit_price]" class="form-control unit-price" step="0.01" min="0" required></td>
                    <td><input type="number" name="items[${itemIndex}][vat_rate]" class="form-control vat-rate" step="0.01" min="0" required></td>
                    <td><input type="number" name="items[${itemIndex}][amount]" class="form-control amount" step="0.01" min="0" readonly></td>
                    <td><button type="button" class="btn btn-danger remove-item">Remove</button></td>
                </tr>
            `);
                itemIndex++;
                updateTotalAmount();
            });

            $(document).on("click", ".remove-item", function() {
                $(this).closest("tr").remove();
                updateTotalAmount();
            });

            $(document).on("input", ".quantity, .unit-price, .vat-rate", function() {
                let row = $(this).closest("tr");
                let quantity = parseFloat(row.find(".quantity").val()) || 0;
                let unitPrice = parseFloat(row.find(".unit-price").val()) || 0;
                let vatRate = parseFloat(row.find(".vat-rate").val()) || 0;

                let amount = quantity * unitPrice * (1 + vatRate / 100);
                row.find(".amount").val(amount.toFixed(2));
                updateTotalAmount();
            });

            // Initial calculation of total amount
            updateTotalAmount();
        });
    </script>

@endsection