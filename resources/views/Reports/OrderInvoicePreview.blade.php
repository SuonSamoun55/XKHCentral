@extends($layout)

@push('styles')
    <style>
            .page-wrap{
                background-color:white;
                border-radius: 12px
            }
        .report-preview-page {
            display: flex;
            flex-direction: column;
            height: 100vh;
            padding: 20px;
            box-sizing: border-box;
            overflow: hidden;

        }
        .report-preview-toolbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 16px;
            flex-shrink: 0;
        }
        .report-preview-heading {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .report-preview-title {
            font-size: 24px;
            font-weight: 700;
            color: #3BACB4;
        }
        .report-preview-actions {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .report-preview-actions a,
        .report-preview-actions button {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            border: none;
            cursor: pointer;
            font-family: inherit;
        }
        .btn-back {
            display: flex;
            align-items: center;
            justify-content: center;
            background: #fff;
            color: #222;
            padding: 0;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: 1px solid #e7e9ee;
            font-size: 18px;
            flex-shrink: 0;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.05);
            transition: all 0.2s;
        }
        .btn-back:hover {
            border-color: #0EA8B2;
            color: #0EA8B2;
        }
        .btn-download {
            background: #0EA8B2;
            color: #fff;
        }
        .fit-toggle {
            display: inline-flex;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            overflow: hidden;
        }
        .fit-toggle button {
            border-radius: 0;
            background: #f3f4f6;
            color: #374151;
            padding: 8px 14px;
            display: flex;
            justify-content: center
        }
        .fit-toggle button + button {
            border-left: 1px solid #e5e7eb;
        }
        .fit-toggle button.active {
            background: #0EA8B2;
            color: #fff;
        }
        .report-preview-frame-wrap {
            flex: 1;
            min-height: 0;
            display: flex;
            justify-content: center;
        }
        .report-preview-frame-wrap::-webkit-scrollbar{
            width: 0px
        }
        #pdfViewerContainer {
            width: 100%;
            overflow: auto;
            background: #e5e7eb;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 16px 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 16px;
        }
        #pdfViewerContainer::-webkit-scrollbar{
            width: 0px
        }
        #pdfViewerContainer canvas {
            background: #fff;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            display: block;
        }
        .pdf-viewer-loading {
            color: #6b7280;
            font-size: 13px;
            padding: 40px 0;
        }
        @media (max-width: 768px) {
            .report-preview-page {
                padding: 0px;
            }
           
            .page-wrap{
                border-radius: 0px;
            }
            .main-wrapper{
                height: 100%;
            }
            .report-preview-toolbar {
                flex-wrap: wrap;
                margin-bottom: 12px;
            }
            .report-preview-title {
                font-size: 15px;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }
            .report-preview-heading {
                min-width: 0;
                flex: 1 1 100%;
                display: none

            }
            .report-preview-actions {
                flex: 1 1 100%;
                justify-content: stretch;
            }
            .fit-toggle {
                flex: 1;
                border-radius: 5px;
            }
            .fit-toggle button {
                flex: 1;
                padding: 8px 6px;
                font-size: 12px;

            }
            .btn-download {
                flex-shrink: 0;
                padding: 8px 12px;
                font-size: 12px;
            }
            .report-preview-actions a, .report-preview-actions button{
                border-radius: 5px;

            }

        }
    </style>
@endpush

@section('title', 'Report Preview - ' . $order->order_no)

@section('content')
    <div class="page-wrap report-preview-page">
        <div class="report-preview-toolbar">
            <div class="report-preview-heading">
                <a href="{{ url()->previous() }}" class="btn-back" title="Back" aria-label="Back">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <div class="report-preview-title">Report Preview — Order {{ $order->order_no }}</div>
            </div>
            <div class="report-preview-actions">
                <div class="fit-toggle">
                    <button type="button" id="fitWidthBtn" class="active">Fit Width</button>
                    <button type="button" id="fitPageBtn">Fit Page</button>
                </div>
                <a href="{{ $downloadUrl }}" class="btn-download">Download PDF</a>
            </div>
        </div>
        <div class="report-preview-frame-wrap">
            <div id="pdfViewerContainer">
                <div class="pdf-viewer-loading">Loading report…</div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
    <script>
        (function () {
            pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';

            var streamUrl = @json($streamUrl);
            var container = document.getElementById('pdfViewerContainer');
            var fitWidthBtn = document.getElementById('fitWidthBtn');
            var fitPageBtn = document.getElementById('fitPageBtn');
            var pdfDoc = null;
            var mode = 'width';

            pdfjsLib.getDocument(streamUrl).promise.then(function (pdf) {
                pdfDoc = pdf;
                renderAllPages();
            }).catch(function (err) {
                container.innerHTML = '<div class="pdf-viewer-loading">Couldn\'t load the report preview.</div>';
                console.error(err);
            });

            function renderAllPages() {
                container.innerHTML = '';

                var pageNumbers = [];
                for (var i = 1; i <= pdfDoc.numPages; i++) {
                    pageNumbers.push(i);
                }
                pageNumbers.reduce(function (chain, pageNumber) {
                    return chain.then(function () { return renderPage(pageNumber); });
                }, Promise.resolve());
            }

            function renderPage(pageNumber) {
                return pdfDoc.getPage(pageNumber).then(function (page) {
                    var unscaledViewport = page.getViewport({ scale: 1 });
                    var wrapWidth = container.clientWidth - 32; // minus container's own padding/scrollbar slack
                    var wrapHeight = container.clientHeight - 32;

                    var scale = mode === 'page'
                        ? Math.min(wrapWidth / unscaledViewport.width, wrapHeight / unscaledViewport.height)
                        : wrapWidth / unscaledViewport.width;

                    var viewport = page.getViewport({ scale: scale });
                    var outputScale = Math.max(window.devicePixelRatio || 1, 2);
                    var canvas = document.createElement('canvas');
                    canvas.width = Math.floor(viewport.width * outputScale);
                    canvas.height = Math.floor(viewport.height * outputScale);
                    canvas.style.width = Math.floor(viewport.width) + 'px';
                    canvas.style.height = Math.floor(viewport.height) + 'px';
                    container.appendChild(canvas);

                    return page.render({
                        canvasContext: canvas.getContext('2d'),
                        viewport: viewport,
                        transform: [outputScale, 0, 0, outputScale, 0, 0],
                    }).promise;
                });
            }

            fitWidthBtn.addEventListener('click', function () {
                mode = 'width';
                fitWidthBtn.classList.add('active');
                fitPageBtn.classList.remove('active');
                if (pdfDoc) renderAllPages();
            });

            fitPageBtn.addEventListener('click', function () {
                mode = 'page';
                fitPageBtn.classList.add('active');
                fitWidthBtn.classList.remove('active');
                if (pdfDoc) renderAllPages();
            });
        })();
    </script>
@endpush
