<div class="form-group">
    @include('helium::input.common.label')

    <div @include('helium::input.common.attributes')>
        {{ $field->getValue() }}
    </div>
</div>