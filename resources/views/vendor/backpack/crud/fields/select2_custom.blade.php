<!-- select2 -->
@php
    $current_value = old($field['name']) ?? $field['value'] ?? $field['default'] ?? '';
    $options = call_user_func($field['options'], $crud->entry);
    $old_options = $crud->entry->{$field['entry']} ?? [];
@endphp

<div @include('crud::inc.field_wrapper_attributes') >

    <label>{!! $field['label'] !!}</label>
    @include('crud::inc.field_translatable_icon')

    <select
            name="{{ isset($field['multiple']) && $field['multiple'] ? $field['name'] . "[]" : $field['name'] }}"
            style="width: 100%"
            @include('crud::inc.field_attributes', ['default_class' =>  'form-control select2_field'])
            {{ isset($field['multiple']) && $field['multiple']  ? 'multiple' : '' }}
    >

        @if (isset($field['null_value']))
            <option value="">-</option>
        @endif
        @if (count($options))
            @foreach ($options as $option)
                @if($current_value == $option->getKey() || ($old_options->count() && in_array($option->getKey(), $old_options->pluck($option->getKeyName(), $option->getKeyName())->toArray())))
                    <option value="{{ $option->getKey() }}" selected>{{ $option->{$field['attribute']} }}</option>
                @else
                    <option value="{{ $option->getKey() }}">{{ $option->{$field['attribute']} }}</option>
                @endif
            @endforeach

            {{--@foreach ($old_options as $option)--}}
                {{--<option value="{{ $option->getKey() }}" selected>{{ $option->{$field['attribute']} }}</option>--}}
            {{--@endforeach--}}
        @else

        @endif
    </select>

    {{-- HINT --}}
    @if (isset($field['hint']))
        <p class="help-block">{!! $field['hint'] !!}</p>
    @endif
</div>

{{-- ########################################## --}}
{{-- Extra CSS and JS for this particular field --}}
{{-- If a field type is shown multiple times on a form, the CSS and JS will only be loaded once --}}
@if ($crud->checkIfFieldIsFirstOfItsType($field))

    {{-- FIELD CSS - will be loaded in the after_styles section --}}
    @push('crud_fields_styles')
        <!-- include select2 css-->
        <link href="{{ asset('vendor/adminlte/bower_components/select2/dist/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />
    @endpush

    {{-- FIELD JS - will be loaded in the after_scripts section --}}
    @push('crud_fields_scripts')
        <!-- include select2 js-->
        <script src="{{ asset('vendor/adminlte/bower_components/select2/dist/js/select2.min.js') }}"></script>
        <script>
            jQuery(document).ready(function($) {
                // trigger select2 for each untriggered select2 box
                $('.select2_field').each(function (i, obj) {
                    if (!$(obj).hasClass("select2-hidden-accessible"))
                    {
                        $(obj).select2({
                            theme: "bootstrap",
                            multiple: "{{ isset($field['multiple']) ? $field['multiple'] : false }}",
                            placeholder: "{{ isset($field['placeholder']) ? $field['placeholder'] : '' }}",
                        });
                    }
                });
            });
        </script>
    @endpush

@endif
{{-- End of Extra CSS and JS --}}
{{-- ########################################## --}}
