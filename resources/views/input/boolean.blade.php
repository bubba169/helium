<div class="form-group toggle-checkbox">
    <label for="{{ $field->getId() }}">
        <div class="label">{{ $field->getLabel() }}</div>
        <div class="switch">
            <input type="hidden" value="0" name="{{ $field->getName() }}"/>
            <input
                @include('helium::input.common.attributes')
                {{ $field->getValue() ? 'checked' : '' }}
            >
            <div class="slider"></div>
        </div>
    </label>
</div>
