<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title }}</title>
    <link rel="manifest" href="{{ asset('manifest.webmanifest') }}?v=14">
    <link rel="icon" type="image/svg+xml" href="{{ asset('f-circle.svg') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('f-circle.svg') }}">
    <meta name="application-name" content="VA Estriche">
    <meta name="apple-mobile-web-app-title" content="VA Estriche">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="theme-color" content="#5746A3">
    <link href="{{ asset('files/vendor/bootstrap-select/css/bootstrap-select.min.css') }}" rel="stylesheet">
    <link href="{{ asset('files/css/style.css') }}?v={{ filemtime(public_path('files/css/style.css')) }}" rel="stylesheet">
    <link href="{{ asset('files/css/custom.css') }}?v={{ filemtime(public_path('files/css/custom.css')) }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        html,
        body {
            width: 100%;
            height: 100%;
            margin: 0;
            background: #f4f6fb;
        }

        .pdf-viewer-page {
            min-height: 100vh;
            min-height: 100dvh;
            display: flex;
            flex-direction: column;
            color: #1f2937;
        }

        .pdf-toolbar {
            min-height: 64px;
            padding: 10px 16px;
            padding-top: max(10px, env(safe-area-inset-top));
            background: #fff;
            border-bottom: 1px solid #eef1f7;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            box-shadow: 0 8px 24px rgba(31, 41, 55, 0.06);
            z-index: 2;
        }

        .pdf-title {
            min-width: 0;
        }

        .pdf-title strong {
            display: block;
            font-size: 15px;
            line-height: 1.25;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .pdf-title span {
            display: block;
            margin-top: 2px;
            color: #8a93a3;
            font-size: 12px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .pdf-actions {
            flex: 0 0 auto;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .pdf-actions .btn {
            width: 42px;
            height: 42px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px !important;
        }

        .pdf-frame-wrap {
            width: 100%;
            flex: 1 1 auto;
            min-height: calc(100vh - 64px);
            min-height: calc(100dvh - 64px);
            padding: 0;
            background: #fff;
        }

        .pdf-object,
        .pdf-frame {
            width: 100%;
            height: 100%;
            min-height: calc(100vh - 64px);
            min-height: calc(100dvh - 64px);
            border: 0;
            background: #fff;
            display: block;
        }

         @media (min-width: 767px) {
            .pdf-toolbar {
                display: none;
            }
        }

        @media (max-width: 767px) {
            .pdf-toolbar {
                min-height: 58px;
                padding-left: 10px;
                padding-right: 10px;
                gap: 8px;
            }

            .pdf-title strong {
                font-size: 13px;
            }

            .pdf-title span {
                font-size: 11px;
            }

            .pdf-actions {
                gap: 6px;
            }

            .pdf-actions .btn {
                width: 40px;
                height: 40px;
            }

            .pdf-frame-wrap {
                min-height: calc(100vh - 58px);
                min-height: calc(100dvh - 58px);
            }

            .pdf-object,
            .pdf-frame {
                min-height: calc(100vh - 58px);
                min-height: calc(100dvh - 58px);
            }
        }

        @media print {
            .pdf-toolbar {
                display: none;
            }

            .pdf-frame-wrap {
                padding: 0;
            }
        }
    </style>
</head>
<body>
    <div class="pdf-viewer-page">
        <div class="pdf-toolbar">
            <div class="pdf-title">
                <strong>{{ $title }}</strong>
                <span>{{ $fileName }}</span>
            </div>
            <div class="pdf-actions">
                <button class="btn btn-danger" id="backPdf" type="button" title="@lang('Zurück')" aria-label="@lang('Zurück')">
                    <i class="fa fa-arrow-left"></i>
                </button>
                <a class="btn btn-primary light" href="{{ $downloadUrl }}" title="@lang('Download')" aria-label="@lang('Download')">
                    <i class="fa fa-download"></i>
                </a>
                <button class="btn btn-primary" id="sharePdf" type="button" title="@lang('Share')" aria-label="@lang('Share')">
                    <i class="fa fa-share-nodes"></i>
                </button>
            </div>
        </div>

        <div class="pdf-frame-wrap">
            <object class="pdf-object" data="{{ $pdfUrl }}" type="application/pdf">
                <iframe class="pdf-frame" src="{{ $pdfUrl }}" title="{{ $title }}"></iframe>
            </object>
        </div>
    </div>

    <script>
        const pdfUrl = @json($pdfUrl);
        const downloadUrl = @json($downloadUrl);
        const fileName = @json($fileName);
        const title = @json($title);

        document.getElementById('backPdf').addEventListener('click', function () {
            window.history.back();
        });

        document.getElementById('sharePdf').addEventListener('click', async function () {
            try {
                if (navigator.share) {
                    if (navigator.canShare && window.File) {
                        try {
                            const response = await fetch(pdfUrl, { credentials: 'same-origin' });
                            const blob = await response.blob();
                            const file = new File([blob], fileName, { type: 'application/pdf' });

                            if (navigator.canShare({ files: [file] })) {
                                await navigator.share({ title: title, files: [file] });
                                return;
                            }
                        } catch (fileShareError) {}
                    }

                    await navigator.share({ title: title, url: downloadUrl });
                    return;
                }

                await navigator.clipboard.writeText(downloadUrl);
                alert(@json(__('Link je kopiran.')));
            } catch (error) {}
        });
    </script>
</body>
</html>
