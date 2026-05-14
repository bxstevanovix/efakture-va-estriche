<div id="invoiceModal" class="modal angebot-modal rechnung-modal">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="createInvoiceModalLabel">@lang('Kreiranje računa')</h5>
            <button type="button" id="closeModal" class="btn btn-secondary">@lang('Zatvori')</button>
        </div>

        <div class="modal-body">
            <div class="modal-left">
                <div class="card">
                    <div class="card-body">
                        <div class="form-validation">
                            <div class="rechnung-type-selector">
                                <div class="doc-card active" data-type="rechnung">Rechnung</div>
                                <div class="doc-card" data-type="teilrechnung">Teilrechnung</div>
                                <div class="doc-card" data-type="schlussrechnung">Schlussrechnung</div>
                            </div>
                            <input type="hidden" name="invoice_type" id="invoice_type" value="rechnung">

                            <div class="row angebot-field-row">
                                <div class="mb-3 col-md-12">
                                    <label for="firma_select" class="form-label">Firma auswählen</label>
                                    <select id="firma_select" class="form-control">
                                        <option value="">-- Odaberi firmu --</option>
                                        @foreach($firme as $firma)
                                            <option
                                                value="{{ $firma->id }}"
                                                data-name="{{ $firma->name }}"
                                                data-adress="{{ $firma->address }}"
                                                data-ort="{{ $firma->ort }}"
                                                data-uid="{{ $firma->uid }}"
                                            >
                                                {{ $firma->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3 col-md-8" style="position: relative;">
                                    <label for="customer_name" class="form-label">@lang('Ime firme')</label>
                                    <input
                                        id="customer_name"
                                        type="text"
                                        class="form-control @errorClass('name', 'is-invalid')"
                                        name="name"
                                        placeholder="@lang('Ime firme')"
                                        maxlength="155"
                                        autocomplete="off"
                                        required
                                    >
                                    <div id="firma_box" class="autocomplete-box"></div>
                                    <div class="invalid-feedback">
                                        Bitte geben Sie den Namen der Firma ein.
                                    </div>
                                </div>

                                <div class="mb-3 col-md-4 spacing-control">
                                    <label for="spacing_input" class="form-label">Abstand Logo/Titel px</label>
                                    <input
                                        type="number"
                                        id="spacing_input"
                                        class="form-control"
                                        min="0"
                                        max="160"
                                        value="20"
                                        style="text-align:center;"
                                    >
                                </div>

                                <div class="mb-3 col-md-6" style="position: relative;">
                                    <label for="adress" class="form-label">@lang('Adresa')</label>
                                    <input
                                        id="adress"
                                        type="text"
                                        class="form-control @errorClass('adress', 'is-invalid')"
                                        name="adress"
                                        placeholder="@lang('Adresa')"
                                        maxlength="155"
                                        autocomplete="off"
                                        required
                                    >
                                    <div id="adress_box" class="autocomplete-box"></div>
                                    <div class="invalid-feedback">
                                        Bitte geben Sie die Adresse ein.
                                    </div>
                                </div>

                                <div class="mb-3 col-md-6">
                                    <label for="ort" class="form-label">@lang('Plz Ort Land')</label>
                                    <input
                                        id="ort"
                                        type="text"
                                        class="form-control @errorClass('ort', 'is-invalid')"
                                        name="ort"
                                        placeholder="@lang('Plz/Ort/Land')"
                                        maxlength="155"
                                        autocomplete="off"
                                    >
                                </div>

                                <div class="mb-3 col-md-4">
                                    <label for="uid" class="form-label">@lang('UID-Nummer')</label>
                                    <input
                                        id="uid"
                                        type="text"
                                        class="form-control @errorClass('uid', 'is-invalid')"
                                        name="uid"
                                        placeholder="@lang('UID-Nummer')"
                                        maxlength="30"
                                        autocomplete="off"
                                    >
                                </div>

                                <div class="mb-3 col-md-4">
                                    <label for="date" class="form-label">@lang('Datum')</label>
                                    <input
                                        id="date"
                                        type="date"
                                        class="form-control text-center @errorClass('date', 'is-invalid')"
                                        name="date"
                                        value="{{ now()->format('Y-m-d') }}"
                                        autocomplete="off"
                                        required
                                    >
                                </div>

                                <div class="mb-3 col-md-4">
                                    <label for="bvh" class="form-label">BVH</label>
                                    <input
                                        id="bvh"
                                        type="text"
                                        class="form-control"
                                        name="bvh"
                                        placeholder="z.B. 11000 Ort, Straße 11"
                                        maxlength="255"
                                        autocomplete="off"
                                    >
                                </div>

                                <div class="mb-3 col-md-4">
                                    <label for="auftragsnr" class="form-label">Objekt / Auftragsnr.</label>
                                    <input
                                        id="auftragsnr"
                                        type="text"
                                        class="form-control"
                                        placeholder="Obj. Nr. 123/36WE, Auftragsnr. 123/989/38/1"
                                        maxlength="255"
                                        autocomplete="off"
                                    >
                                </div>

                                <div class="mb-3 col-md-4 angebot-number-field">
                                    <label for="rechnung_nr" class="form-label">Rechnung Nr.</label>
                                    <input
                                        id="rechnung_nr"
                                        type="text"
                                        class="form-control"
                                        name="rechnung_nr"
                                        placeholder="R-001"
                                        maxlength="20"
                                        autocomplete="off"
                                        required
                                    >
                                    <div class="text-danger rechnung-error"></div>
                                </div>

                                <div class="mb-3 col-md-4 ausfuehrungszeit-field">
                                    <label for="ausführungszeit" class="form-label">Ausführungszeit</label>
                                    <input
                                        id="ausführungszeit"
                                        type="text"
                                        class="form-control"
                                        placeholder="KW: 32/25"
                                        maxlength="100"
                                        autocomplete="off"
                                    >
                                </div>
                            </div>

                            <hr>

                            <div class="row" id="items">
                                <div class="item-row col-12 first-row">
                                    <div style="position:relative;">
                                        <input name="items[0][name]" type="text" class="item-name form-control" placeholder="Beschreibung" maxlength="255" autocomplete="off">
                                        <div class="autocomplete-box beschreibung-box"></div>
                                    </div>
                                    <input name="items[0][qty]" type="text" class="item-qty form-control" value="0" autocomplete="off">
                                    <input name="items[0][price]" type="text" class="item-price form-control" value="0" autocomplete="off">
                                    <input name="items[0][total]" type="text" class="item-total form-control" value="0" autocomplete="off">
                                    <button style="background-color:#ddd;" disabled type="button" class="remove-item"></button>
                                </div>
                            </div>

                            <button type="button" id="addItem" class="btn btn-primary light add-item-btn">
                                <i class="fa fa-plus me-1"></i>Hinzufügen
                            </button>

                            <div class="row angebot-field-row">
                                <div class="col-md-2 mb-3 discount-percent-field">
                                    <label for="discount_percent" class="form-label">Nachlass %</label>
                                    <input type="number" id="discount_percent" class="form-control text-center" value="0" min="0" step="1" autocomplete="off">
                                </div>

                                <div class="col-md-2 mb-3 discount-fixed-field">
                                    <label for="discount_fixed" class="form-label">Nachlass Pauschale €</label>
                                    <input type="number" id="discount_fixed" class="form-control text-center" value="0" min="0" step="1" autocomplete="off">
                                </div>

                                <div class="col-md-2 mb-3 deckungs-field">
                                    <label for="deckungsrucklass_percent" class="form-label">Deckungsrücklass %</label>
                                    <input type="number" id="deckungsrucklass_percent" class="form-control text-center" value="0" min="0" step="1" autocomplete="off">
                                </div>

                                <div class="col-md-2 mb-3 abzug-label-field">
                                    <label for="abzug_tr_label" class="form-label">Abz. TR Text</label>
                                    <input type="text" id="abzug_tr_label" class="form-control text-center" value="Abz. TR 1" maxlength="40" autocomplete="off">
                                </div>

                                <div class="col-md-2 mb-3 abzug-value-field">
                                    <label for="abzug_tr1" class="form-label">Abz. TR €</label>
                                    <input type="number" id="abzug_tr1" class="form-control text-center" value="0" min="0" step="1" autocomplete="off">
                                </div>

                                <div class="col-md-2 mb-3 tax-field">
                                    <div class="form-check mt-md-4 pt-md-2">
                                        <input type="checkbox" id="use_tax" class="form-check-input" autocomplete="off">
                                        <label class="form-check-label" for="use_tax">20% MwSt</label>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="mb-3 col-md-8 note-editor-field">
                                    <div id="editor"></div>
                                </div>
                                <div class="mb-3 col-md-4 submit-action-field">
                                    <button id="createInvoice" type="button" class="btn btn-success waves-effect waves-light angebot-submit-btn">
                                        <i class="fa fa-save me-1"></i>
                                        @lang('Kreiraj račun')
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-right">
                <div id="a4wrapper" class="a4-wrapper">
                    <div id="previewPages" class="angebot-preview-pages"></div>
                </div>
            </div>
        </div>
    </div>
</div>
