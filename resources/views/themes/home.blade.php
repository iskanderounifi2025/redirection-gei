<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title> Admin</title>
  @include('themes.style')


</head>
<body>
  <div class="container-scroller">
    <!-- partial:partials/_navbar.html -->
  
@include('themes.header')
@include('themes.sideleft')






      <!-- partial -->
      <!-- partial:partials/_sidebar.html -->
      
      <!-- partial -->
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row">
      <div class="col-lg-4 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Brands Ranking YTD
                  </h4>
               
                  </p>
                  <div class="table-responsive">
                    <table class="table table-hover">
                      <thead>
                        <tr>
                          <th>Brand</th>
                          <th>Montant</th>
                          
                        </tr>
                      </thead>
                      <tbody>
                    
                    </table>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-4 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Brands Ranking MTD

                  </h4>
               
                  </p>
                  <div class="table-responsive">
                    <table class="table table-hover">
                      <thead>
                        <tr>
                          <th>Brand</th>
                          <th>Montant</th>
                          
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>Breurer</td>
                           <td class="text-danger">530 085, 500
                           <i class="ti-arrow-down"></i></td>
                         </tr>
                        <tr>
                          <td>Braun</td>
                           <td class="text-danger"> 499 000, 000
                           <i class="ti-arrow-down"></i></td>
                         </tr>
                        <tr>
                          <td>Techwood</td>
                           <td class="text-danger"> 450 700, 500
                           <i class="ti-arrow-down"></i></td>
                         </tr>
                        <tr>
                          <td>Westinghouse</td>
                           
                          <td class="text-success"> 320 080, 000
                          <i class="ti-arrow-up"></i></td>
                         </tr>
                     
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
<!-- -->
<div class="col-lg-4 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Brands Ranking DoD


                  </h4>
               
                  </p>
                  <div class="table-responsive">
                    <table class="table table-hover">
                      <thead>
                        <tr>
                          <th>Brand</th>
                          <th>Montant</th>
                          
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>Breurer</td>
                           <td class="text-danger">530 085, 500
                           <i class="ti-arrow-down"></i></td>
                         </tr>
                        <tr>
                          <td>Braun</td>
                           <td class="text-danger"> 499 000, 000
                           <i class="ti-arrow-down"></i></td>
                         </tr>
                        <tr>
                          <td>Techwood</td>
                           <td class="text-danger"> 450 700, 500
                           <i class="ti-arrow-down"></i></td>
                         </tr>
                        <tr>
                          <td>Westinghouse</td>
                           
                          <td class="text-success"> 320 080, 000
                          <i class="ti-arrow-up"></i></td>
                         </tr>
                     
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
<!-- -->



<div class="col-lg-4 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">SKUs Ranking YTD



                  </h4>
               
                  </p>
                  <div class="table-responsive">
                    <table class="table table-hover">
                      <thead>
                        <tr>
                          <th>SKU</th>
                          <th>Montant</th>
                          
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>Breurer</td>
                           <td class="text-danger">BR SES5500
                           <i class="ti-arrow-down"></i></td>
                         </tr>
                        <tr>
                          <td>BE BY40</td>
                           <td class="text-danger"> 499 000, 000
                           <i class="ti-arrow-down"></i></td>
                         </tr>
                        <tr>
                          <td>KA -K3</td>
                           <td class="text-danger"> 450 700, 500
                           <i class="ti-arrow-down"></i></td>
                         </tr>
                        <tr>
                          <td>CA 56/154</td>
                           
                          <td class="text-success"> 320 080, 000
                          <i class="ti-arrow-up"></i></td>
                         </tr>
                     
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
        <!-- content-wrapper ends -->
        <!-- partial:partials/_footer.html -->
        
        <!-- partial -->
      </div>
      <!-- main-panel ends -->
    </div>   
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->
 <!-- plugins:js -->

 <script src="{{ asset('vendors/js/vendor.bundle.base.js') }}"></script>
    <script src="{{ asset('vendors/chart.js/Chart.min.js') }}"></script>
    <script src="{{ asset('vendors/datatables.net/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('vendors/datatables.net-bs4/dataTables.bootstrap4.js') }}"></script>
    <script src="{{ asset('vendors/datatables.net-select/dataTables.select.min.js') }}"></script>
    <script src="{{ asset('js/off-canvas.js') }}"></script>
    <script src="{{ asset('js/hoverable-collapse.js') }}"></script>
    <script src="{{ asset('js/template.js') }}"></script>
    <script src="{{ asset('js/settings.js') }}"></script>
    <script src="{{ asset('js/todolist.js') }}"></script>
    <script src="{{ asset('js/dashboard.js') }}"></script>
    <script src="{{ asset('js/Chart.roundedBarCharts.js') }}"></script>

<!-- End custom js for this page-->
</body>

</body>

</html>

