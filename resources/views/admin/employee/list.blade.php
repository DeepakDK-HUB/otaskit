@extends('layouts.admin.app')

@section('pageTitle', 'Employee List')

@section('content')

<div class="card-header" style="font-size:30px;">{{ __('Employees List') }}</div>

<div class="card user-group-table view-users mt-12 mb-12">
  <form action="">
    <div class="table-responsive">
      <table class="table table-bordered table-striped mt-12" id="employeesTable">
        <thead>
          <tr>
            <th>Sl.No</th>
            <th>Name</th>
            <th>Email</th>
            <th>Mobile</th>
            <th>Department</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @php
            $count = 1;
          @endphp
          @foreach ($employees as $employee)
          <tr>
            <td>{{ $count }}</td>
            <td>{{ $employee->name }}</td>
            <td>{{ $employee->email }}</td>
            <td>{{ $employee->mobile }}</td>
            <td>{{ ucfirst(trans($employee->department)) }}</td>
            <td>{{ $employee->status == 1 ? 'Active' : 'Inactive' }}</td>
            <td> 
              <div class="btn-group">
                <button class="btn-action danger" type="button">
                <a href="/employee/{{ $employee->id }}/edit" class="btn-action primary" data-toggle="tooltip" title="Edit">
                  Edit
                </a>
                </button>
                &nbsp;&nbsp;&nbsp;
                <button class="btn-action danger" type="button" name="usrdelete"  id="usrdelete" data-toggle="tooltip" title="Delete" data-bs-toggle="modal" data-bs-target="#deleteModal" data-target-id="{{ $employee->id }}">
                  Delete
                </button>
              </div>
            </td>
          </tr>
          @php
            $count++;
          @endphp
          @endforeach
        </tbody>
      </table>
    </div>
  </form>
</div>
<div class="modal fade" id="deleteModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Delete</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">Are you sure you want to delete this employee?</div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
        <button type="button" class="btn btn-primary" id="deleteEmployee" name="deleteEmployee">Yes</button>
      </div>
    </div>
  </div>
</div>
@endsection

@section('js')

<script>

  $("#deleteModal").on("show.bs.modal", function (e) {
    var id = $(e.relatedTarget).data('target-id');
    alert(id);
    $('#deleteModal button#deleteEmployee').attr('data-id',id);
  });

  $("button#deleteEmployee").click(function () {
    $('#deleteModal').modal('hide');

    var idemployee = $(this).data('id');

    $.ajax({
      type: "DELETE",
      url: '/employee/'+idemployee,
      data: {
        "_token": "{{ csrf_token() }}",
        'id':$(this).data('id'),
      },
      success: function (result) {
        location.reload();
        // alert("User Deleted");
      },
    });
  });

</script>
@endsection