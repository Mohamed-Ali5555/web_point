@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('facilites'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Title -->
        <div class="mb-3">
            <h2 class="h1 mb-1 text-capitalize d-flex align-items-center gap-2">
                <img width="20" src="{{ asset('/public/assets/back-end/img/facilite.png') }}" alt="">
                {{ \App\CPU\translate('facilites') }}
            </h2>
        </div>
        <!-- End Page Title -->

        <!-- Content Row -->
        <div class="row pb-4 d--none" id="main-facilite"
            style="text-align: {{ Session::get('direction') === 'rtl' ? 'right' : 'left' }};">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 text-capitalize">{{ \App\CPU\translate('facilites') }}</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.facilite.store') }}" method="post" enctype="multipart/form-data"
                            class="banner_form">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="hidden" id="id" name="id">
                                        <label for="name"
                                            class="title-color text-capitalize">{{ \App\CPU\translate('facilites') }}</label>
                                        <input type="text" name="name" class="form-control" id="name" required>
                                    </div>









                                </div>

                                <div class="col-12 d-flex justify-content-end flex-wrap gap-10">
                                    <button class="btn btn-secondary cancel px-4"
                                        type="reset">{{ \App\CPU\translate('reset') }}</button>
                                    <button id="add" type="submit"
                                        class="btn btn--primary px-4">{{ \App\CPU\translate('save') }}</button>
                                    <button id="update"
                                        class="btn btn--primary d--none text-white">{{ \App\CPU\translate('update') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row" id="facilite-table">
            <div class="col-md-12">
                <div class="card">
                    <div class="px-3 py-4">
                        <div class="row align-items-center">
                            <div class="col-md-4 col-lg-6 mb-2 mb-md-0">
                                <h5 class="mb-0 text-capitalize d-flex gap-2">
                                    {{ \App\CPU\translate('facilite_table') }}
                                    <span class="badge badge-soft-dark radius-50 fz-12">{{ $facilites->total() }}</span>
                                </h5>
                            </div>
                            <div class="col-md-8 col-lg-6">
                                <div
                                    class="d-flex align-items-center justify-content-md-end flex-wrap flex-sm-nowrap gap-2">
                                    <!-- Search -->
                                    <form action="{{ url()->current() }}" method="GET">
                                        <div class="input-group input-group-merge input-group-custom">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <i class="tio-search"></i>
                                                </div>
                                            </div>
                                            <input id="datatableSearch_" type="search" name="search" class="form-control"
                                                placeholder="{{ \App\CPU\translate('Search_by_facilite_Type') }}"
                                                aria-label="Search orders" value="{{ $search }}">
                                            <button type="submit" class="btn btn--primary">
                                                {{ \App\CPU\translate('Search') }}
                                            </button>
                                        </div>
                                    </form>
                                    <!-- End Search -->

                                    <div id="facilite-btn">
                                        <button id="main-facilite-add" class="btn btn--primary text-nowrap">
                                            <i class="tio-add"></i>
                                            {{ \App\CPU\translate('facilite') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table id="columnSearchDatatable"
                            style="text-align: {{ Session::get('direction') === 'rtl' ? 'right' : 'left' }};"
                            class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100">
                            <thead class="thead-light thead-50 text-capitalize">
                                <tr>
                                    <th class="pl-xl-5">{{ \App\CPU\translate('SL') }}</th>
                                    <th>{{ \App\CPU\translate('name') }}</th>
                                    <th class="text-center">{{ \App\CPU\translate('Active') }}
                                        {{ \App\CPU\translate('status') }}</th>

                                    <th class="text-center">{{ \App\CPU\translate('action') }}</th>
                                </tr>
                            </thead>
                            @foreach ($facilites as $key => $facilite)
                                <tbody>
                                    <tr id="data-{{ $facilite->id }}">
                                        <td class="pl-xl-5">{{ $facilites->firstItem() + $key }}</td>
                                        <td>{{ \App\CPU\translate(str_replace('_', ' ', $facilite->name)) }}</td>
                                        <td class="text-center">
                                            <label class="mx-auto switcher">
                                                <input type="checkbox" class="status switcher_input"
                                                    id="{{ $facilite['id'] }}"
                                                    {{ $facilite->status == 1 ? 'checked' : '' }}>
                                                <span class="switcher_control"></span>
                                            </label>
                                        </td>
                                        <td>
                                            <div class="d-flex gap-10 justify-content-center">
                                                <a class="btn btn-outline--primary btn-sm cursor-pointer edit"
                                                    title="{{ \App\CPU\translate('Edit') }}"
                                                    href="{{ route('admin.facilite.edit', [$facilite['id']]) }}">
                                                    <i class="tio-edit"></i>
                                                </a>
                                                <a class="btn btn-outline-danger btn-sm cursor-pointer delete"
                                                    title="{{ \App\CPU\translate('Delete') }}"
                                                    id="{{ $facilite['id'] }}">
                                                    <i class="tio-delete"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            @endforeach
                        </table>
                    </div>

                    <div class="table-responsive mt-4">
                        <div class="px-4 d-flex justify-content-lg-end">
                            <!-- Pagination -->
                            {{ $facilites->links() }}
                        </div>
                    </div>

                    @if (count($facilites) == 0)
                        <div class="text-center p-4">
                            <img class="mb-3 w-160" src="{{ asset('public/assets/back-end') }}/svg/illustrations/sorry.svg"
                                alt="Image Description">
                            <p class="mb-0">{{ \App\CPU\translate('No_data_to_show') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        $(".js-example-theme-single").select2({
            theme: "classic"
        });

        $(".js-example-responsive").select2({
            // dir: "rtl",
            width: 'resolve'
        });

        function display_data(data) {

            $('#resource-product').hide()
            $('#resource-brand').hide()
            $('#resource-category').hide()
            $('#resource-shop').hide()

            if (data === 'product') {
                $('#resource-product').show()
            } else if (data === 'brand') {
                $('#resource-brand').show()
            } else if (data === 'category') {
                $('#resource-category').show()
            } else if (data === 'shop') {
                $('#resource-shop').show()
            }
        }
    </script>
    <script>
        function mbimagereadURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $('#mbImageviewer').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#mbimageFileUploader").change(function() {
            mbimagereadURL(this);
        });

        function fbimagereadURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $('#fbImageviewer').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#fbimageFileUploader").change(function() {
            fbimagereadURL(this);
        });

        function pbimagereadURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $('#pbImageviewer').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#pbimageFileUploader").change(function() {
            pbimagereadURL(this);
        });
    </script>
    <script>
        $('#main-facilite-add').on('click', function() {
            $('#main-facilite').show();
        });

        $('.cancel').on('click', function() {
            $('.banner_form').attr('action', "{{ route('admin.facilite.store') }}");
            $('#main-facilite').hide();
        });

        $(document).on('change', '.status', function() {
            var id = $(this).attr("id");
            if ($(this).prop("checked") === true) {
                var status = 1;
            } else if ($(this).prop("checked") === false) {
                var status = 0;
            }

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{ route('admin.facilite.status') }}",
                method: 'POST',
                data: {
                    id: id,
                    status: status
                },
                success: function(data) {
                    if (data == 1) {
                        toastr.success('{{ \App\CPU\translate('facilite_published_successfully') }}');
                    } else {
                        toastr.success('{{ \App\CPU\translate('facilite_unpublished_successfully') }}');
                    }
                }
            });
        });

        $(document).on('click', '.delete', function() {
            var id = $(this).attr("id");
            Swal.fire({
                title: "{{ \App\CPU\translate('Are_you_sure_delete_this_banner') }}?",
                text: "{{ \App\CPU\translate('You_will_not_be_able_to_revert_this') }}!",
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: '{{ \App\CPU\translate('Yes') }}, {{ \App\CPU\translate('delete_it') }}!',
                type: 'warning',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url: "{{ route('admin.facilite.delete') }}",
                        method: 'POST',
                        data: {
                            id: id
                        },
                        success: function(response) {
                            console.log(response)
                            toastr.success(
                                '{{ \App\CPU\translate('facilite_deleted_successfully') }}');
                            $('#data-' + id).hide();
                        }
                    });
                }
            })
        });
    </script>
    <!-- Page level plugins -->
@endpush
