@extends('core.base::layouts.master')
@section('content')
    <style>
        .ld-version-tag {
            background-color: #F5716C;
        }
        .bs-callout {
            padding: 0 20px;
            margin:  0  0 20px 0;
            border: 1px solid #eee;
            border-left-width: 5px;
            border-radius: 3px;
        }

        .bs-callout p {
            margin: 0 0 10px 0;
        }
        .bs-callout-primary {
            border-left-color: #428bca;
        }
        .bs-callout-primary h4 {
            color: #428bca;
        }
        .glyphicon-ok {
            color: #7ad03a;
        }
        .glyphicon-remove {
            color: red;
        }
        .panel-title {
            font-weight: 600;
        }
        #system_management {
            border: 1px solid #ddd !important;
        }
        #system_management th {
            color: #757575;
        }
        #system_management td {
            text-align: left;
            padding : 5px 0;
        }

        #system_management td ul li {
            margin-bottom : 5px;
        }
        #system_management span.highlight {
            background-color: #FFF176;
            border-radius: 0.28571429rem;
        }
        #txt-report {
            margin: 10px 0;
        }
        #report-wrapper {
            display: none;
        }
    </style>

    <div class="row">
        <div class="col-sm-12">
            <div class="bs-callout bs-callout-primary">
                <p>{{ trans('core.base::system.report_description') }}:</p>
                <button id="btn-report" class="btn btn-info btn-sm">{{ trans('core.base::system.get_system_report') }}</button>

                <div id="report-wrapper">
                    <textarea name="txt-report" id="txt-report" class="col-sm-12" rows="10" spellcheck="false" onfocus="this.select()">
                        ### {{ trans('core.base::system.system_environment') }}

                        - {{ trans('core.base::system.framework_version') }}: {{ $systemEnv['version'] }}
                        - {{ trans('core.base::system.timezone') }}: {{ $systemEnv['timezone'] }}
                        - {{ trans('core.base::system.debug_mode') }}: {!! $systemEnv['debug_mode'] ? '&#10004;' : '&#10008;' !!}
                        - {{ trans('core.base::system.storage_dir_writable') }}: {!! $systemEnv['storage_dir_writable'] ? '&#10004;' : '&#10008;' !!}
                        - {{ trans('core.base::system.cache_dir_writable') }}: {!! $systemEnv['cache_dir_writable'] ? '&#10004;' : '&#10008;' !!}
                        - {{ trans('core.base::system.app_size') }}: {{ $systemEnv['app_size'] }}
                        @foreach($systemExtras as $extraStatKey => $extraStatValue)
                            - {{ $extraStatKey }}: {{ is_bool($extraStatValue) ? ($extraStatValue ? '&#10004;' : '&#10008;') : $extraStatValue }}
                        @endforeach

                        ### {{ trans('core.base::system.server_environment') }}

                        - {{ trans('core.base::system.php_version') }}: {{ $serverEnv['version'] }}
                        - {{ trans('core.base::system.server_software') }}: {{ $serverEnv['server_software'] }}
                        - {{ trans('core.base::system.server_os') }}: {{ $serverEnv['server_os'] }}
                        - {{ trans('core.base::system.database') }}: {{ $serverEnv['database_connection_name'] }}
                        - {{ trans('core.base::system.ssl_installed') }}: {!! $serverEnv['ssl_installed'] ? '&#10004;' : '&#10008;' !!}
                        - {{ trans('core.base::system.cache_driver') }}: {{ $serverEnv['cache_driver'] }}
                        - {{ trans('core.base::system.session_driver') }}: {{ $serverEnv['session_driver'] }}
                        - {{ trans('core.base::system.mbstring_ext') }}: {!! $serverEnv['mbstring'] ? '&#10004;' : '&#10008;' !!}
                        - {{ trans('core.base::system.openssl_ext') }}: {!! $serverEnv['openssl'] ? '&#10004;' : '&#10008;' !!}
                        - {{ trans('core.base::system.pdo_ext') }}: {!! $serverEnv['pdo'] ? '&#10004;' : '&#10008;' !!}
                        - {{ trans('core.base::system.curl_ext') }}: {!! $serverEnv['curl'] ? '&#10004;' : '&#10008;' !!}
                        - {{ trans('core.base::system.exif_ext') }}: {!! $serverEnv['exif'] ? '&#10004;' : '&#10008;' !!}
                        - {{ trans('core.base::system.file_info_ext') }}: {!! $serverEnv['fileinfo'] ? '&#10004;' : '&#10008;' !!}
                        - {{ trans('core.base::system.tokenizer_ext') }}: {!! $serverEnv['tokenizer']  ? '&#10004;' : '&#10008;'!!}
                        @foreach($serverExtras as $extraStatKey => $extraStatValue)
                            - {{ $extraStatKey }}: {{ is_bool($extraStatValue) ? ($extraStatValue ? '&#10004;' : '&#10008;') : $extraStatValue }}
                        @endforeach

                        ### {{ trans('core.base::system.installed_packages') }}

                        @foreach($packages as $package)
                            - {{ $package['name'] }} : {{ $package['version'] }}
                        @endforeach

                        @if(!empty($extraStats))
                            ### {{ trans('core.base::system.extra_information') }}

                            @foreach($extraStats as $extraStatKey => $extraStatValue)
                                - {{ $extraStatKey }} : {{ is_bool($extraStatValue) ? ($extraStatValue ? '&#10004;' : '&#10008;') : $extraStatValue }}
                            @endforeach
                        @endif
                    </textarea>
                    <button id="copy-report" class="btn btn-info btn-sm">{{ trans('core.base::system.copy_report') }}</button>
                </div>
            </div>
        </div>
    </div>

    <div class="row"> <!-- Main Row -->

        <div class="col-sm-8"> <!-- Package & Dependency column -->
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">{{ trans('core.base::system.installed_packages') }}</h3>
                </div>
                <div class="panel-body">
                    <table id="system_management" class="table table-hover table-bordered">
                        <thead>
                        <tr>
                            <th>{{ trans('core.base::system.package_name') }} : {{ trans('core.base::system.version') }}</th>
                            <th>{{ trans('core.base::system.dependency_name') }} : {{ trans('core.base::system.version') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($packages as $package)
                            <tr>
                                <td>{{ $package['name'] }} : <span class="label ld-version-tag">{{ $package['version'] }}</span></td>
                                <td>
                                    <ul>
                                        @if(is_array($package['dependencies']))
                                            @foreach($package['dependencies'] as $dependencyName => $dependencyVersion)
                                                <li>{{ $dependencyName }} : <span class="label ld-version-tag">{{ $dependencyVersion }}</span></li>
                                            @endforeach
                                        @else
                                            <li><span class="label label-primary">{{ $package['dependencies'] }}</span></li>
                                        @endif
                                    </ul>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div> <!-- / Package & Dependency column -->

        <div class="col-sm-4"> <!-- Server Environment column -->
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">{{ trans('core.base::system.system_environment') }}</h3>
                </div>

                <ul class="list-group">
                    <li class="list-group-item">{{ trans('core.base::system.framework_version') }}: {{ $systemEnv['version'] }}</li>
                    <li class="list-group-item">{{ trans('core.base::system.timezone') }}: {{ $systemEnv['timezone'] }}</li>
                    <li class="list-group-item">{{ trans('core.base::system.debug_mode') }}: {!! $systemEnv['debug_mode'] ? '<span class="glyphicon glyphicon-ok"></span>' : '<span class="glyphicon glyphicon-remove"></span>' !!}</li>
                    <li class="list-group-item">{{ trans('core.base::system.storage_dir_writable') }}: {!! $systemEnv['storage_dir_writable'] ? '<span class="glyphicon glyphicon-ok"></span>' : '<span class="glyphicon glyphicon-remove"></span>' !!}</li>
                    <li class="list-group-item">{{ trans('core.base::system.cache_dir_writable') }}: {!! $systemEnv['cache_dir_writable'] ? '<span class="glyphicon glyphicon-ok"></span>' : '<span class="glyphicon glyphicon-remove"></span>' !!}</li>
                    <li class="list-group-item">{{ trans('core.base::system.app_size') }}: {{ $systemEnv['app_size'] }}</li>
                    @foreach($systemExtras as $extraStatKey => $extraStatValue)
                        <li class="list-group-item">{{ $extraStatKey }}: {!! is_bool($extraStatValue) ? ($extraStatValue ? '<span class="glyphicon glyphicon-ok"></span>' : '<span class="glyphicon glyphicon-remove"></span>') : $extraStatValue !!}</li>
                    @endforeach
                </ul>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">{{ trans('core.base::system.server_environment') }}</h3>
                </div>

                <ul class="list-group">
                    <li class="list-group-item">{{ trans('core.base::system.php_version') }}: {{ $serverEnv['version'] }}</li>
                    <li class="list-group-item">{{ trans('core.base::system.server_software') }}: {{ $serverEnv['server_software'] }}</li>
                    <li class="list-group-item">{{ trans('core.base::system.server_os') }}: {{ $serverEnv['server_os'] }}</li>
                    <li class="list-group-item">{{ trans('core.base::system.database') }}: {{ $serverEnv['database_connection_name'] }}</li>
                    <li class="list-group-item">{{ trans('core.base::system.ssl_installed') }}: {!! $serverEnv['ssl_installed'] ? '<span class="glyphicon glyphicon-ok"></span>' : '<span class="glyphicon glyphicon-remove"></span>' !!}</li>
                    <li class="list-group-item">{{ trans('core.base::system.cache_driver') }}: {{ $serverEnv['cache_driver'] }}</li>
                    <li class="list-group-item">{{ trans('core.base::system.session_driver') }}: {{ $serverEnv['session_driver'] }}</li>
                    <li class="list-group-item">{{ trans('core.base::system.openssl_ext') }}: {!! $serverEnv['openssl'] ? '<span class="glyphicon glyphicon-ok"></span>' : '<span class="glyphicon glyphicon-remove"></span>' !!}</li>
                    <li class="list-group-item">{{ trans('core.base::system.mbstring_ext') }}: {!! $serverEnv['mbstring'] ? '<span class="glyphicon glyphicon-ok"></span>' : '<span class="glyphicon glyphicon-remove"></span>' !!}</li>
                    <li class="list-group-item">{{ trans('core.base::system.pdo_ext') }}: {!! $serverEnv['pdo'] ? '<span class="glyphicon glyphicon-ok"></span>' : '<span class="glyphicon glyphicon-remove"></span>' !!}</li>
                    <li class="list-group-item">{{ trans('core.base::system.curl_ext') }}: {!! $serverEnv['curl'] ? '<span class="glyphicon glyphicon-ok"></span>' : '<span class="glyphicon glyphicon-remove"></span>' !!}</li>
                    <li class="list-group-item">{{ trans('core.base::system.exif_ext') }}: {!! $serverEnv['exif'] ? '<span class="glyphicon glyphicon-ok"></span>' : '<span class="glyphicon glyphicon-remove"></span>' !!}</li>
                    <li class="list-group-item">{{ trans('core.base::system.file_info_ext') }}: {!! $serverEnv['fileinfo'] ? '<span class="glyphicon glyphicon-ok"></span>' : '<span class="glyphicon glyphicon-remove"></span>' !!}</li>
                    <li class="list-group-item">{{ trans('core.base::system.tokenizer_ext') }}: {!! $serverEnv['tokenizer']  ? '<span class="glyphicon glyphicon-ok"></span>' : '<span class="glyphicon glyphicon-remove"></span>'!!}</li>
                    @foreach($serverExtras as $extraStatKey => $extraStatValue)
                        <li class="list-group-item">{{ $extraStatKey }}: {!! is_bool($extraStatValue) ? ($extraStatValue ? '<span class="glyphicon glyphicon-ok"></span>' : '<span class="glyphicon glyphicon-remove"></span>') : $extraStatValue !!}</li>
                    @endforeach
                </ul>
            </div>

            @if(!empty($extraStats))
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">{{ trans('core.base::system.extra_stats') }}</h3>
                    </div>

                    <ul class="list-group">
                        @foreach($extraStats as $extraStatKey => $extraStatValue)
                            <li class="list-group-item">{{ $extraStatKey }}: {!! is_bool($extraStatValue) ? ($extraStatValue ? '<span class="glyphicon glyphicon-ok"></span>' : '<span class="glyphicon glyphicon-remove"></span>') : $extraStatValue !!}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div> <!-- / Server Environment column -->

    </div> <!-- / Main Row -->

    <!-- jQuery & Datables JS -->
    <script src="https://bartaz.github.io/sandbox.js/jquery.highlight.js"></script>
    <script src="https://cdn.datatables.net/plug-ins/1.10.13/features/searchHighlight/dataTables.searchHighlight.min.js"></script>

    <!-- Initialize & config datatables -->
    <script>
        $(document).ready(function() {
            $('#system_management').DataTable({
                'dom': "rt<'datatables__info_wrap'plfi<'clearfix'>>",
                'searchHighlight': true,
                'paging': true,
                'searching': true,
                'info': true,
                'searchDelay': 350,
                'bStateSave': true,
                'lengthMenu': [
                    [10, 30, 50, -1],
                    [10, 30, 50, '{{ trans('core.base::tables.all') }}']
                ],
                'pageLength': 10,
                'processing': true,
                'bProcessing': true,
                'language': {
                    'aria': {
                        'sortAscending': 'orderby asc',
                        'sortDescending': 'orderby desc'
                    },
                    'emptyTable': '{{ trans('core.base::tables.no_data') }}',
                    'info': '<span class="dt-length-records"><i class="fa fa-globe"></i> <span class="hidden-xs"> {{ trans('core.base::tables.show_from') }}</span> _START_ {{  trans('core.base::tables.to')  }} _END_ {{ trans('core.base::tables.in') }} <span class="badge bold badge-dt">_TOTAL_</span> <span class="hidden-xs">{{  trans('core.base::tables.records') }}</span></span>',
                    'infoEmpty': '{{ trans('core.base::tables.no_record') }}',
                    'infoFiltered': '( {{ trans('core.base::tables.filtered_from') }} _MAX_ {{ trans('core.base::tables.records') }})',
                    'lengthMenu': '<span class="dt-length-style">_MENU_</span>',
                    'search': '',
                    'zeroRecords': '{{ trans('core.base::tables.no_record') }}',
                    'processing': '<img src="{{  url('/vendor/core/images/loading-spinner-blue.gif') }}" />',
                },
            });

            s = document.getElementById('txt-report').value;
            s = s.replace(/(^\s*)|(\s*$)/gi,"");
            s = s.replace(/[ ]{2,}/gi," ");
            s = s.replace(/\n /,"\n");
            document.getElementById('txt-report').value = s;

            $('#btn-report').on('click', function() {
                $('#report-wrapper').slideToggle();
            });

            $('#copy-report').on('click', function() {
                $('#txt-report').select();
                document.execCommand('copy');
            });
        });

    </script>
@stop
