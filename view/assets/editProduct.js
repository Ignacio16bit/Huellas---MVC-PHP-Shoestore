function editProduct(id, name, price){
    //Abrir modal con el formulario relleno
    //Los argumentos que recoge en cada dato del foreach los pasa a los valores de cada formulario
    document.getElementById('edit-product-id').value = id;
    document.getElementById('product_name').value = name;
    document.getElementById('product_price').value = price + ' €';

    $('#edit-modal').modal('show');
}

function removeProduct(id, name){
    document.getElementById('pID').value = id;
    document.getElementById('pName').placeholder = name;

    $('#rm-modal').modal('show');
}