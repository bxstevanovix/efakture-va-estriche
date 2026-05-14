<form id="entity-form" method="post" action="" enctype="multipart/form-data" autocomplete="off" class="needs-validation company-form">
    @csrf

    <div class="form-validation">
        <div class="row">
            <div class="mb-3 col-12">
                <label class="form-label">@lang('Ime firme')</label>
                <input
                    type="text"
                    class="form-control @errorClass('name', 'is-invalid')"
                    name="name"
                    placeholder="@lang('Ime firme')"
                    value="{{ old('name', $entity->name) }}"
                    maxlength="155"
                    required
                >
                <div class="invalid-feedback">
                    Please enter a username.
                </div>
            </div>

            <div class="mb-3 col-lg-7">
                <label class="form-label">@lang('Adresa')</label>
                <input
                    type="text"
                    class="form-control @errorClass('address', 'is-invalid')"
                    name="address"
                    placeholder="@lang('Adresa')"
                    value="{{ old('address', $entity->address) }}"
                    maxlength="155"
                    required
                >
                <div class="invalid-feedback">
                    Please enter a adress.
                </div>
            </div>

            <div class="mb-3 col-lg-5">
                <label class="form-label">@lang('Plz Ort Land')</label>
                <input
                    type="text"
                    class="form-control @errorClass('ort', 'is-invalid')"
                    name="ort"
                    placeholder="@lang('Plz Ort Land')"
                    value="{{ old('ort', $entity->ort) }}"
                    maxlength="155"
                    required
                >
                <div class="invalid-feedback">
                    Please enter a adress.
                </div>
            </div>

            <div class="mb-3 col-md-6">
                <label class="form-label">@lang('Firmenbuchnummer')</label>
                <input
                    type="text"
                    class="form-control @errorClass('jib', 'is-invalid')"
                    name="jib"
                    placeholder="@lang('Firmenbuchnummer')"
                    value="{{ old('jib', $entity->jib) }}"
                    maxlength="30"
                    required
                >
                <div class="invalid-feedback">
                    Please enter a jiv number.
                </div>
            </div>

            <div class="mb-3 col-md-6">
                <label class="form-label">@lang('UID-Nummer')</label>
                <input
                    type="text"
                    class="form-control @errorClass('uid', 'is-invalid')"
                    name="uid"
                    placeholder="@lang('UID-Nummer')"
                    value="{{ old('uid', $entity->uid) }}"
                    maxlength="30"
                    required
                >
                <div class="invalid-feedback">
                    Please enter a uid number.
                </div>
            </div>

            <div class="mb-3 col-md-6">
                <label class="form-label">@lang('E-mail')</label>
                <input
                    type="email"
                    class="form-control @errorClass('email', 'is-invalid')"
                    name="email"
                    placeholder="@lang('Email')"
                    value="{{ old('email', $entity->email) }}"
                    maxlength="155"
                    required
                >
                <div class="invalid-feedback">
                    Please enter a valid email.
                </div>
            </div>

            <div class="mb-3 col-md-6">
                <label class="form-label">@lang('Telefon')</label>
                <input
                    type="text"
                    class="form-control @errorClass('phone', 'is-invalid')"
                    name="phone"
                    placeholder="@lang('Telefon')"
                    value="{{ old('phone', $entity->phone) }}"
                    maxlength="30"
                    required
                >
                <div class="invalid-feedback">
                    Please enter a phone number.
                </div>
            </div>
        </div>
    </div>

    <div class="form-actions">
        <button type="submit" class="btn btn-success waves-effect waves-light">
            <i class="fa fa-save"></i>
            @lang('Sačuvaj')
        </button>
    </div>
</form>
