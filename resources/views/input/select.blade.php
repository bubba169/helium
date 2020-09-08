<div class="form-group">
    @include('helium::input.common.label')
    <select @include('helium::input.common.attributes')>
        @if (empty($field->getAttribute('required')))
            <option value="">{{ $field->getPlaceholder() }}</option>
        @endif

        @foreach ($field->getOptions() as $value => $name)
            <option
                value="{{ $value }}"
                {{ $field->getValue() == $value ? 'selected' : '' }}
            >
                {{ $name }}
            </option>
        @endforeach
    </select>
</div>
