@php
    $statuses = $statuses ?? \App\Models\Employee::STATUSES;
    $contractTypes = $contractTypes ?? \App\Models\Employee::CONTRACT_TYPES;
    $documentTypes = $documentTypes ?? \App\Models\Employee::DOCUMENT_TYPES;
@endphp

<style>
    .employee-form .form-section-title {
        font-size: 14px;
        font-weight: 700;
        color: #2f3a4a;
        margin: 0 0 14px;
    }

    .employee-form .document-upload-row {
        border: 1px solid #eef1f7;
        border-radius: 8px;
        padding: 14px;
        margin-bottom: 12px;
        background: #fff;
    }

    .employee-form .document-upload-title {
        font-weight: 700;
        color: #2f3a4a;
        margin-bottom: 10px;
    }

    .employee-form .form-actions {
        display: flex;
        justify-content: flex-end;
        padding-top: 16px;
        border-top: 1px solid #eef1f7;
    }

    @media (max-width: 767px) {
        .employee-form .form-actions {
            display: block;
        }

        .employee-form .form-actions .btn {
            width: 100%;
        }
    }
</style>

<form method="post" action="{{ route('employees.store') }}" enctype="multipart/form-data" autocomplete="off" class="employee-form needs-validation">
    @csrf

    <h5 class="form-section-title">@lang('Osnovni podaci')</h5>
    <div class="row">
        <div class="mb-3 col-md-3">
            <label class="form-label">@lang('Broj radnika')</label>
            <input type="text" name="employee_number" class="form-control @errorClass('employee_number', 'is-invalid')" value="{{ old('employee_number', $entity->employee_number) }}" maxlength="50">
            @error('employee_number')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3 col-md-3">
            <label class="form-label">@lang('Ime')</label>
            <input type="text" name="first_name" class="form-control @errorClass('first_name', 'is-invalid')" value="{{ old('first_name', $entity->first_name) }}" maxlength="100" required>
            @error('first_name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3 col-md-3">
            <label class="form-label">@lang('Prezime')</label>
            <input type="text" name="last_name" class="form-control @errorClass('last_name', 'is-invalid')" value="{{ old('last_name', $entity->last_name) }}" maxlength="100" required>
            @error('last_name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3 col-md-3">
            <label class="form-label">@lang('Status')</label>
            <select name="status" class="form-control @errorClass('status', 'is-invalid')" required>
                @foreach($statuses as $value => $label)
                    <option value="{{ $value }}" @selected(old('status', $entity->status ?: 'active') === $value)>{{ __($label) }}</option>
                @endforeach
            </select>
            @error('status')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3 col-md-4">
            <label class="form-label">@lang('Broj telefona')</label>
            <input type="text" name="phone" class="form-control @errorClass('phone', 'is-invalid')" value="{{ old('phone', $entity->phone) }}" maxlength="50">
            @error('phone')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3 col-md-4">
            <label class="form-label">@lang('E-mail')</label>
            <input type="email" name="email" class="form-control @errorClass('email', 'is-invalid')" value="{{ old('email', $entity->email) }}" maxlength="255">
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3 col-md-4">
            <label class="form-label">@lang('Datum rođenja')</label>
            <input type="date" name="birth_date" class="form-control @errorClass('birth_date', 'is-invalid')" value="{{ old('birth_date', $entity->birth_date?->format('Y-m-d')) }}">
            @error('birth_date')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3 col-md-8">
            <label class="form-label">@lang('Adresa')</label>
            <input type="text" name="address" class="form-control @errorClass('address', 'is-invalid')" value="{{ old('address', $entity->address) }}" maxlength="1000">
            @error('address')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3 col-md-4">
            <label class="form-label">@lang('Državljanstvo')</label>
            <input type="text" name="nationality" class="form-control @errorClass('nationality', 'is-invalid')" value="{{ old('nationality', $entity->nationality) }}" maxlength="100">
            @error('nationality')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <h5 class="form-section-title mt-3">@lang('Zaposlenje')</h5>
    <div class="row">
        <div class="mb-3 col-md-3">
            <label class="form-label">@lang('Pozicija')</label>
            <input type="text" name="position" class="form-control @errorClass('position', 'is-invalid')" value="{{ old('position', $entity->position) }}" maxlength="120" placeholder="@lang('Radnik')">
            @error('position')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3 col-md-3">
            <label class="form-label">@lang('Datum početka rada')</label>
            <input type="date" name="entry_date" class="form-control @errorClass('entry_date', 'is-invalid')" value="{{ old('entry_date', $entity->entry_date?->format('Y-m-d')) }}">
            @error('entry_date')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3 col-md-3">
            <label class="form-label">@lang('Tip ugovora')</label>
            <select name="contract_type" class="form-control @errorClass('contract_type', 'is-invalid')">
                <option value="">-</option>
                @foreach($contractTypes as $value => $label)
                    <option value="{{ $value }}" @selected(old('contract_type', $entity->contract_type) === $value)>{{ __($label) }}</option>
                @endforeach
            </select>
            @error('contract_type')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3 col-md-3">
            <label class="form-label">@lang('Satnica')</label>
            <div class="input-group">
                <input type="number" name="hourly_wage" class="form-control @errorClass('hourly_wage', 'is-invalid')" value="{{ old('hourly_wage', $entity->hourly_wage) }}" min="0" max="999.99" step="0.01">
                <span class="input-group-text">EUR</span>
                @error('hourly_wage')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="mb-3 col-12">
            <label class="form-label">@lang('Napomene')</label>
            <textarea name="notes" class="form-control @errorClass('notes', 'is-invalid')" rows="3" maxlength="2000">{{ old('notes', $entity->notes) }}</textarea>
            @error('notes')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <h5 class="form-section-title mt-3">@lang('Dokumentacija radnika')</h5>
    <div class="row">
        @foreach($documentTypes as $type => $label)
            <div class="col-xl-6">
                <div class="document-upload-row">
                    <div class="document-upload-title">{{ __($label) }}</div>
                    <input type="hidden" name="documents[{{ $loop->index }}][type]" value="{{ $type }}">
                    <div class="row">
                        <div class="mb-3 col-md-7">
                            <input type="file" name="documents[{{ $loop->index }}][file]" class="form-control @errorClass('documents.' . $loop->index . '.file', 'is-invalid')" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                            @error('documents.' . $loop->index . '.file')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-5">
                            <input type="date" name="documents[{{ $loop->index }}][expires_at]" class="form-control @errorClass('documents.' . $loop->index . '.expires_at', 'is-invalid')">
                            @error('documents.' . $loop->index . '.expires_at')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-12">
                            <input type="text" name="documents[{{ $loop->index }}][notes]" class="form-control" placeholder="@lang('Napomena')">
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="form-actions">
        <button type="submit" class="btn btn-success">
            <i class="fa fa-save"></i> @lang('Sačuvaj')
        </button>
    </div>
</form>
