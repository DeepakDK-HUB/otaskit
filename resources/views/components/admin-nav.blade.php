<div>
<!-- Knowing is not enough; we must apply. Being willing is not enough; we must do. - Leonardo da Vinci -->
  <div class="dashboard-nav-wrap">
    <ul class="dashboard-nav">
      <li class="nav-item">
        <a href="{{ url('/home') }}">
          <i class="icon icon-grid"></i> Dashboard
        </a>
      </li>
      <li class="nav-item">
        <a href="{{ route('employee.index') }}">
          <i class="icon icon-grid"></i> Employees
        </a>
        <ul class="nav-dropdown">
          <li class="">
            <a href="{{ route('employee.create') }}">Add Employee</a>
          </li>
        </ul>
      </li>
      <li class="nav-item">
        <a href="{{ route('task.index') }}">
          <i class="icon icon-grid"></i> Tasks
        </a>
        <ul class="nav-dropdown">
          <li class="">
            <a href="{{ route('task.create') }}">Add Task</a>
          </li>
        </ul>
      </li>
      <li class="nav-item">
        <a href="{{  url('task/assign-task') }}">
          <i class="icon icon-grid"></i> Assign Tasks
        </a>
      </li>
    </ul>
  </div>
</div>