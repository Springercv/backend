<!-- array input -->

<?php
$max = isset($field['max']) && (int) $field['max'] > 0 ? $field['max'] : -1;
$min = isset($field['min']) && (int) $field['min'] > 0 ? $field['min'] : -1;
$item_name = strtolower(isset($field['entity_singular']) && !empty($field['entity_singular']) ? $field['entity_singular'] : $field['label']);

$items = old(square_brackets_to_dots($field['name'])) ?? $field['value'] ?? $field['default'] ?? [];
if (is_array($items)) {
    if (count($items)) {
        foreach ($items as $index => $item) {
            $items[$index] = (object) $item;
        }
        $items = collect($items);
    } else {
        $items = collect([]);
    }
}
$stt = 0;
?>
<div @include('crud::inc.field_wrapper_attributes') >

    <label>{!! $field['label'] !!}</label>
    @include('crud::inc.field_translatable_icon')

    <input class="array-json" type="hidden" id="{{ $field['name'] }}" name="{{ $field['name'] }}">

    <div class="array-container form-group">

        <table class="table table-bordered table-striped m-b-0">

            <thead>
            <tr>

                @foreach( $field['columns'] as $data )
                    <th style="font-weight: 600!important;">
                        {{ $data['label'] }}
                    </th>
                @endforeach
                <th class="text-center" ng-if="max == -1 || max > 1"> {{-- <i class="fa fa-trash"></i> --}} </th>
            </tr>
            </thead>

            <tbody class="table-striped" id="table_{{ $field['name'] }}">
            @if (count($items))
                @foreach( $items as $item)
                    @php
                        $idItem = Illuminate\Support\Str::random();
                    @endphp
                    <tr id="tr_{{ $idItem }}" class="tr_item">
                        <input type="hidden" name="{{ $field['name'] . "[" . $stt . "]" ."[id]" }}" value="{{ $item->id }}">
                        @foreach( $field['columns'] as $prop => $data)
                            <td>
                                @if (count($data['options']))
                                    <select
                                            name="{{ isset($field['multiple']) && $field['multiple'] ? $field['name'] . "[" . $stt . "]" . "[" . $prop . "][]" : $field['name'] . "[" . $stt . "]" ."[" . $prop . "]"}}"
                                            style="width: 100%"
                                            class = 'form-control select2_field_custom'
                                            {{ isset($field['multiple']) && $field['multiple']  ? 'multiple' : '' }}
                                    >
                                        @foreach ($data['options'] as $key => $value)
                                            <option value="{{ $key }}" {{ $item->$prop == $key ? 'selected' : ''}}>{{ $value }}</option>
                                        @endforeach
                                    </select>
                                @else
                                    <input class="form-control input-sm" name="{{ $field['name'] . "[" . $stt . "]" ."[" . $prop . "]"}}" type="{{ isset($data['type']) ? $data['type'] : 'text' }}" value="{{ $item->$prop }}">
                                @endif
                            </td>
                        @endforeach
                        <td>
                            <button id="btn_{{ $idItem }}" class="btn btn-sm btn-default btn_delete_item" type="button"><span class="sr-only">delete item</span><i class="fa fa-trash" role="presentation" aria-hidden="true"></i></button>
                        </td>
                    </tr>
                    @php
                        $stt++;
                    @endphp
                @endforeach
            @endif
            </tbody>

        </table>

        <div class="array-controls btn-group m-t-10">
            <button class="btn btn-sm btn-default" type="button" id="add_row_{{ $field['name'] }}"><i class="fa fa-plus"></i> {{trans('backpack::crud.add')}} {{ $item_name }}</button>
        </div>

    </div>

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
        {{-- YOUR JS HERE --}}
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
        <script src="{{ asset('vendor/adminlte/bower_components/select2/dist/js/select2.min.js') }}"></script>
        <script>
            $(function(){
                var mutil = parseInt("{{ isset($field['multiple']) && $field['multiple'] ? 1 : 0 }}") == 1 ? "[]" : ""
                var triggerSelect2 = function () {
                    // trigger select2 for each untriggered select2 box
                    $('.select2_field_custom').each(function (i, obj) {
                        if (!$(obj).hasClass("select2-hidden-accessible"))
                        {
                            $(obj).select2({
                                theme: "bootstrap",
                                multiple: "{{ isset($field['multiple']) && $field['multiple'] ? $field['multiple'] : '' }}",
                                placeholder: "{{ isset($field['placeholder']) && $field['placeholder'] ? $field['placeholder'] : '' }}",
                            });
                        }
                    });
                }

                var count = parseInt({{$items->count()}})
                var idAdd = 0
                $("#add_row_{{ $field['name'] }}").on('click', function(e) {
                    count++;
                    idAdd--;
                    {{--$("#table_{{ $field['name'] }}").append($(templateRow))--}}
                    let idItem = Math.random().toString(36).substring(7) + count;
                    var template = `<tr id="tr_${idItem}" class="tr_item">
                        <input type="hidden" name="{{ $field['name'] }}[${count}][id]" value="${idAdd}">
                        @foreach( $field['columns'] as $prop => $data)
                            <td>
                                @if (count($data['options']))
                                    <select
                                            name="{{ $field['name'] }}[${count}][{{ $prop }}]${mutil}"
                                            style="width: 100%"
                                            class = 'form-control select2_field_custom'
                                            {{ isset($field['multiple']) && $field['multiple']  ? 'multiple' : '' }}
                                    >
                                    @foreach ($data['options'] as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                    </select>
                                @else
                                    <input class="form-control input-sm" name="{{ $field['name'] }}[${count}][{{ $prop }}]" type="{{ isset($data['type']) ? $data['type'] : 'text' }}" value="">
                                @endif
                            </td>
                        @endforeach
                            <td>
                                <button id="btn_${idItem}" class="btn btn-sm btn-default btn_delete_item" type="button"><span class="sr-only">delete item</span><i class="fa fa-trash" role="presentation" aria-hidden="true"></i></button>
                            </td>
                        </tr>`
                    $("#table_{{ $field['name'] }}").append(template);
                    triggerSelect2()
                });
                triggerSelect2()

                $(document).on('click', '.btn_delete_item', function () {
                    const itemId = $(this).attr('id').replace('btn_', 'tr_')
                    $(`#${itemId}`).remove()
                })
            })
        </script>
    @endpush
@endif
{{-- End of Extra CSS and JS --}}
{{-- ########################################## --}}
