@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            @include('backend.block.navbar')
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">{{ $title }}</div>
                    <div class="card-body">
                        @if (session('msg'))
                            <div class="alert alert-success text-center">{{ session('msg') }}</div>
                        @endif
                        @if ($errors->any())
                            <div class="alert alert-danger text-center">Dữ liệu không hợp lệ vui lòng nhập lại</div>
                        @endif
                        <form action="{{ route('admin.group.postAdd') }}" method="POST">
                            <div class="mb-3">
                                <label for="txtGroupName" class="form-label">Tên</label>
                                <input type="text" class="form-control" name="txtGroupName" id="txtGroupName"
                                    value='{{ old('txtGroupName') }}'>
                                @error('txtGroupName')
                                    <p class="text-danger mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="txtGroupDescrbie" class="form-label">Miêu tả</label>
                                <input type="text" class="form-control" name="txtGroupDescribe" id="txtGroupDescribe"
                                    value='{{ old('txtGroupDescribe') }}'>
                                @error('txtGroupDescribe')
                                    <p class="text-danger mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="slGroupStatus" class="form-label">Trạng Thái</label>
                                <select class="form-select" name="slGroupStatus">
                                    <option selected disabled>Hãy chọn trạng thái</option>
                                    <option value=1 {{ old('slGroupStatus') == 1 ? 'selected' : false }}>không kích
                                        hoạt
                                    </option>
                                    <option value=2 {{ old('slGroupStatus') == 2 ? 'selected' : false }}>kích hoạt
                                    </option>
                                </select>
                                @error('slGroupStatus')
                                    <p class="text-danger mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                            @can('group.decentralize')
                                <div class="mb-3">
                                    @if ($module->count() > 0)
                                        <table class="table">
                                            <tr>
                                                <th>Tên module</th>
                                                <th>Action</th>
                                            </tr>
                                            @foreach ($module as $item)
                                                <tr>
                                                    <td>
                                                        {{ $item->title }}
                                                    </td>
                                                    <td>
                                                        @if (!empty($roleArr))
                                                            @foreach ($roleArr as $roleName => $roleLabel)
                                                                <div class="form-check form-check-inline">
                                                                    <input class="form-check-input" type="checkbox"
                                                                        id="role{{ $item->name . $roleName }}"
                                                                        name="role[{{ $item->name }}][]"
                                                                        value="{{ $roleName }}"
                                                                        {{ isRole(old('role'), $item->name, $roleName) ? 'checked' : '' }}>
                                                                    <label class="form-check-label"
                                                                        for="role{{ $item->name . $roleName }}">{{ $roleLabel }}</label>
                                                                </div>
                                                            @endforeach
                                                        @endif
                                                        @if ($item->name == 'Group')
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="checkbox"
                                                                    id="role{{ $item->name }}Decentralize"
                                                                    name="role[{{ $item->name }}][]" value="Decentralize"
                                                                    {{ isRole(old('role'), $item->name, 'Decentralize') ? 'checked' : '' }}>
                                                                <label class="form-check-label"
                                                                    for="role{{ $item->name }}Decentralize">Phân
                                                                    Quyền</label>
                                                            </div>
                                                        @endif
                                                        <div class="form-group d-inline-block">
                                                            <label for="role{{ $item->name }}Scope" class="form-label">
                                                                Phạm vi
                                                            </label>
                                                            <select class="form-select d-inline ml-1" style="width:62%"
                                                                name="role[{{ $item->name }}][Scope]"
                                                                id="role{{ $item->name }}Scope">
                                                                <option selected value="NULL" disabled>Hãy chọn phạm vi truy
                                                                    cập </option>
                                                                <option value="1"
                                                                    @if (old('role')) @if (array_key_exists($item->name, old('role')))
                                                                    @if (array_key_exists('Scope', old('role')[$item->name]))
                                                                    {{ old('role')[$item->name]['Scope'] == 1 ? 'selected' : false }} @endif
                                                                    @endif
                                            @endif>Tất cả</option>
                                            <option value="2"
                                                @if (old('role')) @if (array_key_exists($item->name, old('role')))
                                                        @if (array_key_exists('Scope', old('role')[$item->name]))
                                                        {{ old('role')[$item->name]['Scope'] == 2 ? 'selected' : false }} @endif
                                                @endif
                                    @endif>
                                    Single</option>
                                    </select>
                                </div>
                                </td>

                                </tr>
                                @endforeach
                                </table>
                                @error('role')
                                    <p class="text-danger mt-2">{{ $message }}</p>
                                @enderror
                                @endif
                        </div>
                    @endcan
                    <div class="mb-3">
                        <button type="submit" class="btn btn-primary">Submit</button>
                        @csrf
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
@section('extraJs')
    <script>
        var inputs = document.querySelectorAll("input[type='checkbox']");

        function defaultCheck(checkbox) {
            var role = checkbox.id.replace(checkbox.value, '');
            var viewRole = document.getElementById(role + 'View');
            var action = checkbox.value;
            var view = document.getElementById(role + 'View');

            var roleCheckbox = Array.prototype.filter.call(inputs, function(checkbox) {
                if (checkbox.id != role + "View") {
                    return checkbox.id.includes(role);
                }
            });
            var scope = document.getElementById(role + 'Scope');
            if (checkbox.checked == false) {
                if (viewRole.checked === false) {
                    console.log(viewRole.checked);
                    scope.value = 'NULL';
                }
            }


            if (checkbox.checked == true) {
                if (checkbox.value != 'View') {
                    if (view.checked == false) {
                        view.checked = true;
                    }
                }
            }
            if (checkbox.value == 'View') {
                if (checkbox.checked == false) {
                    roleCheckbox.forEach(element => {
                        element.checked = false;
                    });
                }
            }
            if (checkbox.value == 'Decentralize') {
                if (checkbox.checked == true) {
                    if (role == 'roleGroup') {
                        editRole = document.getElementById(role + 'Edit')
                        if (editRole.checked == false) {
                            editRole.checked = true;
                        }
                    }
                }
                if (checkbox.checked == false) {
                    addRole = document.getElementById(role + 'Add');
                    if (addRole.checked == true) {
                        addRole.checked = false;
                    }

                }
            }
            if (checkbox.value == 'Add') {
                if (role == 'roleGroup') {
                    decentralizeRole = document.getElementById(role + 'Decentralize');
                    if (decentralizeRole.checked == false) {
                        decentralizeRole.checked = true
                    }
                }

            }
            if (checkbox.value == 'Edit' && role == 'roleGroup') {
                decentralizeRole = document.getElementById(role + 'Decentralize');
                editRole = document.getElementById(role + 'Edit');

                if (checkbox.checked == false && decentralizeRole.checked == true) {
                    decentralizeRole.checked = false;
                }
            }
        }

        inputs.forEach(function(checkbox) {
            checkbox.onchange = function() {
                var role = checkbox.id.replace(checkbox.value, '');
                if (role == 'roleManga' || role == 'roleChapter') {
                    action = checkbox.value
                    if (role == 'roleManga') {
                        diffRole = 'roleChapter';
                    } else {
                        diffRole = 'roleManga';
                    }
                    roleTag = document.getElementById(role + action)
                    diffRoleTag = document.getElementById(diffRole + action)
                    if (roleTag.checked == true) {
                        diffRoleTag.checked = true;
                        defaultCheck(diffRoleTag);
                    }
                    if (roleTag.checked == false) {
                        diffRoleTag.checked = false;
                        defaultCheck(diffRoleTag);
                    }
                }
                defaultCheck(checkbox);

            }
        });
    </script>
@endsection
