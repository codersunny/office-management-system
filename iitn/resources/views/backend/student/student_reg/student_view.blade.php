@extends('admin.admin_master')
@section('admin')


 <div class="content-wrapper">
	  <div class="container-full">
		<!-- Content Header (Page header) -->
		 

		<!-- Main content -->
		<section class="content">
		  <div class="row">


			<div class="col-12">

			 <div class="box">
				<div class="box-header with-border">
				  <h3 class="box-title">Student List</h3>
	<a href="{{ route('student.registration.add') }}" style="float: right;" class="btn btn-rounded btn-success mb-5"> Add Student  </a>			  

				</div>
				<!-- /.box-header -->
				<div class="box-body">
					<div class="table-responsive">

	  <table id="example1" class="table table-bordered table-striped">
						<thead>
			<tr>
				<th width="5%">SL</th>  
				<th>Name</th>
				<th>ID No</th>
				<th>Year</th>
				<th>Shift</th>
				<th>Course</th>
				<th>Gender</th>
				<th>Image</th>
				@if(Auth::user()->role == "Admin")
				<th>Code</th>
				 @endif
				<th width="25%">Action</th>
				 
			</tr>
		</thead>
		<tbody>
			@foreach($allData as $key => $value )
			<tr>
				<td>{{ $key+1 }}</td>
				<td> {{ $value->name }}</td>
				<td> {{ $value->id_no }}</td>	
				<td> {{ $value->year_id }}</td>
				<td> {{ $value->group_id }}</td>
				<td> {{ $value->shift_id }}</td>
				<td> {{ $value->gender }}</td>


					
				<td>
	 <img src="{{ (!empty($value->image))? url('upload/student_images/'.$value->image):url('upload/no_image.jpg') }}" style="width: 50px; width: 50px;"> 
				</td>	
				<td>  {{ $value->code }}</td>			 
				<td>
<a title="Edit" href="{{ route('student.registration.edit',$value->id) }}" class="btn btn-info"> <i class="fa fa-edit"></i> </a>


<a target="_blank" title="Delete" class="btn  btn-danger" id="delete" href="{{ route('student.registration.delete',$value->id)  }}">
	<i class="si-trash si"></i>
</a>



<a target="_blank" title="Details" href="{{ route('student.registration.details',$value->id) }}" class="btn btn-danger"  ><i class="fa fa-eye"></i></a> 

				</td>
				 
			</tr>
			@endforeach
							 
						</tbody>
						<tfoot>
							 
						</tfoot>
					  </table>




					</div>
				</div>
				<!-- /.box-body -->
			  </div>
			  <!-- /.box -->

			       
			</div>
			<!-- /.col -->
		  </div>
		  <!-- /.row -->
		</section>
		<!-- /.content -->
	  
	  </div>
  </div>





@endsection
