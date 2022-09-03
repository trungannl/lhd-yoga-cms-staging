@push('css_lib')
    <link rel="stylesheet" href="{{asset('plugins/dropzone/bootstrap.min.css')}}">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
@endpush

<div class="col-md-6">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Staff Information</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Display Name</label>
                        {!! Form::text('name', null,  $errors->has('name') ? ['class' => 'form-control is-invalid'] : ['class' => 'form-control']) !!}
                        @error('name')
                        <span class="error invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Email</label>
                        {!! Form::email('email', null,  $errors->has('email') ? ['class' => 'form-control is-invalid'] : ['class' => 'form-control']) !!}
                        @error('email')
                        <span class="error invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Phone number</label>
                        <div class="input-group">
                            {!! Form::text('phone', null,  $errors->has('phone') ? ['class' => 'form-control is-invalid'] : ['class' => 'form-control']) !!}
                            @error('phone')
                            <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Avatar</label>
                        <div style="width: 50%" class="dropzone avatar" id="avatar" data-field="avatar">
                            <input type="hidden" name="avatar">
                        </div>
                    </div>
                </div>
            </div>
            <div>

            </div>

        </div>
    </div>
</div>

<div class="col-md-6">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Staff Role & Access Rights</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Staff Role</label>
                        {!! Form::select('role_id', $roles, null, ['class' => 'form-control', 'id' => 'role']) !!}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <label>Access To</label>
                </div>
                <div class="card-body" style="min-height: 344px;">
                    <div class="row" style="padding-left: 5px;">
                        @foreach($permissions as $pemission)
                            <div class="permission_border" data-id="{{ $pemission->id }}" id="permission_{{ $pemission->id }}">
                                <i class="nav-icon fas"><img src="/images/icon/{{ $pemission->name }}.svg" alt="icon_dashboard"></i>
                                <p>{{ $pemission->name }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts_lib')
    <script type="text/javascript" src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script type="text/javascript" src="{{asset('plugins/dropzone/dropzone.js')}}"></script>
    <script type="text/javascript">
        Dropzone.autoDiscover = false;
        var dropzoneFields = [];
    </script>

    <script type="text/javascript">
        var user_avatar = '';
        @if(isset($staff) && $staff->hasMedia('avatar'))
            user_avatar = {
            name: "{!! $staff->getFirstMedia('avatar')->name !!}",
            size: "{!! $staff->getFirstMedia('avatar')->size !!}",
            type: "{!! $staff->getFirstMedia('avatar')->mime_type !!}",
            collection_name: "{!! $staff->getFirstMedia('avatar')->collection_name !!}"
        };
        @endif
        var dz_user_avatar = $(".dropzone.avatar").dropzone({
            url: "{!!url('uploads/store')!!}",
            addRemoveLinks: true,
            maxFiles: 1,
            accept: function (file, done) {
                dzAccept(file, done, this.element, "{!!config('medialibrary.icons_folder')!!}");
            },
            sending: function (file, xhr, formData) {
                dzSending(this, file, formData, '{!! csrf_token() !!}');
            },
            maxfilesexceeded: function (file) {
                dz_user_avatar[0].mockFile = '';
                dzMaxfile(this, file);
            },
            removedfile: function (file) {
                dzRemoveFile(
                    file, user_avatar, '{!! url("staffs/remove-media") !!}',
                    'avatar', '{!! isset($staff) ? $staff->id : 0 !!}', '{!! url("uplaods/clear") !!}', '{!! csrf_token() !!}'
                );
            }
        });
        dz_user_avatar[0].mockFile = user_avatar;
        dropzoneFields['avatar'] = dz_user_avatar;

        function dzAccept(file, done, dzElement = '.dropzone', iconBaseUrl) {
            var ext = file.name.split('.').pop().toLowerCase();
            if(['jpg','png','gif','jpeg','bmp'].indexOf(ext) === -1){
                var thumbnail = $(dzElement).find('.dz-preview.dz-file-preview .dz-image:last');
                var icon = iconBaseUrl+"/"+ext+".png";
                thumbnail.css('background-image', 'url('+icon+')');
                thumbnail.css('background-size', 'contain');
            }
            done();
        }

        function dzRemoveFile(file, mockFile = '', existRemoveUrl = '', collection, modelId, newRemoveUrl, csrf) {
            if (file.previewElement != null && file.previewElement.parentNode != null) {
                file.previewElement.parentNode.removeChild(file.previewElement);
            }
        }

        function dzSending(_this, file, formData, csrf) {
            _this.element.children[0].value = file.upload.uuid;
            formData.append('_token', csrf);
            formData.append('field', _this.element.dataset.field);
            formData.append('uuid', file.upload.uuid);
        }

    </script>

    <script type="text/javascript">
        $(function () {
            $("#birthday").datepicker({
                changeYear: true,
                changeMonth: true,
            });

            @if (isset($staff))
                @if ($staff->district_id)
                changeAddress({{ $staff->city_id }}, '{{ route('area.district') }}', $('#district'), {{ $staff->district_id }});
                @else
                initAddress($('#city').val(), '{{ route('area.district') }}', $('#district'), true);
                @endif

                @if ($staff->ward_id)
                changeAddress({{ $staff->district_id }}, '{{ route('area.ward') }}', $('#ward'), {{ $staff->ward_id }});
                @endif
            @else
            if ($('#city').val()) {
                initAddress($('#city').val(), '{{ route('area.district') }}', $('#district'), true);
            }
            @endif


            $('#city').change(function (){
                var value = $(this).val();
                initAddress(value, '{{ route('area.district') }}', $('#district'), true);
            });

            $('#district').change(function (){
                var value = $(this).val();
                initAddress(value, '{{ route('area.ward') }}', $('#ward'), false);
            });

            loadPermissionRole($('#role').val());
            $('#role').change(function (){
                $('.permission_border').each(function() {
                    $(this).removeClass('permission_select');
                });
                var value = $(this).val();
                loadPermissionRole(value);
            });

        });

        function initAddress(value, url, element, isCity)
        {
            $.ajax({
                url: url,
                type: 'GET',
                data: {
                    'city': value
                },
                success: function (data, textStatus, xhr) {
                    if (xhr.status == 200) {
                        setSelectOptions(data, element);
                        if (isCity == true) {
                            var value = $('#district').val();
                            initAddress(value, '{{ route('area.ward') }}', $('#ward'), false);
                        }
                    }
                }
            });
        }

        function changeAddress(value, url, element, change_value)
        {
            $.ajax({
                url: url,
                type: 'GET',
                data: {
                    'city': value
                },
                success: function (data, textStatus, xhr) {
                    if (xhr.status == 200) {
                        setSelectOptions(data, element);
                        element.val(change_value).change;
                    }
                }
            });
        }

        function setSelectOptions(data, element)
        {
            element.prop('disabled', false);
            element.empty();
            $.each(data, function (i, item) {
                element.append($("<option></option>")
                        .attr("value", i)
                        .text(item));

            });
        }

        function loadPermissionRole(id)
        {
            $.ajax({
                url: "/roles/" + id,
                type: 'GET',
                data: {},
                success: function (data, textStatus, xhr) {
                    if (xhr.status == 200) {
                        data.forEach(function(item) {
                            $('#permission_' + item.id).addClass('permission_select');
                        });
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(textStatus, errorThrown);
                }
            });
        }
    </script>

@endpush
