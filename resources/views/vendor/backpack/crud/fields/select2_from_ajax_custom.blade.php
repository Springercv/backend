<!-- select2 from ajax multiple -->
@php
    if (!isset($field['options'])) {
        $options = $field['model']::all();
    } else {
        $options = @call_user_func($field['options'], $field['model']::query());
    }
    if (isset($field['value'])) {
        $value = @call_user_func($field['value'], $crud->entry);
    }
@endphp

<div @include('crud::inc.field_wrapper_attributes') >
    <label>{!! $field['label'] !!}</label>
    @include('crud::inc.field_translatable_icon')
    <select
            name="{{ $field['name_input'] }}[]"
            style="width: 100%"
            id="{{ isset($field['attributes']['id']) ? $field['attributes']['id'] : 'select2_ajax_custom_' . $field['name'] }}"
            @include('crud::inc.field_attributes', ['default_class' =>  'form-control']) {{ isset($field['multiple']) && $field['multiple'] ? 'multiple' : '' }}>

        @if (isset($field['model']))
            @foreach ($options as $option)
                @if( (old(square_brackets_to_dots($field["name"])) && in_array($option->getKey(), old($field["name"]))) || (is_null(old(square_brackets_to_dots($field["name"]))) && isset($value) && in_array($option->getKey(), $value->pluck($option->getKeyName(), $option->getKeyName())->toArray())))
                    <option value="{{ $option->getKey() }}" selected>{{ $option->{$field['attribute']} }}</option>
                @else
                    <option value="{{ $option->getKey() }}">{{ $option->{$field['attribute']} }}</option>
                @endif
            @endforeach
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
    @endpush

@endif

<!-- include field specific select2 js-->
@push('crud_fields_scripts')
    <script>
        jQuery(document).ready(function($) {
            // trigger select2 for each untriggered select2 box
            $("#{{ isset($field['attributes']['id']) ? $field['attributes']['id'] : 'select2_ajax_custom_' . $field['name'] }}").each(function (i, obj) {
                var form = $(obj).closest('form');
                if (!$(obj).hasClass("select2-hidden-accessible"))
                {
                    $(obj).select2({
                        theme: 'bootstrap',
                        multiple: {{ isset($field['multiple']) && $field['multiple'] ? "true" : "false" }},
                        placeholder: "{{ $field['placeholder'] }}",
                    });
                }
                $(obj).on('change', function (e) {
                    e.preventDefault()
                    $.ajax({
                        url: "{{ $field['data_source'] }}",
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            {{ $field['name_input'] }} : $(obj).val()
                        }
                }).done(function(options) {
                        var selectChange =  $("#{{ $field['select2_change_id'] }}")
                        if (jQuery.isEmptyObject(options)) {
                            selectChange.empty()
                        } else {
                            var currentSelectedChangeOptionIds_{{ $field['name'] }} = selectChange.find('option:selected').map( function() {
                                return $(this).attr('value')
                            }).get()
                            selectChange.find('option').each( function() {
                                if (!($(this).attr('value') in options)) {
                                    selectChange.find("option[value='" + $(this).attr('value') + "']").remove()
                                }
                            }).get()
                            for (var key in options) {
                                if(!selectChange.find("option[value='" + key + "']").length){
                                    var checked = currentSelectedChangeOptionIds_{{ $field['name'] }}.includes(key) ? 'checked' : ''
                                    selectChange.append("<option value=\"" + key + "\" " + checked + ">" + options[key] + "</option>")
                                }
                            }
                        }
                    });
                })
                @if(!$crud->entry)
                    $(obj).trigger({
                        type: 'change'
                    });
                @endif
            });
        });
    </script>
@endpush
{{-- End of Extra CSS and JS --}}
{{-- ########################################## --}}
