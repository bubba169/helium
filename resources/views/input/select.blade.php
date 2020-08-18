<div class="form-group">
    @if ($field->getAttribute('type') !== 'hidden')
        <label for="{{ $field->getId() }}" class="control-label">{{ $field->getLabel() }}</label>
    @endif

    <select
        id="{{ $field->getId() }}"
        name="{{ $field->getName() }}"

        class="{{ $field->getClasses()->join(' ') }}"

        @foreach ($field->getAttributes() as $attribute => $value)
            {{ $attribute }}="{{ $value }}"
        @endforeach
    >
        @foreach ($field->getOptions() as $value => $name)
            <option
                value="{{ $value }}"
                {{ old($field->getName(), $field->getValue()) ? 'selected' : '' }}
            >
                {{ $name }}
            </option>
        @endforeach
    </select>
</div>
