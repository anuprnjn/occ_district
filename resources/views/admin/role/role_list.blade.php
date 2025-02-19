@extends('admin.layouts.app')

@section('title', 'High Court of Jharkhand || Role List')

@section('content')
   
    <div class="body-wrapper" style="margin-top: -70px;">
     
      <div class="body-wrapper-inner">
        <div class="container-fluid">
          
            <div class="card-body">
              <h5 class="card-title fw-semibold mb-4">Role List</h5>
              <div class="card">
                <div class="card-body">
                  <form>
                    <div class="mb-3">
                      <label for="exampleInputEmail1" class="form-label">Role Type</label>
                      <input type="text" class="form-control" id="role_type" placeholder="Enter Role Type">
                      
                    </div>
                   
                    <button type="submit" class="btn btn-primary">Submit</button>
                  </form>
                </div>
              </div>
            
          </div>
        </div>
      </div>
    </div>



    </div>
  </div>

      
@endsection