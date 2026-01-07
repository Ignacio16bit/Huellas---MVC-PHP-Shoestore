var addBtn = document.getElementById('btn-add');
var rm = document.getElementById('delete-storage');
var cartCount = document.getElementById('cart-count');

addBtn.addEventListener('click', function(){
    let Product = {
        id: document.getElementById('product-id').textContent,
        pName: document.getElementById('product-title').textContent,
        image: document.getElementById('main-product-img').src,
        price: parseFloat(document.getElementById('product-price').textContent)
    };

    console.log('Guardando pedido: ', Product);
    let cart = JSON.parse(localStorage.getItem('cart')) ||[];

    cart.push(Product);
    console.log('Producto añadido');

    localStorage.setItem('cart', JSON.stringify(cart));
    console.log(JSON.parse(localStorage.getItem('cart')));

    if(cartCount){
        cartCount = cart.length;
    }
    const event = new CustomEvent('cartUpdated',{
        detail: {cartItems: cart.length}
    });
    document.dispatchEvent(event);
    return cart;
})


rm.addEventListener('click', ()=>{
    localStorage.clear();
    console.log(JSON.parse(localStorage.getItem('cart')));
})