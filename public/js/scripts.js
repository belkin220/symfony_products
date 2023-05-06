

const deleteProduct = () => {

$("table").on('click', '#btn-delete', function () {
  var currentRow = $(this).closest("tr");
  var productName = currentRow.find("td:eq(2)").text();
  var id = currentRow.find("td:eq(0)").text();
  var token = "{{csrf_token('token_id')}}";
    Swal.fire({
      title: 'Va a eliminar ' + productName ,
      icon: 'warning',
      showDenyButton: false,
      showCancelButton: true,
      cancelButtonText: 'Cancelar',
      confirmButtonText: 'Eliminar',
    }).then((result) => {
    /* Read more about isConfirmed, isDenied below */
      if (result.isConfirmed) {
        $.ajax({
          method: 'POST',
          data: { 'id': id, '_token': token },
          url: '/product/remove/' + id,
          success: function () {
            window.location.href = '/muestra';
          }
        })
      }
    })
  })
}



