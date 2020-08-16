<div class="form-group toggle-checkbox">
    <label for="{{ $field->getId() }}">
        <div class="label">{{ $field->getLabel() }}</div>
        <div class="switch">
            <input type="hidden" value="0" name="{{ $field->getName() }}"/>
            <input
                id="{{ $field->getId() }}"
                name="{{ $field->getName() }}"

                {{ old($field->getName(), $field->getValue()) ? 'checked' : '' }}

                class="{{ $field->getClasses()->join(' ') }}"

                type="checkbox"

                @foreach ($field->getAttributes() as $attribute => $value)
                    {{ $attribute }}="{{ $value }}"
                @endforeach
            >
            <div class="slider"></div>
        </div>
    </label>
</div>
