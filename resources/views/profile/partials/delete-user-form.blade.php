<form method="POST" action="{{ route('profile.destroy') }}">
    @csrf
    @method('DELETE')

    <p class="text-muted mb-3">
        @lang('Ova akcija je nepovratna. Svi podaci će biti obrisani.')
    </p>

    <div class="mb-3">
        <input type="password" name="password" class="form-control"
               placeholder="@lang('Unesite šifru za potvrdu')">
        @error('password', 'userDeletion')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>

    <button style="float: right;" class="btn btn-danger">@lang('Obriši nalog')</button>
</form>