<div>
    <label for="{{ $field->getId() }}">{{ $field->getLabel() }}</label>

    <input
        id="{{ $field->getId() }}"
        name="{{ $field->getName() }}"
        value="{{ old($field->getName(), $field->getValue()) }}"
        type="datetime-local"
        @foreach ($field->getAttributes() as $attribute => $value)
            {{ $attribute }}="{{ $value }}"
        @endforeach
    >
</div>
