jQuery(document).ready(function () {
    // console.log(document.getElementsByClassName("tableMonedas"));
    $(".tableMov").DataTable({
        "pageLength": 5,
        "lengthMenu": [5, 10, 25],
        responsive: true,
        paging: true,
        pagingTag: 'button',
        searching: false,
        language: language,
    });
});