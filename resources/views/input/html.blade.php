<div class="form-group">
    @include('helium::input.common.label')
    <textarea
        @include('helium::input.common.attributes')
        data-config="{{ $field->getTinyConfig() }}"
    >{{ $field->getValue() }}</textarea>
</div>
