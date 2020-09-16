<div class="form-group">
    <div class="custom-control custom-switch custom-switch-md">
        <input type="hidden" value="0" name="{{ $field->getName() }}"/>
        <input
            @include('helium::input.common.attributes')
            {{ $field->getValue() ? 'checked' : '' }}
        >
        @include('helium::input.common.label')
    </div>
</div>
