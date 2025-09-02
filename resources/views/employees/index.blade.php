<!DOCTYPE html>
<html>
<head>
    <title>Employee CRUD</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h2>Employee Management</h2>
    <button class="btn btn-primary mb-3" onclick="resetForm()" data-bs-toggle="modal" data-bs-target="#employeeModal">Add Employee</button>

    <table id="employeeTable" class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th><th>Name</th><th>Email</th><th>Gender</th>
                <th>Department</th><th>Profile</th><th>Action</th>
            </tr>
        </thead>
    </table>
</div>

<!-- Modal -->
<div class="modal fade" id="employeeModal">
  <div class="modal-dialog">
    <form id="employeeForm" enctype="multipart/form-data">
      @csrf
      <input type="hidden" name="id" id="emp_id">
      <div class="modal-content">
        <div class="modal-header"><h5 class="modal-title">Employee Form</h5></div>
        <div class="modal-body">
          <div class="mb-2"><label>Name</label><input type="text" name="name" id="name" class="form-control"></div>
          <div class="mb-2"><label>Email</label><input type="email" name="email" id="email" class="form-control"></div>
          <div class="mb-2">
            <label>Gender</label><br>
            <input type="radio" name="gender" value="Male"> Male
            <input type="radio" name="gender" value="Female"> Female
          </div>
          <div class="mb-2">
            <label>Department</label>
            <select name="department" id="department" class="form-control">
              <option value="HR">HR</option><option value="IT">IT</option><option value="Finance">Finance</option>
            </select>
          </div>
          
          <div class="mb-2"><label>Profile Picture</label><input type="file" name="profile" id="profile" class="form-control"></div>
        </div>
        <div class="modal-footer"><button type="submit" class="btn btn-success">Save</button></div>
      </div>
    </form>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.5/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
let table = $('#employeeTable').DataTable({
    ajax: "{{ route('employees.list') }}",
    columns: [
        {data: 'id'},
        {data: 'name'},
        {data: 'email'},
        {data: 'gender'},
        {data: 'department'},
        
        {data: 'profile', render: d => d ? `<img src='/storage/${d}' width='40'>` : ''},
        {data: null, render: row =>
            `<button class='btn btn-warning btn-sm' onclick='editEmployee(${JSON.stringify(row)})'>Edit</button>
             <button class='btn btn-danger btn-sm' onclick='deleteEmployee(${row.id})'>Delete</button>`
        }
    ]
});

$('#employeeForm').submit(function(e){
    e.preventDefault();
    let formData = new FormData(this);
    let url = $('#emp_id').val() ? "{{ route('employees.update') }}" : "{{ route('employees.store') }}";
    $.ajax({
        type: "POST", url: url, data: formData, contentType: false, processData: false,
        success: () => { $('#employeeModal').modal('hide'); table.ajax.reload(); }
    });
});

function editEmployee(row){
    $('#emp_id').val(row.id);
    $('#name').val(row.name);
    $('#email').val(row.email);
    $(`input[name="gender"][value="${row.gender}"]`).prop('checked', true);
    $('#department').val(row.department);
   
    $('#employeeModal').modal('show');
}

function deleteEmployee(id){
    if(confirm("Delete this employee?")){
        $.ajax({
            type: "DELETE",
            url: "/employees/"+id,
            data: {_token:"{{ csrf_token() }}"},
            success: () => table.ajax.reload()
        });
    }
}

function resetForm(){
    $('#employeeForm')[0].reset();
    $('#emp_id').val('');
}
</script>
</body>
</html>
