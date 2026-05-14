@extends('_layouts.layout')

@section('head_title', __('Editovanje Firme'))

@push('head_links')

@endpush

@section('content')

<div class="row page-titles mx-0">
	<ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{route('firme.index')}}">@lang('Firme')</a></li>
		<li class="breadcrumb-item active">@lang('Editovanje Firme')</li>
	</ol>
</div>

<style>
    .company-edit-page .card-header {
        gap: 12px;
    }

    .company-edit-page .form-actions {
        display: flex;
        justify-content: flex-end;
        padding-top: 8px;
        border-top: 1px solid #eef1f7;
    }

    .company-edit-page #file-table td {
        vertical-align: middle;
    }

    .company-edit-page .file-row {
        cursor: pointer;
    }

    .company-edit-page .file-row:hover {
        background-color: #f5f7fb;
    }

    .company-edit-page .file-toolbar {
        border-bottom: 1px solid #eef1f7;
        padding-bottom: 10px;
        flex: 0 0 auto;
        margin-bottom: 10px;
    }

    .company-edit-page .file-breadcrumb {
        font-size: 14px;
        line-height: 1.5;
    }

    .company-edit-page .file-breadcrumb span {
        color: #6c757d;
        cursor: pointer;
        transition: 0.2s;
    }

    .company-edit-page .file-breadcrumb span:hover {
        color: var(--primary);
    }

    .company-edit-page .file-breadcrumb .active {
        font-weight: 600;
        color: #212529;
        cursor: default;
    }

    .company-edit-page .file-breadcrumb .separator {
        margin: 0 6px;
        color: #adb5bd;
    }

    .company-edit-page .files-panel .card {
        max-height: 688px;
        display: flex;
        flex-direction: column;
    }

    .company-edit-page .files-panel .card-body {
        display: flex;
        flex-direction: column;
        flex: 1 1 auto;
        overflow: hidden;
    }

    .company-edit-page .files-panel .table-responsive {
        flex: 1 1 auto;
        overflow-y: auto;
        overflow-x: auto;
    }

    .company-edit-page .files-panel .table-responsive table thead {
        position: sticky;
        top: 0;
        background-color: #fff;
        z-index: 10;
    }

    .company-edit-page .files-panel .table th,
    .company-edit-page .files-panel .table td {
        white-space: nowrap;
    }

    @media (max-width: 767px) {
        .company-edit-page .card-header {
            display: block;
        }

        .company-edit-page .card-header .btn {
            width: 100%;
            margin-top: 12px;
        }

        .company-edit-page .card-body {
            padding: 18px;
        }

        .company-edit-page .form-actions {
            display: block;
        }

        .company-edit-page .form-actions .btn {
            width: 100%;
        }

        .company-edit-page .files-panel {
            margin-top: 18px;
        }

        .company-edit-page .files-panel .card {
            max-height: none;
        }

        .company-edit-page .files-panel .card-body {
            min-height: 0;
            overflow: visible;
        }

        .company-edit-page .file-toolbar {
            display: block !important;
        }

        .company-edit-page .file-breadcrumb {
            margin-bottom: 10px;
        }

        .company-edit-page #btn-back {
            width: 100%;
        }

        .company-edit-page .files-panel .table-responsive {
            overflow: visible;
        }

        .company-edit-page #file-table,
        .company-edit-page #file-table tbody,
        .company-edit-page #file-table tr,
        .company-edit-page #file-table td {
            display: block;
            width: 100%;
        }

        .company-edit-page #file-table thead {
            display: none;
        }

        .company-edit-page #file-table tr {
            border: 1px solid #eef1f7;
            border-radius: 8px;
            padding: 10px 12px;
            margin-bottom: 10px;
            background: #fff;
        }

        .company-edit-page #file-table td {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 14px;
            border: 0;
            padding: 7px 0;
            white-space: normal;
            text-align: right !important;
        }

        .company-edit-page #file-table td:before {
            content: attr(data-label);
            color: #7e7e7e;
            font-size: 12px;
            font-weight: 700;
            text-align: left;
            min-width: 82px;
        }

        .company-edit-page #file-table td:first-child {
            display: block;
            text-align: left !important;
        }

        .company-edit-page #file-table td:first-child:before {
            display: block;
            margin-bottom: 6px;
        }

        .company-edit-page #file-table td .ms-2 {
            overflow-wrap: anywhere;
            white-space: normal;
        }
    }
</style>

<div class="company-edit-page">
<div class="row">
    <div class="col-12 col-xl-7">
        <div class="card h-auto">
            <div class="card-header">
				<h4 class="card-title">@lang('Editovanje Nove Firme')</h4>
                
                <a href="{{route('firme.index')}}" class="btn btn-secondary">
                    <i class="fa fa-arrow-left"></i>
                    @lang('Nazad')
                </a>
            </div>
            <div class="card-body">
                @include('firme.partials.form', ['entity' => $entity])
            </div>
        </div>
    </div>
    <div class="col-12 col-xl-5 files-panel">
        <div class="card h-auto">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3 file-toolbar">
                    <div id="breadcrumb" class="file-breadcrumb"></div>
                    <button id="btn-back" class="btn btn-sm btn-outline-secondary d-none">
                        <i class="fa fa-arrow-left"></i> @lang('Nazad')
                    </button>
                </div>
                <!-- Tabela -->
                <div class="table-responsive">
                    <table id="file-table" class="table table-hover">
                        <thead>
                            <tr>
                                <th>@lang('Naziv')</th>
                                <th>@lang('Tip')</th>
                                <th>@lang('Datum')</th>
                                <th class="text-end">@lang('Akcija')</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>
</div>

@endsection

@push('footer_scripts')
<script>
let companyId = {{ $entity->id }};
let currentPath = ''; // ROOT
const fileTableLabels = {
    name: @json(__('Naziv')),
    type: @json(__('Tip')),
    date: @json(__('Datum')),
    action: @json(__('Akcija'))
};

function loadFiles(path = '') {

    currentPath = path;

    $.get(`/file-list/${companyId}`, { path: path }, function(data) {

        let rows = '';

        // ✅ BACK dugme (NE MOŽE IZAĆI IZ FIRME)
        if (path) {

            let parts = path.split('/');

            // dozvoli back samo ako si dublje od firme
            if (parts.length > 2) {

                $('#btn-back').removeClass('d-none');

                let parent = parts.slice(0, -1).join('/');

                $('#btn-back').off().on('click', function () {
                    loadFiles(parent);
                });

            } else {
                // 🔒 na nivou firme → nema nazad
                $('#btn-back').addClass('d-none');
            }

        } else {
            $('#btn-back').addClass('d-none');
        }

        // FILES
        data.forEach(item => {

            rows += `
            <tr class="file-row">
                <td data-label="${fileTableLabels.name}" onclick="${item.is_dir ? `loadFiles('${item.path}')` : ''}" style="cursor:pointer;">
                    <div class="d-flex align-items-center">
                        ${
                            item.is_dir
                            ? '<i class="fa fa-folder text-warning"></i>'
                            : '<i class="fa fa-file text-primary"></i>'
                        }
                        <span class="ms-2">${item.name}</span>
                    </div>
                </td>

                <td data-label="${fileTableLabels.type}">${item.type}</td>
                <td data-label="${fileTableLabels.date}">${item.date}</td>

                <td class="text-end" data-label="${fileTableLabels.action}">
                    ${
                        item.is_dir
                        ? `<button class="btn btn-xs btn-info" onclick="loadFiles('${item.path}')">Otvori</button>`
                        : `<a href="/file-manager/view?path=${encodeURIComponent(item.path)}" target="_blank" class="btn btn-sm btn-light" title="Preview">
                                <i class="fa fa-eye"></i>
                           </a>`
                    }
                </td>
            </tr>`;
        });

        $('#file-table tbody').html(rows);

        renderBreadcrumb(path);
    });
}


// ✅ BREADCRUMB
function renderBreadcrumb(path) {

    // ROOT
    if (!path) {
        $('#breadcrumb').html(`
            <span class="active">
                <i class="fa fa-home me-1"></i> Fakture
            </span>
        `);
        return;
    }

    let parts = path.split('/');
    let html = '';
    let current = '';

    parts.forEach((part, index) => {

        current += (index === 0 ? part : '/' + part);

        let isLast = index === parts.length - 1;

        let icon = !isLast 
            ? '<i class="fa fa-folder text-warning me-1"></i>' 
            : '<i class="fa fa-folder-open text-warning me-1"></i>';

        // ❗ prvi dio (ausgangsrechnungen / eingangsrechnungen) NIJE klikabilan
        if (index === 0) {
            html += `
                <span class="active">
                    ${icon} ${formatBreadcrumbName(part)}
                </span>
            `;
        } else {
            html += `
                <span 
                    class="${isLast ? 'active' : ''}" 
                    ${!isLast ? `onclick="loadFiles('${current}')"` : ''}
                >
                    ${icon} ${formatBreadcrumbName(part)}
                </span>
            `;
        }

        if (!isLast) {
            html += `<span class="separator"> / </span>`;
        }
    });

    $('#breadcrumb').html(html);
}


// ✅ FORMAT NAZIVA
function formatBreadcrumbName(name) {

    if (name === 'ausgangsrechnungen') return 'Izlazne Fakture';
    if (name === 'eingangsrechnungen') return 'Ulazne Fakture';

    return name.replaceAll('-', ' ');
}


// INIT
$(function(){
    loadFiles(); // ROOT → Izlazne + Ulazne
});
</script>
@endpush
