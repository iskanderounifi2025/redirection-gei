<!DOCTYPE html>
<html lang="en">


<head>
   
<title>Ajouter Produit </title>
  <!-- plugins:css -->
  @include('themes.style')
  </head>
  <body>
  <div class="container-scroller">
    <!-- partial:partials/_navbar.html -->
    @include('themes.header')
    @include('themes.sideleft')




 
      <!-- partial -->
      <div class="main-panel">
        <div class="content-wrapper">
        <div class="row">
    <div class="col-md-12 grid-margin">
        <div class="row">
            <div class="col-12 col-md-8 mb-4 mb-md-0">
                
            </div>
            <div class="col-12 col-md-4">
                <div class="d-flex justify-content-md-end">
                    <div>
                        <button class="btn btn-sm btn-light bg-white dropdown-toggle" type="button" id="dropdownMenuDate2" aria-haspopup="true" aria-expanded="true">
                            <i class="mdi mdi-calendar"></i> Aujourd'hui ({{ now()->format('d M Y') }} - {{ now()->format('H:i') }})
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


       
          <div class="row"> 
            <div class="col-md-12 stretch-card grid-margin">
              <div class="card">
                <div class="card-body">
                <div class="container mx-auto p-4">
                <h1 class="text-center mb-4">Ajouter un Produit</h1>

@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

@if($errors->any())
<div class="alert alert-danger">
<ul>
@foreach($errors->all() as $error)
<li>{{ $error }}</li>
@endforeach
</ul>
</div>
@endif

    <div id="alertError" class="alert alert-danger d-none" role="alert">Erreur lors de l'ajout du produit.</div>
    <div id="alertSuccess" class="alert alert-success d-none" role="alert">Produit ajouté avec succès !</div>

    <form id="productForm" method="POST" action="{{ route('produit.store') }}" enctype="multipart/form-data" class="mb-4">
        @csrf
        <div class="mb-3">
            <label for="productName" class="form-label">Nom de Produit:</label>
            <input type="text" id="productName" name="name" class="form-control" placeholder="Entrez le nom du produit" required />
        </div>

        <div class="mb-3">
            <label for="brandSelect" class="form-label">Choisir une Marque:</label>
            <select name="brand_id" id="brand_id" class="form-control">
              <option>Choisir Une marque</option>
                @foreach ($brands as $brand)
                    <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="categorySelect" class="form-label">Choisir Categorie:</label>
            <select id="categorySelect" name="category_id" class="form-control" required>
                <option value="" disabled selected>Choisir Categorie</option>
                <option value="1">Cat 1</option>
                <option value="2">Cat 2</option>
                <option value="3">Cat 3</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="productPrice" class="form-label">Prix de Produit:</label>
            <input type="number" id="productPrice" name="price" class="form-control" placeholder="Entrez le prix du produit" required />
        </div>

        <div class="mb-3">
            <label for="sku" class="form-label">SKU:</label>
            <input type="text" id="sku" name="sku" class="form-control" placeholder="Entrez SKU" required />
        </div>

        <div class="mb-3">
                                            <label for="productImage" class="form-label">Image de Produit:</label>
                                            <input type="file" id="productImage" name="image_path" accept="image/*" class="form-control" required />
                                            <div id="logoPreview" class="mt-2" style="display: none;">
                                                <img id="logoImage" alt="Image Preview" class="img-thumbnail" style="max-width: 100px;" />
                                            </div>
                                        </div>
                                        <div class="mb-3">

<input type="hidden" name="id_team" value="{{ $team->id }}">
</div>

                                        <button type="submit" class="btn btn-primary w-100">Ajouter Produit</button>
                                    </form>
                                </div>

                                <script>
                                    document.getElementById('productImage').addEventListener('change', function (event) {
                                        const file = event.target.files[0];
                                        const logoPreview = document.getElementById('logoPreview');
                                        const logoImage = document.getElementById('logoImage');

                                        if (file) {
                                            const reader = new FileReader();
                                            reader.onload = function (e) {
                                                logoImage.src = e.target.result;
                                                logoPreview.style.display = 'block';
                                            }
                                            reader.readAsDataURL(file);
                                        } else {
                                            logoPreview.style.display = 'none';
                                        }
                                    });
                                </script>
                            </div>
                        </div>
                    </div>
                </div>

 


         

      </div>
    </div>   
  </div>


  @include('themes.js')

</body>

</html>

