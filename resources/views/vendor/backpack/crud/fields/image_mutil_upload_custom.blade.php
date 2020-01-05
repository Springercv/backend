<div @include('crud::inc.field_wrapper_attributes')>
    <div>
        <label>{!! $field['label'] !!}</label>
    </div>
    <div class="upload_images_panel col-md-6 images_choice" style="">
        <div class="images_choice_content">
            <input type="file" name="image_uploads[]" class="form-control" id="upload_images_preview" multiple>
            <div id="upload_images_preview_panel" class="row"></div>
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
        {{-----------------UPLOAD IMAGES-------------------}}
        <style>
            .image_preview_box {
                padding: 3px;
                margin: 5px;
            }
        </style>
    @endpush

    @push('crud_fields_scripts')
        {{-----------------UPLOADS IMAGES-------------------}}
        <script src="{{ asset('vendor/backpack/cropper/dist/cropper.min.js') }}"></script>
        <script>
            jQuery(document).ready(function($) {
                // Multiple images preview in browser
                var totalSize = 0;
                var imagesPreview = function(input, placeToInsertImagePreview) {
                    if (input.files) {
                        var filesAmount = input.files.length;
                        for (i = 0; i < filesAmount; i++) {
                            var reader = new FileReader();
                            reader.onload = function(event) {
                                var imagePreviewHtml = "<div class='col-md-3'><div class='image_preview_box'><img src=" + event.target.result +" style='max-height: 100%; max-width: 100%; border-radius: 10px;'/></div></div>"
                                $($.parseHTML(imagePreviewHtml)).appendTo(placeToInsertImagePreview);
                            }
                            totalSize += input.files[i].size;
                            reader.readAsDataURL(input.files[i]);
                        }
                    }
                };

                $('#upload_images_preview').on('change', function() {
                    $('#upload_images_preview_panel').empty();
                    imagesPreview(this, '#upload_images_preview_panel');
                    console.log(totalSize)
                    const totalSizeTemp = totalSize
                    totalSize = 0
                    if (totalSizeTemp/1024/1024 >= 10) {
                        alert("Total files upload must be less than 10MB");
                        $(this).closest('form').find("button[type='submit']").prop('disabled', true)
                    } else {
                        $(this).closest('form').find("button[type='submit']").prop('disabled', false)
                    }
                });
            });
        </script>
    @endpush
@endif