<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Purchase #{{ $purchase->id }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            color: #212529;
        }

        .header-table {
            width: 100%;
            border-bottom: 2px solid #212529;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .header-table td {
            vertical-align: top;
        }

        .company-name {
            font-size: 20px;
            font-weight: bold;
            margin: 0;
        }

        .subtitle {
            color: #6c757d;
            margin: 2px 0 0 0;
        }

        .po-number {
            font-size: 16px;
            font-weight: bold;
            margin: 0;
            text-align: right;
        }

        .po-date {
            text-align: right;
            margin: 2px 0 0 0;
        }

        table.info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table.info-table td {
            padding: 4px 0;
            border-bottom: 1px solid #eee;
        }

        table.info-table td.label {
            color: #6c757d;
            width: 30%;
        }

        .badge {
            display: inline-block;
            padding: 2px 8px;
            border: 1px solid #333;
            border-radius: 3px;
            font-size: 10px;
        }

        table.items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table.items-table th {
            background-color: #f1f1f1;
            text-align: left;
            padding: 6px 8px;
            border: 1px solid #dee2e6;
            font-size: 11px;
        }

        table.items-table td {
            padding: 6px 8px;
            border: 1px solid #dee2e6;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        table.totals-table {
            width: 40%;
            float: right;
            border-collapse: collapse;
        }

        table.totals-table td {
            padding: 4px 8px;
        }

        table.totals-table .grand-total td {
            border-top: 1px solid #333;
            font-weight: bold;
            font-size: 14px;
            padding-top: 8px;
        }

        .clear {
            clear: both;
        }

        .signature-table {
            width: 100%;
            margin-top: 60px;
        }

        .signature-table td {
            width: 45%;
            text-align: center;
            border-top: 1px solid #333;
            padding-top: 4px;
            font-size: 11px;
        }

        .signature-spacer {
            width: 10%;
        }
    </style>
</head>
<body>

    <table class="header-table">
        <tr>
            <td style="width: 60%;">
                <p class="company-name">{{ config('app.name', 'Company Name') }}</p>
                <p class="subtitle">Purchase Order Receipt</p>
            </td>
            <td style="width: 40%;">
                <p class="po-number">PO #{{ $purchase->id }}</p>
                <p class="po-date">{{ $purchase->purchase_date->format('F d, Y') }}</p>
            </td>
        </tr>
    </table>

    <table class="info-table">
        <tr>
            <td class="label">Warehouse</td>
            <td>{{ $purchase->warehouse->warehouse_name ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="label">Supplier</td>
            <td>{{ $purchase->supplier->supplier_name ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="label">Status</td>
            <td><span class="badge">{{ $purchase->status }}</span></td>
        </tr>
        <tr>
            <td class="label">Payment</td>
            <td><span class="badge">Cash</span></td>
        </tr>
        <tr>
            <td class="label">Note</td>
            <td>{{ $purchase->note ?? '-' }}</td>
        </tr>
    </table>

    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 5%;">#</th>
                <th style="width: 40%;">Product</th>
                <th style="width: 12%;" class="text-center">Qty</th>
                <th style="width: 18%;" class="text-right">Price</th>
                <th style="width: 25%;" class="text-right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @forelse($purchase->purchaseItems as $key => $item)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $item->product->product_name ?? 'N/A' }}</td>
                    <td class="text-center">{{ $item->quantity }}</td>
                    <td class="text-right">₱ {{ number_format($item->net_unit_cost, 2) }}</td>
                    <td class="text-right">₱ {{ number_format($item->subtotal, 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">No items found for this purchase.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <table class="totals-table">
        <tr>
            <td>Discount</td>
            <td class="text-right">− ₱ {{ number_format($purchase->discount, 2) }}</td>
        </tr>
        <tr>
            <td>Shipping</td>
            <td class="text-right">+ ₱ {{ number_format($purchase->shipping, 2) }}</td>
        </tr>
        <tr class="grand-total">
            <td>Grand Total</td>
            <td class="text-right">₱ {{ number_format($purchase->grand_total, 2) }}</td>
        </tr>
    </table>

    <div class="clear"></div>

    <table class="signature-table">
        <tr>
            <td>Prepared By</td>
            <td class="signature-spacer" style="border-top: none;"></td>
            <td>Received By</td>
        </tr>
    </table>

</body>
</html>
