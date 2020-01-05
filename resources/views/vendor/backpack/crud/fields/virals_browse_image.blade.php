@php
    $multiple = array_get($field, 'multiple', true);
    $value = old(square_brackets_to_dots($field['name'])) ?? $field['value'] ?? $field['default'] ?? [];

    if (!$multiple && is_array($value)) {
        $value = array_first($value);
    }

    if ($value instanceof Illuminate\Database\Eloquent\Collection) {
        $value = $value->pluck('url')->toArray();
    }
    $old_images = $field['old_images'] ?? [];
@endphp

<div @include('crud::inc.field_wrapper_attributes') >

    <div><label>{!! $field['label'] !!}</label></div>
    <div class="panel panel-primary">
        <div class="panel-heading">Images</div>
        <div class="panel-body" id="image_box_{{ $field['name'] }}">
            @if(count((array)$value))
            @else
                No images
            @endif
        </div>
    </div>
    <div class="panel panel-default" style="padding: 5px">
        @include('crud::inc.field_translatable_icon')
        {{--@if ($multiple)--}}
            {{--@foreach((array)$value as $v)--}}
                {{--@if ($v)--}}
                    {{--<div class="input-group input-group-sm">--}}
                        {{--<input type="text" name="{{ $field['name'] }}[]" value="{{ $v }}" @include('crud::inc.field_attributes') readonly>--}}
                        {{--<div class="input-group-btn">--}}
                            {{--<button type="button" class="browse_{{ $field['name'] }} remove btn btn-default">--}}
                                {{--<i class="fa fa-trash"></i>--}}
                            {{--</button>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--@endif--}}
            {{--@endforeach--}}
        {{--@else--}}
            {{--<input type="text" name="{{ $field['name'] }}" value="{{ $value }}" @include('crud::inc.field_attributes') readonly>--}}
        {{--@endif--}}

        <div class="btn-group browse_multiple_box_{{ $field['name'] }}" role="group" aria-label="..." style="margin-top: 3px; width: 100%;">
            <button type="button" class="browse_{{ $field['name'] }} popup btn btn-default">
                <i class="fa fa-cloud-upload"></i>
                {{ trans('backpack::crud.browse_uploads') }}
            </button>
            <button type="button" class="browse_{{ $field['name'] }} clear btn btn-default">
                <i class="fa fa-eraser"></i>
                {{ trans('backpack::crud.clear') }}
            </button>
        </div>
    </div>
    @if (isset($field['hint']))
        <p class="help-block">{!! $field['hint'] !!}</p>
    @endif

</div>

<script type="text/html" id="browse_multiple_template_{{ $field['name'] }}">
    <div class="input-group input-group-sm browse_multiple_element_{{ $field['name'] }}" remove_item="">
        <input type="text" name="{{ $field['name'] }}[]" @include('crud::inc.field_attributes') readonly>
        <div class="input-group-btn">
            <button type="button" class="browse_{{ $field['name'] }} remove btn btn-default">
                <i class="fa fa-trash"></i>
            </button>
        </div>
    </div>
</script>


<script type="text/html" id="image_box_multiple_template_{{ $field['name'] }}">
    <div class="input-group input-group-sm image_multiple_element_{{ $field['name'] }}" style="width: max-content; min-width: 100px" remove_item="">
        <img src="" width="200">
        <div class="input-group-btn image-btn-remove">
            <button type="button" class="browse_{{ $field['name'] }} remove btn btn-default">
                <i class="fa fa-trash"></i>
            </button>
        </div>
    </div>
</script>

{{-- ########################################## --}}
{{-- Extra CSS and JS for this particular field --}}
{{-- If a field type is shown multiple times on a form, the CSS and JS will only be loaded once --}}
@if ($crud->checkIfFieldIsFirstOfItsType($field))
    {{-- FIELD CSS - will be loaded in the after_styles section --}}
    @push('crud_fields_styles')
        <!-- include browse server css -->
        <link rel="stylesheet" type="text/css"
              href="//cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
        <link rel="stylesheet" type="text/css" href="{{ asset('packages/barryvdh/elfinder/css/elfinder.min.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('packages/barryvdh/elfinder/css/theme.css') }}">
        <link href="{{ asset('vendor/backpack/colorbox/example2/colorbox.css') }}" rel="stylesheet" type="text/css"/>
        <style>
            #cboxContent, #cboxLoadedContent, .cboxIframe {
                background: transparent;
            }
            .image-btn-remove {
                position: absolute;
                top: 0;
                right: 27px;
            }

        </style>
    @endpush

    @push('crud_fields_scripts')
        <!-- include browse server js -->
        <script src="//cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
        <script src="{{ asset('vendor/backpack/colorbox/jquery.colorbox-min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('packages/barryvdh/elfinder/js/elfinder.min.js') }}"></script>
        {{-- <script type="text/javascript" src="{{ asset('packages/barryvdh/elfinder/js/extras/editors.default.min.js') }}"></script> --}}
        @if (($locale = \App::getLocale()) != 'en')
            <script type="text/javascript" src="{{ asset("packages/barryvdh/elfinder/js/i18n/elfinder.{$locale}.js") }}"></script>
        @endif
    @endpush
@endif

{{-- FIELD JS - will be loaded in the after_scripts section --}}
@push('crud_fields_scripts')
    <script>
        $(function () {
            var template = document.getElementById('browse_multiple_template_{{ $field['name'] }}').innerHTML;
            var imgTemplate = document.getElementById("image_box_multiple_template_{{ $field['name'] }}").innerHTML;
            var oldValue = {!! json_encode((array)$value) !!};

            var setItemImages = function(path, browse_multiple_box_element) {
                var remove_item = Math.random().toString(36).substring(10);
                var ele = browse_multiple_box_element  == null ? $(".popup.browse_{{ $field['name'] }}") : browse_multiple_box_element
                path = path.includes('storage/') ? path : 'storage/' + path
                var input = $(template);
                input.find('input').val(path);
                input.find("button.remove.browse_{{ $field['name'] }}").attr('remove_item', remove_item);
                input.attr('remove_item', remove_item)
                ele.before(input);

                var image = $(imgTemplate)
                var src = window.location.protocol + "//" + window.location.hostname + (window.location.port ? ':' + window.location.port: '') + '/' + path;
                console.log(src)
                image.find('img').attr('src', src)
                image.find("button.remove.browse_{{ $field['name'] }}").attr('remove_item', remove_item);
                image.attr('remove_item', remove_item)
                $("#image_box_{{ $field['name'] }}").before(image)
            }

            for (i = 0; i < oldValue.length; i++) {
                console.log(encodeURI(oldValue[i]))
                setItemImages(oldValue[i])
            }

            $(document).on('click', '.popup.browse_{{ $field['name'] }}', function (event) {
                event.preventDefault();

                var element = $(this);

                var div = $('<div>');
                var elfinder = div.elfinder({
                    lang: '{{ \App::getLocale() }}',
                    customData: {
                        _token: '{{ csrf_token() }}'
                    },
                    url: '{{ route("elfinder.connector") }}',
                    soundPath: '{{ asset('/packages/barryvdh/elfinder/sounds') }}',
                    dialog: {
                        width: 900,
                        modal: true,
                        @if ($multiple)
                        title: '{{ trans('backpack::crud.select_files') }}',
                        @else
                        title: '{{ trans('backpack::crud.select_file') }}',
                        @endif
                    },
                    resizable: false,
                    @if ($mimes = array_get($field, 'mime_types'))
                    onlyMimes: {!! json_encode($mimes) !!},
                    @endif
                    commandsOptions: {
                        getfile: {
                            @if ($multiple)
                            multiple: true,
                            @endif
                            oncomplete: 'destroy'
                        }
                    },
                    getFileCallback: function (files) {
                        @if ($multiple)
                        files.forEach(function (file) {
                            setItemImages(file.path, element)
                        });
                        @else
                        $('input[name=\'{{ $field['name'] }}\']').val(files.path);

                        var image = $(imgTemplate)
                        var src = window.location.protocol + "//" + window.location.hostname + (window.location.port ? ':' + window.location.port: '') + '/' + files.path;
                        image.find('img').attr('src', src)
                        image.find('button').parent().remove()
                        $("#image_box_{{ $field['name'] }}").after(image)
                        @endif
                        $.colorbox.close();
                    },
                    ui : ['toolbar', '', '', 'path', 'stat'],
                    uiOptions : {
                        // toolbar configuration
                        toolbar : [
                            [],
                            // ['reload'],
                            // ['home', 'up'],
                            ['upload'],
                            ['open', 'download'],
                            ['info'],
                            ['quicklook'],
                            [],
                            [],
                            ['resize'],
                            [],
                            ['search'],
                            ['view'],
                            []
                        ],
                    },
                    contextmenu : {
                        // navbarfolder menu
                        navbar : ['open', '|', '', '', '', '', '|', '', '|', 'info'],

                        // current directory menu
                        cwd    : ['reload', 'back', '|', 'upload', '', '', '', '|', 'info'],

                        // current directory file menu
                        files  : [
                            '', '|','open', 'quicklook', '|', 'download', '|', '', '', '', '', '|',
                            '', '|', '', '', 'resize', '|', '', '', '|', 'info'
                        ]
                    },
                }).elfinder('instance');

                // trigger the reveal modal with elfinder inside
                $.colorbox({
                    href: div,
                    inline: true,
                    width: '80%',
                    height: '100%'
                });

                elfinder.bind('sync', function(event) {
                    console.log(event)
                });

            });

            $(document).on('click', '.clear.browse_{{ $field['name'] }}', function (event) {
                event.preventDefault();
                @if ($multiple)
                $(".browse_multiple_element_{{ $field['name'] }}").remove();
                $(".image_multiple_element_{{ $field['name'] }}").remove()
                @else
                $('input[name=\'{{ $field['name'] }}\']').val('');
                $(".image_multiple_element_{{ $field['name'] }}").remove()
                @endif
            });

            @if ($multiple)
            $(document).on('click', '.remove.browse_{{ $field['name'] }}', function (event) {
                event.preventDefault();
                var remove_item = $(this).attr('remove_item')
                $("div[remove_item='" + remove_item + "']").remove();
            });
            @endif

            @if (count($old_images))
            @foreach ($old_images as $imagePath)
            setItemImages("{{ $imagePath }}", null)
            @endforeach
            @endif
        });
    </script>
@endpush

{{-- End of Extra CSS and JS --}}
{{-- ########################################## --}}
