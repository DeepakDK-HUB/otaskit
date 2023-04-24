@extends('layouts.admin.app')

@if(isset($updating) && $updating)
@section('pageTitle', 'Edit Tasks')
@else
@section('pageTitle', 'Add Tasks')
@endif

@section('content')

<div class="container padding-around">
  <div class="card-header">
    <h3>Tasks</h3>
  </div>
  <div class="row">
    <div class="col-md-6">
      @if(isset($updating) && $updating)
      <form method="POST" id="taskform" action="{{ route('task.update', $task->id) }}">
      @method('PUT') @else
      <form method="POST" id="taskform" action="{{ route('task.store') }}">
      @endif
      @csrf
        <div class="container padding-around">
          <div class="row">
            <div class="col-md-12">
              <div class="form-group mb-2">
                <label for="" class="form-label" style="margin-bottom:0px;">Task Title<span class="mandatory">*</span></label>
                <input type="text" class="form-control" id="taskTitle" name="taskTitle" required value="{{ isset($task) ? $task->title : old('title') }}">
              </div>
              <div class="form-group mb-2">
                <label for="" class="form-label" style="margin-bottom:0px;">Task Description<span class="mandatory">*</span></label>
                <textarea id="taskDescription" class="form-control" name="taskDescription" cols="10" rows="5" required>{{ isset($task) ? $task->description : old('description') }}</textarea>
              </div>
            </div>
          </div>
        </div>
        <div class="btn-group mt-12" style="display: block; float: right;">
          <input type="submit" class="btn btn-primary" value="Save">
          <input type="hidden" name="saveAddHdn" id="saveAddHdn" data-task="{{ isset($task) ? $task->id : '0' }}" value=""/>
        </div>
      </form>
    </div>
  </div>
</div>


@endsection

@section('js')

<script>

$(document).ready(function() {

  $('#taskform').validate({
    ignore: false,
    rules: {
      taskName: {
        required: true,
        minlength: 4,
      },
      taskDescription: {
        required: true,
        minlength: 10,
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

      var taskId = $("#saveAddHdn").data("task");
      if(taskId != 0)
      {

        var method ='POST';
        formData.append('_method', "PUT");

      } else{

        var method ='POST';
      }
        
      formData.append('_token', "{{ csrf_token() }}");
      formData.append('taskTitle', $("#taskTitle").val());
      formData.append('taskDescription', $("textarea#taskDescription").val());
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
            window.location.href = '{{ url("task") }}';
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