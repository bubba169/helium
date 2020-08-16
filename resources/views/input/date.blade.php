<div class="form-group">
    <label for="{{ $field->getId() }}">{{ $field->getLabel() }}</label>

    <input
        id="{{ $field->getId() }}"
        name="{{ $field->getName() }}"
        value="{{ old($field->getName(), $field->getValue()) }}"

        class="{{ $field->getClasses()->join(' ') }}"

        data-config="{{ json_encode($field->getConfigAttribute('flatpickr')) }}"

        @foreach ($field->getAttributes() as $attribute => $value)
            {{ $attribute }}="{{ $value }}"
        @endforeach
    >
</div>
