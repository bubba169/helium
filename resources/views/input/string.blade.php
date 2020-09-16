<div class="form-group">
    @if($field->getAttribute('type') != 'hidden')
        @include('helium::input.common.label')
    @endif

    <input
        @include('helium::input.common.attributes')
        value="{{ $field->getAttribute('type') != 'password' ? $field->getValue() : '' }}"
    >
    <div class="invalid-feedback">
        @if($field->getAttribute('type') == 'email')
            Please enter a valid email address
        @elseif($field->getAttribute('type') == 'url')
            Please enter a valid url
        @else
            This field is required
        @endif
    </div>
</div>
