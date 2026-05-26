@include('make_angebote.partials.style')

<style>
    .rechnung-type-selector {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 8px;
        margin-bottom: 16px;
    }

    .doc-card {
        min-height: 42px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #d8deea;
        border-radius: 8px;
        background: #fff;
        color: #2f3a4a;
        font-size: 13px;
        font-weight: 700;
        text-align: center;
        cursor: pointer;
        transition: border-color .15s ease, color .15s ease, background .15s ease;
    }

    .doc-card:hover,
    .doc-card.active {
        border-color: var(--primary);
        background: #f3f7ff;
        color: var(--primary);
    }

    .rechnung-modal .select2-container {
        width: 100% !important;
    }

    .rechnung-modal .select2-container .select2-selection--single {
        min-height: 38px;
        display: flex;
        align-items: center;
        border-color: #d8deea;
    }

    @media (max-width: 575px) {
        .rechnung-type-selector {
            grid-template-columns: 1fr;
        }
    }
</style>
