<div class="container my-5">
    <h2 class="text-center mb-4">Zig-Zag Responsive Gallery</h2>

    <!-- Item 1 -->
    <div class="row align-items-center mb-5">
        <div class="col-md-6 order-md-1">
            <img src="{{asset('/img/test.png')}}" class="img-fluid rounded shadow" alt="Gallery 1">
        </div>
        <div class="col-md-6 order-md-2">
            <h3>Gallery Title 1</h3>
            <p>
                Image is left, text is right on desktop.  
                On mobile → image stacks above text.
            </p>
        </div>
    </div>

    <!-- Item 2 -->
    <div class="row align-items-center mb-5">
        <div class="col-md-6 order-md-2">
            <img src="{{asset('/img/test.png')}}" class="img-fluid rounded shadow" alt="Gallery 2">
        </div>
        <div class="col-md-6 order-md-1">
            <h3>Gallery Title 2</h3>
            <p>
                Image is right, text is left on desktop.  
                On mobile → stacks normally.
            </p>
        </div>
    </div>

    <!-- Item 3 -->
    <div class="row align-items-center mb-5">
        <div class="col-md-6 order-md-1">
            <img src="{{asset('/img/test.png')}}" class="img-fluid rounded shadow" alt="Gallery 3">
        </div>
        <div class="col-md-6 order-md-2">
            <h3>Gallery Title 3</h3>
            <p>
                Alternates back → image left, text right.  
                Fully responsive across devices.
            </p>
        </div>
    </div>
</div>



<style>
    img {
    transition: transform 0.3s ease-in-out;
}
img:hover {
    transform: scale(1.05);
}

</style>