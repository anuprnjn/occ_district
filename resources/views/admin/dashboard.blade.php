@extends('admin.layouts.app')

@section('title', 'High Court of Jharkhand || ADMIN')

@section('content')

  <!--  Body Wrapper -->

    <!--  Sidebar End -->
    <!--  Main wrapper -->
    <div class="body-wrapper" style="margin-top: -70px;">
     
      <div class="body-wrapper-inner">
        <div class="container-fluid">
          <!--  Row 1 -->
          <div class="row">
            
          <div class="container">
            <div class="row">
               <div class="card-body">
              <h5 class="card-title fw-semibold mb-4">Form Template Designs</h5>
              <div class="card">
                <div class="card-body">
                  <form>
                    <div class="mb-3">
                      <label for="exampleInputEmail1" class="form-label">Email address</label>
                      <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
                      <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
                    </div>
                    <div class="mb-3">
                      <label for="exampleInputPassword1" class="form-label">Password</label>
                      <input type="password" class="form-control" id="exampleInputPassword1">
                    </div>
                    <div class="mb-3">
                      <label for="exampleInputPassword1" class="form-label">Text Box</label>
                      <input type="text" class="form-control" id="exampleInputPassword1">
                    </div>
                    <div class="mb-3">
                      <label for="exampleInputPassword1" class="form-label">Text Box</label>
                      <input type="date" class="form-control" id="exampleInputPassword1">
                    </div>
                    <div class="mb-3">
                        <label for="colorPicker" class="form-label">Choose a Color</label>
                        <input type="color" class="form-control form-control-color" id="colorPicker">
                    </div>
                    <div class="mb-3">
                        <label for="colorSelect" class="form-label">Choose a Color</label>
                        <select class="form-control" id="colorSelect">
                            <option value="">Select a color</option>
                            <option value="red">Red</option>
                            <option value="blue">Blue</option>
                            <option value="green">Green</option>
                            <option value="yellow">Yellow</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Select an Option</label>
                        <div class="form-check">
                            <input type="radio" class="form-check-input" id="option1" name="options">
                            <label class="form-check-label" for="option1">Option 1</label>
                        </div>
                        <div class="form-check">
                            <input type="radio" class="form-check-input" id="option2" name="options">
                            <label class="form-check-label" for="option2">Option 2</label>
                        </div>
                    </div>
                    <div class="mb-3 form-check">
                      <input type="checkbox" class="form-check-input" id="exampleCheck1">
                      <label class="form-check-label" for="exampleCheck1">Check me out</label>
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
    </div>

      
@endsection