@extends('layouts.customer-layout')

@section('title', 'Detail Transaksi')

@section('content')
    <div class="container">
        <div class="mb-4">
            <h4 class="fw-semibold mb-1">Detail Transaksi</h4>
            <span class="text-muted">{{ $transaction->invoice }}</span>
        </div>
        <div class="row g-4 mt-4">
            {{-- 🔹 RIGHT PANEL --}}
            <div class="col-md-7 d-flex flex-column gap-4">

                {{-- Alamat Pengiriman Card --}}
                <div class="card shadow-sm border-0 rounded-4 flex-fill">
                    <div class="card-body">

                        <h6 class="mb-3">
                            <i class="bi bi-geo-alt me-2"></i> Delivery Address
                        </h6>

                        {{-- Nama --}}
                        <div class="fw-bold mb-1">{{ $transaction->address->name }}</div>

                        {{-- Nomor HP --}}
                        <div class="text-muted mb-2">{{ $transaction->address->phone ?? '-' }}</div>

                        {{-- Alamat --}}
                        <div>{{ $transaction->address->address }}</div>

                        @if ($transaction->address->detail_address)
                            <div>{{ $transaction->address->detail_address }}</div>
                        @endif

                        {{-- Wilayah --}}
                        <div class="text-muted mt-2">
                            {{ $transaction->address->district->name ?? '-' }},
                            {{ $transaction->address->city->name ?? '-' }},
                            {{ $transaction->address->province->name ?? '-' }}
                        </div>

                        {{-- Negara + Kode Pos --}}
                        <div class="text-muted">Indonesia ({{ $transaction->address->postal_code ?? '-' }})</div>
                    </div>
                </div>

                {{-- Delivery Detail Card --}}
                <div class="card shadow-sm border-0 rounded-4 flex-fill">
                    <div class="card-body">

                        <h6 class="mb-3">
                            <i class="bi bi-truck me-2"></i> Delivery Detail
                        </h6>

                        <div class="d-flex justify-content-between mb-2">
                            <span class="fw-semibold">Courier:</span>
                            <span>{{ $transaction->courier_name }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="fw-semibold">Service:</span>
                            <span>{{ $transaction->courier_service }}</span>
                        </div>

                        <div class="d-flex justify-content-between mb-2">
                            <span class="fw-semibold">Estimated:</span>
                            <span>{{ $transaction->estimated_delivery }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="fw-semibold">Notes:</span>
                            <span>{{ $transaction->notes ?? '-no notes provided-' }}</span>
                        </div>

                    </div>
                </div>

            </div>
            <div class="col-md-5">
                <div class="card  h-100 shadow-sm rounded-4 border-0">
                    <div class="card-body">

                        <h6 class="mb-3">
                            <i class="bi bi-receipt me-2"></i> Order Detail
                        </h6>

                        {{-- Order ID --}}
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Invoice</span>
                            <span class="fw-semibold fs-8">#{{ $transaction->invoice ?? '-' }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Order ID</span>
                            <span class="fw-semibold">#{{ $transaction->id ?? '-' }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Weight (Grams)</span>
                            <span class="fw-semibold">{{ number_format($transaction->total_weight ?? 0) ?? '-' }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Transaction Date</span>
                            <span>
                                {{ $transaction->created_at ? $transaction->created_at->format('d M Y H:i') : '-' }}
                            </span>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Payment Status</span>
                            <span class="text-capitalize">
                                {{ $transaction->payment_status ?? '-' }}
                            </span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Payment Metdod</span>
                            <span class="text-capitalize">
                                {{ $transaction->payment_method ?? '-' }}
                            </span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Payment Date</span>
                            <span>
                                {{ $transaction->payment_date ? $transaction->payment_date->format('d M Y H:i') : '-' }}
                            </span>
                        </div>
                        <hr>
                        {{-- Tanggal --}}
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Status Transaction</span>
                            <span
                                class="badge {{ match ($transaction->transaction_status) {
                                    'Pending' => 'bg-warning',
                                    'Waiting Payment' => 'bg-secondary',
                                    'Packing' => 'bg-info',
                                    'Sending' => 'bg-primary',
                                    'Delivered' => 'bg-info',
                                    'Completed' => 'bg-success',
                                    'Cancelled' => 'bg-danger',
                                    default => 'bg-secondary',
                                } }}">
                                {{ $transaction->transaction_status }}
                            </span>
                        </div>

                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Sending Date</span>
                            <span>
                                {{ $transaction->sending_date ? $transaction->sending_date->format('d M Y H:i') : '-' }}
                            </span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Delivery Date</span>
                            <span>
                                {{ $transaction->delivered_date ? $transaction->delivered_date->format('d M Y H:i') : '-' }}
                            </span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Completed Date</span>
                            <span>
                                {{ $transaction->completed_date ? $transaction->completed_date->format('d M Y H:i') : '-' }}
                            </span>
                        </div>
                        @if ($transaction->canceled_date && $transaction->transaction_status === 'Cancelled')
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-danger">Canceled Date</span>
                                <span class="text-danger">
                                    {{ $transaction->canceled_date ? $transaction->canceled_date->format('d M Y H:i') : '-' }}
                                </span>
                            </div>
                        @endif

                        {{-- Total --}}
                        <div class="d-flex justify-content-between mt-3 pt-2 border-top">
                            <span class="fw-semibold">Total</span>
                            <span class="fw-bold text-success">
                                Rp {{ number_format($transaction->total ?? 0, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <table class="table table-borderless table-striped mt-4">
            <thead>
                <tr>
                    <th scope="col">Images</th>
                    <th scope="col">Products</th>
                    <th scope="col">Price</th>
                    <th scope="col">Quantity</th>
                    <th scope="col">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($transaction->transactionDetails as $item)
                    <tr>
                        <td>
                            <img src="{{ $item->product->image }}" alt="{{ $item->product->name }}" class="img-thumbnail"
                                style="max-width: 80px;">
                        </td>
                        <td width="45%">{{ $item->product->name }}</td>
                        <td> {{ number_format($item->price) }}</td>
                        <td>{{ $item->qty }} pcs</td>
                        <td>Rp {{ number_format($item->total) }}</td>
                    </tr>
                @endforeach

            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4" class="text-end fw-semibold">Subtotal</td>
                    <td>Rp {{ number_format($transaction->subtotal, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td colspan="4" class="text-end fw-semibold">Discount Merchant</td>
                    <td>Rp {{ number_format($transaction->discount_by_merchant, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td colspan="4" class="text-end fw-semibold">Shipping Cost</td>
                    <td>Rp {{ number_format($transaction->shipping_cost, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td colspan="4" class="text-end fw-bold">Grand Total</td>
                    <td class="fw-bold text-success">Rp {{ number_format($transaction->total, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>
        {{-- Back Button --}}
        <div class="mt-4">
            <a href="{{ route('customer.transaction.index') }}" class="btn btn-secondary px-4">
                Kembali
            </a>
        </div>
    </div>
@endsection
