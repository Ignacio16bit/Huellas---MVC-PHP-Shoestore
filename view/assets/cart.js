//Cuando cargue la página que actualice el contador
let pageId = document.body.id;
let pageType = document.body.getAttribute('data-page');
if (pageType==='cart'){
    cartPage();
}

function updateCart(){
    const cartCount=document.getElementById('cart-count');
    if (cartCount){
        let cart = JSON.parse(localStorage.getItem('cart')) ||[];
        cartCount.textContent = cart.length;
    }
}
document.addEventListener('DOMContentLoaded', updateCart);
document.addEventListener('cartUpdated', updateCart);

//Al comprar eliminar cart -> mover de cart a Histórico pedidos(profile.php)

//Que muestre en html el contenido obtenido
function cartPage(){
    let cart = JSON.parse(localStorage.getItem('cart')) ||[];
    let cartDiv = document.getElementById('cart-container');

    cartDiv.innerHTML = '';
    cartDiv.className= 'ui three column grid container centered'; //Creo tres columnas

    for (let i = 0; i<cart.length; i++){
        let item = cart[i];

        let column = document.createElement('div');
        column.className = 'column';
        column.innerHTML= `
            <div class="ui card">
                <div class="image">
                    <img src="${item.image}" class="ui medium image">
                </div>
                <div class="content">
                    <div class="header">${item.pName}</div>
                    <div class="meta">
                        <span class="price">${item.price}€</div>
                </div>
                <button class="ui red button" onclick="removeFromCart(${i})">
                    Eliminar
                </button>
            </div>
        `;

        cartDiv.appendChild(column);
    }
    //Variable global del total 
    var total = cart.reduce((sum, item) => sum+item.price, 0).toFixed(2);

    let totalRow = document.createElement('div');
    totalRow.className = 'row';
    totalRow.innerHTML = `
        <div class="sixteen wide column">
            <div class="ui segment" style="margin-top="">
                <div class="ui two column grid">
                    <div class="column">
                        <h2 class="ui header teal">
                        Total: ${total}€
                        </h2>
                    </div>
                <div class="column right aligned">
                    <button class="ui primary big button" onclick="checkOut()">
                        <i class="credit card icon"></i>
                        Pagar
                    </button>
                </div>
            </div>
        </div>
    `;
    cartDiv.appendChild(totalRow);
}
//Borrar items
function removeFromCart(index){
    let cart = JSON.parse(localStorage.getItem('cart')) ||[];
    //Filtro por id para el borrado
    cart.splice(index,1);
    //Acutalizo
    localStorage.setItem('cart', JSON.stringify(cart));
    updateCart();
    cartPage();
}
function checkOut(){
    //Es un formulario que al pulsar un boton manda la información a PHP para guardarla
    let cart = JSON.parse(localStorage.getItem('cart'));
    let checkDiv = document.getElementById('checkout-div');
    checkDiv.innerHTML= '';

    let form = document.createElement("form");
    form.className ="ui form";
    form.method="POST";
    form.action="../controllers/create_order.php";

    for (let i = 0; i<cart.length; i++){
        let item = cart[i];

        let entrada = document.createElement("div")
        entrada.innerHTML= `
            <div class="ui segment">
                <h3 name="pName">${item.pName}</h3>
                <h3 name="price">${item.price} €</h3>
            </div>
            <input type="hidden" name="product_id[]" value="${item.id}">
            
            <input type="hidden" name="price[]" value="${item.price}">
        `;
        form.appendChild(entrada);
    }

    checkDiv.appendChild(form);
    checkDiv.className="ui three column grid container centered";
    //Crea un boton que elimina el localStorage y manda la informacion al PHP
    let botones = document.createElement("div");
    botones.innerHTML = `
        <div class="ui big buttons">
            <button class="ui primary button" type="submit" name="pago">Pagar</button>
            <button class="ui cancel button" type="button" onclick="cancelarPago();">Cancelar</button>
            <button class="ui red button" type="button" onclick="removeFromCart();">Limpiar carro</button>
        </div>
    `;

    form.appendChild(botones);

    //Elimino el localStorage
    form.addEventListener('submit', function(){
        localStorage.removeItem('cart');
    })
}
/*<input type="hidden" name="cantidad[]" value="${item.cantidad}">*/ 
function cancelarPago(){
    let checkDiv = document.getElementById('checkout-div');
    checkDiv.innerHTML= '';
}


