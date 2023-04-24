@extends('layouts.admin.app')

@if(isset($updating) && $updating)
@section('pageTitle', 'Edit Assign Tasks')
@else
@section('pageTitle', 'Add Assign Tasks')
@endif

@section('content')

<div class="container padding-around">
  <div class="card-header">
    <h3>Add Task Assignee</h3>
  </div>
  <div class="row">
    <div class="col-md-6">
      @if(isset($updating) && $updating)
      <form method="POST" id="taskassignform" action="{{ route('task.update', $task->id) }}">
      @method('PUT') @else
      <form method="POST" id="taskassignform" action="{{ url('task/add-assign-task') }}">
      @endif
      @csrf
        <div class="container padding-around">
          <div class="row">
            <div class="col-md-12">
              <div class="form-group mb-2">
                <label for="" class="form-label" style="margin-bottom:0px;">Task Title<span class="mandatory">*</span></label>
                <select class="form-select" id="taskAssign" name="taskAssign" required>
                  <option value="">Select Task</option>
                  @foreach ($tasks as $task)
                  <option value="{{ $task->id }}" {{ (isset($task) && $task->id == "sales") ? "selected" : "" }}>{{ $task->title }}</option>
                  @endforeach
                </select>
              </div>
              <div class="form-group mb-2">
                <label for="" class="form-label" style="margin-bottom:0px;">Assignee<span class="mandatory">*</span></label>
                <select class="form-select" id="taskAssignee" name="taskAssignee" required>
                  <option value="">Select Assignee</option>
                  @foreach ($employees as $employee)
                  <option value="{{ $employee->id }}" {{ (isset($employee) && $employee->id == "sales") ? "selected" : "" }}>{{ $employee->name }}</option>
                  @endforeach
                </select>
              </div>
            </div>
          </div>
          <div class="btn-group mt-12" style="display: block; float: right;">
            <input type="submit" class="btn btn-primary" value="Save">
            <input type="hidden" name="saveAddHdn" id="saveAddHdn" data-employee="{{ isset($employee) ? $employee->id : '0' }}" data-task="{{ isset($task) ? $task->id : '0' }}" value=""/>
          </div>
        </div>

@endsection

@section('js')

<script>

$(document).ready(function() {

$('#taskassignform').validate({
  ignore: false,
  rules: {
    taskAssign: {
      required: true,
    },
    taskAssignee: {
      required: true,
    },
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

    // var employeeId = $("#saveAddHdn").data("employee");
    // if(employeeId != 0)
    // {

    //   var method ='POST';
    //   formData.append('_method', "PUT");

    // } else{

      var method ='POST';
      formData.append('_method', "POST");
    // }
      
    formData.append('_token', "{{ csrf_token() }}");
    formData.append('taskAssign', $("#taskAssign").val());
    formData.append('taskAssignee', $("#taskAssignee").val());
    formData.append('saveAddHdn', $('#saveAddHdn').val());
    for (var pair of formData.entries()) {
      console.log(pair[0]+ ', ' + pair[1]); 
    }
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
          window.location.href = '{{ route("task.index") }}';
        }
        else{
          alert('The task assigning cannot be updated as the task status is In Progress/Done.');
        }
      }
    })
  },
});

});

</script>

@endsection