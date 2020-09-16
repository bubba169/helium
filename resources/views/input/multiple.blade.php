<div class="form-group">
    <input name="{{ $field->getFieldName() }}" value="" type="hidden"/>

    @include('helium::input.common.label')

    <select @include('helium::input.common.attributes') multiple>
        @if (empty($field->getAttribute('required')))
            <option value="">{{ $field->getPlaceholder() }}</option>
        @endif

        @php($fieldValue = optional($field->getValue())->toArray() ?? [])

        @foreach ($field->getOptions() as $value => $name)
            <option
                value="{{ $value }}"
                {{ in_array($value, $fieldValue) ? 'selected' : '' }}
            >
                {{ $name }}
            </option>
        @endforeach
    </select>

    <div class="invalid-feedback">
        This field is required
    </div>
</div>
