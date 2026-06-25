@extends('layouts.app')

@section('title', 'Dashboard')

@php
    $b = $analytics['balances'];
    $tx = $analytics['transactions'];
    $manual = $analytics['manual'];
    $wallet = $filters['wallet_type'] ?: 'all';
    $hasFilter = collect($filters)->filter(fn($v) => $v !== '' && $v !== 'all')->isNotEmpty();
@endphp

@push('styles')
    <style>
        .stat-card {
            border: none;
            border-radius: 0.875rem;
            overflow: hidden;
        }

        .stat-card .stat-label {
            font-size: 0.875rem;
            opacity: 0.9;
        }

        .stat-card .stat-value {
            font-size: 1.65rem;
            font-weight: 700;
            line-height: 1.2;
        }

        .stat-card .stat-sub {
            font-size: 0.8rem;
            opacity: 0.85;
        }

        .stat-emoney {
            background: linear-gradient(135deg, #7367f0 0%, #5e50ee 100%);
            color: #fff;
        }

        .stat-cash {
            background: linear-gradient(135deg, #28c76f 0%, #1f9d57 100%);
            color: #fff;
        }

        .stat-neutral {
            background: #fff;
            border: 1px solid #e9ecef;
        }

        .stat-neutral .stat-value {
            color: #566a7f;
        }

        .stat-profit-cash .stat-value {
            color: #28c76f;
        }

        .stat-profit-emoney .stat-value {
            color: #7367f0;
        }

        .stat-profit-total .stat-value {
            color: #ff9f43;
        }

        .stat-profit-cash {
            border: 1px solid #28c76f33;
        }

        .stat-profit-emoney {
            border: 1px solid #7367f033;
        }

        .stat-profit-total {
            border: 1px solid #ff9f4333;
        }

        .wallet-dimmed {
            opacity: 0.55;
        }

        .dashboard-card {
            border: none;
            border-radius: 0.875rem;
            box-shadow: 0 2px 12px rgba(47, 43, 61, 0.06);
        }

        .filter-note {
            font-size: 0.8rem;
            color: #6c757d;
        }

        .chart-empty {
            min-height: 280px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #a1acb8;
            font-size: 0.9rem;
        }

        .breakdown-table thead th {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.03em;
            white-space: nowrap;
        }

        .breakdown-table tbody td {
            font-size: 0.875rem;
            vertical-align: middle;
        }

        .tx-type-badge {
            font-size: 0.75rem;
            font-weight: 600;
        }
    </style>
@endpush

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">

        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-4">
            <div>
                <h4 class="mb-1">Analytics Dashboard</h4>
                <div class="filter-note">
                    System monitoring &amp; analytical overview
                    @if ($hasFilter)
                        <span class="badge bg-label-primary ms-1">Filtered</span>
                    @else
                        <span class="text-muted">— All time</span>
                    @endif
                </div>
            </div>
        </div>

        <form method="GET" action="{{ route('dashboard') }}" id="dashboard-filter-form">
            <div class="accordion mb-4" id="dashboard-filter-accordion">
                <div class="card super-rounded accordion-item active">
                    <h2 class="accordion-header">
                        <button type="button" class="accordion-button" data-bs-toggle="collapse"
                            data-bs-target="#dashboardFilterBody" aria-expanded="true">
                            Dashboard Filter
                        </button>
                    </h2>
                    <div id="dashboardFilterBody" class="accordion-collapse collapse show">
                        <div class="accordion-body">
                            <div class="row">
                                @include('admin.dashboard.filter')
                            </div>
                            <div class="d-flex justify-content-end mt-2 gap-2">
                                <a href="{{ route('dashboard') }}" class="btn btn-outline-warning">Clear</a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="ti ti-search"></i> Apply Filter
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        {{-- Live Balances --}}
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card stat-card stat-emoney shadow-sm h-100 {{ $wallet === 'cash' ? 'wallet-dimmed' : '' }}">
                    <div class="card-body p-4">
                        <div class="stat-label mb-1">လက်ရှိ EMoney</div>
                        <div class="stat-value">{{ number_format($b['emoney']) }} <small class="fs-6">MMK</small></div>
                        <div class="stat-sub mt-1">{{ $b['accounts_count'] }} accounts</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card stat-cash shadow-sm h-100 {{ $wallet === 'emoney' ? 'wallet-dimmed' : '' }}">
                    <div class="card-body p-4">
                        <div class="stat-label mb-1">လက်ရှိ Cash</div>
                        <div class="stat-value">{{ number_format($b['cash']) }} <small class="fs-6">MMK</small></div>
                        <div class="stat-sub mt-1">{{ $b['bank_types_count'] }} bank types</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card stat-neutral shadow-sm h-100">
                    <div class="card-body p-4">
                        <div class="stat-label text-muted mb-1">Filtered Transactions</div>
                        <div class="stat-value">{{ number_format($tx['count']) }}</div>
                        <div class="stat-sub mt-1 text-muted">
                            Cash-In: {{ number_format($tx['cash_in_count']) }} /
                            Cash-Out: {{ number_format($tx['cash_out_count']) }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card stat-neutral shadow-sm h-100">
                    <div class="card-body p-4">
                        <div class="stat-label text-muted mb-1">Filtered Volume</div>
                        <div class="stat-value">{{ number_format($tx['amount']) }} <small class="fs-6">MMK</small></div>
                        <div class="stat-sub mt-1 text-muted">Fee: {{ number_format($tx['fee_amount']) }} MMK</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Profit & Impact --}}
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div
                    class="card stat-card stat-profit-cash shadow-sm h-100 {{ $wallet === 'emoney' ? 'wallet-dimmed' : '' }}">
                    <div class="card-body p-3">
                        <div class="stat-label text-muted mb-1">Cash Profit</div>
                        <div class="stat-value">{{ number_format($tx['fee_cash_profit']) }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div
                    class="card stat-card stat-profit-emoney shadow-sm h-100 {{ $wallet === 'cash' ? 'wallet-dimmed' : '' }}">
                    <div class="card-body p-3">
                        <div class="stat-label text-muted mb-1">EMoney Profit</div>
                        <div class="stat-value">{{ number_format($tx['fee_emoney_profit']) }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card stat-profit-total shadow-sm h-100">
                    <div class="card-body p-3">
                        <div class="stat-label text-muted mb-1">Total Profit</div>
                        <div class="stat-value">{{ number_format($tx['total_profit']) }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                {{-- <div class="card stat-card stat-neutral shadow-sm h-100">
                    <div class="card-body p-3">
                        <div class="stat-label text-muted mb-1">Cash / EMoney Impact</div>
                        <div class="stat-value" style="font-size:1.1rem;">
                            <span class="{{ $wallet === 'emoney' ? 'text-muted' : 'text-success' }}">
                                {{ number_format($tx['total_cash_impact']) }}
                            </span>
                            <span class="text-muted mx-1">/</span>
                            <span class="{{ $wallet === 'cash' ? 'text-muted' : 'text-primary' }}">
                                {{ number_format($tx['total_emoney_impact']) }}
                            </span>
                        </div>
                    </div>
                </div> --}}
            </div>
        </div>

        {{-- Cash-In / Cash-Out summary --}}
        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <div class="card dashboard-card h-100">
                    <div class="card-body">
                        <h6 class="mb-3 text-success"><i class="ti ti-arrow-down-left"></i> Cash-In Summary</h6>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Count</span>
                            <strong>{{ number_format($tx['cash_in_count']) }}</strong>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Amount</span>
                            <strong>{{ number_format($tx['cash_in_amount']) }} MMK</strong>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card dashboard-card h-100">
                    <div class="card-body">
                        <h6 class="mb-3 text-warning"><i class="ti ti-arrow-up-right"></i> Cash-Out Summary</h6>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Count</span>
                            <strong>{{ number_format($tx['cash_out_count']) }}</strong>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Amount</span>
                            <strong>{{ number_format($tx['cash_out_amount']) }} MMK</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Manual operations --}}
        {{-- <div class="row g-3 mb-4">
            <div class="col-md-6">
                <div class="card dashboard-card h-100 {{ $wallet === 'cash' ? 'wallet-dimmed' : '' }}">
                    <div class="card-body">
                        <h6 class="mb-3"><i class="ti ti-wallet"></i> EMoney Manual Operations</h6>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Add (count / amount)</span>
                            <strong>{{ number_format($manual['account_add_count']) }} /
                                {{ number_format($manual['account_add_amount']) }} MMK</strong>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Reset (count)</span>
                            <strong>{{ number_format($manual['account_reset_count']) }}</strong>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card dashboard-card h-100 {{ $wallet === 'emoney' ? 'wallet-dimmed' : '' }}">
                    <div class="card-body">
                        <h6 class="mb-3"><i class="ti ti-cash"></i> Cash Manual Operations</h6>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Opening (count / amount)</span>
                            <strong>{{ number_format($manual['cash_opening_count']) }} /
                                {{ number_format($manual['cash_opening_amount']) }} MMK</strong>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Reset (count)</span>
                            <strong>{{ number_format($manual['cash_reset_count']) }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}

        {{-- Charts --}}
        <div class="row g-3 mb-4">
            <div class="col-lg-8">
                <div class="card dashboard-card h-100">
                    <div class="card-header bg-transparent border-0 pb-0">
                        <h6 class="mb-0">Transactions by Bank Type</h6>
                        <small class="text-muted">Transaction records grouped by bank type</small>
                    </div>
                    <div class="card-body">
                        @if (count($analytics['by_bank_type_chart']['labels']) > 0)
                            <div id="bank-type-chart"></div>
                        @else
                            <div class="chart-empty">No transaction data for selected filters</div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card dashboard-card h-100">
                    <div class="card-header bg-transparent border-0 pb-0">
                        <h6 class="mb-0">Profit Breakdown</h6>
                        <small class="text-muted">Cash vs EMoney fee profit</small>
                    </div>
                    <div class="card-body">
                        @if ($tx['total_profit'] > 0)
                            <div id="profit-chart"></div>
                        @else
                            <div class="chart-empty">No profit data for selected filters</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Breakdown tables --}}
        <div class="row g-3 mb-4">
            <div class="col-lg-4">
                <div class="card dashboard-card h-100">
                    <div class="card-header bg-transparent">
                        <h6 class="mb-0">By Account (Top 10)</h6>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover breakdown-table mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Account</th>
                                    <th class="text-end">Tx</th>
                                    <th class="text-end">Amount</th>
                                    <th class="text-end">Profit</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($analytics['by_account'] as $row)
                                    <tr>
                                        <td>{{ $row['label'] }}</td>
                                        <td class="text-end">{{ number_format($row['tx_count']) }}</td>
                                        <td class="text-end">{{ number_format($row['total_amount']) }}</td>
                                        <td class="text-end">{{ number_format($row['total_profit']) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">No data</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card dashboard-card h-100">
                    <div class="card-header bg-transparent">
                        <h6 class="mb-0">By Bank Type</h6>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover breakdown-table mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Bank Type</th>
                                    <th class="text-end">Tx</th>
                                    <th class="text-end">Amount</th>
                                    <th class="text-end">Profit</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($analytics['by_bank_type'] as $row)
                                    <tr>
                                        <td>{{ $row['label'] }}</td>
                                        <td class="text-end">{{ number_format($row['tx_count']) }}</td>
                                        <td class="text-end">{{ number_format($row['total_amount']) }}</td>
                                        <td class="text-end">{{ number_format($row['total_profit']) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">No data</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card dashboard-card h-100">
                    <div class="card-header bg-transparent">
                        <h6 class="mb-0">By Provider</h6>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover breakdown-table mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Provider</th>
                                    <th class="text-end">Tx</th>
                                    <th class="text-end">Amount</th>
                                    <th class="text-end">Profit</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($analytics['by_provider'] as $row)
                                    <tr>
                                        <td>{{ $row['label'] }}</td>
                                        <td class="text-end">{{ number_format($row['tx_count']) }}</td>
                                        <td class="text-end">{{ number_format($row['total_amount']) }}</td>
                                        <td class="text-end">{{ number_format($row['total_profit']) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">No data</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Recent transactions --}}
        <div class="card dashboard-card">
            <div class="card-header bg-transparent d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="mb-0">Recent Transactions</h6>
                    <small class="text-muted">Latest 8 records matching filter</small>
                </div>
                @can('has-permission', 'transaction-list')
                    <a href="{{ route('transactions.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
                @endcan
            </div>
            <div class="table-responsive">
                <table class="table table-hover breakdown-table mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Date</th>
                            <th>Account</th>
                            <th>Type</th>
                            <th class="text-end">Amount</th>
                            <th class="text-end">Cash Impact</th>
                            <th class="text-end">EMoney Impact</th>
                            <th class="text-end">Profit</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($analytics['recent'] as $row)
                            <tr>
                                <td>{{ $row['date'] }}</td>
                                <td>{{ $row['account'] }}</td>
                                <td>
                                    @if ($row['type_value'] === 'cash_in')
                                        <span class="badge bg-label-success tx-type-badge">{{ $row['type'] }}</span>
                                    @else
                                        <span class="badge bg-label-warning tx-type-badge">{{ $row['type'] }}</span>
                                    @endif
                                </td>
                                <td class="text-end">{{ number_format($row['amount']) }}</td>
                                <td class="text-end">{{ number_format($row['cash_impact']) }}</td>
                                <td class="text-end">{{ number_format($row['emoney_impact']) }}</td>
                                <td class="text-end">{{ number_format($row['profit']) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">No recent transactions</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/sneat/vendor/libs/apex-charts/apexcharts.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const analytics = @json($analytics);
            const wallet = @json($wallet);

            const nf = (v) => new Intl.NumberFormat().format(v ?? 0);

            if (analytics.by_bank_type_chart.labels.length > 0 && document.querySelector('#bank-type-chart')) {
                const bankTypeChart = analytics.by_bank_type_chart;

                new ApexCharts(document.querySelector('#bank-type-chart'), {
                    chart: {
                        type: 'bar',
                        height: 320,
                        toolbar: {
                            show: false
                        },
                        fontFamily: 'inherit',
                    },
                    series: [{
                            name: 'Total Records',
                            data: bankTypeChart.txCount
                        },
                        {
                            name: 'Cash-In',
                            data: bankTypeChart.cashInCount
                        },
                        {
                            name: 'Cash-Out',
                            data: bankTypeChart.cashOutCount
                        },
                    ],
                    colors: ['#7367f0', '#28c76f', '#ff9f43'],
                    plotOptions: {
                        bar: {
                            horizontal: false,
                            columnWidth: '55%',
                            borderRadius: 4,
                            borderRadiusApplication: 'end',
                        },
                    },
                    dataLabels: {
                        enabled: false
                    },
                    xaxis: {
                        categories: bankTypeChart.labels,
                        labels: {
                            rotate: -45,
                            trim: true,
                            style: {
                                fontSize: '11px'
                            }
                        },
                    },
                    yaxis: {
                        title: {
                            text: 'Records'
                        },
                        labels: {
                            formatter: (v) => nf(Math.round(v)),
                        },
                    },
                    tooltip: {
                        custom: function({ series, seriesIndex, dataPointIndex, w }) {
                            const label = w.globals.labels[dataPointIndex];
                            const count = series[seriesIndex][dataPointIndex];
                            const amount = bankTypeChart.totalAmount[dataPointIndex];
                            const profit = bankTypeChart.totalProfit[dataPointIndex];

                            return '<div class="p-2">' +
                                '<div class="fw-semibold mb-1">' + label + '</div>' +
                                '<div>' + w.globals.seriesNames[seriesIndex] + ': <strong>' + nf(count) + '</strong></div>' +
                                '<div class="text-muted small">Amount: ' + nf(amount) + ' MMK</div>' +
                                '<div class="text-muted small">Profit: ' + nf(profit) + ' MMK</div>' +
                                '</div>';
                        },
                    },
                    legend: {
                        position: 'top'
                    },
                }).render();
            }

            if (analytics.transactions.total_profit > 0 && document.querySelector('#profit-chart')) {
                const profitData = wallet === 'cash' ? [analytics.profit_chart.cash, 0] :
                    wallet === 'emoney' ? [0, analytics.profit_chart.emoney] : [analytics.profit_chart.cash,
                        analytics.profit_chart.emoney
                    ];

                new ApexCharts(document.querySelector('#profit-chart'), {
                    chart: {
                        type: 'donut',
                        height: 300,
                        fontFamily: 'inherit',
                    },
                    labels: ['Cash Profit', 'EMoney Profit'],
                    series: profitData,
                    colors: ['#28c76f', '#7367f0'],
                    legend: {
                        position: 'bottom'
                    },
                    dataLabels: {
                        enabled: true
                    },
                    tooltip: {
                        y: {
                            formatter: (v) => nf(v) + ' MMK'
                        },
                    },
                }).render();
            }
        });
    </script>
@endpush
