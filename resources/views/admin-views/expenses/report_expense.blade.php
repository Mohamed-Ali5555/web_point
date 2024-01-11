@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('Order Report'))

@push('css_or_js')
    <style>
        @media print {
            .print-button {
                display: none;
            }
        }
    </style>
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Title -->
        <div class="mb-3">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img width="20" src="{{ asset('/public/assets/back-end/img/order_report.png') }}" alt="">
                {{ \App\CPU\translate('Order_Report') }}
            </h2>
        </div>
        <!-- End Page Title -->

        <div class="card mb-3">
            <div class="card-body">
                <div class="media align-items-center gap-3">
                    <div class="avatar avatar-xl">
                        <img class="avatar-img" src="{{ asset('public/assets/back-end') }}/svg/illustrations/order.png"
                            alt="Image Description">
                    </div>

                    <div class="media-body">
                        <div class="row align-items-center">
                            <div class="col-md mb-1 mb-md-0 d-block"
                                style="text-align: {{ Session::get('direction') === 'rtl' ? 'right' : 'left' }};">
                                <div>
                                    <h1 class="page-header-title">{{ \App\CPU\translate('Order') }}
                                        {{ \App\CPU\translate('Report') }} {{ \App\CPU\translate('Overview') }}</h1>
                                </div>

                                <div class="row align-items-center">
                                    <div class="d-flex col-auto">
                                        <h5 class="text-muted {{ Session::get('direction') === 'rtl' ? 'ml-1' : 'mr-1' }}">
                                            {{ \App\CPU\translate('Admin') }}
                                            :</h5>
                                        <h5 class="text-muted">{{ auth('admin')->user()->name }}</h5>
                                    </div>

                                    <div class="col-auto">
                                        <div class="d-flex gap-2 flex-wrap align-items-center">
                                            <h5 class="text-muted">{{ \App\CPU\translate('Date') }}</h5>

                                            <h5 class="text-muted">( {{ session('from_date') }} - {{ session('to_date') }}
                                                )</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-auto">
                                <div class="d-flex">
                                    <a class="btn btn-icon btn--primary rounded-circle"
                                        href="{{ route('admin.dashboard') }}">
                                        <i class="tio-home-outlined"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card mb-3">
                    <div class="card-body">
                        <form action="{{ route('admin.expense.report_search') }}" method="post">
                            @csrf
                            <div class="row">
                                <div class="col-12">
                                    <div class="mb-2 d-flex">
                                        <label for="exampleInputEmail1"
                                            class="title-color">{{ \App\CPU\translate('Show data by date range') }}</label>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-lg-4">
                                    <div class="mb-3">
                                        <label style="font-size:16px;font-weight: 600;">تاريخ البدء</label>
                                        <input type="date" value="{{ $start_at ?? '' }}" name="start_at" id="from_date"
                                            class="form-control">

                                    </div>
                                </div>
                                <div class="col-sm-6 col-lg-4">
                                    <div class="mb-3">
                                        <label style="font-size:16px;font-weight: 600;">تاريخ النهايه</label>
                                        <input type="date" name="end_at" value="{{ $end_at ?? '' }}" id="to_date"
                                            class="form-control">

                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="mb-3">
                                        <button type="submit" class="btn btn--primary btn-block" name="rdio"
                                            value="1">{{ \App\CPU\translate('alone') }}</button>
                                    </div>

                                    <div class="mb-3">
                                        <button type="submit" class="btn btn--primary btn-block" name="rdio"
                                            value="2">{{ \App\CPU\translate('collect') }}</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>








        </div>
        <!-- End Stats -->

        <!-- Body -->
        <div class="card-body" id="print">

            <div class="table-responsive">
                @if (isset($expenses))
                    <table id="columnSearchDatatable"
                        style="text-align: {{ Session::get('direction') === 'rtl' ? 'right' : 'left' }};"
                        class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100">

                        <tbody id="set-rows">
                            @if (isset($expenses))
                                <table id="columnSearchDatatable"
                                    style="text-align: {{ Session::get('direction') === 'rtl' ? 'right' : 'left' }};"
                                    class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100">
                                    <thead class="thead-light thead-50 text-capitalize">
                                        <tr>
                                            <th class="pl-xl-5">{{ \App\CPU\translate('SL') }}</th>
                                            <th>{{ \App\CPU\translate('facilite_name') }}</th>

                                            <th>{{ \App\CPU\translate('date') }}</th>

                                            {{-- <th>{{ \App\CPU\translate('total_value') }}
                                                    </th> --}}
                                            <th>{{ \App\CPU\translate('value') }}</th>
                                        </tr>
                                        <tr class="text-center"> <button class="btn btn-primary my-1 mb-0 print-button "
                                                onclick="printDiv()" id="print_div"><i class="fa fa-paper-plane-o"></i>
                                                print Invoice</button>

                                            <h5 class="text-muted" style="display:flex;">( {{ $start_at }} -
                                                {{ $end_at }}
                                                )</h5>
            </div>


            </thead>
            <tbody id="set-rows">
                @if ($expenses->count() > 0)
                    <?php $i = 0; ?>
                    @foreach ($expenses as $key => $expense)
                        <?php $i++; ?>
                        <tr id="data-{{ $expense->id }}">
                            <td class="pl-xl-5">{{ $i }}</td>
                            <td>{{ \App\CPU\translate(str_replace('_', ' ', $expense['facilite']['name'] ?? 'not facilite')) }}
                            </td>

                            </td>
                            <td>{{ $expense->date }}</td>

                            <td>
                                @if (!empty($expense->total_value))
                                    {{ $expense->total_value }}
                                @else
                                    {{ \App\CPU\translate(str_replace('_', ' ', $expense->value)) }}
                                @endif
                            </td>

                            {{-- <td>
                                                        <div class="d-flex gap-10 justify-content-center">


                                                            <a class="btn btn-outline--primary square-btn btn-sm mr-1"
                                                                title="{{ \App\CPU\translate('view') }}"
                                                                href="{{ route('admin.expense.report_details', ['facilite_id' => $expense->facilite_id]) }}">
                                                                <img src="{{ asset('/public/assets/back-end/img/eye.svg') }}"
                                                                    class="svg" alt="">
                                                            </a>


                                                            <a class="btn btn-outline--primary btn-sm cursor-pointer edit"
                                                                title="{{ \App\CPU\translate('Edit') }}"
                                                                href="{{ route('admin.expense.edit', [$expense['id']]) }}">
                                                                <i class="tio-edit"></i>
                                                            </a>
                                                            <a class="btn btn-outline-danger btn-sm cursor-pointer delete"
                                                                title="{{ \App\CPU\translate('Delete') }}"
                                                                id="{{ $expense['id'] }}">
                                                                <i class="tio-delete"></i>
                                                            </a>
                                                        </div>
                                                    </td> --}}
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="5" class="text-center">
                            <div class="alert alert-danger m-1">
                                there is no data......
                            </div>
                        </td>
                    </tr>
                @endif
            </tbody>
            </table>

            @if ($expenses->count() > 0)
                <tr>
                    <div class="alert alert-primary m-1 text-center" style="">total values
                        {{ $totalPrice }}</div>
                </tr>
            @endif
            @endif

            </tbody>
            </table>
            @endif

        </div>
        <!-- End Table -->
    </div>
    <!-- End Body -->
    </div>
@endsection

@push('script')
@endpush

@push('script_2')
    <script src="{{ asset('public/assets/back-end') }}/vendor/chart.js/dist/Chart.min.js"></script>
    <script src="{{ asset('public/assets/back-end') }}/vendor/chartjs-chart-matrix/dist/chartjs-chart-matrix.min.js">
    </script>
    <script src="{{ asset('public/assets/back-end') }}/js/hs.chartjs-matrix.js"></script>

    <script>
        $(document).on('ready', function() {

            // INITIALIZATION OF FLATPICKR
            // =======================================================
            $('.js-flatpickr').each(function() {
                $.HSCore.components.HSFlatpickr.init($(this));
            });


            // INITIALIZATION OF NAV SCROLLER
            // =======================================================
            $('.js-nav-scroller').each(function() {
                new HsNavScroller($(this)).init()
            });


            // INITIALIZATION OF DATERANGEPICKER
            // =======================================================
            $('.js-daterangepicker').daterangepicker();

            $('.js-daterangepicker-times').daterangepicker({
                timePicker: true,
                startDate: moment().startOf('hour'),
                endDate: moment().startOf('hour').add(32, 'hour'),
                locale: {
                    format: 'M/DD hh:mm A'
                }
            });

            var start = moment();
            var end = moment();

            function cb(start, end) {
                $('#js-daterangepicker-predefined .js-daterangepicker-predefined-preview').html(start.format(
                    'MMM D') + ' - ' + end.format('MMM D, YYYY'));
            }

            $('#js-daterangepicker-predefined').daterangepicker({
                startDate: start,
                endDate: end,
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1,
                        'month').endOf('month')]
                }
            }, cb);

            cb(start, end);


            // INITIALIZATION OF CHARTJS
            // =======================================================
            $('.js-chart').each(function() {
                $.HSCore.components.HSChartJS.init($(this));
            });

            var updatingChart = $.HSCore.components.HSChartJS.init($('#updatingData'));

            // Call when tab is clicked
            $('[data-toggle="chart"]').click(function(e) {
                let keyDataset = $(e.currentTarget).attr('data-datasets')

                // Update datasets for chart
                updatingChart.data.datasets.forEach(function(dataset, key) {
                    dataset.data = updatingChartDatasets[keyDataset][key];
                });
                updatingChart.update();
            })


            // INITIALIZATION OF MATRIX CHARTJS WITH CHARTJS MATRIX PLUGIN
            // =======================================================
            function generateHoursData() {
                var data = [];
                var dt = moment().subtract(365, 'days').startOf('day');
                var end = moment().startOf('day');
                while (dt <= end) {
                    data.push({
                        x: dt.format('YYYY-MM-DD'),
                        y: dt.format('e'),
                        d: dt.format('YYYY-MM-DD'),
                        v: Math.random() * 24
                    });
                    dt = dt.add(1, 'day');
                }
                return data;
            }

            $.HSCore.components.HSChartMatrixJS.init($('.js-chart-matrix'), {
                data: {
                    datasets: [{
                        label: 'Commits',
                        data: generateHoursData(),
                        width: function(ctx) {
                            var a = ctx.chart.chartArea;
                            return (a.right - a.left) / 70;
                        },
                        height: function(ctx) {
                            var a = ctx.chart.chartArea;
                            return (a.bottom - a.top) / 10;
                        }
                    }]
                },
                options: {
                    tooltips: {
                        callbacks: {
                            title: function() {
                                return '';
                            },
                            label: function(item, data) {
                                var v = data.datasets[item.datasetIndex].data[item.index];

                                if (v.v.toFixed() > 0) {
                                    return '<span class="font-weight-bold">' + v.v.toFixed() +
                                        ' hours</span> on ' + v.d;
                                } else {
                                    return '<span class="font-weight-bold">No time</span> on ' + v.d;
                                }
                            }
                        }
                    },
                    scales: {
                        xAxes: [{
                            position: 'bottom',
                            type: 'time',
                            offset: true,
                            time: {
                                unit: 'week',
                                round: 'week',
                                displayFormats: {
                                    week: 'MMM'
                                }
                            },
                            ticks: {
                                "labelOffset": 20,
                                "maxRotation": 0,
                                "minRotation": 0,
                                "fontSize": 12,
                                "fontColor": "rgba(22, 52, 90, 0.5)",
                                "maxTicksLimit": 12,
                            },
                            gridLines: {
                                display: false
                            }
                        }],
                        yAxes: [{
                            type: 'time',
                            offset: true,
                            time: {
                                unit: 'day',
                                parser: 'e',
                                displayFormats: {
                                    day: 'ddd'
                                }
                            },
                            ticks: {
                                "fontSize": 12,
                                "fontColor": "rgba(22, 52, 90, 0.5)",
                                "maxTicksLimit": 2,
                            },
                            gridLines: {
                                display: false
                            }
                        }]
                    }
                }
            });


            // INITIALIZATION OF CLIPBOARD
            // =======================================================
            $('.js-clipboard').each(function() {
                var clipboard = $.HSCore.components.HSClipboard.init(this);
            });


            // INITIALIZATION OF CIRCLES
            // =======================================================
            $('.js-circle').each(function() {
                var circle = $.HSCore.components.HSCircles.init($(this));
            });
        });
    </script>

    <script>
        $('#from_date,#to_date').change(function() {
            let fr = $('#from_date').val();
            let to = $('#to_date').val();
            if (fr != '' && to != '') {
                if (fr > to) {
                    $('#from_date').val('');
                    $('#to_date').val('');
                    toastr.error('{{ \App\CPU\translate('Invalid date range') }}!', Error, {
                        CloseButton: true,
                        ProgressBar: true
                    });
                }
            }

        })
    </script>




    <script>
        function printDiv() {
            var printContents = document.getElementById('print').innerHTML;
            {{-- alert ('printContents'); --}}
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
            location.reload();
        }
    </script>
@endpush
