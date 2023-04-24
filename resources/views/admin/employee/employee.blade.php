@extends('layouts.admin.app')

@if(isset($updating) && $updating)
@section('pageTitle', 'Edit Employee')
@else
@section('pageTitle', 'Add Employee')
@endif

@section('content')

<div class="container padding-around">
  <div class="card-header">
    <h3>Employee Details</h3>
  </div>
  <div class="row">
    <div class="col-md-6">
      @if(isset($updating) && $updating)
      <form method="POST" id="employeeform" action="{{ route('employee.update', $employee->id) }}">
      @method('PUT') @else
      <form method="POST" id="employeeform" action="{{ route('employee.store') }}">
      @endif
      @csrf
        <div class="container padding-around">
          <div class="row">
            <div class="col-md-12">
              <div class="form-group mb-2">
                <label for="" class="form-label" style="margin-bottom:0px;">Employee Name<span class="mandatory">*</span></label>
                <input type="text" class="form-control" id="employeeName" name="employeeName" required value="{{ isset($employee) ? $employee->name : old('name') }}">
              </div>
              <div class="form-group mb-2">
                <label for="" class="form-label" style="margin-bottom:0px;">Employee Email<span class="mandatory">*</span></label>
                <input type="email" class="form-control" id="employeeEmail" name="employeeEmail" required value="{{ isset($employee) ? $employee->email : old('email') }}">
              </div>
              <div class="form-group mb-2">
                <label for="" class="form-label" style="margin-bottom:0px;">Employee Mobile<span class="mandatory">*</span></label>
                <input type="text" class="form-control" id="employeeMobile" min=10 name="employeeMobile" required value="{{ isset($employee) ? $employee->mobile : old('mobile') }}">
              </div>
              <div class="form-group mb-2">
                <label for="" class="form-label" style="margin-bottom:0px;">Employee Department</label>
                {{-- <input type="text" class="form-control" id="employeeDepartment" name="employeeDepartment" value="{{ isset($employee) ? $employee->department : old('department') }}"> --}}
                <select class="form-select" id="employeeDepartment" name="employeeDepartment">
                  <option value="">Select Department</option>
                  <option value="sales" {{ (isset($employee) && $employee->department == "sales") ? "selected" : "" }}>Sales</option>
                  <option value="marketing" {{ (isset($employee) && $employee->department == "marketing") ? "selected" : "" }}>Marketing</option>
                  <option value="it" {{ (isset($employee) && $employee->department == "it") ? "selected" : "" }}>IT</option>
                </select>
              </div>
              <div class="form-group mb-2">
                <label for="" class="form-label" style="margin-bottom:0px;">Employee Status</label>
                {{-- <input type="text" class="form-control" id="employeeStatus" name="employeeStatus" value="{{ isset($employee) ? $employee->status : old('status') }}"> --}}
                <ul class="radio-button-list">
                  <li>
                    <input class="radio-button" type="radio" id="employeeStatusYes" name="employeeStatus" value="1" {{ (isset($employee) && $employee->status == '1') ? 'checked' : '' }}>
                    <label for="employeeStatusYes">Active</label>
                  </li>
                  <li>
                    <input class="radio-button" type="radio" id="employeeStatusNo" name="employeeStatus" value="0" {{ (isset($employee) && $employee->status == '0') ? 'checked' : '' }}>
                    <label for="employeeStatusNo">Inactive</label>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
        <div class="btn-group mt-12" style="display: block; float: right;">
          <input type="submit" class="btn btn-primary" value="Save">
          <input type="hidden" name="saveAddHdn" id="saveAddHdn" data-employee="{{ isset($employee) ? $employee->id : '0' }}" value=""/>
        </div>
      </form>
    </div>
  </div>
</div>


@endsection

@section('js')

<script>

$(document).ready(function() {

  $('#employeeform').validate({
    ignore: false,
    rules: {
      employeeName: {
        required: true,
        minlength: 4,
      },
      employeeEmail: {
        required: true,
        email: true,
        remote: {
          url: '{{ url("employee/check-mail") }}',
          type: "post",
          data: {
            email: function() {
                  return $("#employeeEmail").val();
                },
            _token:"{{ csrf_token() }}"
          },
          dataFilter: function (data) {
            var json = JSON.parse(data);
            if (json.msg == "true") {
                return "\"" + "Email address already in use!" + "\"";
            } else {
                return 'true';
            }
          }
        }
      },
      employeeMobile: {
        required: true,
        digits: true,
        minlength: 10,
      },
      employeeDepartment: {
        required: true
      },
      employeeStatus: {
        required: true
      }
    },
    messages: {
      
    },
    errorElement: 'span',
    errorPlacement: function (error, element) {
      element.closest('.form-group').append(error);
    },
    highlight: function (element, errorClass, validClass) {
      $(element).addClass('is-invalid');
    },
    unhighlight: function (element, errorClass, validClass) {
      $(element).removeClass('is-invalid');
    },
    submitHandler: function(form,e) {

      var formData = new FormData();

      var employeeId = $("#saveAddHdn").data("employee");
      if(employeeId != 0)
      {

        var method ='POST';
        formData.append('_method', "PUT");

      } else{

        var method ='POST';
      }
        
      formData.append('_token', "{{ csrf_token() }}");
      formData.append('employeeName', $("#employeeName").val());
      formData.append('employeeEmail', $("#employeeEmail").val());
      formData.append('employeeMobile', $("#employeeMobile").val());
      formData.append('employeeDepartment', $("#employeeDepartment").val());
      formData.append('employeeStatus',  $("input[name=employeeStatus]").val());
      formData.append('saveAddHdn', $('#saveAddHdn').val());

      $.ajax({
        url: form.action,
        method: method,
        dataType: "text",
        data: formData,
        cache: false,
        processData : false,
        contentType: false,
        success: function(response) {
          if (response = "OK") {
            window.location.href = '{{ url("employee") }}';
          }
          else{
            alert('Nadanila');
          }
        }
      })
    },
  });

});

</script>

@endsection