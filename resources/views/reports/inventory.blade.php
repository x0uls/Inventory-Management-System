<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Total Inventory Report - {{ now()->format('Y-m-d') }}</title>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            color: #1e293b;
            line-height: 1.5;
            padding: 2rem;
            background-color: #f1f5f9;
        }
        .paper {
            max-width: 1000px;
            margin: 0 auto;
            background: white;
            padding: 3rem;
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        .header {
            text-align: center;
            margin-bottom: 3rem;
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 1.5rem;
        }
        .header h1 {
            margin: 0;
            font-size: 1.875rem;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -0.025em;
        }
        .header p {
            margin: 0.5rem 0 0;
            color: #64748b;
            font-size: 0.875rem;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 1rem;
        }
        th, td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #e2e8f0;
            font-size: 0.875rem;
        }
        th {
            background-color: #f8fafc;
            font-weight: 600;
            color: #475569;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.05em;
        }
        tr:nth-child(even) {
            background-color: #fcfcfc;
        }
        .text-right {
            text-align: right;
        }
        .total-row td {
            background-color: #f8fafc;
            font-weight: 700;
            color: #0f172a;
            border-top: 2px solid #cbd5e1;
            font-size: 1rem;
        }
        
        @media print {
            body { 
                padding: 0; 
                background: white;
            }
            .paper {
                box-shadow: none;
                padding: 0;
                max-width: none;
            }
            .no-print { display: none; }
            th { background-color: transparent !important; }
            tr:nth-child(even) { background-color: transparent !important; }
        }
    </style>
</head>
<body>
    <div class="no-print" style="max-width: 1000px; margin: 0 auto 1rem; text-align: right;">
        <button onclick="window.print()" style="padding: 0.5rem 1rem; background: #0f172a; color: white; border: none; border-radius: 0.375rem; cursor: pointer; font-weight: 500; display: inline-flex; align-items: center; gap: 0.5rem;">
            <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
            Print Report
        </button>
    </div>

    <div class="paper">
        <div class="header">
            <h1>Comprehensive Business Report</h1>
            <p>Generated on {{ now()->format('F d, Y \a\t h:i A') }} by {{ auth()->user()->name ?? 'System' }}</p>
        </div>

        <table>
            <thead>
                <tr>
                    <th style="width: 35%;">Product</th>
                    <th>Category</th>
                    <th class="text-right">In Stock</th>
                    <th class="text-right">Stock Value</th>
                    <th class="text-right">Units Sold</th>
                    <th class="text-right">Revenue</th>
                </tr>
            </thead>
            <tbody>
                @php 
                    $grandTotalStockValue = 0;
                    $grandTotalRevenue = 0;
                    $grandTotalSold = 0;
                @endphp
                @foreach($products as $product)
                    @php 
                        $totalStock = $product->batches_sum_quantity ?? 0;
                        $stockValue = $totalStock * $product->unit_price;
                        
                        $grandTotalStockValue += $stockValue;
                        $grandTotalRevenue += $product->total_revenue;
                        $grandTotalSold += $product->total_sold;
                    @endphp
                    <tr>
                        <td>
                            <div style="font-weight: 600; color: #0f172a;">{{ $product->product_name }}</div>
                            <div style="font-size: 0.75rem; color: #64748b; margin-top: 0.125rem;">{{ Str::limit($product->description, 50) }}</div>
                        </td>
                        <td>
                            <span style="background: #f1f5f9; padding: 0.125rem 0.5rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 500; color: #475569;">
                                {{ $product->category->category_name ?? 'Uncategorized' }}
                            </span>
                        </td>
                        <td class="text-right" style="font-variant-numeric: tabular-nums;">{{ number_format($totalStock) }}</td>
                        <td class="text-right" style="font-variant-numeric: tabular-nums;">RM {{ number_format($stockValue, 2) }}</td>
                        <td class="text-right" style="font-variant-numeric: tabular-nums;">{{ number_format($product->total_sold) }}</td>
                        <td class="text-right" style="font-variant-numeric: tabular-nums; font-weight: 500; color: #059669;">RM {{ number_format($product->total_revenue, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="total-row">
                    <td colspan="2">Grand Totals</td>
                    <td class="text-right">{{ number_format($products->sum('batches_sum_quantity')) }}</td>
                    <td class="text-right">RM {{ number_format($grandTotalStockValue, 2) }}</td>
                    <td class="text-right">{{ number_format($grandTotalSold) }}</td>
                    <td class="text-right">RM {{ number_format($grandTotalRevenue, 2) }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
</body>
</html>
