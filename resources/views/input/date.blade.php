<div>
    <label for="{{ $field->getId() }}">{{ $field->getLabel() }}</label>

    <input
        id="{{ $field->getId() }}"
        name="{{ $field->getName() }}"
        value="{{ old($field->getName(), $field->getValue()) }}"
        type="date"
        @foreach ($field->getAttributes() as $attribute => $value)
            {{ $attribute }}="{{ $value }}"
        @endforeach
    >
</div>
