<div class="form-group">
    @include('helium::input.common.label')
    <input
        @include('helium::input.common.attributes')
        value="{{ $field->getValue() }}"
        data-config="{{ json_encode($field->getConfig('flatpickr')) }}"
    >
    <div class="invalid-feedback">
        This field is required
    </div>
</div>
