@extends('mgr.layouts.master')
@section('title') {{$title}} @endsection
@section('css')
<link href="{{ URL::asset('assets/libs/quill/quill.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('css/cropper.min.css') }}" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="dist/cropper.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.1.20/jquery.fancybox.min.css" />
<style>
.menu{
    cursor: pointer;
}
.del-btn {
    position: absolute;
    top: 2px;
    left: 14px;
    margin: 3%;
    height: 25px;
    width: 25px;
    line-height: 25px;
    padding: 0 !important;
}

.pics.row {
    display: -webkit-box;
    display: -webkit-flex;
    display: -ms-flexbox;
    display: flex;
    flex-wrap: wrap;
}

.pics.row>[class*='col-'] {
    display: flex;
    flex-direction: column;
}
</style>
@endsection
@section('content')
@component('mgr.components.breadcrumb', ['btns' => $btns??array()])
@slot('li_1_url') {{$parent_url}} @endslot
@slot('li_1') {{$parent}} @endslot
@slot('title') {{$title}} @endslot
@endcomponent
<div class="row">
    <div class="col-12">
        <div class="card">
            <form action="" method="POST" enctype="multipart/form-data" id="form">
                @csrf
                <div class="card-body row">
                    @foreach ($params as $param)
                    <div class="col-lg-{{$param['layout_l']}} col-sm-{{$param['layout_s']}}">
                        <div class="mb-3">
                            @if ($param['type'] == 'block')

                            @endif

                            @if ($param['type'] == 'header')
                            <div class="border mt-3 border-dashed" style="margin-bottom: 15px;"></div>
                            <h4>{{$param['title']}}</h4>
                            @elseif ($param['title'] != '')
                            <label class="form-label" for="{{$param['field']}}">
                                {{$param['title']}}
                                @if ($param['required'])
                                <span class="badge rounded-pill bg-danger"></span>
                                @endif
                            </label>

                                @if ($param['type'] == 'day')
                                    <button type="button" class="btn btn-secondary btn-sm waves-effect float-end btn-date-forever">設為永久</button>
                                @endif
                            @endif

                            @if ($param['type'] == 'plain')
                            <div style="{{ $param['option']??'' }}" class="{{ $param['class'] }}">
                                {{ $param['hint'] }}
                            </div>
                            @endif

                            @if ($param['type'] == 'text')
                            <input type="text" class="form-control {{ $param['class'] }}" id="{{$param['field']}}"
                                name="{{ $param['field'] }}" placeholder="{{ $param['hint'] }}"
                                value="{{$param['value']??''}}">
                            @endif

                            @if ($param['type'] == 'password')
                            <input type="password" class="form-control {{ $param['class'] }}" id="{{$param['field']}}"
                                name="{{ $param['field'] }}" placeholder="{{ $param['hint'] }}"
                                value="{{$param['value']??''}}" autocomplete="off">
                            @endif

                            @if ($param['type'] == 'number')
                            <input type="number" class="form-control {{ $param['class'] }}" id="{{$param['field']}}"
                                name="{{ $param['field'] }}" placeholder="{{ $param['hint'] }}"
                                value="{{$param['value']??''}}">
                            @endif

                            @if ($param['type'] == 'day')
                            <input type="text" class="form-control {{ $param['class'] }}" id="{{$param['field']}}"
                                name="{{ $param['field'] }}" data-provider="flatpickr" data-date-format="Y-m-d"
                                placeholder="{{ $param['hint'] }}" value="{{$param['value']??''}}">
                                
                            @endif

                            @if ($param['type'] == 'select')
                            <select class="form-control {{ $param['class'] }}" name="{{ $param['field'] }}">
                                @foreach ($select[$param['field']] as $item):
                                <option value="{{ $item[$param['option'][0]] }}"
                                    @if($param['value']==$item[$param['option'][0]]) selected @endif>
                                    {{ $item[$param['option'][1]] }}</option>
                                @endforeach
                            </select>
                            @endif

                            @if ($param['type'] == 'select_multi')
                            <select class="form-control {{ $param['class'] }}" name="{{ $param['field'] }}[]"
                                data-choices data-choices-removeItem multiple>
                                @foreach ($select[$param['field']] as $item):
                                <option value="{{ $item[$param['option'][0]] }}"
                                    @if(in_array($item[$param['option'][0]], $param['value'])) selected @endif>
                                    {{ $item[$param['option'][1]] }}</option>
                                @endforeach
                            </select>
                            @endif

                            @if ($param['type'] == 'textarea')
                            <textarea class="form-control {{ $param['class'] }}" name="{{ $param['field'] }}"
                                style="height:{{$param['option']['height']??'100'}}px;">{{$param['value']}}</textarea>
                            @endif

                            @if ($param['type'] == 'editor')
                            <!--  ckeditor-classic -->
                            <!-- <div class="snow-editor {{ $param['class'] }}" name="{{ $param['field'] }}"
                                style="height:{{$param['option']['height']??'200'}}px;">{!!$param['value']!!}</div>
                            <input type="hidden" name="{{ $param['field'] }}" id="{{ $param['field'] }}"> -->
                            <textarea class="ckeditor {{ $param['class'] }}" name="{{ $param['field'] }}"
                                style="height:{{$param['option']['height']??'200'}}px;">{!!$param['value']!!}</textarea>
                            @endif

                            @if ($param['type'] == 'image')
                            <!-- <input name="{{$param['field']}}" type="file" class="form-control"> -->
                            <input data-multi="false" data-ratio="{{ $param['option']['ratio'] }}"
                                data-crop="{{ $param['option']['crop'] }}"
                                data-related="{{ $param['field'] }}" class="img_upload" type="file"
                                id="imgupload_{{ $param['field'] }}" style="display: none;" accept="image/*">

                            <button type="button" class="btn btn-sm btn-info"
                                onclick="imgupload_{{ $param['field'] }}.click();">選擇照片</button>
                            <button type="button" class="btn btn-sm btn-danger" id="delphoto_{{ $param['field'] }}"
                                onclick="delete_photo('{{ $param['field'] }}');" @if($param['value']=='' )
                                style="display:none;" @endif>刪除照片</button>

                            <input type="hidden" name="{{ $param['field'] }}" id="{{ $param['field'] }}"
                                value="{{ $param['value'] }}">
                            <div id="img_{{ $param['field'] }}"
                                style="width: 256px; margin-top: 6px; background-color: #FFF; border:1px solid #DDD; padding: 2px; border-radius: 2px; @if($param['value'] == '') display:none; @endif">
                                <img class="img-thumbnail"
                                    src="@if($param['value'] != '') {{ env('APP_URL').Storage::url($param['value']) }} @else  @endif"
                                    style="width: 250px;">
                            </div>
                            @endif

                            @if ($param['type'] == 'img_multi')
                            <input data-multi="true" data-ratio="{{ $param['option']['ratio'] }}"
                                data-related="{{ $param['field'] }}" class="img_upload" type="file"
                                id="imgupload_{{ $param['field'] }}" style="display: none;" accept="image/*">

                            <button type="button" class="btn btn-sm btn-info"
                                onclick="imgupload_{{ $param['field'] }}.click();">選擇照片</button>
                            <!-- pic1.jpg;pic2.jpg; -->
                            <input type="hidden" name="{{ $param['field'] }}" id="{{ $param['field'] }}"
                                value="{{ $param['value'] }}">
                            <input type="hidden" name="picdeleted_{{ $param['field'] }}"
                                id="picdeleted_{{ $param['field'] }}" value="">
                            <div class="row pics" id="pics_{{ $param['field'] }}">
                                @if (isset($html[$param['field']]))
                                {!! $html[$param['field']] !!}
                                @endif
                            </div>
                            @endif

                            @if ($param['type'] == 'file')
                            <input data-multi="true" data-related="{{ $param['field'] }}" class="file_upload"
                                type="file" id="fileupload_{{ $param['field'] }}" style="display: none;" accept=""
                                multiple>

                            <button type="button" class="btn btn-sm btn-info"
                                onclick="fileupload_{{ $param['field'] }}.click();">選擇檔案</button>
                            <!-- pic1.jpg;pic2.jpg; -->
                            <input type="hidden" name="{{ $param['field'] }}" id="{{ $param['field'] }}"
                                value="{{ $param['value'] }}">
                            <input type="hidden" name="filesdeleted_{{ $param['field'] }}"
                                id="filesdeleted_{{ $param['field'] }}" value="">
                            <div class="row files" id="files_{{ $param['field'] }}">
                                @if (isset($html[$param['field']]))
                                {!! $html[$param['field']] !!}
                                @endif
                            </div>
                            @endif


                            @if ($param['type'] == 'privilege')
                            <div class="table-responsive">
                                <table class="table align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th scope="col">
                                                菜單/功能
                                                <button type="button" class="all-select btn btn-sm rounded-pill btn-secondary waves-effect waves-light">全選</button>
                                            </th>
                                            @foreach ($privilege_action as $action)
                                            <th scope="col">{{ $action->title }}</th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($privilege_menu as $menu)
                                        <tr>
                                            <th scope="row" class="menu">
                                                {{ $menu['name'] }}
                                            </th>
                                            @foreach ($privilege_action as $action)
                                            <th scope="row">
                                                <div class="form-check">
                                                    @if (in_array($action->id, $menu['action']))
                                                    <input 
                                                        @if (in_array($action->id, $menu['action_enabled'])) checked @endif
                                                        class="form-check-input" 
                                                        type="checkbox" 
                                                        id="p_{{ $menu['id'] }}_{{ $action->id }}" 
                                                        name="p_{{ $menu['id'] }}_{{ $action->id }}">
                                                    <label class="form-check-label" for="p_{{ $menu['id'] }}_{{ $action->id }}"></label>
                                                    @endif
                                                </div>
                                            </th>
                                            @endforeach
                                        </tr>
                                            @foreach ($menu['sub'] as $smenu)
                                            <tr>
                                                <th scope="row" class="menu text text-secondary">
                                                    ∟{{ $smenu['name'] }}
                                                </th>
                                                @foreach ($privilege_action as $action)
                                                <th scope="row">
                                                    <div class="form-check">
                                                    @if (in_array($action->id, $smenu['action']))
                                                        <input 
                                                            @if (in_array($action->id, $smenu['action_enabled'])) checked @endif
                                                            class="form-check-input" 
                                                            type="checkbox" 
                                                            id="p_{{ $smenu['id'] }}_{{ $action->id }}" 
                                                            name="p_{{ $smenu['id'] }}_{{ $action->id }}">
                                                        <label class="form-check-label" for="p_{{ $smenu['id'] }}_{{ $action->id }}"></label>
                                                    @endif
                                                    </div>
                                                </th>
                                                @endforeach
                                            </tr>
                                            @endforeach
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @endif

                            @if ($param['hint'] != '' && $param['required'])
                            <p class="text-danger">{{$param['hint']}}</p>
                            @endif
                        </div>
                    </div>
                    @endforeach

                    @if (isset($action_btns))

                    @else
                    <!-- <div class="col-lg-12 col-sm-12">
                        <buttton type="button" class="btn d-grid btn-primary submit_btn">{{$submit_txt??'送出'}}</button>
                    </div> -->
                   
                            <div class="col-lg-12 col-sm-12">
                                <button class="btn btn-outline-secondary submit_btn" type="button">搜尋</button>
                                <button class="btn btn-outline-secondary search_clear_action" type="button" style="border-radius: 0 4px 4px 0;">清除</button>
                            </div>
                
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Cover Modal -->
<div class="modal fade" id="coverModal" role="dialog" aria-labelledby="modalLabel" tabindex="-1">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel">圖片</h5>
            </div>
            <div class="modal-body">
                <div class="img-container">
                    <img id="coverImage" src="" alt="cover Image">
                </div>
            </div>
            <div class="modal-footer">
                <button id="coverCancel" type="button" class="btn btn-default pull-left"
                    data-dismiss="modal">取消</button>
                <button id="coverSave" type="button" class="btn btn-primary" data-dismiss="modal">儲存</button>
            </div>
        </div>
    </div>
</div>

<div class="row">
        <div class="col-12">
      
            <div class="row">
                <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div id="customerList">
                            <div class="row g-4 mb-3">
                                
                                <!-- <div class="col-sm">
                                    <div class="d-flex justify-content-sm-end">
                                        <div class="search-box ms-2">
                                            <input type="text" class="form-control search" placeholder="Search...">
                                            <i class="ri-search-line search-icon"></i>
                                        </div>
                                    </div>
                                </div> -->
                            </div>
                            <div class="row">
                                <div class="col-xl-12">
                                    <div class="table-responsive table-card mb-1">
                                        <table class="table align-middle">
                                            <thead class="table-light">
                                                @if(isset($th_title))
                                                <tr>
                                                    @foreach ($th_title as $th)
                                                    <th scope="col" style="width:{{$th['width']}};">{{$th['title']}}</th>
                                                    @endforeach
                                                </tr>
                                                @endif
                                            </thead>
                                            <tbody>
                                            @if(isset($data))
                                                @foreach ($data as $item)
                                                @component($template_item, ['item'=>$item, 'th_title'=>$th_title])
                                                @endcomponent
                                                @endforeach
                                            @endif
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="d-flex justify-content-end">
                                        <div class="pagination-wrap hstack gap-2">
                                            <a class="page-item pagination-prev disabled" href="#">
                                                Previous
                                            </a>
                                            <ul class="pagination listjs-pagination mb-0"></ul>
                                            <a class="page-item pagination-next disabled" href="#">
                                                Next
                                            </a>
                                        </div>
                                    </div>
                                </div><!--end col-->

                            </div><!--end row-->
                        </div>
                    </div><!-- end card-body -->
                </div><!-- end card -->
            </div>      
            </div>

        </div>
    </div>

    <div class="modal fade" id="showModal" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-light p-3">
                    <h5 class="modal-title" id="exampleModalLabel"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        id="close-modal"></button>
                </div>
                <form>
                    <div class="modal-body" id="detail_content">

                    </div>
                    <div class="modal-footer">
                        <div class="hstack gap-2 justify-content-end">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">關閉</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
<!-- Cover Modal -->
@endsection
@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.1.20/jquery.fancybox.min.js"></script>
<script src="{{ URL::asset('assets/libs/quill/quill.min.js') }}"></script>
<script src="https://unpkg.com/quill-html-edit-button@2.2.7/dist/quill.htmlEditButton.min.js"></script>

<script src="{{ URL::asset('js/cropper.min.js') }}"></script>
<!-- <script src="{{ URL::asset('assets/libs/@ckeditor/@ckeditor.min.js') }}"></script> -->
<script src="{{ URL::asset('assets/ckeditor/ckeditor.js') }}"></script>
<!-- <script src="https://cdn.ckeditor.com/ckeditor5/35.0.1/classic/ckeditor.js"></script> -->

<script>
$(document).ready(function(e) {
    {!!$custom_js ?? ''!!}

    $('.menu').on('click', function () {
        $(this).closest('tr').find('input[type=checkbox]').each(function (index, element) {
            $(this).prop('checked', true);
        });
    });
    $('.all-select').on('click', function () {
        if ($(this).text() == '全選'){
            $(document).find('input[type=checkbox]').each(function (index, element) {
                $(this).prop('checked', true);
            });
            $(this).text('全不選')
        }else{
            $(document).find('input[type=checkbox]').each(function (index, element) {
                $(this).prop('checked', false);
            });
            $(this).text('全選')
        }
        
    });
    $(".btn-date-forever").on('click', function () {
        $(this).next('.flatpickr-input').val('2099-12-31');
    });

    //editor

    var snowEditor = document.querySelectorAll(".snow-editor");
    snowEditor.forEach(function(item) {
        var snowEditorData = {};
        var issnowEditorVal = item.classList.contains("snow-editor");

        if (issnowEditorVal == true) {
            snowEditorData.theme = 'snow', snowEditorData.modules = {
                'toolbar': [
                    [
                    // {
                    //     'font': []
                    // }, 
                    {
                        'size': []
                    }],
                    ['bold', 'italic', 'underline', 'strike'],
                    [{
                        'color': []
                    }, {
                        'background': []
                    },
                    {
                        'align': []
                    }],
                    // [{
                    //     'script': 'super'
                    // }, {
                    //     'script': 'sub'
                    // }],
                    // [{
                    //     'header': [false, 1, 2, 3, 4, 5, 6]
                    // }, 'blockquote', 'code-block'],
                    [{
                        'list': 'ordered'
                    }, {
                        'list': 'bullet'
                    }, {
                        'indent': '-1'
                    }, {
                        'indent': '+1'
                    }],
                    ['link', 'image'
                        // , 'video'
                    ],
                    // ['clean']
                ],
                htmlEditButton: {
                    debug: true, // logging, default:false
                    msg: "Edit the content in HTML format", //Custom message to display in the editor, default: Edit HTML here, when you click "OK" the quill editor's contents will be replaced
                    okText: "確認", // Text to display in the OK button, default: Ok,
                    cancelText: "取消", // Text to display in the cancel button, default: Cancel
                    buttonHTML: "&lt;&gt;", // Text to display in the toolbar button, default: <>
                    buttonTitle: "Show HTML source", // Text to display as the tooltip for the toolbar button, default: Show HTML source
                    syntax: false, // Show the HTML with syntax highlighting. Requires highlightjs on window.hljs (similar to Quill itself), default: false
                    prependSelector: 'div#myelement', // a string used to select where you want to insert the overlayContainer, default: null (appends to body),
                    editorModules: {} // The default mod
                }
            };
            // snowEditorData.modules  = {
                
            // }
        }

        Quill.register("modules/htmlEditButton", htmlEditButton);
        new Quill(item, snowEditorData);
    });


    var ckClassicEditor = document.querySelectorAll(".ckeditor-classic")
    ckClassicEditor.forEach(function(ediv) {
        // var ediv = document.querySelector('.ckeditor-classic');
        var height = ediv.getAttribute('data-height');
        if (height == '')
            height = '200px';
        else
            height += 'px';

        ClassicEditor
            .create(ediv, {
                language: 'zh',
                // plugins: [  ],//Font
                // toolbar: {
                //     items: [
                //         'fontfamily', 'fontsize', '|',
                //         'alignment', '|',
                //         'fontFamily', 'fontColor', 'fontBackgroundColor', '|',
                //         'bold', 'italic', 'strikethrough', 'underline', '|',
                //         'link', '|',
                //         'bulletedList', 'numberedList', 'todoList', '|',
                //         // 'code', 'codeBlock', '|',
                //         'insertTable', 'uploadImage', 'video', '|', //'blockQuote', 
                //         'undo', 'redo'
                //     ],
                //     shouldNotGroupWhenFull: false
                // },
                // fontFamily: {
                //     options: [
                //         'default',
                //         '微軟正黑體',
                //         '新細明體'
                //     ]
                // },
                // fontSize: {
                //     options: [
                //         9,
                //         11,
                //         13,
                //         'default',
                //         17,
                //         19,
                //         21,
                //         24,
                //         28,
                //         32,
                //         40,
                //         48,
                //         64
                //     ]
                // },
                // fontColor: {
                //     colors: [
                //         // 9 colors defined here...
                //     ],
                //     columns: 3,
                // },
                // fontBackgroundColor: {
                //     columns: 6,
                // },
            })
            .then(function(editor) {
                editor.ui.view.editable.element.style.height = height;
            })
            .catch(function(error) {});
    });

    //submit form
    $(document).on('click', '.submit_btn', function(e) {
        @if(isset($precheck_url) && $precheck_url != '')
        $.ajax({
            url: "{{ $precheck_url }}@if(isset($id))/{{ $id }}@endif",
            data: $("#form").serialize(),
            type: "POST",
            dataType: 'json',
            success: function(data) {
                if (data.status) {
                    submit_form();
                } else {
                    alert(data.msg);
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {

            }
        });
        @else
        submit_form();
        @endif
    });

    function submit_form() {
        $(document).find('.snow-editor').each(function(index, el) {
            let name = $(this).attr('name');
            $("#" + name).val($(this).find('.ql-editor').html());
        });
        @if(isset($ajax_form) && $ajax_form)
        $.ajax({
            url: $("#form").attr('action'),
            data: $("#form").serialize(),
            type: "POST",
            dataType: 'json',
            success: function(data) {
                if (data.status) {
                    alert(data.msg);
                    location.href = data.redirect_url;
                } else {
                    alert(data.msg);
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert("照片上傳發生錯誤");
            }
        });
        @else
        $("#form").submit();
        @endif
    }

    //Upload files

    $(".file_upload").on('change', function(event) {
        var multiple = $(this).attr("data-multi");
        var related_id = $(this).attr("data-related");
        var formData = new FormData();
        formData.append('multi', multiple);
        formData.append('field', related_id);

        if (multiple == "true") {
            $.each($(this)[0].files, function(i, file) {
                formData.append('files[' + i + ']', file);
            });
        } else {
            formData.append('files', $(this)[0].files[0]);
        }

        $.ajax({
            url: "{{ env('APP_URL') }}/util/file_upload",
            cache: false,
            contentType: false,
            processData: false,
            data: formData,
            type: "POST",
            dataType: 'json',
            success: function(data) {
                if (data.status) {
                    $("#files_" + related_id).append(
                        data.html
                    );
                    $.each(data.data, function(index, item) {
                        $("#" + related_id).val($("#" + related_id).val() + item
                            .path + ";");
                    });
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                // alert("檔案上傳發生錯誤"); 
            }
        });
    });
});

//Image crop
window.addEventListener('DOMContentLoaded', function() {
    /* crop */
    var coverImage = document.getElementById("coverImage");
    var cropper;
    var related_id;
    var ratio = 1;
    var multi;

    $(document).on("change", ".img_upload", function() {
        multi = $(this).attr("data-multi");
        related_id = $(this).attr("data-related");
        ratio = parseFloat($(this).attr("data-ratio"));
        let crop = $(this).data('crop');
        if (ratio <= 0) ratio = 0;

        if (crop === 'without') {
            let formData = new FormData();
            formData.append('multi', multi);
            formData.append('field', related_id);
            formData.append('crop', 'without');
            
            formData.append('image', $(this)[0].files[0]);
            img_upload(formData, related_id, is_crop = false);
            return;
        }

        var input = this;
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                $("#coverImage").attr('src', e.target.result);
                $("#coverImage").width("100%");
            }

            reader.readAsDataURL(input.files[0]);

            $("#coverModal").modal({
                'backdrop': 'static'
            });
            $("#coverModal").modal("show");
        }
        $(this).val("");
    });

    $('#coverModal').on('shown.bs.modal', function() {
        let param = {
            // aspectRatio: ratio,
            autoCropArea: 1,
            movable: false,
            rotatable: false,
            scalable: false,
            zoomable: false,
            zoomOnTouch: false,
            zoomOnWheel: false,
            responsive: true,
        };
        if (ratio > 0) {
            param['aspectRatio'] = ratio;
        }
        cropper = new Cropper(coverImage, param);
    }).on('hidden.bs.modal', function() {
        cropper.destroy();
    });

    $("#coverCancel").on('click', function(event) {
        $("#coverModal").modal("hide");
    });

    $("#coverSave").on('click', function(event) {
        var result = cropper.getCroppedCanvas();
        $('#getCroppedCanvasModal').modal().find('.modal-body').html(result);

        img_upload({
            imageData: result.toDataURL("image/jpeg"),
            multi: multi,
            field: related_id,
            crop: 'crop'
        }, related_id, is_crop = true);

        cropper.destroy();
        $("#coverModal").modal("hide");
    });

    /* crop end */
});

function img_upload(data, related_id, is_crop = true){
    $.ajax({
        url: "{{ env('APP_URL') }}/util/img_upload",
        data: data,
        // cache: false,
        contentType: ((is_crop)?'application/x-www-form-urlencoded':false),
        processData: ((is_crop)?true:false),
        type: "POST",
        dataType: 'json',
        success: function(data) {
            if (data.status) {
                if (data.multi) {
                    $("#pics_" + related_id).append(
                        data.html
                    );
                    $("#" + related_id).val($("#" + related_id).val() + data.path +
                    ";");
                } else {
                    $("#img_" + related_id + " img").attr("src", data.realpath);
                    $("#img_" + related_id).show();
                    $("#" + related_id).val(data.path);
                    $("#delphoto_" + related_id).show();
                }
            } else {
                alert(data.msg);
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert("照片上傳發生錯誤");
        }
    });
}

function delete_photo(id) {
    $("#delphoto_" + id).hide();
    $("#" + id).val("");
    $("#img_" + id + " img").attr("src", "");
    $("#img_" + id).hide();
}

function del_multi_img(obj, pic, id) {
    if (!confirm("確定刪除此照片嗎?刪除後請按下方儲存鈕，才會真正刪除。")) return;
    $(obj).closest(".multi_img_item").fadeOut();
    $("#picdeleted_" + id).val($("#picdeleted_" + id).val() + pic + ";");
}

function delete_file(related_id, id, path, alarm = true) {
    if (alarm && !confirm("確定刪除此檔案?" + "\n" + "注意! 儲存後此動作才會正式生效")) return;
    $("#file_" + id).fadeTo('fast', 0, function() {
        $(this).remove();
    });
    $("#filesdeleted_" + related_id).val($("#filesdeleted_" + related_id).val() + path + ";");
}
</script>
@endsection