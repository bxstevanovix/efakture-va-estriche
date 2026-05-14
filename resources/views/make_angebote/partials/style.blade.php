<style>
    .angebot-modal {
        display: none;
        position: fixed;
        inset: 0;
        z-index: 999;
        overflow: hidden;
        color: #111827;
        background: rgba(17, 24, 39, 0.62);
    }

    .angebot-modal .modal-content {
        width: 96%;
        height: 94vh;
        margin: 3vh auto;
        overflow: hidden;
        border: 0;
        border-radius: 10px;
        background: #fff;
        box-shadow: 0 18px 60px rgba(15, 23, 42, 0.26);
    }

    .angebot-modal .modal-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        padding: 14px 18px;
        border-bottom: 1px solid #eef1f7;
        background: #fff;
    }

    .angebot-modal .modal-title {
        margin: 0;
        color: #111827;
        font-weight: 700;
    }

    .angebot-modal .modal-body {
        height: calc(94vh - 67px);
        display: flex;
        gap: 0;
        overflow: hidden;
        padding: 0;
        background: #f5f6fa;
    }

    .angebot-modal .modal-left,
    .angebot-modal .modal-right {
        min-width: 0;
        height: 100%;
        overflow-y: auto;
    }

    .angebot-modal .modal-left {
        width: 50%;
        padding: 14px;
        background: #fff;
        border-right: 1px solid #eef1f7;
    }

    .angebot-modal .modal-right {
        width: 50%;
        padding: 18px 14px 28px;
        display: flex;
        justify-content: center;
        background: #f5f6fa;
    }

    .angebot-modal .card {
        height: auto !important;
        margin-bottom: 0;
        border-radius: 8px;
        box-shadow: none;
    }

    .angebot-modal .card-body {
        padding: 16px !important;
        padding-bottom: 24px !important;
    }

    .angebot-modal button,
    .angebot-modal #openModal {
        float: none;
    }

    .angebot-field-row {
        align-items: end;
    }

    .spacing-control .form-label {
        white-space: nowrap;
    }

    .item-row {
        width: 100%;
        display: grid;
        grid-template-columns: minmax(180px, 1fr) 95px 110px 110px 34px;
        gap: 8px;
        align-items: center;
        margin-bottom: 8px;
        position: relative;
    }

    .item-qty,
    .item-price,
    .item-total {
        text-align: center;
    }

    .item-total {
        font-weight: 500;
        text-align: right;
    }

    .item-qty::-webkit-inner-spin-button,
    .item-qty::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    .item-qty {
        -moz-appearance: textfield;
    }

    .remove-item {
        width: 34px;
        height: 34px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 0;
        border-radius: 6px;
        background: #ff4d4f;
        color: #fff;
        font-size: 16px;
        line-height: 1;
    }

    .remove-item:hover {
        background: #d9363e;
    }

    .add-item-btn {
        width: 100%;
        max-width: 500px;
        display: block;
        margin: 18px auto;
        text-align: center;
    }

    .angebot-submit-btn {
        width: 100%;
        min-height: 44px;
        margin-top: 32px;
    }

    .autocomplete-box {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        z-index: 9999;
        width: auto;
        min-width: 0;
        max-height: 200px;
        overflow-y: auto;
        border: 1px solid #ddd;
        border-top: 0;
        border-radius: 0 0 10px 10px;
        background: #fff;
        box-shadow: 0 6px 15px rgba(15, 23, 42, 0.1);
        box-sizing: border-box;
    }

    .angebot-field-row > [class*="col-"] > .autocomplete-box {
        left: 15px;
        right: 15px;
    }

    .autocomplete-box:empty {
        display: none;
    }

    .autocomplete-item {
        padding: 8px;
        overflow: hidden;
        cursor: pointer;
        white-space: nowrap;
        text-overflow: ellipsis;
    }

    .autocomplete-item:hover,
    .autocomplete-item.is-active {
        background: #f2f2f2;
    }

    .ql-editor {
        min-height: 135px;
        font-family: Arial, Helvetica, sans-serif;
        font-size: 10pt;
    }

    .a4-wrapper,
    .angebot-preview-pages {
        width: 794px;
        transform-origin: top center;
    }

    .angebot-preview-pages {
        display: flex;
        flex-direction: column;
        gap: 18px;
    }

    .a4-preview {
        width: 794px;
        height: 1123px;
        padding: 5mm 16mm 22mm 16mm;
        box-sizing: border-box;
        overflow: hidden;
        position: relative;
        background: #fff;
        color: #000 !important;
        font-family: Arial, Helvetica, sans-serif;
        font-size: 10pt;
        font-weight: 350;
        letter-spacing: 0.08px;
        -webkit-font-smoothing: antialiased;
        text-rendering: geometricPrecision;
        box-shadow: 0 0 15px rgba(15, 23, 42, 0.18);
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }

    .angebot-page-content {
        position: relative;
        z-index: 1;
        max-height: calc(1123px - 5mm - 42mm);
        padding-bottom: 24mm;
    }

    .header-a4 {
        height: 185px;
        display: grid;
        grid-template-columns: minmax(0, 1fr) 455px;
        grid-template-rows: 1fr auto;
        align-items: start;
        justify-items: end;
        position: relative;
        padding: 10px 4mm 0 0;
        box-sizing: border-box;
    }

    .company-logo {
        width: 96px;
        justify-self: end;
        margin-top: 0;
    }

    .company-logo img {
        width: 100%;
        height: auto;
        display: block;
    }

    .company-text {
        grid-column: 2;
        grid-row: 1;
        width: 335px;
        padding-right: 0;
        box-sizing: border-box;
    }

    .company-name {
        width: 100%;
        margin: 0 0 13px 0;
        color: #666666;
        font-size: 20pt;
        font-weight: 500;
        line-height: 1.12;
        text-align: right;
    }

    .company-content {
        display: grid;
        grid-template-columns: minmax(0, 1fr) 96px;
        column-gap: 12px;
        align-items: start;
        width: 100%;
        margin-left: 5px;
    }

    .company-details {
        display: block;
        width: max-content;
        justify-self: start;
    }

    .company-text-left,
    .company-text-right {
        width: auto;
    }

    .company-text-left p,
    .company-text-right p {
        margin: 0 0 6px;
        color: #666666;
        font-size: 8pt;
        line-height: 1.25;
        text-align: left;
    }

    .firma {
        margin-top: 5px;
    }

    .customer-lead {
        max-width: 350px;
        margin: 0 0 8px 0;
        font-size: 10pt;
        font-weight: 700;
        word-wrap: break-word;
    }

    .customer-address {
        margin: 4px 0 0 0;
        font-size: 10pt;
        line-height: 1.08;
    }

    .customer-address + .customer-address {
        margin-top: 2px;
    }

    .firma-hr {
        width: 350px;
        max-width: 100%;
        height: 1px;
        margin: 2px 0 6px;
        background-color: #000;
    }

    .customer-meta {
        display: flex;
        justify-content: space-between;
        margin-top: 0;
        font-size: 10pt;
    }

    .customer {
        margin-top: 5px;
        font-size: 10pt;
    }

    .customer p {
        margin: 0 0 4px 0;
        line-height: 1.05;
    }

    .angebot-title-line {
        font-weight: 700;
    }

    .page-continuation {
        margin: 0 0 10px 0;
        color: #555;
        font-size: 10pt;
        font-weight: 500;
        text-align: right;
    }

    .invoice-table {
        width: 100%;
        table-layout: fixed;
        border-collapse: collapse;
        border: 0.6px solid #333;
        font-family: Arial, Helvetica, sans-serif;
        font-size: 10pt;
        font-weight: 350;
    }

    .invoice-table-head {
        margin-bottom: 0;
    }

    .invoice-table-gap {
        width: 100%;
        height: 8px;
    }

    .invoice-table col.col-desc {
        width: calc(100% - 300px);
    }

    .invoice-table col.col-qty,
    .invoice-table col.col-price,
    .invoice-table col.col-total {
        width: 100px;
    }

    .invoice-table th,
    .invoice-table td {
        padding: 3px 8px;
        border-bottom: 0.6px solid #333;
        line-height: 1.25;
        vertical-align: middle;
    }

    .invoice-table th {
        font-weight: 500;
    }

    .invoice-table th:not(:first-child),
    .invoice-table td:not(:first-child) {
        border-left: 0.6px solid #333;
    }

    .invoice-table th:first-child,
    .invoice-table td:first-child {
        text-align: left !important;
        word-break: break-word;
    }

    .invoice-table td:nth-child(2),
    .invoice-table td:nth-child(3),
    .invoice-table td:nth-child(4) {
        text-align: center;
        white-space: nowrap;
    }

    .invoice-table .amount-cell {
        display: flex;
        justify-content: space-between;
        gap: 8px;
    }

    .offer-summary-wrap {
        margin-right: 9px;
    }

    .offer-summary {
        width: fit-content;
        min-width: 300px;
        max-width: 100%;
        margin-top: 18px;
        margin-left: auto;
        font-size: 10pt;
    }

    .summary-row {
        display: grid;
        grid-template-columns: minmax(0, 1fr) auto;
        align-items: baseline;
        column-gap: 14px;
        margin-bottom: 2px;
    }

    .summary-row > span:first-child {
        margin-left: 50px;
    }

    .summary-divider {
        margin-left: 50px;
    }

    .summary-amount {
        min-width: 100px;
        width: max-content;
        display: inline-grid;
        grid-template-columns: auto auto;
        justify-content: end;
        justify-self: end;
        white-space: nowrap;
    }

    .summary-running-total {
        margin-top: -1px;
        margin-bottom: 5px;
    }

    .summary-running-total .summary-amount {
        padding-top: 2px;
        border-top: 1px solid #777;
    }

    .summary-total {
        font-weight: 500;
        font-size: 11pt;
    }

    .summary-total-value {
        column-gap: 8px;
    }

    .summary-total-value {
        padding-bottom: 2px;
        border-bottom: 2px double #000;
        white-space: nowrap;
    }

    .description-left {
        width: 385px;
        margin-top: 18px;
        margin-left: 24px;
        padding: 0;
        color: #000;
        font-family: Arial, Helvetica, sans-serif;
        font-size: 10pt;
        font-weight: 350;
        line-height: 1.25;
        overflow-wrap: break-word;
    }

    .a4-preview .description-left.preview-note {
        min-height: 0;
    }

    .description-left p,
    .a4-preview .description-left.preview-note p {
        margin: 0 0 4px 0;
    }

    .description-left ul,
    .description-left ol,
    .a4-preview .description-left.preview-note ul,
    .a4-preview .description-left.preview-note ol {
        margin: 0 0 4px 0;
        padding-left: 20px;
        list-style-position: outside;
    }

    .description-left ul,
    .a4-preview .description-left.preview-note ul {
        list-style-type: disc;
    }

    .description-left ol,
    .a4-preview .description-left.preview-note ol {
        list-style-type: decimal;
    }

    .description-left li,
    .a4-preview .description-left.preview-note li {
        display: list-item;
        margin: 0 0 3px 0;
        padding-left: 2px;
        list-style: inherit;
    }

    .description-left strong,
    .description-left b,
    .a4-preview .description-left.preview-note strong,
    .a4-preview .description-left.preview-note b {
        font-weight: 700 !important;
    }

    .description-left em,
    .description-left i,
    .a4-preview .description-left.preview-note em,
    .a4-preview .description-left.preview-note i {
        font-style: italic !important;
    }

    .description-left u,
    .a4-preview .description-left.preview-note u {
        text-decoration: underline !important;
    }

    .description-left .ql-size-small,
    .a4-preview .description-left.preview-note .ql-size-small {
        font-size: 8pt;
    }

    .description-left .ql-size-large,
    .a4-preview .description-left.preview-note .ql-size-large {
        font-size: 13pt;
    }

    .description-left .ql-size-huge,
    .a4-preview .description-left.preview-note .ql-size-huge {
        font-size: 18pt;
    }

    .a4-preview .description-left.preview-note ol > li::before,
    .a4-preview .description-left.preview-note ul > li::before {
        content: none !important;
    }

    .reverse-vat-note {
        width: 100%;
        margin-top: 50px;
        color: #000;
        font-size: 9pt;
        text-align: center;
    }

    .invoice-footer {
        position: absolute;
        bottom: 14mm;
        left: 16mm;
        right: 16mm;
        min-height: 8mm;
        z-index: 50;
        color: #000;
        background: #fff;
        font-family: Arial, Helvetica, sans-serif;
        font-size: 9pt;
        font-weight: 350;
        line-height: 1.2;
        display: flex;
        align-items: flex-end;
        justify-content: center;
        text-align: center;
        -webkit-font-smoothing: antialiased;
        text-rendering: geometricPrecision;
    }

    .page-counter {
        position: absolute;
        right: 0;
        top: -5mm;
        display: none;
        color: #000;
        text-align: right;
    }

    @media (max-width: 1199px) {
        .angebot-modal .modal-body {
            display: block;
            overflow-y: auto;
        }

        .angebot-modal .modal-left,
        .angebot-modal .modal-right {
            width: 100%;
            height: auto;
            overflow: visible;
        }

        .angebot-modal .modal-left {
            border-right: 0;
            border-bottom: 1px solid #eef1f7;
        }

        .angebot-modal .modal-right {
            min-height: 420px;
        }

        .note-editor-field,
        .submit-action-field {
            flex: 0 0 100%;
            max-width: 100%;
        }

        .submit-action-field {
            clear: both;
        }

        .angebot-submit-btn {
            margin-top: 24px;
        }
    }

    @media (max-width: 767px) {
        .angebot-modal .modal-content {
            width: 100%;
            height: 100%;
            margin: 0;
            border-radius: 0;
        }

        .angebot-modal .modal-header {
            padding: 12px 14px;
        }

        .angebot-modal .modal-body {
            height: calc(100vh - 61px);
        }

        .angebot-modal .modal-left {
            padding: 12px;
        }

        .angebot-modal .modal-right {
            padding: 14px 0 28px;
        }

        .angebot-modal .card-body {
            padding: 14px !important;
        }

        .a4-preview .invoice-table {
            border: 1px solid #111;
            box-shadow: inset 0 0 0 1px #111;
        }

        .a4-preview .invoice-table th,
        .a4-preview .invoice-table td {
            border-bottom: 1px solid #111;
        }

        .a4-preview .invoice-table th:not(:first-child),
        .a4-preview .invoice-table td:not(:first-child) {
            border-left: 1px solid #111;
        }

        .angebot-number-field,
        .ausfuehrungszeit-field,
        .discount-percent-field,
        .discount-fixed-field,
        .deckungs-field,
        .tax-field,
        .abzug-label-field,
        .abzug-value-field {
            flex: 0 0 50%;
            max-width: 50%;
        }

        .discount-percent-field {
            order: 1;
        }

        .discount-fixed-field {
            order: 2;
        }

        .deckungs-field {
            order: 3;
        }

        .tax-field {
            order: 4;
            display: flex;
            align-items: end;
            padding-bottom: 2px;
        }

        .tax-field .form-check {
            min-height: 38px;
            display: flex;
            align-items: center;
            margin-bottom: 0;
        }

        .abzug-label-field {
            order: 5;
        }

        .abzug-value-field {
            order: 6;
        }

        .angebot-number-field .form-label,
        .ausfuehrungszeit-field .form-label,
        .discount-percent-field .form-label,
        .discount-fixed-field .form-label,
        .deckungs-field .form-label,
        .abzug-label-field .form-label,
        .abzug-value-field .form-label {
            min-height: 34px;
            display: flex;
            align-items: end;
            line-height: 1.15;
        }

        .item-row {
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 8px;
            padding: 10px;
            border: 1px solid #eef1f7;
            border-radius: 8px;
            background: #fff;
        }

        .item-row > div:first-child {
            grid-column: 1 / -1;
        }

        .item-qty,
        .item-price,
        .item-total {
            width: 100%;
            min-width: 0;
        }

        .item-qty {
            grid-column: 1;
        }

        .item-price {
            grid-column: 2;
        }

        .item-total {
            grid-column: 3;
        }

        .remove-item {
            width: 100%;
            grid-column: 1 / -1;
        }

        .angebot-submit-btn {
            margin-top: 40px;
        }
    }
</style>
